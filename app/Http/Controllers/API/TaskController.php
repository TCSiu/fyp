<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Order;
use App\Models\Task;
use App\Models\OrderStatus;
use App\Models\TaskOrder;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    public function getTask(Request $request, string $uuid)
    {
        $task = Task::findRecordByUuid($uuid);
        if ($task instanceof Task) {
            $task = $task->toarray();
            $task['route_order'] = json_decode($task['route_order'], true);
            return $this->sendResponse($task, 'success');
        }
        return $this->sendError('Fail', ['error' => 'Task not found by UUID!'], 400);
    }

    public function getTaskStatus(Request $request, string $uuid)
    {
        $task_details = Task::getRouteUuid($uuid);
        if (is_array($task_details) && sizeof($task_details) > 0) {
            $status = OrderStatus::getBatchStatusByUuid($task_details);
            return $this->sendResponse($status, 'success');
        }
        return $this->sendError('Fail', ['error' => 'UUID does not resolve to a route!'], 400);
    }

    public function getAllTasks(Request $request)
    {
        if ($user = $request->user()) {
            $allTasks = Task::findRecordByStaffId($user->id);
            return $this->sendResponse($allTasks, 'success');
        }
        return $this->unauthorized();
    }

    public function updateOrderStatus(Request $request)
    {
        $relative_staff = $request->user();
        $staff_id = $relative_staff->id;
        $order_uuid = $request->order_uuid;
        $order_status = $request->order_status;
        $task_uuid = TaskOrder::getTaskByOrderUuid($order_uuid);
        if (!($task_uuid instanceof TaskOrder)) {
            return $this->sendError('Fail', ['error' => 'Order has not been assigned a task.']);
        }
        $task = Task::findRecordByUuid($task_uuid->task_uuid);
        if (!($task instanceof Task)) {
            return $this->sendError('Fail', ['error' => 'Task not found when updating order status.']);
        }
        if ($task->relative_staff == $staff_id) {
            $result = OrderStatus::updateStatus($order_uuid, $order_status);
            Task::updateStatus($task_uuid->task_uuid);
            // if (!$update_status) {
            //     return $this->sendError('Fail', ['error' => 'Fail to update order status']);
            // }
            // if ($result && $update_status) {
            //     return $this->sendResponse($result, 'Successfully updated order status.');
            // }
            if ($result) {
                return $this->sendResponse($result, 'Successfully updated order status.');
            }
        }
        return $this->sendError('Fail', ['error' => 'Fail to update order status']);
    }

    public function OrderSearch(Request $request, string $order_uuid = '')
    {
        $timeline = ['created' => null, 'preparing' => null, 'delivering' => null, 'finished' => null];
        $order = Order::findRecordByUuid($order_uuid);
        if (!($order instanceof Order)) {
            return $this->sendError('Fail', ['Order not found']);
        }
        $timeline['created'] = $order->created_at->format('Y-m-d H:i:s');
        if ($order->is_in_group) {
            $task_uuid = TaskOrder::getTaskByOrderUuid($order_uuid);
            if (!($task_uuid instanceof TaskOrder)) {
                // Order has not been assigned a task.
                return $this->sendResponse($timeline, 'success');
            }
            $order_status = OrderStatus::getOrderAllStatus($order_uuid);
            foreach ($order_status as $value) {
                $timeline[$value['status']] = $value['created_at']->format('Y-m-d H:i:s');
            }
        }
        return $this->sendResponse($timeline, 'success');
    }

    public function viewOrder(Request $request, string $order_uuid = '')
    {
        $order = Order::findRecordByUuid($order_uuid);
        if (!$order instanceof Order) {
            return $this->sendError('Fail', ['Order not found']);
        }
        $order_items = json_decode($order['product_name_and_number'], true);
        return $this->sendResponse($order_items, 'success');
    }
}
