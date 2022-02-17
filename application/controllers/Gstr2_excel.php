<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Backup
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Gstr2_excel extends CI_Controller
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
        if($this->applib->have_access_role(MODULE_GSTR2_EXCEL_EXPORT_ID,"view")) {
            $data = array();
            set_page('gstr2_excel_export', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }
    function gstr2_excel_export() {

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
        $fileName = FCPATH . '/uploads/GSTR2-Format-Excel.xls';

        // Read the file
        $objPHPExcel = PHPExcel_IOFactory::load($fileName);

        $current_company_id = $this->logged_in_id;
        $company_data = $this->crud->get_data_row_by_id('user', 'user_id', $current_company_id);
        $company_state_id = $company_data->state;
        $company_gst_no = $company_data->gst_no;
        $company_user_name = $company_data->user_name;
        $purchase_invoice_result = $this->crud->get_purchase_invoice($current_company_id, $from_date, $to_date);
        // Change in GSTR 3B WorkSheet

        $row_inc = 5;
        foreach ($purchase_invoice_result as $row) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row_inc, $row->account_gst_no)
                    ->setCellValue('B'.$row_inc, $row->account_name)
                    ->setCellValue('C'.$row_inc, $row->purchase_invoice_no)
                    ->setCellValue('D'.$row_inc, 'Purchase')
                    ->setCellValue('E'.$row_inc, date('d M Y', strtotime($row->purchase_invoice_date)))
                    ->setCellValue('F'.$row_inc, $row->amount_total)
                    ->setCellValue('G'.$row_inc, $row->state_name)
                    ->setCellValue('H'.$row_inc, '?')
                    ->setCellValue('I'.$row_inc, '?')
                    ->setCellValue('J'.$row_inc, $row->pure_amount_total)
                    ->setCellValue('K'.$row_inc, $row->igst_amount_total)
                    ->setCellValue('L'.$row_inc, $row->cgst_amount_total)
                    ->setCellValue('M'.$row_inc, $row->sgst_amount_total)
                    ->setCellValue('N'.$row_inc, '')
                    ->setCellValue('O'.$row_inc, '');
            $row_inc++;
        }
        
        //---------------------------------------

        $filename = 'GSTR2_Excel.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        // Write the file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
        $objWriter->save('php://output');
        exit;
    }
}
