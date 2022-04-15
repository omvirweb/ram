<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Transaction extends CI_Controller {

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
        $this->prefix = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['prefix'];
        $this->now_time = date('Y-m-d H:i:s');
    }

    function payment($transaction_id = '') {
        $data = array();
        $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'payment_date'));
        if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
            $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
        } else {
            $data['transaction_date'] = date('d-m-Y');
        }

        if (isset($transaction_id) && !empty($transaction_id)) {
            if($this->applib->have_access_role(MODULE_PAYMENT_ID,"edit")) {
                $transaction_data = $this->crud->get_row_by_id('transaction_entry', array('transaction_id' => $transaction_id));
                $transaction_data = $transaction_data[0];
                $data['transaction_data'] = $transaction_data;
                set_page('transaction/transaction', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) {
                set_page('transaction/transaction', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }            
        }
    }
    
    function gst_payment($transaction_id = '') {
        $data = array();
        $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'payment_date'));
        if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
            $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
        } else {
            $data['transaction_date'] = date('d-m-Y');
        }

        if (isset($transaction_id) && !empty($transaction_id)) {
            if($this->applib->have_access_role(MODULE_PAYMENT_ID,"edit")) {
                $transaction_data = $this->crud->get_row_by_id('transaction_entry', array('transaction_id' => $transaction_id));
                $transaction_data = $transaction_data[0];
                $data['transaction_data'] = $transaction_data;
                set_page('transaction/transaction', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) {
                set_page('transaction/gst_payment', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }            
        }
    }

    function payment_print($transaction_id) {
        $data = array();
        if (isset($transaction_id) && !empty($transaction_id)) {
            $this->db->select('t.*,a.account_name,aa.account_name as cash_bank_acc');
            $this->db->from('transaction_entry t');
            $this->db->join('account a','a.account_id = t.to_account_id');
            $this->db->join('account aa','aa.account_id = t.from_account_id');
            $this->db->where('t.transaction_id',$transaction_id);
            $this->db->limit(1);
            $query = $this->db->get();
            $transaction_row = array();
            if($query->num_rows() > 0) {
                $transaction_row = $query->row();
            }
            $data['company_row'] = $this->crud->get_data_row_by_id('user', 'user_id',$this->logged_in_id);
            $data['transaction_row'] = $transaction_row;
            $html = $this->load->view('transaction/payment_print', $data, true);
            //echo $html;exit();
            $pdfFilePath = "CashPayment.pdf";
            $this->load->library('m_pdf');
            $this->m_pdf->pdf->AddPage('','', '', '', '',
                          15, // margin_left
                          15, // margin right
                          15, // margin top
                          15, // margin bottom
                          5, // margin header
                          5);
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
            exit();
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');            
        }
    }

    function receipt_print($transaction_id) {
        $data = array();
        if (isset($transaction_id) && !empty($transaction_id)) {
            $this->db->select('t.*,a.account_name,aa.account_name as cash_bank_acc,c.city_name');
            $this->db->from('transaction_entry t');
            $this->db->join('account a','a.account_id = t.from_account_id');
            $this->db->join('city c','c.city_id = a.account_city','left');
            $this->db->join('account aa','aa.account_id = t.to_account_id');
            $this->db->where('t.transaction_id',$transaction_id);
            $this->db->limit(1);
            $query = $this->db->get();
            $transaction_row = array();
            if($query->num_rows() > 0) {
                $transaction_row = $query->row();
            }
            $data['company_row'] = $this->crud->get_data_row_by_id('user', 'user_id',$this->logged_in_id);
            $data['transaction_row'] = $transaction_row;
            $html = $this->load->view('transaction/receipt_print', $data, true);
            //echo $html;exit();
            $pdfFilePath = "CashReceipt.pdf";
            $this->load->library('m_pdf');
            $this->m_pdf->pdf->AddPage('','', '', '', '',
                          15, // margin_left
                          15, // margin right
                          10, // margin top
                          15, // margin bottom
                          5, // margin header
                          5);
            $this->m_pdf->pdf->WriteHTML($html);
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
            exit();
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');            
        }
    }

    function print_multiple_transaction()
    {
        $post_data = $this->input->post();
        $transaction_type = $post_data['transaction_type'];
        $transaction_ids = $post_data['transaction_ids'];

        if(!empty($transaction_ids) && $transaction_type == 1) { //Payment
            $this->db->select('t.*,a.account_name,aa.account_name as cash_bank_acc');
            $this->db->from('transaction_entry t');
            $this->db->join('account a','a.account_id = t.to_account_id');
            $this->db->join('account aa','aa.account_id = t.from_account_id');
            $this->db->where_in('t.transaction_id',$transaction_ids);
            $this->db->order_by('t.transaction_date','desc');
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                $company_row = $this->crud->get_data_row_by_id('user', 'user_id',$this->logged_in_id);
                $this->load->library('m_pdf');
                foreach ($query->result() as $key => $transaction_row) {
                    $data = array();
                    $data['company_row'] = $company_row;
                    $data['transaction_row'] = $transaction_row;
                    $html = $this->load->view('transaction/payment_print', $data, true);
                    $this->m_pdf->pdf->AddPage('','', '', '', '',
                                  15, // margin_left
                                  15, // margin right
                                  10, // margin top
                                  15, // margin bottom
                                  5, // margin header
                                  5);
                    $this->m_pdf->pdf->WriteHTML($html);
                }
                $pdfFilePath = "CashPayment.pdf";
                $this->m_pdf->pdf->Output($pdfFilePath, 'I');
                exit();
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }

        } elseif (!empty($transaction_ids) && $transaction_type == 2) { //Receipt
            $this->db->select('t.*,a.account_name,aa.account_name as cash_bank_acc,c.city_name');
            $this->db->from('transaction_entry t');
            $this->db->join('account a','a.account_id = t.from_account_id');
            $this->db->join('city c','c.city_id = a.account_city','left');
            $this->db->join('account aa','aa.account_id = t.to_account_id');
            $this->db->where_in('t.transaction_id',$transaction_ids);
            $this->db->order_by('t.transaction_date','desc');
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                $company_row = $this->crud->get_data_row_by_id('user', 'user_id',$this->logged_in_id);
                $this->load->library('m_pdf');
                foreach ($query->result() as $key => $transaction_row) {
                    $data = array();
                    $data['company_row'] = $company_row;
                    $data['transaction_row'] = $transaction_row;
                    $html = $this->load->view('transaction/receipt_print', $data, true);
                    $this->m_pdf->pdf->AddPage('','', '', '', '',
                                  15, // margin_left
                                  15, // margin right
                                  10, // margin top
                                  15, // margin bottom
                                  5, // margin header
                                  5);
                    $this->m_pdf->pdf->WriteHTML($html);
                }
                $pdfFilePath = "CashReceipt.pdf";
                $this->m_pdf->pdf->Output($pdfFilePath, 'I');
                exit();
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function payment_list() {
        if($this->applib->have_access_role(MODULE_PAYMENT_ID,"view")) {
            $data = array();
            $data['transaction_type'] = 1;
            set_page('transaction/transaction_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
        
    } 
    
    function receipt($transaction_id = '') {
        $data = array();
        $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'receipt_date'));
        if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
            $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
        } else {
            $data['transaction_date'] = date('d-m-Y');
        }
        if (isset($transaction_id) && !empty($transaction_id)) {
            if($this->applib->have_access_role(MODULE_RECEIPT_ID,"edit")) {
                $transaction_data = $this->crud->get_row_by_id('transaction_entry', array('transaction_id' => $transaction_id));
                $transaction_data = $transaction_data[0];
                $data['transaction_data'] = $transaction_data;
                set_page('transaction/transaction', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_RECEIPT_ID,"add")) {
                set_page('transaction/transaction', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }            
        }
    }
    function receipt_list() {
        if($this->applib->have_access_role(MODULE_RECEIPT_ID,"view")) {
            $data = array();
            $data['transaction_type'] = 2;
            set_page('transaction/transaction_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function save_transaction() {
        $post_data = $this->input->post();
//        echo "<pre>"; print_r($post_data); exit;
        $checked_items = array();
        $checked_amt_data = array();
        if(isset($post_data['invoice_amount'])){
            $checked_amt_data = $post_data['invoice_amount'];
            unset($post_data['invoice_amount']);
        }
        if(isset($post_data['checked_items'])){
            $checked_items = $post_data['checked_items'];
            unset($post_data['checked_items']);
        }
        if (isset($post_data['transaction_id']) && !empty($post_data['transaction_id'])) {
            $old_acc_id = $post_data['old_account_id'];
            unset($post_data['old_account_id']);
            $post_data['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $post_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $where_array['transaction_id'] = $post_data['transaction_id'];
            $result = $this->crud->update('transaction_entry', $post_data, $where_array);
            if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise'] == '1'){
                if(empty($checked_items)){
                    $this->crud->delete('invoice_paid_details', array('transaction_id' => $post_data['transaction_id']));
                } else {
                    if($post_data['from_account_id'] != $old_acc_id){
                        $this->crud->delete('invoice_paid_details', array('transaction_id' => $post_data['transaction_id']));
                        foreach ($checked_items as $invoice_id => $p_item){
                            $paid_data = array();
                            $paid_data['transaction_id'] = $post_data['transaction_id'];
                            $paid_data['invoice_id'] = $invoice_id;
                            $paid_data['account_id'] = $post_data['from_account_id'];
                            $paid_data['created_at'] = $this->now_time;
                            $paid_data['created_by'] = $this->logged_in_id;
                            $paid_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                            $this->crud->insert('invoice_paid_details', $paid_data);
                        }
                    } else {
                        $invoice_old_data = $this->crud->getFromSQL("SELECT sales_invoice_id FROM sales_invoice WHERE account_id = " . $post_data['from_account_id'] . " AND sales_invoice_id IN (SELECT invoice_id FROM invoice_paid_details WHERE account_id = " . $post_data['from_account_id'] . " AND transaction_id = ".$post_data['transaction_id'].") ");
                        $old_array = array();
                        if(!empty($invoice_old_data)){
                            foreach ($invoice_old_data as $invoice_old){
                                $old_array[] = $invoice_old->sales_invoice_id;
                            }
                        }
                        foreach ($checked_items as $invoice_id => $p_item) {
                            if(in_array($invoice_id, $old_array)){
                                if (($key = array_search($invoice_id, $old_array)) !== false) {
                                    unset($old_array[$key]);
                                }
                            } else {
                                $paid_data = array();
                                $paid_data['transaction_id'] = $post_data['transaction_id'];
                                $paid_data['invoice_id'] = $invoice_id;
                                $paid_data['account_id'] = $post_data['from_account_id'];
                                $paid_data['created_at'] = $this->now_time;
                                $paid_data['created_by'] = $this->logged_in_id;
                                $paid_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                $this->crud->insert('invoice_paid_details', $paid_data);
                            }
                        }
                        if(!empty($old_array)){
                            foreach ($old_array as $old){
                                $this->crud->delete('invoice_paid_details', array('invoice_id' => $old));
                            }
                        }
                    }
                }
            }
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Transaction Updated Successfully');
            }
        } else {
            $post_data['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('transaction_entry', $post_data);
            $last_tr_id = $this->db->insert_id();
            // echo $this->db->last_query(); exit;

            if($post_data['transaction_type'] == 1) { //payment
                $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'payment_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $post_data['transaction_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'payment_date',"setting_value" => $post_data['transaction_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
                }
            } else { //receipt
                $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'receipt_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $post_data['transaction_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'receipt_date',"setting_value" => $post_data['transaction_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
                }
            }

            if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise'] == '1'){
                if(isset($checked_amt_data) && !empty($checked_amt_data)){
                    foreach ($checked_amt_data as $invoice_id => $p_item){
                        if($p_item > 0){
                            $paid_data = array();
                            $paid_data['transaction_id'] = $last_tr_id;
                            $paid_data['invoice_id'] = $invoice_id;
                            $paid_data['paid_amount'] = $p_item;
                            $paid_data['account_id'] = $post_data['from_account_id'];
                            $paid_data['created_at'] = $this->now_time;
                            $paid_data['created_by'] = $this->logged_in_id;
                            $paid_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                            $this->crud->insert('invoice_paid_details', $paid_data);
                        }
                    }
                }
            }
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
    
    function save_gst_payment() {
        $return = array();
        $post_data = $this->input->post();
//        echo "<pre>"; print_r($post_data); exit;
        if (isset($post_data['transaction_id']) && !empty($post_data['transaction_id'])) {
            
        } else {
            $in_arr = array();
            $in_arr['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
            $in_arr['from_account_id'] = $post_data['from_account_id'];
            $in_arr['to_account_id'] = $post_data['to_account_id'];
            $in_arr['receipt_no'] = $post_data['receipt_no'];
            $in_arr['amount'] = $post_data['amount'];
            $in_arr['note'] = $post_data['note'];
            $in_arr['transaction_type'] = '1';
            $in_arr['created_at'] = $this->now_time;
            $in_arr['created_by'] = $this->logged_in_id;
            $in_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('transaction_entry', $in_arr);
//            echo $this->db->last_query(); exit;
            $last_tr_id = $this->db->insert_id();
            $this->crud->update('transaction_entry', array('relation_id' => $last_tr_id), array('transaction_id' => $last_tr_id));
            $to_acc_type = $this->crud->get_id_by_val('account', 'account_type', 'account_id', $post_data['to_account_id']);
            if(!empty($post_data['interest'])){
                $in_arr = array();
                if($to_acc_type == CGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => CGST_INTEREST_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == SGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => SGST_INTEREST_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == IGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => IGST_INTEREST_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == UTGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => UTGST_INTEREST_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                }
                $in_arr['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
                $in_arr['from_account_id'] = $post_data['from_account_id'];
                $in_arr['receipt_no'] = $post_data['receipt_no'];
                $in_arr['amount'] = $post_data['interest'];
                $in_arr['note'] = $post_data['note'];
                $in_arr['transaction_type'] = '1';
                $in_arr['relation_id'] = $last_tr_id;
                $in_arr['created_at'] = $this->now_time;
                $in_arr['created_by'] = $this->logged_in_id;
                $in_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                $result = $this->crud->insert('transaction_entry', $in_arr);
            }
            
            if(!empty($post_data['penalty'])){
                $in_arr = array();
                if($post_data['to_account_id'] == CGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => CGST_PENALTY_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == SGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => SGST_PENALTY_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == IGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => IGST_PENALTY_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == UTGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => UTGST_PENALTY_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                }
                $in_arr['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
                $in_arr['from_account_id'] = $post_data['from_account_id'];
                $in_arr['receipt_no'] = $post_data['receipt_no'];
                $in_arr['amount'] = $post_data['penalty'];
                $in_arr['note'] = $post_data['note'];
                $in_arr['transaction_type'] = '1';
                $in_arr['relation_id'] = $last_tr_id;
                $in_arr['created_at'] = $this->now_time;
                $in_arr['created_by'] = $this->logged_in_id;
                $in_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                $result = $this->crud->insert('transaction_entry', $in_arr);
            }
            
            if(!empty($post_data['fees'])){
                $in_arr = array();
                if($post_data['to_account_id'] == CGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => CGST_FEES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == SGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => SGST_FEES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == IGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => IGST_FEES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == UTGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => UTGST_FEES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                }
                $in_arr['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
                $in_arr['from_account_id'] = $post_data['from_account_id'];
                $in_arr['receipt_no'] = $post_data['receipt_no'];
                $in_arr['amount'] = $post_data['penalty'];
                $in_arr['note'] = $post_data['note'];
                $in_arr['transaction_type'] = '1';
                $in_arr['relation_id'] = $last_tr_id;
                $in_arr['created_at'] = $this->now_time;
                $in_arr['created_by'] = $this->logged_in_id;
                $in_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                $result = $this->crud->insert('transaction_entry', $in_arr);
            }
            
            if(!empty($post_data['other_charges'])){
                $in_arr = array();
                if($post_data['to_account_id'] == CGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => CGST_OTHER_CHARGES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == SGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => IGST_OTHER_CHARGES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == IGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => IGST_OTHER_CHARGES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                } else if($post_data['to_account_id'] == UTGST_TYPE_ID){
                    $to_acc_id = $this->crud->get_row_by_id('account', array('account_type' => IGST_OTHER_CHARGES_TYPE_ID, 'created_by' => $this->logged_in_id));
                    $in_arr['to_account_id'] = $to_acc_id[0]->account_id;
                }
                $in_arr['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
                $in_arr['from_account_id'] = $post_data['from_account_id'];
                $in_arr['receipt_no'] = $post_data['receipt_no'];
                $in_arr['amount'] = $post_data['penalty'];
                $in_arr['note'] = $post_data['note'];
                $in_arr['transaction_type'] = '1';
                $in_arr['relation_id'] = $last_tr_id;
                $in_arr['created_at'] = $this->now_time;
                $in_arr['created_by'] = $this->logged_in_id;
                $in_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                $result = $this->crud->insert('transaction_entry', $in_arr);
            }

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

    function payment_datatable() {
        $config['table'] = 'transaction_entry t';
        $config['select'] = 't.*,a.account_name,aa.account_name as cash_bank_acc';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.to_account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = t.from_account_id', 'join_type' => 'left');
        $config['column_order'] = array(null, 't.transaction_date', 't.amount');
        $config['column_search'] = array('DATE_FORMAT(t.transaction_date,"%d-%m-%Y")', 't.amount');
        $config['wheres'][] = array('column_name' => 't.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 't.transaction_type', 'column_value' => '1');
        if(isset($_POST['site_id']) && $_POST['site_id'] != ''){
            $config['wheres'][] = array('column_name' => 't.site_id', 'column_value' => $_POST['site_id']);
        }
        $config['order'] = array('t.transaction_date' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        
        $isEdit = $this->applib->have_access_role(MODULE_PAYMENT_ID, "edit");
        $isDelete = $this->applib->have_access_role(MODULE_PAYMENT_ID, "delete");

        $data = array();
        foreach ($list as $transaction) {
            $row = array();
            $action = '';
            if($isEdit) {
                $action .= '<a href="' . base_url("transaction/payment/" . $transaction->transaction_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';    
            }

            if($isDelete) {
                $action .= ' <a href="javascript:void(0);" class="delete_transaction" data-href="' . base_url('transaction/delete_transaction/' . $transaction->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }

            $action .= '<a href="' . base_url("transaction/payment_print/" . $transaction->transaction_id) . '" target="_blank" title="Payment Print" class="btn-info btn-xs"><span class="fa fa-print"></span></a>';    

            $action .= ' &nbsp; <input type="checkbox" name="transaction_ids[]" value="'.$transaction->transaction_id.'" style="height:17px;width: 17px;">';

            $row[] = $action;
            $row[] = (!empty(strtotime($transaction->transaction_date))) ? date('d-m-Y', strtotime($transaction->transaction_date)) : '';
            $row[] = $transaction->account_name;
            $row[] = $transaction->cash_bank_acc;
            $row[] = $transaction->amount;
            $row[] = $transaction->note;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw'])?$_POST['draw']:0,
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function receipt_datatable() {
        $config['table'] = 'transaction_entry t';
        $config['select'] = 't.*,a.account_name,aa.account_name as cash_bank_acc';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.from_account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = t.to_account_id', 'join_type' => 'left');
        $config['column_order'] = array(null, 't.transaction_date','t.amount');
        $config['column_search'] = array('DATE_FORMAT(t.transaction_date,"%d-%m-%Y")','t.amount');
        $config['wheres'][] = array('column_name' => 't.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 't.transaction_type', 'column_value' => '2');
        if(isset($_POST['site_id']) && $_POST['site_id'] != ''){
            $config['wheres'][] = array('column_name' => 't.site_id', 'column_value' => $_POST['site_id']);
        }
        $config['order'] = array('t.transaction_date' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
       
        $isEdit = $this->applib->have_access_role(MODULE_RECEIPT_ID, "edit");
        $isDelete = $this->applib->have_access_role(MODULE_RECEIPT_ID, "delete");

        $data = array();
        foreach ($list as $transaction) {
            $row = array();
            $action = '';
            if($isEdit) {
                $action .= '<a href="' . base_url("transaction/receipt/" . $transaction->transaction_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';    
            }
            if($isDelete) {
                $action .= '<a href="javascript:void(0);" class="delete_transaction" data-href="' . base_url('transaction/delete_receipt_transaction/' . $transaction->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';    
            }

            $action .= '<a href="' . base_url("transaction/receipt_print/" . $transaction->transaction_id) . '" target="_blank" title="Payment Print" class="btn-info btn-xs"><span class="fa fa-print"></span></a>';

            $action .= ' &nbsp; <input type="checkbox" name="transaction_ids[]" value="'.$transaction->transaction_id.'" style="height:17px;width: 17px;">';

            $row[] = $action;
            $row[] = (!empty(strtotime($transaction->transaction_date))) ? date('d-m-Y', strtotime($transaction->transaction_date)) : '';
            $row[] = $transaction->account_name;
            $row[] = $transaction->cash_bank_acc;
            $row[] = $transaction->amount;
            $row[] = $transaction->note;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw'])?$_POST['draw']:0,
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
    
    function delete_receipt_transaction($id = '') {
        $where_array = array('transaction_id' => $id);
        $this->crud->delete('invoice_paid_details', array('transaction_id' => $id));
        $this->crud->delete('transaction_entry', $where_array);
    }
    
    function get_invoice_data(){
        $data = array();
//        echo "<pre>"; print_r($_POST); exit;
        $rec_amt = isset($_POST['rec_amount']) ? $_POST['rec_amount'] : '0';
        if(isset($_POST['account_id']) && !empty($_POST['account_id'])){
            if(isset($_POST['tr_id']) && !empty($_POST['tr_id'])){
                $invoice_data = $this->crud->getFromSQL("SELECT sales_invoice_id,sales_invoice_no,DATE_FORMAT(sales_invoice_date,'%d-%m-%Y') AS sales_invoice_date,amount_total FROM sales_invoice WHERE account_id = " . $_POST['account_id'] . " AND sales_invoice_id NOT IN (SELECT invoice_id FROM invoice_paid_details WHERE account_id = " . $_POST['account_id'] . ") ");
                $invoice_paid_data = $this->crud->getFromSQL("SELECT 'old_data',sales_invoice_id,sales_invoice_no,DATE_FORMAT(sales_invoice_date,'%d-%m-%Y') AS sales_invoice_date,amount_total FROM sales_invoice WHERE account_id = " . $_POST['account_id'] . " AND sales_invoice_id IN (SELECT invoice_id FROM invoice_paid_details WHERE account_id = " . $_POST['account_id'] . " AND transaction_id = ".$_POST['tr_id'].") ");
                $invoice_data = array_merge($invoice_paid_data,$invoice_data);
                if (!empty($invoice_data)) {
                    $data['invoice_data'] = $invoice_data;
                }
            } else {
                $invoice_data = $this->crud->getFromSQL("SELECT sales_invoice_id,sales_invoice_no,DATE_FORMAT(sales_invoice_date,'%d-%m-%Y') AS sales_invoice_date,amount_total FROM sales_invoice WHERE account_id = ".$_POST['account_id']." ");
                $invoice_paid_data = $this->crud->getFromSQL("SELECT invoice_id,SUM(paid_amount) as paid_amount FROM invoice_paid_details WHERE invoice_id IN (SELECT sales_invoice_id FROM sales_invoice WHERE account_id = ".$_POST['account_id'].") GROUP BY invoice_id ");
//                echo "<pre>"; print_r($invoice_paid_data); exit;
                if(!empty($invoice_data)){
                    foreach ($invoice_data as $key => $invoice){
                        $invoice_data[$key]->bill_amount = $invoice->amount_total;  
                        $invoice_data[$key]->paid_amount = 0;  
                        $invoice_data[$key]->pending_amount = $invoice->amount_total;  
                        if(!empty($invoice_paid_data)){
                            foreach ($invoice_paid_data as $key => $paid){
                                if($paid->invoice_id == $invoice->sales_invoice_id){
                                    $invoice_data[$key]->paid_amount = $paid->paid_amount;
                                    $invoice_data[$key]->pending_amount = $invoice->amount_total - $paid->paid_amount;
                                    if($invoice_data[$key]->pending_amount <= 0){
                                        unset($invoice_data[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                if(!empty($invoice_data)){
                    foreach ($invoice_data as $key => $invoice){
                        $new_amt = '';
                        if($rec_amt >= 0){
                            if($invoice_data[$key]->pending_amount >= $rec_amt){
                                $new_amt = $rec_amt;
                                $rec_amt = 0;
                            } else {
                                $new_amt = $invoice_data[$key]->pending_amount;
                                $rec_amt = $rec_amt - $invoice_data[$key]->pending_amount;
                            }
                        }
                        $invoice_data[$key]->new_amount = $new_amt;
                    }
                }
//                echo "<pre>"; print_r($invoice_data); exit;
                if(!empty($invoice_data)){
                    $data['invoice_data'] = $invoice_data;
                }
            }
        }
        echo json_encode($data);
        exit;
    }



    function sales_purchase_transaction($voucher_type, $order_id = '') {

        $data = array();
        $page_title = '';
        $invoice_id = 0;
        $sales_type=0;
        $page_title = "";
        $voucher_label="";
        $invoice_list_url="";
        if($voucher_type == "sales"){
            $sales_type=1;
        }elseif($voucher_type == "sales2"){
            $sales_type=2;
        }elseif($voucher_type == "sales3"){
            $sales_type=3;
        }elseif($voucher_type == "sales4"){
            $sales_type=4;
        }

        $data['transaction_date'] = date('d-m-Y');
        $line_item_fields_data = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 2 AND setting_value = 1');
        $data['line_item_fields_data'] = json_encode($line_item_fields_data);
       /*echo "<pre>"; print_r($line_item_fields_data); exit;*/
        if($voucher_type == "sales" || $voucher_type == "sales2" || $voucher_type == "sales3" || $voucher_type == "sales4") {
            if (isset($_POST['sales_invoice_id'])) {
                if(!($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['sales_invoice_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
            if($sales_type==1){
                $page_title = 'Sales - CTRL + F1';
                $voucher_label = 'Sales Invoice';
                $invoice_list_url = base_url('sales/invoice_list');
            }elseif($sales_type==2){
                $page_title = 'Sales2 - CTRL + F1';
                $voucher_label = 'Sales2 Invoice';
                $invoice_list_url = base_url('sales/invoice_list/2');
            }elseif($sales_type==3){
                $page_title = 'Sales3 - CTRL + F1';
                $voucher_label = 'Sales3 Invoice';
                $invoice_list_url = base_url('sales/invoice_list/3');
            }elseif($sales_type==4){
                $page_title = 'Sales4 - CTRL + F1';
                $voucher_label = 'Sales4 Invoice';
                $invoice_list_url = base_url('sales/invoice_list/4');
            }
            
            $module_id = MODULE_SALES_INVOICE_ID;
            $invoice_type = 2;
            $invoice_save_url = base_url('transaction/save_invoice');
            $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date'));
            if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
                $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
            }

        }elseif($voucher_type == "purchase") {
            if (isset($_POST['purchase_invoice_id'])) {
                if(!($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['purchase_invoice_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
            $page_title = 'Purchase';
            $voucher_label = 'Purchase Invoice';
            $invoice_type = 2;
            $module_id = MODULE_PURCHASE_INVOICE_ID;
            $invoice_list_url = base_url('purchase/invoice_list');
            $invoice_save_url = base_url('purchase/save_purchase_invoice');
        } elseif($voucher_type == "order") {
            if (isset($_POST['purchase_invoice_id'])) {
                if(!($this->applib->have_access_role(MODULE_ORDER_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['purchase_invoice_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_ORDER_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }

            $voucher_type = "purchase";
            $invoice_type = 1;
            $page_title = 'Order';
            $voucher_label = 'Order';
            $module_id = MODULE_ORDER_ID;
            $invoice_list_url = base_url('purchase/order_invoice_list');
            $invoice_save_url = base_url('purchase/save_purchase_invoice');            

        } elseif($voucher_type == "sales_order") {
            if (isset($_POST['purchase_invoice_id'])) {
                if(!($this->applib->have_access_role(MODULE_ORDER_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['purchase_invoice_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_ORDER_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
            $voucher_type = "purchase";
            $invoice_type = 4;
            $page_title = 'Sales Order';
            $voucher_label = 'Sales Order';
            $module_id = 8;
            $invoice_list_url = base_url('sales/order_invoice_list');
            $invoice_save_url = base_url('sales/save_purchase_invoice');
            
        } elseif($voucher_type == "debit_note") {
            if (isset($_POST['debit_note_id'])) {
                if(!($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['debit_note_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
            $page_title = 'Debit Note (Purchase Return)';
            $voucher_label = 'Debit Note';
            $module_id = MODULE_DEBIT_NOTE_ID;
            $invoice_list_url = base_url('debit_note/list_page');
            $invoice_save_url = base_url('debit_note/save_debit_note');

        } elseif($voucher_type == "credit_note") {
            if (isset($_POST['credit_note_id'])) {
                if(!($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['credit_note_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
            $page_title = 'Credit Note (Sales Return) - CTRL + F2';
            $voucher_label = 'Credit Note';
            $module_id = MODULE_CREDIT_NOTE_ID;
            $invoice_list_url = base_url('credit_note/list_page');
            $invoice_save_url = base_url('credit_note/save_credit_note');

            $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date'));
            if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
                $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
            }
        } elseif($voucher_type == "dispatch") {
            if (isset($_POST['dispatch_invoice_id'])) {
                if(!($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $invoice_id = $_POST['dispatch_invoice_id'];
            } else {
                if(!($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
            }
            $page_title = 'Dispatch';
            $voucher_label = 'Dispatch Invoice';
            $module_id = MODULE_SALES_INVOICE_ID;
            $invoice_list_url = base_url('dispatch/invoice_list');
            $invoice_save_url = base_url('transaction/save_invoice');
            $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date'));
            if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
                $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
            }
        } elseif($voucher_type == "material_in") {
            if (isset($_POST['purchase_invoice_id'])) {
                $invoice_id = $_POST['purchase_invoice_id'];
            }
            $page_title = 'Material In';
            $voucher_label = 'Material In';
            $invoice_type = 4;
            $module_id = 8;
            $invoice_list_url = base_url('purchase/material_in_list');
            $invoice_save_url = base_url('purchase/save_purchase_invoice');
        } else {
            redirect('/');
        }

        $data['page_title'] = $page_title;
        $data['voucher_type'] = $voucher_type;
        $data['voucher_label'] = $voucher_label;
        $data['module_id'] = $module_id;
        $data['invoice_list_url'] = $invoice_list_url;
        $data['invoice_save_url'] = $invoice_save_url;
        $data['invoice_id'] = $invoice_id;
        
        if(isset($invoice_type)) {
            $data['invoice_type'] = $invoice_type;
        }

        $is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
        if($is_single_line_item) {

            $data['against_account_group_id'] = SALES_BILL_ACCOUNT_GROUP_ID;

            if (isset($_POST['purchase_invoice_id'])) {
                $where = array('purchase_invoice_id' => $_POST['purchase_invoice_id']);
                $purchase_invoice_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                $data['invoice_id'] = $purchase_invoice_data[0]->purchase_invoice_id;
                $data['transaction_date'] = $purchase_invoice_data[0]->purchase_invoice_date;
                $data['amount'] = $purchase_invoice_data[0]->amount_total;
                $data['account_id'] = $purchase_invoice_data[0]->account_id;
                $data['against_account_id'] = $purchase_invoice_data[0]->against_account_id;
                $data['note'] = $purchase_invoice_data[0]->purchase_invoice_desc;
            }

            if (isset($_POST['sales_invoice_id'])) {
                $where = array('sales_invoice_id' => $_POST['sales_invoice_id']);
                $sales_invoice_data = $this->crud->get_row_by_id('sales_invoice', $where);
                $data['invoice_id'] = $sales_invoice_data[0]->sales_invoice_id;
                $data['transaction_date'] = $sales_invoice_data[0]->sales_invoice_date;
                $data['amount'] = $sales_invoice_data[0]->amount_total;
                $data['account_id'] = $sales_invoice_data[0]->account_id;
                $data['against_account_id'] = $sales_invoice_data[0]->against_account_id;
                $data['note'] = $sales_invoice_data[0]->sales_invoice_desc;
            }

            if (isset($_POST['credit_note_id'])) {
                $where = array('credit_note_id' => $_POST['credit_note_id']);
                $credit_note_data = $this->crud->get_row_by_id('credit_note', $where);
                $data['invoice_id'] = $credit_note_data[0]->credit_note_id;
                $data['transaction_date'] = $credit_note_data[0]->credit_note_date;
                $data['amount'] = $credit_note_data[0]->amount_total;
                $data['account_id'] = $credit_note_data[0]->account_id;
                $data['against_account_id'] = $credit_note_data[0]->against_account_id;
                $data['note'] = $credit_note_data[0]->credit_note_desc;
            }

            if (isset($_POST['debit_note_id'])) {
                $where = array('debit_note_id' => $_POST['debit_note_id']);
                $debit_note_data = $this->crud->get_row_by_id('debit_note', $where);
                $data['invoice_id'] = $debit_note_data[0]->debit_note_id;
                $data['transaction_date'] = $debit_note_data[0]->debit_note_date;
                $data['amount'] = $debit_note_data[0]->amount_total;
                $data['account_id'] = $debit_note_data[0]->account_id;
                $data['against_account_id'] = $debit_note_data[0]->against_account_id;
                $data['note'] = $debit_note_data[0]->debit_note_desc;
            }

            if (isset($_POST['dispatch_invoice_id'])) {
                $where = array('dispatch_invoice_id' => $_POST['dispatch_invoice_id']);
                $dispatch_invoice_data = $this->crud->get_row_by_id('dispatch_invoice', $where);
                $data['invoice_id'] = $dispatch_invoice_data[0]->sales_invoice_id;
                $data['transaction_date'] = $dispatch_invoice_data[0]->sales_invoice_date;
                $data['amount'] = $dispatch_invoice_data[0]->amount_total;
                $data['account_id'] = $dispatch_invoice_data[0]->account_id;
                $data['against_account_id'] = $dispatch_invoice_data[0]->against_account_id;
                $data['note'] = $dispatch_invoice_data[0]->sales_invoice_desc;
            }

            set_page('single_line_item_transaction',$data);
        } else {
            $main_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE setting_key != "display_dollar_sign" AND company_id = "'.$this->logged_in_id.'" AND module_name = 1 AND setting_value = 1');
            $invoice_main_fields = array();
            if(!empty($main_fields)) {
                foreach ($main_fields as $value) {
                    $invoice_main_fields[] = $value->setting_key;
                }
            }
            $data['invoice_main_fields'] = $invoice_main_fields;

            $line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 2 AND setting_value = 1');
            $invoice_line_item_fields = array();
            if(!empty($line_item_fields)) {
                foreach ($line_item_fields as $value) {
                    $invoice_line_item_fields[] = $value->setting_key;
                }
            }
            $data['invoice_line_item_fields'] = $invoice_line_item_fields;
            $data['company_invoice_prefix'] = $this->crud->get_row_by_id('company_invoice_prefix', array('company_id' => $this->logged_in_id));

            if($voucher_type == "sales" || $voucher_type == "sales2" || $voucher_type == "sales3" || $voucher_type == "sales4") {
                if(isset($_POST['sales_invoice_id'])) {
                    $where = array('sales_invoice_id' => $_POST['sales_invoice_id']);
                    $sales_invoice_data = $this->crud->get_row_by_id('sales_invoice', $where);
                    $sales_invoice_data[0]->invoice_no = $sales_invoice_data[0]->sales_invoice_no;
                    $sales_invoice_data[0]->invoice_date = $sales_invoice_data[0]->sales_invoice_date;
                    $sales_invoice_data[0]->invoice_desc = $sales_invoice_data[0]->sales_invoice_desc;
                    
                    $data['invoice_data'] = $sales_invoice_data[0];

                    
                    $lineitems = '';
                    $where = array('module' => '2', 'parent_id' => $_POST['sales_invoice_id']);
                    $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                    foreach($sales_invoice_lineitems as $sales_invoice_lineitem){
                        $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                        $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                        $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                        $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                        $lineitems .= "'".json_encode($sales_invoice_lineitem)."',";
                    }
                    /*echo "<pre>";
                    print_r( $lineitems);
                    die();*/
                    $data['sales_invoice_lineitems'] = $lineitems;
                } else {
                    $data['prefix'] = $this->crud->get_id_by_val('user', 'prefix', 'user_id', $this->logged_in_id);
                    $in_where = array('created_by' => $this->logged_in_id);
                    if($this->prefix){
                        $in_where['prefix'] = $this->prefix;
                    }
                    $sales_invoice_no = $this->crud->get_max_number_where('sales_invoice', 'sales_invoice_no', $in_where);
                    if(empty($sales_invoice_no->sales_invoice_no)){
                        $data['invoice_no'] = $this->crud->get_id_by_val('user', 'invoice_no_start_from', 'prefix', $this->prefix);
                    } else {
                        $data['invoice_no'] = $sales_invoice_no->sales_invoice_no + 1;    
                    }
                    $data['invoice_type'] = $this->crud->get_column_value_by_id('user','invoice_type',array('user_id'=>$this->logged_in_id));
                    if(!empty($order_id)){
                        $where = array('purchase_invoice_id' => $order_id);
                        $order_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                        $data['order_account_id'] = $order_data[0]->account_id;
                        $lineitems = '';
                        $where = array('module' => '1', 'parent_id' => $order_id);
                        $order_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                        foreach($order_lineitems as $order_lineitem){
                            unset($order_lineitem->id);
                            $order_lineitem->pure_amount = $order_lineitem->pure_amount;
                            $order_lineitem->cgst_amt = $order_lineitem->cgst_amount;
                            $order_lineitem->sgst_amt = $order_lineitem->sgst_amount;
                            $order_lineitem->igst_amt = $order_lineitem->igst_amount;
                            $lineitems .= "'".json_encode($order_lineitem)."',";
                        }
                        $data['order_lineitems'] = $lineitems;
                    }
                }
            } elseif($voucher_type == "purchase") {
                if (isset($_POST['purchase_invoice_id'])) {
                    $where = array('purchase_invoice_id' => $_POST['purchase_invoice_id']);
                    $purchase_invoice_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                    $purchase_invoice_data[0]->invoice_date = $purchase_invoice_data[0]->purchase_invoice_date;
                    $purchase_invoice_data[0]->invoice_desc = $purchase_invoice_data[0]->purchase_invoice_desc;
                    $data['invoice_data'] = $purchase_invoice_data[0];

                    $lineitems = '';
                    $where = array('module' => '1', 'parent_id' => $_POST['purchase_invoice_id']);
                    $purchase_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                    foreach ($purchase_invoice_lineitems as $purchase_invoice_lineitem) {
                        $purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->pure_amount;
                        $purchase_invoice_lineitem->cgst_amt = $purchase_invoice_lineitem->cgst_amount;
                        $purchase_invoice_lineitem->sgst_amt = $purchase_invoice_lineitem->sgst_amount;
                        $purchase_invoice_lineitem->igst_amt = $purchase_invoice_lineitem->igst_amount;
                        $lineitems .= "'" . json_encode($purchase_invoice_lineitem) . "',";
                    }
                    $data['purchase_invoice_lineitems'] = $lineitems;
                }

            } elseif($voucher_type == "credit_note") {
                if (isset($_POST['credit_note_id'])) {
                    $where = array('credit_note_id' => $_POST['credit_note_id']);
                    $credit_note_data = $this->crud->get_row_by_id('credit_note', $where);
                    $credit_note_data[0]->note_date = $credit_note_data[0]->invoice_date;
                    $credit_note_data[0]->invoice_date = $credit_note_data[0]->credit_note_date;
                    $credit_note_data[0]->invoice_desc = $credit_note_data[0]->credit_note_desc;
                    $data['invoice_data'] = $credit_note_data[0];
                    $lineitems = '';
                    $where = array('module' => '3', 'parent_id' => $_POST['credit_note_id']);
                    $credit_note_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                    foreach($credit_note_lineitems as $credit_note_lineitem){
                        $credit_note_lineitem->pure_amount = $credit_note_lineitem->pure_amount;
                        $credit_note_lineitem->cgst_amt = $credit_note_lineitem->cgst_amount;
                        $credit_note_lineitem->sgst_amt = $credit_note_lineitem->sgst_amount;
                        $credit_note_lineitem->igst_amt = $credit_note_lineitem->igst_amount;
                        $lineitems .= "'".json_encode($credit_note_lineitem)."',";
                    }
                    $data['credit_note_lineitems'] = $lineitems;
                }

            } elseif($voucher_type == "debit_note") {
                if (isset($_POST['debit_note_id'])) {
                    $where = array('debit_note_id' => $_POST['debit_note_id']);
                    $debit_note_data = $this->crud->get_row_by_id('debit_note', $where);
                    $debit_note_data[0]->note_date = $debit_note_data[0]->invoice_date;
                    $debit_note_data[0]->invoice_date = $debit_note_data[0]->debit_note_date;
                    $debit_note_data[0]->invoice_desc = $debit_note_data[0]->debit_note_desc;
                    $data['invoice_data'] = $debit_note_data[0];
                    $lineitems = '';
                    $where = array('module' => '4', 'parent_id' => $_POST['debit_note_id']);
                    $debit_note_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                    foreach($debit_note_lineitems as $debit_note_lineitem){
                        $debit_note_lineitem->pure_amount = $debit_note_lineitem->pure_amount;
                        $debit_note_lineitem->cgst_amt = $debit_note_lineitem->cgst_amount;
                        $debit_note_lineitem->sgst_amt = $debit_note_lineitem->sgst_amount;
                        $debit_note_lineitem->igst_amt = $debit_note_lineitem->igst_amount;
                        $lineitems .= "'".json_encode($debit_note_lineitem)."',";
                    }
                    $data['debit_note_lineitems'] = $lineitems;
                }

            } elseif($voucher_type == "dispatch") {
                if(isset($_POST['dispatch_invoice_id'])) {
                    $where = array('dispatch_invoice_id' => $_POST['dispatch_invoice_id']);
                    $dispatch_invoice_data = $this->crud->get_row_by_id('sales_invoice', $where);
                    $dispatch_invoice_data[0]->invoice_no = $dispatch_invoice_data[0]->dispatch_invoice_no;
                    $dispatch_invoice_data[0]->invoice_date = $dispatch_invoice_data[0]->dispatch_invoice_date;
                    $dispatch_invoice_data[0]->invoice_desc = $dispatch_invoice_data[0]->dispatch_invoice_desc;
                    
                    $data['invoice_data'] = $dispatch_invoice_data[0];

                    
                    $lineitems = '';
                    $where = array('module' => '7', 'parent_id' => $_POST['dispatch_invoice_id']);
                    $dispatch_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                    foreach($dispatch_invoice_lineitems as $dispatch_invoice_lineitem){
                        $dispatch_invoice_lineitem->pure_amount = $dispatch_invoice_lineitem->pure_amount;
                        $dispatch_invoice_lineitem->cgst_amt = $dispatch_invoice_lineitem->cgst_amount;
                        $dispatch_invoice_lineitem->sgst_amt = $dispatch_invoice_lineitem->sgst_amount;
                        $dispatch_invoice_lineitem->igst_amt = $dispatch_invoice_lineitem->igst_amount;
                        $lineitems .= "'".json_encode($dispatch_invoice_lineitem)."',";
                    }
                    $data['dispatch_invoice_lineitems'] = $lineitems;
                } else {
                    $data['prefix'] = $this->crud->get_id_by_val('user', 'prefix', 'user_id', $this->logged_in_id);
                    $dispatch_invoice_no = 0;
                    // $dispatch_invoice_no = $this->crud->get_max_number_where('dispatch_invoice', 'dispatch_invoice_no', array('created_by' => $this->logged_in_id, 'prefix' => $this->prefix));
                    if(empty($dispatch_invoice_no->dispatch_invoice_no)){
                        $data['invoice_no'] = $this->crud->get_id_by_val('user', 'invoice_no_start_from', 'prefix', $this->prefix);
                    } else {
                        $data['invoice_no'] = $dispatch_invoice_no->dispatch_invoice_no + 1;    
                    }
                    $data['invoice_type'] = $this->crud->get_column_value_by_id('user','invoice_type',array('user_id'=>$this->logged_in_id));
                    if(!empty($order_id)){
                        $where = array('purchase_invoice_id' => $order_id);
                        $order_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                        $data['order_account_id'] = $order_data[0]->account_id;
                        $lineitems = '';
                        $where = array('module' => '1', 'parent_id' => $order_id);
                        $order_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                        foreach($order_lineitems as $order_lineitem){
                            unset($order_lineitem->id);
                            $order_lineitem->pure_amount = $order_lineitem->pure_amount;
                            $order_lineitem->cgst_amt = $order_lineitem->cgst_amount;
                            $order_lineitem->sgst_amt = $order_lineitem->sgst_amount;
                            $order_lineitem->igst_amt = $order_lineitem->igst_amount;
                            $lineitems .= "'".json_encode($order_lineitem)."',";
                        }
                        $data['order_lineitems'] = $lineitems;
                    }
                }
            } elseif($voucher_type == "material_in") {
                if (isset($_POST['purchase_invoice_id'])) {
                    $where = array('purchase_invoice_id' => $_POST['purchase_invoice_id']);
                    $purchase_invoice_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                    $purchase_invoice_data[0]->invoice_date = $purchase_invoice_data[0]->purchase_invoice_date;
                    $purchase_invoice_data[0]->invoice_desc = $purchase_invoice_data[0]->purchase_invoice_desc;
                    $data['invoice_data'] = $purchase_invoice_data[0];

                    $lineitems = '';
                    $where = array('module' => '8', 'parent_id' => $_POST['purchase_invoice_id']);
                    $purchase_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                    foreach ($purchase_invoice_lineitems as $purchase_invoice_lineitem) {
                        $purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->pure_amount;
                        $purchase_invoice_lineitem->cgst_amt = $purchase_invoice_lineitem->cgst_amount;
                        $purchase_invoice_lineitem->sgst_amt = $purchase_invoice_lineitem->sgst_amount;
                        $purchase_invoice_lineitem->igst_amt = $purchase_invoice_lineitem->igst_amount;
                        $lineitems .= "'" . json_encode($purchase_invoice_lineitem) . "',";
                    }
                    $data['purchase_invoice_lineitems'] = $lineitems;
                }

            }
            set_page('mutiple_line_item_transaction',$data);
        }
    }

    function save_invoice(){
        $return = array();
        $post_data = $this->input->post();
        // echo "<pre>";
        // print_r($post_data['line_items_data']);
        // exit;
        $line_items_data = json_decode('['.$post_data['line_items_data'].']');
        
        $invoice_data = array();     
        $voucher_type = $post_data['voucher_type'];

        if(!isset($post_data['prefix'])) {
            $post_data['prefix'] = '';
        }

        if($voucher_type == 'sales') {
            $module = 2;
            $invoice_data['prefix'] = $post_data['prefix'];
            $invoice_data['sales_invoice_no'] = $post_data['invoice_no'];
            $invoice_data['sales_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['sales_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['cash_customer'] = (isset($post_data['cash_customer'])?$post_data['cash_customer']:'');
            $invoice_data['tax_type'] = (isset($post_data['tax_type'])?$post_data['tax_type']:'');
            $invoice_data['our_bank_id'] = $post_data['our_bank_label'];
            $invoice_data['sales_type'] = 1;

        }elseif($voucher_type == 'sales2') {
            $module = 2;
            $invoice_data['prefix'] = $post_data['prefix'];
            $invoice_data['sales_invoice_no'] = $post_data['invoice_no'];
            $invoice_data['sales_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['sales_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['cash_customer'] = (isset($post_data['cash_customer'])?$post_data['cash_customer']:'');
            $invoice_data['tax_type'] = (isset($post_data['tax_type'])?$post_data['tax_type']:'');
            $invoice_data['our_bank_id'] = $post_data['our_bank_label'];
            $invoice_data['total_pf_amount'] = (isset($post_data['total_pf_amount'])) ? $post_data['total_pf_amount'] : '';
            $invoice_data['aspergem_service_charge'] = (isset($post_data['aspergem_service_charge'])) ? $post_data['aspergem_service_charge'] : '';
            $invoice_data['sales_subject'] = (isset($post_data['sales_subject'])) ? $post_data['sales_subject'] : '';
            $invoice_data['sales_note'] = (isset($post_data['sales_note'])) ? $post_data['sales_note'] : '';
            $invoice_data['prof_tax'] = (isset($post_data['prof_tax'])) ? $post_data['prof_tax'] : '';
            $invoice_data['sales_type'] = 2;

        }elseif($voucher_type == 'sales3') {
            $module = 2;
            $invoice_data['prefix'] = $post_data['prefix'];
            $invoice_data['sales_invoice_no'] = $post_data['invoice_no'];
            $invoice_data['sales_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['sales_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['cash_customer'] = (isset($post_data['cash_customer'])?$post_data['cash_customer']:'');
            $invoice_data['tax_type'] = (isset($post_data['tax_type'])?$post_data['tax_type']:'');
            $invoice_data['our_bank_id'] = $post_data['our_bank_label'];
            $invoice_data['book_no'] = (isset($post_data['book_no'])) ? $post_data['book_no'] : '';
            $invoice_data['ship_party_name'] = (isset($post_data['ship_party_name'])) ? $post_data['ship_party_name'] : '';
            $invoice_data['ship_party_address'] = (isset($post_data['ship_party_address'])) ? $post_data['ship_party_address'] : '';
            $invoice_data['ship_party_gstin'] = (isset($post_data['ship_party_gstin'])) ? $post_data['ship_party_gstin'] : '';
            $invoice_data['ship_party_state'] = (isset($post_data['ship_party_state'])) ? $post_data['ship_party_state'] : '';
            $invoice_data['ship_party_code'] = (isset($post_data['ship_party_code'])) ? $post_data['ship_party_code'] : '';
            $invoice_data['sales_note'] = (isset($post_data['sales_note'])) ? $post_data['sales_note'] : '';
            $invoice_data['sales_type'] = 3;

        }elseif($voucher_type == 'sales4') {
            $module = 2;
            $invoice_data['prefix'] = $post_data['prefix'];
            $invoice_data['sales_invoice_no'] = $post_data['invoice_no'];
            $invoice_data['sales_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['sales_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['cash_customer'] = (isset($post_data['cash_customer'])?$post_data['cash_customer']:'');
            $invoice_data['tax_type'] = (isset($post_data['tax_type'])?$post_data['tax_type']:'');
            $invoice_data['our_bank_id'] = $post_data['our_bank_label'];
            $invoice_data['book_no'] = (isset($post_data['book_no'])) ? $post_data['book_no'] : '';
            $invoice_data['ship_party_name'] = (isset($post_data['ship_party_name'])) ? $post_data['ship_party_name'] : '';
            $invoice_data['ship_party_address'] = (isset($post_data['ship_party_address'])) ? $post_data['ship_party_address'] : '';
            $invoice_data['ship_party_gstin'] = (isset($post_data['ship_party_gstin'])) ? $post_data['ship_party_gstin'] : '';
            $invoice_data['ship_party_state'] = (isset($post_data['ship_party_state'])) ? $post_data['ship_party_state'] : '';
            $invoice_data['ship_party_code'] = (isset($post_data['ship_party_code'])) ? $post_data['ship_party_code'] : '';
            $invoice_data['sales_subject'] = (isset($post_data['sales_subject'])) ? $post_data['sales_subject'] : '';
            $invoice_data['sales_note'] = (isset($post_data['sales_note'])) ? $post_data['sales_note'] : '';
            $invoice_data['sales_type'] = 4;

        }elseif($voucher_type == 'purchase') {
            $module = 1;
            $invoice_data['bill_no'] = $post_data['bill_no'];
            $invoice_data['purchase_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['purchase_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['our_bank_id'] = $post_data['our_bank_label'];

        } elseif($voucher_type == 'credit_note') {
            $module = 3;
            $invoice_data['credit_note_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['credit_note_desc'] = $post_data['invoice_desc'];
            $invoice_data['bill_no'] = $post_data['bill_no'];
            $invoice_data['invoice_date'] = date('Y-m-d', strtotime($post_data['note_date']));
        
        } elseif($voucher_type == 'debit_note') {
            $module = 4;
            $invoice_data['bill_no'] = $post_data['bill_no'];
            $invoice_data['debit_note_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['debit_note_desc'] = $post_data['invoice_desc'];
            $invoice_data['invoice_date'] = date('Y-m-d', strtotime($post_data['note_date']));

        } elseif($voucher_type == 'dispatch') {
            $module = 7;
            $invoice_data['prefix'] = $post_data['prefix'];
            $invoice_data['dispatch_invoice_no'] = $post_data['invoice_no'];
            $invoice_data['dispatch_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['dispatch_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['cash_customer'] = (isset($post_data['cash_customer'])?$post_data['cash_customer']:'');
            $invoice_data['tax_type'] = (isset($post_data['tax_type'])?$post_data['tax_type']:'');

        } elseif($voucher_type == 'material_in') {
            $module = 8;
            $invoice_data['bill_no'] = $post_data['bill_no'];
            $invoice_data['vehicle_no'] = $post_data['vehicle_no'];
            $invoice_data['driver_name'] = $post_data['driver_name'];
            $invoice_data['purchase_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
            $invoice_data['purchase_invoice_desc'] = $post_data['invoice_desc'];
            $invoice_data['invoice_type'] = !empty($post_data['invoice_type']) ? $post_data['invoice_type'] : null;
        }

        if($voucher_type == 'sales' || $voucher_type == 'sales2' || $voucher_type == 'sales3' || $voucher_type == 'sales4' || $voucher_type == 'purchase' || $voucher_type == 'dispatch') {

            $invoice_data['transport_name'] = $post_data['transport_name'];
            $invoice_data['lr_no'] = $post_data['lr_no'];
            $invoice_data['invoice_type'] = !empty($post_data['invoice_type']) ? $post_data['invoice_type'] : null;

            if(isset($post_data['triplicate']) && !empty($post_data['triplicate'])) {
                $invoice_data['print_type'] = $post_data['triplicate'];
            
            } elseif(isset($post_data['duplicate']) && !empty($post_data['duplicate'])) {
                $invoice_data['print_type'] = $post_data['duplicate'];
            }
            
            if(isset($post_data['shipping_address_chkbox']) && !empty($post_data['shipping_address_chkbox'])) {
                $invoice_data['is_shipping_same_as_billing_address'] = 0;
                $invoice_data['shipping_address'] = $this->crud->get_column_value_by_id('user','address',array('user_id' => $this->logged_in_id));
            } else {
                $invoice_data['is_shipping_same_as_billing_address'] = 1;
                $invoice_data['shipping_address'] = $post_data['shipping_address'];
            }
        }

        $invoice_data['account_id'] = $post_data['account_id'];
        $invoice_data['qty_total'] = $post_data['qty_total'];
        // $invoice_data['pure_amount_total'] = $post_data['pure_amount_total'];
        // $invoice_data['discount_total'] = $post_data['discount_total'];
        // $invoice_data['cgst_amount_total'] = $post_data['cgst_amount_total'];
        // $invoice_data['sgst_amount_total'] = $post_data['sgst_amount_total'];
        // $invoice_data['igst_amount_total'] = $post_data['igst_amount_total'];
        // $invoice_data['other_charges_total'] = (isset($post_data['other_charges_total'])?$post_data['other_charges_total']:0);
        $invoice_data['round_off_amount'] = (isset($post_data['round_off_amount'])?$post_data['round_off_amount']:0);
        $invoice_data['amount_total'] = ($post_data['amount_total'] + $invoice_data['round_off_amount']);

        $main_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE setting_key != "display_dollar_sign" AND company_id = "'.$this->logged_in_id.'" AND module_name = 1 AND setting_value = 1');
        if(!empty($main_fields)) {
            foreach ($main_fields as $value) {
                if (strpos($value->setting_key, 'date') !== false || strpos($value->setting_key, 'date') !== false) {
                    $post_data[$value->setting_key] = !empty($post_data[$value->setting_key]) ? date('Y-m-d', strtotime($post_data[$value->setting_key])) : null;
                }
                $invoice_data[$value->setting_key] = $post_data[$value->setting_key];
            }
        }

        $dateranges = $this->crud->getFromSQL('SELECT `daterange` FROM `locked_daterange` WHERE `user_id`="'.$this->logged_in_id.'" ');
        if(!empty($dateranges)){
            foreach($dateranges as $daterange){
                $pieces = explode(" ", $daterange->daterange);
                $from = date('d-m-Y', strtotime($pieces[0]));
                $to = date('d-m-Y', strtotime($pieces[2]));
                $invoice_date = date('d-m-Y', strtotime($post_data['invoice_date']));
                if(strtotime($invoice_date) >= strtotime($from) && strtotime($invoice_date) <= strtotime($to)){
                    $return['error'] = "Locked_Date";
                    print json_encode($return);
                    exit;
                }
            }    
        }

        if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])) {
            
            if($voucher_type == 'sales' || $voucher_type == 'sales2' || $voucher_type == 'sales3' || $voucher_type == 'sales4') {
                $invoice_no = $post_data['invoice_no'];
                $invoice_prefix = isset($post_data['prefix']) ? $post_data['prefix'] : null;

                $where = array('prefix' => $invoice_prefix, 'sales_invoice_no' => $invoice_no, 'created_by' => $this->logged_in_id, 'sales_invoice_id !=' => $post_data['invoice_id']);
                $invoice_result = $this->crud->get_row_by_id('sales_invoice', $where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'Exist'));
                    exit;
                }
            }

            if($voucher_type == 'purchase' && !empty($post_data['bill_no'])) {
                $bill_no = $post_data['bill_no'];
                $where = array('invoice_type' => $post_data['invoice_type'],'bill_no' => $bill_no, 'created_by' => $this->logged_in_id, 'purchase_invoice_id !=' => $post_data['invoice_id']);
                $invoice_result = $this->crud->get_row_by_id('purchase_invoice', $where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'BillNoExist'));
                    exit;
                }
            }

            if($voucher_type == 'dispatch') {
                $invoice_no = $post_data['invoice_no'];
                $invoice_prefix = isset($post_data['prefix']) ? $post_data['prefix'] : null;

                $where = array('prefix' => $invoice_prefix, 'dispatch_invoice_no' => $invoice_no, 'created_by' => $this->logged_in_id, 'dispatch_invoice_id !=' => $post_data['invoice_id']);
                $invoice_result = $this->crud->get_row_by_id('dispatch_invoice', $where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'Exist'));
                    exit;
                }
            }

            if($voucher_type == 'material_in' && !empty($post_data['bill_no'])) {
                $bill_no = $post_data['bill_no'];
                $where = array('invoice_type' => 4,'bill_no' => $bill_no, 'created_by' => $this->logged_in_id, 'purchase_invoice_id !=' => $post_data['invoice_id']);
                $invoice_result = $this->crud->get_row_by_id('purchase_invoice', $where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'BillNoExist'));
                    exit;
                }
            }

            $invoice_data['updated_at'] = $this->now_time;
            $invoice_data['updated_by'] = $this->logged_in_id;
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];

            if($voucher_type == 'sales') {
                $where_array['sales_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('sales_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Sales Invoice Updated Successfully');

            }elseif($voucher_type == 'sales2') {
                $where_array['sales_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('sales_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Sales Invoice2 Updated Successfully');

            }elseif($voucher_type == 'sales3') {
                $where_array['sales_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('sales_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Sales Invoice3 Updated Successfully');

            }elseif($voucher_type == 'sales4') {
                $where_array['sales_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('sales_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Sales Invoice4 Updated Successfully');

            }
            elseif($voucher_type == 'purchase') {

                $where_array['purchase_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('purchase_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Purchase Invoice Updated Successfully');

            } elseif($voucher_type == 'credit_note') {
                $where_array['credit_note_id'] = $post_data['invoice_id'];
                $this->crud->update('credit_note', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Credit Note Updated Successfully');

            } elseif($voucher_type == 'debit_note') {
                $where_array['debit_note_id'] = $post_data['invoice_id'];
                $this->crud->update('debit_note', $invoice_data, $where_array);    
                $this->session->set_flashdata('message','Debit Note Updated Successfully');

            } elseif($voucher_type == 'dispatch') {
                $where_array['dispatch_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('dispatch_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Dispatch Invoice Updated Successfully');

            } elseif($voucher_type == 'material_in') {

                $where_array['purchase_invoice_id'] = $post_data['invoice_id'];
                $this->crud->update('purchase_invoice', $invoice_data, $where_array);
                $this->session->set_flashdata('message','Material In Updated Successfully');
            }
            
            $return['success'] = "Updated";
            $this->session->set_flashdata('success',true);
            
            $parent_id = $post_data['invoice_id'];
            
            if(isset($post_data['deleted_lineitem_id']) && !empty($post_data['deleted_lineitem_id'])){
                $deleted_lineitem_ids = $post_data['deleted_lineitem_id'];
                $lineitems_res = $this->crud->get_where_in_result('lineitems', 'id', $deleted_lineitem_ids);
                if(!empty($lineitems_res)) {
                    foreach ($lineitems_res as $key => $lineitem) {
                        $this->crud->update_item_current_stock_qty($lineitem->item_id,$lineitem->parent_id,$voucher_type,$lineitem->item_qty,'delete');       
                    }
                }
                $this->crud->delete_where_in('lineitems', 'id', $deleted_lineitem_ids);
            }
            
            $gst_per = 0;
            foreach($line_items_data[0] as $lineitem){
                if ( isset($lineitem->gst_rate) ) {
                    $gst_per = $lineitem->gst_rate;
                } else if ( isset($lineitem->gst) ) {
                    $gst_per = $lineitem->gst;
                } else {
                    $gst_per = 0;
                }
                $add_lineitem = array();
                $add_lineitem['item_group_id'] = isset($lineitem->item_group_id) ? $lineitem->item_group_id : null;
                $add_lineitem['cat_id'] = isset($lineitem->cat_id) ? $lineitem->cat_id : NULL;
                $add_lineitem['sub_cat_id'] = isset($lineitem->sub_cat_id) ? $lineitem->sub_cat_id : NULL;
                $add_lineitem['item_id'] = isset($lineitem->item_id) ? $lineitem->item_id : 0;
                $add_lineitem['item_qty'] = $lineitem->item_qty;
                $add_lineitem['price'] = isset($lineitem->price) ? $lineitem->price : NULL;
                $add_lineitem['pure_amount'] = isset($lineitem->pure_amount)?$lineitem->pure_amount:NULL;
                $add_lineitem['amount'] = isset($lineitem->amount)?$lineitem->amount:NULL;
                $add_lineitem['unit_id'] = isset($lineitem->unit_id)?$lineitem->unit_id:NULL;
                $add_lineitem['gst'] = $gst_per;
                $add_lineitem['hsn'] = isset($lineitem->hsn) ? $lineitem->hsn:NULL;  
                $add_lineitem['site_id'] = isset($lineitem->site_id)?$lineitem->site_id:NULL;     
                $add_lineitem['module'] = $module;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['note'] = isset($lineitem->note)?$lineitem->note:'';
                $add_lineitem['line_item_des'] = isset($lineitem->line_item_des)?$lineitem->line_item_des:NULL;
                if(isset($lineitem->id) && !empty($lineitem->id)){
                    $this->crud->update_item_current_stock_qty(isset($lineitem->item_id)?$lineitem->item_id:0,$parent_id,$voucher_type,$lineitem->item_qty,'update');
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $this->logged_in_id;
                    $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                    $where_id['id'] = $lineitem->id;
                    $this->crud->update('lineitems', $add_lineitem, $where_id);
                } else {
                    $this->crud->update_item_current_stock_qty(isset( $lineitem->item_id ) ? $lineitem->item_id:0,$parent_id,$voucher_type,$lineitem->item_qty,'add');

                    $add_lineitem['created_at'] = $this->now_time;
                    $add_lineitem['created_by'] = $this->logged_in_id;
                    $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $this->logged_in_id;
                    $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                    $this->crud->insert('lineitems',$add_lineitem);
                }
            }
        } else {

            if($voucher_type == 'sales' || $voucher_type == 'sales2' || $voucher_type == 'sales3' || $voucher_type == 'sales4') {
                $invoice_no = $post_data['invoice_no'];
                $invoice_prefix = $post_data['prefix'];
                $where = array('prefix' => $invoice_prefix, 'sales_invoice_no' => $invoice_no, 'created_by' => $this->logged_in_id);
                $invoice_result = $this->crud->get_row_by_id('sales_invoice',$where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'Exist'));
                    exit;
                }
            }
            if($voucher_type == 'purchase' && !empty($post_data['bill_no'])) {
                $bill_no = $post_data['bill_no'];
                $where = array('invoice_type' => $post_data['invoice_type'], 'bill_no' => $bill_no, 'created_by' => $this->logged_in_id);
                $invoice_result = $this->crud->get_row_by_id('purchase_invoice', $where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'BillNoExist'));
                    exit;
                }
            }
            if($voucher_type == 'dispatch') {
                $invoice_no = $post_data['invoice_no'];
                $invoice_prefix = $post_data['prefix'];
                $where = array('prefix' => $invoice_prefix, 'dispatch_invoice_no' => $invoice_no, 'created_by' => $this->logged_in_id);
                $invoice_result = $this->crud->get_row_by_id('dispatch_invoice',$where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'Exist'));
                    exit;
                }
            }

            if($voucher_type == 'material_in' && !empty($post_data['bill_no'])) {
                $bill_no = $post_data['bill_no'];
                $where = array('invoice_type' => 4, 'bill_no' => $bill_no, 'created_by' => $this->logged_in_id);
                $invoice_result = $this->crud->get_row_by_id('purchase_invoice', $where);
                if(!empty($invoice_result)){
                    echo json_encode(array("error" => 'BillNoExist'));
                    exit;
                }
            }
            
            $invoice_data['created_at'] = $this->now_time;
            $invoice_data['created_by'] = $this->logged_in_id;
            $invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            
            if($voucher_type == 'sales') {
                $this->crud->insert('sales_invoice', $invoice_data);
                $this->session->set_flashdata('message','Sales Invoice Added Successfully');

            }elseif($voucher_type == 'sales2') {
                $this->crud->insert('sales_invoice', $invoice_data);
                $this->session->set_flashdata('message','Sales Invoice2 Added Successfully');
            
            } elseif($voucher_type == 'sales3') {
                $this->crud->insert('sales_invoice', $invoice_data);
                $this->session->set_flashdata('message','Sales Invoice3 Added Successfully');
            } elseif($voucher_type == 'sales4') {
                $this->crud->insert('sales_invoice', $invoice_data);
                $this->session->set_flashdata('message','Sales Invoice4 Added Successfully');
            } 
            elseif($voucher_type == 'purchase') {
                $this->crud->insert('purchase_invoice', $invoice_data);
                $this->session->set_flashdata('message','Purchase Invoice Added Successfully');

            } elseif($voucher_type == 'credit_note') {
                $credit_note_no = $this->crud->get_max_number('credit_note', 'credit_note_no', $this->logged_in_id);
                $invoice_data['credit_note_no'] = $credit_note_no->credit_note_no + 1;
                $this->crud->insert('credit_note', $invoice_data);
                $this->session->set_flashdata('message','Credit Note Added Successfully');

            } elseif($voucher_type == 'debit_note') {
                $debit_note_no = $this->crud->get_max_number('debit_note', 'debit_note_no', $this->logged_in_id);
                $invoice_data['debit_note_no'] = $debit_note_no->debit_note_no + 1;
                $this->crud->insert('debit_note', $invoice_data);    
                $this->session->set_flashdata('message','Debit Note Added Successfully');

            } elseif($voucher_type == 'dispatch') {
                $this->crud->insert('dispatch_invoice', $invoice_data);
                $this->session->set_flashdata('message','Dispatch Invoice Added Successfully');

            } elseif($voucher_type == 'material_in') {
                $this->crud->insert('purchase_invoice', $invoice_data);
                $this->session->set_flashdata('message','Material In Added Successfully');
            }

            $return['success'] = "Added";
            $this->session->set_flashdata('success',true);

            $parent_id = $this->db->insert_id();

            if($voucher_type == 'sales' || $voucher_type == 'sales2' || $voucher_type == 'sales3' || $voucher_type == 'sales4' ) {
                $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $invoice_data['sales_invoice_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date',"setting_value" => $invoice_data['sales_invoice_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
                } 

            } elseif($voucher_type == 'credit_note') {

                $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $invoice_data['credit_note_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date',"setting_value" => $invoice_data['credit_note_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
                }
            }

            $return['is_print'] = isset($post_data['is_print'])?$post_data['is_print']:0;
            if($return['is_print'] == 1) {
                $return['invoice_id'] = $parent_id;
            }
            
            foreach($line_items_data[0] as $lineitem){
                $add_lineitem = array();
                $add_lineitem['item_group_id'] = isset($lineitem->item_group_id) ? $lineitem->item_group_id : null;
                $add_lineitem['cat_id'] = isset($lineitem->cat_id) ? $lineitem->cat_id : NULL;
                $add_lineitem['sub_cat_id'] = isset($lineitem->sub_cat_id) ? $lineitem->sub_cat_id : NULL;


                $add_lineitem['item_id'] = isset($lineitem->item_id)?$lineitem->item_id:0;
                $add_lineitem['item_qty'] = $lineitem->item_qty;
                $add_lineitem['price'] = isset($lineitem->price) ? $lineitem->price : NULL;
                $add_lineitem['pure_amount'] = isset($lineitem->pure_amount)?$lineitem->pure_amount:NULL;
                $add_lineitem['amount'] = isset($lineitem->amount)?$lineitem->amount:NULL;
                $add_lineitem['unit_id'] = isset($lineitem->unit_id)?$lineitem->unit_id:NULL;
                $add_lineitem['gst'] = isset($lineitem->gst_rate)?$lineitem->gst_rate:0;
                $add_lineitem['site_id'] = isset($lineitem->site_id)?$lineitem->site_id:NULL;
                // Add Comapany id
                $add_lineitem['company_id'] = $this->logged_in_id;
                $add_lineitem['module'] = $module;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['note'] = isset($lineitem->note)?$lineitem->note:'';
                $add_lineitem['line_item_des'] = isset($lineitem->line_item_des)?$lineitem->line_item_des:NULL;
                $add_lineitem['created_at'] = $this->now_time;
                $add_lineitem['updated_at'] = $this->now_time;
                $add_lineitem['updated_by'] = $this->logged_in_id;
                $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                $add_lineitem['created_by'] = $this->logged_in_id;
                $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                /*echo "line item data => <pre>";
                print_r( $add_lineitem );
                die();*/
                $this->crud->insert('lineitems',$add_lineitem);
                /* print_r($this->db->last_query());exit;*/

                if($voucher_type == 'sales' && $voucher_type == 'sales2' && $voucher_type == 'sales3' && $voucher_type == 'sales4') {
                    if($voucher_type == 'purchase') {
                        // Stock change update main item : Start //
                        $stock_s_data = array();
                        $stock_s_data['st_change_date'] =  date("Y-m-d");
                        $stock_s_data['from_status'] =  IN_PURCHASE_ID;
                        $stock_s_data['to_status'] = IN_STOCK_ID;
                        $stock_s_data['item_id'] = $lineitem->item_id;
                        $stock_s_data['qty'] =  $lineitem->item_qty;
                        $stock_s_data['tr_type'] =  '1';
                        $stock_s_data['tr_id'] =  $parent_id;
                        $stock_s_data['created_at'] =  $this->now_time;
                        $stock_s_data['created_by'] =  $this->logged_in_id;
                        $this->db->insert('stock_status_change', $stock_s_data);
                        // Stock change main item : End //
                    }
                    $this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,$voucher_type,$lineitem->item_qty,'add');
                } else {
                     // Stock change update main item : Start //
                    $stock_s_data = array();
                    $stock_s_data['st_change_date'] =  date("Y-m-d");
                    $stock_s_data['from_status'] =  IN_WORK_DONE_ID;
                    $stock_s_data['to_status'] = IN_SALE_ID;
                    $stock_s_data['item_id'] = isset( $lineitem->item_id )? $lineitem->item_id : 0;
                    $stock_s_data['qty'] =  $lineitem->item_qty;
                    $stock_s_data['tr_type'] =  '2';
                    $stock_s_data['tr_id'] =  $parent_id;
                    $stock_s_data['created_at'] =  $this->now_time;
                    $stock_s_data['created_by'] =  $this->logged_in_id;
                    $this->db->insert('stock_status_change', $stock_s_data);
                    // Stock change main item : End //
                    if(!empty($lineitem->sub_item_data)){
                        $sub_item_data = array();
                        foreach ($lineitem->sub_item_data as $item){
                            $sub_item_data[] = (object) [
                                'item_id' => isset($lineitem->item_id)?$lineitem->item_id:0,
                                'item_level' => $item->sub_item_level,
                                'item_qty' => $item->sub_item_qty,
                                'item_unit_id' => $item->sub_item_unit_id,
                                'sub_item_id' => $item->sub_item_id,
                                'sub_item_add_less' => $item->sub_item_add_less,
                                'sub_item_qty' => $item->sub_sub_item_qty,
                                'sub_item_unit_id' => $item->sub_sub_item_unit_id];
                            if(isset($lineitem->apply_to_master) && !empty($lineitem->apply_to_master)){
                                $this->crud->delete('sub_item_add_less_settings', array('item_id' => $lineitem->item_id));
                                $sub_arr = array();
                                $sub_arr['item_id'] =  isset($lineitem->item_id)?$lineitem->item_id:0;
                                $sub_arr['item_level'] = $item->sub_item_level;
                                $sub_arr['item_qty'] = $item->sub_item_qty;
                                $sub_arr['item_unit_id'] = $item->sub_item_unit_id;
                                $sub_arr['sub_item_id'] = $item->sub_item_id;
                                $sub_arr['sub_item_add_less'] = $item->sub_item_add_less;
                                $sub_arr['sub_item_qty'] = $item->sub_sub_item_qty;
                                $sub_arr['sub_item_unit_id'] = $item->sub_sub_item_unit_id;
                                $sub_arr['created_at'] = $this->now_time;
                                $sub_arr['updated_at'] = $this->now_time;
                                $sub_arr['updated_by'] = $this->logged_in_id;
                                $sub_arr['user_updated_by'] = $this->session->userdata()['login_user_id'];
                                $sub_arr['created_by'] = $this->logged_in_id;
                                $sub_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                                $this->crud->insert('sub_item_add_less_settings',$sub_arr);
                            }
                            $sub_arr = array();
                            $sub_arr['sales_invoice_id'] =  $parent_id;
                            $sub_arr['item_id'] =  isset($lineitem->item_id)?$lineitem->item_id:0;
                            $sub_arr['item_level'] = $item->sub_item_level;
                            $sub_arr['item_qty'] = $item->sub_item_qty;
                            $sub_arr['item_unit_id'] = $item->sub_item_unit_id;
                            $sub_arr['sub_item_id'] = $item->sub_item_id;
                            $sub_arr['sub_item_add_less'] = $item->sub_item_add_less;
                            $sub_arr['sub_item_qty'] = $item->sub_sub_item_qty;
                            $sub_arr['sub_item_unit_id'] = $item->sub_sub_item_unit_id;
                            $sub_arr['created_at'] = $this->now_time;
                            $sub_arr['updated_at'] = $this->now_time;
                            $sub_arr['updated_by'] = $this->logged_in_id;
                            $sub_arr['company_id'] = $this->logged_in_id;
                            $sub_arr['user_updated_by'] = $this->session->userdata()['login_user_id'];
                            $sub_arr['created_by'] = $this->logged_in_id;
                            $sub_arr['user_created_by'] = $this->session->userdata()['login_user_id'];
                            $this->crud->insert('sub_item_add_less_settings_sales_invoice_wise',$sub_arr);
                        }
                        $this->crud->update_item_current_stock_qty_by_sales(isset($lineitem->item_id)?$lineitem->item_id:0,$parent_id,$voucher_type,$lineitem->item_qty,'add',$sub_item_data);
                    }
                }
            }
        }

        if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])) {
            $voucher_id = $post_data['invoice_id'];
            $operation = 'update';
        } else {
            $voucher_id = $parent_id;
            $operation = 'add';
        }
        $round_off_amount = $invoice_data['round_off_amount'];
        $other_params = array();
        $other_params['invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
        $this->crud->kasar_entry($invoice_data['account_id'],$voucher_id,$voucher_type,$invoice_data['round_off_amount'],$operation,$other_params);
        if(isset($post_data['send_whatsapp_sms'])){
            
            $acc_mobile_no = $this->crud->get_id_by_val('account', 'account_phone', 'account_id', $post_data['account_id']);
            if(!empty($acc_mobile_no)){
                $sms = SEND_SMS_FOR_INVOICE;
                $vars = array(
                    '{{amount}}' => isset($post_data['amount_total']) ? number_format((float)$post_data['amount_total'], 2, '.', '') : '',
                );
                $sms = strtr($sms, $vars);
                $return['send_whatsapp_sms_no'] = $acc_mobile_no;
                $return['send_whatsapp_sms'] = $sms;
//                redirect('https://api.whatsapp.com/send?phone=91'.$acc_mobile_no.'&text='.$sms);
            }
        }
//        echo "<pre>"; print_r($post_data); exit;
        print json_encode($return);
        exit;
    }

    function save_sales_purchase_transaction() {
        $post_data = $this->input->post();
        $response = array();
        $voucher_type = $post_data['voucher_type'];

        if($voucher_type == "sales" || $voucher_type == 'sales2' || $voucher_type == 'sales3' || $voucher_type == 'sales4') {
            $invoice_data = array();
            $invoice_data['account_id'] = $post_data['account_id'];
            $invoice_data['against_account_id'] = $post_data['against_account_id'];
            $invoice_data['sales_invoice_date'] = date('Y-m-d', strtotime($post_data['transaction_date']));
            $invoice_data['sales_invoice_desc'] = $post_data['note'];
            $invoice_data['qty_total'] = 1;
            $invoice_data['pure_amount_total'] = $post_data['amount'];
            $invoice_data['amount_total'] = $post_data['amount'];
            $invoice_data['discount_total'] = 0;
            $invoice_data['cgst_amount_total'] = 0;
            $invoice_data['sgst_amount_total'] = 0;
            $invoice_data['igst_amount_total'] = 0;
            $invoice_data['updated_at'] = $this->now_time;
            $invoice_data['updated_by'] = $this->logged_in_id;
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            
            if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])){
                $where_array = array();
                $where_array['sales_invoice_id'] = $post_data['invoice_id'];
                $result = $this->crud->update('sales_invoice', $invoice_data, $where_array);

                $update_lineitem = array();
                $update_lineitem['price'] = $post_data['amount'];
                $update_lineitem['discounted_price'] = $post_data['amount'];
                $update_lineitem['pure_amount'] = $post_data['amount'];
                $update_lineitem['amount'] = $post_data['amount'];
                $update_lineitem['note'] = $post_data['note'];
                $update_lineitem['updated_at'] = $this->now_time;
                $update_lineitem['updated_by'] = $this->logged_in_id;
                $update_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];

                $where_lineitems = array();
                $where_lineitems['parent_id'] = $post_data['invoice_id'];
                $where_lineitems['module'] = 2;
                $this->crud->update('lineitems',$update_lineitem,$where_lineitems);

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                if($voucher_type == 'sales2'){
                    $this->session->set_flashdata('message','Sales Invoice2 Updated Successfully');
                }else if($voucher_type == 'sales3'){
                    $this->session->set_flashdata('message','Sales Invoice3 Updated Successfully');
                }else if($voucher_type == 'sales4'){
                    $this->session->set_flashdata('message','Sales Invoice4 Updated Successfully');
                }else{
                    $this->session->set_flashdata('message','Sales Invoice Updated Successfully');
                }

            } else {
                
                $invoice_data['created_at'] = $this->now_time;
                $invoice_data['created_by'] = $this->logged_in_id;
                $invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];

                $sales_invoice_no = $this->crud->get_max_number_where('sales_invoice', 'sales_invoice_no', array('created_by' => $this->logged_in_id, 'prefix' => $this->prefix));
                if(empty($sales_invoice_no->sales_invoice_no)){
                    $invoice_data['sales_invoice_no'] = $this->crud->get_id_by_val('user', 'invoice_no_start_from', 'prefix', $this->prefix);
                } else {
                    $invoice_data['sales_invoice_no'] = $sales_invoice_no->sales_invoice_no + 1;    
                }

                $parent_id = $this->crud->insert('sales_invoice',$invoice_data);

                $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $invoice_data['sales_invoice_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date',"setting_value" => $invoice_data['sales_invoice_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
                }

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                if($voucher_type == 'sales2'){
                    $this->session->set_flashdata('message','Sales Invoice2 Added Successfully');
                }else if($voucher_type == 'sales3'){
                    $this->session->set_flashdata('message','Sales Invoice3 Added Successfully');
                }else if($voucher_type == 'sales4'){
                    $this->session->set_flashdata('message','Sales Invoice4 Added Successfully');
                }else{
                    $this->session->set_flashdata('message','Sales Invoice Added Successfully');
                }

                /*--- Line Item ---*/
                $add_lineitem = array();
                $add_lineitem['item_id'] = SALES_INVOICE_ITEM_ID;
                $add_lineitem['item_qty'] = 1;
                $add_lineitem['price'] = $post_data['amount'];
                $add_lineitem['discount'] = 0;
                $add_lineitem['cgst'] = 0;
                $add_lineitem['cgst_amount'] = 0;
                $add_lineitem['sgst'] = 0;
                $add_lineitem['sgst_amount'] = 0;
                $add_lineitem['igst'] = 0;
                $add_lineitem['igst_amount'] = 0;
                $add_lineitem['other_charges'] = 0;
                $add_lineitem['discounted_price'] = $post_data['amount'];
                $add_lineitem['pure_amount'] = $post_data['amount'];
                $add_lineitem['amount'] = $post_data['amount'];
                $add_lineitem['module'] = 2;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['note'] = $post_data['note'];
                $add_lineitem['created_at'] = $this->now_time;
                $add_lineitem['updated_at'] = $this->now_time;
                $add_lineitem['updated_by'] = $this->logged_in_id;
                $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                $add_lineitem['created_by'] = $this->logged_in_id;
                $add_lineitem['company_id'] = $this->logged_in_id;
                $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('lineitems',$add_lineitem);

                $this->crud->update_item_current_stock_qty(SALES_INVOICE_ITEM_ID,$parent_id,'sales',1,'add');
                /*---/Line Item ---*/
            }

        } elseif($voucher_type == "purchase") {
            $invoice_data = array();
            $invoice_data['account_id'] = $post_data['account_id'];
            $invoice_data['against_account_id'] = $post_data['against_account_id'];
            $invoice_data['purchase_invoice_date'] = date('Y-m-d', strtotime($post_data['transaction_date']));
            $invoice_data['purchase_invoice_desc'] = $post_data['note'];
            $invoice_data['qty_total'] = 1;
            $invoice_data['pure_amount_total'] = $post_data['amount'];
            $invoice_data['amount_total'] = $post_data['amount'];
            $invoice_data['discount_total'] = 0;
            $invoice_data['cgst_amount_total'] = 0;
            $invoice_data['sgst_amount_total'] = 0;
            $invoice_data['igst_amount_total'] = 0;
            $invoice_data['invoice_type'] = ($post_data['invoice_type'] == "1"?1:2);
            $invoice_data['created_at'] = $this->now_time;
            $invoice_data['created_by'] = $this->logged_in_id;
            $invoice_data['company_id'] = $this->logged_in_id;
            $invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $invoice_data['updated_at'] = $this->now_time;
            $invoice_data['updated_by'] = $this->logged_in_id;
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])){
                $where_array = array();
                $where_array['purchase_invoice_id'] = $post_data['invoice_id'];
                $result = $this->crud->update('purchase_invoice', $invoice_data, $where_array);

                $update_lineitem = array();
                $update_lineitem['price'] = $post_data['amount'];
                $update_lineitem['discounted_price'] = $post_data['amount'];
                $update_lineitem['pure_amount'] = $post_data['amount'];
                $update_lineitem['amount'] = $post_data['amount'];
                $update_lineitem['note'] = $post_data['note'];
                $update_lineitem['updated_at'] = $this->now_time;
                $update_lineitem['updated_by'] = $this->logged_in_id;
                $update_lineitem['company_id'] = $this->logged_in_id;
                $update_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];

                $where_lineitems = array();
                $where_lineitems['parent_id'] = $post_data['invoice_id'];
                $where_lineitems['module'] = 1;
                $this->crud->update('lineitems',$update_lineitem,$where_lineitems);

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                if($post_data['invoice_type'] == "1") {
                    $this->session->set_flashdata('message','Order Updated Successfully');
                } else {
                    $this->session->set_flashdata('message','Purchase Invoice Updated Successfully');    
                }
                
            } else {
                $purchase_invoice_no = $this->crud->get_max_number('purchase_invoice', 'purchase_invoice_no', $this->logged_in_id);
                $invoice_data['purchase_invoice_no'] = $purchase_invoice_no->purchase_invoice_no + 1;
                $parent_id = $this->crud->insert('purchase_invoice',$invoice_data);

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                if($post_data['invoice_type'] == "1") {
                    $this->session->set_flashdata('message','Order Added Successfully');
                } else {
                    $this->session->set_flashdata('message','Purchase Invoice Added Successfully');    
                }

                /*--- Line Item ---*/
                $add_lineitem = array();
                $add_lineitem['item_id'] = PURCHASE_INVOICE_ITEM_ID;
                $add_lineitem['item_qty'] = 1;
                $add_lineitem['price'] = $post_data['amount'];
                $add_lineitem['discount'] = 0;
                $add_lineitem['cgst'] = 0;
                $add_lineitem['cgst_amount'] = 0;
                $add_lineitem['sgst'] = 0;
                $add_lineitem['sgst_amount'] = 0;
                $add_lineitem['igst'] = 0;
                $add_lineitem['igst_amount'] = 0;
                $add_lineitem['other_charges'] = 0;
                $add_lineitem['discounted_price'] = $post_data['amount'];
                $add_lineitem['pure_amount'] = $post_data['amount'];
                $add_lineitem['amount'] = $post_data['amount'];
                $add_lineitem['module'] = 1;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['note'] = $post_data['note'];
                $add_lineitem['created_at'] = $this->now_time;
                $add_lineitem['updated_at'] = $this->now_time;
                $add_lineitem['updated_by'] = $this->logged_in_id;
                $add_lineitem['company_id'] = $this->logged_in_id;
                $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                $add_lineitem['created_by'] = $this->logged_in_id;
                $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('lineitems',$add_lineitem);

                $this->crud->update_item_current_stock_qty(PURCHASE_INVOICE_ITEM_ID,$parent_id,'purchase',1,'add');
                /*---/Line Item ---*/
            }
        
        } elseif($voucher_type == "credit_note") { //Sales Return
            $invoice_data = array();
            $invoice_data['account_id'] = $post_data['account_id'];
            $invoice_data['against_account_id'] = $post_data['against_account_id'];
            $invoice_data['credit_note_date'] = date('Y-m-d', strtotime($post_data['transaction_date']));
            $invoice_data['invoice_date'] = date('Y-m-d', strtotime($post_data['transaction_date']));
            $invoice_data['credit_note_desc'] = $post_data['note'];
            $invoice_data['qty_total'] = 1;
            $invoice_data['pure_amount_total'] = $post_data['amount'];
            $invoice_data['amount_total'] = $post_data['amount'];
            $invoice_data['discount_total'] = 0;
            $invoice_data['cgst_amount_total'] = 0;
            $invoice_data['sgst_amount_total'] = 0;
            $invoice_data['igst_amount_total'] = 0;
            $invoice_data['updated_at'] = $this->now_time;
            $invoice_data['updated_by'] = $this->logged_in_id;
            $invoice_data['company_id'] = $this->logged_in_id;
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])){
                $where_array = array();
                $where_array['credit_note_id'] = $post_data['invoice_id'];
                $this->crud->update('credit_note', $invoice_data, $where_array);

                $update_lineitem = array();
                $update_lineitem['price'] = $post_data['amount'];
                $update_lineitem['discounted_price'] = $post_data['amount'];
                $update_lineitem['pure_amount'] = $post_data['amount'];
                $update_lineitem['amount'] = $post_data['amount'];
                $update_lineitem['note'] = $post_data['note'];
                $update_lineitem['updated_at'] = $this->now_time;
                $update_lineitem['updated_by'] = $this->logged_in_id;
                $update_lineitem['company_id'] = $this->logged_in_id;
                $update_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];

                $where_lineitems = array();
                $where_lineitems['parent_id'] = $post_data['invoice_id'];
                $where_lineitems['module'] = 3;
                $this->crud->update('lineitems',$update_lineitem,$where_lineitems);

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Credit Note Updated Successfully');
            } else {
                $invoice_data['created_at'] = $this->now_time;
                $invoice_data['created_by'] = $this->logged_in_id;
                $invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];

                $credit_note_no = $this->crud->get_max_number('credit_note', 'credit_note_no', $this->logged_in_id);
                $invoice_data['credit_note_no'] = $credit_note_no->credit_note_no + 1;
                $invoice_data['bill_no'] = $invoice_data['credit_note_no'];
                
                $parent_id = $this->crud->insert('credit_note',$invoice_data);

                $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $invoice_data['credit_note_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date',"setting_value" => $invoice_data['credit_note_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
                }

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Credit Note Added Successfully');

                /*--- Line Item ---*/
                $add_lineitem = array();
                $add_lineitem['item_id'] = CREDIT_NOTE_ITEM_ID;
                $add_lineitem['item_qty'] = 1;
                $add_lineitem['price'] = $post_data['amount'];
                $add_lineitem['discount'] = 0;
                $add_lineitem['cgst'] = 0;
                $add_lineitem['cgst_amount'] = 0;
                $add_lineitem['sgst'] = 0;
                $add_lineitem['sgst_amount'] = 0;
                $add_lineitem['igst'] = 0;
                $add_lineitem['igst_amount'] = 0;
                $add_lineitem['other_charges'] = 0;
                $add_lineitem['discounted_price'] = $post_data['amount'];
                $add_lineitem['pure_amount'] = $post_data['amount'];
                $add_lineitem['amount'] = $post_data['amount'];
                $add_lineitem['module'] = 3;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['note'] = $post_data['note'];
                $add_lineitem['created_at'] = $this->now_time;
                $add_lineitem['updated_at'] = $this->now_time;
                $add_lineitem['updated_by'] = $this->logged_in_id;
                $add_lineitem['company_id'] = $this->logged_in_id;
                $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                $add_lineitem['created_by'] = $this->logged_in_id;
                $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('lineitems',$add_lineitem);

                $this->crud->update_item_current_stock_qty(CREDIT_NOTE_ITEM_ID,$parent_id,'credit_note',1,'add');
                /*---/Line Item ---*/
            }
        
        } elseif($voucher_type == "debit_note") { //Purchase Return
            $invoice_data = array();
            $invoice_data['account_id'] = $post_data['account_id'];
            $invoice_data['against_account_id'] = $post_data['against_account_id'];
            $invoice_data['debit_note_date'] = date('Y-m-d', strtotime($post_data['transaction_date']));
            $invoice_data['invoice_date'] = date('Y-m-d', strtotime($post_data['transaction_date']));
            $invoice_data['debit_note_desc'] = $post_data['note'];
            $invoice_data['qty_total'] = 1;
            $invoice_data['pure_amount_total'] = $post_data['amount'];
            $invoice_data['amount_total'] = $post_data['amount'];
            $invoice_data['discount_total'] = 0;
            $invoice_data['cgst_amount_total'] = 0;
            $invoice_data['sgst_amount_total'] = 0;
            $invoice_data['igst_amount_total'] = 0;
            $invoice_data['updated_at'] = $this->now_time;
            $invoice_data['updated_by'] = $this->logged_in_id;
            $invoice_data['company_id'] = $this->logged_in_id;
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])){
                $where_array = array();
                $where_array['debit_note_id'] = $post_data['invoice_id'];
                $this->crud->update('debit_note', $invoice_data, $where_array);

                $update_lineitem = array();
                $update_lineitem['price'] = $post_data['amount'];
                $update_lineitem['discounted_price'] = $post_data['amount'];
                $update_lineitem['pure_amount'] = $post_data['amount'];
                $update_lineitem['amount'] = $post_data['amount'];
                $update_lineitem['note'] = $post_data['note'];
                $update_lineitem['updated_at'] = $this->now_time;
                $update_lineitem['company_id'] = $this->logged_in_id;
                $update_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];

                $where_lineitems = array();
                $where_lineitems['parent_id'] = $post_data['invoice_id'];
                $where_lineitems['module'] = 4;
                $this->crud->update('lineitems',$update_lineitem,$where_lineitems);

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Debit Note Updated Successfully');
            } else {
                $invoice_data['created_at'] = $this->now_time;
                $invoice_data['created_by'] = $this->logged_in_id;
                $invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];

                $debit_note_no = $this->crud->get_max_number('debit_note', 'debit_note_no', $this->logged_in_id);
                $invoice_data['debit_note_no'] = $debit_note_no->debit_note_no + 1;
                $invoice_data['bill_no'] = $invoice_data['debit_note_no'];
                
                $parent_id = $this->crud->insert('debit_note',$invoice_data);

                $response['status'] = "success";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Debit Note Added Successfully');

                /*--- Line Item ---*/
                $add_lineitem = array();
                $add_lineitem['item_id'] = DEBIT_NOTE_ITEM_ID;
                $add_lineitem['item_qty'] = 1;
                $add_lineitem['price'] = $post_data['amount'];
                $add_lineitem['discount'] = 0;
                $add_lineitem['cgst'] = 0;
                $add_lineitem['cgst_amount'] = 0;
                $add_lineitem['sgst'] = 0;
                $add_lineitem['sgst_amount'] = 0;
                $add_lineitem['igst'] = 0;
                $add_lineitem['igst_amount'] = 0;
                $add_lineitem['other_charges'] = 0;
                $add_lineitem['discounted_price'] = $post_data['amount'];
                $add_lineitem['pure_amount'] = $post_data['amount'];
                $add_lineitem['amount'] = $post_data['amount'];
                $add_lineitem['module'] = 4;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['note'] = $post_data['note'];
                $add_lineitem['created_at'] = $this->now_time;
                $add_lineitem['updated_at'] = $this->now_time;
                $add_lineitem['updated_by'] = $this->logged_in_id;
                $add_lineitem['company_id'] = $this->logged_in_id;
                $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                $add_lineitem['created_by'] = $this->logged_in_id;
                $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('lineitems',$add_lineitem);

                $this->crud->update_item_current_stock_qty(DEBIT_NOTE_ITEM_ID,$parent_id,'debit_note',1,'add');
                /*---/Line Item ---*/
            }
        }
        echo json_encode($response);
        exit();
    }
    
    function get_sub_item_data(){
        $post_data = $this->input->post();
        $config['table'] = 'sub_item_add_less_settings setting';
        $config['select'] = 'setting.*,si.item_name as sub_item_name,spu.pack_unit_name as sub_item_unit';
        $config['joins'][] = array('join_table' => 'item si', 'join_by' => 'si.item_id = setting.sub_item_id');
        $config['joins'][] = array('join_table' => 'pack_unit spu', 'join_by' => 'spu.pack_unit_id = setting.sub_item_unit_id');
        if(empty($post_data['item_id'])) {
                $post_data['item_id'] = -1;
        }
        $config['wheres'][] = array('column_name' => 'setting.item_id', 'column_value' => $post_data['item_id']);

        $config['column_search'] = array('setting.sub_item_qty','setting.sub_item_add_less','si.item_name','spu.pack_unit_name');
        $config['order'] = array('setting.id' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        if(!empty($list)){
            foreach ($list as $item){
                $item_arr = array();
                $item_arr['sub_item_qty'] = $item->item_qty;
                $item_arr['sub_item_unit_id'] = $item->item_unit_id;
                $item_arr['sub_item_id'] = $item->sub_item_id;
                $item_arr['sub_item_add_less'] = $item->sub_item_add_less;
                $item_arr['sub_sub_item_unit_id'] = $item->sub_item_unit_id;
                $item_arr['sub_sub_item_qty'] = $item->sub_item_qty;
                $item_arr['sub_item_level'] = $item->item_level;
                $item_arr['sub_item_name'] = $item->sub_item_name;
                $item_arr['sub_add_less_name'] = ($item->sub_item_add_less == '1') ? 'Add' : 'Less';
                $item_arr['sub_unit_name'] = $item->sub_item_unit;
                $data[] = $item_arr;
            }
        }
        echo json_encode($data);
        exit;
    }

    function order_type2()
    {
        $data = array();
        $line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 2 AND setting_value = 1');
        $invoice_line_item_fields = array();
        if(!empty($line_item_fields)) {
            foreach ($line_item_fields as $value) {
                $invoice_line_item_fields[] = $value->setting_key;
            }
        }
        $data['invoice_line_item_fields'] = $invoice_line_item_fields;
        $data['type'] = 'Order';
        $data['page_title'] = 'Order';
        if (isset($_POST['purchase_invoice_id'])) {
            if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"edit")) {
                $where = array('purchase_invoice_id' => $_POST['purchase_invoice_id']);
                $purchase_invoice_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                $purchase_invoice_data[0]->invoice_date = $purchase_invoice_data[0]->purchase_invoice_date;
                $purchase_invoice_data[0]->invoice_desc = $purchase_invoice_data[0]->purchase_invoice_desc;
                $data['invoice_id'] = $_POST['purchase_invoice_id'];
                $data['invoice_data'] = $purchase_invoice_data[0];

                $lineitems = '';
                $where = array('module' => '1', 'parent_id' => $_POST['purchase_invoice_id']);
                $invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                foreach ($invoice_lineitems as $invoice_lineitem) {
                    $invoice_lineitem->pure_amount = $invoice_lineitem->pure_amount;
                    $invoice_lineitem->cgst_amt = $invoice_lineitem->cgst_amount;
                    $invoice_lineitem->sgst_amt = $invoice_lineitem->sgst_amount;
                    $invoice_lineitem->igst_amt = $invoice_lineitem->igst_amount;
                    $lineitems .= "'" . json_encode($invoice_lineitem) . "',";
                }
                $data['invoice_lineitems'] = $lineitems;
                set_page('transaction/order_type2', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) {
                set_page('transaction/order_type2', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }
    }

    function save_order_type2()
    {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode('['.$post_data['line_items_data'].']');
        
        $invoice_data = array();     
        $voucher_type = "purchase";

        if(!isset($post_data['prefix'])) {
            $post_data['prefix'] = '';
        }

        $module = 1;
        $invoice_data['invoice_type'] = $post_data['invoice_type']; 
        $invoice_data['purchase_invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
        $invoice_data['account_id'] = $post_data['account_id'];
        $invoice_data['qty_total'] = $post_data['qty_total'];
        $invoice_data['pure_amount_total'] = $post_data['pure_amount_total'];
        $invoice_data['discount_total'] = ($post_data['pure_amount_total'] - $post_data['discounted_price_total']);
        $invoice_data['cgst_amount_total'] = 0;
        $invoice_data['sgst_amount_total'] = 0;
        $invoice_data['igst_amount_total'] = 0;
        $invoice_data['other_charges_total'] = 0;
        $invoice_data['round_off_amount'] = (isset($post_data['round_off_amount'])?$post_data['round_off_amount']:0);
        $invoice_data['amount_total'] = ($post_data['discounted_price_total'] + $invoice_data['round_off_amount']);


        $dateranges = $this->crud->getFromSQL('SELECT `daterange` FROM `locked_daterange` WHERE `user_id`="'.$this->logged_in_id.'" ');
        if(!empty($dateranges)){
            foreach($dateranges as $daterange){
                $pieces = explode(" ", $daterange->daterange);
                $from = date('d-m-Y', strtotime($pieces[0]));
                $to = date('d-m-Y', strtotime($pieces[2]));
                $invoice_date = date('d-m-Y', strtotime($post_data['invoice_date']));
                if(strtotime($invoice_date) >= strtotime($from) && strtotime($invoice_date) <= strtotime($to)){
                    $return['error'] = "Locked_Date";
                    print json_encode($return);
                    exit;
                }
            }    
        }

        if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])) {

            $invoice_data['updated_at'] = $this->now_time;
            $invoice_data['updated_by'] = $this->logged_in_id;
            $invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];

            $where_array['purchase_invoice_id'] = $post_data['invoice_id'];
            $this->crud->update('purchase_invoice', $invoice_data, $where_array);
            $this->session->set_flashdata('message','Order Updated Successfully');
            $this->session->set_flashdata('success',true);
            
            $return['success'] = "Updated";
            
            $parent_id = $post_data['invoice_id'];
            
            if(isset($post_data['deleted_lineitem_id']) && !empty($post_data['deleted_lineitem_id'])){
                $deleted_lineitem_ids = $post_data['deleted_lineitem_id'];
                $this->crud->delete_where_in('lineitems', 'id', $deleted_lineitem_ids);
            }

            foreach($line_items_data[0] as $lineitem){
                $add_lineitem = array();
                $add_lineitem['item_group_id'] = isset($lineitem->item_group_id) ? $lineitem->item_group_id : null;
                $add_lineitem['cat_id'] = isset($lineitem->cat_id) ? $lineitem->cat_id : NULL;
                $add_lineitem['sub_cat_id'] = isset($lineitem->sub_cat_id) ? $lineitem->sub_cat_id : NULL;
                $add_lineitem['item_id'] = $lineitem->item_id;
                $add_lineitem['item_qty'] = $lineitem->item_qty;
                $add_lineitem['unit_id'] = isset($lineitem->unit_id)?$lineitem->unit_id:NULL;
                $add_lineitem['price'] = $lineitem->price;
                $add_lineitem['item_mrp'] = isset($lineitem->item_mrp)?$lineitem->item_mrp:NULL;
                $add_lineitem['pure_amount'] = $lineitem->pure_amount;
                $add_lineitem['discount_type'] = isset($lineitem->discount_type)?$lineitem->discount_type:1;
                $add_lineitem['discount'] = isset($lineitem->discount)?$lineitem->discount:NULL;
                $add_lineitem['discounted_price'] = isset($lineitem->discounted_price)?$lineitem->discounted_price:$lineitem->pure_amount;
                $add_lineitem['cgst'] = isset($lineitem->cgst)?$lineitem->cgst:NULL;
                $add_lineitem['cgst_amount'] = isset($lineitem->cgst_amt)?$lineitem->cgst_amt:NULL;
                $add_lineitem['sgst'] = isset($lineitem->sgst)?$lineitem->sgst:NULL;
                $add_lineitem['sgst_amount'] = isset($lineitem->sgst_amt)?$lineitem->sgst_amt:NULL;
                $add_lineitem['igst'] = isset($lineitem->igst)?$lineitem->igst:NULL;
                $add_lineitem['igst_amount'] = isset($lineitem->igst_amt)?$lineitem->igst_amt:NULL;
                $add_lineitem['other_charges'] = isset($lineitem->other_charges)?$lineitem->other_charges:NULL;
                $add_lineitem['amount'] = isset($lineitem->amount)?$lineitem->amount:$lineitem->pure_amount;
                $add_lineitem['module'] = $module;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['rate_for_itax'] = isset($lineitem->rate_for_itax)?$lineitem->rate_for_itax:0;
                $add_lineitem['price_for_itax'] = isset($lineitem->price_for_itax)?$lineitem->price_for_itax:0;
                $add_lineitem['igst_for_itax'] = isset($lineitem->igst_for_itax)?$lineitem->igst_for_itax:0;
                $add_lineitem['note'] = isset($lineitem->note)?$lineitem->note:'';
                $add_lineitem['line_item_des'] = isset($lineitem->line_item_des)?$lineitem->line_item_des:NULL;
                if(isset($lineitem->id) && !empty($lineitem->id)){
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $this->logged_in_id;
                    $add_lineitem['company_id'] = $this->logged_in_id;
                    $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                    $where_id['id'] = $lineitem->id;
                    $this->crud->update('lineitems', $add_lineitem, $where_id);
                } else {
                    $add_lineitem['created_at'] = $this->now_time;
                    $add_lineitem['created_by'] = $this->logged_in_id;
                    $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $this->logged_in_id;
                    $add_lineitem['company_id'] = $this->logged_in_id;
                    $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                    $this->crud->insert('lineitems',$add_lineitem);
                }
            }
        } else {            
            $invoice_data['created_at'] = $this->now_time;
            $invoice_data['created_by'] = $this->logged_in_id;
            $invoice_data['company_id'] = $this->logged_in_id;
            $invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];

            $this->crud->insert('purchase_invoice', $invoice_data);
            $this->session->set_flashdata('message','Purchase Invoice Added Successfully');
            $this->session->set_flashdata('success',true);
            $return['success'] = "Added";

            $parent_id = $this->db->insert_id();

            foreach($line_items_data[0] as $lineitem){
                $add_lineitem = array();
                $add_lineitem['item_group_id'] = isset($lineitem->item_group_id) ? $lineitem->item_group_id : null;
                $add_lineitem['cat_id'] = isset($lineitem->cat_id) ? $lineitem->cat_id : NULL;
                $add_lineitem['sub_cat_id'] = isset($lineitem->sub_cat_id) ? $lineitem->sub_cat_id : NULL;
                $add_lineitem['item_id'] = $lineitem->item_id;
                $add_lineitem['item_qty'] = $lineitem->item_qty;
                $add_lineitem['unit_id'] = isset($lineitem->unit_id)?$lineitem->unit_id:NULL;
                $add_lineitem['price'] = $lineitem->price;
                $add_lineitem['item_mrp'] = isset($lineitem->item_mrp)?$lineitem->item_mrp:NULL;
                $add_lineitem['pure_amount'] = $lineitem->pure_amount;
                $add_lineitem['discount_type'] = isset($lineitem->discount_type)?$lineitem->discount_type:1;
                $add_lineitem['discount'] = isset($lineitem->discount)?$lineitem->discount:NULL;
                $add_lineitem['discounted_price'] = isset($lineitem->discounted_price)?$lineitem->discounted_price:$lineitem->pure_amount;
                $add_lineitem['cgst'] = isset($lineitem->cgst)?$lineitem->cgst:NULL;
                $add_lineitem['cgst_amount'] = isset($lineitem->cgst_amt)?$lineitem->cgst_amt:NULL;
                $add_lineitem['sgst'] = isset($lineitem->sgst)?$lineitem->sgst:NULL;
                $add_lineitem['sgst_amount'] = isset($lineitem->sgst_amt)?$lineitem->sgst_amt:NULL;
                $add_lineitem['igst'] = isset($lineitem->igst)?$lineitem->igst:NULL;
                $add_lineitem['igst_amount'] = isset($lineitem->igst_amt)?$lineitem->igst_amt:NULL;
                $add_lineitem['other_charges'] = isset($lineitem->other_charges)?$lineitem->other_charges:NULL;
                $add_lineitem['amount'] = isset($lineitem->amount)?$lineitem->amount:$lineitem->pure_amount;
                $add_lineitem['module'] = $module;
                $add_lineitem['parent_id'] = $parent_id;
                $add_lineitem['rate_for_itax'] = isset($lineitem->rate_for_itax)?$lineitem->rate_for_itax:0;
                $add_lineitem['price_for_itax'] = isset($lineitem->price_for_itax)?$lineitem->price_for_itax:0;
                $add_lineitem['igst_for_itax'] = isset($lineitem->igst_for_itax)?$lineitem->igst_for_itax:0;
                $add_lineitem['note'] = isset($lineitem->note)?$lineitem->note:'';
                $add_lineitem['line_item_des'] = isset($lineitem->line_item_des)?$lineitem->line_item_des:NULL;
                $add_lineitem['created_at'] = $this->now_time;
                $add_lineitem['updated_at'] = $this->now_time;
                $add_lineitem['updated_by'] = $this->logged_in_id;
                $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                $add_lineitem['created_by'] = $this->logged_in_id;
                $add_lineitem['company_id'] = $this->logged_in_id;
                $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('lineitems',$add_lineitem);
            }
        }

        if(isset($post_data['invoice_id']) && !empty($post_data['invoice_id'])) {
            $voucher_id = $post_data['invoice_id'];
            $operation = 'update';
        } else {
            $voucher_id = $parent_id;
            $operation = 'add';
        }
        $round_off_amount = $invoice_data['round_off_amount'];
        $other_params = array();
        $other_params['invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
        $this->crud->kasar_entry($invoice_data['account_id'],$voucher_id,$voucher_type,$invoice_data['round_off_amount'],$operation,$other_params);

        print json_encode($return);
        exit;        
    }
    function get_discount_data(){
        $return = array();
        $post_data = $this->input->post();
//        echo "<pre>"; print_r($post_data); exit;
        $date = date('Y-m-d',strtotime($post_data['sales_date']));
        $get_dis_data = '';
        if(!empty($post_data['item_id'])){
            $get_dis_data = $this->crud->getFromSQL("SELECT * FROM discount_detail WHERE discount_for_id = 1 AND account_id = ".$post_data['acc_id']." AND from_date >= '".$date."' AND item_id = ".$post_data['item_id']." LIMIT 1 ");
        }
        if(empty($get_dis_data)){
            if(!empty($post_data['cat_id'])){
                $get_dis_data = $this->crud->getFromSQL("SELECT * FROM discount_detail WHERE discount_for_id = 1 AND account_id = ".$post_data['acc_id']." AND from_date >= '".$date."' AND category_id = ".$post_data['cat_id']." LIMIT 1 ");
            }
            if(empty($get_dis_data)){
                if(!empty($post_data['item_group_id'])){
                    $get_dis_data = $this->crud->getFromSQL("SELECT * FROM discount_detail WHERE discount_for_id = 1 AND account_id = ".$post_data['acc_id']." AND from_date >= '".$date."' AND item_group_id = ".$post_data['item_group_id']." LIMIT 1 ");
//                    echo $this->db->last_query(); exit;
                }
            }
        }
//        echo "<pre>"; print_r($get_dis_data); exit;
        if(!empty($get_dis_data)){
            $return['discount_type'] = 1;
            if(!empty($get_dis_data[0]->discount_per_1)){
                $return['discount_type'] = 1;
                $return['discount_per_1'] = (!empty($get_dis_data[0]->discount_per_1)) ? $get_dis_data[0]->discount_per_1 : '0';
                $return['discount_per_2'] = (!empty($get_dis_data[0]->discount_per_2)) ? $get_dis_data[0]->discount_per_2 : '0';
                
            } else  {
                if(!empty($get_dis_data[0]->rate)){
                    $return['discount_type'] = 2;
                    $return['discount_rate'] = (!empty($get_dis_data[0]->rate)) ? $get_dis_data[0]->rate : '0';
                }
            }
        }
        print json_encode($return);
        exit;        
    }

    function get_item_hsn_data(){
        $hsn = '';
        if(isset($_POST['item_id'])){
            $hsnid = $this->crud->get_column_value_by_id('item','hsn_code',array('item_id'=>$_POST['item_id']));
            if($hsnid){
                $hsn =  $this->crud->get_column_value_by_id('hsn','hsn',array('hsn_id'=>$hsnid));
                $gst_per =  $this->crud->get_column_value_by_id('hsn','gst_per',array('hsn_id'=>$hsnid));
            }
        }
        $return['hsn'] = $hsn;
        $return['gst_per'] = $gst_per;
        print json_encode($return);
        exit;
    }

    function day_book(){
        $data = array();
        set_page('transaction/day_book', $data);
    }

    function day_book_datatable(){
        $from_date = '';
        $to_date = '';
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }
        $config['table'] = 'transaction_entry t';
        $config['select'] = 't.*,a.account_name,aa.account_name as cash_bank_acc';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.to_account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = t.from_account_id', 'join_type' => 'left');
        $config['column_order'] = array(null, 't.transaction_date', 't.amount');
        $config['column_search'] = array('DATE_FORMAT(t.transaction_date,"%d-%m-%Y")', 't.amount');
        $config['wheres'][] = array('column_name' => 't.created_by', 'column_value' => $this->logged_in_id);
        // $config['wheres'][] = array('column_name' => 't.transaction_type', 'column_value' => '1');
        if(isset($_POST['site_id']) && $_POST['site_id'] != ''){
            $config['wheres'][] = array('column_name' => 't.site_id', 'column_value' => $_POST['site_id']);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 't.transaction_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 't.transaction_date <=', 'column_value' => $to_date);
        }
        $config['order'] = array('t.transaction_date' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        
        $data = array();
        foreach ($list as $transaction) {
            $row = array();
            $ttype = array(
                1=>['name' => 'Payment',
                    'edit' => 'transaction/payment/',
                    'delete' => 'transaction/delete_transaction/',
                ],2=>['name' => 'Recepit',
                    'edit' => 'transaction/receipt/',
                    'delete' => 'transaction/delete_receipt_transaction/',
                ],3=>['name' => 'Contra',
                    'edit' => 'contra/contra/',
                    'delete' => 'transaction/delete_transaction/',
                ],4=>['name' => 'Journal',
                    'edit' => (!empty($transaction->journal_id)) ? 'journal/journal_type2/' : 'journal/journal/',
                    'delete' => 'transaction/delete_transaction/',
                ],
            );
            $type = '';
            $type = $ttype[$transaction->transaction_type]['name'];
            $action = '';
            $action .= '<a href="' . base_url($ttype[$transaction->transaction_type]['edit'] . $transaction->transaction_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';    
            $action .= ' <a href="javascript:void(0);" class="delete_transaction" data-href="' . base_url($ttype[$transaction->transaction_type]['delete'] . $transaction->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            $row[] = $action;
            $row[] = (!empty(strtotime($transaction->transaction_date))) ? date('d-m-Y', strtotime($transaction->transaction_date)) : '';
            $row[] = $type;
            $row[] = $transaction->cash_bank_acc;
            $row[] = $transaction->account_name;
            $debit = ($transaction->transaction_type == 1 || $transaction->is_credit_debit == 1) ? $transaction->amount : '';
            $row[] = $debit;
            $credit = '';
            if($transaction->transaction_type != 1){
                if($transaction->transaction_type == 4 && $transaction->is_credit_debit){
                    if($transaction->is_credit_debit == 2){
                        $credit = $transaction->amount;
                    }
                }else{
                    $credit = $transaction->amount;
                }
            }
            $row[] = $credit;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw'])?$_POST['draw']:0,
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
}
