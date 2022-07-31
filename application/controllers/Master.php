<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Master
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Master extends CI_Controller
{
	public $logged_in_id = null;
	public $now_time = null;
	
	function __construct(){
		parent::__construct();
		$this->load->model('Appmodel', 'app_model');
		$this->load->model('Crud', 'crud');
		if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
			redirect('/auth/login/');
		}
		$this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
		$this->now_time = date('Y-m-d H:i:s');
	}
	
	function vehicle(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('vehicle','vehicle_id',$_POST['id']);
			$data = array(
				'id' => $result->vehicle_id,
				'name' => $result->vehicle_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/vehicle', $data);
	}

	function vehicle_datatable(){
		$config['select'] = 'vehicle_id, vehicle_name';
		$config['table'] = 'vehicle';
		$config['column_order'] = array(null, 'vehicle_name');
		$config['column_search'] = array('vehicle_name');
		$config['order'] = array('vehicle_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $vehicles) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $vehicles->vehicle_id . '" method="post" action="' . base_url() . 'master/vehicle" class="pull-left">
                            <input type="hidden" name="id" id="id" value="' . $vehicles->vehicle_id . '">
                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $vehicles->vehicle_id . '\').submit();" title="Edit Vehicle"><i class="fa fa-edit"></i></a>
                        </form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/vehicle/' . $vehicles->vehicle_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $vehicles->vehicle_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $vehicles->vehicle_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_vehicle(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('vehicle','vehicle_id','vehicle_name',trim($post_data['vehicle_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			
			$data['vehicle_name'] = $post_data['vehicle_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['vehicle_id'] = $post_data['id'];
			$result = $this->crud->update('vehicle', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('vehicle','vehicle_id','vehicle_name',trim($post_data['vehicle_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['vehicle_name'] = $post_data['vehicle_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('vehicle',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function vehicle_model(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('vehicle_model','vehicle_model_id',$_POST['id']);
			$data = array(
				'id' => $result->vehicle_model_id,
				'vehicle_id' => $result->vehicle_id,
				'model_name' => $result->vehicle_model_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/vehicle_model', $data);
	}
	
	function save_vehicle_model(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_same_by_val('vehicle_model','vehicle_model_id','vehicle_model_name',trim($post_data['vehicle_model_name']),'vehicle_id',$post_data['vehicle_id'],$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['vehicle_id'] = $post_data['vehicle_id'];
			$data['vehicle_model_name'] = $post_data['vehicle_model_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['vehicle_model_id'] = $post_data['id'];
			$result = $this->crud->update('vehicle_model', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_same_by_val('vehicle_model','vehicle_model_id','vehicle_model_name',trim($post_data['vehicle_model_name']),'vehicle_id',$post_data['vehicle_id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['vehicle_id'] = $post_data['vehicle_id'];
			$data['vehicle_model_name'] = $post_data['vehicle_model_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('vehicle_model',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function vehicle_model_datatable(){
		$config['table'] = 'vehicle_model vm';
		$config['select'] = 'vm.*, v.vehicle_id, v.vehicle_name';
		$config['column_order'] = array(null, 'v.vehicle_name', 'vm.vehicle_model_name');
		$config['column_search'] = array('v.vehicle_name', 'vm.vehicle_model_name');
		$config['joins'][] = array('join_table' => 'vehicle v', 'join_by' => 'v.vehicle_id = vm.vehicle_id', 'join_type' => 'left');
		$config['order'] = array('vm.vehicle_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $vehicles) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $vehicles->vehicle_model_id . '" method="post" action="' . base_url() . 'master/vehicle_model" class="pull-left">
                            <input type="hidden" name="id" id="id" value="' . $vehicles->vehicle_model_id . '">
                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $vehicles->vehicle_model_id . '\').submit();" title="Edit Vehicle model"><i class="fa fa-edit"></i></a>
                        </form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/vehicle/' . $vehicles->vehicle_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $vehicles->vehicle_model_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $vehicles->vehicle_name;
			$row[] = $vehicles->vehicle_model_name;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function location(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('location','location_id',$_POST['id']);
			$data = array(
				'id' => $result->location_id,
				'name' => $result->location_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/location', $data);
	}

	function location_datatable(){
		$config['select'] = 'location_id, location_name';
		$config['table'] = 'location';
		$config['column_order'] = array(null, 'location_name');
		$config['column_search'] = array('location_name');
		$config['order'] = array('location_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $locations) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $locations->location_id . '" method="post" action="' . base_url() . 'master/location" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $locations->location_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $locations->location_id . '\').submit();" title="Edit Location"><i class="fa fa-edit"></i></a>
						</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/location/' . $locations->location_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $locations->location_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $locations->location_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_location(){
		$return = array();
		$post_data = $this->input->post();
		//print_r($post_data);exit;
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('location','location_id','location_name',trim($post_data['location_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['location_name'] = $post_data['location_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['location_id'] = $post_data['id'];
			$result = $this->crud->update('location', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('location','location_id','location_name',trim($post_data['location_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['location_name'] = $post_data['location_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('location',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function rack(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('rack','rack_id',$_POST['id']);
			$data = array(
				'id' => $result->rack_id,
				'location_id' => $result->location_id,
				'model_name' => $result->rack_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/rack', $data);
	}

	function save_rack(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_same_by_val('rack','rack_id','rack_name',trim($post_data['rack_name']),'location_id',$post_data['location_id'],$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['location_id'] = $post_data['location_id'];
			$data['rack_name'] = $post_data['rack_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['rack_id'] = $post_data['id'];
			$result = $this->crud->update('rack', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_same_by_val('rack','rack_id','rack_name',trim($post_data['rack_name']),'location_id',$post_data['location_id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['location_id'] = $post_data['location_id'];
			$data['rack_name'] = $post_data['rack_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('rack',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function rack_datatable(){
		$config['table'] = 'rack vm';
		$config['select'] = 'vm.*, v.location_id, v.location_name';
		$config['column_order'] = array(null, 'v.location_name', 'vm.rack_name');
		$config['column_search'] = array('v.location_name', 'vm.rack_name');
		$config['joins'][] = array('join_table' => 'location v', 'join_by' => 'v.location_id = vm.location_id', 'join_type' => 'left');
		$config['order'] = array('vm.rack_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $racks) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $racks->rack_id . '" method="post" action="' . base_url() . 'master/rack" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $racks->rack_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $racks->rack_id . '\').submit();" title="Edit Rack"><i class="fa fa-edit"></i></a>
						</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/rack/' . $racks->location_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $racks->rack_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $racks->location_name;
			$row[] = $racks->rack_name;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	function item_type(){
		$data = array();
        if (!empty($_POST['id']) && isset($_POST['id'])) {
            if($this->applib->have_access_role(MASTER_ITEM_TYPE_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('item_type','item_type_id',$_POST['id']);
				$data = array(
					'id' => $result->item_type_id,
					'name' => $result->item_type_name,
				);
	        	set_page('master/item_type', $data);;
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
        } else {
            if($this->applib->have_access_role(MASTER_ITEM_TYPE_ID,"view") || $this->applib->have_access_role(MASTER_ITEM_TYPE_ID,"add")) {
	        	set_page('master/item_type', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
        }
	}

	function item_type_datatable(){
		$config['select'] = 'item_type_id, item_type_name';
		$config['table'] = 'item_type';
		$config['column_order'] = array(null, 'item_type_name');
		$config['column_search'] = array('item_type_name');
		$config['order'] = array('item_type_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_ITEM_TYPE_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_ITEM_TYPE_ID, "delete");
		foreach ($list as $item_types) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $item_types->item_type_id . '" method="post" action="' . base_url() . 'master/item_type" class="pull-left">
						<input type="hidden" name="id" id="id" value="' . $item_types->item_type_id . '">
						<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $item_types->item_type_id . '\').submit();" title="Edit Company"><i class="fa fa-edit"></i></a>
						</form>';	
			}
			
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $item_types->item_type_id) . '"><i class="fa fa-trash"></i></a>';	
			}
			
			$row[] = $action;
			$row[] = $item_types->item_type_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_item_type(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('item_type','item_type_id','item_type_name',trim($post_data['item_type_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['item_type_name'] = $post_data['item_type_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['item_type_id'] = $post_data['id'];
			$result = $this->crud->update('item_type', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('item_type','item_type_id','item_type_name',trim($post_data['item_type_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['item_type_name'] = $post_data['item_type_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('item_type',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
        
	function invoice_type(){
		if(!empty($_POST['invoice_type_id']) && isset($_POST['invoice_type_id'])){
			if($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('invoice_type','invoice_type_id',$_POST['invoice_type_id']);
				$data = array(
					'invoice_type_id' => $result->invoice_type_id,
					'invoice_type' => $result->invoice_type,
				);
	        	set_page('master/invoice_type', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
			
		} else {
			if($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"view") || $this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"add")) {
				$data = array();
	        	set_page('master/invoice_type', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		}
	}

	function invoice_type_datatable(){
		$config['select'] = 'invoice_type_id, invoice_type, delete_allow';
		$config['table'] = 'invoice_type';
		$config['column_order'] = array(null, 'invoice_type');
		$config['column_search'] = array('invoice_type');
		$config['order'] = array('invoice_type' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_INVOICE_TYPE_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_INVOICE_TYPE_ID, "delete");
		foreach ($list as $invoice_type) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $invoice_type->invoice_type_id . '" method="post" action="' . base_url() . 'master/invoice_type" class="pull-left">
						<input type="hidden" name="invoice_type_id" id="invoice_type_id" value="' . $invoice_type->invoice_type_id . '">
						<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $invoice_type->invoice_type_id . '\').submit();" title="Edit Company"><i class="fa fa-edit"></i></a>
						</form>';
			}

			if($isDelete) {
				if($invoice_type->delete_allow == '0'){
                    $action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $invoice_type->invoice_type_id) . '"><i class="fa fa-trash"></i></a>';
                }
			}
                        
			$row[] = $action;
			$row[] = $invoice_type->invoice_type;
			$data[] = $row;
		}
                
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_invoice_type(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['invoice_type_id']) && !empty($post_data['invoice_type_id'])){
			$v_id = $this->crud->get_id_by_val_not('invoice_type','invoice_type_id','invoice_type',trim($post_data['invoice_type']),$post_data['invoice_type_id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['invoice_type'] = $post_data['invoice_type'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['invoice_type_id'] = $post_data['invoice_type_id'];
			$result = $this->crud->update('invoice_type', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('invoice_type','invoice_type_id','invoice_type',trim($post_data['invoice_type']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['invoice_type'] = $post_data['invoice_type'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('invoice_type',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	        
	function segment(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('segment','segment_id',$_POST['id']);
			$data = array(
				'id' => $result->segment_id,
				'name' => $result->segment_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/segment', $data);
	}

	function segment_datatable(){
		$config['select'] = 'segment_id, segment_name';
		$config['table'] = 'segment';
		$config['column_order'] = array(null, 'segment_name');
		$config['column_search'] = array('segment_name');
		$config['order'] = array('segment_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $segments) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $segments->segment_id . '" method="post" action="' . base_url() . 'master/segment" class="pull-left">
	<input type="hidden" name="id" id="id" value="' . $segments->segment_id . '">
	<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $segments->segment_id . '\').submit();" title="Edit Segment"><i class="fa fa-edit"></i></a>
</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/segment/' . $segments->segment_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $segments->segment_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $segments->segment_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_segment(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('segment','segment_id','segment_name',trim($post_data['segment_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['segment_name'] = $post_data['segment_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['segment_id'] = $post_data['id'];
			$result = $this->crud->update('segment', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('segment','segment_id','segment_name',trim($post_data['segment_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['segment_name'] = $post_data['segment_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('segment',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function employee_type(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('employee_type','employee_type_id',$_POST['id']);
			$data = array(
				'id' => $result->employee_type_id,
				'name' => $result->employee_type_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/employee_type', $data);
	}

	function employee_type_datatable(){
		$config['select'] = 'employee_type_id, employee_type_name';
		$config['table'] = 'employee_type';
		$config['column_order'] = array(null, 'employee_type_name');
		$config['column_search'] = array('employee_type_name');
		$config['order'] = array('employee_type_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $employee_types) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $employee_types->employee_type_id . '" method="post" action="' . base_url() . 'master/employee_type" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $employee_types->employee_type_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $employee_types->employee_type_id . '\').submit();" title="Edit Employee Type"><i class="fa fa-edit"></i></a>
						</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/employee_type/' . $employee_types->employee_type_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $employee_types->employee_type_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $employee_types->employee_type_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_employee_type(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('employee_type','employee_type_id','employee_type_name',trim($post_data['employee_type_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['employee_type_name'] = $post_data['employee_type_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['employee_type_id'] = $post_data['id'];
			$result = $this->crud->update('employee_type', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('employee_type','employee_type_id','employee_type_name',trim($post_data['employee_type_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['employee_type_name'] = $post_data['employee_type_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('employee_type',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function plant(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('plant','plant_id',$_POST['id']);
			$data = array(
				'id' => $result->plant_id,
				'name' => $result->plant_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/plant', $data);
	}

	function plant_datatable(){
		$config['select'] = 'plant_id, plant_name';
		$config['table'] = 'plant';
		$config['column_order'] = array(null, 'plant_name');
		$config['column_search'] = array('plant_name');
		$config['order'] = array('plant_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $plants) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $plants->plant_id . '" method="post" action="' . base_url() . 'master/plant" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $plants->plant_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $plants->plant_id . '\').submit();" title="Edit Plant"><i class="fa fa-edit"></i></a>
						</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/plant/' . $plants->plant_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $plants->plant_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $plants->plant_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_plant(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('plant','plant_id','plant_name',trim($post_data['plant_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['plant_name'] = $post_data['plant_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['plant_id'] = $post_data['id'];
			$result = $this->crud->update('plant', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('plant','plant_id','plant_name',trim($post_data['plant_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['plant_name'] = $post_data['plant_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('plant',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function pack_unit(){
     //    $li_unit = isset($this->session->userdata()['li_unit']) ? $this->session->userdata()['li_unit'] : '0';
    	// if ($li_unit == 1) {
			if(!empty($_POST['id']) && isset($_POST['id'])){
				if($this->applib->have_access_role(MASTER_UNIT_ID,"edit")) {
	            	$result = $this->crud->get_data_row_by_id('pack_unit','pack_unit_id',$_POST['id']);
					$data = array(
						'id' => $result->pack_unit_id,
						'name' => $result->pack_unit_name,
						'created_by' => $result->created_by,
						'updated_by' => $result->updated_by,
						'created_at' => date('Y-m-d H:m a', strtotime( $result->created_at )),
						'updated_at' => date('Y-m-d H:m a', strtotime( $result->updated_at )),
					);
					set_page('master/pack_unit', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
                	$this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
			} else {
				if($this->applib->have_access_role(MASTER_UNIT_ID,"view") || $this->applib->have_access_role(MASTER_UNIT_ID,"add")) {
					$data = array();	
					set_page('master/pack_unit', $data);
				} else {
					$this->session->set_flashdata('success', false);
                	$this->session->set_flashdata('message', 'You have not permission to access this page.');
	                redirect('/');
		        }
			}			
        // } else {
        //     redirect('/');
        // }
	}

	function pack_unit_datatable(){
		$config['select'] = 'pack_unit_id, pack_unit_name';
		$config['table'] = 'pack_unit';
		$config['column_order'] = array(null, 'pack_unit_name');
		$config['column_search'] = array('pack_unit_name');
		$config['order'] = array('pack_unit_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_UNIT_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_UNIT_ID, "delete");
		foreach ($list as $pack_units) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $pack_units->pack_unit_id . '" method="post" action="' . base_url() . 'master/pack_unit" class="pull-left">
						<input type="hidden" name="id" id="id" value="' . $pack_units->pack_unit_id . '">
						<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $pack_units->pack_unit_id . '\').submit();" title="Edit Pack Unit"><i class="fa fa-edit"></i></a>
					</form>';
			}
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $pack_units->pack_unit_id) . '"><i class="fa fa-trash"></i></a>';
			}			
			$row[] = $action;
			$row[] = $pack_units->pack_unit_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_pack_unit(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('pack_unit','pack_unit_id','pack_unit_name',trim($post_data['pack_unit_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['pack_unit_name'] = $post_data['pack_unit_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['pack_unit_id'] = $post_data['id'];
			$result = $this->crud->update('pack_unit', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('pack_unit','pack_unit_id','pack_unit_name',trim($post_data['pack_unit_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['pack_unit_name'] = $post_data['pack_unit_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('pack_unit',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function transport(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('transport','transport_id',$_POST['id']);
			$data = array(
				'id' => $result->transport_id,
				'name' => $result->transport_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/transport', $data);
	}

	function transport_datatable(){
		$config['select'] = 'transport_id, transport_name';
		$config['table'] = 'transport';
		$config['column_order'] = array(null, 'transport_name');
		$config['column_search'] = array('transport_name');
		$config['order'] = array('transport_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $transports) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $transports->transport_id . '" method="post" action="' . base_url() . 'master/transport" class="pull-left">
	<input type="hidden" name="id" id="id" value="' . $transports->transport_id . '">
	<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $transports->transport_id . '\').submit();" title="Edit Transport"><i class="fa fa-edit"></i></a>
</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/transport/' . $transports->transport_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $transports->transport_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $transports->transport_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_transport(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('transport','transport_id','transport_name',trim($post_data['transport_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['transport_name'] = $post_data['transport_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['transport_id'] = $post_data['id'];
			$result = $this->crud->update('transport', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('transport','transport_id','transport_name',trim($post_data['transport_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['transport_name'] = $post_data['transport_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('transport',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function category(){
        $li_category = isset($this->session->userdata()['li_category']) ? $this->session->userdata()['li_category'] : '0';
        if($li_category == '1'){
			if(!empty($_POST['id']) && isset($_POST['id'])){
				if($this->applib->have_access_role(MASTER_CATEGORY_ID,"edit")) {
	            	$result = $this->crud->get_data_row_by_id('category','cat_id',$_POST['id']);
					$data = array(
						'id' => $result->cat_id,
						'cat_name' => $result->cat_name,
					);
					set_page('master/category', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
                	$this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
			} else {
				if($this->applib->have_access_role(MASTER_CATEGORY_ID,"view") || $this->applib->have_access_role(MASTER_CATEGORY_ID,"add")) {
	            	$data = array();	
					set_page('master/category', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
                	$this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
			}
        } else {
            redirect('/');
        }
	}

	function save_category(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$id_result = $this->crud->get_id_by_val_not('category','cat_id','cat_name',trim($post_data['cat_name']),$post_data['id']);
			if(!empty($id_result)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['cat_name'] = $post_data['cat_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['cat_id'] = $post_data['id'];
			$result = $this->crud->update('category', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		} else {
			$id_result = $this->crud->get_id_by_val('category','cat_id','cat_name',trim($post_data['cat_name']));
			if(!empty($id_result)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['cat_name'] = $post_data['cat_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('category',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function category_datatable(){
		$config['table'] = 'category c';
		$config['select'] = 'c.cat_id AS cat_id, c.cat_name AS cat_name';
		$config['column_order'] = array(null, 'c.cat_name');
		$config['column_search'] = array('c.cat_name');
		$config['order'] = array('c_cat_name' => 'asc');
                $config['wheres'][] = array('column_name' => 'c.created_by', 'column_value' => $this->logged_in_id);
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_CATEGORY_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_CATEGORY_ID, "delete");
		foreach ($list as $categorys) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $categorys->cat_id . '" method="post" action="' . base_url() . 'master/category" class="pull-left">
				<input type="hidden" name="id" id="id" value="' . $categorys->cat_id . '">
				<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $categorys->cat_id . '\').submit();" title="Edit Category"><i class="fa fa-edit"></i></a>
				</form>';
			}
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $categorys->cat_id) . '"><i class="fa fa-trash"></i></a>';
			}

			$row[] = $action;
			$row[] = $categorys->cat_name;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	function account_group(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			if($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('account_group','account_group_id',$_POST['id']);
				$data = array(
					'id' => $result->account_group_id,
					'parent_group_id' => $result->parent_group_id,
					'account_group_name' => $result->account_group_name,
					'sequence' => $result->sequence,
					'display_in_balance_sheet' => $result->display_in_balance_sheet,
				);
				set_page('master/account_group', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
			
		} else {
			if($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"view") || $this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"add")) {
            	$data = array();
	        	set_page('master/account_group', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }	
		}
		
	}

	function save_account_group(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['parent_group_id']) && !empty($post_data['parent_group_id'])){
			$parent_group_id = $post_data['parent_group_id'];
		} else {
			$parent_group_id = 0;
		}
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$id_result = $this->crud->get_same_by_val('account_group','account_group_id','account_group_name',trim($post_data['account_group_name']),'parent_group_id',$parent_group_id,$post_data['id']);
			if(!empty($id_result)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['display_in_balance_sheet'] = (isset($post_data['display_in_balance_sheet'])?1:0);
			$data['parent_group_id'] = $parent_group_id;
			$data['account_group_name'] = $post_data['account_group_name'];
			$data['sequence'] = $post_data['sequence'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['account_group_id'] = $post_data['id'];
			$result = $this->crud->update('account_group', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		} else {
			$id_result = $this->crud->get_same_by_val('account_group','account_group_id','account_group_name',trim($post_data['account_group_name']),'parent_group_id',$parent_group_id);
			if(!empty($id_result)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['display_in_balance_sheet'] = (isset($post_data['display_in_balance_sheet'])?1:0);
			$data['parent_group_id'] = $parent_group_id;
			$data['account_group_name'] = $post_data['account_group_name'];
			$data['sequence'] = $post_data['sequence'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('account_group',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function account_group_datatable(){
		$config['table'] = 'account_group a';
		$config['select'] = 'a.*, pa.account_group_name as pa_account_group_name';
		$config['column_order'] = array(null,'pa.account_group_name', 'a.account_group_name', 'a.sequence');
		$config['column_search'] = array('pa.account_group_name','a.account_group_name');
		$config['joins'][] = array('join_table' => 'account_group pa', 'join_by' => 'pa.account_group_id = a.parent_group_id', 'join_type' => 'left');
		$config['order'] = array('a account_group_name' => 'asc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID, "delete");
		foreach ($list as $account_group) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $account_group->account_group_id . '" method="post" action="' . base_url() . 'master/account_group" class="pull-left">
					<input type="hidden" name="id" id="id" value="' . $account_group->account_group_id . '">
					<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $account_group->account_group_id . '\').submit();" title="Edit Account Group"><i class="fa fa-edit"></i></a>
					</form>';
			}
			if($isDelete) {
				if($account_group->is_deletable == 1){
					$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete_account_group/' . $account_group->account_group_id) . '"><i class="fa fa-trash"></i></a>';
				}
			}
			
			
			$row[] = $action;
			$row[] = $account_group->pa_account_group_name;
			$row[] = $account_group->account_group_name;
			$row[] = $account_group->sequence;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	function lineitem_type(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('lineitem_type','lineitem_type_id',$_POST['id']);
			$data = array(
				'id' => $result->lineitem_type_id,
				'name' => $result->lineitem_type_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/lineitem_type', $data);
	}

	function lineitem_type_datatable(){
		$config['select'] = 'lineitem_type_id, lineitem_type_name';
		$config['table'] = 'lineitem_type';
		$config['column_order'] = array(null, 'lineitem_type_name');
		$config['column_search'] = array('lineitem_type_name');
		$config['order'] = array('lineitem_type_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $lineitem_types) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $lineitem_types->lineitem_type_id . '" method="post" action="' . base_url() . 'master/lineitem_type" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $lineitem_types->lineitem_type_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $lineitem_types->lineitem_type_id . '\').submit();" title="Edit Lineitem Type"><i class="fa fa-edit"></i></a>
						</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/lineitem_type/' . $lineitem_types->lineitem_type_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $lineitem_types->lineitem_type_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $lineitem_types->lineitem_type_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_lineitem_type(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('lineitem_type','lineitem_type_id','lineitem_type_name',trim($post_data['lineitem_type_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['lineitem_type_name'] = $post_data['lineitem_type_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['lineitem_type_id'] = $post_data['id'];
			$result = $this->crud->update('lineitem_type', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('lineitem_type','lineitem_type_id','lineitem_type_name',trim($post_data['lineitem_type_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['lineitem_type_name'] = $post_data['lineitem_type_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('lineitem_type',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function via(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('via','via_id',$_POST['id']);
			$data = array(
				'id' => $result->via_id,
				'name' => $result->via_name,
			);
		}else{
			$data = array();	
		}
		set_page('master/via', $data);
	}

	function via_datatable(){
		$config['select'] = 'via_id, via_name';
		$config['table'] = 'via';
		$config['column_order'] = array(null, 'via_name');
		$config['column_search'] = array('via_name');
		$config['order'] = array('via_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->applib->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $vias) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $vias->via_id . '" method="post" action="' . base_url() . 'master/via" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $vias->via_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $vias->via_id . '\').submit();" title="Edit Via"><i class="fa fa-edit"></i></a>
						</form>';
			//$action .= ' &nbsp; <a href="' . base_url('master/via/' . $vias->via_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $vias->via_id) . '"><i class="fa fa-trash"></i></a>';
			$row[] = $action;
			$row[] = $vias->via_name;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_via(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('via','via_id','via_name',trim($post_data['via_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['via_name'] = $post_data['via_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['via_id'] = $post_data['id'];
			$result = $this->crud->update('via', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('via','via_id','via_name',trim($post_data['via_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['via_name'] = $post_data['via_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('via',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function state(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			if($this->applib->have_access_role(MASTER_STATE_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('state','state_id',$_POST['id']);
				$data = array(
					'id' => $result->state_id,
					'name' => $result->state_name,
	                                'code' => $result->state_code
				);
				set_page('master/state', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		} else {
			if($this->applib->have_access_role(MASTER_STATE_ID,"view") || $this->applib->have_access_role(MASTER_STATE_ID,"add")) {
            	$data = array();
				set_page('master/state', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		}		
	}

	function state_datatable(){
		$config['select'] = 'state_id, state_name, state_code';
		$config['table'] = 'state';
		$config['column_order'] = array(null, 'state_name', 'state_code');
		$config['column_search'] = array('state_name', 'state_code');
		$config['order'] = array('state_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_STATE_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_STATE_ID, "delete");
		foreach ($list as $states) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $states->state_id . '" method="post" action="' . base_url() . 'master/state" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $states->state_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $states->state_id . '\').submit();" title="Edit State"><i class="fa fa-edit"></i></a>
						</form>';	
			}

			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $states->state_id) . '"><i class="fa fa-trash"></i></a>';	
			}
			
			$row[] = $action;
			$row[] = $states->state_name;
                        $row[] = $states->state_code;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_state(){
		$return = array();
		$post_data = $this->input->post();
                $state_code = $post_data['state_code'];
                if(strlen((string)$state_code) == 1) {
                    $state_code = '0'.$state_code;
                }
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('state','state_id','state_name',trim($post_data['state_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}

			$data['state_name'] = $post_data['state_name'];
                        $data['state_code'] = $state_code;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['state_id'] = $post_data['id'];
			$result = $this->crud->update('state', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('state','state_id','state_name',trim($post_data['state_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['state_name'] = ucfirst($post_data['state_name']);
                        $data['state_code'] = $state_code;
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('state',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}
	
	function city(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			if($this->applib->have_access_role(MASTER_CITY_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('city','city_id',$_POST['id']);
				$data = array(
					'id' => $result->city_id,
					'state_id' => $result->state_id,
					'model_name' => $result->city_name,
				);
				set_page('master/city', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
			
		} else {
			if($this->applib->have_access_role(MASTER_CITY_ID,"view") || $this->applib->have_access_role(MASTER_CITY_ID,"add")) {
            	$data = array();
	        	set_page('master/city', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		}
		
	}

	function save_city(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_same_by_val('city','city_id','city_name',trim($post_data['city_name']),'state_id',$post_data['state_id'],$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['state_id'] = $post_data['state_id'];
			$data['city_name'] = $post_data['city_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['city_id'] = $post_data['id'];
			$result = $this->crud->update('city', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_same_by_val('city','city_id','city_name',trim($post_data['city_name']),'state_id',$post_data['state_id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['state_id'] = $post_data['state_id'];
			$data['city_name'] = $post_data['city_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('city',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function city_datatable(){
		$config['table'] = 'city c';
		$config['select'] = 'c.*, s.state_id, s.state_name';
		$config['column_order'] = array(null, 's.state_name', 'c.city_name');
		$config['column_search'] = array('s.state_name', 'c.city_name');
		$config['joins'][] = array('join_table' => 'state s', 'join_by' => 's.state_id = c.state_id', 'join_type' => 'left');
		$config['order'] = array('c.city_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_CITY_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_CITY_ID, "delete");
		foreach ($list as $cities) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $cities->city_id . '" method="post" action="' . base_url() . 'master/city" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $cities->city_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $cities->city_id . '\').submit();" title="Edit City"><i class="fa fa-edit"></i></a>
						</form>';
			}
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $cities->city_id) . '"><i class="fa fa-trash"></i></a>';
			}
			
			$row[] = $action;
			$row[] = $cities->state_name;
			$row[] = $cities->city_name;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function delete($id){
		$return = array();
		$table = $_POST['table_name'];
		$id_name = $_POST['id_name'];
		$result = $this->crud->delete($table,array($id_name=>$id));
		if(isset($result['error'])){
            $return['error'] = "Error";
        } else {
        	$return['success'] = "Deleted";
        }
        print json_encode($return);
        exit;
	}

	function check_is_unique(){
		$table_name = trim($_POST['table_name']);
		$column_name = trim($_POST['column_name']);
		$column_value = trim($_POST['column_value']);
		$id_column_name = isset($_POST['id_column_name']) ? trim($_POST['id_column_name']) : '';
		$id_column_value = isset($_POST['id_column_value']) ? trim($_POST['id_column_value']) : '';

		$this->db->select($column_name);
		$this->db->from($table_name);
		if ($id_column_name != '' && $id_column_value != '') {
			$this->db->where("$id_column_name !=", $id_column_value);
		}
		$this->db->where("LOWER($column_name)", strtolower($column_value));
		if ($this->db->get()->num_rows() > 0) {
			echo "0";
		} else {
			echo "1";
		}
		exit();
	}

    function item_group() {
        $data = array();
        if (!empty($_POST['id']) && isset($_POST['id'])) {
            if($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('item_group', 'item_group_id', $_POST['id']);
	            $data['id'] = $result->item_group_id;
	            $data['name'] = $result->item_group_name;
	            $data['edit_discount_on'] = $result->discount_on;
	        	set_page('master/item_group', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
        } else {
            $data['edit_discount_on'] = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['discount_on'];
            if($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"view") || $this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"add")) {
	        	set_page('master/item_group', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
        }        
    }

    function item_group_datatable(){
		$config['select'] = 'item_group_id, item_group_name, discount_on';
		$config['table'] = 'item_group';
		$config['column_order'] = array(null, 'item_group_name');
		$config['column_search'] = array('item_group_name');
		$config['order'] = array('item_group_name' => 'desc');
        $config['wheres'][] = array('column_name' => 'created_by', 'column_value' => $this->logged_in_id);
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_ITEM_GROUP_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_ITEM_GROUP_ID, "delete");
		foreach ($list as $item_groups) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $item_groups->item_group_id . '" method="post" action="' . base_url() . 'master/item_group" class="pull-left">
						<input type="hidden" name="id" id="id" value="' . $item_groups->item_group_id . '">
						<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $item_groups->item_group_id . '\').submit();" title="Edit Item Group"><i class="fa fa-edit"></i></a>
						</form>';	
			}

			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $item_groups->item_group_id) . '"><i class="fa fa-trash"></i></a>';	
			}
			
			$row[] = $action;
			$row[] = $item_groups->item_group_name;
            $discount_on = '';
            if($item_groups->discount_on == 1){ $discount_on = 'List Price'; }
            if($item_groups->discount_on == 2){ $discount_on = 'MRP'; }
			$row[] = $discount_on;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_item_group() {
        $return = array();
        $post_data = $this->input->post();
        $data['item_group_name'] = $post_data['item_group_name'];
        $data['discount_on'] = $post_data['discount_on'];
        $data['updated_at'] = $this->now_time;
        $data['updated_by'] = $this->logged_in_id;
        $data['user_updated_by'] = $this->session->userdata()['login_user_id'];
        if (isset($post_data['id']) && !empty($post_data['id'])) {
            $v_id = $this->crud->get_id_by_val_not('item_group', 'item_group_id', 'item_group_name', trim($post_data['item_group_name']), $post_data['id']);
            if (!empty($v_id)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $where_array['item_group_id'] = $post_data['id'];
            $result = $this->crud->update('item_group', $data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Item Group Successfully Updated.');
            }
        } else {
            $v_id = $this->crud->get_id_by_val('item_group', 'item_group_id', 'item_group_name', trim($post_data['item_group_name']));
            if (!empty($v_id)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $data['created_at'] = $this->now_time;
            $data['created_by'] = $this->logged_in_id;
            $data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('item_group', $data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Item Group Successfully Added.');
            }
        }
        print json_encode($return);
        exit;
    }

    function import(){
    	if($this->applib->have_access_role(MASTER_IMPORT_ID,"view")) {
        	$data = array();
        	set_page('master/import', $data);
        } else {
        	$this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
        	redirect('/');
        }
    }

    function save_import() {
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time',0);
		set_time_limit(0);
		
            $data = array();

            if ($this->input->post('submit')) {
                $radio_type = $this->input->post('import_radio');
                if($radio_type == 2) {
                	require_once('application/third_party/PHPExcel/PHPExcel.php');
                	$objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    unset($allDataInSheet[1]);
                    $import_acc_data = array();
                    foreach ($allDataInSheet as $key => $excel_row) {
                    	$excel_row = array_map('trim', $excel_row);

                    	if(empty($excel_row['A'])) {
                    		continue;
                    	}

                    	$acc_data = array(
                    		'account_name' => $excel_row['A'],
                    		'account_group_id' => $excel_row['B'],
                    		'opening_balance' => $excel_row['C'],
                    		'credit_debit' => ($excel_row['C'] >= 0?"1":"2"),
                    		'account_city' => $excel_row['D'],
                    		'account_postal_code' => $excel_row['E'],
                    		'account_state' => $excel_row['F'],
                    		'account_pan' => $excel_row['G'],
                    		'account_aadhaar' => $excel_row['H'],
                    		'account_gst_no' => $excel_row['I'],
                    		'account_contect_person_name' => $excel_row['J'],
                    		'account_address' => $excel_row['K'],
                    		'account_mobile_numbers' => $excel_row['L'],
                    		'account_phone' => $excel_row['M'],
                    		'account_email_ids' => $excel_row['N'],
                    		'consider_in_pl' => 1,
                    		'created_by' => $this->logged_in_id,
                    		'user_created_by' => $this->session->userdata()['login_user_id'],
                    		'created_at' => $this->now_time,
                    	);

                    	$account_row = $this->crud->get_data_row_by_where('account',array('account_name' => $excel_row['A'], 'created_by' => $this->logged_in_id));

                    	if(!empty($account_row)) {
                    		$import_acc_data[] = array(
                    			'account_name' => $excel_row['A'],
                    			'message' => '<span class="text-red">Account Already Exist</span>',
                    		);
                    		continue;
                    	}


                    	if(!empty($excel_row['N'])) {
                    		$this->db->select('*');
		                    $this->db->from('account');
		                    $this->db->where('created_by', $this->logged_in_id);
		                    $this->db->like('account_email_ids',$excel_row['N']);
		                    $res = $this->db->get();
		                    if ($res->num_rows() > 0) {
		                        $import_acc_data[] = array(
	                    			'account_name' => $excel_row['A'],
	                    			'message' => '<span class="text-red">Email Id Already Exist</span>',
	                    		);
	                    		continue;
		                    }	
                    	}
                    	

                    	$account_group_row = $this->crud->get_data_row_by_id('account_group','account_group_name',$excel_row['B']);
                    	if(empty($account_group_row)) {
                    		$import_acc_data[] = array(
                    			'account_name' => $excel_row['A'],
                    			'message' => '<span class="text-red">Account Group Not Exist</span>',
                    		);
                    		continue;
                    	}
                    	$acc_data['account_group_id'] = $account_group_row->account_group_id;

                    	$account_state_row = $this->crud->get_data_row_by_id('state','state_name',$excel_row['F']);
                    	if(empty($account_state_row)) {
                    		$import_acc_data[] = array(
                    			'account_name' => $excel_row['A'],
                    			'message' => '<span class="text-red">Account State Not Exist</span>',
                    		);
                    		continue;
                    		
                    	}
                    	$acc_data['account_state'] = $account_state_row->state_id;

                    	$account_city_row = $this->crud->get_data_row_by_where('city',array('city_name'=>$excel_row['D'],'state_id' => $account_state_row->state_id));
                    	if(!empty($account_city_row)) {
                    		$acc_data['account_city'] = $account_city_row->city_id;
                    	} else {
                    		$data = array();
                    		$data['state_id'] = $account_state_row->state_id;
							$data['city_name'] = $excel_row['D'];
							$data['created_at'] = $this->now_time;
							$data['updated_at'] = $this->now_time;
							$data['updated_by'] = $this->logged_in_id;
							$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
							$data['created_by'] = $this->logged_in_id;
							$data['user_created_by'] = $this->session->userdata()['login_user_id'];
							$acc_data['account_city'] = $this->crud->insert('city',$data);
                    	}

                    	$this->crud->insert('account',$acc_data);
                    	$import_acc_data[] = array(
                			'account_name' => $excel_row['A'],
                			'message' => '<span class="text-green">Account Inserted</span>',
                		);
                    }

                    $data['import_acc_data'] = $import_acc_data;
                }
                if($radio_type == 3) {
                    $ob= simplexml_load_file($_FILES['userfile']['tmp_name']);
                    $json  = json_encode($ob);
                    $configData = json_decode($json, true);
                    $xmlData = '';
                    if(!empty($configData) && isset($configData['BODY']['IMPORTDATA']['REQUESTDATA']['TALLYMESSAGE'])) {
                        $xmlData = $configData['BODY']['IMPORTDATA']['REQUESTDATA']['TALLYMESSAGE'];
                    }
                    if(!empty($xmlData)) {
                        $duplicate_parties = array();
                        $master_parties = array();
                        foreach ($xmlData as $key => $value) {
                            $voucher_remote_id = isset($value['VOUCHER']['@attributes']['REMOTEID']) ? $value['VOUCHER']['@attributes']['REMOTEID'] : '';
                            $account_name = isset($value['VOUCHER']['PARTYLEDGERNAME']) ? $value['VOUCHER']['PARTYLEDGERNAME'] : '';
                            $payment_date = isset($value['VOUCHER']['DATE']) && !empty($value['VOUCHER']['DATE']) ? date('Y-m-d', strtotime($value['VOUCHER']['DATE'])) : '';
                            $amount = isset($value['VOUCHER']['ALLLEDGERENTRIES.LIST']['AMOUNT']) ? $value['VOUCHER']['ALLLEDGERENTRIES.LIST']['AMOUNT'] : '';
                            $account_name_exist = $this->crud->getFromSQL("SELECT * FROM account WHERE TRIM(`account_name`) = '".trim($account_name)."' ");
                            if($account_name_exist){
                                $account_id = $account_name_exist[0]->account_id;
                                //print_r($account_id);die;
                                $remoteId_duplication = $this->crud->get_column_value_by_id('transaction_entry', 'voucher_remote_id', array('voucher_remote_id' => $voucher_remote_id));
                                if($remoteId_duplication){
                                    $duplicate_parties[] = $account_name;
                                } else {
                                    $payment_data = array();
                                    $payment_data['to_account_id'] = $account_id;
                                    $payment_data['transaction_date'] = $payment_date;
                                    $payment_data['transaction_type'] = 1;
                                    $payment_data['amount'] = $amount;
                                    $payment_data['voucher_remote_id'] = $voucher_remote_id;
                                    $payment_data['created_at'] = $this->now_time;
                                    $payment_data['created_by'] = $this->logged_in_id;
                                    $payment_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('transaction_entry', $payment_data);
                                }
                            } else {
                                $master_parties[] = $account_name;
                            }
                        }
                        $data['master_parties'] = $master_parties;
                        $data['duplicate_parties'] = $duplicate_parties;
                    }
                }
                if($radio_type == 4) {
                    $this->load->library('CSVReader');
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['userfile']['tmp_name']);
                    // Insert/update CSV data into database
                    if(!empty($csvData)){
                        foreach($csvData as $row){
//                            echo "<pre>"; print_r($row); exit;
                            $item_type_id = NULL;
                            if(!empty($row['Item_Type'])){
                                $item_type_id = $this->crud->get_column_value_by_id('item_type','item_type_id',array('item_type_name' => trim($row['Item_Type'])));
                                if(empty($item_type_id)) {
                                    $item_type_data = array();
                                    $item_type_data['item_type_name'] = $row['Item_Type'];
                                    $item_type_data['created_at'] = $this->now_time;
                                    $item_type_data['created_by'] = $this->logged_in_id;
                                    $item_type_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('item_type',$item_type_data);
                                    $item_type_id = $this->db->insert_id();
                                }
                            }
                            $item_group_id = NULL;
                            if(!empty($row['Item_Group'])){
                                $item_group_id = $this->crud->get_column_value_by_id('item_group','item_group_id',array('item_group_name' => trim($row['Item_Group']), 'created_by' => $this->logged_in_id));
                                if(empty($item_group_id)) {
                                    $item_group_data = array();
                                    $item_group_data['item_group_name'] = $row['Item_Group'];
                                    $item_group_data['created_at'] = $this->now_time;
                                    $item_group_data['created_by'] = $this->logged_in_id;
                                    $item_group_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('item_group',$item_group_data);
                                    $item_group_id = $this->db->insert_id();
                                }
                            }
                            $item_category_id = NULL;
                            if(!empty($row['Item_Category'])){
                                $item_category_id = $this->crud->get_column_value_by_id('category','cat_id',array('cat_name' => trim($row['Item_Category']), 'created_by' => $this->logged_in_id));
                                if(empty($item_category_id)) {
                                    $item_category_data = array();
                                    $item_category_data['cat_name'] = $row['Item_Category'];
                                    $item_category_data['created_at'] = $this->now_time;
                                    $item_category_data['created_by'] = $this->logged_in_id;
                                    $item_category_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('category',$item_category_data);
                                    $item_category_id = $this->db->insert_id();
                                }
                            }
                            $item_sub_category_id = NULL;
                            if(!empty($item_category_id)){
                                if(!empty($row['Item_Sub_Category'])){
                                    $it_data = $this->crud->getFromSQL(" SELECT * FROM sub_category WHERE cat_id = ".$item_category_id." AND sub_cat_name = '".trim($row['Item_Sub_Category'])."' AND created_by = ".$this->logged_in_id." ");
//                                    echo "<pre>"; print_r($it_data); exit;
                                    if(isset($it_data[0])){
                                        $item_sub_category_id = $it_data[0]->sub_cat_id;
                                    } else {
                                        $item_sub_category_data = array();
                                        $item_sub_category_data['cat_id'] = $item_category_id;
                                        $item_sub_category_data['sub_cat_name'] = $row['Item_Sub_Category'];
                                        $item_sub_category_data['created_at'] = $this->now_time;
                                        $item_sub_category_data['created_by'] = $this->logged_in_id;
                                        $item_sub_category_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                        $result = $this->crud->insert('sub_category',$item_sub_category_data);
                                        $item_sub_category_id = $this->db->insert_id();
                                    }
                                }
                            }
                            $pack_unit_id = NULL;
                            if(!empty($row['Unit'])){
                                $pack_unit_id = $this->crud->get_column_value_by_id('pack_unit','pack_unit_id',array('pack_unit_name' => trim($row['Unit'])));
                                if(empty($pack_unit_id)) {
                                    $pack_unit_data = array();
                                    $pack_unit_data['pack_unit_name'] = $row['Unit'];
                                    $pack_unit_data['created_at'] = $this->now_time;
                                    $pack_unit_data['created_by'] = $this->logged_in_id;
                                    $pack_unit_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('pack_unit',$pack_unit_data);
                                    $pack_unit_id = $this->db->insert_id();
                                }
                            }
                            $alternate_unit_id = NULL;
                            if(!empty($row['Alternate_Unit'])){
                                $alternate_unit_id = $this->crud->get_column_value_by_id('pack_unit','pack_unit_id',array('pack_unit_name' => trim($row['Alternate_Unit'])));
                                if(empty($alternate_unit_id)) {
                                    $pack_unit_data = array();
                                    $pack_unit_data['pack_unit_name'] = $row['Alternate_Unit'];
                                    $pack_unit_data['created_at'] = $this->now_time;
                                    $pack_unit_data['created_by'] = $this->logged_in_id;
                                    $pack_unit_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('pack_unit',$pack_unit_data);
                                    $alternate_unit_id = $this->db->insert_id();
                                }
                            }
                            $hsn_code = NULL;
                            if(!empty($row['HSN/SAC'])){
                                $hsn_code = $this->crud->get_column_value_by_id('hsn','hsn_id',array('hsn' => trim($row['HSN/SAC'])));
                                if(empty($hsn_code)) {
                                    $hsn_data = array();
                                    $hsn_data['hsn'] = $row['HSN/SAC'];
                                    $hsn_data['created_at'] = $this->now_time;
                                    $hsn_data['created_by'] = $this->logged_in_id;
                                    $hsn_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('hsn',$hsn_data);
                                    $hsn_code = $this->db->insert_id();
                                }
                            }
                            $item_id = $this->crud->get_column_value_by_id('item','item_id',array('item_name' => trim($row['Item_Name']), 'created_by' => $this->logged_in_id));
                            if(!empty($item_id)) {
                                    $item_data = array();
                                    $item_data['item_type_id'] = $item_type_id;
                                    $item_data['item_group_id'] = $item_group_id;
                                    $item_data['sub_category_id'] = $item_sub_category_id;
                                    $item_data['category_id'] = $item_category_id;
                                    $item_data['pack_unit_id'] = $pack_unit_id;
                                    $item_data['alternate_unit_id'] = $alternate_unit_id;
                                    $item_data['hsn_code'] = $hsn_code;
                                    $item_data['list_price'] = $row['List_Price'] ? $row['List_Price'] : NULL;
                                    $item_data['mrp'] = $row['MRP'] ? $row['MRP'] : NULL;
                                    $item_data['opening_qty'] = $row['Opening_Qty'] ? $row['Opening_Qty'] : NULL;
                                    $item_data['opening_amount'] = $row['Opening_Amount'] ? $row['Opening_Amount'] : NULL;
                                    $item_data['igst_per'] = $row['IGST(%)'] ? $row['IGST(%)'] : NULL;
                                    $item_data['cgst_per'] = $row['CGST(%)'] ? $row['CGST(%)'] : NULL;
                                    $item_data['sgst_per'] = $row['SGST(%)'] ? $row['SGST(%)'] : NULL;
                                    $item_data['cess'] = $row['Cess'] ? $row['Cess'] : NULL;
                                    $item_data['item_desc'] = $row['Description'] ? $row['Description'] : NULL;
                                    $item_data['purchase_rate'] = $row['Purchase_Rate'] == 'Including_Tax' ? 1 : 2;
                                    $item_data['sales_rate'] = $row['Sales_Rate'] == 'Including_Tax' ? 1 : 2;
                                    $item_data['purchase_rate_val'] = $row['Purchase_Rate_Value'] ? $row['Purchase_Rate_Value'] : NULL;
                                    $item_data['sales_rate_val'] = $row['Sales_Rate_Value'] ? $row['Sales_Rate_Value'] : NULL;
                                    $item_data['minimum'] = $row['Minimum'] ? $row['Minimum'] : NULL;
                                    $item_data['maximum'] = $row['Maximum'] ? $row['Maximum'] : NULL;
                                    $item_data['reorder_stock'] = $row['Reorder_Stock'] ? $row['Reorder_Stock'] : NULL;
                                    $item_data['discount_on'] = '1';
                                    $item_data['updated_at'] = $this->now_time;
                                    $item_data['updated_by'] = $this->logged_in_id;
                                    $item_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->update('item', $item_data, array('item_id' => $item_id));
                            } else {
                                    $item_data = array();
                                    $item_data['item_name'] = $row['Item_Name'];
                                    $item_data['item_type_id'] = $item_type_id;
                                    $item_data['item_group_id'] = $item_group_id;
                                    $item_data['category_id'] = $item_category_id;
                                    $item_data['sub_category_id'] = $item_sub_category_id;
                                    $item_data['pack_unit_id'] = $pack_unit_id;
                                    $item_data['alternate_unit_id'] = $alternate_unit_id;
                                    $item_data['hsn_code'] = $hsn_code;
                                    $item_data['list_price'] = $row['List_Price'] ? $row['List_Price'] : NULL;
                                    $item_data['mrp'] = $row['MRP'] ? $row['MRP'] : NULL;
                                    $item_data['opening_qty'] = $row['Opening_Qty'] ? $row['Opening_Qty'] : NULL;
                                    $item_data['opening_amount'] = $row['Opening_Amount'] ? $row['Opening_Amount'] : NULL;
                                    $item_data['igst_per'] = $row['IGST(%)'] ? $row['IGST(%)'] : NULL;
                                    $item_data['cgst_per'] = $row['CGST(%)'] ? $row['CGST(%)'] : NULL;
                                    $item_data['sgst_per'] = $row['SGST(%)'] ? $row['SGST(%)'] : NULL;
                                    $item_data['cess'] = $row['Cess'] ? $row['Cess'] : NULL;
                                    $item_data['item_desc'] = $row['Description'] ? $row['Description'] : NULL;
                                    $item_data['purchase_rate'] = $row['Purchase_Rate'] == 'Including_Tax' ? 1 : 2;
                                    $item_data['sales_rate'] = $row['Sales_Rate'] == 'Including_Tax' ? 1 : 2;
                                    $item_data['purchase_rate_val'] = $row['Purchase_Rate_Value'] ? $row['Purchase_Rate_Value'] : NULL;
                                    $item_data['sales_rate_val'] = $row['Sales_Rate_Value'] ? $row['Sales_Rate_Value'] : NULL;
                                    $item_data['minimum'] = $row['Minimum'] ? $row['Minimum'] : NULL;
                                    $item_data['maximum'] = $row['Maximum'] ? $row['Maximum'] : NULL;
                                    $item_data['reorder_stock'] = $row['Reorder_Stock'] ? $row['Reorder_Stock'] : NULL;
                                    $item_data['discount_on'] = '1';
                                    $item_data['created_at'] = $this->now_time;
                                    $item_data['created_by'] = $this->logged_in_id;
                                    $item_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $result = $this->crud->insert('item',$item_data);
                            }
                        }
                    }
                }
                if($radio_type == 6) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                    $objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    if(!empty($allDataInSheet)){
                        unset($allDataInSheet[1]);
                        unset($allDataInSheet[2]);
                        unset($allDataInSheet[3]);
                        $acc_name = substr($allDataInSheet[4]['A'], strpos($allDataInSheet[4]['A'], 'For') + 4);
                        $acc_id = $this->crud->getFromSQL("SELECT account_id FROM account WHERE account_name = '".$acc_name."' AND created_by = ".$this->logged_in_id." ");
                        if(empty($acc_id)){
                            $this->session->set_flashdata('success', false);
                            $this->session->set_flashdata('message', '<b>'.$acc_name.'</b> : This Account not Exists !');
                            redirect('master/import');
                        } else {
                            $acc_id = $acc_id[0]->account_id;
                        }
                        unset($allDataInSheet[4]);
                        unset($allDataInSheet[5]);
                        unset($allDataInSheet[6]);

                        foreach ($allDataInSheet as $sheet_entry){
                            if($sheet_entry['B'] == 'DB Closing Balance' || $sheet_entry['D'] == 'DB Closing Balance'){
                                break;
                            } else {
                                if(!empty($sheet_entry['A'])){
                                    $rec_dat = str_replace('/', '-', substr($sheet_entry['B'], 0, 10));
                                    $rec_date = date('Y-m-d', strtotime($rec_dat));
                                    $opposite_data = substr($sheet_entry['B'], 11);
                                    $exd = explode(' ', $opposite_data);
                                    $opposite_acc = $exd[0];
                                    $note = substr($opposite_data, strpos($opposite_data, $opposite_acc));
                                    $op_acc_id = CASH_ACCOUNT_ID;
                                    $entry_arr = array();
                                    $entry_arr['transaction_type'] = '2';
                                    $entry_arr['transaction_date'] = $rec_date;
                                    $entry_arr['amount'] = $sheet_entry['A'];
                                    $entry_arr['to_account_id'] = $op_acc_id;
                                    $entry_arr['from_account_id'] = $acc_id;
                                    $entry_arr['note'] = 'Import from Excel : '.$note;
                                    $entry_arr['created_at'] = $this->now_time;
                                    $entry_arr['created_by'] = $this->logged_in_id;
                                    $entry_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $this->crud->insert('transaction_entry', $entry_arr);
                                }
                                if(!empty($sheet_entry['C'])){
                                    $pay_dat = str_replace('/', '-', substr($sheet_entry['D'], 0, 10));
                                    $pay_date = date('Y-m-d', strtotime($pay_dat));
                                    $opposite_data = substr($sheet_entry['D'], 11);
                                    $exd = explode(' ', $opposite_data);
                                    $opposite_acc = $exd[0];
                                    $note = substr($opposite_data, strpos($opposite_data, $opposite_acc));
                                    $op_acc_id = CASH_ACCOUNT_ID;
                                    $entry_arr = array();
                                    $entry_arr['transaction_type'] = '1';
                                    $entry_arr['transaction_date'] = $pay_date;
                                    $entry_arr['amount'] = $sheet_entry['C'];
                                    $entry_arr['to_account_id'] = $acc_id;
                                    $entry_arr['from_account_id'] = $op_acc_id;
                                    $entry_arr['note'] = 'Import from Excel : '.$note;
                                    $entry_arr['created_at'] = $this->now_time;
                                    $entry_arr['created_by'] = $this->logged_in_id;
                                    $entry_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                    $this->crud->insert('transaction_entry', $entry_arr);
                                }
                            }
                        }
                    }
                }
                if($radio_type == 7) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                    // Cash account id = CASH_ACCOUNT_ID 227  //
                    $objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    $import_client_ledger_data = array();
                    if(!empty($allDataInSheet)){
                        unset($allDataInSheet[1]);
                        unset($allDataInSheet[2]);
                        unset($allDataInSheet[3]);
                        $acc_name = substr($allDataInSheet[4]['A'], strpos($allDataInSheet[4]['A'], 'For') + 4);

                        $acc_id = $this->crud->getFromSQL("SELECT account_id FROM account WHERE account_name = '".$acc_name."' AND created_by = ".$this->logged_in_id." ");
                        if(empty($acc_id)){
                            $this->session->set_flashdata('success', false);
                            $this->session->set_flashdata('message', '<b>'.$acc_name.'</b> : This Account not Exists !');
                            redirect('master/import');
                        } else {
                            $acc_id = $acc_id[0]->account_id;
                            unset($allDataInSheet[4]);
                            unset($allDataInSheet[5]);
                            unset($allDataInSheet[6]);

                            if(!empty($allDataInSheet)){
                                foreach ($allDataInSheet as $sheet_entry){
                                    if($sheet_entry['B'] == 'DB Closing Balance' || $sheet_entry['D'] == 'DB Closing Balance'){
                                        break;
                                    } else {
                                        if(!empty($sheet_entry['A']) && !empty($sheet_entry['B'])){
                                            $rec_dat = str_replace('/', '-', substr($sheet_entry['B'], 0, 10));
                                            $rec_date = date('Y-m-d', strtotime($rec_dat));
                                            $opposite_data = substr($sheet_entry['B'], 11);
                                            $exd = explode(' ', $opposite_data);
                                            $opposite_acc = $exd[0];
                                            $note = substr($opposite_data, strpos($opposite_data, $opposite_acc));

                                            if($opposite_acc == 'CRct' || $opposite_acc == 'Jrnl' || $opposite_acc == 'SRet'){ } else {
                                                if(!empty($sheet_entry['B'])){
                                                    $import_client_ledger_data[] = array('0' => 'Credit', '1' => $sheet_entry['A'], '2' => $sheet_entry['B']);
                                                }
                                            }
                                            if($opposite_acc == 'CRct' || $opposite_acc == 'Jrnl'){
                                                $op_acc_id = CASH_ACCOUNT_ID;
                                                $entry_arr = array();
                                                $entry_arr['transaction_type'] = '2';
                                                $entry_arr['transaction_date'] = $rec_date;
                                                $entry_arr['amount'] = $sheet_entry['A'];
                                                $entry_arr['to_account_id'] = $op_acc_id;
                                                $entry_arr['from_account_id'] = $acc_id;
                                                $tr_note = 'Import from Excel : '.$note;
                                                $entry_arr['note'] = $tr_note;
                                                $entry_arr['created_at'] = $this->now_time;
                                                $entry_arr['created_by'] = $this->logged_in_id;
                                                $entry_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                                $this->crud->insert('transaction_entry', $entry_arr);
                                            }
                                            if($opposite_acc == 'SRet'){
                                                $credit_note_no = $this->crud->get_max_number('credit_note', 'credit_note_no', $this->logged_in_id);
                                                if(!empty($credit_note_no->credit_note_no)){
                                                    $new_credit_note_no = $credit_note_no->credit_note_no + 1;
                                                } else {
                                                    $new_credit_note_no = 1;
                                                }
                                                $entry_arr = array();
                                                $entry_arr['credit_note_no'] = $new_credit_note_no;
                                                $entry_arr['bill_no'] = $new_credit_note_no;
                                                $entry_arr['invoice_date'] = $rec_date;
                                                $entry_arr['account_id'] = $acc_id;
                                                $entry_arr['against_account_id'] = '152';
                                                $entry_arr['credit_note_date'] = $rec_date;
                                                $entry_arr['qty_total'] = '1';
                                                $entry_arr['pure_amount_total'] = $sheet_entry['A'];
                                                $entry_arr['amount_total'] = $sheet_entry['A'];
                                                $entry_arr['credit_note_desc'] = 'Import from Excel : '.$note;
                                                $entry_arr['created_at'] = $this->now_time;
                                                $entry_arr['created_by'] = $this->logged_in_id;
                                                $entry_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                                $this->crud->insert('credit_note', $entry_arr);
                                            }
                                        }
                                        
                                        if(!empty($sheet_entry['C']) && !empty($sheet_entry['D'])){
                                            $pay_dat = str_replace('/', '-', substr($sheet_entry['D'], 0, 10));
                                            $rec_date = date('Y-m-d', strtotime($pay_dat));
                                            $opposite_data = substr($sheet_entry['D'], 11);
                                            $exd = explode(' ', $opposite_data);
                                            $opposite_acc = $exd[0];
                                            $note = substr($opposite_data, strpos($opposite_data, $opposite_acc));
                                            if($opposite_acc == 'CPmt' || $opposite_acc == 'Jrnl' || $opposite_acc == 'Sale'){ } else {
                                                if(!empty($sheet_entry['D'])){
                                                    $import_client_ledger_data[] = array('0' => 'Debit', '1' => $sheet_entry['C'], '2' => $sheet_entry['D']);
                                                }
                                            }
                                            if($opposite_acc == 'CPmt' || $opposite_acc == 'Jrnl'){
                                                $op_acc_id = CASH_ACCOUNT_ID;
                                                $entry_arr = array();
                                                $entry_arr['transaction_type'] = '1';
                                                $entry_arr['transaction_date'] = $rec_date;
                                                $entry_arr['amount'] = $sheet_entry['C'];
                                                $entry_arr['to_account_id'] = $acc_id;
                                                $entry_arr['from_account_id'] = $op_acc_id;
                                                $tr_note = 'Import from Excel : '.$note;
                                                $entry_arr['note'] = $tr_note;
                                                $entry_arr['created_at'] = $this->now_time;
                                                $entry_arr['created_by'] = $this->logged_in_id;
                                                $entry_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                                $this->crud->insert('transaction_entry', $entry_arr);
                                            }
                                            if($opposite_acc == 'Sale'){
                                                $sales_invoice_no = $this->crud->get_max_number_where('sales_invoice', 'sales_invoice_no', array('created_by' => $this->logged_in_id));
                                                if(empty($sales_invoice_no->sales_invoice_no)){
                                                    $new_sales_invoice_no = 1;
                                                } else {
                                                    $new_sales_invoice_no = $sales_invoice_no->sales_invoice_no + 1;    
                                                }
                                                $entry_arr = array();
                                                $entry_arr['sales_invoice_no'] = $new_sales_invoice_no;
                                                $entry_arr['account_id'] = $acc_id;
                                                $entry_arr['against_account_id'] = '152';
                                                $entry_arr['sales_invoice_date'] = $rec_date;
                                                $entry_arr['sales_invoice_desc'] = 'Import from Excel : '.$note;
                                                $entry_arr['qty_total'] = '1';
                                                $entry_arr['pure_amount_total'] = $sheet_entry['C'];
                                                $entry_arr['amount_total'] = $sheet_entry['C'];
                                                $entry_arr['created_at'] = $this->now_time;
                                                $entry_arr['created_by'] = $this->logged_in_id;
                                                $entry_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                                $this->crud->insert('sales_invoice', $entry_arr);
                                            }
                                        }
                                    }
                                }
                                $data['import_client_ledger_data'] = $import_client_ledger_data;
                            }
                        }
                        
                    }
                }
                if($radio_type == 8) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                	$objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    unset($allDataInSheet[1]);
                    unset($allDataInSheet[2]);
                    unset($allDataInSheet[3]);
                    unset($allDataInSheet[4]);
                    unset($allDataInSheet[5]);
                    unset($allDataInSheet[6]);
                    unset($allDataInSheet[7]);
                    $import_acc_data = array();

                    $sales_invoice_res = array();
                    $sales_invoice_no = 0;
                    foreach ($allDataInSheet as $key => $excel_row) {
                    	if(empty($excel_row['I'])) { //Product
                    		continue;
                    	}

                    	$excel_row = array_map('trim', $excel_row);

                    	if(!empty($excel_row['B'])) {
                    		$sales_invoice_no = $excel_row['B'];	
                    	}

                    	if(!empty($sales_invoice_no)) {
                    		if(isset($sales_invoice_res[$sales_invoice_no]['lineitem'])) {
                    			$sales_invoice_res[$sales_invoice_no]['lineitem'][] = $excel_row;
                    		} else {
                    			$excel_row['K'] = str_replace(array(' ','%','GST'),'',$excel_row['K']);
                    			$sales_invoice_res[$sales_invoice_no] = $excel_row;
                    			$sales_invoice_res[$sales_invoice_no]['lineitem'][] = $excel_row;
                    		}
                    	}                	
                    }
                    /*echo "<pre>";
                    print_r($sales_invoice_res);
                    exit();*/
                    $import_sales_data = array();
                    foreach ($sales_invoice_res as $sales_invoice_no => $excel_row) {
                    	if(empty($excel_row['I'])) { //Product
                    		continue;
                    	}                    	
                    	/*echo "<pre>";
                    	print_r($excel_row);
                    	exit();*/

                    	$sales_invoice_row = $this->crud->get_data_row_by_where('sales_invoice',array('sales_invoice_no' => $sales_invoice_no, 'created_by' => $this->logged_in_id));

                    	if(!empty($sales_invoice_row)) {
                    		$import_sales_data[] = array(
                    			'invoice_no' => $excel_row['B'],
                    			'message' => '<span class="text-red">Invoice Already Added</span>',
                    		);
                    		continue;
                    	}
                    	
                    	$account_row = $this->crud->get_data_row_by_where('account',array('account_name' => $excel_row['D'], 'created_by' => $this->logged_in_id));
                    	if(!empty($account_row)) {
                    		$account_id = $account_row->account_id;
                    	} else {
                    		if($excel_row['C'] == "Cash") {
	                    		$account_group_id = CASH_IN_HAND_ACC_GROUP_ID;
	                    	} else {
	                    		$account_group_id = SUNDRY_DEBTORS_ACC_GROUP_ID;
	                    	}

                    		if(empty($excel_row['F'])) {
                    			$excel_row['F'] = 'Gujarat';	
                    		}                    		
                    		$account_state = $this->crud->get_data_row_by_where('state',array('state_name' => $excel_row['F']));

                    		if(!empty($account_state)) {
                    			$account_state = $account_state->state_id;
                    		} else {
                    			$state_data = array(
                    				'state_name' => $excel_row['F'],
                    				'is_deleted' => 0,
                    				'created_by' => $this->logged_in_id,
                    				'user_created_by' => $this->session->userdata()['login_user_id'],
                    				'created_at' => $this->now_time,
                    			);
                    			$account_state = $this->crud->insert('state',$state_data);
                    		}

                    		$account_city = $this->crud->get_data_row_by_where('city',array('city_name' => $excel_row['E'],'state_id' => $account_state));
                    		if(!empty($account_city)) {
                    			$account_city = $account_city->city_id;
                    		} else {
                    			$city_data = array(
                    				'state_id' => $account_state,
                    				'city_name' => $excel_row['E'],
                    				'is_deleted' => 0,
                    				'created_by' => $this->logged_in_id,
                    				'user_created_by' => $this->session->userdata()['login_user_id'],
                    				'created_at' => $this->now_time,
                    			);
                    			$account_city = $this->crud->insert('city',$city_data);
                    		}

                    		$account_data = array(
                    			'account_name' => $excel_row['D'],
                    			'account_group_id' => $account_group_id,
                    			'account_state' => $account_state,
                    			'account_city' => $account_city,
                    			'account_gst_no' => $excel_row['S'],
                    			'created_by' => $this->logged_in_id,
                    			'user_created_by' => $this->session->userdata()['login_user_id'],
                    			'created_at' => $this->now_time,
                    		);
                    		$account_id = $this->crud->insert('account',$account_data);
                    	}

                    	$qty_total = 0;
                    	$pure_amount_total = 0;
                    	$gst_per = $excel_row['K'];
                    	$cgst_amount_total = 0;
                		$sgst_amount_total = 0;
                		$igst_amount_total = 0;
                    	$amount_total = $excel_row['R'];

                		if($excel_row['P'] > 0) {
                    		$igst = $gst_per;
                    		$cgst = 0;
                    		$sgst = 0;
                    	} else {
                    		$igst = 0;
                    		$cgst = ($gst_per / 2);
                    		$sgst = ($gst_per / 2);
                    	}

                    	$lineitem_data = array();
                    	foreach ($excel_row['lineitem'] as $key => $lineitem_row) {
                    		$item_data = $this->crud->get_data_row_by_where('item',array('item_name' => $lineitem_row['I']));
                    		if(!empty($item_data)) {
                    			$item_id = $item_data->item_id;
                    			$cat_id = $item_data->category_id;
                    			$sub_cat_id = $item_data->sub_category_id;
                    			$item_group_id = $item_data->item_group_id;
                    		} else {
                    			$hsn_code = $this->crud->get_data_row_by_where('hsn',array('hsn' => $lineitem_row['J']));
                    			if(!empty($hsn_code)) {
                    				$hsn_code = $hsn_code->hsn_id;
                    			} else {
                    				$hsn_code = $this->crud->insert('hsn',array('hsn' => $lineitem_row['J'],'created_by' => $this->logged_in_id,'created_at' => $this->now_time));
                    			}

                    			$item_data = array(
                    				'item_name' => $lineitem_row['I'],
                    				'hsn_code' => $hsn_code,
                    				'current_stock_qty' => 0,
                    				'created_by' => $this->logged_in_id,
                    				'user_created_by' => $this->session->userdata()['login_user_id'],
                    				'created_at' => $this->now_time,
                    			);
                    			$item_id = $this->crud->insert('item',$item_data);
                    			$cat_id = null;
                    			$sub_cat_id = null;
                    			$item_group_id = null;
                    		}

                    		$cgst_amount = 0;
                    		if($cgst > 0) {
                    			$cgst_amount = (($lineitem_row['M'] * $cgst) / 100);	
                    			$cgst_amount = round($cgst_amount);
                    		}

                    		$sgst_amount = 0;
                    		if($sgst > 0) {
                    			$sgst_amount = (($lineitem_row['M'] * $sgst) / 100);	
                    			$sgst_amount = round($sgst_amount);
                    		}

                    		$igst_amount = 0;
                    		if($igst > 0) {
                    			$igst_amount = (($lineitem_row['M'] * $igst) / 100);
                    			$igst_amount = round($igst_amount);
                    		}
                    		
                    		$amount = $lineitem_row['M'] + $cgst_amount + $sgst_amount + $igst_amount;
                    		if($lineitem_row['M'] > 0 && $lineitem_row['L'] > 0) {
                    			$price = ($lineitem_row['M'] / $lineitem_row['L']);
                    		} else {
                    			$price = 0;
                    		}                    		
                    		$price = round($price,2);

                    		$lineitem_data[] = array(
                    			'cat_id' => $cat_id,
                    			'sub_cat_id' => $sub_cat_id,
                    			'item_id' => $item_id,
                    			'item_group_id' => $item_group_id,
                    			'item_qty' => $lineitem_row['L'],
                    			'pure_amount' => $lineitem_row['M'],
                    			'unit_id' => KG_PACK_UNIT_ID,
                    			'price' => $price,
                    			'discount' => 0,
                    			'discounted_price' => $lineitem_row['M'],
                    			'cgst' => $cgst,
                    			'cgst_amount' => $cgst_amount,
                    			'sgst' => $sgst,
                    			'sgst_amount' => $sgst_amount,
                    			'igst' => $igst,
                    			'igst_amount' => $igst_amount,
                    			'other_charges' => 0,
                    			'amount' => $amount,
                    		);
                    		
                    		$qty_total += $lineitem_row['L'];
                    		$pure_amount_total += $lineitem_row['M'];
                    		$cgst_amount_total += $cgst_amount;
                    		$sgst_amount_total += $sgst_amount;
                    		$igst_amount_total += $igst_amount;
                    	}
						
						$round_off_amount = $amount_total - ($pure_amount_total + $cgst_amount_total + $sgst_amount_total + $igst_amount_total);
						$round_off_amount = round($round_off_amount,2);

						$invoice_type_row = $this->crud->get_data_row_by_where('invoice_type',array('invoice_type' => $excel_row['H']));
                    	if(!empty($invoice_type_row)) {
                    		$invoice_type = $invoice_type_row->invoice_type_id;
                    	} else {
                    		$invoice_type = $this->crud->insert('invoice_type',array('invoice_type' => $excel_row['H'],'created_by' => $this->logged_in_id,'created_at' => $this->now_time));
                    	}

                    	$sales_invoice_date = str_replace('/', '-',$excel_row['A']);
                    	$sales_invoice_date = date('Y-m-d', strtotime($sales_invoice_date));

                    	$invoice_data = array(
                    		'sales_invoice_no' => $excel_row['B'],
                    		'account_id' => $account_id,
                    		'sales_invoice_date' => $sales_invoice_date,
                    		'tax_type' => ($excel_row['G'] == "GST"?1:2),
                    		'invoice_type' => $invoice_type,
                    		'sales_invoice_desc' => 'Miracle Sales Data',
                    		'qty_total' => $qty_total,
                    		'pure_amount_total' => $pure_amount_total,
                    		'discount_total' => 0,
                    		'cgst_amount_total' => $cgst_amount_total,
                    		'sgst_amount_total' => $sgst_amount_total,
                    		'igst_amount_total' => $igst_amount_total,
                    		'other_charges_total' => 0,
                    		'round_off_amount' => $round_off_amount,
                    		'amount_total' => $amount_total,
                    		'created_by' => $this->logged_in_id,
                    		'user_created_by' => $this->session->userdata()['login_user_id'],
                    		'created_at' => $this->now_time,
                    	);
                    	$parent_id = $this->crud->insert('sales_invoice', $invoice_data);

                    	foreach($lineitem_data as $lineitem) {
			                $lineitem['module'] = 2;
			                $lineitem['parent_id'] = $parent_id;
			                $lineitem['created_at'] = $this->now_time;
			                $lineitem['updated_at'] = $this->now_time;
			                $lineitem['updated_by'] = $this->logged_in_id;
			                $lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
			                $lineitem['created_by'] = $this->logged_in_id;
			                $lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
			                $this->crud->insert('lineitems',$lineitem);

			                $this->crud->update_item_current_stock_qty($lineitem['item_id'],$parent_id,'sales',1,'add');
			            }

                    	$voucher_id = $parent_id;
            			$operation = 'add';
				        $other_params = array();
				        $other_params['invoice_date'] = date('Y-m-d', strtotime($invoice_data['sales_invoice_date']));
				        $this->crud->kasar_entry($invoice_data['account_id'],$voucher_id,'sales',$invoice_data['round_off_amount'],$operation,$other_params);

                    	/*$import_sales_data[] = array(
                			'invoice_no' => $excel_row['B'],
                			'message' => '<span class="text-green">Sales Invoice Inserted</span>',
                		);*/
                    }
                    $data['import_sales_data'] = $import_sales_data;
                }
                if($radio_type == 9) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                    $objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    unset($allDataInSheet[1]);
                    $import_acc_data = array();
                    if(!empty($allDataInSheet)){
                        foreach ($allDataInSheet as $item){
                            $item_arr = array();
                            $item_arr['item_name'] = $item['A'];
                            $item_arr['igst_per'] = $item['B'];
                            $item_arr['cgst_per'] = $item['C'];
                            $item_arr['sgst_per'] = $item['D'];
                            $item_arr['created_at'] = $this->now_time;
                            $item_arr['updated_at'] = $this->now_time;
                            $item_arr['updated_by'] = $this->logged_in_id;
                            $item_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('item',$item_arr);
                        }
                    }
//                    echo "<pre>"; print_r($allDataInSheet); exit;
                }
                if($radio_type == 10) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                	$objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    unset($allDataInSheet[1]);
                    unset($allDataInSheet[2]);
                    unset($allDataInSheet[3]);
                    unset($allDataInSheet[4]);
                    unset($allDataInSheet[5]);
                    unset($allDataInSheet[6]);
                    unset($allDataInSheet[7]);
                    $import_acc_data = array();

                    $purchase_invoice_res = array();
                    $purchase_invoice_no = 0;
                    foreach ($allDataInSheet as $key => $excel_row) {
                    	if(empty($excel_row['I'])) { //Product
                    		continue;
                    	}

                    	$excel_row = array_map('trim', $excel_row);

                    	if(!empty($excel_row['B'])) {
                    		$purchase_invoice_no = $excel_row['B'];	
                    	}

                    	if(!empty($purchase_invoice_no)) {
                    		if(isset($purchase_invoice_res[$purchase_invoice_no]['lineitem'])) {
                    			$purchase_invoice_res[$purchase_invoice_no]['lineitem'][] = $excel_row;
                    		} else {
                    			$excel_row['K'] = str_replace(array(' ','%','GST'),'',$excel_row['K']);
                    			$purchase_invoice_res[$purchase_invoice_no] = $excel_row;
                    			$purchase_invoice_res[$purchase_invoice_no]['lineitem'][] = $excel_row;
                    		}
                    	}                	
                    }
                    /*echo "<pre>";
                    print_r($purchase_invoice_res);
                    exit();*/
                    $import_purchase_data = array();
                    foreach ($purchase_invoice_res as $purchase_invoice_no => $excel_row) {
                    	if(empty($excel_row['I'])) { //Product
                    		continue;
                    	}                    	
                    	/*echo "<pre>";
                    	print_r($excel_row);
                    	exit();*/

                    	$purchase_invoice_row = $this->crud->get_data_row_by_where('purchase_invoice',array('purchase_invoice_no' => $purchase_invoice_no, 'created_by' => $this->logged_in_id));

                    	if(!empty($purchase_invoice_row)) {
                    		$import_purchase_data[] = array(
                    			'invoice_no' => $excel_row['B'],
                    			'message' => '<span class="text-red">Invoice Already Added</span>',
                    		);
                    		continue;
                    	}
                    	
                    	$account_row = $this->crud->get_data_row_by_where('account',array('account_name' => $excel_row['D'], 'created_by' => $this->logged_in_id));
                    	if(!empty($account_row)) {
                    		$account_id = $account_row->account_id;
                    	} else {
                    		if($excel_row['C'] == "Cash") {
	                    		$account_group_id = CASH_IN_HAND_ACC_GROUP_ID;
	                    	} else {
	                    		$account_group_id = SUNDRY_DEBTORS_ACC_GROUP_ID;
	                    	}

                    		if(empty($excel_row['F'])) {
                    			$excel_row['F'] = 'Gujarat';	
                    		}                    		
                    		$account_state = $this->crud->get_data_row_by_where('state',array('state_name' => $excel_row['F']));

                    		if(!empty($account_state)) {
                    			$account_state = $account_state->state_id;
                    		} else {
                    			$state_data = array(
                    				'state_name' => $excel_row['F'],
                    				'is_deleted' => 0,
                    				'created_by' => $this->logged_in_id,
                    				'user_created_by' => $this->session->userdata()['login_user_id'],
                    				'created_at' => $this->now_time,
                    			);
                    			$account_state = $this->crud->insert('state',$state_data);
                    		}

                    		$account_city = $this->crud->get_data_row_by_where('city',array('city_name' => $excel_row['E'],'state_id' => $account_state));
                    		if(!empty($account_city)) {
                    			$account_city = $account_city->city_id;
                    		} else {
                    			$city_data = array(
                    				'state_id' => $account_state,
                    				'city_name' => $excel_row['E'],
                    				'is_deleted' => 0,
                    				'created_by' => $this->logged_in_id,
                    				'user_created_by' => $this->session->userdata()['login_user_id'],
                    				'created_at' => $this->now_time,
                    			);
                    			$account_city = $this->crud->insert('city',$city_data);
                    		}

                    		$account_data = array(
                    			'account_name' => $excel_row['D'],
                    			'account_group_id' => $account_group_id,
                    			'account_state' => $account_state,
                    			'account_city' => $account_city,
                    			'account_gst_no' => $excel_row['S'],
                    			'created_by' => $this->logged_in_id,
                    			'user_created_by' => $this->session->userdata()['login_user_id'],
                    			'created_at' => $this->now_time,
                    		);
                    		$account_id = $this->crud->insert('account',$account_data);
                    	}

                    	$qty_total = 0;
                    	$pure_amount_total = 0;
                    	$gst_per = $excel_row['K'];
                    	$cgst_amount_total = 0;
                		$sgst_amount_total = 0;
                		$igst_amount_total = 0;
                    	$amount_total = $excel_row['R'];

                		if($excel_row['P'] > 0) {
                    		$igst = $gst_per;
                    		$cgst = 0;
                    		$sgst = 0;
                    	} else {
                    		$igst = 0;
                    		$cgst = ($gst_per / 2);
                    		$sgst = ($gst_per / 2);
                    	}

                    	$lineitem_data = array();
                    	foreach ($excel_row['lineitem'] as $key => $lineitem_row) {
                    		$item_data = $this->crud->get_data_row_by_where('item',array('item_name' => $lineitem_row['I']));
                    		if(!empty($item_data)) {
                    			$item_id = $item_data->item_id;
                    			$cat_id = $item_data->category_id;
                    			$sub_cat_id = $item_data->sub_category_id;
                    			$item_group_id = $item_data->item_group_id;
                    		} else {
                    			$hsn_code = $this->crud->get_data_row_by_where('hsn',array('hsn' => $lineitem_row['J']));
                    			if(!empty($hsn_code)) {
                    				$hsn_code = $hsn_code->hsn_id;
                    			} else {
                    				$hsn_code = $this->crud->insert('hsn',array('hsn' => $lineitem_row['J'],'created_by' => $this->logged_in_id,'created_at' => $this->now_time));
                    			}

                    			$item_data = array(
                    				'item_name' => $lineitem_row['I'],
                    				'hsn_code' => $hsn_code,
                    				'current_stock_qty' => 0,
                    				'created_by' => $this->logged_in_id,
                    				'user_created_by' => $this->session->userdata()['login_user_id'],
                    				'created_at' => $this->now_time,
                    			);
                    			$item_id = $this->crud->insert('item',$item_data);
                    			$cat_id = null;
                    			$sub_cat_id = null;
                    			$item_group_id = null;
                    		}

                    		$cgst_amount = 0;
                    		if($cgst > 0) {
                    			$cgst_amount = (($lineitem_row['M'] * $cgst) / 100);	
                    			$cgst_amount = round($cgst_amount);
                    		}

                    		$sgst_amount = 0;
                    		if($sgst > 0) {
                    			$sgst_amount = (($lineitem_row['M'] * $sgst) / 100);	
                    			$sgst_amount = round($sgst_amount);
                    		}

                    		$igst_amount = 0;
                    		if($igst > 0) {
                    			$igst_amount = (($lineitem_row['M'] * $igst) / 100);
                    			$igst_amount = round($igst_amount);
                    		}
                    		
                    		$amount = $lineitem_row['M'] + $cgst_amount + $sgst_amount + $igst_amount;
                    		if($lineitem_row['M'] > 0 && $lineitem_row['L'] > 0) {
                    			$price = ($lineitem_row['M'] / $lineitem_row['L']);
                    		} else {
                    			$price = 0;
                    		}                    		
                    		$price = round($price,2);

                    		$lineitem_data[] = array(
                    			'cat_id' => $cat_id,
                    			'sub_cat_id' => $sub_cat_id,
                    			'item_id' => $item_id,
                    			'item_group_id' => $item_group_id,
                    			'item_qty' => $lineitem_row['L'],
                    			'pure_amount' => $lineitem_row['M'],
                    			'unit_id' => KG_PACK_UNIT_ID,
                    			'price' => $price,
                    			'discount' => 0,
                    			'discounted_price' => $lineitem_row['M'],
                    			'cgst' => $cgst,
                    			'cgst_amount' => $cgst_amount,
                    			'sgst' => $sgst,
                    			'sgst_amount' => $sgst_amount,
                    			'igst' => $igst,
                    			'igst_amount' => $igst_amount,
                    			'other_charges' => 0,
                    			'amount' => $amount,
                    		);
                    		
                    		$qty_total += $lineitem_row['L'];
                    		$pure_amount_total += $lineitem_row['M'];
                    		$cgst_amount_total += $cgst_amount;
                    		$sgst_amount_total += $sgst_amount;
                    		$igst_amount_total += $igst_amount;
                    	}
						
						$round_off_amount = $amount_total - ($pure_amount_total + $cgst_amount_total + $sgst_amount_total + $igst_amount_total);
						$round_off_amount = round($round_off_amount,2);

						$invoice_type_row = $this->crud->get_data_row_by_where('invoice_type',array('invoice_type' => $excel_row['H']));
                    	if(!empty($invoice_type_row)) {
                    		$invoice_type = $invoice_type_row->invoice_type_id;
                    	} else {
                    		$invoice_type = $this->crud->insert('invoice_type',array('invoice_type' => $excel_row['H'],'created_by' => $this->logged_in_id,'created_at' => $this->now_time));
                    	}

                    	$purchase_invoice_date = str_replace('/', '-',$excel_row['A']);
                    	$purchase_invoice_date = date('Y-m-d', strtotime($purchase_invoice_date));

                    	$invoice_data = array(
                    		'purchase_invoice_no' => $excel_row['B'],
                    		'account_id' => $account_id,
                    		'purchase_invoice_date' => $purchase_invoice_date,
                    		'invoice_type' => $invoice_type,
                    		'purchase_invoice_desc' => 'Miracle Purchase Data',
                    		'qty_total' => $qty_total,
                    		'pure_amount_total' => $pure_amount_total,
                    		'discount_total' => 0,
                    		'cgst_amount_total' => $cgst_amount_total,
                    		'sgst_amount_total' => $sgst_amount_total,
                    		'igst_amount_total' => $igst_amount_total,
                    		'other_charges_total' => 0,
                    		'round_off_amount' => $round_off_amount,
                    		'amount_total' => $amount_total,
                    		'created_by' => $this->logged_in_id,
                    		'user_created_by' => $this->session->userdata()['login_user_id'],
                    		'created_at' => $this->now_time,
                    	);
                    	$parent_id = $this->crud->insert('purchase_invoice', $invoice_data);

                    	foreach($lineitem_data as $lineitem) {
			                $lineitem['module'] = 2;
			                $lineitem['parent_id'] = $parent_id;
			                $lineitem['created_at'] = $this->now_time;
			                $lineitem['updated_at'] = $this->now_time;
			                $lineitem['updated_by'] = $this->logged_in_id;
			                $lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
			                $lineitem['created_by'] = $this->logged_in_id;
			                $lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
			                $this->crud->insert('lineitems',$lineitem);

			                $this->crud->update_item_current_stock_qty($lineitem['item_id'],$parent_id,'purchase',1,'add');
			            }

                    	$voucher_id = $parent_id;
            			$operation = 'add';
				        $other_params = array();
				        $other_params['invoice_date'] = date('Y-m-d', strtotime($invoice_data['purchase_invoice_date']));
				        $this->crud->kasar_entry($invoice_data['account_id'],$voucher_id,'purchase',$invoice_data['round_off_amount'],$operation,$other_params);

                    	/*$import_purchase_data[] = array(
                			'invoice_no' => $excel_row['B'],
                			'message' => '<span class="text-green">Purchase Invoice Inserted!</span>',
                		);*/
                    }
                    $data['import_purchase_data'] = $import_purchase_data;
                }
				if($radio_type == 11) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                    // Cash account id = CASH_ACCOUNT_ID 7  //
                    $objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					// echo '<pre>';
					// print_r($allDataInSheet);
					// die();
                    $import_client_ledger_data = array();
                    if(!empty($allDataInSheet)){
                        unset($allDataInSheet[1]);
                        unset($allDataInSheet[2]);
                        unset($allDataInSheet[3]);
						unset($allDataInSheet[4]);
						unset($allDataInSheet[5]);
						unset($allDataInSheet[6]);
						unset($allDataInSheet[7]);
						unset($allDataInSheet[8]);
						unset($allDataInSheet[9]);

						if (!empty($allDataInSheet)) {
							foreach ($allDataInSheet as $sheet_entry) {
								if (empty($sheet_entry['F'])) {
									break;
								} else {
									if (!empty($sheet_entry['A']) && !empty($sheet_entry['B'])) {
										$rec_dat = str_replace('/', '-', substr($sheet_entry['A'], 0, 10));
										$rec_date = date('Y-m-d', strtotime($rec_dat));

										$opposite_data = trim($sheet_entry['B'], " ");
										$opposite_acc = $opposite_data;
										$note = "This is note";
										$account_row = $this->crud->get_data_row_by_where('account', array('account_name' => $opposite_acc));
										if (!empty($account_row)) {
											$account_id = $account_row->account_id;
										}
										else
										{
											$account_data['account_name'] = $opposite_acc;
											$account_data['account_group_id'] = SUNDRY_DEBTORS_ACC_GROUP_ID;
											$account_data['opening_balance'] = 0;
											$account_data['credit_debit'] = "1";
											$account_data['account_city'] = 2;
											$account_data['account_postal_code'] = "";
											$account_data['account_state'] = 7;
											$account_data['account_pan'] = "";
											$account_data['account_aadhaar'] = "";
											$account_data['account_gst_no'] = "";
											$account_data['account_contect_person_name'] = "";
											$account_data['account_address'] = "";
											$account_data['account_mobile_numbers'] = "";
											$account_data['account_phone'] = "";
											$account_data['account_email_ids'] = "";
											$account_data['consider_in_pl'] = 1;
											$account_data['created_by'] = $this->logged_in_id;
											$account_data['user_created_by'] = $this->session->userdata()['login_user_id'];
											$account_data['created_at'] = $this->now_time;
											
											$account_id = $this->crud->insert('account',$account_data);
										}
										
										$credit_value = trim($sheet_entry['D'], " ");
										$debit_value  = trim($sheet_entry['E'], " ");
										if (empty($credit_value) && !empty($debit_value)) {
											$transaction_entry_data['transaction_type'] = 2; // 1 = Payment, 2 = Receipt
											$transaction_entry_data['transaction_date'] = $rec_date;
											$transaction_entry_data['from_account_id']  = $account_id;
											$transaction_entry_data['to_account_id']    = CASH_ACCOUNT_ID;
											$transaction_entry_data['receipt_no']       = "";//there is No column for this in excel
											$transaction_entry_data['amount']           = $debit_value;
											$transaction_entry_data['created_at'] = $this->now_time;
											$transaction_entry_data['created_by'] = $this->logged_in_id;
											$transaction_entry_data['user_created_by'] = $this->session->userdata()['login_user_id'];
											$result = $this->crud->insert('transaction_entry', $transaction_entry_data);
											$last_tr_id = $this->db->insert_id();
										} else if (!empty($credit_value) && empty($debit_value)) {
											$transaction_entry_data['transaction_type'] = 1; // 1 = Payment, 2 = Receipt
											$transaction_entry_data['transaction_date'] = $rec_date;
											$transaction_entry_data['from_account_id']  = CASH_ACCOUNT_ID;
											$transaction_entry_data['to_account_id']    = $account_id;
											$transaction_entry_data['receipt_no']       = "";//there is No column for this in excel
											$transaction_entry_data['amount']           = $credit_value;
											$transaction_entry_data['created_at'] = $this->now_time;
											$transaction_entry_data['created_by'] = $this->logged_in_id;
											$transaction_entry_data['user_created_by'] = $this->session->userdata()['login_user_id'];
											$result = $this->crud->insert('transaction_entry', $transaction_entry_data);
											$last_tr_id = $this->db->insert_id();
										}
									}
								}
							}
							$data['import_client_ledger_data'] = $import_client_ledger_data;
						}
					}
				}
				if($radio_type == 12) {
                    require_once('application/third_party/PHPExcel/PHPExcel.php');
                    // Cash account id = CASH_ACCOUNT_ID 7  //
                    $objPHPExcel = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					// echo '<pre>';
					// print_r($allDataInSheet);
					// die();
                    $import_client_ledger_data = array();
                    if(!empty($allDataInSheet)){
                        unset($allDataInSheet[1]);
                        unset($allDataInSheet[2]);
                        unset($allDataInSheet[3]);
						unset($allDataInSheet[4]);
						unset($allDataInSheet[5]);

                        //For Bank Name : Start
						$bank_acc_name = substr($allDataInSheet[6]['A'], strpos($allDataInSheet[6]['A'], 'For') + 4);
						$bank_account_row = $this->crud->get_data_row_by_where('account', array('account_name' => $bank_acc_name));
						if (!empty($bank_account_row)) {
							$bank_account_id = $bank_account_row->account_id;
						}
						else
						{
							$bank_account_data['account_name'] = $bank_acc_name;
							$bank_account_data['account_group_id'] = BANK_ACC_GROUP_ID;
							$bank_account_data['opening_balance'] = 0;
							$bank_account_data['credit_debit'] = "1";
							$bank_account_data['account_city'] = 2;
							$bank_account_data['account_postal_code'] = "";
							$bank_account_data['account_state'] = 7;
							$bank_account_data['account_pan'] = "";
							$bank_account_data['account_aadhaar'] = "";
							$bank_account_data['account_gst_no'] = "";
							$bank_account_data['account_contect_person_name'] = "";
							$bank_account_data['account_address'] = "";
							$bank_account_data['account_mobile_numbers'] = "";
							$bank_account_data['account_phone'] = "";
							$bank_account_data['account_email_ids'] = "";
							$bank_account_data['consider_in_pl'] = 1;
							$bank_account_data['created_by'] = $this->logged_in_id;
							$bank_account_data['user_created_by'] = $this->session->userdata()['login_user_id'];
							$bank_account_data['created_at'] = $this->now_time;
							
							$bank_account_id = $this->crud->insert('account',$bank_account_data);
						}
                        //For Bank Name : End

						unset($allDataInSheet[6]);
						unset($allDataInSheet[7]);
						unset($allDataInSheet[8]);
						unset($allDataInSheet[9]);

						if (!empty($allDataInSheet)) {
							foreach ($allDataInSheet as $sheet_entry) {
								if (strpos($sheet_entry['A'], 'Total') > 0) {
									break;
								} else {
									if (!empty($sheet_entry['A'])) {
										$rec_dat = str_replace('/', '-', substr($sheet_entry['A'], 0, 10));
										$rec_date = date('Y-m-d', strtotime($rec_dat));

										$opposite_data = trim($sheet_entry['B'], " ");
										$opposite_acc = $opposite_data;
										$note = "This is note";
										$account_row = $this->crud->get_data_row_by_where('account', array('account_name' => $opposite_acc));
										if (!empty($account_row)) {
											$account_id = $account_row->account_id;
										}
										else
										{
											$account_data['account_name'] = $opposite_acc;
											$account_data['account_group_id'] = SUNDRY_DEBTORS_ACC_GROUP_ID;
											$account_data['opening_balance'] = 0;
											$account_data['credit_debit'] = "1";
											$account_data['account_city'] = 2;
											$account_data['account_postal_code'] = "";
											$account_data['account_state'] = 7;
											$account_data['account_pan'] = "";
											$account_data['account_aadhaar'] = "";
											$account_data['account_gst_no'] = "";
											$account_data['account_contect_person_name'] = "";
											$account_data['account_address'] = "";
											$account_data['account_mobile_numbers'] = "";
											$account_data['account_phone'] = "";
											$account_data['account_email_ids'] = "";
											$account_data['consider_in_pl'] = 1;
											$account_data['created_by'] = $this->logged_in_id;
											$account_data['user_created_by'] = $this->session->userdata()['login_user_id'];
											$account_data['created_at'] = $this->now_time;
											
											$account_id = $this->crud->insert('account',$account_data);
										}
										
										$credit_value = trim($sheet_entry['D'], " ");
										$debit_value  = trim($sheet_entry['E'], " ");
										if (empty($credit_value) && !empty($debit_value)) {
											$transaction_entry_data['transaction_type'] = 2; // 1 = Payment, 2 = Receipt
											$transaction_entry_data['transaction_date'] = $rec_date;
											$transaction_entry_data['from_account_id']  = $account_id;
											$transaction_entry_data['to_account_id']    = $bank_account_id;
											$transaction_entry_data['receipt_no']       = "";//there is No column for this in excel
											$transaction_entry_data['amount']           = $debit_value;
											$transaction_entry_data['created_at'] = $this->now_time;
											$transaction_entry_data['created_by'] = $this->logged_in_id;
											$transaction_entry_data['user_created_by'] = $this->session->userdata()['login_user_id'];
											$result = $this->crud->insert('transaction_entry', $transaction_entry_data);
											$last_tr_id = $this->db->insert_id();
										} else if (!empty($credit_value) && empty($debit_value)) {
											$transaction_entry_data['transaction_type'] = 1; // 1 = Payment, 2 = Receipt
											$transaction_entry_data['transaction_date'] = $rec_date;
											$transaction_entry_data['from_account_id']  = $bank_account_id;
											$transaction_entry_data['to_account_id']    = $account_id;
											$transaction_entry_data['receipt_no']       = "";//there is No column for this in excel
											$transaction_entry_data['amount']           = $credit_value;
											$transaction_entry_data['created_at'] = $this->now_time;
											$transaction_entry_data['created_by'] = $this->logged_in_id;
											$transaction_entry_data['user_created_by'] = $this->session->userdata()['login_user_id'];
											$result = $this->crud->insert('transaction_entry', $transaction_entry_data);
											$last_tr_id = $this->db->insert_id();
										}
									}
								}
							}
							$data['import_client_ledger_data'] = $import_client_ledger_data;
						}
					}
				}

                
                //if($result) {
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('message', 'File Imported Successfully');
                //}
                set_page('master/import',$data);
        }
    }
    
    function hsn(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			if($this->applib->have_access_role(MASTER_HSN_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('hsn','hsn_id',$_POST['id']);
				$data = array(
					'id' => $result->hsn_id,
					'hsn' => $result->hsn,
					'gst_per' => $result->gst_per,
					'hsn_discription' => $result->hsn_discription,
					'created_by' => $result->created_by,
					'updated_by' => $result->updated_by,
					'created_at' => date('Y-m-d H:m a', strtotime( $result->created_at )),
					'updated_at' => date('Y-m-d H:m a', strtotime( $result->updated_at )),
				);
				set_page('master/hsn', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
			
		} else {
			if($this->applib->have_access_role(MASTER_HSN_ID,"view") || $this->applib->have_access_role(MASTER_HSN_ID,"add")) {
            	$data = array();	
				set_page('master/hsn', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		}
		
	}

	function save_hsn(){
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$data['hsn'] = $post_data['hsn'];
			$data['gst_per'] = $post_data['gst_per'];
			$data['hsn_discription'] = $post_data['hsn_discription'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['hsn_id'] = $post_data['id'];
			$result = $this->crud->update('hsn', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$data['hsn'] = $post_data['hsn'];
			$data['gst_per'] = $post_data['gst_per'];
			$data['hsn_discription'] = $post_data['hsn_discription'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('hsn',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

	function hsn_datatable(){
		$config['table'] = 'hsn h';
		$config['select'] = 'h.*';
		$config['column_order'] = array(null, 'h.hsn', 'h.hsn_discription');
		$config['column_search'] = array('h.hsn', 'h.hsn_discription');
		$config['order'] = array('h.hsn_id' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_HSN_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_HSN_ID, "delete");
		foreach ($list as $hsn) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $hsn->hsn_id . '" method="post" action="' . base_url() . 'master/hsn" class="pull-left">
							<input type="hidden" name="id" id="id" value="' . $hsn->hsn_id . '">
							<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $hsn->hsn_id . '\').submit();" title="Edit City"><i class="fa fa-edit"></i></a>
						</form>';
			}
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $hsn->hsn_id) . '"><i class="fa fa-trash"></i></a>';
			}

			$row[] = $action;
			$row[] = $hsn->hsn;
			$row[] = $hsn->gst_per;
			$row[] = $hsn->hsn_discription;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
    
    function sub_category(){
        $li_sub_category = isset($this->session->userdata()['li_sub_category']) ? $this->session->userdata()['li_sub_category'] : '0';
        if($li_sub_category == '1'){
            if (!empty($_POST['id']) && isset($_POST['id'])) {
            	if($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"edit")) {
	            	$result = $this->crud->get_data_row_by_id('sub_category', 'sub_cat_id', $_POST['id']);
	                $data = array(
	                    'id' => $result->sub_cat_id,
	                    'cat_id' => $result->cat_id,
	                    'sub_cat_name' => $result->sub_cat_name,
	                );
	                set_page('master/sub_category', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
                	$this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
            } else {
            	if($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"view") || $this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"add")) {
	            	$data = array();
		        	set_page('master/sub_category', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
                	$this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
            }
        } else {
            redirect('/');
        }
    }

    function save_sub_category(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
            $id_result = $this->crud->get_same_by_val('sub_category','sub_cat_id','sub_cat_name',trim($post_data['sub_cat_name']),'cat_id',$post_data['cat_id'],$post_data['id']);
            if(!empty($id_result)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $data['cat_id'] = $post_data['cat_id'];
			$data['sub_cat_name'] = $post_data['sub_cat_name'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['sub_cat_id'] = $post_data['id'];
			$result = $this->crud->update('sub_category', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		} else {
			$id_result = $this->crud->get_same_by_val('sub_category','cat_id','sub_cat_name',trim($post_data['sub_cat_name']),'cat_id',$post_data['cat_id']);
			if(!empty($id_result)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
            $data['cat_id'] = $post_data['cat_id'];
			$data['sub_cat_name'] = $post_data['sub_cat_name'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('sub_category',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

    function sub_category_datatable(){
        $config['table'] = 'sub_category c';
        $config['select'] = 'c.sub_cat_id, c.sub_cat_name, pc.cat_name';
        $config['joins'][] = array('join_table' => 'category pc', 'join_by' => 'pc.cat_id = c.cat_id', 'join_type' => 'left');
        $config['column_order'] = array(null, 'pc.cat_name', 'c.sub_cat_name');
        $config['column_search'] = array('pc.cat_name', 'c.sub_cat_name');
        $config['order'] = array('c.sub_cat_name' => 'asc');
        $config['wheres'][] = array('column_name' => 'c.created_by', 'column_value' => $this->logged_in_id);
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $isEdit = $this->applib->have_access_role(MASTER_SUB_CATEGORY_ID, "edit");
        $isDelete = $this->applib->have_access_role(MASTER_SUB_CATEGORY_ID, "delete");
        foreach ($list as $categorys) {
            $row = array();
            $action = '';            
            if($isEdit) {
            	$action .= '<form id="edit_' . $categorys->sub_cat_id . '" method="post" action="' . base_url() . 'master/sub_category" class="pull-left">
                    <input type="hidden" name="id" id="id" value="' . $categorys->sub_cat_id . '">
                    <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $categorys->sub_cat_id . '\').submit();" title="Edit Category"><i class="fa fa-edit"></i></a>
	            </form>';
            }
            if($isDelete) {
            	$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $categorys->sub_cat_id) . '"><i class="fa fa-trash"></i></a>';	
            }            
            $row[] = $action;
            $row[] = $categorys->cat_name;
            $row[] = $categorys->sub_cat_name;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function delete_account($id=''){
        $return = array();
        $result = $this->crud->delete('account', array('account_id' => $id));
        if(isset($result['error'])){
            $return['error'] = "Error";
        } else {
        	$return['success'] = "Deleted";
        }
        print json_encode($return);
        exit;
    }

    function delete_account_group($id=''){
        $return = array();
        $result = $this->crud->delete('account_group', array('account_group_id' => $id));
        if(isset($result['error'])){
            $return['error'] = "Error";
        } else {
                $return['success'] = "Deleted";
        }
        print json_encode($return);
    }
        
    function user_rights(){
    	if($this->applib->have_access_role(MASTER_USER_RIGHTS_ID,"view")) {
        	$data = array();
	        $data['users'] = $this->crud->getFromSQL("SELECT * FROM `user` WHERE userType != '".USER_TYPE_USER."' ORDER BY `user_name` ASC ");
	        $user_id = isset($_GET['user_type']) ? $_GET['user_type']:0;
	        $data['user_type_id'] = $user_id;
	        $data['modules_roles'] = $this->app_model->getModuleRoles();
	        $data['user_roles'] = $this->app_model->getUserRoleIDS($user_id);
	        set_page('master/user_rights', $data);
        } else {
        	$this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
        	redirect('/');
        }
    }
        
    function user_user_rights(){
    	if($this->applib->have_access_role(MASTER_USER_ID,"view")) {
        	$data = array();
	        $data['users'] = $this->crud->getFromSQL("SELECT * FROM `user` WHERE userType = '".USER_TYPE_USER."' AND company_id = '".$this->logged_in_id."' ORDER BY `user_name` ASC ");

	        $user_id = isset($_GET['user_type']) ? $_GET['user_type']:0;
	        $data['user_type_id'] = $user_id;
	        $data['modules_roles'] = $this->app_model->getModuleRoles();
	        $data['user_roles'] = $this->app_model->getUserRoleIDS($user_id);
	        set_page('master/user_user_rights', $data);
        } else {
        	$this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
        	redirect('/');
        }
    }
    
    function update_roles(){
        $status = 1;
        $msg = "Roles has been updated successfully.";
        $user_id = $this->input->post("user_type");
        if(intval($user_id) > 0){
            $roles = $this->input->post("roles");
            $sql = "DELETE FROM user_roles WHERE user_id='$user_id'";
            $this->crud->execuetSQL($sql);
            // add new roles
            $dataToInsert = array();
            if(is_array($roles) && count($roles) > 0){
                if(!empty($user_id)){
                    if (is_array($roles) && count($roles) > 0) {
                        $user_role_data = array();
                        foreach ($roles as $module_id => $role_id) {
                            $tmp = explode("_", $module_id);
                            $module_id = $tmp[1];
                            $data = array(
                                'user_id' => $user_id,
                                'website_module_id' => $module_id,
                                'role_type_id' => $role_id,
                            );
                            array_push($user_role_data, $data);
                        }
                        $this->db->insert_batch('user_roles', $user_role_data);
                    }
                }
            }
        }else{
            $status = 0;
            $msg = "Please Select User.";
        }

        echo json_encode(array("status" => $status,"msg" => $msg));
        exit;
    }

    function stock_status_change()
    {
    	$data = array();
        if (!empty($_POST['id']) && isset($_POST['id'])) {
            if($this->applib->have_access_role(MODULE_STOCK_STATUS_CHANGE_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('stock_status_change','st_change_id',$_POST['id']);
            	if(!empty($result)) {
            		$data = array(
						'id' => $result->st_change_id,
						'st_change_date' => $result->st_change_date,
						'from_status' => $result->from_status,
						'to_status' => $result->to_status,
						'item_id' => $result->item_id,
						'qty' => $result->qty,
					);
            	}
	        	set_page('master/stock_status_change', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
        } else {
            if($this->applib->have_access_role(MODULE_STOCK_STATUS_CHANGE_ID,"view") || $this->applib->have_access_role(MODULE_STOCK_STATUS_CHANGE_ID,"add")) {
	        	set_page('master/stock_status_change', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
        }
    }
    
    function get_current_item_stock_data_by_status($item_id = ''){
        $in_stock_qty = 0;
        $in_wip_qty = 0;
        $in_work_done_qty = 0;
        $sale_total_qty = 0;
        $item_minimum_stock = 0;
        
        $st_data = $this->crud->getFromSQL(" SELECT * FROM stock_status_change WHERE item_id = ".$item_id." AND created_by=".$this->logged_in_id);
        if(!empty($st_data)){
            foreach ($st_data as $st){
                if($st->from_status == IN_PURCHASE_ID && $st->to_status == IN_STOCK_ID){
                    $in_stock_qty = $in_stock_qty + $st->qty;
                } else if($st->from_status == IN_STOCK_ID && $st->to_status == IN_WIP_ID){
                    $in_stock_qty = $in_stock_qty - $st->qty;
                    $in_wip_qty = $in_wip_qty + $st->qty;
                } else if($st->from_status == IN_ORDER_ID && $st->to_status == IN_WIP_ID){
                    $in_wip_qty = $in_wip_qty + $st->qty;
                } else if($st->from_status == IN_WIP_ID && $st->to_status == IN_WORK_DONE_ID){
                    $in_wip_qty = $in_wip_qty - $st->qty;
                    $in_work_done_qty = $in_work_done_qty + $st->qty;
                } else if($st->from_status == IN_WORK_DONE_ID && $st->to_status == IN_STOCK_ID){
                    $in_work_done_qty = $in_work_done_qty - $st->qty;
                    $in_stock_qty = $in_stock_qty + $st->qty;
                } else if($st->from_status == IN_WORK_DONE_ID && $st->to_status == IN_SALE_ID){
                    $in_stock_qty = $in_stock_qty - $st->qty;
                } else if($st->from_status == IN_SIDE_PRODUCT_ID && $st->to_status == IN_STOCK_ID){
                    $in_stock_qty = $in_stock_qty + $st->qty;
                } else if($st->from_status == IN_WIP_ID && $st->to_status == IN_SUB_ITEM_ID){
                    $in_wip_qty = $in_wip_qty - $st->qty;
                }
            }
        }
        
//        echo $in_stock_qty."<br/>";
//        echo $in_work_done_qty."<br/>";
//        echo $sale_total_qty."<br/>";
//        exit;
        
        
//        $temp_in_work_done_qty = $in_work_done_qty - $sale_total_qty;
//        if($temp_in_work_done_qty < 0){
//            $in_work_done_qty = 0;
//            $after_work_done_qty = abs($temp_in_work_done_qty);
//            $temp_wip_qty = $in_wip_qty - $after_work_done_qty;
////             echo $temp_wip_qty."<br/>";exit;
//            if($temp_wip_qty < 0){
//                $in_wip_qty = 0;
//                $in_stock_qty = $in_stock_qty - abs($temp_wip_qty);
//            }
//        } else {
//            $in_work_done_qty = $temp_in_work_done_qty;
//        }
        echo json_encode(array(
                'in_stock_qty' => $in_stock_qty,
                'in_wip_qty' => $in_wip_qty,
                'in_work_done_qty' => $in_work_done_qty,
                'item_minimum_stock' => $item_minimum_stock,
        ));
        exit();
    }
    
    function save_stock_status_change() {
        
        $return = array();
        $data = array();
        $post_data = $this->input->post();
        
        
        if($post_data['from_status'] == IN_ORDER_ID){
            $post_data['to_status'] = IN_WIP_ID;
        } else if($post_data['from_status'] == IN_WIP_ID){
            $post_data['to_status'] = IN_WORK_DONE_ID;
        } else if($post_data['from_status'] == IN_WORK_DONE_ID){
            $post_data['to_status'] = IN_STOCK_ID;
        } else {
            $post_data['to_status'] = '';
        } 
//        echo "<pre>"; print_r($post_data); exit;
        
        $st_change_date = date("Y-m-d", strtotime($post_data['st_change_date']));
        $from_status = $post_data['from_status'];
        $to_status = $post_data['to_status'];
        $item_id = $post_data['item_id'];
        $qty = $post_data['qty'];
        
        $stock_s_data = array();
        $stock_s_data['st_change_date'] =  $st_change_date;
        $stock_s_data['from_status'] =  $from_status;
        $stock_s_data['to_status'] = $to_status;
        $stock_s_data['item_id'] = $item_id;
        $stock_s_data['qty'] =  $qty;
        $stock_s_data['tr_type'] =  null;
        $stock_s_data['tr_id'] =  null;
        $stock_s_data['created_at'] =  $this->now_time;
        $stock_s_data['created_by'] =  $this->logged_in_id;
        $this->db->insert('stock_status_change', $stock_s_data);
        $st_relation_id = $this->db->insert_id();
        
        if($from_status == IN_ORDER_ID && $to_status == IN_WIP_ID){
            $qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '" . $item_id . "' ORDER BY id DESC");
            if(!empty($qty_setting_res)){
                foreach ($qty_setting_res as $setting){
                    if($setting->sub_item_add_less == 2){
                        $stock_s_data = array();
                        $stock_s_data['st_change_date'] =  $st_change_date;
                        $stock_s_data['from_status'] =  IN_STOCK_ID;
                        $stock_s_data['to_status'] = $to_status;
                        $stock_s_data['item_id'] = $setting->sub_item_id;
                        $sub_qty = ($setting->sub_item_qty / $setting->item_qty) * $qty;
                        $sub_qty = round($sub_qty, 2);
                        $stock_s_data['qty'] =  $sub_qty;
                        $stock_s_data['tr_type'] =  null;
                        $stock_s_data['tr_id'] =  null;
                        $stock_s_data['st_relation_id'] =  $st_relation_id;
                        $stock_s_data['created_at'] =  $this->now_time;
                        $stock_s_data['created_by'] =  $this->logged_in_id;
                        $this->db->insert('stock_status_change', $stock_s_data);
                    }
                }
            }
        }
        if($from_status == IN_WIP_ID && $to_status == IN_WORK_DONE_ID){
            $qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '" . $item_id . "' ORDER BY id DESC");
            if(!empty($qty_setting_res)){
                foreach ($qty_setting_res as $setting){
                    if($setting->sub_item_add_less == 1){
                        $stock_s_data = array();
                        $stock_s_data['st_change_date'] =  $st_change_date;
                        $stock_s_data['from_status'] =  IN_SIDE_PRODUCT_ID;
                        $stock_s_data['to_status'] = IN_STOCK_ID;
                        $stock_s_data['item_id'] = $setting->sub_item_id;
                        $sub_qty = ($setting->sub_item_qty / $setting->item_qty) * $qty;
                        $sub_qty = round($sub_qty, 2);
                        $stock_s_data['qty'] =  $sub_qty;
                        $stock_s_data['tr_type'] =  null;
                        $stock_s_data['tr_id'] =  null;
                        $stock_s_data['st_relation_id'] =  $st_relation_id;
                        $stock_s_data['created_at'] =  $this->now_time;
                        $stock_s_data['created_by'] =  $this->logged_in_id;
                        $this->db->insert('stock_status_change', $stock_s_data);
                    } else {
                        $stock_s_data = array();
                        $stock_s_data['st_change_date'] =  $st_change_date;
                        $stock_s_data['from_status'] =  IN_WIP_ID;
                        $stock_s_data['to_status'] = IN_SUB_ITEM_ID;
                        $stock_s_data['item_id'] = $setting->sub_item_id;
                        $sub_qty = ($setting->sub_item_qty / $setting->item_qty) * $qty;
                        $sub_qty = round($sub_qty, 2);
                        $stock_s_data['qty'] =  $sub_qty;
                        $stock_s_data['tr_type'] =  null;
                        $stock_s_data['tr_id'] =  null;
                        $stock_s_data['st_relation_id'] =  $st_relation_id;
                        $stock_s_data['created_at'] =  $this->now_time;
                        $stock_s_data['created_by'] =  $this->logged_in_id;
                        $this->db->insert('stock_status_change', $stock_s_data);
                    }
                }
            }
        }
        $return['success'] = "Added";
        print json_encode($return);
        exit;
    }

    function save_stock_status_change_old()
    {
		$return = array();
		$data = array();
		$post_data = $this->input->post();
		$qty = $post_data['qty'];
		$item_id = $post_data['item_id'];
		$from_status = $post_data['from_status'];
		$from_status_text = $this->get_stock_status_text_by_id($post_data['from_status']);
		$to_status = $post_data['to_status'];
		$to_status_text = $this->get_stock_status_text_by_id($post_data['to_status']);
		$note = 'Stock Status Changed From '.$from_status_text.' To '.$to_status_text;

		if(isset($post_data['id']) && !empty($post_data['id'])){
			$old_data = $this->crud->get_data_row_by_id('stock_status_change','st_change_id',$_POST['id']);

			$st_change_id =$post_data['id'];
			$data['st_change_date'] = date("Y-m-d",strtotime($post_data['st_change_date']));
			$data['from_status'] = $post_data['from_status'];
			$data['to_status'] = $post_data['to_status'];
			$data['item_id'] = $post_data['item_id'];
			$data['qty'] = $post_data['qty'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['st_change_id'] = $post_data['id'];
			$result = $this->crud->update('stock_status_change', $data, $where_array);

			/*-- Maintain Stock --*/
			if($old_data->to_status != $post_data['to_status']) {
				$qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '".$item_id."' ORDER BY id DESC");
		        if(!empty($qty_setting_res)){
		        	if($from_status == IN_WIP_ID && $to_status == IN_WORK_DONE_ID) {
		        		foreach ($qty_setting_res as $key => $qty_setting_row) {
		        			$sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
		        			$sub_item_qty = round($sub_item_qty,2);
			        		$stock_data = array(
								'item_id' => $qty_setting_row->sub_item_id,
								'st_change_id' => $st_change_id,
								'is_sales_or_purchase' => 'stock_status_change',
								'qty' => $sub_item_qty,
								'action' => 'stock_status_change',
								'note' => $note,
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							);

							if($qty_setting_row->sub_item_add_less == 1) {
								$this->db->insert('item_current_stock_detail',$stock_data);

								$this->db->where('item_id',$qty_setting_row->sub_item_id);
								$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$sub_item_qty, FALSE);
								$this->db->update('item');
							}
		        		}

		        		$stock_data = array(
							'item_id' => $item_id,
							'st_change_id' => $st_change_id,
							'is_sales_or_purchase' => 'stock_status_change',
							'qty' => $qty,
							'action' => 'stock_status_change',
							'note' => $note,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);
						$this->db->insert('item_current_stock_detail',$stock_data);

						$this->db->where('item_id',$item_id);
						$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$qty, FALSE);
						$this->db->update('item');

		        	}
		        }
			}
			/*--/Maintain Stock --*/

			if ($result) {
				$return['success'] = "Updated";
			}
		} else {
			$data['st_change_date'] = date("Y-m-d",strtotime($post_data['st_change_date']));
			$data['from_status'] = $post_data['from_status'];
			$data['to_status'] = $post_data['to_status'];
			$data['item_id'] = $post_data['item_id'];
			$data['qty'] = $post_data['qty'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$st_change_id = $this->crud->insert('stock_status_change',$data);
			$result = $st_change_id;

			/*-- Maintain Stock --*/
			$qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '".$item_id."' ORDER BY id DESC");
	        if(!empty($qty_setting_res)){
	        	foreach ($qty_setting_res as $key => $qty_setting_row) {
	        		if($qty_setting_row->sub_item_qty != 0 && $qty_setting_row->item_qty != 0) {
	        			if(in_array($to_status,array(IN_WIP_ID,IN_WORK_DONE_ID))) {

	        				$sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
		        			$sub_item_qty = round($sub_item_qty,2);
			        		$stock_data = array(
								'item_id' => $qty_setting_row->sub_item_id,
								'st_change_id' => $st_change_id,
								'is_sales_or_purchase' => 'stock_status_change',
								'qty' => $sub_item_qty,
								'action' => 'stock_status_change',
								'note' => $note,
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							);

							if($qty_setting_row->sub_item_add_less == 2) {
								$stock_data['qty'] = ($sub_item_qty * -1);
								$this->db->insert('item_current_stock_detail',$stock_data);

								$this->db->where('item_id',$qty_setting_row->sub_item_id);
								$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$sub_item_qty, FALSE);
								$this->db->update('item');

							} else {
								if(in_array($to_status,array(IN_WORK_DONE_ID))) {
									$this->db->insert('item_current_stock_detail',$stock_data);

									$this->db->where('item_id',$qty_setting_row->sub_item_id);
									$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$sub_item_qty, FALSE);
									$this->db->update('item');
								}
							}
	        			}
	        		}
	        	}
	        }

        	if(in_array($to_status,array(IN_WORK_DONE_ID))) {
        		$stock_data = array(
					'item_id' => $item_id,
					'st_change_id' => $st_change_id,
					'is_sales_or_purchase' => 'stock_status_change',
					'qty' => $qty,
					'action' => 'stock_status_change',
					'note' => $note,
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
				);
				$this->db->insert('item_current_stock_detail',$stock_data);

				$this->db->where('item_id',$item_id);
				$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$qty, FALSE);
				$this->db->update('item');
			}
			/*--/Maintain Stock --*/
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;		
    }

    function get_stock_status_text_by_id($id)
    {
    	$status_text = '';
    	if($id == IN_STOCK_ID) {
			$status_text = 'In Stock';
		} elseif($id == IN_WIP_ID) {
			$status_text = 'In WIP';
		} elseif($id == IN_WORK_DONE_ID) {
			$status_text = 'In Work Done';
		}
    	return $status_text;
    }

    function stock_status_change_datatable(){
		$config['select'] = 'r.*,i.item_name';
		$config['table'] = 'stock_status_change r';
		$config['joins'][] = array('join_table' => 'item i', 'join_by' => 'i.item_id = r.item_id');
		$config['wheres'][] = array('column_name' => 'r.created_by', 'column_value' => $this->logged_in_id);
		$config['column_order'] = array(null, 'r.st_change_date','i.item_name','r.qty','r.to_status');
		$config['column_search'] = array('DATE_FORMAT(r.st_change_date,"%d-%m-%Y")','i.item_name','r.qty','r.to_status');
		$config['order'] = array('r.st_change_id' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		$isEdit = $this->applib->have_access_role(MODULE_STOCK_STATUS_CHANGE_ID, "edit");
		$isDelete = $this->applib->have_access_role(MODULE_STOCK_STATUS_CHANGE_ID, "delete");
		foreach ($list as $request_row) {
			$row = array();
			$action = '';
			
//			if($request_row->to_status != IN_WORK_DONE_ID &&$isEdit) {
//				$action .= '<form id="edit_' . $request_row->st_change_id . '" method="post" action="' . base_url() . 'master/stock_status_change" class="pull-left">
//						<input type="hidden" name="id" id="id" value="' . $request_row->st_change_id . '">
//						<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $request_row->st_change_id . '\').submit();" title="Edit Company"><i class="fa fa-edit"></i></a>
//						</form>';	
//			}
			
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete_stock_status_change/' . $request_row->st_change_id) . '"><i class="fa fa-trash"></i></a>';	
			}
			
			$row[] = $action;
			$row[] = date("d-m-Y",strtotime($request_row->st_change_date));
			$row[] = $request_row->item_name;
			$row[] = $request_row->qty;
			$to_status = '';
			if($request_row->to_status == IN_STOCK_ID) {
				$to_status = 'In Stock';
			} elseif($request_row->to_status == IN_WIP_ID) {
				$to_status = 'In WIP';
			} elseif($request_row->to_status == IN_WORK_DONE_ID) {
				$to_status = 'In Work Done';
			} elseif($request_row->to_status == IN_PURCHASE_ID) {
				$to_status = 'Purchase';
			} elseif($request_row->to_status == IN_SALE_ID) {
				$to_status = 'Sale';
			} elseif($request_row->to_status == IN_ORDER_ID) {
				$to_status = 'Order';
			} elseif($request_row->to_status == IN_SIDE_PRODUCT_ID) {
				$to_status = 'Sub Add';
			} elseif($request_row->to_status == IN_SUB_ITEM_ID) {
				$to_status = 'Sub Less';
			}
			$row[] = $to_status;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

    function delete_stock_status_change($st_change_id) {
        $return = array();
        $this->crud->delete('stock_status_change',array('st_relation_id'=>$st_change_id));
        $this->crud->delete('stock_status_change',array('st_change_id'=>$st_change_id));
        $return['success'] = "Deleted";
        print json_encode($return);
        exit;
    }

    function delete_stock_status_change_old($st_change_id)
	{
		$return = array();

		$old_data = $this->crud->get_data_row_by_id('stock_status_change','st_change_id',$st_change_id);
		if(!empty($old_data)) {
			$qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '".$old_data->item_id."' ORDER BY id DESC");
	        if(!empty($qty_setting_res)){
	        	$qty = $old_data->qty;
				$item_id = $old_data->item_id;
				$from_status = $old_data->from_status;
				$from_status_text = $this->get_stock_status_text_by_id($old_data->from_status);
				$to_status = $old_data->to_status;
				$to_status_text = $this->get_stock_status_text_by_id($old_data->to_status);
				$note = 'Delete : Stock Status Changed From '.$from_status_text.' To '.$to_status;

	        	if(in_array($old_data->from_status,array(IN_STOCK_ID,IN_WIP_ID)) && $old_data->to_status == IN_WORK_DONE_ID) {
	        		foreach ($qty_setting_res as $key => $qty_setting_row) {
	        			$sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
	        			$sub_item_qty = round($sub_item_qty,2);
		        		$stock_data = array(
							'item_id' => $qty_setting_row->sub_item_id,
							'st_change_id' => $st_change_id,
							'is_sales_or_purchase' => 'stock_status_change',
							'qty' => $sub_item_qty,
							'action' => 'stock_status_change',
							'note' => $note,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);

						if($qty_setting_row->sub_item_add_less == 1) { //remove added qty
							$stock_data['qty'] = ($sub_item_qty * -1);
							$this->db->insert('item_current_stock_detail',$stock_data);

							$this->db->where('item_id',$qty_setting_row->sub_item_id);
							$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$sub_item_qty, FALSE);
							$this->db->update('item');
						} else { //add removed qty
							$this->db->insert('item_current_stock_detail',$stock_data);

							$this->db->where('item_id',$qty_setting_row->sub_item_id);
							$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$sub_item_qty, FALSE);
							$this->db->update('item');
						}
	        		}
	        		$stock_data = array(
						'item_id' => $item_id,
						'st_change_id' => $st_change_id,
						'is_sales_or_purchase' => 'stock_status_change',
						'qty' => ($qty * -1),
						'action' => 'delete_stock_status_change',
						'note' => $note,
						'created_at' => date("Y-m-d H:i:s"),
						'updated_at' => date("Y-m-d H:i:s"),
					);
					$this->db->insert('item_current_stock_detail',$stock_data);

					$this->db->where('item_id',$item_id);
					$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$qty, FALSE);
					$this->db->update('item');

	        	} elseif($old_data->from_status == IN_STOCK_ID && $old_data->to_status == IN_WIP_ID) {
	        		foreach ($qty_setting_res as $key => $qty_setting_row) {
	        			$sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
	        			$sub_item_qty = round($sub_item_qty,2);
		        		$stock_data = array(
							'item_id' => $qty_setting_row->sub_item_id,
							'st_change_id' => $st_change_id,
							'is_sales_or_purchase' => 'stock_status_change',
							'qty' => $sub_item_qty,
							'action' => 'stock_status_change',
							'note' => $note,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);

						if($qty_setting_row->sub_item_add_less == 1) { //remove added qty
							
						} else { //add removed qty
							$this->db->insert('item_current_stock_detail',$stock_data);

							$this->db->where('item_id',$qty_setting_row->sub_item_id);
							$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$sub_item_qty, FALSE);
							$this->db->update('item');
						}
	        		}
	        	}
	        }	
		}
		/*--/Maintain Stock --*/

		$result = $this->crud->delete('stock_status_change',array('st_change_id'=>$st_change_id));
		if(isset($result['error'])){
            $return['error'] = "Error";
        } else {
        	$return['success'] = "Deleted";
        }
        print json_encode($return);
        exit;
	}

	function user($user_id = '')
	{
		if(!empty($user_id) && $user_id == "profile") {
			$user_id = $this->session->userdata()['login_user_id'];
			$result = $this->crud->get_data_row_by_id('user', 'user_id', $user_id);
            $data = array(
                'profile_edit' => true,
                'edit_user_id' => $result->user_id,
                'edit_user_name' => $result->user_name,
                'edit_user' => $result->user,
                'edit_password' => $result->password,
                'edit_phone' => $result->phone,
            );
            set_page('master/user', $data);
		} else {
			$data = array();
	        if (!empty($_POST['user_id']) && isset($_POST['user_id'])) {
	            if($this->applib->have_access_role(MASTER_USER_ID,"edit")) {
	            	$result = $this->crud->get_data_row_by_id('user', 'user_id', $_POST['user_id']);
	                $data = array(
	                        'edit_user_id' => $result->user_id,
	                        'edit_user_name' => $result->user_name,
	                        'edit_user' => $result->user,
	                        'edit_password' => $result->password,
	                        'edit_phone' => $result->phone,
	                    );
	                set_page('master/user', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
	                $this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
	        } else {
	            if($this->applib->have_access_role(MASTER_USER_ID,"add")) {
		        	set_page('master/user', $data);
		        } else {
		        	$this->session->set_flashdata('success', false);
	                $this->session->set_flashdata('message', 'You have not permission to access this page.');
		        	redirect('/');
		        }
	        }
		}
	}

	function save_user() {
		$return = array();
        $post_data = $this->input->post();

        $user_id = isset($post_data['user_id']) ? $post_data['user_id'] : 0;

        $post_data['user'] = isset($post_data['user']) ? $post_data['user'] : null;
        $post_data['phone'] = isset($post_data['phone']) ? $post_data['phone'] : null;

        if (isset($post_data['user_id']) && !empty($post_data['user_id'])) {
            $where_arr['user_name'] = trim($post_data['user_name']);

            $ac_id = $this->crud->check_duplicate('user', 'user_id', $where_arr, $post_data['user_id']);
            if (!empty($ac_id)) {
                $return['error'] = "userExist";
                print json_encode($return);
                exit;
            }
            if (isset($post_data['user']) && !empty($post_data['user'])) {
                $mail_val = $this->crud->get_id_by_val_not('user', 'user_id', 'user', trim($post_data['user']), $post_data['user_id']);
                if (!empty($mail_val)) {
                    $return['error'] = "emailExist";
                    print json_encode($return);
                    exit;
                }
            }
        } else {

            $where_arr['user_name'] = trim($post_data['user_name']);

            $ac_id = $this->crud->check_duplicate('user', 'user_id', $where_arr);
            if (!empty($ac_id)) {
                $return['error'] = "userExist";
                print json_encode($return);
                exit;
            }
            if (isset($post_data['user']) && !empty($post_data['user'])) {
                $mail_val = $this->crud->get_id_by_val('user', 'user_id', 'user', trim($post_data['user']));
                if (!empty($mail_val)) {
                    $return['error'] = "emailExist";
                    print json_encode($return);
                    exit;
                }
            }
        }

        if (isset($post_data['user_id']) && !empty($post_data['user_id']) && $post_data['user_id'] != 0) {
        	if (isset($post_data['password']) && !empty($post_data['password'])) {
                $post_data['password'] = md5($post_data['password']);
            } else {
                $result = $this->crud->get_data_row_by_id('user', 'user_id', $post_data['user_id']);
                $post_data['password'] = $result->password;
            }
            if (empty($post_data['user'])) {
                $result = $this->crud->get_data_row_by_id('user', 'user_id', $post_data['user_id']);
                $post_data['user'] = $result->user;
            }
            $post_data['updated_by'] = $this->logged_in_id;
            $post_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $post_data['updated_at'] = $this->now_time;
            $where_array['user_id'] = $post_data['user_id'];
            $result = $this->crud->update('user', $post_data, $where_array);
            if ($result) {
            	$return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                if($this->session->userdata()['login_user_id'] == $post_data['user_id']) {
                	$this->session->set_flashdata('message', 'Profile Updated Successfully');
                } else {
                	$this->session->set_flashdata('message', 'User Updated Successfully');	
                }
                
            } else {
                $return['error'] = "errorUpdated";
            }
        } else {
            $post_data['userType'] = USER_TYPE_USER;
        	$post_data['password'] = isset($post_data['password']) ? md5($post_data['password']) : null;
            $post_data['company_id'] = $this->logged_in_id;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $post_data['created_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $post_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $post_data['updated_at'] = $this->now_time;
            $result = $this->crud->insert('user', $post_data);
            $last_query_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'User Added Successfully');
            } else {
                $return['error'] = "errorAdded";
            }
        }
        print json_encode($return);
        exit;
	}

	function user_list()
	{
		if ($this->applib->have_access_role(MASTER_USER_ID, "view")){
            $data = array();
            set_page('master/user_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
	}

	function user_datatable(){
        $config['table'] = 'user';
        $config['select'] = 'user_id, user_name, email_ids, phone, isActive, verify_otp';
        $config['column_order'] = array(null, null, null, 'user_name', 'email_ids', 'phone','isActive');
        $config['column_search'] = array('user_name', 'email_ids', 'phone','isActive');
        $config['order'] = array('user_name' => 'desc');
        $config['wheres'][] = array('column_name' => 'userType', 'column_value' => USER_TYPE_USER);
        $config['wheres'][] = array('column_name' => 'company_id', 'column_value' => $this->logged_in_id);

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        foreach ($list as $users) {
            $row = array();
            $action = '';
            
            if($users->isActive == '1'){
                $action .= ' <a href="javascript:void(0);" class="change_status btn-danger btn-xs" title="Click To Inactive." data-status = "active" data-href="' . base_url('user/change_status/' . $users->user_id) . '"><i class="fa fa-times"></i></a>';
            } else {
                $action .= ' <a href="javascript:void(0);" class="change_status btn-success btn-xs" title="Click To Active." data-status = "deactive" data-href="' . base_url('user/change_status/' . $users->user_id) . '"><i class="fa fa-check"></i></a>';
            }

            if ($this->applib->have_access_role(MASTER_USER_ID, "edit")){
                $action .= ' | <form id="edit_' . $users->user_id . '" method="post" action="' . base_url() . 'master/user" style="width: 25px; display: initial;" >
                    <input type="hidden" name="user_id" id="user_id" value="' . $users->user_id . '">
                    <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $users->user_id . '\').submit();" title="Edit User"><i class="fa fa-edit"></i></a>
                </form> ';
            }
            
            if ($this->applib->have_access_role(MASTER_USER_ID, "delete")){
                $action .= ' | <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $users->user_id) . '"><i class="fa fa-trash"></i></a>';
            }

            $row[] = $users->user_id;
            $row[] = $action;
            $row[] = $users->user_name;
            $row[] = $users->phone;
            if($users->isActive == '1'){
                $isActive = '<font color="green">Active</font>';
            }else{
                $isActive = '<font color="red">InActive</font>';
            }
            $row[] = $isActive;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function export() {
        if ($this->applib->have_access_role(MASTER_IMPORT_ID, "view")) {
            $data = array();
            set_page('master/export', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function export_data() {
        print_r($_POST); exit;
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $data = array();

        if ($this->input->post('submit')) {
            $radio_type = $this->input->post('import_radio');
            if ($radio_type == 2) {}
            if ($radio_type == 3) {}
            if ($radio_type == 4) {}
            if ($radio_type == 6) {}
            if ($radio_type == 7) {}
            if ($radio_type == 8) {}
            if ($radio_type == 9) {}


            //if($result) {
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'File Exported Successfully');
            //}
            set_page('master/import', $data);
        }
    }

	function site(){
		if(!empty($_POST['id']) && isset($_POST['id'])){
			$result = $this->crud->get_data_row_by_id('sites','site_id',$_POST['id']);
			$data = array(
				'id' => $result->site_id,
				'name' => $result->site_name,
				'site_address' => $result->site_address,
			);
			set_page('master/site', $data);
		} else {
			$data = array();
			set_page('master/site', $data);
		}		
	}

	function site_datatable(){
		$config['select'] = 'site_id, site_name,site_address';
		$config['table'] = 'sites';
		$config['column_order'] = array(null, 'site_name');
		$config['column_search'] = array('site_name');
		$config['wheres'][] = array('column_name' => 'created_by', 'column_value' => $this->logged_in_id);
		$config['order'] = array('site_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		foreach ($list as $sites) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $sites->site_id . '" method="post" action="' . base_url() . 'master/site" class="pull-left">
						<input type="hidden" name="id" id="id" value="' . $sites->site_id . '">
						<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $sites->site_id . '\').submit();" title="Edit Site"><i class="fa fa-edit"></i></a>
					</form>';
			$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $sites->site_id) . '"><i class="fa fa-trash"></i></a>';	
			$row[] = $action;
			$row[] = $sites->site_name;
			$row[] = $sites->site_address;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->datatable->count_all(),
			"recordsFiltered" => $this->datatable->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function save_site(){
		$return = array();
		$post_data = $this->input->post();
		if(isset($post_data['id']) && !empty($post_data['id'])){
			$v_id = $this->crud->get_id_by_val_not('sites','site_id','site_name',trim($post_data['site_name']),$post_data['id']);
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['site_name'] = $post_data['site_name'];
			$data['site_address'] = $post_data['site_address'];
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$where_array['site_id'] = $post_data['id'];
			$result = $this->crud->update('sites', $data, $where_array);
			if ($result) {
				$return['success'] = "Updated";
			}
		}else{
			$v_id = $this->crud->get_id_by_val('sites','site_id','site_name',trim($post_data['site_name']));
			if(!empty($v_id)) {
				$return['error'] = "Exist";
				print json_encode($return);
				exit;
			}
			$data['site_name'] = ucfirst($post_data['site_name']);
			$data['site_address'] = $post_data['site_address'];
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['created_by'] = $this->logged_in_id;
			$result = $this->crud->insert('sites',$data);
			if($result){
				$return['success'] = "Added";
			}
		}
		print json_encode($return);
		exit;
	}

}

