<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Backup
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Gstr1_excel extends CI_Controller
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

    function index(){
        if($this->applib->have_access_role(MODULE_GSTR1_EXCEL_EXPORT_ID,"view")) {
            $data = array();
            set_page('gstr1_excel_export', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function get_pending_locked_dates(){
        //echo '<pre>';print_r($_POST); exit;
        
        $from_input = $_POST['daterange'];
        if(isset($from_input) && !empty($from_input)){
            $from_input = explode(' ', $from_input);
        }
        
        $from_date_sql = date('Y-m-d', strtotime(trim($from_input[0])));
        $sql="SELECT `sales_invoice_date` AS dates FROM `sales_invoice` WHERE `data_lock_unlock` = 0 AND `sales_invoice_date` <= '$from_date_sql'
        UNION ALL SELECT `purchase_invoice_date` FROM `purchase_invoice` WHERE `data_lock_unlock` = 0  AND `purchase_invoice_date` <= '$from_date_sql'
        UNION ALL SELECT `credit_note_date` FROM `credit_note` WHERE `data_lock_unlock` = 0  AND `credit_note_date` <= '$from_date_sql'
        UNION ALL SELECT `debit_note_date` FROM `debit_note` WHERE `data_lock_unlock` = 0  AND `debit_note_date` <= '$from_date_sql'";
        
        $result_sql = $this->crud->getFromSQL($sql);
        $newArray = array();
        foreach($result_sql as $key => $value) {
            foreach($value as $key2 => $value2) {
                $newArray[$key] = date('d-m-Y', strtotime(trim($value2)));;
            }
        }
        $newArray_dates = array_unique($newArray);
        
        $dateranges_sql = $this->crud->getFromSQL('SELECT `daterange` FROM `locked_daterange` WHERE `user_id`="'.$this->logged_in_id.'" ');
        $db_dates = array();
        foreach($dateranges_sql as $daterange){
            $pieces = explode(" ", $daterange->daterange);
            $from = date('d-m-Y', strtotime(trim($pieces[0])));
            $to = date('d-m-Y', strtotime(trim($pieces[2])));
            $begin = new DateTime($from);
            $end = new DateTime($to);
            $db_dates[] = $from;
            $db_dates[] = $to;
            $daterange_dates = new DatePeriod($begin, new DateInterval('P1D'), $end);
            foreach($daterange_dates as $daterange_date){
                $db_dates[] = $daterange_date->format("d-m-Y");
            }
        }
        /*$begin = new DateTime(SOFTWARE_START_YEAR.'-01-01');
        $end = new DateTime($from_input[0]);
        $daterange_dates = new DatePeriod($begin, new DateInterval('P1D'), $end);
        $all_dates = array();
        foreach($daterange_dates as $date){
            $all_dates[] = $date->format("d-m-Y");
        }*/
        $result['dates'] = array_unique(array_diff($newArray,$db_dates));
        print json_encode($result);
        exit;
    }

    function save_lock_unlock(){
        $post_data = $this->input->post();
        //~ echo '<pre>';print_r($post_data); exit;

        $daterange = $post_data['daterange'];
        $from_date = '';
        $to_date = '';
		
        if(isset($daterange) && !empty($daterange)){
            $daterange = explode(' - ', $daterange);
            $from_date = trim($daterange[0]);
            $from_date = substr($from_date, 6, 4).'-'.substr($from_date, 3, 2).'-'.substr($from_date, 0, 2);
            $to_date = trim($daterange[1]);
            $to_date = substr($to_date, 6, 4).'-'.substr($to_date, 3, 2).'-'.substr($to_date, 0, 2);
        }


        $data_lock_unlock = 0;
        if($post_data['data_lock_unlock'] == 'Lock'){
            $data_lock_unlock = 1;
        }
        if(!empty($from_date) && !empty($to_date)){
            $result = $this->crud->update('sales_invoice', array('data_lock_unlock' => $data_lock_unlock),
                                          array('sales_invoice_date >=' => $from_date, 'sales_invoice_date <=' => $to_date));
            $result = $this->crud->update('purchase_invoice', array('data_lock_unlock' => $data_lock_unlock),
                                          array('purchase_invoice_date >=' => $from_date, 'purchase_invoice_date <=' => $to_date));
            $result = $this->crud->update('credit_note', array('data_lock_unlock' => $data_lock_unlock),
                                          array('credit_note_date >=' => $from_date, 'credit_note_date <=' => $to_date));
            $result = $this->crud->update('debit_note', array('data_lock_unlock' => $data_lock_unlock),
                                          array('debit_note_date >=' => $from_date, 'debit_note_date <=' => $to_date));
            $locked_daterange = array();
            $locked_daterange['daterange'] = $post_data['daterange'];
            $locked_daterange['user_id'] = $this->logged_in_id;
            $locked_daterange['created_at'] = $this->now_time;
            $locked_daterange['created_by'] = $this->logged_in_id;
            $locked_daterange['user_created_by'] = $this->session->userdata()['login_user_id'];
            $locked_daterange['updated_at'] = $this->now_time;
            $locked_daterange['updated_by'] = $this->logged_in_id;
            $locked_daterange['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $this->crud->insert('locked_daterange', $locked_daterange);
            if($result){
                $return['success'] = "Updated";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Data '. $post_data['data_lock_unlock'] .' Successfully');
            }
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function locked_daterange_datatable(){
        $config['table'] = 'locked_daterange ld';
        $config['select'] = 'ld.id, ld.daterange, u.user_name';
        $config['joins'][] = array('join_table' => 'user u', 'join_by' => 'u.user_id = ld.user_id', 'join_type' => 'left');
        $config['column_order'] = array('ld.id', 'u.user_name', 'ld.daterange');
        $config['column_search'] = array('u.user_name', 'ld.daterange');
        $config['order'] = array('ld.id' => 'DESC');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        foreach ($list as $daterange) {
            $row = array();
            $action = '';
            $action .= '<a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('gstr1_excel/delete_locked_daterange/' . $daterange->id) . '"><i class="fa fa-trash"></i></a>';
            $row[] = $action;
            $row[] = $daterange->user_name;
            $row[] = $daterange->daterange;
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

    function delete_locked_daterange($id){
        $daterange = $this->crud->get_id_by_val('locked_daterange', 'daterange', 'id', $id);
        $from_date = '';
        $to_date = '';
        if(isset($daterange) && !empty($daterange)){
            $daterange = explode(' - ', $daterange);
            $from_date = trim($daterange[0]);
            $from_date = substr($from_date, 6, 4).'-'.substr($from_date, 3, 2).'-'.substr($from_date, 0, 2);
            $to_date = trim($daterange[1]);
            $to_date = substr($to_date, 6, 4).'-'.substr($to_date, 3, 2).'-'.substr($to_date, 0, 2);
        }
        $data_lock_unlock = 0;
        if(!empty($from_date) && !empty($to_date)){
            $result = $this->crud->update('sales_invoice', array('data_lock_unlock' => $data_lock_unlock), 
                                          array('sales_invoice_date >=' => $from_date, 'sales_invoice_date <=' => $to_date));
            $result = $this->crud->update('purchase_invoice', array('data_lock_unlock' => $data_lock_unlock), 
                                          array('purchase_invoice_date >=' => $from_date, 'purchase_invoice_date <=' => $to_date));
            $result = $this->crud->update('credit_note', array('data_lock_unlock' => $data_lock_unlock), 
                                          array('credit_note_date >=' => $from_date, 'credit_note_date <=' => $to_date));
            $result = $this->crud->update('debit_note', array('data_lock_unlock' => $data_lock_unlock), 
                                          array('debit_note_date >=' => $from_date, 'debit_note_date <=' => $to_date));
            $this->crud->delete('locked_daterange', array('id' => $id));
            $this->session->set_flashdata('success',true);
            $this->session->set_flashdata('message','Deleted Successfully');
        }
    }

    function b2b_export(){

        $filename = "b2b.csv";
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename='.$filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $grand_total = 0;
        $where = array();
        $result = $this->crud->get_row_by_id('sales_invoice', $where);
        $count = count($result);
        foreach ($result as $row) {
            $grand_total += $row->amount_total;
        }

        $output_mainhead = array('Summary For B2B(4)', '', '', '', '', '', '', '', '', '', 'HELP');
        fputcsv($handle, $output_mainhead);
        $output_mainhead = array('No. of Recipients', 'No. of Invoices', '', 'Total Invoice Value', '', '', '', '', '', 'Total Taxable Value', 'Total Cess');
        fputcsv($handle, $output_mainhead);
        $output_mainhead_val = array('1', $count, '', $grand_total, '', '', '', '', '', $grand_total, '');
        fputcsv($handle, $output_mainhead_val);
        $output_head = array('GSTIN/UIN of Recipient', 'Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Reverse Charge', 'Invoice Type', 'E-Commerce GSTIN', 'Rate', 'Taxable Value', 'Cess Amount');
        fputcsv($handle, $output_head);

        foreach ($result as $row) {
            $output_fields = array();
            $output_fields[] = '';
            $output_fields[] = $row->sales_invoice_no;
            $output_fields[] = date('d-m-Y', strtotime($row->sales_invoice_date));
            $output_fields[] = $row->amount_total;
            $output_fields[] = '';
            $output_fields[] = '';
            $output_fields[] = '';
            $output_fields[] = '';
            $output_fields[] = '';
            $output_fields[] = '';
            $output_fields[] = '';
            fputcsv($handle, $output_fields);
        }
        fclose($handle);
        exit;
    }

    function b2cl_export(){
        $wheres = array();
        $output = '';

        $filename = "b2cl.csv";
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename='.$filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $output_mainhead = array('Summary For B2CL(5)', '', '', '', '', '', '', 'HELP');
        fputcsv($handle, $output_mainhead);
        $output_mainhead = array('No. of Invoices', '', 'Total Invoice Value', '', '', 'Total Taxable Value', 'Total Cess', '');
        fputcsv($handle, $output_mainhead);
        $output_mainhead_val = array('5', '', '1123123', '', '', '1234123', '123123', '');
        fputcsv($handle, $output_mainhead_val);
        $output_head = array('Invoice Number', 'Invoice date', 'Invoice Value', 'Place Of Supply', 'Rate', 'Taxable Value', 'Cess Amount', 'E-Commerce GSTIN');
        fputcsv($handle, $output_head);
        fclose($handle);
        exit;
    }

    function gstr1_excel_export(){

        $daterange = implode('/', func_get_args());
        $daterange = str_replace('%20', ' ', $daterange);
        $from_date = '';
        $to_date = '';
        if(isset($daterange) && !empty($daterange)){
            $daterange = explode(' - ', $daterange);
            $from_date = trim($daterange[0]);
            $from_date = substr($from_date, 6, 4).'-'.substr($from_date, 3, 2).'-'.substr($from_date, 0, 2);
            $to_date = trim($daterange[1]);
            $to_date = substr($to_date, 6, 4).'-'.substr($to_date, 3, 2).'-'.substr($to_date, 0, 2);
        }

        /* START : Read Excel - Write Data - Download Excel */

        $this->load->library('excel');
        $fileType = 'Excel5';
        $fileName = FCPATH.'/uploads/GSTR1_Excel_Workbook_Template-V1.2_demo.xls';

        // Read the file
        $objPHPExcel = PHPExcel_IOFactory::load($fileName);

        $created_by = $this->logged_in_id;
        $company_state_id = $this->crud->get_id_by_val('user', 'state', 'user_id', $created_by);
        //~ $sales_invoice_result = $this->crud->get_sales_invoice($created_by);
        $invoice_lineitem_result = $this->crud->get_sales_invoice_lineitems($created_by, $from_date, $to_date);
        $credit_lineitem_result = $this->crud->get_credit_note_lineitems($created_by, $from_date, $to_date);
        $debit_lineitem_result = $this->crud->get_debit_note_lineitems($created_by, $from_date, $to_date);
        //~ echo '<pre>'; print_r($invoice_lineitem_result); exit;

        // Change in B2B WorkSheet
        $total_invoice_value = 0;
        $total_taxable_value = 0;
        $recipients_arr = array();
        $num_of_recipients = 0;
        $invoice_no_arr = array();
        $num_of_invoice = 0;
        $row_inc = 5;
        foreach ($invoice_lineitem_result as $row) {

            $gst_pre = $row->cgst + $row->sgst + $row->igst;
            if(!empty(trim($row->account_gst_no)) && $gst_pre != 0){
                if(in_array($row->account_id, $recipients_arr)){ } else {
                    $num_of_recipients ++;
                    $recipients_arr[] = $row->account_id;
                }
                if(in_array($row->sales_invoice_no, $invoice_no_arr)){ } else {
                    $num_of_invoice ++;
                    $invoice_no_arr[] = $row->sales_invoice_no;
                }

                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A'.$row_inc, $row->account_gst_no)
                    ->setCellValue('B'.$row_inc, $row->sales_invoice_no)
                    ->setCellValue('C'.$row_inc, date('d M Y', strtotime($row->sales_invoice_date)))
                    ->setCellValue('D'.$row_inc, $row->amount)
                    ->setCellValue('E'.$row_inc, $row->state_name)
                    ->setCellValue('F'.$row_inc, 'N')
                    ->setCellValue('G'.$row_inc, 'Regular')
                    ->setCellValue('H'.$row_inc, '')
                    ->setCellValue('I'.$row_inc, $gst_pre)
                    ->setCellValue('J'.$row_inc, $row->discounted_price)
                    ->setCellValue('K'.$row_inc, '');
                $total_invoice_value += $row->amount;
                $total_taxable_value += $row->discounted_price;
                $row_inc++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A3', $num_of_recipients)
            ->setCellValue('B3', $num_of_invoice)
            ->setCellValue('D3', $total_invoice_value)
            ->setCellValue('J3', $total_taxable_value);
        //---------------------------------------

        // Change in B2CL WorkSheet
        $total_invoice_value = 0;
        $total_taxable_value = 0;
        $invoice_no_arr = array();
        $num_of_invoice = 0;
        $row_inc = 5;
        foreach ($invoice_lineitem_result as $row) {

            $total_discounted_price = $row->pure_amount_total - $row->discount_total;
            $gst_pre = $row->cgst + $row->sgst + $row->igst;
            if(empty(trim($row->account_gst_no)) && $gst_pre != 0){
                if($row->state_id != $company_state_id && $total_discounted_price > 250000){
                    if(in_array($row->sales_invoice_no, $invoice_no_arr)){ } else {
                        $num_of_invoice ++;
                        $invoice_no_arr[] = $row->sales_invoice_no;
                    }
                    $objPHPExcel->setActiveSheetIndex(2)
                        ->setCellValue('A'.$row_inc, $row->sales_invoice_no)
                        ->setCellValue('B'.$row_inc, date('d M Y', strtotime($row->sales_invoice_date)))
                        ->setCellValue('C'.$row_inc, $row->amount)
                        ->setCellValue('D'.$row_inc, $row->state_name)
                        ->setCellValue('E'.$row_inc, $gst_pre)
                        ->setCellValue('F'.$row_inc, $row->discounted_price)
                        ->setCellValue('G'.$row_inc, '')
                        ->setCellValue('H'.$row_inc, '');
                    $total_invoice_value += $row->amount;
                    $total_taxable_value += $row->discounted_price;
                    $row_inc++;
                }
            }
        }
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A3', $num_of_invoice)
            ->setCellValue('C3', $total_invoice_value)
            ->setCellValue('F3', $total_taxable_value);
        //---------------------------------------

        // Change in B2CS WorkSheet
        $total_taxable_value = 0;
        $unique_arr = array();
        $unique_key = 0;
        $b2cs_result = array();
        foreach ($invoice_lineitem_result as $row) {
            $total_discounted_price = $row->pure_amount_total - $row->discount_total;
            $gst_pre = $row->cgst + $row->sgst + $row->igst;
            if(empty(trim($row->account_gst_no)) && $gst_pre != 0){
                if($row->state_id == $company_state_id || ( $row->state_id != $company_state_id && $total_discounted_price < 250000)){
                    $unique_value = $row->state_name . '_' . $gst_pre;
                    if (in_array($unique_value, $unique_arr)){ 
                        $key = array_search ($unique_value, $unique_arr);
                        $b2cs_result[$key]['discounted_price'] += $row->discounted_price;
                    } else {
                        $unique_arr[$unique_key] = $unique_value;
                        $b2cs_result[$unique_key]['state_name'] = $row->state_name;
                        $b2cs_result[$unique_key]['gst_pre'] = $gst_pre;
                        $b2cs_result[$unique_key]['discounted_price'] = $row->discounted_price;
                        $unique_key++;
                    }
                    $total_taxable_value += $row->discounted_price;
                }
            }
        }
        $row_inc = 5;
        foreach($unique_arr as $key => $unique_row){
            $objPHPExcel->setActiveSheetIndex(3)
                ->setCellValue('A'.$row_inc, 'OE')
                ->setCellValue('B'.$row_inc, $b2cs_result[$key]['state_name'])
                ->setCellValue('C'.$row_inc, $b2cs_result[$key]['gst_pre'])
                ->setCellValue('D'.$row_inc, $b2cs_result[$key]['discounted_price'])
                ->setCellValue('E'.$row_inc, '')
                ->setCellValue('F'.$row_inc, '');
            $row_inc++;
        }
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('D3', $total_taxable_value);
        //---------------------------------------

        // Change in CDNR WorkSheet
        $total_amount = 0;
        $total_taxable_value = 0;
        $recipients_arr = array();	
        $num_of_recipients = 0;
        $credit_no_arr = array();	
        $num_of_credit = 0;
        $bill_no_arr = array();	
        $num_of_bill = 0;
        $debit_no_arr = array();	
        $num_of_debit = 0;
        $row_inc = 5;
        foreach ($credit_lineitem_result as $row) {

            if(!empty(trim($row->account_gst_no))){

                if(in_array($row->account_id, $recipients_arr)){ } else {
                    $num_of_recipients ++;
                    $recipients_arr[] = $row->account_id;
                }
                if(in_array($row->credit_note_no, $credit_no_arr)){ } else {
                    $num_of_credit ++;
                    $credit_no_arr[] = $row->credit_note_no;
                }
                if(in_array($row->bill_no, $bill_no_arr)){ } else {
                    $num_of_bill ++;
                    $bill_no_arr[] = $row->bill_no;
                }

                $gst_pre = $row->cgst + $row->sgst + $row->igst;
                $invoice_date = '';
                if(strtotime($row->invoice_date) != 0) { $invoice_date = date('d M Y', strtotime($row->invoice_date)); }
                $credit_note_date = '';
                if(strtotime($row->credit_note_date) != 0) { $credit_note_date = date('d M Y', strtotime($row->credit_note_date)); }
                $objPHPExcel->setActiveSheetIndex(4)
                    ->setCellValue('A'.$row_inc, $row->account_gst_no)
                    ->setCellValue('B'.$row_inc, $row->bill_no)
                    ->setCellValue('C'.$row_inc, $invoice_date)
                    ->setCellValue('D'.$row_inc, $row->credit_note_no)
                    ->setCellValue('E'.$row_inc, $credit_note_date)
                    ->setCellValue('F'.$row_inc, 'C')
                    ->setCellValue('G'.$row_inc, '')
                    ->setCellValue('H'.$row_inc, $row->state_name)
                    ->setCellValue('I'.$row_inc, $row->amount)
                    ->setCellValue('J'.$row_inc, $gst_pre)
                    ->setCellValue('K'.$row_inc, $row->discounted_price)
                    ->setCellValue('L'.$row_inc, '')
                    ->setCellValue('M'.$row_inc, 'N');
                $total_amount += $row->amount;
                $total_taxable_value += $row->discounted_price;
                $row_inc++;
            }
        }
        foreach ($debit_lineitem_result as $row) {

            if(!empty(trim($row->account_gst_no))){

                if(in_array($row->account_id, $recipients_arr)){ } else {
                    $num_of_recipients ++;
                    $recipients_arr[] = $row->account_id;
                }
                if(in_array($row->debit_note_no, $debit_no_arr)){ } else {
                    $num_of_debit ++;
                    $debit_no_arr[] = $row->debit_note_no;
                }
                if(in_array($row->bill_no, $bill_no_arr)){ } else {
                    $num_of_bill ++;
                    $bill_no_arr[] = $row->bill_no;
                }

                $gst_pre = $row->cgst + $row->sgst + $row->igst;
                $invoice_date = '';
                if(strtotime($row->invoice_date) != 0) { $invoice_date = date('d M Y', strtotime($row->invoice_date)); }
                $debit_note_date = '';
                if(strtotime($row->debit_note_date) != 0) { $debit_note_date = date('d M Y', strtotime($row->debit_note_date)); }
                $objPHPExcel->setActiveSheetIndex(4)
                    ->setCellValue('A'.$row_inc, $row->account_gst_no)
                    ->setCellValue('B'.$row_inc, $row->bill_no)
                    ->setCellValue('C'.$row_inc, $invoice_date)
                    ->setCellValue('D'.$row_inc, $row->debit_note_no)
                    ->setCellValue('E'.$row_inc, $debit_note_date)
                    ->setCellValue('F'.$row_inc, 'D')
                    ->setCellValue('G'.$row_inc, '')
                    ->setCellValue('H'.$row_inc, $row->state_name)
                    ->setCellValue('I'.$row_inc, $row->amount)
                    ->setCellValue('J'.$row_inc, $gst_pre)
                    ->setCellValue('K'.$row_inc, $row->discounted_price)
                    ->setCellValue('L'.$row_inc, '')
                    ->setCellValue('M'.$row_inc, 'N');
                $total_amount += $row->amount;
                $total_taxable_value += $row->discounted_price;
                $row_inc++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(4)
            ->setCellValue('A3', $num_of_recipients)
            ->setCellValue('B3', $num_of_bill)
            ->setCellValue('D3', $num_of_credit + $num_of_debit)
            ->setCellValue('I3', $total_amount)
            ->setCellValue('K3', $total_taxable_value);
        //---------------------------------------

        // Change in CDNUR WorkSheet
        $total_amount = 0;
        $total_taxable_value = 0;
        $credit_no_arr = array();	
        $num_of_credit = 0;
        $bill_no_arr = array();	
        $num_of_bill = 0;
        $debit_no_arr = array();	
        $num_of_debit = 0;
        $row_inc = 5;
        foreach ($credit_lineitem_result as $row) {

            if(empty(trim($row->account_gst_no))){

                if(in_array($row->credit_note_no, $credit_no_arr)){ } else {
                    $num_of_credit ++;
                    $credit_no_arr[] = $row->credit_note_no;
                }
                if(in_array($row->bill_no, $bill_no_arr)){ } else {
                    $num_of_bill ++;
                    $bill_no_arr[] = $row->bill_no;
                }

                $gst_pre = $row->cgst + $row->sgst + $row->igst;
                $invoice_date = '';
                if(strtotime($row->invoice_date) != 0) { $invoice_date = date('d M Y', strtotime($row->invoice_date)); }
                $credit_note_date = '';
                if(strtotime($row->credit_note_date) != 0) { $credit_note_date = date('d M Y', strtotime($row->credit_note_date)); }
                $objPHPExcel->setActiveSheetIndex(5)
                    ->setCellValue('A'.$row_inc, 'B2CL')
                    ->setCellValue('B'.$row_inc, $row->credit_note_no)
                    ->setCellValue('C'.$row_inc, $credit_note_date)
                    ->setCellValue('D'.$row_inc, 'C')
                    ->setCellValue('E'.$row_inc, $row->bill_no)
                    ->setCellValue('F'.$row_inc, $invoice_date)
                    ->setCellValue('G'.$row_inc, '')
                    ->setCellValue('H'.$row_inc, $row->state_name)
                    ->setCellValue('I'.$row_inc, $row->amount)
                    ->setCellValue('J'.$row_inc, $gst_pre)
                    ->setCellValue('K'.$row_inc, $row->discounted_price)
                    ->setCellValue('L'.$row_inc, '')
                    ->setCellValue('M'.$row_inc, 'N');
                $total_amount += $row->amount;
                $total_taxable_value += $row->discounted_price;
                $row_inc++;
            }
        }
        foreach ($debit_lineitem_result as $row) {

            if(empty(trim($row->account_gst_no))){

                if(in_array($row->debit_note_no, $debit_no_arr)){ } else {
                    $num_of_debit ++;
                    $debit_no_arr[] = $row->debit_note_no;
                }
                if(in_array($row->bill_no, $bill_no_arr)){ } else {
                    $num_of_bill ++;
                    $bill_no_arr[] = $row->bill_no;
                }

                $gst_pre = $row->cgst + $row->sgst + $row->igst;
                $invoice_date = '';
                if(strtotime($row->invoice_date) != 0) { $invoice_date = date('d M Y', strtotime($row->invoice_date)); }
                $debit_note_date = '';
                if(strtotime($row->debit_note_date) != 0) { $debit_note_date = date('d M Y', strtotime($row->debit_note_date)); }
                $objPHPExcel->setActiveSheetIndex(5)
                    ->setCellValue('A'.$row_inc, 'B2CL')
                    ->setCellValue('B'.$row_inc, $row->debit_note_no)
                    ->setCellValue('C'.$row_inc, $debit_note_date)
                    ->setCellValue('D'.$row_inc, 'D')
                    ->setCellValue('E'.$row_inc, $row->bill_no)
                    ->setCellValue('F'.$row_inc, $invoice_date)
                    ->setCellValue('G'.$row_inc, '')
                    ->setCellValue('H'.$row_inc, $row->state_name)
                    ->setCellValue('I'.$row_inc, $row->amount)
                    ->setCellValue('J'.$row_inc, $gst_pre)
                    ->setCellValue('K'.$row_inc, $row->discounted_price)
                    ->setCellValue('L'.$row_inc, '')
                    ->setCellValue('M'.$row_inc, 'N');
                $total_amount += $row->amount;
                $total_taxable_value += $row->discounted_price;
                $row_inc++;
            }
        }
        $objPHPExcel->setActiveSheetIndex(5)
            ->setCellValue('B3', $num_of_credit + $num_of_debit)
            ->setCellValue('E3', $num_of_bill)
            ->setCellValue('I3', $total_amount)
            ->setCellValue('K3', $total_taxable_value);
        //---------------------------------------

        // Change in EXP WorkSheet
        //---------------------------------------

        // Change in AT WorkSheet
        //---------------------------------------

        // Change in ATADJ WorkSheet
        //---------------------------------------

        // Change in EXEMP WorkSheet
        $total_nil_rated = 0;
        $interstate_reg = 0;
        $intrastate_reg = 0;
        $interstate_unreg = 0;
        $intrastate_unreg = 0;
        foreach ($invoice_lineitem_result as $row) {
            $gst_pre = $row->cgst + $row->sgst + $row->igst;
            if($gst_pre == 0){
                if(!empty(trim($row->account_gst_no)) && $row->state_id != $company_state_id){
                    $interstate_reg += $row->discounted_price;
                }
                if(!empty(trim($row->account_gst_no)) && $row->state_id == $company_state_id){
                    $intrastate_reg += $row->discounted_price;
                }
                if(empty(trim($row->account_gst_no)) && $row->state_id != $company_state_id){
                    $interstate_unreg += $row->discounted_price;
                }
                if(empty(trim($row->account_gst_no)) && $row->state_id == $company_state_id){
                    $intrastate_unreg += $row->discounted_price;
                }
                $total_nil_rated += $row->discounted_price;
            }
        }
        $objPHPExcel->setActiveSheetIndex(9)
            ->setCellValue('B3', $total_nil_rated)
            ->setCellValue('B5', $interstate_reg)
            ->setCellValue('B6', $intrastate_reg)
            ->setCellValue('B7', $interstate_unreg)
            ->setCellValue('B8', $intrastate_unreg);
        //---------------------------------------

        // Change in HSN WorkSheet
        $total_taxable_value = 0;
        $total_cgst = 0;
        $total_sgst = 0;
        $total_igst = 0;
        $total_amount = 0;
        $unique_arr = array();
        $unique_key = 0;
        $hsn_result = array();
        foreach ($invoice_lineitem_result as $row) {
            $gst_pre = $row->cgst + $row->sgst + $row->igst;
            $unique_value = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $row->item_id);
            $pack_unit_id = $this->crud->get_id_by_val('item', 'pack_unit_id', 'item_id', $row->item_id);
            if (in_array($unique_value, $unique_arr)){ 
                $key = array_search ($unique_value, $unique_arr);
                $hsn_result[$key]['item_qty'] += $row->item_qty;
                $hsn_result[$key]['discounted_price'] += $row->discounted_price;
                $hsn_result[$key]['cgst_amount'] += $row->cgst_amount;
                $hsn_result[$key]['sgst_amount'] += $row->sgst_amount;
                $hsn_result[$key]['igst_amount'] += $row->igst_amount;
                $hsn_result[$key]['amount'] += $row->amount - $row->other_charges;
            } else {
                $unique_arr[$unique_key] = $unique_value;
                $hsn_result[$unique_key]['hsn_code'] = $unique_value;
                $hsn_result[$unique_key]['item_id'] = $row->item_id;
                $hsn_result[$unique_key]['pack_unit_id'] = $pack_unit_id;
                $hsn_result[$unique_key]['item_qty'] = $row->item_qty;
                $hsn_result[$unique_key]['discounted_price'] = $row->discounted_price;
                $hsn_result[$unique_key]['cgst_amount'] = $row->cgst_amount;
                $hsn_result[$unique_key]['sgst_amount'] = $row->sgst_amount;
                $hsn_result[$unique_key]['igst_amount'] = $row->igst_amount;
                $hsn_result[$unique_key]['amount'] = $row->amount - $row->other_charges;
                $unique_key++;
            }
            $total_taxable_value += $row->discounted_price;
            $total_cgst += $row->cgst_amount;
            $total_sgst += $row->sgst_amount;
            $total_igst += $row->igst_amount;
            $total_amount += $row->amount - $row->other_charges;
        }
        $row_inc = 5;
        foreach($unique_arr as $key => $unique_row){
            $objPHPExcel->setActiveSheetIndex(10)
                ->setCellValue('A'.$row_inc, $hsn_result[$key]['hsn_code'])
                ->setCellValue('B'.$row_inc, '')
                ->setCellValue('C'.$row_inc, $this->crud->get_id_by_val('pack_unit', 'pack_unit_name', 'pack_unit_id', $hsn_result[$key]['pack_unit_id']))
                ->setCellValue('D'.$row_inc, $hsn_result[$key]['item_qty'])
                ->setCellValue('E'.$row_inc, $hsn_result[$key]['amount'])
                ->setCellValue('F'.$row_inc, $hsn_result[$key]['discounted_price'])
                ->setCellValue('G'.$row_inc, $hsn_result[$key]['igst_amount'])
                ->setCellValue('H'.$row_inc, $hsn_result[$key]['cgst_amount'])
                ->setCellValue('I'.$row_inc, $hsn_result[$key]['sgst_amount'])
                ->setCellValue('J'.$row_inc, '');
            $row_inc++;
        }
        $objPHPExcel->setActiveSheetIndex(10)
            ->setCellValue('E3', $total_amount)
            ->setCellValue('F3', $total_taxable_value)
            ->setCellValue('G3', $total_igst)
            ->setCellValue('H3', $total_cgst)
            ->setCellValue('I3', $total_sgst);
        //---------------------------------------

        // Change in DOCS WorkSheet
        $i_sr_from = '';
        $i_sr_to = '';
        $i_total_no = '';
        $i_canceled = '';
        $c_sr_from = '';
        $c_sr_to = '';
        $c_total_no = '';
        $c_canceled = '';
        $d_sr_from = '';
        $d_sr_to = '';
        $d_total_no = '';
        $d_canceled = '';

        $numbers = array();
        foreach($invoice_lineitem_result as $obj){
            $numbers[] = $obj->sales_invoice_no;
        }
        if(!empty($numbers)){
            $numbers = array_unique($numbers);
            $i_sr_from = min($numbers);
            $i_sr_to = max($numbers);
            $i_total_no = count($numbers);
            $total_no = $i_sr_to - $i_sr_from + 1;
            $i_canceled = $total_no - $i_total_no;
        }
        $numbers = array();
        foreach($credit_lineitem_result as $obj){
            $numbers[] = $obj->credit_note_no;
        }
        if(!empty($numbers)){
            $numbers = array_unique($numbers);
            $c_sr_from = min($numbers);
            $c_sr_to = max($numbers);
            $c_total_no = count($numbers);
            $total_no = $c_sr_to - $c_sr_from + 1;
            $c_canceled = $total_no - $c_total_no;
        }
        $numbers = array();
        foreach($debit_lineitem_result as $obj){
            $numbers[] = $obj->debit_note_no;
        }
        if(!empty($numbers)){
            $numbers = array_unique($numbers);
            $d_sr_from = min($numbers);
            $d_sr_to = max($numbers);
            $d_total_no = count($numbers);
            $total_no = $d_sr_to - $d_sr_from + 1;
            $d_canceled = $total_no - $d_total_no;
        }
        $objPHPExcel->setActiveSheetIndex(11)
            ->setCellValue('B5', $i_sr_from)
            ->setCellValue('C5', $i_sr_to)
            ->setCellValue('D5', $i_total_no)
            ->setCellValue('E5', $i_canceled)
            ->setCellValue('B6', $c_sr_from)
            ->setCellValue('C6', $c_sr_to)
            ->setCellValue('D6', $c_total_no)
            ->setCellValue('E6', $c_canceled)
            ->setCellValue('B7', $d_sr_from)
            ->setCellValue('C7', $d_sr_to)
            ->setCellValue('D7', $d_total_no)
            ->setCellValue('E7', $d_canceled);
        //---------------------------------------

        $filename = 'GST1_Excel.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        // Write the file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
        $objWriter->save('php://output');
        exit;

        /* END : Read Excel - Write Data - Download Excel */


        /* START : Create Excel - Write Data - Download Excel */

        //~ $this->load->library('excel');
        //~ $objPHPExcel = new PHPExcel();
        //~ 
        //~ // Set the active Excel worksheet to sheet 0 
        //~ $objPHPExcel->setActiveSheetIndex(0);  
        //~ $objPHPExcel->getActiveSheet()->setTitle('Help Instruction');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'Help Instruction'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 1
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ 
        //~ $objPHPExcel->setActiveSheetIndex(1)
        //~ ->setCellValue('A1', 'Summary For B2B(4)')
        //~ ->setCellValue('B1', '')
        //~ ->setCellValue('C1', '')
        //~ ->setCellValue('D1', '')
        //~ ->setCellValue('E1', '')
        //~ ->setCellValue('F1', '')
        //~ ->setCellValue('G1', '')
        //~ ->setCellValue('H1', '')
        //~ ->setCellValue('I1', '')
        //~ ->setCellValue('J1', '')
        //~ ->setCellValue('K1', 'HELP');
        //~ 
        //~ $objPHPExcel->setActiveSheetIndex(1)
        //~ ->setCellValue('A2', 'No. of Recipients')
        //~ ->setCellValue('B2', 'No. of Invoices')
        //~ ->setCellValue('C2', '')
        //~ ->setCellValue('D2', 'Total Invoice Value')
        //~ ->setCellValue('E2', '')
        //~ ->setCellValue('F2', '')
        //~ ->setCellValue('G2', '')
        //~ ->setCellValue('H2', '')
        //~ ->setCellValue('I2', '')
        //~ ->setCellValue('J2', 'Total Taxable Value')
        //~ ->setCellValue('K2', 'Total Cess');
        //~ 
        //~ $objPHPExcel->setActiveSheetIndex(1)
        //~ ->setCellValue('A4', 'GSTIN/UIN of Recipient')
        //~ ->setCellValue('B4', 'Invoice Number')
        //~ ->setCellValue('C4', 'Invoice date')
        //~ ->setCellValue('D4', 'Invoice Value')
        //~ ->setCellValue('E4', 'Place Of Supply')
        //~ ->setCellValue('F4', 'Reverse Charge')
        //~ ->setCellValue('G4', 'Invoice Type')
        //~ ->setCellValue('H4', 'E-Commerce GSTIN')
        //~ ->setCellValue('I4', 'Rate')
        //~ ->setCellValue('J4', 'Taxable Value')
        //~ ->setCellValue('K4', 'Cess Amount');
        //~ 
        //~ $grand_total = 0;
        //~ $where = array('created_by' => $this->logged_in_id);
        //~ $result = $this->crud->get_row_by_id('sales_invoice', $where);
        //~ $count = count($result);
        //~ $row_inc = 5;
        //~ foreach ($result as $row) {
        //~ $objPHPExcel->setActiveSheetIndex(1)
        //~ ->setTitle('b2b')
        //~ ->setCellValue('A'.$row_inc, '')
        //~ ->setCellValue('B'.$row_inc, $row->sales_invoice_no)
        //~ ->setCellValue('C'.$row_inc, date('d-m-Y', strtotime($row->sales_invoice_date)))
        //~ ->setCellValue('D'.$row_inc, $row->amount_total)
        //~ ->setCellValue('E'.$row_inc, '')
        //~ ->setCellValue('F'.$row_inc, '')
        //~ ->setCellValue('G'.$row_inc, '')
        //~ ->setCellValue('H'.$row_inc, '')
        //~ ->setCellValue('I'.$row_inc, '')
        //~ ->setCellValue('J'.$row_inc, '')
        //~ ->setCellValue('K'.$row_inc, '');
        //~ $grand_total += $row->amount_total;
        //~ $row_inc++;
        //~ }
        //~ $objPHPExcel->setActiveSheetIndex(1)
        //~ ->setCellValue('A2', '')
        //~ ->setCellValue('B3', $count)
        //~ ->setCellValue('C2', '')
        //~ ->setCellValue('D3', $grand_total)
        //~ ->setCellValue('E2', '')
        //~ ->setCellValue('F2', '')
        //~ ->setCellValue('G2', '')
        //~ ->setCellValue('H2', '')
        //~ ->setCellValue('I2', '')
        //~ ->setCellValue('J3', $count)
        //~ ->setCellValue('K2', '');
        //~ 
        //~ // Set the active Excel worksheet to sheet 2
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(2);
        //~ $objPHPExcel->getActiveSheet()->setTitle('b2cl');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'b2cl'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 3
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(3);
        //~ $objPHPExcel->getActiveSheet()->setTitle('b2cs');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'b2cs'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 4
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(4);
        //~ $objPHPExcel->getActiveSheet()->setTitle('cdnr');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'cdnr'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 5
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(5);
        //~ $objPHPExcel->getActiveSheet()->setTitle('cdnur');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'cdnur'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 6
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(6);
        //~ $objPHPExcel->getActiveSheet()->setTitle('exp');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'exp'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 7
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(7);
        //~ $objPHPExcel->getActiveSheet()->setTitle('at');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'at'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 8
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(8);
        //~ $objPHPExcel->getActiveSheet()->setTitle('atadj');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'atadj'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 9
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(9);
        //~ $objPHPExcel->getActiveSheet()->setTitle('exemp');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'exemp'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 10
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(10);
        //~ $objPHPExcel->getActiveSheet()->setTitle('hsn');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'hsn'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 11
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(11);
        //~ $objPHPExcel->getActiveSheet()->setTitle('docs');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'docs'); 
        //~ 
        //~ // Set the active Excel worksheet to sheet 12
        //~ $objWorkSheet = $objPHPExcel->createSheet();
        //~ $objPHPExcel->setActiveSheetIndex(12);
        //~ $objPHPExcel->getActiveSheet()->setTitle('master');
        //~ $objPHPExcel->getActiveSheet()->SetCellValue('A'.'1', 'master'); 
        //~ 
        //~ $filename='GST1_Excel.xls'; //save our workbook as this file name
        //~ header('Content-Type: application/vnd.ms-excel'); //mime type
        //~ header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        //~ header('Cache-Control: max-age=0'); //no cache
        //~ 
        //~ $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        //~ //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        //~ $objWriter->save('php://output');
        //~ exit;

        /* END : Create Excel - Write Data - Download Excel */
    }

}
