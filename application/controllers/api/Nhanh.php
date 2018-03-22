<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nhanh extends CI_Controller {

    public function __construct(){
		parent::__construct();
		
		header('Access-Control-Allow-Origin: *');
	}
	
	public function getorderhistory($orderid)
	{
	    $this->load->model('nhanhmodel');
	    
	    $response = $this -> nhanhmodel -> get_order_history($orderid);
	    
	    json_output(200, $response);
	}
	
	public function getorder($id)
	{
	    $this->load->model('nhanhmodel');
	    
	    $response = $this -> nhanhmodel -> get_order($id);
	    
	    json_output(200, $response);
	}
	
	
	
	public function searchproductbycode($code)
	{
		$this->load->model('nhanhmodel');
            
        $response = $this -> nhanhmodel -> search_product_by_code($code);
        
        json_output(200, $response);
	}
}