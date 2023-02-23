<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\Company;
use App\Models\Group;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    public function getTask(Request $request, String $uuid){
        $task = Group::findRecordByUuid($uuid);
        $task['route_order'] = json_decode($task['route_order'], true);
        return $this->sendResponse($task, 'Task Details');;
    }

    public function getAllTasks(Request $request){
        if($user = $request->user()){
            $id = $user->id;
            $allTasks = Group::findRecord($id);
            // if(!$allTasks->isEmpty()){
            //     $allTask->route_order = json_decode($allTasks->route_order,true);
            // }
            return $this->sendResponse($allTasks, 'success');
        }
        return $this->sendError('Unauthorised!', ['error'=>'Unauthorised!']); 
    }
}
