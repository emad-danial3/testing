<?php

namespace App\Http\Services;


class Myllerz 
{
    private $username = 'CS Team';
    private $password = 'Netting@2023';
    private $accessToken = '';
    private $link = 'https://integration.mylerz.net/';
   

    public function __construct()
    {

        $this->accessToken = $this->getAccessToken();

    }

    public function getAccessToken()
    {
        if($this->accessToken != '') return $this->accessToken ;
        $client   = new \GuzzleHttp\Client();
        $response = $client->request('POST', $this->link .'token', ['verify'      => false,
                                 'form_params' => array(
                                     'grant_type' => 'password',
                                     'username'   => $this->username,
                                     'password'   => $this->password,
                                 )

        ]);
       $res = $response->getBody();


        if ($res) $res =  json_decode($res);
        $this->accessToken = $res->access_token;
        return $this->accessToken ;
    }
    public function get_order_status_by_barcode($barcode)
    {

       
        $accessToken = $this->getAccessToken() ;
        $token = 'Bearer ' .$accessToken ;
        $client   = new \GuzzleHttp\Client();
        $jsonData = [$barcode];
        $response = $client->request('POST', $this->link .'api/Packages/GetPackageListStatus',
        [   'verify'      => false,
            'json' => $jsonData,
            'headers' => [
                    'Authorization' =>$token ,
                    'Accept' => 'application/json',
                    ] 

        ]);
       $body = $response->getBody()->getContents();
       $res = json_decode($body);
       return $res->Value ;

    }
    public function get_order_stations($order_id,$barcode)
    {
       
        $accessToken = $this->getAccessToken() ;
        $token = 'Bearer ' .$accessToken ;
        $client   = new \GuzzleHttp\Client();
        $jsonData = [
        (object)['ReferenceNumber' => $order_id,'barcode' =>$barcode]
        ];
        $response = $client->request('POST', $this->link .'api/Packages/TrackPackages',
        [   'verify'      => false,
            'json' => $jsonData,
            'headers' => [
                    'Authorization' =>$token ,
                    'Accept' => 'application/json',
                    ] 

        ]);
       $body = $response->getBody()->getContents();
       $res = json_decode($body);
       return $res->Value ;
       

    }
}
