<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\Company;
use App\Models\Group;
use App\Models\OrderStatus;
use App\Models\TaskOrder;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    // public function getTask(Request $request, String $uuid){
    //     $task = Group::findRecordByUuid($uuid);
    //     $task['route_order'] = json_decode($task['route_order'], true);
    //     return $this->sendResponse($task, 'Task Details');
    // }

    public function getTask(Request $request, String $uuid){
        $task = Group::findRecordByUuid($uuid)->toarray();
        if(!empty($task) && sizeof($task) > 0){
            $task['route_order'] = json_decode($task['route_order'], true);
            return $this->sendResponse($task, 'success');
        }
        return $this->sendError('Fail', ['error' => 'Wrong Uuid!']);
        // return $status->toString();
    }

    public function getTaskStatus(Request $request, String $uuid){
        $task_details = Group::getRouteUuid($uuid);
        if(!empty($task_details) && sizeof($task_details) > 0){
            $status = OrderStatus::getBatchStatusByUuid($task_details);
            return $this->sendResponse($status, 'success');
        }
        return $this->sendError('Fail', ['error' => 'Wrong Uuid!']);
    }

    public function getAllTasks(Request $request){
        if($user = $request->user()){
            $id = $user->id;
            $allTasks = Group::findRecordByStaffId($id);
            // if(!$allTasks->isEmpty()){
            //     $allTask->route_order = json_decode($allTasks->route_order,true);
            // }
            return $this->sendResponse($allTasks, 'success');
        }
        return $this->sendError('Unauthorised!', ['error'=>'Unauthorised!']); 
    }

    public function updateOrderStatus(Request $request){
        $order_uuid = $request->order_uuid;
        $order_status = $request->order_status;
        $task_uuid = TaskOrder::getTaskByOrderUuid($order_uuid);
        $task_uuid = json_decode($task_uuid, true);
        $result = OrderStatus::updateStatus($order_uuid, $order_status);
        Group::updateStatus($task_uuid['task_uuid']);
        if($result){
            return $this->sendResponse($result, 'successful update');
        }
        return $this->sendError('Fail', ['error' => 'Fail to update order status']);
    }
}
