<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Contra extends CI_Controller {

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

    function contra($transaction_id = '') {
        $data = array();
        if (isset($transaction_id) && !empty($transaction_id)) {
            if($this->applib->have_access_role(MODULE_CONTRA_ID,"edit")) {
                $transaction_data = $this->crud->get_row_by_id('transaction_entry', array('transaction_id' => $transaction_id));
                $transaction_data = $transaction_data[0];
                $data['transaction_data'] = $transaction_data;
                set_page('contra/contra', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_CONTRA_ID,"add")) {
                set_page('contra/contra', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }            
        }
    }
    
    function contra_list() {
        if($this->applib->have_access_role(MODULE_CONTRA_ID,"view")) {
            $data = array();
            set_page('contra/contra_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    } 
    
    function save_contra() {
        $post_data = $this->input->post();
        //echo '<pre>'; print_r($post_data); exit;
        if (isset($post_data['transaction_id']) && !empty($post_data['transaction_id'])) {
            $post_data['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $post_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $where_array['transaction_id'] = $post_data['transaction_id'];
            $result = $this->crud->update('transaction_entry', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Transaction Updated Successfully');
            }
        } else {
            $contra = $this->crud->get_max_number_from_table('transaction_entry','contra_no');
            $contra_no = 1;
            if($contra->contra_no > 0){
                $contra_no = $contra->contra_no + 1;
            }
            $post_data['contra_no'] = $contra_no;
            $post_data['transaction_type'] = '3';
            $post_data['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('transaction_entry', $post_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Transaction Added Successfully');
            }
        }
        //echo '<pre>';print_r($data);exit;
        print json_encode($return);
        exit;
    }

    function contra_datatable() {
        $config['table'] = 'transaction_entry t';
        $config['select'] = 't.*,a.account_name as from_bank_cash,aa.account_name as to_bank_cash';        
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.from_account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = t.to_account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 't.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 't.transaction_type', 'column_value' => '3');
        $config['column_order'] = array(null, 't.transaction_date', 't.from_account_id','t.to_account_id', 't.amount');
        $config['column_search'] = array('DATE_FORMAT(t.transaction_date,"%d-%m-%Y")','t.from_account_id','t.to_account_id', 't.amount');
        $config['order'] = array('t.transaction_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        
        $isEdit = $this->applib->have_access_role(MODULE_CONTRA_ID, "edit");
        $isDelete = $this->applib->have_access_role(MODULE_CONTRA_ID, "delete");

        $data = array();
        foreach ($list as $transaction) {
            $row = array();
            $action = '';

            if($isEdit) {
                $action .= '<a href="' . base_url("contra/contra/" . $transaction->transaction_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }

            if($isDelete) {
                $action .= '<a href="javascript:void(0);" class="delete_transaction" data-href="' . base_url('transaction/delete_transaction/' . $transaction->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }

            $row[] = $action;
            $row[] = $transaction->contra_no;
            $row[] = (!empty(strtotime($transaction->transaction_date))) ? date('d-m-Y', strtotime($transaction->transaction_date)) : '';
            $row[] = $transaction->from_bank_cash;
            $row[] = $transaction->to_bank_cash;
            $row[] = $transaction->amount;
            $row[] = $transaction->note;
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

    function delete_transaction($id = '') {
        $where_array = array('transaction_id' => $id);
        $this->crud->delete('transaction_entry', $where_array);
    }
    
   

}
