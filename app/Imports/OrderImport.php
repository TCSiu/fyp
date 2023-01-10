<?php

namespace App\Imports;

use App\Commons\Constants;
use App\Models\Order;
use App\Imports\BaseImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

class OrderImport extends BaseImport
{
	/**
	* @param array $row
	*
	* @return \Illuminate\Database\Eloquent\Model|null
	*/

	public function	__construct($user){
		$this->user =   $user;
	}

	public function model(array $row){
        $data   =   $this->processData($row);
        return new Order($data);
	}

	public function processData(array $input = []){
        if(isset($input) && !empty($input) && sizeOf($input) == 9 ){
            $input['items_name']	= json_decode(substr($input['items_name'],1,-1), true);
            $input['items_number']	= json_decode(substr($input['items_number'],1,-1), true);
            $geometry               = static::getGeoLocation($input['deliver1'] ?? '');
            $input['lat']           = $geometry['lat'] ?? null;
            $input['lng']           = $geometry['lng'] ?? null;
            $temp					= Order::modifyData($input);
            $validator				= Validator::make($temp, Order::getValidateRules(), Order::VALIDATE_MESSAGE);
            if($validator->fails()){
                $errors			    = $validator->errors()->toArray();
                throw new \Exception(json_encode($errors));
            }
            $data					= Order::matchField($this->user, $temp);
            // return dd($data);
            return $data;
        }
        throw new \Exception(json_encode(['Error' => ['Wrong Format!']]));
	}

	public static function getGeoLocation(string $place = ''){
        if(isset($place) && !empty($place) && strlen($place) > 0){
            try{
                $http = new \GuzzleHttp\Client;
                $response = $http->post('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', [
                    'query'	=>	[
                        // 'input'         =>  $place,
                        'inputtype'     =>  'textquery',
                        'fields'        =>  'geometry',
                        'key'           =>  Constants::GOOGLE_MAP_KEY,
                    ],
                ]);
                $response = json_decode($response->getBody(), true);
                if(strcmp($response['status'], "OK") == 0){
                    return $response['candidates'][0]['geometry']['location'];
                }else{
                    throw new \Exception(json_encode(['Status' => [$response['status']]], JSON_FORCE_OBJECT));
                }
            }catch(\Exception $e){
                throw $e;
            }
        }
        return $place;
    }
}
