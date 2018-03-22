<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shopee extends CI_Controller {

    public function __construct(){
		parent::__construct();
		
		header('Access-Control-Allow-Origin: *');
	}
	
	public function querystock($id, $sku)
	{
		if ($sku == "H0")
		{
			json_output(200, array(
		            (object)array(
		                'shopeeproductid' => $id,
		                'sku' => $sku,
		                'nhanhproductid' => 0,
		                'nhanhstocknumber' => -30
		            ),
		        ));
		    return;
		}
		$this->load->model('shopeemodel');
		$this->load->model('nhanhmodel');

        //$nhanh_product_id = $this->shopeemodel->map_nhanh_id($id);
        //$nhanh_product_id = $this->shopeemodel->map_nhanh_id_mysql($id, $sku);
        //$stock_status = $this->nhanhmodel->query_stock($nhanh_product_id);
        
        $data = $this->nhanhmodel->query_stock_by_code($sku);
        $data[0]->shopeeproductid = $id;
        
        json_output(200, $data);
	}
	
	public function pushnhanhorder()
	{
	    try {
	        //json_output(200, $this->input->post());
            $datastring = json_encode($this->input->post());
            
            $this->load->model('nhanhmodel');
            $this->load->model('shopeemodel');
            
            $response = $this -> nhanhmodel -> push_order_to_nhanh($datastring);
            
            try{
	            if ($response['code'] == 1)
	            {
	            	$shopee_order = json_decode($datastring);
	            	$shopee_order_id = (int)str_replace("Sh", "", $shopee_order->id);
	            	$new_order_id = (int)reset($response['data']);
	            	
	            	$this->shopeemodel->store_pushed_order($shopee_order_id, $new_order_id);
	            }
	            
	            $object = array(
	            	(object)array(
	            		'code' => (int)$response['code'],
	            		'result' => $response,
	            		'shopee_order' => $shopee_order_id,
	            		'nhanh_order' => $new_order_id
	            	)
	            );
            }
            catch(Exception $e)
            {
            	$object = array(
	            	(object)array(
	            		'code' => 0,
	            		'message' => $e.getMessage()
	            	)
	            );
            }
            
            json_output(200, $object);
        }
        catch(Exception $e) {
            json_output(200, $e->getMessage());
        }
	}
	
	public function checkpushed($order_id)
	{
	    $this->load->model('shopeemodel');
	    
	    $is_pushed = $this->shopeemodel->check_order_pushed($order_id);
	    
	    $data = array(
            (object)array(
                'ispushed' => $is_pushed,
            ),
        );
        
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
	
	public function storepushedorder($shopee_order_id, $nhanh_order_id)
	{
	    
	}
}