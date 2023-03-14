<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Base\Model;
use App\Models\Company;
use App\Exports\BaseExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Maatwebsite\Excel\Facades\Excel;

class PanelController extends BaseController {
    public function routePlanning(Request $request){
        // file upload and export csv
        $company_id             =   intval($request->company_id);
        $available_vehicle      =   intval($request->available_vehicle);
        $vehicle_capacity       =   intval($request->vehicle_capacity);
        if($className   =   Model::checkModel('company')){
            $company    =   $className::findRecord($company_id);
            if(isset($company)){
                if($exportName = BaseExport::checkModel('order')){
                    $filename = $company->company_name . '_' . date('Y_m_d_H_i_s') . '.csv';
                    $file = Excel::store(new $exportName($company_id), $filename, 'csv');
                    if($file){
                        $url = Storage::disk('csv')->url($filename);
                        $process = new Process(['python', 'CVRP.py', $url, $available_vehicle, $vehicle_capacity]);
                        $process->setTimeout(120);
                        $process->run();
                        // error handling
                        if (!$process->isSuccessful()) {
                            // throw new ProcessFailedException($process);
                            $errorMsgs = preg_split('/\r\n/',$process->getIncrementalErrorOutput());
                            $errorMsg = $errorMsgs[sizeof($errorMsgs) - 2];
                            return $this->sendError('Route Planning Fail!', [$errorMsg]);
                        }
                        // return dd($process->getOutput());
                        $output_data = $process->getOutput();
                        return $this->sendResponse($output_data, 'Route Planning Success!');
                    }
                    throw new Exception('Can\'t Export the CSV');
                }
            }
        }
        throw new Exception();
    }

    // public function routePrepare(Request $request, $company_id = -1){
    //     if($className = Model::checkModel('order')) {
    //         $output_data = $className::getUngroupOrder($company_id);
    //         return $this->sendResponse($output_data, 'Route Prepare Success!');
    //     }
    // }

    public function getStaffList(int $company_id = 0){
        if(($accountClass = Model::checkModel('account'))){
            return $accountClass::getStaffList($company_id);
        }
        throw new Exception();
    }

    public function routeStoring(Request $request){
        $order_group_list = [];
        // dd(Model::checkModel('task order'));
        if(($taskClass = Model::checkModel('task')) && ($orderStatusClass = Model::checkModel('OrderStatus')) && ($orderClass = Model::checkModel('order')) && ($taskOrderClass = Model::checkModel('TaskOrder'))){
            $company_id = $request->company_id;
            $data = $request->data;
            foreach($data as $key => $value){
                $temp['company_id']     = $company_id;
                $route_order            = $taskClass::initOrder($value);
                if(!$route_order){
                    continue;
                }else{
                    $temp['route_order']    = $route_order;
                    $task                   = $taskClass::create($temp);
                    $order_uuid_list        = $taskClass::getOrderUuid($value);
                    $orderStatusClass::batchCreate($order_uuid_list);
                    $orderClass::batchUpdate($order_uuid_list);
                    $taskOrderClass::batchCreate($task->uuid, $order_uuid_list);
                }
            }
            return $this->sendResponse('Success', 'Create Success!');
        }
        throw new \Exception();
    }

    public function OrderSearch(Request $request, String $order_uuid = ''){
        $timeline = ['created' => null, 'preparing' => null, 'delivering' => null, 'finished' => null];
        if(($taskClass = Model::checkModel('task')) && ($orderStatusClass = Model::checkModel('OrderStatus')) && ($orderClass = Model::checkModel('order')) && ($taskOrderClass = Model::checkModel('TaskOrder'))){
            // return $this->sendResponse($temp, 'success');
            $order                      = $orderClass::findRecordByUuid($order_uuid);
            if($order){
                $timeline['created'] = $order->created_at->format('Y-m-d H:i:s');
                if($order->is_in_group){
                    $task_uuid          = $taskOrderClass::getTaskByOrderUuid($order_uuid);
                    if($task_uuid){
                        $order_status   = $orderStatusClass::getOrderAllStatus($order_uuid);
                        foreach($order_status as $value){
                            $timeline[$value['status']] = $value['created_at']->format('Y-m-d H:i:s');
                        }
                    }
                }
                return $this->sendResponse($timeline, 'success');
            }
            return $this->sendError('Fail', ['Order not found']);
        }
        throw new \Exception();
    }

    public function viewOrder(Request $request, String $order_uuid = ''){
        $order_items = [];
        if($orderClass = Model::checkModel('order')){
            $order                      = $orderClass::findRecordByUuid($order_uuid);
            $order_items                = json_decode($order['product_name_and_number'], true);
            return $this->sendResponse($order_items, 'success');
        }
        return $this->sendError('Fail', ['Order not found']);
    }
}