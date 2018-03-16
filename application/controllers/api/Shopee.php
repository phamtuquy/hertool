<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shopee extends CI_Controller {

	public function querystock($id)
	{
		$this->load->model('shopeemodel');
		$this->load->model('nhanhmodel');

        //$nhanh_product_id = $this->shopeemodel->map_nhanh_id($id);
        $nhanh_product_id = $this->shopeemodel->map_nhanh_id_mysql($id);
        $stock_status = $this->nhanhmodel->query_stock($nhanh_product_id);
        
        $json_string = "{";
        $json_string .= "shopeeproductid: {$id},";
        $json_string .= "nhanhproductid: {$nhanh_product_id},";
        $json_string .= "nhanhstocknumber: {$stock_status}";
        $json_string .= "}";
        json_output(200, $json_string);
	}
	
	public function putproductstring()
	{
	    $txt = $this -> input -> post('dataString') . "\n";
	    $filename = dirname(__FILE__) . "/test.csv";
	    
	    $myfile = fopen($filename, "a") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
	}
}