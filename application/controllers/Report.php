<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sales
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Report extends CI_Controller {

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

    function stock() {
        if($this->applib->have_access_role(MODULE_STOCK_REGISTER_ID,"view")) {
            $data = array();
            set_page('report/stock', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function stock_datatable() {
        $from_date = '';
        $to_date = '';
        $account_id = '';
        $purchase_amt = '';
        $sales_amt = '';

        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = date('Y-m-d',strtotime($from_date));
            $to_date = trim($_POST['daterange_2']);
            $to_date = date('Y-m-d',strtotime($to_date));
        }
        if (isset($_POST['account_id'])) {
            $account_id = $_POST['account_id'];
        }

        $config['table'] = 'item i';
        $config['select'] = 'i.item_id, i.item_name';
        $config['wheres'][] = array('column_name' => 'i.created_by', 'column_value' => $this->logged_in_id);
        $config['order'] = array('i.item_name' => 'asc');
        $config['column_search'] = array('i.item_name');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        foreach ($list as $list_row) {
            $item_id = $list_row->item_id;

            $item_data = $this->crud->get_item_stock_data($list_row->item_id,$from_date,$to_date,$account_id);

            $row = array();
            $row[] = $inc;
            $row[] = $list_row->item_name;
            $row[] = $item_data['opening_stock'];
            $row[] = number_format($item_data['opening_amt'], 2, '.', '');
            $row[] = $item_data['inward'];
            $row[] = number_format($item_data['inward_amt'], 2, '.', '');
            $row[] = $item_data['outward'];
            $row[] = number_format($item_data['outward_amt'], 2, '.', '');
            $row[] = $item_data['closing_stock'];
            $row[] = number_format($item_data['closing_amt'], 2, '.', '');
            $data[] = $row;
            $inc++;
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

    function purchase() {
        if($this->applib->have_access_role(MODULE_PURCHASE_REGISTER_ID,"view")) {
            $data = array();
            set_page('report/purchase', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function purchase_datatable() {
        $from_date = '';
        $to_date = '';
        $account_id = '';
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }
        if (isset($_POST['account_id'])) {
            $account_id = $_POST['account_id'];
        }

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, pi.purchase_invoice_id, pi.purchase_invoice_no, pi.bill_no, pi.purchase_invoice_date, pi.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'purchase_invoice pi', 'join_by' => 'pi.purchase_invoice_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pi.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'pi.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'pi.invoice_type', 'column_value' => '2');
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $account_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'pi.purchase_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'pi.purchase_invoice_date <=', 'column_value' => $to_date);
        }
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '1');
        $config['order'] = array('pi.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $inc = 1;
        $purchase_invoice_id = '';
        foreach ($list as $list_row) {
            $row = array();
            if($purchase_invoice_id == $list_row->purchase_invoice_id){
                $row[] = '';
                $row[] = '';
                $row[] = '';
            } else {
                $row[] = $inc;
                $row[] = date('d-m-Y', strtotime($list_row->purchase_invoice_date));
                $row[] = $list_row->bill_no;
                $inc++;
            }
            $purchase_invoice_id = $list_row->purchase_invoice_id;
            $row[] = $list_row->item_name;
            $row[] = $list_row->hsn_code;
            $row[] = $list_row->account_name;
            $row[] = $list_row->account_gst_no;
            $row[] = number_format($list_row->discounted_price, 2, '.', '');

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $row[] = number_format($discount_amount, 2, '.', '');
            $row[] = number_format($list_row->cgst_amount, 2, '.', '');
            $row[] = number_format($list_row->sgst_amount, 2, '.', '');
            $row[] = number_format($list_row->igst_amount, 2, '.', '');
            $row[] = number_format($list_row->other_charges, 2, '.', '');
            $row[] = number_format($list_row->amount, 2, '.', '');
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

    function purchase_export() {
        $wheres = array();
        $output = '';

        $filename = "purchase_data.csv";
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $output_mainhead = array('Sr. No.', 'Date', 'Voucher .No.', 'Bill No.', 'Prdocuts Name', 'HSN Code', 'Party Name', 'GSTN No.', 'BasicValue', 'Charges', 'CGST Amount', 'SGST Amount', 'IGST Amount', 'Other Charges', 'Grand Total');
        fputcsv($handle, $output_mainhead);

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, pi.purchase_invoice_id, pi.purchase_invoice_no, pi.bill_no, pi.purchase_invoice_date, pi.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'purchase_invoice pi', 'join_by' => 'pi.purchase_invoice_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pi.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'pi.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '1');
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = $_POST['daterange_1'];
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = $_POST['daterange_2'];
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
            $config['wheres'][] = array('column_name' => 'pi.purchase_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'pi.purchase_invoice_date <=', 'column_value' => $to_date);
        }
        if (isset($_POST['account_id']) && !empty($_POST['account_id'])) {
            $config['wheres'][] = array('column_name' => 'pi.account_id', 'column_value' => $_POST['account_id']);
        }
        $config['order'] = array('pi.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $purchase_invoice_id = '';
        foreach ($list as $list_row) {
            $output_fields = array();
            if($purchase_invoice_id == $list_row->purchase_invoice_id){
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
            } else {
                $output_fields[] = $inc;
                $output_fields[] = date('d-m-Y', strtotime($list_row->purchase_invoice_date));
                $output_fields[] = $list_row->purchase_invoice_no;
                $output_fields[] = $list_row->bill_no;
                $inc++;
            }
            $purchase_invoice_id = $list_row->purchase_invoice_id;
            $output_fields[] = $list_row->item_name;
            $output_fields[] = $list_row->hsn_code;
            $output_fields[] = $list_row->account_name;
            $output_fields[] = $list_row->account_gst_no;
            $output_fields[] = $list_row->discounted_price;

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $output_fields[] = $discount_amount;
            $output_fields[] = $list_row->cgst_amount;
            $output_fields[] = $list_row->sgst_amount;
            $output_fields[] = $list_row->igst_amount;
            $output_fields[] = $list_row->other_charges;
            $output_fields[] = $list_row->amount;
            fputcsv($handle, $output_fields);
        }

        fclose($handle);
        exit;
    }

    function sales() {
        if($this->applib->have_access_role(MODULE_SALES_REGISTER_ID,"view")) {
            $data = array();
            set_page('report/sales', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function sales_datatable() {
        $from_date = '';
        $to_date = '';
        $account_id = '';
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }
        if (isset($_POST['account_id'])) {
            $account_id = $_POST['account_id'];
        }

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, pi.sales_invoice_id, pi.sales_invoice_no, pi.bill_no, pi.sales_invoice_date, pi.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'sales_invoice pi', 'join_by' => 'pi.sales_invoice_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pi.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'pi.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '2');
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $account_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'pi.sales_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'pi.sales_invoice_date <=', 'column_value' => $to_date);
        }
        $config['order'] = array('pi.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $sales_invoice_id = '';
        foreach ($list as $list_row) {
            $row = array();
            if($sales_invoice_id == $list_row->sales_invoice_id){
                $row[] = '';
                $row[] = '';
                $row[] = '';
            } else {
                $row[] = $inc;
                $row[] = date('d-m-Y', strtotime($list_row->sales_invoice_date));
                $row[] = $list_row->sales_invoice_no;
                $inc++;
            }
            $sales_invoice_id = $list_row->sales_invoice_id;
            $row[] = $list_row->item_name;
            $row[] = $list_row->hsn_code;
            $row[] = $list_row->account_name;
            $row[] = $list_row->account_gst_no;
            $row[] = number_format($list_row->discounted_price, 2, '.', '');

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $row[] = number_format($discount_amount, 2, '.', '');
            $row[] = number_format($list_row->cgst_amount, 2, '.', '');
            $row[] = number_format($list_row->sgst_amount, 2, '.', '');
            $row[] = number_format($list_row->igst_amount, 2, '.', '');
            $row[] = number_format($list_row->other_charges, 2, '.', '');
            $row[] = number_format($list_row->amount, 2, '.', '');
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

    function sales_export() {
        $wheres = array();
        $output = '';

        $filename = "sales_data.csv";
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $output_mainhead = array('Sr. No.', 'Date', 'Inv.No.', 'Bill No.', 'Prdocuts Name', 'HSN Code', 'Party Name', 'GSTN No.', 'BasicValue', 'Charges', 'CGST Amount', 'SGST Amount', 'IGST Amount', 'Other Charges', 'Grand Total');
        fputcsv($handle, $output_mainhead);

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, pi.sales_invoice_id, pi.sales_invoice_no, pi.bill_no, pi.sales_invoice_date, pi.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'sales_invoice pi', 'join_by' => 'pi.sales_invoice_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pi.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'pi.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '2');
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = $_POST['daterange_1'];
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = $_POST['daterange_2'];
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
            $config['wheres'][] = array('column_name' => 'pi.sales_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'pi.sales_invoice_date <=', 'column_value' => $to_date);
        }
        if (isset($_POST['account_id']) && !empty($_POST['account_id'])) {
            $config['wheres'][] = array('column_name' => 'pi.account_id', 'column_value' => $_POST['account_id']);
        }
        $config['order'] = array('pi.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $sales_invoice_id = '';
        foreach ($list as $list_row) {
            $output_fields = array();
            if($sales_invoice_id == $list_row->sales_invoice_id){
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
            } else {
                $output_fields[] = $inc;
                $output_fields[] = date('d-m-Y', strtotime($list_row->sales_invoice_date));
                $output_fields[] = $list_row->sales_invoice_no;
                $output_fields[] = $list_row->bill_no;
                $inc++;
            }
            $sales_invoice_id = $list_row->sales_invoice_id;
            $output_fields[] = $list_row->item_name;
            $output_fields[] = $list_row->hsn_code;
            $output_fields[] = $list_row->account_name;
            $output_fields[] = $list_row->account_gst_no;
            $output_fields[] = $list_row->discounted_price;

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $output_fields[] = $discount_amount;
            $output_fields[] = $list_row->cgst_amount;
            $output_fields[] = $list_row->sgst_amount;
            $output_fields[] = $list_row->igst_amount;
            $output_fields[] = $list_row->other_charges;
            $output_fields[] = $list_row->amount;
            fputcsv($handle, $output_fields);
        }

        fclose($handle);
        exit;
    }



    function sales_bill() {
        if($this->applib->have_access_role(MODULE_SALES_REGISTER_ID,"view")) {
            $data = array();
            set_page('report/sales_bill', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function sales_bill_datatable() {
        $from_date = '';
        $to_date = '';
        $account_id = '';
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }
        if (isset($_POST['account_id'])) {
            $account_id = $_POST['account_id'];
        }

        $config['table'] = 'sales_invoice pi';
        $config['select'] = 'pi.*,a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pi.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'pi.created_by', 'column_value' => $this->logged_in_id);
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $account_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'pi.sales_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'pi.sales_invoice_date <=', 'column_value' => $to_date);
        }
        $config['order'] = array('pi.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $sales_invoice_id = '';
        foreach ($list as $list_row) {
            $row = array();
            $row[] = $inc;
            $row[] = date('d-m-Y', strtotime($list_row->sales_invoice_date));
            $row[] = $list_row->sales_invoice_no;
            $row[] = $list_row->account_name;
            $row[] = $list_row->account_gst_no;
            $row[] = number_format($list_row->pure_amount_total, 2, '.', '');
            $row[] = number_format($list_row->discount_total, 2, '.', '');
            $row[] = number_format($list_row->cgst_amount_total, 2, '.', '');
            $row[] = number_format($list_row->sgst_amount_total, 2, '.', '');
            $row[] = number_format($list_row->igst_amount_total, 2, '.', '');;
            $row[] = number_format($list_row->other_charges_total, 2, '.', '');
            $row[] = number_format($list_row->amount_total, 2, '.', '');
            $inc++;
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

    function credit_note() {
        if($this->applib->have_access_role(MODULE_CREDIT_NOTE_REGISTER_ID,"view")) {
            $data = array();
            set_page('report/credit_note', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function credit_note_datatable() {
        $from_date = '';
        $to_date = '';
        $account_id = '';
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }
        if (isset($_POST['account_id'])) {
            $account_id = $_POST['account_id'];
        }

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, cn.credit_note_id, cn.credit_note_no, cn.bill_no, cn.credit_note_date, cn.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'credit_note cn', 'join_by' => 'cn.credit_note_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = cn.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'cn.created_by', 'column_value' => $this->logged_in_id);
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $account_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'cn.credit_note_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'cn.credit_note_date <=', 'column_value' => $to_date);
        }
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '3');
        $config['order'] = array('cn.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $credit_note_id = '';
        foreach ($list as $list_row) {
            $row = array();
            if($credit_note_id == $list_row->credit_note_id){
                $row[] = '';
                $row[] = '';
                $row[] = '';
            } else {
                $row[] = $inc;
                $row[] = date('d-m-Y', strtotime($list_row->credit_note_date));
                $row[] = $list_row->bill_no;
                $inc++;
            }
            $credit_note_id = $list_row->credit_note_id;
            $row[] = $list_row->item_name;
            $row[] = $list_row->hsn_code;
            $row[] = $list_row->account_name;
            $row[] = $list_row->account_gst_no;
            $row[] = $list_row->discounted_price;

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $row[] = $discount_amount;
            $row[] = $list_row->cgst_amount;
            $row[] = $list_row->sgst_amount;
            $row[] = $list_row->igst_amount;
            $row[] = $list_row->other_charges;
            $row[] = $list_row->amount;
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

    function credit_note_export() {
        $wheres = array();
        $output = '';

        $filename = "credit_note_data.csv";
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $output_mainhead = array('Sr. No.', 'Date', 'Voucher .No.', 'Bill No.', 'Prdocuts Name', 'HSN Code', 'Party Name', 'GSTN No.', 'BasicValue', 'Charges', 'CGST Amount', 'SGST Amount', 'IGST Amount', 'Other Charges', 'Grand Total');
        fputcsv($handle, $output_mainhead);

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, cn.credit_note_id, cn.credit_note_no, cn.bill_no, cn.credit_note_date, cn.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'credit_note cn', 'join_by' => 'cn.credit_note_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = cn.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'cn.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '3');
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = $_POST['daterange_1'];
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = $_POST['daterange_2'];
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
            $config['wheres'][] = array('column_name' => 'cn.credit_note_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'cn.credit_note_date <=', 'column_value' => $to_date);
        }
        if (isset($_POST['account_id']) && !empty($_POST['account_id'])) {
            $config['wheres'][] = array('column_name' => 'cn.account_id', 'column_value' => $_POST['account_id']);
        }
        $config['order'] = array('cn.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $credit_note_id = '';
        foreach ($list as $list_row) {
            $output_fields = array();
            if($credit_note_id == $list_row->credit_note_id){
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
            } else {
                $output_fields[] = $inc;
                $output_fields[] = date('d-m-Y', strtotime($list_row->credit_note_date));
                $output_fields[] = $list_row->credit_note_no;
                $output_fields[] = $list_row->bill_no;
                $inc++;
            }
            $credit_note_id = $list_row->credit_note_id;
            $output_fields[] = $list_row->item_name;
            $output_fields[] = $list_row->hsn_code;
            $output_fields[] = $list_row->account_name;
            $output_fields[] = $list_row->account_gst_no;
            $output_fields[] = $list_row->discounted_price;

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $output_fields[] = $discount_amount;
            $output_fields[] = $list_row->cgst_amount;
            $output_fields[] = $list_row->sgst_amount;
            $output_fields[] = $list_row->igst_amount;
            $output_fields[] = $list_row->other_charges;
            $output_fields[] = $list_row->amount;
            fputcsv($handle, $output_fields);
        }

        fclose($handle);
        exit;
    }

    function debit_note() {
        if($this->applib->have_access_role(MODULE_DEBIT_NOTE_REGISTER_ID,"view")) {
            $data = array();
            set_page('report/debit_note', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function debit_note_datatable() {
        $from_date = '';
        $to_date = '';
        $account_id = '';
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }
        if (isset($_POST['account_id'])) {
            $account_id = $_POST['account_id'];
        }

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, dn.debit_note_id, dn.debit_note_no, dn.bill_no, dn.debit_note_date, dn.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'debit_note dn', 'join_by' => 'dn.debit_note_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = dn.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'dn.created_by', 'column_value' => $this->logged_in_id);
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $account_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'dn.debit_note_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'dn.debit_note_date <=', 'column_value' => $to_date);
        }
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '4');
        $config['order'] = array('dn.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $debit_note_id = '';
        foreach ($list as $list_row) {
            $row = array();
            if($debit_note_id == $list_row->debit_note_id){
                $row[] = '';
                $row[] = '';
                $row[] = '';
            } else {
                $row[] = $inc;
                $row[] = date('d-m-Y', strtotime($list_row->debit_note_date));
                $row[] = $list_row->bill_no;
                $inc++;
            }
            $debit_note_id = $list_row->debit_note_id;
            $row[] = $list_row->item_name;
            $row[] = $list_row->hsn_code;
            $row[] = $list_row->account_name;
            $row[] = $list_row->account_gst_no;
            $row[] = $list_row->discounted_price;

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $row[] = $discount_amount;
            $row[] = $list_row->cgst_amount;
            $row[] = $list_row->sgst_amount;
            $row[] = $list_row->igst_amount;
            $row[] = $list_row->other_charges;
            $row[] = $list_row->amount;
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

    function debit_note_export() {
        $wheres = array();
        $output = '';

        $filename = "debit_note_data.csv";
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $output_mainhead = array('Sr. No.', 'Date', 'Voucher .No.', 'Bill No.', 'Prdocuts Name', 'HSN Code', 'Party Name', 'GSTN No.', 'BasicValue', 'Charges', 'CGST Amount', 'SGST Amount', 'IGST Amount', 'Other Charges', 'Grand Total');
        fputcsv($handle, $output_mainhead);

        $config['table'] = 'lineitems li';
        $config['select'] = 'li.*, dn.debit_note_id, dn.debit_note_no, dn.bill_no, dn.debit_note_date, dn.amount_total, item.item_name, item.hsn_code, a.account_name, a.account_gst_no';
        $config['joins'][] = array('join_table' => 'debit_note dn', 'join_by' => 'dn.debit_note_id = li.parent_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item item', 'join_by' => 'item.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = dn.account_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'dn.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'li.module', 'column_value' => '4');
        if (isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])) {
            $from_date = $_POST['daterange_1'];
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = $_POST['daterange_2'];
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
            $config['wheres'][] = array('column_name' => 'dn.debit_note_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'dn.debit_note_date <=', 'column_value' => $to_date);
        }
        if (isset($_POST['account_id']) && !empty($_POST['account_id'])) {
            $config['wheres'][] = array('column_name' => 'dn.account_id', 'column_value' => $_POST['account_id']);
        }
        $config['order'] = array('dn.created_at' => 'desc');

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $inc = 1;
        $debit_note_id = '';
        foreach ($list as $list_row) {
            $output_fields = array();
            if($debit_note_id == $list_row->debit_note_id){
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
                $output_fields[] = '';
            } else {
                $output_fields[] = $inc;
                $output_fields[] = date('d-m-Y', strtotime($list_row->debit_note_date));
                $output_fields[] = $list_row->debit_note_no;
                $output_fields[] = $list_row->bill_no;
                $inc++;
            }
            $debit_note_id = $list_row->debit_note_id;
            $output_fields[] = $list_row->item_name;
            $output_fields[] = $list_row->hsn_code;
            $output_fields[] = $list_row->account_name;
            $output_fields[] = $list_row->account_gst_no;
            $output_fields[] = $list_row->discounted_price;

            $discount_amount = 0;
            if ($list_row->discount_type == 1) {
                $discount_amount = $list_row->pure_amount * $list_row->discount / 100;
            }
            if ($list_row->discount_type == 2) {
                $discount_amount = $list_row->discount;
            }
            $output_fields[] = $discount_amount;
            $output_fields[] = $list_row->cgst_amount;
            $output_fields[] = $list_row->sgst_amount;
            $output_fields[] = $list_row->igst_amount;
            $output_fields[] = $list_row->other_charges;
            $output_fields[] = $list_row->amount;
            fputcsv($handle, $output_fields);
        }

        fclose($handle);
        exit;
    }
    
    function login_report() {
        if($this->applib->have_access_role(MODULE_USER_LOG_ID,"view")) {

            $isAdmin = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['userType'];
            if ($isAdmin == 'Admin') {
                set_page('report/login_report');
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

    function login_report_datatable(){
		$config['table'] = 'user_login_log log';
		$config['select'] = 'log.*,u.user_name';
		$config['column_order'] = array('log.login_logout', 'u.user_name', 'log.ip_add', 'log.datetime');
		$config['column_search'] = array('log.login_logout', 'u.user_name', 'log.ip_add', 'DATE_FORMAT(log.datetime,"%d-%m-%Y")');
		$config['joins'][] = array('join_table' => 'user u', 'join_by' => 'u.user_id = log.user_id', 'join_type' => 'left');
		$config['order'] = array('log.id' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		//echo $this->db->last_query();exit;
		$data = array();
		foreach ($list as $user) {
			$row = array();
            $type = '';
            if($user->login_logout == 1){
                $type = 'Login';
            } else if ($user->login_logout == 2){
                $type = 'Logout';
            }
			$row[] = $type;
			$row[] = $user->user_name;
			$row[] = $user->ip_add;
			$row[] = (!empty(strtotime($user->datetime))) ? date('d-m-Y h:i:s A', strtotime($user->datetime)) : '';
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
    
    function ledger($account_id=''){
        if($this->applib->have_access_role(MODULE_LEDGER_ID,"view")) {
            $data = array();
            if(isset($account_id) && !empty($account_id)){
                $data['account_id'] = $account_id;
            }
            set_page('report/ledger_report', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
        
    }
    
    function ledger_datatable() {
        $from_date = date('Y-m-d', strtotime($_POST['daterange_1']));
        $to_date = date('Y-m-d', strtotime($_POST['daterange_2']));
        $account_id = $_POST['account_id'];
//        $is_kasar_account = $this->crud->get_val_by_id('account',$account_id,'account_id','is_kasar_account');
        $tmp_from_date = date('Y-m-d',strtotime('-1 day',strtotime($from_date)));
        $opening_bal = $this->crud->get_account_balance($account_id,$tmp_from_date);
        
        $opening_balance = array();
        $opening_balance[] = (object) array("tr_date" => $tmp_from_date,'opening_amount' => $opening_bal,'tran_type' => 'opening_balance');
        
        $ledger_data = array();
        $ledger_data = array_merge($ledger_data,$opening_balance);
        
        
        $account_row = $this->crud->get_data_row_by_where('account',array('account_id' => $account_id));

        /*-------- Payment ------*/   
        $from_payment_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'from_payment' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.to_account_id  WHERE tr.transaction_type = 1 AND tr.from_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");
        
        if(!empty($from_payment_res)) {
            $ledger_data = array_merge($ledger_data,$from_payment_res);
        }


        $to_payment_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'to_payment' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id  WHERE tr.transaction_type = 1 AND tr.to_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");
        
        if(!empty($to_payment_res)) {
            $ledger_data = array_merge($ledger_data,$to_payment_res);
        }

       
        /*------- Receipt -------*/
        $from_receipt_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'from_receipt' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.to_account_id  WHERE tr.transaction_type = 2 AND tr.from_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");

        if(!empty($from_receipt_res)) {
            $ledger_data = array_merge($ledger_data,$from_receipt_res);
        }
        
        $to_receipt_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'to_receipt' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id  WHERE tr.transaction_type = 2 AND tr.to_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");

        if(!empty($to_receipt_res)) {
            $ledger_data = array_merge($ledger_data,$to_receipt_res);
        }


        /*-------- Contra ------*/
        $from_contra_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'from_contra' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.to_account_id  WHERE tr.transaction_type = 3 AND tr.from_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");

        if(!empty($from_contra_res)) {
            $ledger_data = array_merge($ledger_data,$from_contra_res);
        }

        $to_contra_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'to_contra' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id  WHERE tr.transaction_type = 3 AND tr.to_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");

        if(!empty($to_contra_res)) {
            $ledger_data = array_merge($ledger_data,$to_contra_res);
        }
        
        /*------- Journal ------*/
        $from_journal_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'from_journal' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.to_account_id WHERE tr.transaction_type = 4 AND tr.from_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."'");

        if(!empty($from_journal_res)) {
            if($account_row->is_kasar_account == 1 && !empty($from_journal_res)) {
                foreach ($from_journal_res as $key => $from_journal_row) {
                    $acc_row = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.to_account_id WHERE tr.transaction_type = 4 AND tr.journal_id=".$from_journal_row->journal_id." AND transaction_id!=".$from_journal_row->transaction_id." LIMIT 1");
                    if(!empty($acc_row)) {
                        $acc_row = $acc_row[0];
                        $from_journal_res[$key]->opp_acc_name = $acc_row->opp_acc_name;
                    }
                }
            }
            $ledger_data = array_merge($ledger_data,$from_journal_res);
        }

        $to_journal_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.transaction_date AS tr_date,'to_journal' as tran_type FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id WHERE tr.transaction_type = 4 AND tr.to_account_id=".$account_id." AND tr.transaction_date >= '".$from_date."' AND tr.transaction_date <= '".$to_date."' AND a.is_kasar_account = 0 ");

        if(!empty($to_journal_res)) {
            if($account_row->is_kasar_account == 1 && !empty($to_journal_res)) {
                foreach ($to_journal_res as $key => $from_journal_row) {
                    $acc_row = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id WHERE tr.transaction_type = 4 AND tr.journal_id=".$from_journal_row->journal_id." AND transaction_id!=".$from_journal_row->transaction_id." LIMIT 1");
                    if(!empty($acc_row)) {
                        $acc_row = $acc_row[0];
                        $to_journal_res[$key]->opp_acc_name = $acc_row->opp_acc_name;
                    }
                }
            }
            $ledger_data = array_merge($ledger_data,$to_journal_res);
        }


        /*-------- Purchase ------*/
        $purchase_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.purchase_invoice_date AS tr_date,'purchase' as tran_type FROM purchase_invoice as tr LEFT JOIN account as a ON a.account_id = tr.against_account_id WHERE tr.account_id=".$account_id." AND tr.purchase_invoice_date >= '".$from_date."' AND tr.purchase_invoice_date <= '".$to_date."'");

        if(!empty($purchase_res)) {
            $ledger_data = array_merge($ledger_data,$purchase_res);
        }
        $against_purchase_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.purchase_invoice_date AS tr_date,'against_purchase' as tran_type FROM purchase_invoice as tr LEFT JOIN account as a ON a.account_id = tr.account_id WHERE tr.against_account_id=".$account_id." AND tr.purchase_invoice_date >= '".$from_date."' AND tr.purchase_invoice_date <= '".$to_date."'");

        if(!empty($against_purchase_res)) {
            $ledger_data = array_merge($ledger_data,$against_purchase_res);
        }


        /*-------- Sale ------*/
        $sales_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.sales_invoice_date AS tr_date,'sales' as tran_type FROM sales_invoice as tr LEFT JOIN account as a ON a.account_id = tr.against_account_id  WHERE tr.account_id=".$account_id." AND tr.sales_invoice_date >= '".$from_date."' AND tr.sales_invoice_date <= '".$to_date."'");

        if(!empty($sales_res)) {
            $ledger_data = array_merge($ledger_data,$sales_res);
        }

        $against_sales_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.sales_invoice_date AS tr_date,'against_sales' as tran_type FROM sales_invoice as tr LEFT JOIN account as a ON a.account_id = tr.account_id  WHERE tr.against_account_id=".$account_id." AND tr.sales_invoice_date >= '".$from_date."' AND tr.sales_invoice_date <= '".$to_date."'");

        if(!empty($against_sales_res)) {
            $ledger_data = array_merge($ledger_data,$against_sales_res);
        }


        /*-------- Credit Note ------*/
        $credit_note_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.credit_note_date AS tr_date,'credit_note' as tran_type FROM credit_note as tr LEFT JOIN account as a ON a.account_id = tr.against_account_id WHERE tr.account_id=".$account_id." AND tr.credit_note_date >= '".$from_date."' AND tr.credit_note_date <= '".$to_date."'");

        if(!empty($credit_note_res)) {
            $ledger_data = array_merge($ledger_data,$credit_note_res);
        }

        $against_credit_note_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.credit_note_date AS tr_date,'against_credit_note' as tran_type FROM credit_note as tr LEFT JOIN account as a ON a.account_id = tr.account_id  WHERE tr.against_account_id=".$account_id." AND tr.credit_note_date >= '".$from_date."' AND tr.credit_note_date <= '".$to_date."'");

        if(!empty($against_credit_note_res)) {
            $ledger_data = array_merge($ledger_data,$against_credit_note_res);
        }


        /*-------- Debit Note ------*/
        $debit_note_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.debit_note_date AS tr_date,'debit_note' as tran_type FROM debit_note as tr LEFT JOIN account as a ON a.account_id = tr.against_account_id WHERE tr.account_id=".$account_id." AND tr.debit_note_date >= '".$from_date."' AND tr.debit_note_date <= '".$to_date."'");

        if(!empty($debit_note_res)) {
            $ledger_data = array_merge($ledger_data,$debit_note_res);
        }


        $against_debit_note_res = $this->crud->getFromSQL("SELECT a.account_name as opp_acc_name,tr.*,tr.debit_note_date AS tr_date,'against_debit_note' as tran_type FROM debit_note as tr LEFT JOIN account as a ON a.account_id = tr.account_id WHERE tr.against_account_id=".$account_id." AND tr.debit_note_date >= '".$from_date."' AND tr.debit_note_date <= '".$to_date."'");

        if(!empty($against_debit_note_res)) {
            $ledger_data = array_merge($ledger_data,$against_debit_note_res);
        }

        
        function date_compare($a, $b){
			$t1 = strtotime($a->tr_date);
            $t2 = strtotime($b->tr_date);
			return $t1 - $t2;
		}

		usort($ledger_data, 'date_compare');
        
        $data = array();
        $tr_date = '';
        $particular = '';
        $credit_amt = 0;
        $total_credit_amt = 0;
        $debit_amt = 0;
        $total_debit_amt = 0;
        $balance_amt = 0;
        $opening_amount = 0;

        $is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
        
        foreach ($ledger_data as $list_row) {
            
            $tr_date = (!empty(strtotime($list_row->tr_date))) ? date('d-m-Y', strtotime($list_row->tr_date)) : '';
            $particular = '';
            $bill_no = '';
            $delete_link = '';
            $row = array();

            if($list_row->tran_type == "opening_balance") {
                $particular = "Opening Balance";
                if($list_row->opening_amount >= 0){
                    $debit_amt = $list_row->opening_amount;
                    $opening_amount = $list_row->opening_amount;
                } else {
                    $opening_amount = $list_row->opening_amount;
                    $credit_amt = $list_row->opening_amount;
                     
                }

            } elseif ($list_row->tran_type == "from_payment") {
                
                $particular = 'From Payment';

                $isEdit = $this->applib->have_access_role(MODULE_PAYMENT_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_PAYMENT_ID, "delete");

                if($isEdit) {
                    $particular = '<a href="'.base_url("transaction/payment/" . $list_row->transaction_id) . '">Payment</a>';   
                } else {
                    $particular = 'Payment';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }               
            
                $debit_amt = 0;
                $credit_amt = $list_row->amount; 

            } elseif ($list_row->tran_type == "to_payment") {
                
                $particular = 'To Payment';
                
                $isEdit = $this->applib->have_access_role(MODULE_PAYMENT_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_PAYMENT_ID, "delete");

                if($isEdit) {
                    $particular = '<a href="'.base_url("transaction/payment/" . $list_row->transaction_id) . '">Payment</a>';   
                } else {
                    $particular = 'Payment';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = $list_row->amount; 
                $credit_amt = 0;
            
            } elseif ($list_row->tran_type == "from_receipt") {
                
                $particular = 'From Receipt';

                $isEdit = $this->applib->have_access_role(MODULE_RECEIPT_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_RECEIPT_ID, "delete");

                if($isEdit) {
                    $particular = '<a href="'.base_url("transaction/receipt/" . $list_row->transaction_id) . '">Receipt</a>';   
                } else {
                    $particular = 'Receipt';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_receipt_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = 0;
                $credit_amt = $list_row->amount; 
            
            } elseif ($list_row->tran_type == "to_receipt") {
                
                $particular = 'To Receipt';

                $isEdit = $this->applib->have_access_role(MODULE_RECEIPT_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_RECEIPT_ID, "delete");

                if($isEdit) {
                    $particular = '<a href="'.base_url("transaction/receipt/" . $list_row->transaction_id) . '">Receipt</a>';   
                } else {
                    $particular = 'Receipt';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_receipt_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = $list_row->amount; 
                $credit_amt = 0;
            
            } elseif ($list_row->tran_type == "from_contra") {
                $bill_no = $list_row->contra_no;
                $particular = 'From Contra';

                $isEdit = $this->applib->have_access_role(MODULE_CONTRA_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_CONTRA_ID, "delete");

                if($isEdit) {
                    $particular = '<a href="'.base_url("contra/contra/" . $list_row->transaction_id) . '">Contra</a>';
                } else {
                    $particular = 'Contra';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }
                
                $debit_amt = 0;
                $credit_amt = $list_row->amount; 
            
            } elseif ($list_row->tran_type == "to_contra") {
                $bill_no = $list_row->contra_no;
                $particular = 'To Contra';
                
                $isEdit = $this->applib->have_access_role(MODULE_CONTRA_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_CONTRA_ID, "delete");

                if($isEdit) {
                    $particular = '<a href="'.base_url("contra/contra/" . $list_row->transaction_id) . '">Contra</a>';
                } else {
                    $particular = 'Contra';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = $list_row->amount; 
                $credit_amt = 0;
            
            } elseif ($list_row->tran_type == "from_journal") {
                $bill_no = $list_row->contra_no;
                $particular = 'From Journal';

                $isEdit = $this->applib->have_access_role(MODULE_JOURNAL_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_JOURNAL_ID, "delete");

                if($isEdit) {
                    if(!empty($list_row->journal_id)) {
                        $particular = '<a href="'.base_url("journal/journal_type2/" . $list_row->journal_id) . '">Journal</a>';
                    } else {
                        $particular = '<a href="'.base_url("journal/journal/" . $list_row->transaction_id) . '">Journal</a>';    
                    }    

                } else {
                    $particular = 'Journal';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }
                

                $debit_amt = 0;
                $credit_amt = $list_row->amount; 
            
            } elseif ($list_row->tran_type == "to_journal") {
                $bill_no = $list_row->contra_no;
                $particular = 'To Journal';
                
                $isEdit = $this->applib->have_access_role(MODULE_JOURNAL_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_JOURNAL_ID, "delete");

                if($isEdit) {
                    if(!empty($list_row->journal_id)) {
                        $particular = '<a href="'.base_url("journal/journal_type2/" . $list_row->journal_id) . '">Journal</a>';
                    } else {
                        $particular = '<a href="'.base_url("journal/journal/" . $list_row->transaction_id) . '">Journal</a>';    
                    }    

                } else {
                    $particular = 'Journal';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('transaction/delete_transaction/' . $list_row->transaction_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = $list_row->amount; 
                $credit_amt = 0;

            } elseif ($list_row->tran_type == "purchase") {
                $bill_no = $list_row->bill_no;
                $particular = 'Purchase Invoice';

                $isEdit = $this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        if($list_row->invoice_type == 3) {
                            $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url('transaction/order_type2') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                        } else {
                            $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url($list_row->invoice_type == '2' ? 'transaction/sales_purchase_transaction/purchase' : 'purchase/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                        }
                    } else {
                        $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url($list_row->invoice_type ? 'purchase/invoice' : 'purchase/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                    }
                } else {
                    $particular = 'Purchase Invoice';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('purchase/invoice_delete/' . $list_row->purchase_invoice_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }
                
                $debit_amt = 0;
                $credit_amt = $list_row->amount_total; 
            
            } elseif ($list_row->tran_type == "against_purchase") {
                $bill_no = $list_row->bill_no;
                $particular = 'Against Purchase Invoice';
                
                $isEdit = $this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        if($list_row->invoice_type == 3) {
                            $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url('transaction/order_type2') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                        } else {
                            $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url($list_row->invoice_type == '2' ? 'transaction/sales_purchase_transaction/purchase' : 'purchase/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                        }
                    } else {
                        $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url($list_row->invoice_type ? 'purchase/invoice' : 'purchase/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                    }
                } else {
                    $particular = 'Purchase Invoice';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('purchase/invoice_delete/' . $list_row->purchase_invoice_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = $list_row->amount_total; 
                $credit_amt = 0;
            
            } elseif ($list_row->tran_type == "credit_note") {
                $bill_no = $list_row->credit_note_no;
                $particular = 'Credit Note';

                $isEdit = $this->applib->have_access_role(MODULE_CREDIT_NOTE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_CREDIT_NOTE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        $particular = '<form id="edit_' . $list_row->credit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/credit_note" style="width: 25px; display: initial;" >
                                <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $list_row->credit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->credit_note_id . '\').submit();" title="Edit Credit Note">Credit Note</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->credit_note_id . '" method="post" action="' . base_url() . 'credit_note/add" style="width: 25px; display: initial;" >
                                <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $list_row->credit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->credit_note_id . '\').submit();" title="Edit Credit Note">Credit Note</a>
                            </form> ';
                    }
                } else {
                    $particular = 'Credit Note';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('credit_note/credit_note_delete/' . $list_row->credit_note_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = 0;
                $credit_amt = $list_row->amount_total; 
            
            } elseif ($list_row->tran_type == "against_credit_note") {
                $bill_no = $list_row->credit_note_no;
                $particular = 'Against Credit Note';
                $isEdit = $this->applib->have_access_role(MODULE_CREDIT_NOTE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_CREDIT_NOTE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        $particular = '<form id="edit_' . $list_row->credit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/credit_note" style="width: 25px; display: initial;" >
                                <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $list_row->credit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->credit_note_id . '\').submit();" title="Edit Credit Note">Credit Note</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->credit_note_id . '" method="post" action="' . base_url() . 'credit_note/add" style="width: 25px; display: initial;" >
                                <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $list_row->credit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->credit_note_id . '\').submit();" title="Edit Credit Note">Credit Note</a>
                            </form> ';
                    }
                } else {
                    $particular = 'Credit Note';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('credit_note/credit_note_delete/' . $list_row->credit_note_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $debit_amt = $list_row->amount_total; 
                $credit_amt = 0;
            
            } elseif ($list_row->tran_type == "sales") {
                $bill_no = $this->applib->format_invoice_number($list_row->sales_invoice_id, $list_row->sales_invoice_date);
                $particular = 'Sales Invoice';
                $isEdit = $this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        $particular = '<form id="edit_' . $list_row->sales_invoice_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/sales" style="width: 25px; display: initial;" >
                                <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $list_row->sales_invoice_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->sales_invoice_id . '" method="post" action="' . base_url() . 'sales/invoice" style="width: 25px; display: initial;" >
                                <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $list_row->sales_invoice_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                            </form> ';
                    }
                } else {
                    $particular = 'Sales Invoice';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('sales/invoice_delete/' . $list_row->sales_invoice_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $credit_amt = 0;
                $debit_amt = $list_row->amount_total; 
            
            } elseif ($list_row->tran_type == "against_sales") {
                $bill_no = $this->applib->format_invoice_number($list_row->sales_invoice_id, $list_row->sales_invoice_date);
                $particular = 'Against Sales Invoice';
                $isEdit = $this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        $particular = '<form id="edit_' . $list_row->sales_invoice_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/sales" style="width: 25px; display: initial;" >
                                <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $list_row->sales_invoice_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->sales_invoice_id . '" method="post" action="' . base_url() . 'sales/invoice" style="width: 25px; display: initial;" >
                                <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $list_row->sales_invoice_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                            </form> ';
                    }
                } else {
                    $particular = 'Sales Invoice';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('sales/invoice_delete/' . $list_row->sales_invoice_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }

                $credit_amt = $list_row->amount_total; 
                $debit_amt = 0;
            
            } elseif ($list_row->tran_type == "debit_note") {
                $bill_no = $list_row->debit_note_no;
                $particular = 'Debit Note';
                $isEdit = $this->applib->have_access_role(MODULE_DEBIT_NOTE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_DEBIT_NOTE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        $particular = '<form id="edit_' . $list_row->debit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/debit_note" style="width: 25px; display: initial;" >
                                <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $list_row->debit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->debit_note_id . '\').submit();" title="Edit Debit Note">Debit Note</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->debit_note_id . '" method="post" action="' . base_url() . 'debit_note/add" style="width: 25px; display: initial;" >
                                <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $list_row->debit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->debit_note_id . '\').submit();" title="Edit Debit Note">Debit Note</a>
                            </form> ';
                    }
                } else {
                    $particular = 'Debit Note';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('debit_note/debit_note_delete/' . $list_row->debit_note_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }
                
                $credit_amt = 0;
                $debit_amt = $list_row->amount_total; 
            
            } elseif ($list_row->tran_type == "against_debit_note") {
                $bill_no = $list_row->debit_note_no;
                $particular = 'Against Debit Note';
                $isEdit = $this->applib->have_access_role(MODULE_DEBIT_NOTE_ID, "edit");
                $isDelete = $this->applib->have_access_role(MODULE_DEBIT_NOTE_ID, "delete");

                if($isEdit) {
                    if($is_single_line_item == 1 || 1) {
                        $particular = '<form id="edit_' . $list_row->debit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/debit_note" style="width: 25px; display: initial;" >
                                <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $list_row->debit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->debit_note_id . '\').submit();" title="Edit Debit Note">Debit Note</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->debit_note_id . '" method="post" action="' . base_url() . 'debit_note/add" style="width: 25px; display: initial;" >
                                <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $list_row->debit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->debit_note_id . '\').submit();" title="Edit Debit Note">Debit Note</a>
                            </form> ';
                    }
                } else {
                    $particular = 'Debit Note';
                }
                
                if($isDelete) {
                    $delete_link = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('debit_note/debit_note_delete/' . $list_row->debit_note_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                } else {
                    $delete_link = '';
                }
                
                $credit_amt = $list_row->amount_total; 
                $debit_amt = 0;
            }
            $credit_amt = round($credit_amt);
            $debit_amt = round($debit_amt);
            
            $total_credit_amt += abs($credit_amt);
            $total_debit_amt += abs($debit_amt);
            $balance_amt = $total_debit_amt - $total_credit_amt;

            if ( isset( $list_row->purchase_invoice_id )) {
                $unit_data = $this->crud->getFromSQL("SELECT u.pack_unit_name as unit_name FROM lineitems as li LEFT JOIN pack_unit as u ON u.pack_unit_id = li.unit_id  WHERE li.parent_id=".$list_row->purchase_invoice_id."");
            }

            $row[] = $delete_link;
            $row[] = $tr_date;
            $row[] = $bill_no;
            $row[] = isset( $list_row->qty_total ) ? $list_row->qty_total : 0;
            $row[] = isset( $list_row->vehicle_no ) ? $list_row->vehicle_no : '';
            $row[] = isset( $unit_data[0]->unit_name ) ? $unit_data[0]->unit_name : '';
            $row[] = $particular;
            $row[] = isset($list_row->opp_acc_name) ? $list_row->opp_acc_name : '';
            $row[] = abs($credit_amt);
            $row[] = abs($debit_amt);
            $row[] = $balance_amt;
            $data[] = $row;
        }
        $total[] = '';
        $total[] = '';
        $total[] = '';
        $total[] = '';
        $total[] = '';
        $total[] = '';
        $total[] = '';
        $total[] = 'Total';
        $total[] = $total_credit_amt;
        $total[] = $total_debit_amt;
        $total[] = '';
        $data[] = $total;
        
        $total = $total_debit_amt - $total_credit_amt;
        $total2[] = '';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '<b>Closing Balance</b>';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '<b>'.$total.'</b>';
        $data[] = $total2;
        $output = array(
            "draw" => $_POST['draw'],
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    function ledger_datatable_old() {
        $from_date = date('Y-m-d', strtotime($_POST['daterange_1']));
        $to_date = date('Y-m-d', strtotime($_POST['daterange_2']));
        $account_id = $_POST['account_id'];
        $account_credit = $this->crud->getFromSQL("SELECT opening_balance FROM account WHERE credit_debit = 1 and account_id=".$account_id);
        if(!empty($account_credit)){
            $account_credit = $account_credit[0]->opening_balance;
        }
        $account_debit = $this->crud->getFromSQL("SELECT opening_balance FROM account WHERE credit_debit = 2 and account_id=".$account_id);
        if(!empty($account_debit)){
            $account_debit = $account_debit[0]->opening_balance;
        }
        
        $tr_credit = $this->crud->getFromSQL("SELECT SUM(amount) as total_credit_amount FROM transaction_entry WHERE transaction_type =1 AND from_account_id=".$account_id." AND transaction_date < '".$from_date."'");
        $tr_credit = $tr_credit[0]->total_credit_amount;
        
        $tr_debit = $this->crud->getFromSQL("SELECT SUM(amount) as total_debit_amount FROM transaction_entry WHERE transaction_type =2 AND to_account_id=".$account_id." AND transaction_date < '".$from_date."'");
        $tr_debit = $tr_debit[0]->total_debit_amount;
        
        $purchase_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_purchase_amount FROM purchase_invoice WHERE account_id=".$account_id." AND purchase_invoice_date < '".$from_date."'");
        $purchase_opening = $purchase_opening[0]->total_purchase_amount;
        
        $sales_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_sales_amount FROM sales_invoice WHERE account_id=".$account_id." AND sales_invoice_date < '".$from_date."'");
        $sales_opening = $sales_opening[0]->total_sales_amount;
        
        $credit_note_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_credit_note_amount FROM credit_note WHERE account_id=".$account_id." AND credit_note_date < '".$from_date."'");
        $credit_note_opening = $credit_note_opening[0]->total_credit_note_amount;
        
        $debit_note_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_debit_note_amount FROM debit_note WHERE account_id=".$account_id." AND debit_note_date < '".$from_date."'");
        $debit_note_opening = $debit_note_opening[0]->total_debit_note_amount;
        
        $credit_total = $tr_credit + $purchase_opening + $credit_note_opening;
        $debit_total = $tr_debit + $sales_opening + $debit_note_opening;

        if(!empty($account_credit)){
            $credit_total = $tr_credit + $purchase_opening + $credit_note_opening + $account_credit;
        }
        if(!empty($account_debit)){
            $debit_total = $tr_debit + $sales_opening + $debit_note_opening + $account_debit;
        }

        $opening_bal = $credit_total - $debit_total;
        $opening_balance = array();
        $opening_balance[] = (object) array("date" => $from_date,'opening_amount' => $opening_bal);
        
        $tr_data = $this->crud->getFromSQL("SELECT *,transaction_date AS date FROM transaction_entry WHERE from_account_id=".$account_id." AND transaction_date >= '".$from_date."' AND transaction_date <= '".$to_date."'");

        $purchase_data = $this->crud->getFromSQL("SELECT *,purchase_invoice_date AS date FROM purchase_invoice WHERE account_id=".$account_id." AND purchase_invoice_date >= '".$from_date."' AND purchase_invoice_date <= '".$to_date."'");

        $sales_data = $this->crud->getFromSQL("SELECT *,sales_invoice_date AS date  FROM sales_invoice WHERE account_id=".$account_id." AND sales_invoice_date >= '".$from_date."' AND sales_invoice_date <= '".$to_date."'");

        $credit_note_data = $this->crud->getFromSQL("SELECT *,credit_note_date AS date FROM credit_note WHERE account_id=".$account_id." AND credit_note_date >= '".$from_date."' AND credit_note_date <= '".$to_date."'");

        $debit_note_data = $this->crud->getFromSQL("SELECT *,debit_note_date AS date FROM debit_note WHERE account_id=".$account_id." AND debit_note_date >= '".$from_date."' AND debit_note_date <= '".$to_date."'");

        $ledger_data = array_merge($opening_balance,$tr_data,$purchase_data,$sales_data,$credit_note_data,$debit_note_data);
        
        function date_compare($a, $b){
            $t1 = strtotime($a->date);
            $t2 = strtotime($b->date);
            return $t1 - $t2;
        }    
        usort($ledger_data, 'date_compare');
        
        $data = array();
        $date = '';
        $particular = '';
        $credit_amt = 0;
        $total_credit_amt = 0;
        $debit_amt = 0;
        $total_debit_amt = 0;
        $balance = 0;
        $opening_amount = 0;

        $is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
        foreach ($ledger_data as $list_row) {
            $row = array();
            $date = (!empty(strtotime($list_row->date))) ? date('d-m-Y', strtotime($list_row->date)) : '';
            $particular = '';
            if(isset($list_row->opening_amount)){
                $particular = "Opening Balance";
                if($list_row->opening_amount >= 0){
                    $opening_amount = $list_row->opening_amount;
                    $credit_amt = $list_row->opening_amount; 
                    $total_credit_amt += $list_row->opening_amount; 
                } else {
                    $debit_amt = $list_row->opening_amount; 
                    $total_debit_amt += $list_row->opening_amount; 
                }
                $balance += $list_row->opening_amount;
            } else if(isset($list_row->transaction_type)){
                $particular = 'Transaction';
                if($list_row->transaction_type == '1'){
                    $debit_amt = 0;
                    $credit_amt = $list_row->amount; 
                    $total_credit_amt += $list_row->amount; 
                    $balance += $list_row->amount; 
                } else {
                    $credit_amt = 0;
                    $debit_amt = $list_row->amount; 
                    $total_debit_amt += $list_row->amount; 
                    $balance -= $list_row->amount; 
                }

                if($list_row->transaction_type == '1'){
                    $particular = '<a href="'.base_url("transaction/payment/" . $list_row->transaction_id) . '">Transaction</a>';

                } elseif($list_row->transaction_type == '2'){
                    $particular = '<a href="'.base_url("transaction/receipt/" . $list_row->transaction_id) . '">Transaction</a>';

                } elseif($list_row->transaction_type == '3'){
                    $particular = '<a href="'.base_url("contra/contra/" . $list_row->transaction_id) . '">Transaction</a>';
                
                } elseif($list_row->transaction_type == '4'){
                    $particular = '<a href="'.base_url("contra/contra/" . $list_row->transaction_id) . '">Transaction</a>';
                }

            } else if(isset($list_row->purchase_invoice_id) || isset($list_row->credit_note_id)){
                if(isset($list_row->purchase_invoice_id)){

                    if($is_single_line_item == 1) {
                        if($list_row->invoice_type == 3) {
                            $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url('transaction/order_type2') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                        } else {
                            $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url($list_row->invoice_type == '2' ? 'transaction/sales_purchase_transaction/purchase' : 'purchase/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                        }
                    } else {
                        $particular = '<form id="edit_' . $list_row->purchase_invoice_id . '" method="post" action="' . base_url($list_row->invoice_type ? 'purchase/invoice' : 'purchase/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $list_row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> ';
                    }

                } else if(isset($list_row->credit_note_id)){
                    if($is_single_line_item == 1) {
                        $particular = '<form id="edit_' . $list_row->credit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/credit_note" style="width: 25px; display: initial;" >
                                <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $list_row->credit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->credit_note_id . '\').submit();" title="Edit Credit Note">Credit Note</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->credit_note_id . '" method="post" action="' . base_url() . 'credit_note/add" style="width: 25px; display: initial;" >
                                <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $list_row->credit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->credit_note_id . '\').submit();" title="Edit Credit Note">Credit Note</a>
                            </form> ';
                    }
                }
                $debit_amt = 0;
                $credit_amt = $list_row->amount_total; 
                $total_credit_amt += $list_row->amount_total; 
                $balance += $list_row->amount_total; 
            } else if (isset($list_row->sales_invoice_id) || isset($list_row->debit_note_id)) {
                if(isset($list_row->sales_invoice_id)){
                    if($is_single_line_item == 1) {
                        $particular = '<form id="edit_' . $list_row->sales_invoice_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/sales" style="width: 25px; display: initial;" >
                                <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $list_row->sales_invoice_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->sales_invoice_id . '" method="post" action="' . base_url() . 'sales/invoice" style="width: 25px; display: initial;" >
                                <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $list_row->sales_invoice_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                            </form> ';
                    }
                } else if(isset($list_row->debit_note_id)){
                    if($is_single_line_item == 1) {
                        $particular = '<form id="edit_' . $list_row->debit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/debit_note" style="width: 25px; display: initial;" >
                                <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $list_row->debit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->debit_note_id . '\').submit();" title="Edit Debit Note">Debit Note</a>
                            </form> ';
                    } else {
                        $particular = '<form id="edit_' . $list_row->debit_note_id . '" method="post" action="' . base_url() . 'debit_note/add" style="width: 25px; display: initial;" >
                                <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $list_row->debit_note_id . '">
                                <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $list_row->debit_note_id . '\').submit();" title="Edit Debit Note">Debit Note</a>
                            </form> ';
                    }
                }
                $credit_amt = 0;
                $debit_amt = $list_row->amount_total; 
                $total_debit_amt += $list_row->amount_total; 
                $balance -= $list_row->amount_total; 
            }
            $row[] = $date;
            $row[] = $particular;
            $row[] = abs($credit_amt);
            $row[] = abs($debit_amt);
            $row[] = $balance;
            $data[] = $row;
        }
        $total[] = '';
        $total[] = 'Total';
        $total[] = $total_credit_amt;
        $total[] = $total_debit_amt;
        $total[] = '';
        $data[] = $total;
        
        $total = $opening_amount + $total_credit_amt - $total_debit_amt;
        $total2[] = '';
        $total2[] = '<b>Closing Balance</b>';
        $total2[] = '';
        $total2[] = '';
        $total2[] = '<b>'.$total.'</b>';
        $data[] = $total2;
        $output = array(
            "draw" => $_POST['draw'],
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    function summary(){
        if($this->applib->have_access_role(MODULE_SUMMARY_ID,"view")) {
            set_page('report/summary_report');
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function summary_datatable() {
        $from_date = date('Y-m-d', strtotime($_POST['daterange_1']));
        $company_id = $this->logged_in_id;
        if(isset($_POST['account_id']) && !empty($_POST['account_id'])) {
            $accounts = $this->crud->getFromSQL("SELECT account_id,account_name,opening_balance,credit_debit FROM account WHERE account_id=".$_POST['account_id']." ");
        } else {
            $accounts = $this->crud->getFromSQL("SELECT account_id,account_name,opening_balance,credit_debit FROM account WHERE created_by=".$company_id." ");
        }
        $summary_arr = array();
        $total_credit = 0;
        $total_debit = 0;
        if(!empty($accounts)){
            foreach ($accounts as $account){
                $opening_balance_credit = 0;
                if($account->credit_debit == 1) {
                    $opening_balance_credit += $account->opening_balance;
                }

                $opening_balance_debit = 0;
                if($account->credit_debit == 2) {
                    $opening_balance_debit += $account->opening_balance;
                }

                $tr_credit = $this->crud->getFromSQL("SELECT SUM(amount) as total_credit_amount FROM transaction_entry WHERE transaction_type =1 AND from_account_id=".$account->account_id." AND transaction_date <= '".$from_date."'");
                $tr_credit = $tr_credit[0]->total_credit_amount;
                $tr_debit = $this->crud->getFromSQL("SELECT SUM(amount) as total_debit_amount FROM transaction_entry WHERE transaction_type =2 AND from_account_id=".$account->account_id." AND transaction_date <= '".$from_date."'");
                $tr_debit = $tr_debit[0]->total_debit_amount;
                $purchase_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_purchase_amount FROM purchase_invoice WHERE account_id=".$account->account_id." AND purchase_invoice_date <= '".$from_date."'");
                $purchase_opening = $purchase_opening[0]->total_purchase_amount;
                $sales_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_sales_amount FROM sales_invoice WHERE account_id=".$account->account_id." AND sales_invoice_date <= '".$from_date."'");
                $sales_opening = $sales_opening[0]->total_sales_amount;
                $credit_note_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_credit_note_amount FROM credit_note WHERE account_id=".$account->account_id." AND credit_note_date <= '".$from_date."'");
                $credit_note_opening = $credit_note_opening[0]->total_credit_note_amount;
                $debit_note_opening = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_debit_note_amount FROM debit_note WHERE account_id=".$account->account_id." AND debit_note_date <= '".$from_date."'");
                $debit_note_opening = $debit_note_opening[0]->total_debit_note_amount;
                $credit_total = $opening_balance_credit + $tr_credit + $purchase_opening + $credit_note_opening;
                $debit_total = $opening_balance_debit + $tr_debit + $sales_opening + $debit_note_opening;
                $opening_bal = $credit_total - $debit_total;
                if($opening_bal >= 0){
                    $credit_amt = abs($opening_bal);
                    $total_credit += abs($opening_bal);
                    $summary_arr[] = array("account_id" => $account->account_id, "account" => $account->account_name, "credit_amt" => $credit_amt, "debit_amt" => "0");
                    $debit_amt = 0;
                } else {
                    $debit_amt = abs($opening_bal);
                    $total_debit += abs($opening_bal);
                    $summary_arr[] = array("account_id" => $account->account_id, "account" => $account->account_name, "credit_amt" => "0", "debit_amt" => $debit_amt);
                    $credit_amt = 0;
                }
            }
        }
        $data = array();
        $tr_type = $_POST['tr_type'];

        foreach ($summary_arr as $summ_row) {
            $row = array();
            if($tr_type == 0 && ($summ_row['credit_amt'] != 0 || $summ_row['debit_amt'] != 0)){
                $row[] = '<a href="'.base_url("report/ledger/". $summ_row['account_id']). '">'.$summ_row['account'];

                $row[] = $summ_row['credit_amt'];
                $row[] = $summ_row['debit_amt'];
                $data[] = $row;
            } else if($tr_type == 1 && $summ_row['credit_amt'] != 0) {
                $row[] = '<a href="'.base_url("report/ledger/". $summ_row['account_id']). '">'.$summ_row['account'];
                $row[] = $summ_row['credit_amt'];
                $row[] = $summ_row['debit_amt'];
                $data[] = $row;
            } else if($tr_type == 2 && $summ_row['debit_amt'] != 0) {
                $row[] = '<a href="'.base_url("report/ledger/". $summ_row['account_id']). '">'.$summ_row['account'];
                $row[] = $summ_row['credit_amt'];
                $row[] = $summ_row['debit_amt'];
                $data[] = $row;
            }
        }
        $total[] = '<b>Total</b>';
        $total[] = '<b>'.$total_credit.'</b>';
        $total[] = '<b>'.$total_debit.'</b>';
        $data[] = $total;
        $output = array(
            "draw" => $_POST['draw'],
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function summary_billwise($tr_type = 'all'){
        if($this->applib->have_access_role(MODULE_SUMMARY_ID,"view")) {
            $data = array();
            $data['tr_type'] = '';
            if($tr_type == "payable")  {
                $data['tr_type'] = 1;
            }
            if($tr_type == "receivable")  {
                $data['tr_type'] = 2;
            }
            if($tr_type == "billwise_payable")  {
                $data['tr_type'] = 4;
            }
            if($tr_type == "billwise_receivable")  {
                $data['tr_type'] = 5;
            }
            set_page('report/summary_billwise_report',$data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function summary_billwise_datatable() {
        $from_date = date('Y-m-d', strtotime($_POST['daterange_1']));
        $company_id = $this->logged_in_id;
        if(isset($_POST['account_id']) && !empty($_POST['account_id'])) {
            $accounts = $this->crud->getFromSQL("SELECT account_id,account_name,opening_balance,credit_debit FROM account WHERE account_id=".$_POST['account_id']." ");
        } else {
            $accounts = $this->crud->getFromSQL("SELECT account_id,account_name,opening_balance,credit_debit FROM account WHERE created_by=".$company_id." ");
        }
        $summary_arr = array();
        $billwise_summary_arr = array();
        $total_credit = 0;
        $total_debit = 0;
        if(!empty($accounts)){
            foreach ($accounts as $account){
                $opening_balance_credit = 0;
                /*if($account->credit_debit == 1) {
                    $opening_balance_credit += $account->opening_balance;

                    $billwise_summary_arr[] = array("account_id" => $account->account_id, "account" => $account->account_name, "credit_amt" => $account->opening_balance, "debit_amt" => "0","detail" => "Opening Balance");
                }*/

                $opening_balance_debit = 0;
                /*if($account->credit_debit == 2) {
                    $opening_balance_debit += $account->opening_balance;

                    $billwise_summary_arr[] = array("account_id" => $account->account_id, "account" => $account->account_name, "credit_amt" => "0", "debit_amt" => $account->opening_balance,"detail" => "Opening Balance");
                }*/


                $tr_credit = 0;
                $tr_credit_res = $this->crud->getFromSQL("SELECT * FROM transaction_entry WHERE transaction_type =1 AND from_account_id=".$account->account_id." AND transaction_date <= '".$from_date."'");
                if(!empty($tr_credit_res)) {
                    foreach ($tr_credit_res as $row) {
                        $tr_credit += $row->amount;
                        $billwise_summary_arr[] = array(
                            "account_id" => $account->account_id,
                            "account" => $account->account_name,
                            "credit_amt" => $row->amount, 
                            "debit_amt" => "0",
                            "bill_date" => $row->transaction_date,
                            "particular" => '<a href="'.base_url("transaction/payment/" . $row->transaction_id) . '">Payment</a>',
                        );
                    }
                }

                $tr_debit = 0;
                $tr_debit_res = $this->crud->getFromSQL("SELECT * FROM transaction_entry WHERE transaction_type =2 AND from_account_id=".$account->account_id." AND transaction_date <= '".$from_date."'");
                if(!empty($tr_debit_res)) {
                    foreach ($tr_debit_res as $row) {
                        $tr_debit += $row->amount;
                        $billwise_summary_arr[] = array(
                            "account_id" => $account->account_id,
                            "account" => $account->account_name,
                            "credit_amt" => "0", 
                            "debit_amt" => $row->amount,
                            "bill_date" => $row->transaction_date,
                            "particular" => '<a href="'.base_url("transaction/receipt/" . $row->transaction_id) . '">Receipt</a>',
                        );
                    }
                }
                
                $purchase_opening = 0;
                $purchase_res = $this->crud->getFromSQL("SELECT * FROM purchase_invoice WHERE account_id=".$account->account_id." AND purchase_invoice_date <= '".$from_date."'");
                if(!empty($purchase_res)) {
                    foreach ($purchase_res as $row) {
                        $purchase_opening += $row->amount_total;
                        $billwise_summary_arr[] = array(
                            "account_id" => $account->account_id,
                            "account" => $account->account_name,
                            "credit_amt" => $row->amount_total, 
                            "debit_amt" => "0",
                            "bill_date" => $row->purchase_invoice_date,
                            "particular" => '<form id="edit_' . $row->purchase_invoice_id . '" method="post" action="' . base_url($row->invoice_type == '2' ? 'transaction/sales_purchase_transaction/purchase' : ($row->invoice_type == '3'?'purchase/order_type2':'purchase/order')) . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $row->purchase_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $row->purchase_invoice_id . '\').submit();" title="Edit Invoice">Purchase Invoice</a>
                                        </form> '
                        );
                    }
                }

                $sales_opening = 0;
                $sales_opening_res = $this->crud->getFromSQL("SELECT * FROM sales_invoice WHERE account_id=".$account->account_id." AND sales_invoice_date <= '".$from_date."'");
                if(!empty($sales_opening_res)) {
                    foreach ($sales_opening_res as $row) {
                        $sales_opening += $row->amount_total;
                        $billwise_summary_arr[] = array(
                            "account_id" => $account->account_id,
                            "account" => $account->account_name,
                            "credit_amt" => "0", 
                            "debit_amt" => $row->amount_total,
                            "bill_date" => $row->sales_invoice_date,
                            "particular" => '<form id="edit_' . $row->sales_invoice_id . '" method="post" action="' . base_url('transaction/sales_purchase_transaction/sales') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $row->sales_invoice_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $row->sales_invoice_id . '\').submit();" title="Edit Invoice">Sales Invoice</a>
                                        </form> '
                        );
                    }
                }

                $credit_note_opening = 0;
                $credit_note_res = $this->crud->getFromSQL("SELECT * FROM credit_note WHERE account_id=".$account->account_id." AND credit_note_date <= '".$from_date."'");
                if(!empty($credit_note_res)) {
                    foreach ($credit_note_res as $row) {
                        $credit_note_opening += $row->amount_total;
                        $billwise_summary_arr[] = array(
                            "account_id" => $account->account_id,
                            "account" => $account->account_name,
                            "credit_amt" => $row->amount_total, 
                            "debit_amt" => "0",
                            "bill_date" => $row->credit_note_date,
                            "particular" => '<form id="edit_' . $row->credit_note_id . '" method="post" action="' . base_url('transaction/sales_purchase_transaction/credit_note') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $row->credit_note_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $row->credit_note_id . '\').submit();" title="Edit Invoice">Credit Note</a>
                                        </form> '
                        );
                    }
                }

                $debit_note_opening = 0;
                $debit_note_res = $this->crud->getFromSQL("SELECT * FROM debit_note WHERE account_id=".$account->account_id." AND debit_note_date <= '".$from_date."'");
                if(!empty($debit_note_res)) {
                    foreach ($debit_note_res as $row) {
                        $debit_note_opening += $row->amount_total;
                        $billwise_summary_arr[] = array(
                            "account_id" => $account->account_id,
                            "account" => $account->account_name,
                            "credit_amt" => "0", 
                            "debit_amt" => $row->amount_total,
                            "bill_date" => $row->debit_note_date,
                            "particular" => '<form id="edit_' . $row->debit_note_id . '" method="post" action="' . base_url('transaction/sales_purchase_transaction/debit_note') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="debit_note_id" id="debit_note_id" value="' . $row->debit_note_id . '">
                                            <a class="edit_button" href="javascript:{}" onclick="document.getElementById(\'edit_' . $row->debit_note_id . '\').submit();" title="Debit Note">Debit Note</a>
                                        </form> '
                        );
                    }
                }


                $credit_total = $opening_balance_credit + $tr_credit + $purchase_opening + $credit_note_opening;
                $debit_total = $opening_balance_debit + $tr_debit + $sales_opening + $debit_note_opening;

                $opening_bal = $credit_total - $debit_total;

                if($opening_bal >= 0){
                    $credit_amt = abs($opening_bal);
                    $total_credit += abs($opening_bal);
                    $summary_arr[] = array("account_id" => $account->account_id, "account" => $account->account_name, "credit_amt" => $credit_amt, "debit_amt" => "0");
                    $debit_amt = 0;
                } else {
                    $debit_amt = abs($opening_bal);
                    $total_debit += abs($opening_bal);
                    $summary_arr[] = array("account_id" => $account->account_id, "account" => $account->account_name, "credit_amt" => "0", "debit_amt" => $debit_amt);
                    $credit_amt = 0;
                }
            }
        }
        
        $tr_type = $_POST['tr_type'];

        $data = array();

        $bill_date_arr = array_column($billwise_summary_arr, 'bill_date');
        array_multisort($bill_date_arr,SORT_DESC,$billwise_summary_arr);

        $total_credit = 0;
        $total_debit = 0;

        if(in_array($tr_type,array(3,4,5))) {
            foreach ($billwise_summary_arr as $key => $summ_row) {
                $total_credit += $summ_row['credit_amt'];
                $total_debit += $summ_row['debit_amt'];

                $row = array();
                $row[] = date("d-m-Y",strtotime($summ_row['bill_date']));
                $row[] = $summ_row['particular'];
                $row[] = '<a href="'.base_url("report/ledger/". $summ_row['account_id']). '">'.$summ_row['account'];

                if($tr_type == 3 && ($summ_row['credit_amt'] != 0 || $summ_row['debit_amt'] != 0)){
                    $row[] = $summ_row['credit_amt'];
                    $row[] = $summ_row['debit_amt'];
                    $data[] = $row;
                } else if($tr_type == 4 && $summ_row['credit_amt'] != 0) {
                    $row[] = $summ_row['credit_amt'];
                    $row[] = $summ_row['debit_amt'];
                    $data[] = $row;
                } else if($tr_type == 5 && $summ_row['debit_amt'] != 0) {
                    $row[] = $summ_row['credit_amt'];
                    $row[] = $summ_row['debit_amt'];
                    $data[] = $row;
                }   
            }
        } else {
            foreach ($summary_arr as $summ_row) {
                $total_credit += $summ_row['credit_amt'];
                $total_debit += $summ_row['debit_amt'];

                $row = array();
                $row[] = '';
                $row[] = '';
                $row[] = '<a href="'.base_url("report/ledger/". $summ_row['account_id']). '">'.$summ_row['account'];

                if($tr_type == 0 && ($summ_row['credit_amt'] != 0 || $summ_row['debit_amt'] != 0)){
                    
                    $row[] = $summ_row['credit_amt'];
                    $row[] = $summ_row['debit_amt'];
                    $data[] = $row;
                } else if($tr_type == 1 && $summ_row['credit_amt'] != 0) {
                    
                    $row[] = $summ_row['credit_amt'];
                    $row[] = $summ_row['debit_amt'];
                    $data[] = $row;
                } else if($tr_type == 2 && $summ_row['debit_amt'] != 0) {
                    
                    $row[] = $summ_row['credit_amt'];
                    $row[] = $summ_row['debit_amt'];
                    $data[] = $row;
                }
            }
        }

        $total = array();
        $total[] = '<b>Total</b>';
        $total[] = '';
        $total[] = '';
        $total[] = '<b>'.$total_credit.'</b>';
        $total[] = '<b>'.$total_debit.'</b>';
        $data[] = $total;
        $output = array(
            "draw" => $_POST['draw'],
            "data" => $data,
        );
        echo json_encode($output);
    }

    function item_history_datatable() {
        $item_id = -1;
        if(isset($_POST['item_id'])) {
            $item_id = $_POST['item_id'];
        }
        $company_id = $this->logged_in_id;
        $data = array();

        $sale_item_res = array();
        $this->db->select("l.*,inv.sales_invoice_id,inv.sales_invoice_date,inv.bill_no,a.account_name,item.item_name");
        $this->db->from('lineitems l');
        $this->db->join('item','item.item_id = l.item_id');
        $this->db->join('sales_invoice inv','inv.sales_invoice_id = l.parent_id');
        $this->db->join('account a','a.account_id = inv.account_id');
        $this->db->where('l.module',2);
        $this->db->where('l.item_id',$item_id);
        $this->db->where('inv.created_by',$company_id);
        $this->db->order_by('inv.sales_invoice_date','desc');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $row = array();
            $row[] = "<strong>Sales Invoice</strong>";
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $data[] = $row;

            foreach ($query->result() as $key => $value) {
                $sales_invoice_no = $this->applib->format_invoice_number($value->sales_invoice_id, $value->sales_invoice_date);

                $sale_item_res[] = array(
                    'item_id' => $value->item_id,
                    'item_name' => $value->item_name,
                    'qty' => $value->item_qty,
                    'price' => $value->price,
                    'pure_amount' => $value->pure_amount,
                    'discounted_price' => $value->discounted_price,
                    'amount' => $value->amount,
                    'bill_no' => $sales_invoice_no,
                    'account_name' => $value->account_name,
                    'bill_date' => $value->sales_invoice_date,

                );
            }
        }
        foreach ($sale_item_res as $key => $item_row) {
            $row = array();
            $row[] = $item_row['account_name'];
            $row[] = $item_row['bill_no'];
            $row[] = date("d-m-Y",strtotime($item_row['bill_date']));
            $row[] = $item_row['qty'];
            $row[] = number_format((float) $item_row['price'], 2, '.', '');;
            $row[] = number_format((float) $item_row['amount'], 2, '.', '');
            $data[] = $row;
        }

        $purchase_item_res = array();
        $this->db->select("l.*,inv.purchase_invoice_date,inv.bill_no,a.account_name,item.item_name");
        $this->db->from('lineitems l');
        $this->db->join('item','item.item_id = l.item_id');
        $this->db->join('purchase_invoice inv','inv.purchase_invoice_id = l.parent_id');
        $this->db->join('account a','a.account_id = inv.account_id');
        $this->db->where('l.module',1);
        $this->db->where('l.item_id',$item_id);
        $this->db->where('inv.created_by',$company_id);
        $this->db->order_by('inv.purchase_invoice_date','desc');
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $row = array();
            $row[] = "<strong>Purchase Invoice</strong>";
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $data[] = $row;
            foreach ($query->result() as $key => $value) {
                $purchase_item_res[] = array(
                    'item_id' => $value->item_id,
                    'item_name' => $value->item_name,
                    'qty' => $value->item_qty,
                    'price' => $value->price,
                    'pure_amount' => $value->pure_amount,
                    'discounted_price' => $value->discounted_price,
                    'amount' => $value->amount,
                    'bill_no' => $value->bill_no,
                    'account_name' => $value->account_name,
                    'bill_date' => $value->purchase_invoice_date,

                );
            }
        }     
        foreach ($purchase_item_res as $key => $item_row) {
            $row = array();
            $row[] = $item_row['account_name'];
            $row[] = $item_row['bill_no'];
            $row[] = date("d-m-Y",strtotime($item_row['bill_date']));
            $row[] = $item_row['qty'];
            $row[] = number_format((float) $item_row['price'], 2, '.', '');;
            $row[] = number_format((float) $item_row['amount'], 2, '.', '');
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function balance_sheet(){
        if($this->applib->have_access_role(MODULE_BALANCE_SHEET_ID,"view")) {
            $data = array();
            $data['from_date'] = ($this->session->userdata('balance_sheet_from_date')?$this->session->userdata('balance_sheet_from_date'):get_financial_start_date_by_date());
            $data['to_date'] = ($this->session->userdata('balance_sheet_to_date')?$this->session->userdata('balance_sheet_to_date'):date('d-m-Y'));
            set_page('report/balance_sheet',$data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function balance_sheet_datatable() {
        $company_id = $this->logged_in_id;

        $account_group_res = $this->crud->getFromSQL("SELECT account_group_id,account_group_name,display_in_balance_sheet FROM account_group WHERE display_in_balance_sheet = 1");

        $bs_account_groups = array();
        $account_group_names = array();
        if(!empty($account_group_res)) {
            foreach ($account_group_res as $key => $account_group_row) {
                $account_group_names[$account_group_row->account_group_id] = $account_group_row->account_group_name;
                $bs_account_groups[] = $account_group_row->account_group_id;
            }
        }

        if(!empty($bs_account_groups)) {
            $account_res = $this->crud->getFromSQL("SELECT account_id,account_name,account_group_id FROM account WHERE created_by='".$company_id."' AND account_group_id IN(".implode(',',$bs_account_groups).")");
        }
        

        $from_date = isset($_POST['from_date']) && strtotime($_POST['from_date']) > 0?date("Y-m-d",strtotime($_POST['from_date'])):date("Y-04-01");
        $to_date = isset($_POST['to_date']) && strtotime($_POST['to_date']) > 0?date("Y-m-d",strtotime($_POST['to_date'])):date("Y-m-d");

        $this->session->set_userdata('balance_sheet_from_date',$from_date);
        $this->session->set_userdata('balance_sheet_to_date',$to_date);

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $tmp_from_date = date("Y-m-d",strtotime('-1 day',strtotime($from_date)));
        $total_credit_amount = 0;
        $total_debit_amount = 0;
        $capital = 0;
        $cr_account_group_res = array();
        $dr_account_group_res = array();

        if(!empty($account_res)){
            foreach ($account_res as $account_row){
                $capital = $capital + $this->crud->get_account_balance($account_row->account_id,$tmp_from_date);

                $acc_balance = $this->crud->get_account_balance($account_row->account_id,$to_date);
                if($acc_balance == 0) {
                    continue;
                }
                if($acc_balance >= 0){
                    $total_debit_amount += abs($acc_balance);
                    $account_arr_row = array(
                        "account_id" => $account_row->account_id, 
                        "account_group_id" => $account_row->account_group_id,
                        "account_name" => '<a href="'.base_url("report/ledger/". $account_row->account_id). '">'.$account_row->account_name.'</a>',
                        "amount" => abs($acc_balance)
                    );
                    $dr_account_arr[] = $account_arr_row;
                    if(isset($dr_account_group_res[$account_row->account_group_id])) {
                        $dr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    } else {
                        $dr_account_group_res[$account_row->account_group_id] = array();
                        $dr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    }
                    
                } else {
                    $total_credit_amount += abs($acc_balance);
                    $account_arr_row = array(
                        "account_id" => $account_row->account_id, 
                        "account_group_id" => $account_row->account_group_id,
                        "account_name" => '<a href="'.base_url("report/ledger/". $account_row->account_id). '">'.$account_row->account_name.'</a>',
                        "amount" => abs($acc_balance)
                    );
                    $cr_account_arr[] = $account_arr_row;
                    if(isset($cr_account_group_res[$account_row->account_group_id])) {
                        $cr_account_group_res[$account_row->account_group_id][] = $account_arr_row;    
                    } else {
                        $cr_account_group_res[$account_row->account_group_id] = array();
                        $cr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    }
                }
            }
        }
        /*echo "<pre>";
        print_r($cr_account_group_res);
        die();*/
        
        $dr_account_arr = array();
        foreach ($dr_account_group_res as $account_group_id => $account_group_row) {
            $dr_account_arr[]  = array(
                'account_name' => '<b>'.strtoupper($account_group_names[$account_group_id]).'</b>',
            );
            foreach ($dr_account_group_res[$account_group_id] as $key => $account_group_tr) {
                $dr_account_arr[]  = $account_group_tr;  
            }
        }

        $cr_account_arr = array();
        foreach ($cr_account_group_res as $account_group_id => $account_group_row) {
            $cr_account_arr[]  = array(
                'account_name' => '<b>'.strtoupper($account_group_names[$account_group_id]).'</b>',
            );
            foreach ($cr_account_group_res[$account_group_id] as $key => $account_group_tr) {
                $cr_account_arr[]  = $account_group_tr;  
            }
        }

        $profit_loss_acc = $this->get_profit_loss_acc_balance($from_date,$to_date);

        if($profit_loss_acc !== 0) {

            if($profit_loss_acc >= 0) {
                $total_debit_amount += abs($profit_loss_acc);
                $dr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "account_name" => '<a href="'.base_url("report/profit_loss"). '">Profit & Loss A/c</a>',
                    "amount" => abs($profit_loss_acc)
                );
            } else {
                $total_credit_amount += abs($profit_loss_acc);
                $cr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "account_name" => '<a href="'.base_url("report/profit_loss"). '">Profit & Loss A/c</a>',
                    "amount" => abs($profit_loss_acc)
                );
            }
        }

        if($capital >= 0) {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        } else {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        }

        $data = array();

        /*---- Find Net Profit/Loss ----*/
        if($total_debit_amount != $total_credit_amount) {
            if($total_debit_amount > $total_credit_amount) { //Profit

                $total_net_amount = $total_debit_amount;
                $net_profit = $total_debit_amount - $total_credit_amount;

                $data[] = array(
                    'Net Profit',
                    number_format((float) $net_profit, 2, '.', ''),
                    '',
                    '',
                    ''
                );
            } else {

                $total_net_amount = $total_credit_amount;
                $net_loss = $total_credit_amount - $total_debit_amount;

                $data[] = array(
                    '',
                    '',
                    '',
                    'Net Loss',
                    number_format((float) $net_loss, 2, '.', ''),
                );
            }
        } else {
            $total_net_amount = $total_credit_amount;
        }
        /*---- Find Net Profit/Loss ----*/

        $for_loop_limit = (count($dr_account_arr) > count($cr_account_arr) ?count($dr_account_arr):count($cr_account_arr));

        for ($i=0; $i < $for_loop_limit; $i++) { 
            $row = array();
            
            if(isset($cr_account_arr[$i]['account_name'])) {
                $row[] = $cr_account_arr[$i]['account_name'];
                if(isset($cr_account_arr[$i]['amount'])) {
                    $row[] = number_format((float) $cr_account_arr[$i]['amount'], 2, '.', '');
                } else {
                    $row[] = '';    
                }
                
            } else {
                $row[] = '';
                $row[] = '';
            }
            
            $row[] = '';

            if(isset($dr_account_arr[$i]['account_name'])) {
                $row[] = $dr_account_arr[$i]['account_name'];
                if(isset($dr_account_arr[$i]['amount'])) {
                    $row[] = number_format((float) $dr_account_arr[$i]['amount'], 2, '.', '');
                } else {
                    $row[] = '';    
                }
            } else {
                $row[] = '';
                $row[] = '';
            }

            $data[] = $row;
        }

        $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');

        $output = array(
            "draw" => $_POST['draw'],
            "from_date" => $from_date,
            "to_date" => $to_date,
            "capital" => $capital,
            "total_net_amount" => $total_net_amount,
            "total_debit_amount" => number_format((float) $total_debit_amount, 2, '.', ''),
            "total_credit_amount" => number_format((float) $total_credit_amount, 2, '.', ''),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);
    }
    
    function balance_sheet_new(){
        if($this->applib->have_access_role(MODULE_BALANCE_SHEET_ID,"view")) {
            $data = array();
            $data['company_row'] = $this->crud->get_data_row_by_id('user', 'user_id',$this->logged_in_id);
            $data['from_date'] = ($this->session->userdata('balance_sheet_from_date')?$this->session->userdata('balance_sheet_from_date'):get_financial_start_date_by_date());
            $data['to_date'] = ($this->session->userdata('balance_sheet_to_date')?$this->session->userdata('balance_sheet_to_date'):date('d-m-Y'));
            set_page('report/balance_sheet_new',$data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function balance_sheet_new_datatable() {
        $company_id = $this->logged_in_id;

        $account_group_res = $this->crud->getFromSQL("SELECT account_group_id,account_group_name,display_in_balance_sheet FROM account_group WHERE display_in_balance_sheet = 1");

        $bs_account_groups = array();
        $account_group_names = array();
        if(!empty($account_group_res)) {
            foreach ($account_group_res as $key => $account_group_row) {
                $account_group_names[$account_group_row->account_group_id] = $account_group_row->account_group_name;
                $bs_account_groups[] = $account_group_row->account_group_id;
            }
        }

        if(!empty($bs_account_groups)) {
            $account_res = $this->crud->getFromSQL("SELECT account_id,account_name,account_group_id FROM account WHERE created_by='".$company_id."' AND account_group_id IN(".implode(',',$bs_account_groups).")");
        }
        

        $from_date = isset($_POST['from_date']) && strtotime($_POST['from_date']) > 0?date("Y-m-d",strtotime($_POST['from_date'])):date("Y-04-01");
        $to_date = isset($_POST['to_date']) && strtotime($_POST['to_date']) > 0?date("Y-m-d",strtotime($_POST['to_date'])):date("Y-m-d");

        $this->session->set_userdata('balance_sheet_from_date',$from_date);
        $this->session->set_userdata('balance_sheet_to_date',$to_date);

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $tmp_from_date = date("Y-m-d",strtotime('-1 day',strtotime($from_date)));
        $total_credit_amount = 0;
        $total_debit_amount = 0;
        $capital = 0;
        $cr_account_group_res = array();
        $dr_account_group_res = array();

        if(!empty($account_res)){
            foreach ($account_res as $account_row){
                $capital = $capital + $this->crud->get_account_balance($account_row->account_id,$tmp_from_date);

                $acc_balance = $this->crud->get_account_balance($account_row->account_id,$to_date);
                if($acc_balance == 0) {
                    continue;
                }

                if($acc_balance >= 0){
                    $total_debit_amount += abs($acc_balance);
                    $account_arr_row = array(
                        "account_id" => $account_row->account_id, 
                        "account_group_id" => $account_row->account_group_id,
                        "particular" => '<a href="'.base_url("report/ledger/". $account_row->account_id). '">'.$account_row->account_name.'</a>',
                        "amount" => number_format((float) abs($acc_balance), 2, '.', '')
                    );
                    $dr_account_arr[] = $account_arr_row;
                    if(isset($dr_account_group_res[$account_row->account_group_id])) {
                        $dr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    } else {
                        $dr_account_group_res[$account_row->account_group_id] = array();
                        $dr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    }
                    
                } else {
                    ;
                    $total_credit_amount += abs($acc_balance);
                    $account_arr_row = array(
                        "account_id" => $account_row->account_id, 
                        "account_group_id" => $account_row->account_group_id,
                        "particular" => '<a href="'.base_url("report/ledger/". $account_row->account_id). '">'.$account_row->account_name.'</a>',
                        "amount" => $acc_balance = number_format((float) abs($acc_balance), 2, '.', '')
                    );
                    $cr_account_arr[] = $account_arr_row;
                    if(isset($cr_account_group_res[$account_row->account_group_id])) {
                        $cr_account_group_res[$account_row->account_group_id][] = $account_arr_row;    
                    } else {
                        $cr_account_group_res[$account_row->account_group_id] = array();
                        $cr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    }
                }
            }
        }
        /*echo "<pre>";
        print_r($cr_account_group_res);
        die();*/
        
        $dr_account_arr = array();
        foreach ($dr_account_group_res as $account_group_id => $account_group_row) {
            $dr_account_arr[]  = array(
                'particular' => '<b>'.strtoupper($account_group_names[$account_group_id]).'</b>',
                'amount' => '',
            );
            $group_total = 0;
            foreach ($dr_account_group_res[$account_group_id] as $key => $account_group_tr) {
                $dr_account_arr[]  = $account_group_tr;  
                $group_total += $account_group_tr['amount'];
            }
            $dr_account_arr[]  = array(
                'is_group_total' => 'true',
                'particular' => '',
                'amount' => '<b>'.number_format((float) $group_total, 2, '.', '').'</b>',
            );
            $dr_account_arr[] = array(
                "particular" => '&nbsp;',
                "amount" => '',
            );
        }

        $cr_account_arr = array();
        foreach ($cr_account_group_res as $account_group_id => $account_group_row) {
            $cr_account_arr[]  = array(
                'particular' => '<b>'.strtoupper($account_group_names[$account_group_id]).'</b>',
                'amount' => '',
            );
            $group_total = 0;
            foreach ($cr_account_group_res[$account_group_id] as $key => $account_group_tr) {
                $cr_account_arr[]  = $account_group_tr;
                $group_total += $account_group_tr['amount'];
            }
            $cr_account_arr[]  = array(
                'is_group_total' => 'true',
                'particular' => '',
                'amount' => '<b>'.number_format((float) $group_total, 2, '.', '').'</b>',
            );
            $cr_account_arr[] = array(
                "particular" => '&nbsp;',
                "amount" => '',
            );
        }

        $profit_loss_acc = $this->get_profit_loss_acc_balance($from_date,$to_date);

        if($profit_loss_acc !== 0) {
            if($profit_loss_acc >= 0) {
                $total_debit_amount += abs($profit_loss_acc);
                $dr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => '<a href="'.base_url("report/profit_loss"). '">Profit & Loss A/c</a>',
                    "amount" => abs($profit_loss_acc)
                );
            } else {
                $total_credit_amount += abs($profit_loss_acc);
                $cr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => '<a href="'.base_url("report/profit_loss"). '">Profit & Loss A/c</a>',
                    "amount" => abs($profit_loss_acc)
                );
            }
        }

        if($capital >= 0) {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "particular" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        } else {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "particular" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        }

        $data = array();

        /*---- Find Net Profit/Loss ----*/
        if($total_debit_amount != $total_credit_amount) {
            if($total_debit_amount > $total_credit_amount) { //Profit

                $total_net_amount = $total_debit_amount;
                $net_profit = $total_debit_amount - $total_credit_amount;
                $cr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => 'Net Profit',
                    "amount" => number_format((float) $net_profit, 2, '.', ''),
                );
            } else {

                $total_net_amount = $total_credit_amount;
                $net_loss = $total_credit_amount - $total_debit_amount;
                $dr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => 'Net Loss',
                    "amount" => number_format((float) $net_loss, 2, '.', ''),
                );
            }
        } else {
            $total_net_amount = $total_credit_amount;
        }
        /*---- Find Net Profit/Loss ----*/

        $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');

        $for_loop_limit = (count($dr_account_arr) > count($cr_account_arr) ?count($dr_account_arr):count($cr_account_arr));

        for ($i=0; $i < $for_loop_limit + 5; $i++) { 
            if(!isset($cr_account_arr[$i])) {
                $cr_account_arr[$i] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
            }
            if(!isset($dr_account_arr[$i])) {
                $dr_account_arr[$i] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
            }
        }

        $output = array(
            "date_range" => 'From '.date('d-m-Y',strtotime($from_date)).' To '.date('d-m-Y',strtotime($to_date)),
            "cr_account_arr" => $cr_account_arr,
            "dr_account_arr" => $dr_account_arr,
            "from_date" => $from_date,
            "to_date" => $to_date,
            "capital" => $capital,
            "total_net_amount" => $total_net_amount,
            "total_debit_amount" => number_format((float) $total_debit_amount, 2, '.', ''),
            "total_credit_amount" => number_format((float) $total_credit_amount, 2, '.', ''),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);
    }

    function balance_sheet_print()
    {
        $company_id = $this->logged_in_id;

        $account_group_res = $this->crud->getFromSQL("SELECT account_group_id,account_group_name,display_in_balance_sheet FROM account_group WHERE display_in_balance_sheet = 1");

        $bs_account_groups = array();
        $account_group_names = array();
        if(!empty($account_group_res)) {
            foreach ($account_group_res as $key => $account_group_row) {
                $account_group_names[$account_group_row->account_group_id] = $account_group_row->account_group_name;
                $bs_account_groups[] = $account_group_row->account_group_id;
            }
        }

        if(!empty($bs_account_groups)) {
            $account_res = $this->crud->getFromSQL("SELECT account_id,account_name,account_group_id FROM account WHERE created_by='".$company_id."' AND account_group_id IN(".implode(',',$bs_account_groups).")");
        }
        
        $from_date = ($this->session->userdata('balance_sheet_from_date')?$this->session->userdata('balance_sheet_from_date'):get_financial_start_date_by_date());
        $to_date = ($this->session->userdata('balance_sheet_to_date')?$this->session->userdata('balance_sheet_to_date'):date('d-m-Y'));

        $this->session->set_userdata('balance_sheet_from_date',$from_date);
        $this->session->set_userdata('balance_sheet_to_date',$to_date);

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $tmp_from_date = date("Y-m-d",strtotime('-1 day',strtotime($from_date)));
        $total_credit_amount = 0;
        $total_debit_amount = 0;
        $capital = 0;
        $cr_account_group_res = array();
        $dr_account_group_res = array();

        if(!empty($account_res)){
            foreach ($account_res as $account_row){
                $capital = $capital + $this->crud->get_account_balance($account_row->account_id,$tmp_from_date);

                $acc_balance = $this->crud->get_account_balance($account_row->account_id,$to_date);
                if($acc_balance == 0) {
                    continue;
                }

                if($acc_balance >= 0){
                    $total_debit_amount += abs($acc_balance);
                    $account_arr_row = array(
                        "account_id" => $account_row->account_id, 
                        "account_group_id" => $account_row->account_group_id,
                        "particular" => $account_row->account_name,
                        "amount" => number_format((float) abs($acc_balance), 2, '.', '')
                    );
                    $dr_account_arr[] = $account_arr_row;
                    if(isset($dr_account_group_res[$account_row->account_group_id])) {
                        $dr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    } else {
                        $dr_account_group_res[$account_row->account_group_id] = array();
                        $dr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    }
                    
                } else {
                    ;
                    $total_credit_amount += abs($acc_balance);
                    $account_arr_row = array(
                        "account_id" => $account_row->account_id, 
                        "account_group_id" => $account_row->account_group_id,
                        "particular" => $account_row->account_name,
                        "amount" => $acc_balance = number_format((float) abs($acc_balance), 2, '.', '')
                    );
                    $cr_account_arr[] = $account_arr_row;
                    if(isset($cr_account_group_res[$account_row->account_group_id])) {
                        $cr_account_group_res[$account_row->account_group_id][] = $account_arr_row;    
                    } else {
                        $cr_account_group_res[$account_row->account_group_id] = array();
                        $cr_account_group_res[$account_row->account_group_id][] = $account_arr_row;
                    }
                }
            }
        }
        
        $dr_account_arr = array();
        foreach ($dr_account_group_res as $account_group_id => $account_group_row) {
            $dr_account_arr[]  = array(
                'particular' => '<b>'.strtoupper($account_group_names[$account_group_id]).'</b>',
                'amount' => '',
            );
            $group_total = 0;
            foreach ($dr_account_group_res[$account_group_id] as $key => $account_group_tr) {
                $dr_account_arr[]  = $account_group_tr;  
                $group_total += $account_group_tr['amount'];
            }
            $dr_account_arr[]  = array(
                'is_group_total' => 'true',
                'particular' => '',
                'amount' => '<b>'.number_format((float) $group_total, 2, '.', '').'</b>',
            );
            $dr_account_arr[] = array(
                "particular" => '&nbsp;',
                "amount" => '',
            );
        }

        $cr_account_arr = array();
        foreach ($cr_account_group_res as $account_group_id => $account_group_row) {
            $cr_account_arr[]  = array(
                'particular' => '<b>'.strtoupper($account_group_names[$account_group_id]).'</b>',
                'amount' => '',
            );
            $group_total = 0;
            foreach ($cr_account_group_res[$account_group_id] as $key => $account_group_tr) {
                $cr_account_arr[]  = $account_group_tr;
                $group_total += $account_group_tr['amount'];
            }
            $cr_account_arr[]  = array(
                'is_group_total' => 'true',
                'particular' => '',
                'amount' => '<b>'.number_format((float) $group_total, 2, '.', '').'</b>',
            );
            $cr_account_arr[] = array(
                "particular" => '&nbsp;',
                "amount" => '',
            );
        }

        $profit_loss_acc = $this->get_profit_loss_acc_balance($from_date,$to_date);

        if($profit_loss_acc !== 0) {
            if($profit_loss_acc >= 0) {
                $total_debit_amount += abs($profit_loss_acc);
                $dr_account_arr[] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
                $dr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => '<strong>Profit & Loss A/c</strong>',
                    "amount" => abs($profit_loss_acc)
                );
            } else {
                $total_credit_amount += abs($profit_loss_acc);
                $cr_account_arr[] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
                $cr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => '<strong>Profit & Loss A/c</strong>',
                    "amount" => abs($profit_loss_acc)
                );
            }
        }

        if($capital >= 0) {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "particular" => '&nbsp;',
                "amount" => '',
            );
            $cr_account_arr[] = array(
                "particular" => "<strong>CAPITAL</strong>",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        } else {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "particular" => '&nbsp;',
                "amount" => '',
            );
            $cr_account_arr[] = array(
                "particular" => "<strong>CAPITAL</strong>",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        }

        $data = array();

        /*---- Find Net Profit/Loss ----*/
        if($total_debit_amount != $total_credit_amount) {
            if($total_debit_amount > $total_credit_amount) { //Profit

                $total_net_amount = $total_debit_amount;
                $net_profit = $total_debit_amount - $total_credit_amount;
                $cr_account_arr[] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
                $cr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => '<strong>Net Profit</strong>',
                    "amount" => number_format((float) $net_profit, 2, '.', ''),
                );
            } else {

                $total_net_amount = $total_credit_amount;
                $net_loss = $total_credit_amount - $total_debit_amount;
                $dr_account_arr[] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
                $dr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "particular" => '<strong>Net Loss</strong>',
                    "amount" => number_format((float) $net_loss, 2, '.', ''),
                );
            }
        } else {
            $total_net_amount = $total_credit_amount;
        }
        /*---- Find Net Profit/Loss ----*/

        $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');

        $for_loop_limit = (count($dr_account_arr) > count($cr_account_arr) ?count($dr_account_arr):count($cr_account_arr));

        for ($i=0; $i < $for_loop_limit + 5; $i++) { 
            if(!isset($cr_account_arr[$i])) {
                $cr_account_arr[$i] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
            }
            if(!isset($dr_account_arr[$i])) {
                $dr_account_arr[$i] = array(
                    "particular" => '&nbsp;',
                    "amount" => '',
                );
            }
        }
        $output = array(
            "company_row" => $this->crud->get_data_row_by_id('user', 'user_id',$this->logged_in_id),
            "date_range" => 'From '.date('d-m-Y',strtotime($from_date)).' To '.date('d-m-Y',strtotime($to_date)),
            "cr_account_arr" => $cr_account_arr,
            "dr_account_arr" => $dr_account_arr,
            "from_date" => $from_date,
            "to_date" => $to_date,
            "capital" => $capital,
            "total_net_amount" => $total_net_amount,
            "total_debit_amount" => number_format((float) $total_debit_amount, 2, '.', ''),
            "total_credit_amount" => number_format((float) $total_credit_amount, 2, '.', ''),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        $html = $this->load->view('report/balance_sheet_print', $output, true);
        $pdfFilePath = "balance_sheet.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('','', '', '', '',
                      5, // margin_left
                      5, // margin right
                      5, // margin top
                      5, // margin bottom
                      5, // margin header
                      5);
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        //echo $html;
        exit();
    }

    function profit_loss(){
        if($this->applib->have_access_role(MODULE_PROFIT_LOSS_ID,"view")) {
            $data = array();
            $data['from_date'] = ($this->session->userdata('profit_loss_from_date')?$this->session->userdata('profit_loss_from_date'):get_financial_start_date_by_date());
            $data['to_date'] = ($this->session->userdata('profit_loss_to_date')?$this->session->userdata('profit_loss_to_date'):date('d-m-Y'));
            set_page('report/profit_loss',$data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function profit_loss_datatable() {
        $company_id = $this->logged_in_id;

        $account_group_res = $this->crud->getFromSQL("SELECT account_group_id,account_group_name,display_in_balance_sheet FROM account_group WHERE display_in_balance_sheet = 0");
        $pl_account_groups = array();

        if(!empty($account_group_res)) {
            foreach ($account_group_res as $key => $account_group_row) {
                $pl_account_groups[] = $account_group_row->account_group_id;
            }
        }

        $account_res = $this->crud->getFromSQL("SELECT account_id,account_name,account_group_id FROM account WHERE created_by='".$company_id."' AND account_group_id IN(".implode(',',$pl_account_groups).")");

        $from_date = isset($_POST['from_date']) && strtotime($_POST['from_date']) > 0?date("Y-m-d",strtotime($_POST['from_date'])):date("Y-04-01");
        $to_date = isset($_POST['to_date']) && strtotime($_POST['to_date']) > 0?date("Y-m-d",strtotime($_POST['to_date'])):date("Y-m-d");

        $this->session->set_userdata('profit_loss_from_date',$from_date);
        $this->session->set_userdata('profit_loss_to_date',$to_date);

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $from_date = date("Y-m-d",strtotime('-1 day',strtotime($from_date)));
        $total_credit_amount = 0;
        $total_debit_amount = 0;
        $capital = 0;

        if(!empty($account_res)){
            foreach ($account_res as $account_row){

                $capital = $capital + $this->crud->get_account_balance($account_row->account_id,$from_date);

                $acc_balance = $this->crud->get_account_balance($account_row->account_id,$to_date);
                
                if($acc_balance == 0) {
                    continue;
                }

                if($acc_balance >= 0){
                    $total_debit_amount += abs($acc_balance);
                    $dr_account_arr[] = array(
                        "account_id" => $account_row->account_id, 
                        "account_name" => $account_row->account_name, 
                        "amount" => abs($acc_balance)
                    );
                } else {
                    $total_credit_amount += abs($acc_balance);
                    $cr_account_arr[] = array(
                        "account_id" => $account_row->account_id, 
                        "account_name" => $account_row->account_name, 
                        "amount" => abs($acc_balance)
                    );
                }
            }
        }

        if($capital >= 0) {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        } else {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        }

        $data = array();

        /*---- Find Net Profit/Loss ----*/
        if($total_debit_amount != $total_credit_amount) {
            if($total_debit_amount > $total_credit_amount) { //Profit

                $total_net_amount = $total_debit_amount;
                $net_profit = $total_debit_amount - $total_credit_amount;

                $data[] = array(
                    'Net Loss',
                    number_format((float) $net_profit, 2, '.', ''),
                    '',
                    '',
                    ''
                );
            } else {

                $total_net_amount = $total_credit_amount;
                $net_loss = $total_credit_amount - $total_debit_amount;

                $data[] = array(
                    '',
                    '',
                    '',
                    'Net Profit',
                    number_format((float) $net_loss, 2, '.', ''),
                );
            }
        } else {
            $total_net_amount = $total_credit_amount;
        }
        /*---- Find Net Profit/Loss ----*/

        $for_loop_limit = (count($dr_account_arr) > count($cr_account_arr) ?count($dr_account_arr):count($cr_account_arr));

        for ($i=0; $i < $for_loop_limit; $i++) { 
            $row = array();
            
            if(isset($cr_account_arr[$i]['account_name'])) {
                if(isset($cr_account_arr[$i]['account_id'])) {
                    $row[] = '<a href="'.base_url("report/ledger/". $cr_account_arr[$i]['account_id']). '">'.$cr_account_arr[$i]['account_name'].'</a>';
                } else {
                    $row[] = $cr_account_arr[$i]['account_name'];    
                }
                $row[] = number_format((float) $cr_account_arr[$i]['amount'], 2, '.', '');
            } else {
                $row[] = '';
                $row[] = '';
            }
            
            $row[] = '';

            if(isset($dr_account_arr[$i]['account_name'])) {
                if(isset($dr_account_arr[$i]['account_id'])) {
                    $row[] = '<a href="'.base_url("report/ledger/". $dr_account_arr[$i]['account_id']). '">'.$dr_account_arr[$i]['account_name'].'</a>';
                } else {
                    $row[] = $dr_account_arr[$i]['account_name'];    
                }
                $row[] = number_format((float) $dr_account_arr[$i]['amount'], 2, '.', '');
            } else {
                $row[] = '';
                $row[] = '';
            }

            $data[] = $row;
        }

        $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');

        $output = array(
            "draw" => $_POST['draw'],
            "from_date" => $from_date,
            "to_date" => $to_date,
            "capital" => $capital,
            "total_net_amount" => $total_net_amount,
            "total_debit_amount" => number_format((float) $total_debit_amount, 2, '.', ''),
            "total_credit_amount" => number_format((float) $total_credit_amount, 2, '.', ''),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);
    }

    function get_profit_loss_acc_balance($from_date,$to_date) 
    {
        $company_id = $this->logged_in_id;

        $account_group_res = $this->crud->getFromSQL("SELECT account_group_id,account_group_name,display_in_balance_sheet FROM account_group WHERE display_in_balance_sheet = 0");
        $pl_account_groups = array();

        if(!empty($account_group_res)) {
            foreach ($account_group_res as $key => $account_group_row) {
                $pl_account_groups[] = $account_group_row->account_group_id;
            }
        }

        $account_res = $this->crud->getFromSQL("SELECT account_id,account_name,account_group_id FROM account WHERE created_by='".$company_id."' AND account_group_id IN(".implode(',',$pl_account_groups).")");

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $from_date = date("Y-m-d",strtotime('-1 day',strtotime($from_date)));
        $total_credit_amount = 0;
        $total_debit_amount = 0;
        $capital = 0;

        if(!empty($account_res)){
            foreach ($account_res as $account_row){

                $capital = $capital + $this->crud->get_account_balance($account_row->account_id,$from_date);

                $acc_balance = $this->crud->get_account_balance($account_row->account_id,$to_date);
                
                if($acc_balance == 0) {
                    continue;
                }

                if($acc_balance >= 0){
                    $total_debit_amount += abs($acc_balance);
                    $dr_account_arr[] = array(
                        "account_id" => $account_row->account_id, 
                        "account_name" => $account_row->account_name, 
                        "amount" => abs($acc_balance)
                    );
                } else {
                    $total_credit_amount += abs($acc_balance);
                    $cr_account_arr[] = array(
                        "account_id" => $account_row->account_id, 
                        "account_name" => $account_row->account_name, 
                        "amount" => abs($acc_balance)
                    );
                }
            }
        }

        if($capital >= 0) {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        } else {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        }

        $data = array();

        /*---- Find Net Profit/Loss ----*/
        if($total_debit_amount != $total_credit_amount) {
            if($total_debit_amount > $total_credit_amount) { //Profit

                $total_net_amount = $total_debit_amount;
                $net_profit = $total_debit_amount - $total_credit_amount;
                return $net_profit;
            } else {

                $total_net_amount = $total_credit_amount;
                $net_loss = $total_credit_amount - $total_debit_amount;
                return ($net_loss * -1);
            }
        } else {
            return 0;
        }
    }
    
    function trial_balance(){
        if($this->applib->have_access_role(MODULE_TRIAL_BALANCE_ID,"view")) {
            $data = array();
            $data['from_date'] = ($this->session->userdata('trial_balance_from_date')?$this->session->userdata('trial_balance_from_date'):get_financial_start_date_by_date());
            $data['to_date'] = ($this->session->userdata('trial_balance_to_date')?$this->session->userdata('trial_balance_to_date'):date('d-m-Y'));
            set_page('report/trial_balance',$data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    
    function trial_balance_datatable() {
        $company_id = $this->logged_in_id;
        $account_res = $this->crud->getFromSQL("SELECT account_id,account_name,account_group_id FROM account WHERE created_by='".$company_id."'");

        $from_date = isset($_POST['from_date']) && strtotime($_POST['from_date']) > 0?date("Y-m-d",strtotime($_POST['from_date'])):date("Y-04-01");
        $to_date = isset($_POST['to_date']) && strtotime($_POST['to_date']) > 0?date("Y-m-d",strtotime($_POST['to_date'])):date("Y-m-d");
        $site_id = isset( $_POST['site_id'] ) ? $_POST['site_id'] : "";
        $this->session->set_userdata('trial_balance_from_date',$from_date);
        $this->session->set_userdata('trial_balance_to_date',$to_date);

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $from_date = date("Y-m-d",strtotime('-1 day',strtotime($from_date)));
        $total_credit_amount = 0;
        $total_debit_amount = 0;
        $capital = 0;
        $profit_loss_acc = 0;

        if(!empty($account_res)){
            foreach ($account_res as $account_row){

                $capital = $capital + $this->crud->get_account_balance($account_row->account_id,$from_date,$site_id);

                $acc_balance = $this->crud->get_account_balance($account_row->account_id,$to_date,$site_id);                
                if($acc_balance == 0) {
                    continue;
                }
                if($acc_balance >= 0){
                    $total_debit_amount += abs($acc_balance);
                    $dr_account_arr[] = array(
                        "account_id" => $account_row->account_id, 
                        "account_name" => '<a href="'.base_url("report/ledger/". $account_row->account_id). '">'.$account_row->account_name.'</a>',
                        "amount" => abs($acc_balance)
                    );
                } else {
                    $total_credit_amount += abs($acc_balance);
                    $cr_account_arr[] = array(
                        "account_id" => $account_row->account_id, 
                        "account_name" => '<a href="'.base_url("report/ledger/". $account_row->account_id). '">'.$account_row->account_name.'</a>',
                        "amount" => abs($acc_balance)
                    );
                }
            }
        }

        if($profit_loss_acc !== 0) {
            if($profit_loss_acc >= 0) {
                $total_debit_amount += abs($profit_loss_acc);
                $dr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "account_name" => '<a href="'.base_url("report/profit_loss"). '">Profit & Loss</a>',
                    "amount" => abs($profit_loss_acc)
                );
            } else {
                $total_credit_amount += abs($profit_loss_acc);
                $cr_account_arr[] = array(
                    "account_id" => 'ProfitLoss', 
                    "account_name" => '<a href="'.base_url("report/profit_loss"). '">Profit & Loss</a>',
                    "amount" => abs($profit_loss_acc)
                );
            }
        }

        if($capital >= 0) {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        } else {
            $total_credit_amount += $capital;
            $cr_account_arr[] = array(
                "account_name" => "CAPITAL",
                "amount" => number_format((float) $capital, 2, '.', '')
            );
        }

        $data = array();

        /*---- Find Net Profit/Loss ----*/
        if($total_debit_amount != $total_credit_amount) {
            if($total_debit_amount > $total_credit_amount) { //Profit

                $total_net_amount = $total_debit_amount;
                $net_profit = $total_debit_amount - $total_credit_amount;

                $data[] = array(
                    'Net Profit',
                    number_format((float) $net_profit, 2, '.', ''),
                    '',
                    '',
                    ''
                );
            } else {

                $total_net_amount = $total_credit_amount;
                $net_loss = $total_credit_amount - $total_debit_amount;

                $data[] = array(
                    '',
                    '',
                    '',
                    'Net Loss',
                    number_format((float) $net_loss, 2, '.', ''),
                );
            }
        } else {
            $total_net_amount = $total_credit_amount;
        }
        /*---- Find Net Profit/Loss ----*/

        $for_loop_limit = (count($dr_account_arr) > count($cr_account_arr) ?count($dr_account_arr):count($cr_account_arr));

        for ($i=0; $i < $for_loop_limit; $i++) { 
            $row = array();
            
            if(isset($cr_account_arr[$i]['account_name'])) {
                $row[] = $cr_account_arr[$i]['account_name'];
                $row[] = number_format((float) $cr_account_arr[$i]['amount'], 2, '.', '');
            } else {
                $row[] = '';
                $row[] = '';
            }
            
            $row[] = '';

            if(isset($dr_account_arr[$i]['account_name'])) {
                $row[] = $dr_account_arr[$i]['account_name'];
                $row[] = number_format((float) $dr_account_arr[$i]['amount'], 2, '.', '');
            } else {
                $row[] = '';
                $row[] = '';
            }

            $data[] = $row;
        }

        if($total_net_amount > 0) {
            $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');
        }

        $output = array(
            "draw" => $_POST['draw'],
            "from_date" => $from_date,
            "to_date" => $to_date,
            "capital" => $capital,
            "total_net_amount" => $total_net_amount,
            "total_debit_amount" => number_format((float) $total_debit_amount, 2, '.', ''),
            "total_credit_amount" => number_format((float) $total_credit_amount, 2, '.', ''),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);
    }    

    function stock_status_report(){
        $data = array();
        set_page('report/stock_status_report',$data);
    }
    
    function stock_status_datatable(){
        $data = array();
        $upto_date_sql = '';
        if(!empty($_POST['upto_date'])){
            $upto_date_sql = " AND st_change_date <= '".date('Y-m-d',strtotime($_POST['upto_date']))."' ";
        }
        $inc = 1;
        $items = $this->crud->getFromSQL("SELECT * FROM item WHERE created_by = ".$this->logged_in_id." ");
        if(!empty($items)){
            foreach ($items as $item){
                $in_stock_qty = 0;
                $in_wip_qty = 0;
                $in_work_done_qty = 0;
                $sale_total_qty = 0;
                $item_minimum_stock = 0;

                $st_data = $this->crud->getFromSQL(" SELECT * FROM stock_status_change WHERE item_id = ".$item->item_id." ".$upto_date_sql." ");
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
//                $temp_in_work_done_qty = $in_work_done_qty - $sale_total_qty;
//                if($temp_in_work_done_qty < 0){
//                    $in_work_done_qty = 0;
//                    $after_work_done_qty = abs($temp_in_work_done_qty);
//                    $temp_wip_qty = $in_wip_qty - $after_work_done_qty;
//                    if($temp_wip_qty < 0){
//                        $in_wip_qty = 0;
//                        $in_stock_qty = $in_stock_qty - abs($temp_wip_qty);
//                    }
//                } else {
//                    $in_work_done_qty = $temp_in_work_done_qty;
//                }
//                echo "<pre>"; print_r($ss1_data); die();
                $row = array();
                $row[] = $inc;
                $row[] = $item->item_name;
                $row[] = $in_stock_qty;
                $row[] = $in_wip_qty;
                $row[] = $in_work_done_qty;
                $data[] = $row;
                $inc++;
            }
        }
        
        $output = array(
            "draw" => '',
            "recordsTotal" => '',
            "recordsFiltered" => '',
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    function pending_bills_report(){
        $data = array();
        set_page('report/pending_bills_report',$data);
    }
    
    function pending_bills_datatable() {
        $data = array();
        $due_date = date('Y-m-d', strtotime($_POST['due_date']));
        $account_id = $_POST['account_id'];
        $account_id_sql = " s.account_id IN (SELECT  account_id FROM account WHERE created_by = ".$this->logged_in_id." AND is_bill_wise = 1) ";
        if(!empty($account_id)){
            $account_id_sql = " s.account_id = ".$account_id." ";
        }
        $sale_data = $this->crud->getFromSQL(" SELECT s.sales_invoice_id,s.prefix,s.sales_invoice_no,s.bill_no,s.account_id,s.sales_invoice_date,s.amount_total,SUM(paid_amount) as paid_amount,pre.prefix_name FROM sales_invoice as s LEFT JOIN invoice_paid_details as p ON p.invoice_id = s.sales_invoice_id LEFT JOIN company_invoice_prefix as pre ON pre.id = s.prefix WHERE ".$account_id_sql." GROUP BY s.sales_invoice_id ORDER BY s.account_id ASC, s.sales_invoice_date ASC ");
//        echo $this->db->last_query(); exit;
//        echo "<pre>"; print_r($sale_data); exit;
        $s_data = array();
        if(!empty($sale_data)){
            $last_acc_id = $sale_data[0]->account_id;
            $acc_details = $this->crud->getFromSQL(" SELECT account_name,is_bill_wise FROM account WHERE account_id = ".$last_acc_id." ");
            $in_arr = array();
            $in_arr['acc_name'] = $acc_details[0]->account_name;
            $s_data[] = $in_arr;
            if(!empty($acc_details[0]->is_bill_wise)){
                $is_bill_wise = 1;
            } else {
                $is_bill_wise = 0;
            }
            $balance_amt = 0;
            $total_balance_amt = 0;
//            echo "<pre>"; print_r($s_data); exit;
            foreach ($sale_data as $key => $sale){
                if($last_acc_id != $sale->account_id){
                    $acc_details = $this->crud->getFromSQL(" SELECT account_name,is_bill_wise FROM account WHERE account_id = ".$sale->account_id." ");
                    $in_arr = array();
                    $in_arr['acc_name'] = $acc_details[0]->account_name;
                    $s_data[] = $in_arr;
                    if(!empty($acc_details[0]->is_bill_wise)){
                        $is_bill_wise = 1;
                    } else {
                        $is_bill_wise = 0;
                    }
                    $balance_amt = 0;
                }
//                if($is_bill_wise == 1){
                    $in_arr = array();
                    $in_arr['date'] = $sale->sales_invoice_date;
                    $in_arr['bill_no'] = $sale->prefix_name.'/'.$sale->sales_invoice_no;
                    $in_arr['type'] = 'Sale';
                    $diff = date_diff(date_create($sale->sales_invoice_date), date_create($due_date));
                    $day = $diff->format("%a");
                    $in_arr['due_days'] = $day;
                    $in_arr['bill_amount'] = number_format($sale->amount_total, 0, '.', '');
                    $in_arr['received_amount'] = number_format($sale->paid_amount, 0, '.', '');
                    $in_arr['pending_amount'] = $in_arr['bill_amount'] - $in_arr['received_amount'];
                    $balance_amt = $balance_amt + $in_arr['bill_amount'] - $in_arr['received_amount'];
                    $in_arr['balance_amt'] = $balance_amt;
                    $total_balance_amt = $total_balance_amt + $in_arr['pending_amount'];
                    $s_data[] = $in_arr;
//                }
            }
        }
//        echo "<pre>"; print_r($s_data); exit;
//        $is_kasar_account = $this->crud->get_val_by_id('account',$account_id,'account_id','is_kasar_account');
        if(!empty($s_data)){
            foreach ($s_data as $st){
                if(isset($st['acc_name'])){
                    $row = array();
                    $row[] = '&nbsp;';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $data[] = $row;
                    
                    $row = array();
                    $row[] = "<b style='color:red;'>".$st['acc_name']."</b>";
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $data[] = $row;
                } else {
                    $row = array();
                    $row[] = isset($st['date']) ? date('d-m-Y',strtotime($st['date'])) : '';
                    $row[] = isset($st['bill_no']) ? $st['bill_no'] : '';
                    $row[] = isset($st['type']) ? $st['type'] : '';
                    $row[] = isset($st['due_days']) ? $st['due_days'] : '';
                    $row[] = isset($st['bill_amount']) ? number_format($st['bill_amount'], 2, '.', '') : '';
                    $row[] = isset($st['received_amount']) ? number_format($st['received_amount'], 2, '.', '') : '';
                    $row[] = isset($st['pending_amount']) ? number_format($st['pending_amount'], 2, '.', '') : '';
                    $row[] = isset($st['balance_amt']) ? number_format($st['balance_amt'], 2, '.', '') : '';
                    $data[] = $row;
                }
            }
            $row = array();
            $row[] = '&nbsp;';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $data[] = $row;
            
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '<b>Grand Total</b>';
            $row[] = "<b>".number_format($total_balance_amt, 2, '.', '')."</b>";
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        exit;
    }

    function site_report(){
        $data = array();
        $data['module_option']=[
            '--All--',
            'Purchase Invoice',
            'Sales Invoice',
            'Credit Note',
            'Debit Note',
            'Sales Quotation',
            'Purchse Quotation',
            'Dispatch',
            'Material In'
        ];
        set_page('report/site_report',$data);
    }

    public function site_report_datatable()
    {
        
    }
    
}
