<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Account;
use App\Models\ImageUpload;
use App\Imports\BaseImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends BaseController
{
    public function fileStore(Request $request)
    {
        $id = $request->id;
        if ($request->hasFile('file')) {
            $account = Account::findRecord($id);
            $company_id = $account->company_id;
            $file = $request->file('file');

            $validator = Validator::make($request->all(), ImageUpload::getValidateRules($id));

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors());
            }

            $imageName = $file->getClientOriginalName();
            $imageName = strip_tags($imageName);
            $path = $file->storeAs('/', $imageName, 'media');

            $imageUpload = new ImageUpload();
            $imageUpload->image = $imageName;
            $imageUpload->path = $path;
            $imageUpload->company_id = $company_id;
            $imageUpload->save();
            return $this->sendResponse($imageUpload->image, sprintf('Successfully uploaded "%s".', $imageName));
        }
        throw new \Exception();
    }

    public function getImageInventory(int $company_id = -1)
    {
        $data = ImageUpload::getData(-1, $company_id);
        $data = $data->toArray();
        array_walk($data, function (&$e) {
            $e['path'] = secure_asset(Storage::disk('media')->url($e['image']));
        });
        return $this->sendResponse($data, 'All the images');
    }

    public function fileImport(Request $request)
    {
        $id = $request->id;
        $model = $request->model;
        try {
            if ($className = BaseImport::checkModel($model)) {
                $user = Account::findRecord($id);
                $csv = Excel::import(new $className($user), $request->file('file')->store('temp'));
                return $this->sendResponse($csv, 'File imported successfully!');
            }
        } catch(\Exception $e) {
            $errors = json_decode($e->getMessage(), true);
            $errMsg = [];
            foreach ($errors as $row) {
                foreach ($row as $rrow) {
                    $errMsg[] = $rrow;
                }
            }
            return $this->sendError(sprintf('%d error(s) during file import.', sizeof($errMsg)), implode(PHP_EOL, $errMsg), 400);
        }
    }
}
