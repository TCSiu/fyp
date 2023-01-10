<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\ImageUpload;
use App\Models\ImageUsage;
use App\Http\Requests\WebRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImageController extends BaseController {
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
            $path = $file->storeAs('/', $imageName, 'upload');

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
            $e['path'] = secure_asset(Storage::disk('upload')->url($e['image']));
        });
        return $this->sendResponse($data, 'All the images');
    }
}