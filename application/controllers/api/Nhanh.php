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
	
	public function getorder($mobile, $city, $district)
	{
	    $this->load->model('nhanhmodel');
	    
	    $response = $this -> nhanhmodel -> get_order($mobile, $city, $district);
	    
	    json_output(200, $response);
	}
	
	public function pushnhanhorder()
	{
	    try {
	        //json_output(200, $this->input->post());
            $datastring = json_encode($this->input->post());
            
            $this->load->model('nhanhmodel');
            
            $response = $this -> nhanhmodel -> push_order_to_nhanh($datastring);
            
            json_output(200, $response);
        }
        catch(Exception $e) {
            json_output(200, $e->getMessage());
        }
	    
	}
}