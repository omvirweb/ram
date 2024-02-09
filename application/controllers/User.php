<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class User
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class User extends CI_Controller
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
		$this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
		$this->now_time = date('Y-m-d H:i:s');
	}

    function user($profile = '') {
            if (!empty($profile)) {
                $_POST['user_id'] = $this->logged_in_id;
            }
            if (!empty($_POST['user_id']) && isset($_POST['user_id'])) {
                if ($this->applib->have_access_role(MASTER_COMPANY_ID, "edit")){
                    $result = $this->crud->get_data_row_by_id('user', 'user_id', $_POST['user_id']);
                    $data = array(
                        'edit_user_id' => $result->user_id,
                        'edit_user_name' => $result->user_name,
                        'edit_email_ids' => $result->email_ids,
                        'edit_address' => $result->address,
                        //'country' => $result->country,
                        'edit_state' => $result->state,
                        'edit_city' => $result->city,
                        'edit_postal_code' => $result->postal_code,
                        'edit_phone' => $result->phone,
                        'edit_contect_person_name' => $result->contect_person_name,
                        'edit_user' => $result->user,
                        'edit_userType' => $result->userType,
                        'edit_password' => $result->password,
                        'edit_gst_no' => $result->gst_no,
                        'edit_logo_image' => $result->logo_image,
                        'edit_stamp_image' => $result->stamp_image,
                        'edit_barcode' => $result->barcode,
                        'edit_invoice_no_start_from' => $result->invoice_no_start_from,
                        'edit_is_letter_pad' => $result->is_letter_pad,
                        'edit_is_bill_wise' => $result->is_bill_wise,
                        'edit_sales_invoice_notes' => $result->sales_invoice_notes,
                        'edit_bank_name' => $result->bank_name,
                        'edit_bank_branch' => $result->bank_branch,
                        'edit_bank_ac_no' => $result->bank_ac_no,
                        'edit_rtgs_ifsc_code' => $result->rtgs_ifsc_code,
                        'edit_bank_acc_name' => $result->bank_acc_name,
                        'edit_bank_city' => $result->bank_city,
                        'edit_sales_rate' => $result->sales_rate,
                        'edit_purchase_rate' => $result->purchase_rate,
                        'edit_invoice_type' => $result->invoice_type,
                        'edit_discount_on' => $result->discount_on,
                        'edit_aadhaar' => $result->aadhaar,
                        'edit_pan' => $result->pan,
                        'edit_is_single_line_item' => $result->is_single_line_item,
                        'edit_is_view_item_history' => $result->is_view_item_history,
                    );
                    $main_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "' . $_POST["user_id"] . '" AND module_name = 1 AND setting_value = 1');
                    $invoice_main_fields = array();
                    if (!empty($main_fields)) {
                        foreach ($main_fields as $value) {
                            $invoice_main_fields[] = $value->setting_key;
                        }
                    }
                    $data['invoice_main_fields'] = $invoice_main_fields;

                    $line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "' . $_POST["user_id"] . '" AND module_name = 2 AND setting_value = 1');
                    $invoice_line_item_fields = array();
                    if (!empty($line_item_fields)) {
                        foreach ($line_item_fields as $value) {
                            $invoice_line_item_fields[] = $value->setting_key;
                        }
                    }
                    $data['invoice_line_item_fields'] = $invoice_line_item_fields;

                    $company_invoice_prefix = $this->crud->getFromSQL("select * from company_invoice_prefix WHERE company_id = '" . $_POST['user_id'] . "'");
                    
                    $lineitems = array();
                    if (!empty($company_invoice_prefix)) {
                        foreach ($company_invoice_prefix as $prefix) {
                            $prefix_detail = new \stdClass();
                            $prefix_detail->company_invoice_prefix = $prefix->prefix_name;
                            $prefix_detail->default_prefix = $prefix->is_default;
                            $prefix_detail->id = $prefix->id;
                            
                            $is_used = $this->crud->get_column_value_by_id('sales_invoice', 'sales_invoice_id', array('prefix' => $prefix->id));
                            if($is_used > 0) {
                                $prefix_detail->delete_not_allow = 1; 
                            }
                            $lineitems[] = json_encode($prefix_detail);
                        }
                        $data['prefix_lineitem_details'] = implode(',', $lineitems);
                    }

                    $data['invoice_no_digit'] = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'invoice_no_digit', 'company_id' => $_POST["user_id"]));
                    $data['year_post_fix'] = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'year_post_fix', 'company_id' => $_POST["user_id"]));
                    //echo '<pre>';print_r($data);exit;
                    if (!empty($profile)) {
                        $data['profile'] = $profile;
                    }
                    set_page('user/user', $data);
                } else {
                    redirect('/');
                }
            } else {
                if ($this->applib->have_access_role(MASTER_COMPANY_ID, "add")){
                    $data = array();
                    set_page('user/user', $data);
                } else {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
    }

    function user_list(){
        if ($this->applib->have_access_role(MASTER_COMPANY_ID, "view")){
            $data = array();
            set_page('user/user_list', $data);
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
        $config['wheres'][] = array('column_name' => 'userType !=', 'column_value' => USER_TYPE_USER);
        
        //$config['joins'][] = array('join_table' => 'account_group ag', 'join_by' => 'ag.account_group_id = account_group_id', 'join_type' => 'left');
        $config['order'] = array('user_name' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        //echo $this->db->last_query();exit;
        $data = array();
        //$isEdit = $this->app_model->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
        //$isDelete = $this->app_model->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
        foreach ($list as $users) {
            $row = array();
            $action = '';
            $otp = '';
            if($users->verify_otp == '1'){
                $otp .= ' <a href="javascript:void(0);" class="change_otp_status btn-danger btn-xs" title="Click To Enable." data-status = "disable" data-href="' . base_url('user/change_otp_status/' . $users->user_id) . '"><i class="fa fa-times"></i></a>';
            }else{
                $otp .= ' <a href="javascript:void(0);" class="change_otp_status btn-success btn-xs" title="Click To Disable." data-status = "enable" data-href="' . base_url('user/change_otp_status/' . $users->user_id) . '"><i class="fa fa-check"></i></a>';
            }
            if($users->isActive == '1'){
                $action .= ' <a href="javascript:void(0);" class="change_status btn-danger btn-xs" title="Click To Inactive." data-status = "active" data-href="' . base_url('user/change_status/' . $users->user_id) . '"><i class="fa fa-times"></i></a>';
            }else{
                $action .= ' <a href="javascript:void(0);" class="change_status btn-success btn-xs" title="Click To Active." data-status = "deactive" data-href="' . base_url('user/change_status/' . $users->user_id) . '"><i class="fa fa-check"></i></a>';
            }
            if ($this->applib->have_access_role(MASTER_COMPANY_ID, "edit")){
                $action .= ' | <form id="edit_' . $users->user_id . '" method="post" action="' . base_url() . 'user/user" style="width: 25px; display: initial;" >
                    <input type="hidden" name="user_id" id="user_id" value="' . $users->user_id . '">
                    <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $users->user_id . '\').submit();" title="Edit User"><i class="fa fa-edit"></i></a>
                </form> ';
            }
            //$action .= ' | <a href="' . base_url('master/vehicle/' . $vehicles->vehicle_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
            if ($this->applib->have_access_role(MASTER_COMPANY_ID, "delete")){
                $action .= ' | <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $users->user_id) . '"><i class="fa fa-trash"></i></a>';
            }
            $row[] = $users->user_id;
            $row[] = $action;
            $row[] = $otp;
            $row[] = $users->user_name;
            $p_inc = 1;
            $email_id_arr = explode(',', $users->email_ids);
            $email_ids = '';
            foreach($email_id_arr as $email_id){
                if(!empty(trim($email_id))){
                    $email_ids .= $email_id.', ';
                    if($p_inc%2 == 0){ $email_ids .= '<br />'; }
                    $p_inc++;
                }
            }
            $row[] = $email_ids;
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
        //output to json format
        echo json_encode($output);
    }

	function user_detail(){
		if(!empty($_POST['user_id']) && isset($_POST['user_id'])){
			$result = $this->crud->get_data_row_by_id('user','user_id',$_POST['user_id']);
			$p_inc = 1;
			$email_id_arr = explode(',', $result->email_ids);
			$email_ids = '';
			foreach($email_id_arr as $email_id){
				if(!empty(trim($email_id))){
					$email_ids .= $email_id.', ';
					if($p_inc%2 == 0){ $email_ids .= '<br />'; }
					$p_inc++;
				}
			}
			$data = array(
				'user_id' => $result->user_id,
				'name' => $result->name,
				'email_ids' => $email_ids,
				'sales_person' => $this->crud->get_id_by_val('user', 'name', 'user_id', $result->sales_person),
				'address' => $result->address,
				'country' => $this->crud->get_id_by_val('country', 'country_name', 'country_id', $result->country),
				'state' => $this->crud->get_id_by_val('state', 'state_name', 'state_id', $result->state),
				'city' => $this->crud->get_id_by_val('city', 'city_name', 'city_id', $result->city),
				'postal_code' => $result->postal_code,
				'phone' => $result->phone,
				'home_address' => $result->home_address,
				'home_country' => $this->crud->get_id_by_val('country', 'country_name', 'country_id', $result->home_country),
				'home_state' => $this->crud->get_id_by_val('state', 'state_name', 'state_id', $result->home_state),
				'home_city' => $this->crud->get_id_by_val('city', 'city_name', 'city_id', $result->home_city),
				'home_postal_code' => $result->home_postal_code,
				'home_phone' => $result->home_phone,
				'contect_person_name' => $result->contect_person_name,
				'contect_person_phone' => $result->contect_person_phone,
				'email_id' => $result->email_id,
				'gst_no' => $result->gst_no,
			);
		}else{
			$data = array();
		}
		set_page('user/user_detail', $data);
	}

	function change_status($id){
		$table = $_POST['table_name'];
		$id_name = $_POST['id_name'];
		$res = $this->crud->get_id_by_val($table,'isActive','user_id',$id);
		if($res == 1){
			$post_data['isActive'] = '0';
			$where_array['user_id'] = $id;
			$result = $this->crud->update('user', $post_data, $where_array);
		}else{
			$post_data['isActive'] = '1';
			$where_array['user_id'] = $id;
			$result = $this->crud->update('user', $post_data, $where_array);
		}
		if($result){
			$this->session->set_flashdata('success',true);
			$this->session->set_flashdata('message','Deleted Successfully');
		}
	}
    
	function change_otp_status($id){
		$table = $_POST['table_name'];
		$id_name = $_POST['id_name'];
		$res = $this->crud->get_id_by_val($table,'verify_otp','user_id',$id);
		if($res == 1){
			$post_data['verify_otp'] = '0';
			$where_array['user_id'] = $id;
			$result = $this->crud->update('user', $post_data, $where_array);
		}else{
			$post_data['verify_otp'] = '1';
			$where_array['user_id'] = $id;
			$result = $this->crud->update('user', $post_data, $where_array);
		}
		if($result){
			$this->session->set_flashdata('success',true);
		}
	}

	function activate_status(){
		if($_POST['actionName'] == 'activate'){
			if(!empty($_POST['id']) && isset($_POST['id'])){
				$data['isActive'] = '1';
				$where_in_array = $_POST['id'];
				$result = $this->crud->update_multiple('user', $data,'user_id', $where_in_array);
				if ($result) {
					$return['success'] = "Activate";
				}
			}else{
				$return['error'] = "Empty";
			}
		}
		print json_encode($return);
		exit;
	}

	function deactivate_status(){
		if($_POST['actionName'] == 'deactivate'){
			if(!empty($_POST['id']) && isset($_POST['id'])){
				$data['isActive'] = '0';
				$where_in_array = $_POST['id'];
				$result = $this->crud->update_multiple('user', $data,'user_id', $where_in_array);
				if ($result) {
					$return['success'] = "DeActivate";
				}
			}else{
				$return['error'] = "Empty";
			}
		}
		print json_encode($return);
		exit;
	}

	function multiple_delete(){
		if($_POST['actionName'] == 'delete'){
			if(!empty($_POST['id']) && isset($_POST['id'])){
				$where_in_array = $_POST['id'];
				$result = $this->crud->delete_multiple('user', 'user_id', $where_in_array);
				if ($result) {
					$return['success'] = "DeActivate";
				}
			}else{
				$return['error'] = "Empty";
			}
		}
		print json_encode($return);
		exit;
	}

    function documents(){
        
        if (!empty($_POST['id']) && isset($_POST['id'])) {
            if($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"edit")) {
                $result = $this->crud->get_data_row_by_id('documents','document_id',$_POST['id']);
                $data = array(
                    'id' => $result->document_id,
                    'company_id' => $result->company_id,
                    'document_name' => $result->document_name,
                    'document_link' => $result->document_link,
                    'remark' => $result->remark,
                    'users' => $this->crud->getFromSQL("SELECT * FROM `user` WHERE userType != '".USER_TYPE_USER."' ORDER BY `user_name` ASC ")
                );
                set_page('user/documents', $data);;
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"view") || $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"add")) {
                $data = array();
                $data['users'] = $this->crud->getFromSQL("SELECT * FROM `user` WHERE userType != '".USER_TYPE_USER."' ORDER BY `user_name` ASC ");
                set_page('user/documents', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }
    }

    function documents_datatable()
    {
        $post_data = $this->input->post();
        $config['select'] = 'd.*,u.user_name as company_name';
        $config['table'] = 'documents d';
        $config['joins'][] = array('join_table' => 'user u', 'join_by' => 'u.user_id = d.company_id');
        $config['column_order'] = array(null, 'd.document_name');
        $config['column_search'] = array('d.document_name','d.document_link','d.remark','u.user_name');
        $config['order'] = array('d.document_name' => 'desc');

        $config['wheres'][] = array('where_str' => "(d.company_id='".$this->logged_in_id."' OR d.created_by='".$this->logged_in_id."')");

        if(!empty($post_data['company_id'])) {
            $config['wheres'][] = "d.company_id='".$this->logged_in_id."'";
        }

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $isEdit = $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID, "edit");
        $isDelete = $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID, "delete");
        foreach ($list as $doc_row) {
            $row = array();
            $action = '';
            if($isEdit) {
                $action .= '<form id="edit_' . $doc_row->document_id . '" method="post" action="' . base_url() . 'user/documents" class="pull-left">
                        <input type="hidden" name="id" id="id" value="' . $doc_row->document_id . '">
                        <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $doc_row->document_id . '\').submit();" title="Edit Company"><i class="fa fa-edit"></i></a>
                        </form>';   
            }
            
            if($isDelete) {
                $action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $doc_row->document_id) . '"><i class="fa fa-trash"></i></a>';  
            }
            
            $row[] = $action;
            $row[] = $doc_row->company_name;
            $row[] = $doc_row->document_name;
            $row[] = '<a href="'.$doc_row->document_link.'" target="_blank">View Document</a>';
            $row[] = !empty($doc_row->remark)?nl2br($doc_row->remark):'';
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

    function save_document(){
        $return = array();
        $post_data = $this->input->post();
        $data['company_id'] = $post_data['company_id'];
        $data['document_name'] = $post_data['document_name'];
        $data['document_link'] = $post_data['document_link'];
        $data['remark'] = $post_data['remark'];

        if(isset($post_data['id']) && !empty($post_data['id'])){
            $data['updated_at'] = $this->now_time;
            $data['updated_by'] = $this->logged_in_id;
            $data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $where_array['document_id'] = $post_data['id'];
            $result = $this->crud->update('documents', $data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
            }
        }else{
            $data['created_at'] = $this->now_time;
            $data['created_by'] = $this->logged_in_id;
            $data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('documents',$data);
            if($result){
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }
}
