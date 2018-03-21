<?php

class NhanhModel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    private static $api_username = "HerSkincare";
    private static $api_secret = "Vw46erydhs_Cw6e7r6uhdsgas_43w5yr";
    private static $api_domain_url = "https://dev.nhanh.vn";
    
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
            $response = json_decode($curlResult, true);
        } else {
            // failed, cannot connect nhanh.vn
            $response = new stdClass();
            $response->code = 0;
            $response->messages = array(curl_error($curl));
        }
        curl_close($curl);
    
        if ($response['code'] == 1) {
            //return $response->data->$dataString->inventory->remain; //doesn't work on Codeigniter 3.x
            return reset($response['data'])['inventory']['remain']; //Work on Codeigniter 3.x
        } else {
            // failed, show error messages
            return -30;
            if(isset($response->messages) && is_array($response->messages)) {
                foreach($response->messages as $message) {
                    echo $message;
                }
            }
        }
    }
    
    function get_order_history($order_id)
    {
        $data_string = "" . $order_id;
        $response = call_nhanh_api(
            "https://dev.nhanh.vn/api/order/history",
            "HerSkincare",
            "Vw46erydhs_Cw6e7r6uhdsgas_43w5yr",
            $data_string
        );
        
        return $response;
    }
    
    function get_order($mobile, $city, $district)
    {
        $data_string = array(
            customerMobile => $mobile
        );
        $response = call_nhanh_api(
            "https://dev.nhanh.vn/api/order/history",
            "HerSkincare",
            "Vw46erydhs_Cw6e7r6uhdsgas_43w5yr",
            $data_string
        );
        
        return $response;
    }
    
    function push_order_to_nhanh($order_data)
    {
        $response = call_nhanh_api(
            "https://dev.nhanh.vn/api/order/add",
            "HerSkincare",
            "Vw46erydhs_Cw6e7r6uhdsgas_43w5yr",
            $order_data
        );
        
        return $response;
    }
    
    /*
    function push_order_to_nhanh($order_data)
    {
        $response = call_nhanh_api(
            $api_domain_url . "/api/order/add",
            $api_username,
            $api_secret,
            $order_data
        );
        
        return $response;
    }*/
}
?>