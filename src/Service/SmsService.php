<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SmsService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function sendSms(string $message)
    {
        $curl = curl_init();

        $params = [
            'api_id' => $this->parameterBag->get('sms_api_id'),
            'api_key' => $this->parameterBag->get('sms_api_key'),
            'sender' => '08502740624',
            'message_type' => 'normal',
            'message' => $message,
            'phones' => [
                '5524331111',
                '5354116295',
                '5413779956',
            ]
        ];

        $curl_options = [
            CURLOPT_URL => 'https://api.vatansms.net/api/v1/1toN',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ];

        curl_setopt_array($curl, $curl_options);

        $response = curl_exec($curl);

        curl_close($curl);
    }
}