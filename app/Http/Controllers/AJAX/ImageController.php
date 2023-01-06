<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\AJAX\BaseController as BaseController;
use App\Models\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends BaseController {
    public function fileStore(Request $request, int $id = -1){
        if($request->hasFile('file')){
            $file = $request->file('file');

            $validator = Validator::make($request->all(), ImageUpload::getValidateRules($id));
            
            if($validator->fails()){
                return $this->sendError('Validation Error', $validator->errors());
            }

            $imageName = $file->getClientOriginalName();
            $path = $file->storeAs('/public/uploads', $imageName);

            $imageUpload = new ImageUpload();
            $imageUpload->image = $imageName;
            $imageUpload->path = $path;
            $imageUpload->save();
            return $this->sendResponse($imageUpload->image, 'User Register Success!');
        }
        throw new \Exception();
    }

    public function viewImage(int $id = -1){
        $image = ImageUpload::find($id);
        $image = $image->image;
        return view('panel/imageView')->with('title', 'Panel Page')->with('image', $image);
	}

    public function getImageInventory(){
        $data = ImageUpload::getData();
        return $this->sendResponse($data, 'All the images');
    }
}