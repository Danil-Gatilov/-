<?php

namespace App\Http\Controllers;

use App\Rules\ValidatePhone\PhoneNumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Env;

class AmoController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required', new PhoneNumber()],
            'price' => ['required', 'numeric']
        ]);
        $flag = false;
        $sessionTimeString = $request->session_time;
        $timeParts = explode(':', $sessionTimeString);
        (int)$sessionTimeSeconds = $timeParts[0] * 3600 . $timeParts[1] * 60 . $timeParts[2];

        if ($sessionTimeSeconds > 30) {
            $flag = true;
        }

        $fields = [ 0 => [
            'name' => $data['name'],
            'price' =>(int)$data['price'],
            'custom_fields_values' => [0 => ['field_id' => 344151, 'values' => [0  => ['value' => $flag]]]],
            '_embedded' => ['contacts' => [0 => ['first_name' => $data['name'],
                'custom_fields_values' => [
                    0 => ['field_id' => 340925,
                        'values' => [0 => [
                            "enum_id" => 192329,
                            'value' => $data['phone']
                        ]]],
                    1 => ['field_id' => 340927,
                        'values' => [0 => [
                            'enum_id' => 192341,
                            'value' => $data['email']
                        ]]],]]]]]];


        $subdomain = Env::get('SUBDOMAIN');
        $link = 'https://' . $subdomain . ".amocrm.ru/api/v4/leads/complex";
        $headers = [
            'Authorization: Bearer ' . Env::get('ACCESS_TOKEN'),
            'Content-Type:application/json'
        ];


        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 0);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $code = (int)$code;

            $errors = [
                400 => 'Bad request',
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not found',
                500 => 'Internal server error',
                502 => 'Bad gateway',
                503 => 'Service unavailable',
            ];

            try
            {
                if ($code < 200 || $code > 204) {
                    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
                }
            } catch(\Exception $e)
            {
                die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
            }

            return back()->with('success', 'Ваша заявка была добавлена');
        }
}
