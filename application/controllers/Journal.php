<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Journal extends CI_Controller {

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

    function journal($transaction_id = '') {
        $data = array();
        $transaction_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'journal_date'));
        if(!empty($transaction_date) && strtotime($transaction_date) > 0) {
            $data['transaction_date'] = date('d-m-Y',strtotime($transaction_date));
        } else {
            $data['transaction_date'] = date('d-m-Y');
        }

        if (isset($transaction_id) && !empty($transaction_id)) {
            if($this->applib->have_access_role(MODULE_JOURNAL_ID,"edit")) {
                $transaction_data = $this->crud->get_row_by_id('transaction_entry', array('transaction_id' => $transaction_id));
                $transaction_data = $transaction_data[0];
                $data['transaction_data'] = $transaction_data;
                set_page('journal/journal', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_JOURNAL_ID,"add")) {
                set_page('journal/journal', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }            
        }
    }

    function journal_type2($journal_id = '') {
        $data = array();

        $journal_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'journal_date'));
        if(!empty($journal_date) && strtotime($journal_date) > 0) {
            $data['journal_date'] = date('d-m-Y',strtotime($journal_date));
        } else {
            $data['journal_date'] = date('d-m-Y');
        }
        
        $journal_detail = new \stdClass();
        if (!empty($journal_id)) {
            if($this->applib->have_access_role(MODULE_JOURNAL_ID,"edit")) {
                $journal_data = $this->crud->get_row_by_id('journal', array('journal_id' => $journal_id));
                $journal_items = $this->crud->get_row_by_id('transaction_entry', array('journal_id' => $journal_id));
                $data['journal_data'] = $journal_data[0];
                $lineitems = array();
                foreach ($journal_items as $journal_item) {
                    $journal_detail->transaction_id = $journal_item->transaction_id;
                    $journal_detail->is_credit_debit = $journal_item->is_credit_debit;
                    $journal_detail->account_id = $journal_item->account_id;
                    $journal_detail->account_name = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $journal_item->account_id));
                    $journal_detail->amount = $journal_item->amount;
                    $journal_detail->note = $journal_item->note;
                    
                    $lineitems[] = json_encode($journal_detail);
                }
                $data['journal_detail'] = implode(',', $lineitems);
                set_page('journal/journal_type2', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_JOURNAL_ID,"journal type 2")) {
                set_page('journal/journal_type2', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }
    }

    function save_journal_type2() {
        $response = array();
        $post_data = $this->input->post();
        /*echo json_encode($post_data);
        exit();*/
        $line_items_data = json_decode($post_data['line_items_data']);

        if (isset($post_data['journal_id'])) {
            $journal_id = $post_data['journal_id'];
            $journal_data = array();
            $journal_data['journal_date'] = date("Y-m-d",strtotime($post_data['journal_date']));
            $journal_data['updated_at'] = $this->now_time;
            $journal_data['updated_by'] = $this->logged_in_id;
            $journal_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $journal_where = array('journal_id' => $journal_id);
            $this->crud->update('journal',$journal_data,$journal_where);

            if (!empty($line_items_data)) {
                $transaction_ids = array();
                foreach ($line_items_data as $lineitem) {
                    $tran_data = array();
                    $tran_data['transaction_date'] = date("Y-m-d",strtotime($post_data['journal_date']));
                    $tran_data['account_id'] = $lineitem->account_id;
                    if($lineitem->is_credit_debit == 1) {
                        $tran_data['from_account_id'] = $lineitem->account_id;
                        $tran_data['to_account_id'] = NULL;
                    } else {
                        $tran_data['from_account_id'] = NULL;
                        $tran_data['to_account_id'] = $lineitem->account_id;
                    }
                    $tran_data['transaction_type'] = '4';
                    $tran_data['amount'] = $lineitem->amount;
                    $tran_data['site_id'] = $lineitem->site_id;
                    $tran_data['note'] = $lineitem->note;
                    $tran_data['journal_id'] = $journal_id;
                    $tran_data['is_credit_debit'] = $lineitem->is_credit_debit;

                    if($lineitem->transaction_id > 0) {
                        $tran_data['updated_at'] = $this->now_time;
                        $tran_data['updated_by'] = $this->logged_in_id;
                        $tran_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
                        $tran_where = array('transaction_id' => $lineitem->transaction_id);
                        $this->crud->update('transaction_entry',$tran_data,$tran_where);
                        $transaction_ids[] = $lineitem->transaction_id;
                    } else {
                        $contra = $this->crud->get_max_number_from_table('transaction_entry','contra_no');
                        $contra_no = 1;
                        if($contra->contra_no > 0){
                            $contra_no = $contra->contra_no + 1;
                        }
                        $tran_data['contra_no'] = $contra_no;
                        $tran_data['created_at'] = $this->now_time;
                        $tran_data['created_by'] = $this->logged_in_id;
                        $tran_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                        $transaction_id = $this->crud->insert('transaction_entry',$tran_data);
                        $transaction_ids[] = $transaction_id;
                    }
                }

                if(!empty($transaction_ids)) {
                    $this->crud->execuetSQL("DELETE FROM transaction_entry WHERE journal_id='".$journal_id."' AND transaction_id NOT IN(".implode(',',$transaction_ids).")");
                }
            }
            $response['status'] = "success";
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Transaction Updated Successfully');
        
        } else {
            $journal_data = array();
            $journal_data['journal_date'] = date("Y-m-d",strtotime($post_data['journal_date']));
            $journal_data['created_at'] = $this->now_time;
            $journal_data['created_by'] = $this->logged_in_id;
            $journal_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $journal_id = $this->crud->insert('journal', $journal_data);

            $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'journal_date'));
            if(!empty($company_settings_id)) {
                $this->crud->update('company_settings',array("setting_value" => $journal_data['journal_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
            } else {
                $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'journal_date',"setting_value" => $journal_data['journal_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
            }

            if (!empty($line_items_data)) {
                foreach ($line_items_data as $lineitem) {
                    $contra = $this->crud->get_max_number_from_table('transaction_entry','contra_no');
                    $contra_no = 1;
                    if($contra->contra_no > 0){
                        $contra_no = $contra->contra_no + 1;
                    }
                    $tran_data = array();
                    $tran_data['transaction_date'] = date("Y-m-d",strtotime($post_data['journal_date']));
                    $tran_data['account_id'] = $lineitem->account_id;
                    if($lineitem->is_credit_debit == 1) {
                        $tran_data['from_account_id'] = $lineitem->account_id;
                        $tran_data['to_account_id'] = NULL;
                    } else {
                        $tran_data['from_account_id'] = NULL;
                        $tran_data['to_account_id'] = $lineitem->account_id;
                    }
                    $tran_data['transaction_type'] = '4';
                    $tran_data['amount'] = $lineitem->amount;
                    $tran_data['site_id'] = $lineitem->site_id;
                    $tran_data['contra_no'] = $contra_no;
                    $tran_data['note'] = $lineitem->note;
                    $tran_data['journal_id'] = $journal_id;
                    $tran_data['is_credit_debit'] = $lineitem->is_credit_debit;
                    $tran_data['created_at'] = $this->now_time;
                    $tran_data['created_by'] = $this->logged_in_id;
                    $tran_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                    $this->crud->insert('transaction_entry', $tran_data);
                }
            }
            $response['status'] = "success";
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Journal Added Successfully');
        }

        echo json_encode($response);
        exit;
    }
    
    function journal_list() {
        if($this->applib->have_access_role(MODULE_JOURNAL_ID,"view")) {
            $data = array();
            set_page('journal/journal_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    } 
    
    function save_journal() {
        $post_data = $this->input->post();
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
            $post_data['transaction_type'] = '4';
            $post_data['transaction_date'] = (isset($post_data['transaction_date']) && !empty($post_data['transaction_date'])) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : null;
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $result = $this->crud->insert('transaction_entry', $post_data);

            $company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'journal_date'));
            if(!empty($company_settings_id)) {
                $this->crud->update('company_settings',array("setting_value" => $post_data['transaction_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
            } else {
                $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'journal_date',"setting_value" => $post_data['transaction_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
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

    function journal_datatable() {
        $config['table'] = 'transaction_entry t';
        $config['select'] = 't.*,a.account_name as from_bank_cash,aa.account_name as to_bank_cash';        
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.from_account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = t.to_account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 't.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 't.transaction_type', 'column_value' => '4');
        $config['column_order'] = array(null, 't.transaction_date', 't.from_account_id','t.to_account_id', 't.amount');
        $config['column_search'] = array('DATE_FORMAT(t.transaction_date,"%d-%m-%Y")','t.from_account_id','t.to_account_id', 't.amount');
        $config['order'] = array('t.transaction_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        
        $isEdit = $this->applib->have_access_role(MODULE_JOURNAL_ID, "edit");
        $isDelete = $this->applib->have_access_role(MODULE_JOURNAL_ID, "delete");

        $data = array();
        foreach ($list as $transaction) {
            $row = array();
            $action = '';
            if($isEdit) {
                if(!empty($transaction->journal_id)) {
                    $action .= '<a href="' . base_url("journal/journal_type2/" . $transaction->journal_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                } else {
                    $action .= '<a href="' . base_url("journal/journal/" . $transaction->transaction_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
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
