<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Special
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Special extends CI_Controller
{
	public $logged_in_id = null;
	public $now_time = null;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('Appmodel', 'app_model');
		$this->load->model('Crud', 'crud');
		if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
			redirect('/auth/login/');
		}
		$this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['account_id'];
		$this->now_time = date('Y-m-d H:i:s');
	}
	
	function purchase_discount(){
		set_page('special/discount/purchase_discount');
	}
	
	function purchase_discount_list(){
		set_page('special/discount/purchase_discount_list');
	}
	
	function scheme(){
		set_page('special/scheme/scheme');
	}

	function scheme_list(){
		set_page('special/scheme/scheme_list');
	}
	
}

