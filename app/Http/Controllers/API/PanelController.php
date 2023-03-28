<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Task;
use App\Models\TaskOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

class PanelController extends BaseController
{
    public function routePlanning(Request $request)
    {
        // file upload and export csv
        $company_id             = intval($request->company_id);
        $available_vehicle      = intval($request->available_vehicle);
        $vehicle_capacity       = intval($request->vehicle_capacity);

        $company = Company::findRecord($company_id);
        if (!($company instanceof Company)) {
            throw new \Exception('Compnay not found.');
        }

        $filename = sprintf('%s_%s.csv', Str::slug($company->company_name), date('Y_m_d_H_i_s'));

        if (!Excel::store(new OrderExport($company_id), $filename, 'csv')) {
            throw new \Exception('Cannot export CSV.');
        }

        $export_csv_path = Storage::disk('csv')->path($filename);
        $python_command = implode(' ', [
            env('PYTHON_BIN', 'python3'),
            sprintf('"%s"', storage_path('bin/CVRP.py')),
            sprintf('"%s"', $export_csv_path),
            $available_vehicle,
            $vehicle_capacity,
        ]);

        $output = exec($python_command);
        Log::debug($python_command);

        if (is_array($output)) {
            $output = implode($output);
        }

        try {
            $output = json_decode($output, true);
            if (is_array($output) && sizeof($output) > 0) {
                return $this->sendResponse($output, 'Route Planning Success!');
            }
            return $this->sendError('Route Planning Fail!', [$output]);
        } catch(\Exception $e) {
            return $this->sendError('Route Planning Fail!', [json_decode($output, true)]);
        }
    }

    public function getStaffList(int $company_id = 0)
    {
        return Account::getStaffList($company_id);
    }

    public function routeStoring(Request $request)
    {
        $company_id = $request->company_id;
        $data = $request->data;
        foreach ($data as $value) {
            $temp['company_id'] = $company_id;
            $route_order = Task::initOrder($value);
            if (!$route_order) {
                continue;
            }

            $temp['route_order'] = $route_order;
            $task = Task::create($temp);
            $order_uuid_list = Task::getOrderUuid($value);
            OrderStatus::batchCreate($order_uuid_list);
            Order::batchUpdate($order_uuid_list);
            TaskOrder::batchCreate($task->uuid, $order_uuid_list);
        }
        return $this->sendResponse('Success', 'Create Success!');
    }

    public function assignTask(Request $request)
    {
        $order_id = intval($request->order_id);
        $staff_id = intval($request->staff_id);
        try {
            if (Task::assignTask($order_id, $staff_id)) {
                return $this->sendResponse('success', sprintf('Successfully assigned order %d to staff %d.', $order_id, $staff_id));
            }
            return $this->sendResponse('failed', sprintf('Failed to assign order %d to staff %d.', $order_id, $staff_id));
        } catch(\Exception $e) {
            return $this->sendResponse('failed', $e->getMessage());
        }
    }
}
