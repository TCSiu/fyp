<?php

namespace App\Http\Controllers\Web;

use App\Models\Base\Model;
use App\Models\ImageUsage;
use App\Http\Requests\WebRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PanelController extends Controller
{
    public function index(WebRequest $request)
    {
        return view('panel.dashboard')
            ->with('title', 'Panel Page');
    }

    public function list(WebRequest $request, string $model = '')
    {
        if ($className = Model::checkModel($model)) {
            $page_title 		= $className::PAGE_TITLE;
            $inpage_title 		= 'View ' . $page_title;
            $target_fields 		= $className::TABLE_FIELDS;
            $allow_actions 		= $className::ALLOW_ACTIONS;
            $account			= Auth::user();
            $account_id			= $account->id;
            $company_id			= $account->company_id;
            $operations 		= $className::OPERATION;
            $data 				= $className::getData(20, $company_id);
            $total_count 		= $className::getCount($company_id);
            $images 			= ImageUsage::getImages($className, $account_id);

            return view('panel.list')
                ->with('model', $model)
                ->with('title', $page_title)
                ->with('inpage_title', $inpage_title)
                ->with('target_fields', $target_fields)
                ->with('allow_actions', $allow_actions)
                ->with('operations', $operations)
                ->with('data', $data)
                ->with('total_count', $total_count)
                ->with('company_id', $company_id)
                ->with('images', $images);
        }
        throw new \Exception();
    }

    public function create(WebRequest $request, string $model = '')
    {
        if (!$className = Model::checkModel($model)) {
            throw new \Exception('Requested model do not exist.');
        }
        $page_title = $className::PAGE_TITLE;
        return view('panel.create')
            ->with('model', $model)
            ->with('title', $page_title)
            ->with('inpage_title', sprintf('Create %s', $page_title))
            ->with('msg', [])
            ->with('isCreate', true);
    }

    public function view(WebRequest $request, string $model = '', int $id = -1)
    {
        if (!$className = Model::checkModel($model)) {
            throw new \Exception('Requested model do not exist.');
        }
        $data = $className::findRecord($id);
        return view('panel.view')
            ->with('model', $model)
            ->with('title', $className::PAGE_TITLE)
            ->with('inpage_title', sprintf('View %s', $className::getInpageTitle($id)))
            ->with('fields', $className::VIWES_FIELDS)
            ->with('data', $data)
            ->with('id', $data->id)
            ->with('images', ImageUsage::getImages($className, $id));
    }

    public function edit(WebRequest $request, string $model = '', int $id = 1)
    {
        if (!$className = Model::checkModel($model)) {
            throw new \Exception('Requested model do not exist.');
        }

        $record = $className::findEditableRecord($id);
        if ($record === false) {
            $message['type'] = 'errors';
            $message['message'][] = 'This record cannot be edited.';
            return redirect()->back()->with('msg', $message);
        }

        $page_title = $className::PAGE_TITLE;
        return view('panel.create')
            ->with('model', $model)
            ->with('title', $page_title)
            ->with('inpage_title', sprintf('Edit %s %d', $page_title, $id))
            ->with('msg', [])
            ->with('record', $record)
            ->with('method', 'store')
            ->with('id', $id)
            ->with('images', ImageUsage::getImages($className, $id));
    }

    public function store(WebRequest $request, string $model = '', int $id = -1)
    {
        if (!$className = Model::checkModel($model)) {
            throw new \Exception('Requested model do not exist.');
        }

        $user 				= Auth::user();
        $temp 				= $request->all();
        $temp 				= $className::modifyData($temp);
        $validator 			= Validator::make($temp, $className::getValidateRules($id), $className::VALIDATE_MESSAGE);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $message['type'] = 'errors';
            foreach ($errors as $row) {
                foreach ($row as $rrow) {
                    $message['message'][] = $rrow;
                }
            }
            return redirect()->back()->with('msg', $message)->withInput();
        }

        $data 				= $className::matchField($user, $temp);
        $record 			= $className::updateOrCreate(['id' => $id], $data);
        ImageUsage::fileUsageStore($className, $record->id, $temp);
        return redirect(route('cms.view', ['model' => $model, 'id' => $record->id]));
    }

    public function delete(WebRequest $request, string $model = '', int $id = -1)
    {
        if (!$className = Model::checkModel($model)) {
            throw new \Exception('Requested model do not exist.');
        }

        $record = $className::findRecord($id);
        if (isset($record) && $record instanceof Model) {
            $record->deleteRecord();
        }
        return redirect(route('cms.list', ['model' => $model]));
    }
}
