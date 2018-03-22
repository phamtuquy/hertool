<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
function call_nhanh_api($env = "dev", $api_url, $data_string)
{
    if ($env == "dev"){
        $api_root = "https://dev.nhanh.vn";
        $api_username = "HerSkincare";
        $api_secret = "Vw46erydhs_Cw6e7r6uhdsgas_43w5yr";
    }
    
    if ($env == "prod"){
        $api_root = "https://graph.nhanh.vn";
        $api_username = "HerSkincare";
        $api_secret = "Mew5e6ry_qqw46r7tyjhdsefXzsfseMx";
    }
    
    $checksum = md5(md5($api_secret . $data_string) . $data_string);

    $postArray = array(
        "version" => "1.0",
        "apiUsername" => $api_username,
        "data" => $data_string,
        "checksum" => $checksum
    );

    $curl = curl_init($api_root . $api_url);
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

    return $response;
}