<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function validate(
        Request $request, 
        array $rules, 
        array $messages = [], 
        array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()
            ->make(
                $request->all(), 
                $rules, $messages, 
                $customAttributes
            );
        if ($validator->fails()) {
            throw new \Exception(
                $validator->errors()
            );
        }
    }

    public function base_response($is_success, $code, $messages, $data = null, $header_token = ""){
        return response()->json([
            'success' => $is_success,
            'code'  => $code,
            "message" => $messages,
            "data" => $data
        ])->withHeaders([
            'Content-Type' => "JSON",
            'Header-Token' => $header_token,
        ]);
    }

    public function failed_response($data)
    {
        $message = json_decode($data);
        if(!is_object($message)){
            $message = $data;
        }

        return $this->base_response(FALSE, 500,$message); 
    }

    public function success_response($messages, $data, $data_header_token = [])
    {
        $header_token = hash_hmac('sha256',json_encode(array_keys($data_header_token)), '123');
        return $this->base_response(TRUE, 200, $messages, $data, $header_token);
    }

    function getCurlValue($filename, $contentType, $postname)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postname);
        }

        // Use the old style if using an older version of PHP
        $value = "@{$filename};filename=" . $postname;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }

        return $value;
    }
}
