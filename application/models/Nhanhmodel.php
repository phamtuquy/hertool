<?php

class NhanhModel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    /*private static $api_username = "HerSkincare";
    private static $api_secret = "Vw46erydhs_Cw6e7r6uhdsgas_43w5yr";
    private static $api_domain_url = "https://dev.nhanh.vn";*/
    
    function query_stock($product_id)
    {
        $data_string = $product_id . "";
        $response = call_nhanh_api(
            "prod",
            "/api/product/detail",
            $data_string
        );
        
        if ($response['code'] == 1) {
            //return $response->data->$dataString->inventory->remain; //doesn't work on Codeigniter 3.x
            return reset($response['data'])['inventory']['remain']; //Work on Codeigniter 3.x
        } else {
            // failed, show error messages
            return -30;
        }
    }
    
    function query_stock_by_code($code)
    {
        $response = $this->search_product_by_code($code);
        
        if ($response['code'] == 1) {
            //return $response->data->$dataString->inventory->remain; //doesn't work on Codeigniter 3.x
            $stock_remain = reset($response['data']['products'])['inventory']['remain']; //Work on Codeigniter 3.x
            $nhanh_product_id = reset($response['data']['products'])['idNhanh'];
        } else {
            // failed, show error messages
            $stock_remain = -30;
            $nhanh_product_id = 0;
        }
        
        $data = array(
            (object)array(
                'shopeeproductid' => 0,
                'sku' => $code,
                'nhanhproductid' => $nhanh_product_id,
                'nhanhstocknumber' => $stock_remain
            ),
        );
        
        return $data;
    }
    
    function get_order_history($order_id)
    {
        $data_string = "" . $order_id;
        $response = call_nhanh_api(
            "dev",
            "https://dev.nhanh.vn/api/order/history",
            $data_string
        );
        
        return $response;
    }
    
    function get_order($id)
    {
        $data_array = array(
            'id' => $id
        );
        $data_string = json_encode($data_array);
        
        $response = call_nhanh_api(
            "dev",
            "/api/order/index",
            $data_string
        );
        
        return $response;
    }
    
    function push_order_to_nhanh($order_data)
    {
        $response = call_nhanh_api(
            "prod",
            "/api/order/add",
            $order_data
        );
        
        return $response;
    }
    
    function search_product_by_code($code)
    {
        $data_array = array(
            'name' => $code
        );
        
        $data_string = json_encode($data_array);
    
        $response = call_nhanh_api(
            "prod",
            "/api/product/search",
            $data_string
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