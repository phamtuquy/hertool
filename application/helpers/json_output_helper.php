<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
function json_output($statusHeader,$response)
{
	$ci =& get_instance();
	$ci->output->set_header("Access-Control-Allow-Origin: *");
	$ci->output->set_header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");
	$ci->output->set_content_type('application/json');
	$ci->output->set_status_header($statusHeader);
	$ci->output->set_output(json_encode($response));
}