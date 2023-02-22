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
        return $this->sendResponse($task, 'Task Details');;
    }
}
