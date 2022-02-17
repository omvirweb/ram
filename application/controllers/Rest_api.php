<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sales
 * &@Rest_api Crud $crud
 * &@property AppLib $applib
 */
class Rest_api extends CI_Controller {

    public $logged_in_id = null;

    function __construct() 
    {
        parent::__construct();
        $this->load->model('Appmodel', 'app_model');
        $this->load->model('Crud', 'crud');
        $this->now_time = date('Y-m-d H:i:s');
    }

    function store_to_money_trans() 
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time',0);
        set_time_limit(0);

        $post_data = json_decode(file_get_contents('php://input'), true);
        $account_res = isset($post_data['account_data'])?$post_data['account_data']:array();
        if(!empty($account_res)) {
            $company_id = 43; //MN&CO.1
            $account_group_id = 42; //Sundry Debtors
            $against_account_id = 152; //BILL

            foreach ($account_res as $key => $acc_row) {
                $desc  = isset($acc_row['desc'])?$acc_row['desc']:'Store To Money Transaction';

                $acc_name = $acc_row['firstName'].' '.$acc_row['middleName'].' '.$acc_row['lastName'];
                $acc_name = trim($acc_name);
                $acc_id = $this->crud->getFromSQL("SELECT account_id FROM account WHERE account_name = '".$acc_name."' AND created_by='".$company_id."' LIMIT 1");
                if(empty($acc_id)) {
                    $acc_data = array();
                    $acc_data['account_name'] = $acc_name;
                    $acc_data['account_group_id'] = $account_group_id;
                    $acc_data['account_email_ids'] = $acc_row['email'];
                    $acc_data['account_phone'] = $acc_row['mobile'];
                    $acc_data['account_address'] = $acc_row['address'];
                    $acc_data['added_from'] = $acc_row['added_from'];
                    $acc_data['consider_in_pl'] = 1;
                    $acc_data['created_by'] = $company_id;
                    $acc_data['user_created_by'] = $company_id;
                    $acc_data['created_at'] = $this->now_time;
                    $acc_id = $this->crud->insert('account', $acc_data);
                } else {
                    $acc_id = $acc_id[0]->account_id;
                }

                $acc_row['tran_amount'] = round($acc_row['tran_amount'],2);

                if($acc_row['tran_amount'] > 0) { //Sales Return

                    $credit_note_no = $this->crud->get_max_number('credit_note', 'credit_note_no', $company_id);
                    if(!empty($credit_note_no->credit_note_no)){
                        $new_credit_note_no = $credit_note_no->credit_note_no + 1;
                    } else {
                        $new_credit_note_no = 1;
                    }
                    $entry_arr = array();
                    $entry_arr['credit_note_no'] = $new_credit_note_no;
                    $entry_arr['bill_no'] = $new_credit_note_no;
                    $entry_arr['invoice_date'] = date('Y-m-d');
                    $entry_arr['account_id'] = $acc_id;
                    $entry_arr['against_account_id'] = $against_account_id;
                    $entry_arr['credit_note_date'] = date('Y-m-d');
                    $entry_arr['qty_total'] = '1';
                    $entry_arr['pure_amount_total'] = $acc_row['tran_amount'];
                    $entry_arr['amount_total'] = $acc_row['tran_amount'];
                    $entry_arr['credit_note_desc'] = $desc;
                    $entry_arr['created_at'] = $this->now_time;
                    $entry_arr['created_by'] = $company_id;
                    $entry_arr['user_created_by'] = $company_id;
                    $parent_id = $this->crud->insert('credit_note', $entry_arr);

                    $add_lineitem = array();
                    $add_lineitem['item_id'] = CREDIT_NOTE_ITEM_ID;
                    $add_lineitem['item_qty'] = 1;
                    $add_lineitem['price'] = $acc_row['tran_amount'];
                    $add_lineitem['discount'] = 0;
                    $add_lineitem['cgst'] = 0;
                    $add_lineitem['cgst_amount'] = 0;
                    $add_lineitem['sgst'] = 0;
                    $add_lineitem['sgst_amount'] = 0;
                    $add_lineitem['igst'] = 0;
                    $add_lineitem['igst_amount'] = 0;
                    $add_lineitem['other_charges'] = 0;
                    $add_lineitem['discounted_price'] = $acc_row['tran_amount'];
                    $add_lineitem['pure_amount'] = $acc_row['tran_amount'];
                    $add_lineitem['amount'] = $acc_row['tran_amount'];
                    $add_lineitem['module'] = 3;
                    $add_lineitem['parent_id'] = $parent_id;
                    $add_lineitem['note'] = '';
                    $add_lineitem['created_at'] = $this->now_time;
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $company_id;
                    $add_lineitem['user_updated_by'] = $company_id;
                    $add_lineitem['created_by'] = $company_id;
                    $add_lineitem['user_created_by'] = $company_id;
                    $this->crud->insert('lineitems',$add_lineitem);

                    $this->crud->update_item_current_stock_qty(CREDIT_NOTE_ITEM_ID,$parent_id,'credit_note',1,'add');
                
                } else { 
                    //Sales Invoice
                    $acc_row['tran_amount'] = abs($acc_row['tran_amount']);

                    $sales_invoice_no = $this->crud->get_max_number_where('sales_invoice', 'sales_invoice_no', array('created_by' => $company_id));
                    if(empty($sales_invoice_no->sales_invoice_no)){
                        $new_sales_invoice_no = 1;
                    } else {
                        $new_sales_invoice_no = $sales_invoice_no->sales_invoice_no + 1;    
                    }
                    $entry_arr = array();
                    $entry_arr['sales_invoice_no'] = $new_sales_invoice_no;
                    $entry_arr['account_id'] = $acc_id;
                    $entry_arr['against_account_id'] = $against_account_id;
                    $entry_arr['sales_invoice_date'] = date('Y-m-d');
                    $entry_arr['sales_invoice_desc'] = $desc;
                    $entry_arr['qty_total'] = '1';
                    $entry_arr['pure_amount_total'] = $acc_row['tran_amount'];
                    $entry_arr['amount_total'] = $acc_row['tran_amount'];
                    $entry_arr['created_at'] = $this->now_time;
                    $entry_arr['created_by'] = $company_id;
                    $entry_arr['user_created_by'] = $company_id;
                    $parent_id = $this->crud->insert('sales_invoice', $entry_arr);

                    /*--- Line Item ---*/
                    $add_lineitem = array();
                    $add_lineitem['item_id'] = SALES_INVOICE_ITEM_ID;
                    $add_lineitem['item_qty'] = 1;
                    $add_lineitem['price'] = $acc_row['tran_amount'];
                    $add_lineitem['discount'] = 0;
                    $add_lineitem['cgst'] = 0;
                    $add_lineitem['cgst_amount'] = 0;
                    $add_lineitem['sgst'] = 0;
                    $add_lineitem['sgst_amount'] = 0;
                    $add_lineitem['igst'] = 0;
                    $add_lineitem['igst_amount'] = 0;
                    $add_lineitem['other_charges'] = 0;
                    $add_lineitem['discounted_price'] = $acc_row['tran_amount'];
                    $add_lineitem['pure_amount'] = $acc_row['tran_amount'];
                    $add_lineitem['amount'] = $acc_row['tran_amount'];
                    $add_lineitem['module'] = 2;
                    $add_lineitem['parent_id'] = $parent_id;
                    $add_lineitem['note'] = '';
                    $add_lineitem['created_at'] = $this->now_time;
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $company_id;
                    $add_lineitem['user_updated_by'] = $company_id;
                    $add_lineitem['created_by'] = $company_id;
                    $add_lineitem['user_created_by'] = $company_id;
                    $this->crud->insert('lineitems',$add_lineitem);

                    $this->crud->update_item_current_stock_qty(SALES_INVOICE_ITEM_ID,$parent_id,'sales',1,'add');
                    /*---/Line Item ---*/
                }
            }
        }
        echo json_encode(array("status" => "success","_POST" => $post_data,"account_res" => $account_res));
        exit();
    }
}
