<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shopee extends CI_Controller {

    public function __construct(){
		parent::__construct();
		
		header('Access-Control-Allow-Origin: *');
	}
	
	public function querystock($id, $sku)
	{
		$this->load->model('shopeemodel');
		$this->load->model('nhanhmodel');

        //$nhanh_product_id = $this->shopeemodel->map_nhanh_id($id);
        $nhanh_product_id = $this->shopeemodel->map_nhanh_id_mysql($id, $sku);
        $stock_status = $this->nhanhmodel->query_stock($nhanh_product_id);
        
        $data = array(
            (object)array(
                'shopeeproductid' => $id,
                'sku' => $sku,
                'nhanhproductid' => $nhanh_product_id,
                'nhanhstocknumber' => $stock_status
            ),
        );
        
        //json_output(200, $json_string);
        json_output(200, $data);
	}
	
	public function putproductstring()
	{
	    $txt = $this -> input -> post('dataString') . "\n";
	    $filename = dirname(__FILE__) . "/test.csv";
	    
	    $myfile = fopen($filename, "a") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
	}
	
	public function storesyncorder($shopee_order_id, $nhanh_order_id)
	{
	    
	}
}