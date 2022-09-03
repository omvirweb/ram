<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Quotation extends CI_Controller 
    {
        public $logged_in_id = null;
        public $now_time = null;

        function __construct() 
        {
            parent::__construct();
            $this->load->helper(array('form', 'url'));
            $this->load->model('Appmodel', 'app_model');
            $this->load->model('Crud', 'crud');
            if (!$this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')) {
                redirect('/auth/login/');
            }
            $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['user_id'];
            $this->is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
            $this->now_time = date('Y-m-d H:i:s');
        }

        function purchase_quotation_add()
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
            $data['type'] = 'Quatation';
            $data['page_title'] = 'Purchase Quatation Add/Edit';

            if($this->input->post('quotation_id')){
                if(!($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $quotation_id = $this->input->post('quotation_id');
                $where = array('quotation_id' => $quotation_id);
                $quotation_data = $this->crud->get_row_by_id('quotation', $where);
                $data['quotation_data'] = $quotation_data[0];
                $data['quotation_id'] = $quotation_id;
                $lineitems = '';
                $where = array('module' => '6', 'parent_id' => $quotation_id);
                $quotation_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                foreach ($quotation_lineitems as $quotation_lineitem) {
                    $lineitems .= "'" . json_encode($quotation_lineitem) . "',";
                }
                $data['quotation_lineitems'] = $lineitems;
            }
            
            if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) {
                set_page('quotation/purchase_quotation_add', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }

        function purchase_quotation_list() 
        {
            if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) {
                $data = array();
                $data['page_title'] = 'Purchase Quotation';
                $data['quotation_type'] = 1;
                set_page('quotation/purchase_quotation_list', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }

        function sales_quotation_add()
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
            $data['type'] = 'Quatation';
            $data['page_title'] = 'Sales Quatation Add/Edit';

            if($this->input->post('quotation_id')){
                if(!($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"edit"))) {
                    $this->session->set_flashdata('success', false);
                    $this->session->set_flashdata('message', 'You have not permission to access this page.');
                    redirect('/');
                }
                $quotation_id = $this->input->post('quotation_id');
                $where = array('quotation_id' => $quotation_id);
                $quotation_data = $this->crud->get_row_by_id('quotation', $where);
                $data['quotation_data'] = $quotation_data[0];
                $data['quotation_id'] = $quotation_id;
                $lineitems = '';
                $where = array('module' => '5', 'parent_id' => $quotation_id);
                $quotation_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                foreach ($quotation_lineitems as $quotation_lineitem) {
                    $lineitems .= "'" . json_encode($quotation_lineitem) . "',";
                }
                $data['quotation_lineitems'] = $lineitems;
            }
            
            if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) {
                set_page('quotation/sales_quotation_add', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }

        function sales_quotation_list() 
        {
            if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) {
                $data = array();
                $data['page_title'] = 'Sales Quotation';
                $data['quotation_type'] = 1;
                set_page('quotation/sales_quotation_list', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }

        function save_quotation()
        {

            $return = array();
            $post_data = $this->input->post();
            $line_items_data = json_decode('['.$post_data['line_items_data'].']');
            $quotation_data = array();
            if(!isset($post_data['prefix'])) {
                $post_data['prefix'] = '';
            }
            $module = ($post_data['quotation_type'] == 1) ? 5 : 6;

            $quotation_data['quotation_type'] = $post_data['quotation_type']; 
            $quotation_data['quotation_date'] = date('Y-m-d', strtotime($post_data['quotation_date']));
            $quotation_data['account_id'] = $post_data['account_id'];
            $quotation_data['site_id'] = (isset($post_data['site_id']) && $post_data['site_id'] != '') ? $post_data['site_id'] : '';
            $quotation_data['qty_total'] = $post_data['qty_total'];
            $quotation_data['pure_amount_total'] = $post_data['pure_amount_total'];
            $quotation_data['discount_total'] = ($post_data['pure_amount_total'] - $post_data['discounted_price_total']);
            $quotation_data['cgst_amount_total'] = 0;
            $quotation_data['sgst_amount_total'] = 0;
            $quotation_data['igst_amount_total'] = 0;
            $quotation_data['other_charges_total'] = 0;
            $quotation_data['round_off_amount'] = (isset($post_data['round_off_amount'])?$post_data['round_off_amount']:0);
            $quotation_data['amount_total'] = ($post_data['discounted_price_total'] + $quotation_data['round_off_amount']);

            if(isset($post_data['quotation_id']) && !empty($post_data['quotation_id'])) {

                $quotation_data['updated_at'] = $this->now_time;
                $quotation_data['updated_by'] = $this->logged_in_id;

                $where_array['quotation_id'] = $post_data['quotation_id'];
                $this->crud->update('quotation', $quotation_data, $where_array);
                $this->session->set_flashdata('message','Quotation Updated Successfully');
                $this->session->set_flashdata('success',true);
                
                $return['success'] = "Updated";
                
                $parent_id = $post_data['quotation_id'];
                
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
                    $add_lineitem['item_code'] = isset($lineitem->item_code)?$lineitem->item_code:NULL;
                    $add_lineitem['internal_code'] = isset($lineitem->internal_code)?$lineitem->internal_code:NULL;
                    $add_lineitem['hsn'] = isset($lineitem->hsn)?$lineitem->hsn:NULL;
                    $add_lineitem['free_qty'] = isset($lineitem->free_qty)?$lineitem->free_qty:NULL;
                    $add_lineitem['no1'] = isset($lineitem->no1)?$lineitem->no1:NULL;
                    $add_lineitem['no2'] = isset($lineitem->no2)?$lineitem->no2:NULL;
                    $add_lineitem['net_rate'] = isset($lineitem->net_rate)?$lineitem->net_rate:NULL;
                    $add_lineitem['pure_amount'] = isset($lineitem->pure_amount) ? $lineitem->pure_amount : NULL;
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

                    if(isset($lineitem->id) && !empty($lineitem->id)){
                        $add_lineitem['updated_at'] = $this->now_time;
                        $add_lineitem['updated_by'] = $this->logged_in_id;
                        $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                        $where_id['id'] = $lineitem->id;
                        $this->crud->update('lineitems', $add_lineitem, $where_id);
                    } else {
                        $add_lineitem['created_at'] = $this->now_time;
                        $add_lineitem['created_by'] = $this->logged_in_id;
                        $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                        $add_lineitem['updated_at'] = $this->now_time;
                        $add_lineitem['updated_by'] = $this->logged_in_id;
                        $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                        $this->crud->insert('lineitems',$add_lineitem);
                    }
                }

                // save Docs I think This Update Here I put save Code 
                if($parent_id != '' && $module == 5){
                    if(is_array($_FILES)) {
                        $i = 0;
                        foreach ($_FILES['docs']['name'] as $name => $value){
                                $docs_data = [];
                                $ext = end((explode(".", $value)));
                                $newname='Quotation'.date("dmYGis", time()).$name.'.'.$ext;
                                if(is_uploaded_file($_FILES['docs']['tmp_name'][$name])) {
                                $sourcePath = $_FILES['docs']['tmp_name'][$name];
                                $dir="assets/uploads/quotation_docs";
                                if (!is_dir('assets/uploads/quotation_docs')) {
                                    mkdir('./'.$dir, 0777, TRUE);
                                }
                                $targetPath = $dir.'/'.$newname;
                                move_uploaded_file($sourcePath,$targetPath);
                                $docs_data['quotation_id'] =  $parent_id;
                                $docs_data['doc_name'] = $newname;
                                $docs_data['doc_desc'] = (isset($post_data['docs_type'][$i]) && $post_data['docs_type'][$i] != '') ? $post_data['docs_type'][$i] : $newname;
                                $docs_data['created_at'] = $this->now_time;
                                $docs_data['created_by'] = $this->logged_in_id;
                                $docs_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                $docs_data['updated_at'] = $this->now_time;
                                $docs_data['updated_by'] = $this->logged_in_id;
                                $docs_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
                                $this->crud->insert('quotation_docs',$docs_data);
                            }
                            $i++;

                        }

                    }
                }
            } else { 

                $qno = $this->crud->getFromSQL("SELECT `quotation_no` FROM `quotation` WHERE `quotation_type`='".$post_data['quotation_type']."' ORDER BY `quotation_no` DESC");
                $quotation_no = ($qno) ? $qno[0]->quotation_no+1 : 1;

                $quotation_data['quotation_no'] = $quotation_no;
                $quotation_data['created_at'] = $this->now_time;
                $quotation_data['created_by'] = $this->logged_in_id;

                $this->crud->insert('quotation', $quotation_data);
                $this->session->set_flashdata('message','Quotation Added Successfully');
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
                    $add_lineitem['item_code'] = isset($lineitem->item_code)?$lineitem->item_code:NULL;
                    $add_lineitem['internal_code'] = isset($lineitem->internal_code)?$lineitem->internal_code:NULL;
                    $add_lineitem['hsn'] = isset($lineitem->hsn)?$lineitem->hsn:NULL;
                    $add_lineitem['free_qty'] = isset($lineitem->free_qty)?$lineitem->free_qty:NULL;
                    $add_lineitem['no1'] = isset($lineitem->no1)?$lineitem->no1:NULL;
                    $add_lineitem['no2'] = isset($lineitem->no2)?$lineitem->no2:NULL;
                    $add_lineitem['net_rate'] = isset($lineitem->net_rate)?$lineitem->net_rate:NULL;
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
                    $add_lineitem['created_at'] = $this->now_time;
                    $add_lineitem['updated_at'] = $this->now_time;
                    $add_lineitem['updated_by'] = $this->logged_in_id;
                    $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                    $add_lineitem['created_by'] = $this->logged_in_id;
                    $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                    $this->crud->insert('lineitems',$add_lineitem);
                }
                // save Docs
                if($parent_id != '' && $module == 5){
                    if(is_array($_FILES)) {
                        $i = 0;
                        foreach ($_FILES['docs']['name'] as $name => $value){
                                $docs_data = [];
                                $ext = end((explode(".", $value)));
                                $newname='Quotation'.date("dmYGis", time()).$name.'.'.$ext;
                                if(is_uploaded_file($_FILES['docs']['tmp_name'][$name])) {
                                $sourcePath = $_FILES['docs']['tmp_name'][$name];
                                $dir="assets/uploads/quotation_docs";
                                if (!is_dir('assets/uploads/quotation_docs')) {
                                    mkdir('./'.$dir, 0777, TRUE);
                                }
                                $targetPath = $dir.'/'.$newname;
                                move_uploaded_file($sourcePath,$targetPath);
                                $docs_data['quotation_id'] =  $parent_id;
                                $docs_data['doc_name'] = $newname;
                                $docs_data['doc_desc'] = (isset($post_data['docs_type'][$i]) && $post_data['docs_type'][$i] != '') ? $post_data['docs_type'][$i] : $newname;
                                $docs_data['created_at'] = $this->now_time;
                                $docs_data['created_by'] = $this->logged_in_id;
                                $docs_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                                $docs_data['updated_at'] = $this->now_time;
                                $docs_data['updated_by'] = $this->logged_in_id;
                                $docs_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
                                $this->crud->insert('quotation_docs',$docs_data);
                            }
                            $i++;
                        }
                    }
                }
            }
            
            print json_encode($return);
            exit;        
        }

        function quotation_datatable()
        {
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
            $config['table'] = 'quotation q';
            $config['select'] = 'q.quotation_id, q.quotation_no, q.quotation_date, q.amount_total, a.account_name, a.account_gst_no, q.quotation_type';
            $config['column_order'] = array(null, 'q.quotation_no', 'a.account_name', 'q.quotation_date', 'q.amount_total');
            $config['column_search'] = array('q.quotation_no', 'a.account_name', 'DATE_FORMAT(q.quotation_date,"%d-%m-%Y")', 'q.amount_total');
            $config['wheres'][] = array('column_name' => 'q.created_by', 'column_value' => $this->logged_in_id);
            $config['wheres'][] = array('column_name' => 'q.quotation_type', 'column_value' => $_POST['quotation_type']);
            if (!empty($account_id)) {
                $config['custom_where'] = 'q.account_id IN (' . implode(",", $account_id) . ') ';
            }
            if (!empty($from_date) && !empty($to_date)) {
                $config['wheres'][] = array('column_name' => 'q.quotation_date >=', 'column_value' => $from_date);
                $config['wheres'][] = array('column_name' => 'q.quotation_date <=', 'column_value' => $to_date);
            }
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = q.account_id', 'join_type' => 'left');
            $config['order'] = array('q.created_at' => 'desc');
            $this->load->library('datatables', $config, 'datatable');
            $list = $this->datatable->get_datatables();
    
            $isEdit = $this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID, "edit");
            $isDelete = $this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID, "delete");
    
            $data = array();
            foreach ($list as $order) {
                $row = array();
                $action = '';
                $quotation_type = ($order->quotation_type == 1) ? 5 : 6;
                if($isEdit) {
                    $action .= '<form id="edit_' . $order->quotation_id . '" method="post" action="' . base_url(isset($order->quotation_type) && $order->quotation_type == '1' ? 'quotation/sales_quotation_add' : 'quotation/purchase_quotation_add') . '" style="width: 25px; display: initial;" >
                        <input type="hidden" name="quotation_id" value="' . $order->quotation_id . '">
                        <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->quotation_id . '\').submit();" title="Edit Quotation"><i class="fa fa-edit"></i></a>
                    </form> ';
                }
                if($isDelete) {
                    $action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('quotation/quotation_delete/' . $quotation_type . '/' . $order->quotation_id) . '"><i class="fa fa-trash"></i></a>';    
                }
                $link='<a href="javascript:void(0);" data-id="'.$order->quotation_id.'" class="open_doc_model">'.$order->account_name.'</a>';
                $row[] = $action;
                $row[] = $order->quotation_no;
                $row[] = $link;
                $row[] = $order->account_gst_no;
                $row[] = date('d-m-Y', strtotime($order->quotation_date));
                $row[] = number_format($order->amount_total, 2, '.', '');
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

        function quotation_delete($quotation_type, $quotation_id)
        {
            $this->crud->delete('quotation', array('quotation_id' => $quotation_id));
            $this->crud->delete('lineitems', array('module' => $quotation_type, 'parent_id' => $quotation_id));
        }

        function getQuotationDocs(){
            $return = array(
                'status'=>false,
                'message'=>'Data Not Found'
            );
            $post_data = $this->input->post();
            if(isset($post_data) && $post_data['id'] != ''){
                $table_name = 'quotation_docs';
                $order_by_column = 'quotation_docs_id';
                $order_by_value = 'desc';
                $where_array =[
                    'quotation_id'=>$post_data['id']
                ];
                $data=$this->crud->get_all_with_where($table_name,$order_by_column,$order_by_value,$where_array);
                if(!empty($data)){
                    $return = array(
                        'status'=>true,
                        'data'=>$data
                    );
                }
            }
            echo json_encode($return);
        }
    }
?>