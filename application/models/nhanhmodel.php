<?php

class NhanhModel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function query_stock($product_id)
    {
        $apiUsername = "HerSkincare";
        $secretKey = "Mew5e6ry_qqw46r7tyjhdsefXzsfseMx";
        
        $dataString = $product_id . "";
        $checksum = md5(md5($secretKey . $dataString) . $dataString);
    
        $postArray = array(
            "version" => "1.0",
            "apiUsername" => $apiUsername,
            "data" => $dataString,
            "checksum" => $checksum
        );
    
        $curl = curl_init("https://graph.nhanh.vn/api/product/detail");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
        $curlResult = curl_exec($curl);
    
        if(! curl_error($curl)) {
            // success
            $response = json_decode($curlResult);
        } else {
            // failed, cannot connect nhanh.vn
            $response = new stdClass();
            $response->code = 0;
            $response->messages = array(curl_error($curl));
        }
        curl_close($curl);
    
        if ($response->code == 1) {
            //$data = $response->data;
            return $response->data->$dataString->inventory->remain;
        } else {
            // failed, show error messages
            return -1;
            if(isset($response->messages) && is_array($response->messages)) {
                foreach($response->messages as $message) {
                    echo $message;
                }
            }
        }
    }
}
?>