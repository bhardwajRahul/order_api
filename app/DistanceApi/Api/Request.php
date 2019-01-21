<?php

namespace App\DistanceApi\Api;

use Exception;
use App\Exception\Handler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/*
  |--------------------------------------------------------------------------
  | Request Class
  |--------------------------------------------------------------------------
  |
  | This class is used when querying your Google Distance API.
  |
  | * E.g. Api::getDistance('api');
  |
 */

class Request {

    /**
     * @var string The domain location of the API
     */
    private static $curl;
    private static $requestParameters = array();
    private static $domain = 'https://maps.googleapis.com';

    /**
     * @var string Path to Google Distance Matrix API
     */
    private static $path = 'maps/api/distancematrix/json';
    private static $measuringUnit = 'metric';

    /**
     * @var array Any additional parameters to be sent with each request
     */
    private static function getParameters() {
        return [
            'units' => self::$measuringUnit,
            'key' => env('YOUR_API_KEY'),
        ];
    }

    private static $coordinateParameters = [
        'origins', 'destinations'
    ];

    /**
     * @var bool Set whether the cURL request will be over HTTP or HTTPS
     * false = HTTP
     * true = HTTPS
     */
    private static $secure = true;

    private static function getDistance($params = []) {

        self::$curl = curl_init();
        curl_setopt_array(self::$curl, [
            CURLOPT_SSL_VERIFYPEER => self::$secure,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::urlBuilder($params),
                ]
        );
        return curl_exec(self::$curl);
    }

    /**
     * Function to build URL
     */
    private static function urlBuilder($params = []) {

        return self::$domain . '/' . self::$path . '?' . http_build_query($params);
    }

    /**
     *  Distance calculation
     */
    public static function calculateDistance($data) {
        try {

            $data = array_map(function($v) {
                return implode(',', (array) $v);
            }, $data);
            $data = array_combine(self::$coordinateParameters, $data);
            self::$requestParameters = self::getParameters();
            self::$requestParameters = array_merge($data, self::$requestParameters);

            $response = json_decode(self::getDistance(self::$requestParameters), true);
            if (isset($response['rows'][0]['elements'][0]['distance']['value'])) {
                $response = $response['rows'][0]['elements'][0]['distance']['value'];
            } else {
                $response = $response['error_message'];
            }
            return $response;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

}