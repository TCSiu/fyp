<?php

namespace App\Imports;

use App\Models\Order;
use Illuminate\Support\Facades\Validator;

class OrderImport extends BaseImport
{
    public function __construct($user)
    {
        $this->user =   $user;
    }

    public function model(array $row)
    {
        // return dd($row);
        $data   =   $this->processData($row);
        return new Order($data);
    }

    public function processData(array $input = [])
    {
        if (isset($input) && !empty($input) && sizeof($input) == 8) {
            $input['items_name']	= json_decode(substr($input['items_name'], 1, -1), true);
            $input['items_number']	= json_decode(substr($input['items_number'], 1, -1), true);
            $geometry               = static::getGeoLocation($input['delivery1'] ?? '');
            $input['lat']           = $geometry['lat'] ?? null;
            $input['lng']           = $geometry['lng'] ?? null;
            $temp					= Order::modifyData($input);
            $validator				= Validator::make($temp, Order::getValidateRules(), Order::VALIDATE_MESSAGE);
            if ($validator->fails()) {
                $errors			    = $validator->errors()->toArray();
                throw new \Exception(json_encode($errors));
            }
            return Order::matchField($this->user, $temp);
        }
        throw new \Exception(json_encode(['Error' => ['Wrong Format!']]));
    }

    public static function getGeoLocation(string $place = '')
    {
        if (isset($place) && !empty($place) && strlen($place) > 0) {
            try {
                $http = new \GuzzleHttp\Client();
                $response = $http->post('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', [
                    'query'	=>	[
                        'input'         =>  $place,
                        'inputtype'     =>  'textquery',
                        'fields'        =>  'geometry',
                        'key'           =>  env('GOOGLE_MAP_KEY_NO_RESTRICT'),
                    ],
                ]);
                $response = json_decode($response->getBody(), true);
                if (strcmp($response['status'], "OK") == 0) {
                    return $response['candidates'][0]['geometry']['location'];
                } else {
                    throw new \Exception(json_encode(['Status' => [$response['status']]], JSON_FORCE_OBJECT));
                }
            } catch(\Exception $e) {
                throw new \Exception(json_encode(['Error' => [$e]]));
            }
        }
        return $place;
    }
}
