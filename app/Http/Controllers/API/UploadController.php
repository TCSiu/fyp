<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Imports\BaseImport;
use App\Http\Requests\WebRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends BaseController {
    public function fileImport(Request $request, string $model = '', int $id = -1){
        try{
            if($className = BaseImport::checkModel($model)) {
                $user = Account::findRecord($id);
                $csv = Excel::import(new $className($user), $request->file('file')->store('temp'));
                return $this->sendResponse($csv, 'User Register Success!');
            }
        }catch(\Exception $e){
            $errors = json_decode($e->getMessage(), true);
            $errMsg = '';
            forEach($errors as $k => $row){
                forEach($row as $kk => $rrow){
                    $errMsg .= $rrow . "\n";
                }
            }
            return $this->sendError('Error Occur', $errMsg, 400);
        }
        // return dd($className);
    }
}