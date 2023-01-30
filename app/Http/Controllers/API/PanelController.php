<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Base\Model;
use App\Models\Company;
use App\Exports\BaseExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Maatwebsite\Excel\Facades\Excel;

class PanelController extends BaseController {
    public function routePlanning(Request $request){
        // file upload and export csv
        $company_id     =   intval($request->company_id);
        if($className   =   Model::checkModel('company')){
            $company    =   $className::findRecord($company_id);
            if(isset($company)){
                if($exportName = BaseExport::checkModel('order')){
                    $filename = $company->company_name . '_' . date('Y_m_d_H_i_s') . '.csv';
                    $file = Excel::store(new $exportName($company_id), $filename, 'csv');
                    if($file){
                        $url = Storage::disk('csv')->url($filename);
                        $process = new Process(['python', 'CVRP.py', $url]);
                        $process->setTimeout(120);
                        $process->run();
                        // error handling
                        if (!$process->isSuccessful()) {
                            throw new ProcessFailedException($process);
                        }
                        // return dd($process->getOutput());
                        $output_data = $process->getOutput();
                        return $this->sendResponse($output_data, 'Route Planning Success!');
                    }
                    throw new Exception('Can\'t Export the CSV');
                }
            }
        }
        throw new Exception();
    }

    // public function routePrepare(Request $request, $company_id = -1){
    //     if($className = Model::checkModel('order')) {
    //         $output_data = $className::getUngroupOrder($company_id);
    //         return $this->sendResponse($output_data, 'Route Prepare Success!');
    //     }
    // }

    public function routeStoring(Request $request){
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
        throw new \Exception($e);
    }
}