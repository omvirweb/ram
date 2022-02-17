<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Account extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;

    function __construct() {
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

    function account() {
        //print_r($_REQUEST);exit;
        if (!empty($_POST['account_id']) && isset($_POST['account_id'])) {
            if ($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID, "edit")){
                $result = $this->crud->get_data_row_by_id('account', 'account_id', $_POST['account_id']);
                $data = array(
                    'account_id' => $result->account_id,
                    'account_name' => $result->account_name,
                    'account_email_ids' => $result->account_email_ids,
                    'account_address' => $result->account_address,
                    'account_state' => $result->account_state,
                    'account_city' => $result->account_city,
                    'account_postal_code' => $result->account_postal_code,
                    'account_phone' => $result->account_phone,
                    'account_contect_person_name' => $result->account_contect_person_name,
                    'account_group_id' => $result->account_group_id,
                    'account_gst_no' => $result->account_gst_no,
                    'account_aadhaar' => $result->account_aadhaar,
                    'account_pan' => $result->account_pan,
                    'opening_balance' => $result->opening_balance,
                    'credit_debit' => $result->credit_debit,
                    'consider_in_pl' => $result->consider_in_pl,
                    'is_bill_wise' => $result->is_bill_wise,
                );
                set_page('account/account', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if ($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID, "add")){
                $data = array();
                $data['is_bill_wise_company'] = $this->crud->get_val_by_id('user',$this->logged_in_id,'user_id','is_bill_wise');
                set_page('account/account', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }
        
    }

    function save_account() {
        $return = '';
        $post_data = $this->input->post();
        //echo '<pre>';print_r($post_data);exit;
        $account_id = isset($post_data['account_id']) ? $post_data['account_id'] : 0;
        $post_data['account_state'] = isset($post_data['account_state']) ? $post_data['account_state'] : null;
        $post_data['account_city'] = isset($post_data['account_city']) ? $post_data['account_city'] : null;
        $post_data['account_group_id'] = isset($post_data['account_group_id']) ? $post_data['account_group_id'] : null;
        $post_data['account_email_ids'] = isset($post_data['account_email_ids']) ? $post_data['account_email_ids'] : null;
        $post_data['account_phone'] = isset($post_data['account_phone']) ? $post_data['account_phone'] : null;
        $post_data['account_address'] = isset($post_data['account_address']) ? $post_data['account_address'] : null;
        $post_data['account_postal_code'] = isset($post_data['account_postal_code']) ? $post_data['account_postal_code'] : null;
        $post_data['account_contect_person_name'] = isset($post_data['account_contect_person_name']) ? $post_data['account_contect_person_name'] : null;
        $post_data['account_gst_no'] = isset($post_data['account_gst_no']) ? $post_data['account_gst_no'] : null;
        $post_data['account_aadhaar'] = isset($post_data['account_aadhaar']) ? $post_data['account_aadhaar'] : null;
        $post_data['account_pan'] = isset($post_data['account_pan']) ? $post_data['account_pan'] : null;
        $post_data['opening_balance'] = isset($post_data['opening_balance']) ? $post_data['opening_balance'] : null;
        $post_data['credit_debit'] = isset($post_data['credit_debit']) ? $post_data['credit_debit'] : null;
        $post_data['is_bill_wise'] = isset($post_data['is_bill_wise']) ? 1 : 0;
        $post_data['consider_in_pl'] = 1;
        if (isset($post_data['account_id']) && !empty($post_data['account_id'])) {
            $ac_id = $this->crud->get_id_by_val_count('account', 'account_id', array('account_name' => trim($post_data['account_name']), 'account_id !=' => $post_data['account_id'], 'created_by' => $this->logged_in_id));
            if (!empty($ac_id)) {
                $return['error'] = "accountExist";
                print json_encode($return);
                exit;
            }
            /*if (isset($post_data['account_email_ids']) && !empty($post_data['account_email_ids'])) {
                $mail_val = $this->crud->get_id_by_val_count('account', 'account_id', array('account_email_ids' => trim($post_data['account_email_ids']), 'account_id !=' => $post_data['account_id'], 'created_by' => $this->logged_in_id));
                if (!empty($mail_val)) {
                    $return['error'] = "emailExist";
                    print json_encode($return);
                    exit;
                }
            }*/
        } else {
            $ac_id = $this->crud->get_id_by_val_count('account', 'account_id', array('account_name' => trim($post_data['account_name']), 'created_by' => $this->logged_in_id));
            if (!empty($ac_id)) {
                $return['error'] = "accountExist";
                print json_encode($return);
                exit;
            }
            /*if (isset($post_data['account_email_ids']) && !empty($post_data['account_email_ids'])) {
                $mail_val = $this->crud->get_id_by_val_count('account', 'account_id', array('account_email_ids' => trim($post_data['account_email_ids']), 'created_by' => $this->logged_in_id));
                if (!empty($mail_val)) {
                    $return['error'] = "emailExist";
                    print json_encode($return);
                    exit;
                }
            }*/
        }
        $account_email_ids = isset($post_data['account_email_ids']) ? trim($post_data['account_email_ids']) : '';
        // get emails
        $temp = nl2br($account_email_ids);
        $emails = explode(",", $temp);

        $email_status = 1;
        $email_msg = "";

        $account_email_ids = array();
        // multiple email validation
        if (is_array($emails) && count($emails) > 0) {
            foreach ($emails as $email) {
                if (trim($email) != "") {
                    $email = trim($email);
                    $email = trim($email,',');
                    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        $email_msg .= "$email is not valid email.&nbsp;";
                        $email_status = 0;
                    }
                    $this->db->select('*');
                    $this->db->from('account');
                    $this->db->where('account_id !=', $account_id);
                    $this->db->where('created_by', $this->logged_in_id);
                    $this->db->like('account_email_ids', $email);
                    $res = $this->db->get();
                    $rows = $res->num_rows();
                    if ($rows > 0) {
                        $result = $res->result();
                        $email_msg .= "$email email already exist.&nbsp; <br/> Original Account : " . $result[0]->account_name;
                        $email_status = 0;
                    } else {
                        $account_email_ids[] = $email;
                    }
                }
            }
        }
        if ($email_status == 0) {
            $return['error'] = "email_error";
            $return['msg'] = $email_msg;
            echo json_encode($return);
            exit;
        }
        $account_email_ids = implode(',', $account_email_ids);
        if (isset($post_data['account_id']) && !empty($post_data['account_id']) && $post_data['account_id'] != 0) {
            $post_data['account_email_ids'] = $account_email_ids;
            if (empty($post_data['account_email_ids'])) {
                $result = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['account_id']);
                $post_data['account_email_ids'] = $result->account_email_ids;
            }
            $where_array['account_id'] = $post_data['account_id'];
            $result = $this->crud->update('account', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Account Updated Successfully');
                $last_query_id = $post_data['account_id'];
            } else {
                $return['error'] = "errorUpdated";
            }
        } else {
            $post_data['account_email_ids'] = $account_email_ids;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('account', $post_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Account Added Successfully');
                $last_query_id = $this->db->insert_id();
            } else {
                $return['error'] = "errorAdded";
            }
        }
        //echo '<pre>';print_r($data);exit;
        print json_encode($return);
        exit;
    }

    function account_list() {
        if ($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID, "add")){
            $data = array();
            set_page('account/account_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function account_datatable() {
        $config['table'] = 'account a';
        $config['select'] = 'a.account_id, a.account_name, a.account_email_ids, a.account_phone, ag.account_group_name as ag_name,a.opening_balance,a.credit_debit';
        $config['column_order'] = array(null, 'a.account_name', 'a.account_email_ids', 'a.account_phone', 'ag.account_group_name');
        $config['column_search'] = array('a.account_name', 'a.account_email_ids', 'a.account_phone', 'ag.account_group_name');
        $config['wheres'][] = array('column_name' => 'a.created_by', 'column_value' => $this->logged_in_id);
        $config['joins'][] = array('join_table' => 'account_group ag', 'join_by' => 'ag.account_group_id = a.account_group_id', 'join_type' => 'left');
        $config['order'] = array('a.account_name' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        //echo $this->db->last_query();exit;
        $data = array();
        foreach ($list as $accounts) {
            $row = array();
            $action = '';
            $action .= ' <form id="edit_' . $accounts->account_id . '" method="post" action="' . base_url() . 'account/account" style="width: 25px; display: initial;" >
                            <input type="hidden" name="account_id" id="account_id" value="' . $accounts->account_id . '">
                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $accounts->account_id . '\').submit();" title="Edit Account"><i class="fa fa-edit"></i></a>
                        </form> ';
            $action .= ' | <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete_account/' . $accounts->account_id) . '"><i class="fa fa-trash"></i></a>';
            $row[] = $action;
            $row[] = $accounts->account_name;
            $p_inc = 1;
            $email_id_arr = explode(',', $accounts->account_email_ids);
            $email_ids = '';
            foreach ($email_id_arr as $email_id) {
                if (!empty(trim($email_id))) {
                    $email_ids .= $email_id . ', ';
                    if ($p_inc % 2 == 0) {
                        $email_ids .= '<br />';
                    }
                    $p_inc++;
                }
            }
            $row[] = $email_ids;
            $row[] = $accounts->account_phone;
            $row[] = $accounts->ag_name;
            $row[] = $accounts->opening_balance;
            if($accounts->credit_debit == 1){
                $accounts->credit_debit = 'Credit';
            } elseif ($accounts->credit_debit == 2) {
                $accounts->credit_debit = 'Debit';
            }
            $row[] = $accounts->credit_debit;
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

    function get_state_code() {
        $state_code = $this->crud->get_column_value_by_id('state', 'state_code', array('state_id' => $_POST['account_state']));
        echo json_encode($state_code);
        exit;
    }
    
    function get_account_state_id($account_id) {
        $state_code = $this->crud->get_column_value_by_id('account', 'account_state', array('account_id' => $account_id));
        $state_code_login = $this->crud->get_column_value_by_id('user', 'state', array('user_id' => $this->logged_in_id));
        $tax_type = 1;
        if(!empty($state_code) && !empty($state_code_login)){
            if($state_code == $state_code_login){
                $tax_type = 1;
            } else {
                $tax_type = 2;
            }
        }
        echo json_encode($tax_type);
        exit;
    }
}
