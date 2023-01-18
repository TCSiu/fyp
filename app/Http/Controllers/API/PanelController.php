<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Base\Model;
use App\Exports\BaseImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PanelController extends BaseController {
    public function routePlanning(){
        // file upload and export csv
		$url = Storage::disk('csv')->url('sample_data.csv');
		$process = new Process(['python', 'CVRP.py', $url]);
        $process->run();
        // error handling
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output_data = $process->getOutput();
        return $this->sendResponse($output_data, 'Route Planning Success!');
    }

    // public function routePrepare(Request $request, $company_id = -1){
    //     if($className = Model::checkModel('order')) {
    //         $output_data = $className::getUngroupOrder($company_id);
    //         return $this->sendResponse($output_data, 'Route Prepare Success!');
    //     }
    // }

    public function routeStoring(Request $request){
        try{
            if($className = Model::checkModel('group')){
                $company_id = $request->company_id;
                $data = $request->data;
                $order_group_list = [];
                foreach($data as $key => $value){
                    $temp['company_id']     = $company_id;
                    $temp['route_order']    = json_encode($value);
                    $order_group            = $className::create($temp);
                    array_push($order_group_list, $order_group);
                }
                return $this->sendResponse('Success', 'Create Success!');
            }
        }catch(\Exception $e){
            throw new \Exception($e);
        }
    }
}