<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Base\Model;
use App\Models\Account;
use App\Models\Company;
use App\Models\Task;
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
        $task = Task::findRecordByUuid($uuid)->toarray();
        if(!empty($task) && sizeof($task) > 0){
            $task['route_order'] = json_decode($task['route_order'], true);
            return $this->sendResponse($task, 'success');
        }
        return $this->sendError('Fail', ['error' => 'Wrong Uuid!']);
        // return $status->toString();
    }

    public function getTaskStatus(Request $request, String $uuid){
        $task_details = Task::getRouteUuid($uuid);
        if(!empty($task_details) && sizeof($task_details) > 0){
            $status = OrderStatus::getBatchStatusByUuid($task_details);
            return $this->sendResponse($status, 'success');
        }
        return $this->sendError('Fail', ['error' => 'Wrong Uuid!']);
    }

    public function getAllTasks(Request $request){
        if($user = $request->user()){
            $id = $user->id;
            $allTasks = Task::findRecordByStaffId($id);
            // if(!$allTasks->isEmpty()){
            //     $allTask->route_order = json_decode($allTasks->route_order,true);
            // }
            return $this->sendResponse($allTasks, 'success');
        }
        return $this->sendError('Unauthorised!', ['error'=>'Unauthorised!']); 
    }

    public function updateOrderStatus(Request $request){
        if(($taskClass = Model::checkModel('task')) && ($taskOrderClass = Model::checkModel('TaskOrder')) && ($orderStatusClass = Model::checkModel('OrderStatus'))){
            $relative_staff = $request->user();
            $staff_id = $relative_staff->id;
            $order_uuid = $request->order_uuid;
            $order_status = $request->order_status;
            $task_uuid = $taskOrderClass::getTaskByOrderUuid($order_uuid);
            if(isset($task_uuid)){
                $task = $taskClass::findRecordByUuid($task_uuid->task_uuid);
                if($task->relative_staff == $staff_id){
                    $result = $orderStatusClass::updateStatus($order_uuid, $order_status);
                    $taskClass::updateStatus($task_uuid->task_uuid);
                    if($result){
                        return $this->sendResponse($result, 'successful update');
                    }
                }
            }
            return $this->sendError('Fail', ['error' => 'Fail to update order status']);
        }
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
