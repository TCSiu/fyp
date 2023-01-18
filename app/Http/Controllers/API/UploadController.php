<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\ImageUpload;
use App\Models\ImageUsage;
use App\Imports\BaseImport;
use App\Http\Requests\WebRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends BaseController {
    public function fileStore(Request $request, int $id = -1){
        if($request->hasFile('file')){
            $account = Account::findRecord($id);
            $company_id = $account->company_id;
            $file = $request->file('file');
            
            $validator = Validator::make($request->all(), ImageUpload::getValidateRules($id));
            
            if($validator->fails()){
                return $this->sendError('Validation Error', $validator->errors());
            }

            $imageName = $file->getClientOriginalName();
            $path = $file->storeAs('/', $imageName, 'media');

            $imageUpload = new ImageUpload();
            $imageUpload->image = $imageName;
            $imageUpload->path = $path;
            $imageUpload->company_id = $company_id;
            $imageUpload->save();
            return $this->sendResponse($imageUpload->image, 'User Register Success!');
        }
        throw new \Exception();
    }

    public function getImageInventory(int $company_id = -1){
        $data = ImageUpload::getData(-1, $company_id);
        $data = $data->toArray();
        array_walk($data, function(&$e){
            $e['path'] = secure_asset(Storage::disk('media')->url($e['image']));
        });
        return $this->sendResponse($data, 'All the images');
    }

    public function fileImport(Request $request){
        $id = $request->id;
        $model = $request->model;
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
    }
}