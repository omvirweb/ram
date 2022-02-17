<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Backup
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Gstr_3b_excel extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('Appmodel', 'app_model');
        $this->load->model('Crud', 'crud');
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')) {
            redirect('/auth/login/');
        }
        $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['user_id'];
        $this->now_time = date('Y-m-d H:i:s');
    }

    function index() {
        if($this->applib->have_access_role(MODULE_GSTR_3B_EXCEL_EXPORT_ID,"view")) {
            $data = array();
            set_page('gstr_3b_excel_export', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function gstr_3b_excel_export() {

        $daterange = implode('/', func_get_args());
        $daterange = str_replace('%20', ' ', $daterange);
        $from_date = '';
        $to_date = '';
        if (isset($daterange) && !empty($daterange)) {
            $daterange = explode(' - ', $daterange);
            $from_date = trim($daterange[0]);
            $from_date = substr($from_date, 6, 4) . '-' . substr($from_date, 3, 2) . '-' . substr($from_date, 0, 2);
            $to_date = trim($daterange[1]);
            $to_date = substr($to_date, 6, 4) . '-' . substr($to_date, 3, 2) . '-' . substr($to_date, 0, 2);
        }

        /* START : Read Excel - Write Data - Download Excel */

        $this->load->library('excel');
        $fileType = 'Excel5';
        $fileName = FCPATH . '/uploads/GSTR-3B-Format-Excel.xls';

        // Read the file
        $objPHPExcel = PHPExcel_IOFactory::load($fileName);

        $current_company_id = $this->logged_in_id;
        $company_data = $this->crud->get_data_row_by_id('user', 'user_id', $current_company_id);
        $company_state_id = $company_data->state;
        $company_gst_no = $company_data->gst_no;
        $company_user_name = $company_data->user_name;
        $sales_invoice_lineitem_result = $this->crud->get_sales_invoice_lineitems($current_company_id, $from_date, $to_date);
        $purchase_invoice_lineitem_result = $this->crud->get_purchase_invoice_lineitems($current_company_id, $from_date, $to_date);

        // Change in GSTR 3B WorkSheet

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q5', date('Y', strtotime($from_date)))
            ->setCellValue('Q6', date('m', strtotime($from_date)))
            ->setCellValue('D8', substr($company_gst_no, 0, 1))
            ->setCellValue('E8', substr($company_gst_no, 1, 1))
            ->setCellValue('F8', substr($company_gst_no, 2, 1))
            ->setCellValue('G8', substr($company_gst_no, 3, 1))
            ->setCellValue('H8', substr($company_gst_no, 4, 1))
            ->setCellValue('I8', substr($company_gst_no, 5, 1))
            ->setCellValue('J8', substr($company_gst_no, 6, 1))
            ->setCellValue('K8', substr($company_gst_no, 7, 1))
            ->setCellValue('L8', substr($company_gst_no, 8, 1))
            ->setCellValue('M8', substr($company_gst_no, 9, 1))
            ->setCellValue('N8', substr($company_gst_no, 10, 1))
            ->setCellValue('O8', substr($company_gst_no, 11, 1))
            ->setCellValue('P8', substr($company_gst_no, 12, 1))
            ->setCellValue('Q8', substr($company_gst_no, 13, 1))
            ->setCellValue('R8', substr($company_gst_no, 14, 1))
            ->setCellValue('D9', $company_user_name);

        $total_sales_invoice_value = 0;
        $total_sales_taxable_value_with_gst = 0;
        $total_sales_igst_amount = 0;
        $total_sales_cgst_amount = 0;
        $total_sales_sgst_amount = 0;
        $total_sales_taxable_value_without_gst = 0;

        $supplies_made_to_unregistered_persons_state = array();
        $supplies_made_to_unregistered_persons_taxable_value = 0;
        $supplies_made_to_unregistered_persons_icst = 0;
        $supplies_made_to_composition_persons_state = array();
        $supplies_made_to_composition_persons_taxable_value = 0;
        $supplies_made_to_composition_persons_icst = 0;

        foreach ($sales_invoice_lineitem_result as $row) {
            $total_sales_invoice_value += $row->amount;
            $gst_pre = $row->cgst + $row->sgst + $row->igst;
            if ($gst_pre != 0) {
                $total_sales_taxable_value_with_gst += $row->discounted_price;
                $total_sales_igst_amount += $row->igst_amount;
                $total_sales_cgst_amount += $row->cgst_amount;
                $total_sales_sgst_amount += $row->sgst_amount;
            } else {
                $total_sales_taxable_value_without_gst += $row->discounted_price;
            }

            if ($company_state_id != $row->state_id) {
                if (!empty(trim($row->account_gst_no))) {
                    if(!empty($row->state_name)){
                        if (in_array($row->state_name, $supplies_made_to_composition_persons_state)) {} else {
                            $supplies_made_to_composition_persons_state[] = $row->state_name;
                        }
                    }
                    $supplies_made_to_composition_persons_taxable_value += $row->discounted_price;
                    $supplies_made_to_composition_persons_icst += $row->igst_amount;
                } else {
                    if(!empty($row->state_name)){
                        if (in_array($row->state_name, $supplies_made_to_unregistered_persons_state)) {} else {
                            $supplies_made_to_unregistered_persons_state[] = $row->state_name;
                        }
                    }
                    $supplies_made_to_unregistered_persons_taxable_value += $row->discounted_price;
                    $supplies_made_to_unregistered_persons_icst += $row->igst_amount;
                }
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D15', $total_sales_taxable_value_with_gst)
            ->setCellValue('G15', $total_sales_igst_amount)
            ->setCellValue('J15', $total_sales_cgst_amount)
            ->setCellValue('M15', $total_sales_sgst_amount)
            ->setCellValue('D16', $total_sales_taxable_value_without_gst);

        if (!empty($supplies_made_to_unregistered_persons_state)) {
            $supplies_made_to_unregistered_persons_state = implode(',', $supplies_made_to_unregistered_persons_state);
        } else {
            $supplies_made_to_unregistered_persons_state = '';
        }
        if (!empty($supplies_made_to_composition_persons_state)) {
            $supplies_made_to_composition_persons_state = implode(',', $supplies_made_to_composition_persons_state);
        } else {
            $supplies_made_to_composition_persons_state = '';
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D26', $supplies_made_to_unregistered_persons_state)
            ->setCellValue('I26', $supplies_made_to_unregistered_persons_taxable_value)
            ->setCellValue('N26', $supplies_made_to_unregistered_persons_icst)
            ->setCellValue('D27', $supplies_made_to_composition_persons_state)
            ->setCellValue('I27', $supplies_made_to_composition_persons_taxable_value)
            ->setCellValue('N27', $supplies_made_to_composition_persons_icst);

        $total_purchase_invoice_value = 0;
        $total_purchase_taxable_value = 0;
        $total_purchase_igst_amount = 0;
        $total_purchase_cgst_amount = 0;
        $total_purchase_sgst_amount = 0;
        
        $import_of_goods_icst_amount = 0;
        $import_of_goods_ccst_amount = 0;
        $import_of_goods_scst_amount = 0;
        $import_of_services_icst_amount = 0;
        $import_of_services_ccst_amount = 0;
        $import_of_services_scst_amount = 0;
        
        foreach ($purchase_invoice_lineitem_result as $row) {
            $total_purchase_invoice_value += $row->amount;
            $total_purchase_taxable_value += $row->discounted_price;
            $total_purchase_igst_amount += $row->igst_amount;
            $total_purchase_cgst_amount += $row->cgst_amount;
            $total_purchase_sgst_amount += $row->sgst_amount;
            
            if($row->item_type_id == ITEM_TYPE_GOODS_ID){
                $import_of_goods_icst_amount += $row->igst_amount;
                $import_of_goods_ccst_amount += $row->cgst_amount;
                $import_of_goods_scst_amount += $row->sgst_amount;
            }
            if($row->item_type_id == ITEM_TYPE_SERVICES_ID){
                $import_of_services_icst_amount += $row->igst_amount;
                $import_of_services_ccst_amount += $row->cgst_amount;
                $import_of_services_scst_amount += $row->sgst_amount;
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D18', $total_purchase_taxable_value)
            ->setCellValue('G18', $total_purchase_igst_amount)
            ->setCellValue('J18', $total_purchase_cgst_amount)
            ->setCellValue('M18', $total_purchase_sgst_amount);
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D35', $import_of_goods_icst_amount)
            ->setCellValue('H35', $import_of_goods_ccst_amount)
            ->setCellValue('L35', $import_of_goods_scst_amount)
            ->setCellValue('D36', $import_of_services_icst_amount)
            ->setCellValue('H36', $import_of_services_ccst_amount)
            ->setCellValue('L36', $import_of_services_scst_amount);
        
        //---------------------------------------

        $filename = 'GSTR_3B_Excel.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        // Write the file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
        $objWriter->save('php://output');
        exit;
    }

}
