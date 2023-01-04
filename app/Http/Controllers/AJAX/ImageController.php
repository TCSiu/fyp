<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\AJAX\BaseController as BaseController;
use App\Models\ImageUpload;
use Illuminate\Http\Request;

class ImageController extends BaseController {
    public function fileStore(Request $request, int $id = -1){
        if($request->hasFile('file')){
            $file = $request->file('file');
            $imageName = $file->getClientOriginalName();
            $path = $file->storeAs('/public/uploads', $imageName);

            $imageUpload = new ImageUpload();
            $imageUpload->image = $imageName;
            $imageUpload->path = $path;
            $imageUpload->save();
            return $this->sendResponse($imageUpload->image, 'User Register Success!');
        }
        // $validator = Validator::make($temp, $className::getValidateRules($id));
        // if($validation->fails()){
        //     return $this->sendError('Validation Error', $validation->errors());
        // }
        throw new \Exception();
    }
}