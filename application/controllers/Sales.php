<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Sales
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Sales extends CI_Controller
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
		$this->prefix = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['prefix'];
		$this->is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
		$this->now_time = date('Y-m-d H:i:s');
	}
	//~ Sales Invoice
	function invoice(){
		redirect('transaction/sales_purchase_transaction/sales');
		exit();
		
		if($this->is_single_line_item == 1) {
			redirect('transaction/sales_purchase_transaction/sales');
			exit();
		}
		
        $data = array();

        $sales_invoice_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date'));
        if(!empty($sales_invoice_date) && strtotime($sales_invoice_date) > 0) {
        	$data['sales_invoice_date'] = date('d-m-Y',strtotime($sales_invoice_date));
        } else {
        	$data['sales_invoice_date'] = date('d-m-Y');
        }
        

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

        if(isset($_POST['sales_invoice_id'])){
        	if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"edit")) {
            	$where = array('sales_invoice_id' => $_POST['sales_invoice_id']);
	            $sales_invoice_data = $this->crud->get_row_by_id('sales_invoice', $where);
	            $data['sales_invoice_data'] = $sales_invoice_data[0];
	            $lineitems = '';
	            $where = array('module' => '2', 'parent_id' => $_POST['sales_invoice_id']);
	            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
	            foreach($sales_invoice_lineitems as $sales_invoice_lineitem){
	                //$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
	                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
	                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
	                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
	                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
	                $lineitems .= "'".json_encode($sales_invoice_lineitem)."',";
	            }
	            $data['sales_invoice_lineitems'] = $lineitems;
	            set_page('sales/invoice/invoice', $data);
	        } else {
	        	redirect('/');
	        }
        } else {
        	if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) {
            	$data['prefix'] = $this->crud->get_id_by_val('user', 'prefix', 'user_id', $this->logged_in_id);
	            $sales_invoice_no = $this->crud->get_max_number_where('sales_invoice', 'sales_invoice_no', array('created_by' => $this->logged_in_id, 'prefix' => $this->prefix));
	            if(empty($sales_invoice_no->sales_invoice_no)){
	                $data['sales_invoice_no'] = $this->crud->get_id_by_val('user', 'invoice_no_start_from', 'prefix', $this->prefix);
	            } else {
	                $data['sales_invoice_no'] = $sales_invoice_no->sales_invoice_no + 1;    
	            }
	            $data['invoice_type'] = $this->crud->get_column_value_by_id('user','invoice_type',array('user_id'=>$this->logged_in_id));
	            set_page('sales/invoice/invoice', $data);
	        } else {
	        	redirect('/');
	        }
        }
	}
	
	function save_sales_invoice(){
		$post_data = $this->input->post();
                if(!isset($post_data['prefix'])) {
                    $post_data['prefix'] = '';
                }
		$line_items_data = json_decode('['.$post_data['line_items_data'].']'); 
		$sales_invoice_data = array();
                if(isset($post_data['triplicate']) && !empty($post_data['triplicate'])) {
                    $sales_invoice_data['print_type'] = $post_data['triplicate'];
                } else if(isset($post_data['duplicate']) && !empty($post_data['duplicate'])) {
                    $sales_invoice_data['print_type'] = $post_data['duplicate'];
                }
                if(isset($post_data['shipping_address_chkbox']) && !empty($post_data['shipping_address_chkbox'])) {
                    $sales_invoice_data['is_shipping_same_as_billing_address'] = 0;
                    $sales_invoice_data['shipping_address'] = $this->crud->get_column_value_by_id('user','address',array('user_id' => $this->logged_in_id));
                } else {
                    $sales_invoice_data['is_shipping_same_as_billing_address'] = 1;
                    $sales_invoice_data['shipping_address'] = $post_data['shipping_address'];
                }
		$sales_invoice_data['account_id'] = $post_data['account_id'];
		$sales_invoice_data['sales_invoice_date'] = date('Y-m-d', strtotime($post_data['sales_invoice_date']));
		//~ $sales_invoice_data['bill_no'] = $post_data['bill_no'];
		$sales_invoice_data['sales_invoice_desc'] = $post_data['sales_invoice_desc'];
		$sales_invoice_data['qty_total'] = $post_data['qty_total'];
		$sales_invoice_data['pure_amount_total'] = $post_data['pure_amount_total'];
		$sales_invoice_data['discount_total'] = $post_data['discount_total'];
		$sales_invoice_data['cgst_amount_total'] = $post_data['cgst_amount_total'];
		$sales_invoice_data['sgst_amount_total'] = $post_data['sgst_amount_total'];
		$sales_invoice_data['igst_amount_total'] = $post_data['igst_amount_total'];
		$sales_invoice_data['amount_total'] = $post_data['amount_total'];
                $sales_invoice_main_fields = array('your_invoice_no' => 'Your Invoice No.','shipment_invoice_no' => 'Shipment Invoice No.', 'shipment_invoice_date' => 'Shipment Invoice Date' , 'sbill_no' => 'S/Bill No.',  'sbill_date' => 'S/Bill Date' , 'origin_port' => 'Origin Port' , 'port_of_discharge' => 'Port of Discharge', 'container_size' => 'Container Size', 'container_bill_no' => 'Container Bill No', 'container_date' => 'Container Date' , 'container_no' => 'Container No.' , 'vessel_name_voy' => 'Vessel Name / Voy' ,  'print_date' => 'Print Date');
		$main_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE setting_key != "display_dollar_sign" AND company_id = "'.$this->logged_in_id.'" AND module_name = 1 AND setting_value = 1');
                $invoice_main_fields = array();
                if(!empty($main_fields)) {
                    foreach ($main_fields as $value) {
                        if (strpos($value->setting_key, 'date') !== false || strpos($value->setting_key, 'date') !== false) {
                            $post_data[$value->setting_key] = !empty($post_data[$value->setting_key]) ? date('Y-m-d', strtotime($post_data[$value->setting_key])) : null;
                        }
                        $sales_invoice_data[$value->setting_key] = $post_data[$value->setting_key];
                    }
                }

                $dateranges = $this->crud->getFromSQL('SELECT `daterange` FROM `locked_daterange` WHERE `user_id`="'.$this->logged_in_id.'" ');
                if(!empty($dateranges)){
                    foreach($dateranges as $daterange){
                        $pieces = explode(" ", $daterange->daterange);
                        $from = date('d-m-Y', strtotime($pieces[0]));
                        $to = date('d-m-Y', strtotime($pieces[2]));
                        $sales_invoice_date = date('d-m-Y', strtotime($post_data['sales_invoice_date']));
                        if(strtotime($sales_invoice_date) >= strtotime($from) && strtotime($sales_invoice_date) <= strtotime($to)){
                            $return['error'] = "Locked_Date";
                            print json_encode($return);
                            exit;
                        }
                    }    
                }//exit;
		if(isset($post_data['sales_invoice_id']) && !empty($post_data['sales_invoice_id'])){

                    $sales_invoice_no = $post_data['sales_invoice_no'];
                    $sales_invoice_prefix = isset($post_data['prefix']) ? $post_data['prefix'] : null;
                    $where = array('prefix' => $sales_invoice_prefix, 'sales_invoice_no' => $sales_invoice_no, 'created_by' => $this->logged_in_id, 'sales_invoice_id !=' => $post_data['sales_invoice_id']);
			$sales_invoice_result = $this->crud->get_row_by_id('sales_invoice', $where);
			if(!empty($sales_invoice_result) && $sales_invoice_result != $post_data['sales_invoice_id']){
				echo json_encode(array("error" => 'Exist'));
				exit;
			}

			$sales_invoice_data['prefix'] = $sales_invoice_prefix;
			$sales_invoice_data['sales_invoice_no'] = $sales_invoice_no;
			$sales_invoice_data['transport_name'] = $post_data['transport_name'];
			$sales_invoice_data['lr_no'] = $post_data['lr_no'];
			$sales_invoice_data['invoice_type'] = isset($post_data['invoice_type']) && !empty($post_data['invoice_type']) ? $post_data['invoice_type'] : null;
			$sales_invoice_data['updated_at'] = $this->now_time;
            $sales_invoice_data['updated_by'] = $this->logged_in_id;
            $sales_invoice_data['company_id'] = $this->logged_in_id;
            $sales_invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $where_array['sales_invoice_id'] = $post_data['sales_invoice_id'];
            $result = $this->crud->update('sales_invoice', $sales_invoice_data, $where_array);
			if($result){
				$return['success'] = "Updated";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Sales Invoice Updated Successfully');
				$parent_id = $post_data['sales_invoice_id'];
				if(isset($post_data['deleted_lineitem_id']) && !empty($post_data['deleted_lineitem_id'])){
					$deleted_lineitem_ids = $post_data['deleted_lineitem_id'];

					$lineitems_res = $this->crud->get_where_in_result('lineitems', 'id', $deleted_lineitem_ids);
                    if(!empty($lineitems_res)) {
                        foreach ($lineitems_res as $key => $lineitem) {
                            $this->crud->update_item_current_stock_qty($lineitem->item_id,$lineitem->parent_id,'sales',$lineitem->item_qty,'delete');       
                        }
                    }

					$this->crud->delete_where_in('lineitems', 'id', $deleted_lineitem_ids);
				}
				foreach($line_items_data[0] as $lineitem){
					$add_lineitem = array();
                    $add_lineitem['item_group_id'] = isset($lineitem->item_group_id) ? $lineitem->item_group_id : null;
					$add_lineitem['cat_id'] = isset($lineitem->cat_id) ? $lineitem->cat_id : NULL;
					$add_lineitem['sub_cat_id'] = isset($lineitem->sub_cat_id) ? $lineitem->sub_cat_id : NULL;
					$add_lineitem['item_id'] = $lineitem->item_id;
					$add_lineitem['item_qty'] = $lineitem->item_qty;
					$add_lineitem['unit_id'] = $lineitem->unit_id;
					$add_lineitem['price'] = $lineitem->price;
					$add_lineitem['pure_amount'] = $lineitem->pure_amount;
					$add_lineitem['discount_type'] = $lineitem->discount_type;
					$add_lineitem['discount'] = $lineitem->discount;
					$add_lineitem['discounted_price'] = $lineitem->discounted_price;
					$add_lineitem['cgst'] = $lineitem->cgst;
					$add_lineitem['cgst_amount'] = $lineitem->cgst_amt;
					$add_lineitem['sgst'] = $lineitem->sgst;
					$add_lineitem['sgst_amount'] = $lineitem->sgst_amt;
					$add_lineitem['igst'] = $lineitem->igst;
					$add_lineitem['igst_amount'] = $lineitem->igst_amt;
					$add_lineitem['other_charges'] = $lineitem->other_charges;
					$add_lineitem['amount'] = $lineitem->amount;
					$add_lineitem['module'] = 2;
					$add_lineitem['parent_id'] = $parent_id;
					$add_lineitem['rate_for_itax'] = $lineitem->rate_for_itax;
					$add_lineitem['price_for_itax'] = $lineitem->price_for_itax;
					$add_lineitem['igst_for_itax'] = $lineitem->igst_for_itax;
					$add_lineitem['note'] = $lineitem->note;
					if(isset($lineitem->id) && !empty($lineitem->id)){
						$this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'sales',$lineitem->item_qty,'update');

						$add_lineitem['updated_at'] = $this->now_time;
						$add_lineitem['updated_by'] = $this->logged_in_id;
						$add_lineitem['company_id'] = $this->logged_in_id;
						$add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$where_id['id'] = $lineitem->id;
						$this->crud->update('lineitems', $add_lineitem, $where_id);
					} else {
						$this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'sales',$lineitem->item_qty,'add');

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
			}
			
		} 
		else {
                        $sales_invoice_no = $post_data['sales_invoice_no'];
                        $sales_invoice_prefix = $post_data['prefix'];
			$where = array('prefix' => $sales_invoice_prefix, 'sales_invoice_no' => $sales_invoice_no, 'created_by' => $this->logged_in_id);
			$sales_invoice_result = $this->crud->get_row_by_id('sales_invoice', $where);
                        //print_r($sales_invoice_result);die;
			if(!empty($sales_invoice_result)){
                                echo json_encode(array("error" => 'Exist'));
				exit;
			}
            
                        $sales_invoice_data['sales_invoice_no'] = $sales_invoice_no;
                        $sales_invoice_data['prefix'] = $sales_invoice_prefix;
                        $sales_invoice_data['transport_name'] = $post_data['transport_name'];
			$sales_invoice_data['lr_no'] = $post_data['lr_no'];
			$sales_invoice_data['invoice_type'] = isset($post_data['invoice_type']) ? $post_data['invoice_type'] : null;
			$sales_invoice_data['created_at'] = $this->now_time;
			$sales_invoice_data['created_by'] = $this->logged_in_id;
			$sales_invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$sales_invoice_data['updated_at'] = $this->now_time;
			$sales_invoice_data['updated_by'] = $this->logged_in_id;
			$sales_invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			
			$result = $this->crud->insert('sales_invoice',$sales_invoice_data);

			

			if($result){
				$return['success'] = "Added";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Sales Invoice Added Successfully');
				$parent_id = $this->db->insert_id();


				$company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date'));
		        if(!empty($company_settings_id)) {
		        	$this->crud->update('company_settings',array("setting_value" => $sales_invoice_data['sales_invoice_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
		        } else {
		        	$this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'sales_invoice_date',"setting_value" => $sales_invoice_data['sales_invoice_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
		        }

				

                                $return['is_print'] = $post_data['is_print'];
                                if($post_data['is_print'] == 1) {
                                    $return['invoice_id'] = $parent_id;
                                }
                //echo '<pre>';print_r($line_items_data);exit;
				foreach($line_items_data[0] as $lineitem){
					$add_lineitem = array();
                                        $add_lineitem['item_group_id'] = isset($lineitem->item_group_id) ? $lineitem->item_group_id : null;
					$add_lineitem['cat_id'] = isset($lineitem->cat_id) ? $lineitem->cat_id : NULL;
					$add_lineitem['sub_cat_id'] = isset($lineitem->sub_cat_id) ? $lineitem->sub_cat_id : NULL;
					$add_lineitem['item_id'] = $lineitem->item_id;
					$add_lineitem['item_qty'] = $lineitem->item_qty;
					$add_lineitem['unit_id'] = $lineitem->unit_id;
					$add_lineitem['price'] = $lineitem->price;
					$add_lineitem['pure_amount'] = $lineitem->pure_amount;
					$add_lineitem['discount_type'] = $lineitem->discount_type;
					$add_lineitem['discount'] = $lineitem->discount;
					$add_lineitem['discounted_price'] = $lineitem->discounted_price;
					$add_lineitem['cgst'] = $lineitem->cgst;
					$add_lineitem['cgst_amount'] = $lineitem->cgst_amt;
					$add_lineitem['sgst'] = $lineitem->sgst;
					$add_lineitem['sgst_amount'] = $lineitem->sgst_amt;
					$add_lineitem['igst'] = $lineitem->igst;
					$add_lineitem['igst_amount'] = $lineitem->igst_amt;
					$add_lineitem['other_charges'] = $lineitem->other_charges;
					$add_lineitem['amount'] = $lineitem->amount;
					$add_lineitem['module'] = 2;
					$add_lineitem['parent_id'] = $parent_id;
					$add_lineitem['rate_for_itax'] = $lineitem->rate_for_itax;
					$add_lineitem['price_for_itax'] = $lineitem->price_for_itax;
					$add_lineitem['igst_for_itax'] = $lineitem->igst_for_itax;
					$add_lineitem['note'] = $lineitem->note;
					$add_lineitem['created_at'] = $this->now_time;
					$add_lineitem['updated_at'] = $this->now_time;
					$add_lineitem['updated_by'] = $this->logged_in_id;
					$add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
					$add_lineitem['created_by'] = $this->logged_in_id;
					$add_lineitem['company_id'] = $this->logged_in_id;
					$add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
					$this->crud->insert('lineitems',$add_lineitem);

					$this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'sales',$lineitem->item_qty,'add');
				}
			}
		}
		print json_encode($return);
		exit;
	}
	
	function invoice_list($id=null){
		/*echo "welcome";die();*/
		if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) {
			$data = array();
            if(isset($id) && $id == 2){
                $data=['list_type'=>2];
            } else if (isset($id) && $id == 3){
                $data=['list_type'=>3];
            }
			set_page('sales/invoice/invoice_list', $data);
		} else {
			redirect('/');
		}
	}
	
	function invoice_datatable(){
		/*echo "welcome";die();*/
		$from_date = '';
        $to_date = '';
        $account_id = '';
        $list_type ='';
        if(isset($_POST['list_type']) && $_POST['list_type'] != '')
        {
            if (isset($_POST['list_type']) && $_POST['list_type'] != '') {
                $list_type = $_POST['list_type'];
            }else {
                $list_type = 1;
            }
        }
        if( isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])){
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4).'-'.substr($from_date, 3, 2).'-'.substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4).'-'.substr($to_date, 3, 2).'-'.substr($to_date, 0, 2);
        }
        if(isset($_POST['account_id'])){
            $account_id = $_POST['account_id'];
        }
        
		$config['table'] = 'sales_invoice si';
		$config['select'] = 'pd.transaction_id,si.invoice_type, si.sales_invoice_id, si.sales_invoice_no, si.sales_invoice_date, si.amount_total, si.data_lock_unlock, a.account_name, a.account_group_id';
		$config['column_order'] = array(null, 'si.sales_invoice_no', 'a.account_name', 'si.sales_invoice_date', 'si.amount_total');
		$config['column_search'] = array('si.sales_invoice_no', 'a.account_name', 'DATE_FORMAT(si.sales_invoice_date,"%d-%m-%Y")', 'si.amount_total');
		$config['wheres'][] = array('column_name' => 'si.created_by', 'column_value' => $this->logged_in_id);
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'si.account_id', 'column_value' => $account_id);
        }
        if(!empty($list_type))
        {
            $config['wheres'][] = array('column_name' => 'si.sales_type', 'column_value' => $list_type);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'si.sales_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'si.sales_invoice_date <=', 'column_value' => $to_date);
        }
		$config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = si.account_id', 'join_type' => 'left');
		$config['joins'][] = array('join_table' => 'invoice_paid_details pd', 'join_by' => 'pd.invoice_id = si.sales_invoice_id', 'join_type' => 'left');
		$config['order'] = array('si.created_at' => 'desc');
		
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();

		$isEdit = $this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "edit");
		$isDelete = $this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "delete");

		$data = array();
		foreach ($list as $invoice) {
			$row = array();
			$action = '';

			if($invoice->data_lock_unlock == 0){
				if($isEdit) {
                    $tt="";
                    if($list_type==1)
                    {
                        $tt="sales";
                    }elseif($list_type==2)
                    {
                        $tt="sales2";
                    }
					if($this->is_single_line_item == 1) {
						$action .= '<form id="edit_' . $invoice->sales_invoice_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/'.$tt.'" style="width: 25px; display: initial;" >
	                            <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $invoice->sales_invoice_id . '">
	                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $invoice->sales_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
	                        </form> ';
					} else {
						$action .= '<form id="edit_' . $invoice->sales_invoice_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/'.$tt.'" style="width: 25px; display: initial;" >
	                            <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $invoice->sales_invoice_id . '">
	                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $invoice->sales_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
	                        </form> ';
					}
				}
				if($isDelete) {
					$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('sales/invoice_delete/' . $invoice->sales_invoice_id) . '"><i class="fa fa-trash"></i></a>';		
				}

				$action_detail = ' &nbsp; <form id="detail_' . $invoice->sales_invoice_id . '" method="post" action="' . base_url() . 'sales/invoice_detail" style="width: 25px; display: initial;" >
                            <input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="' . $invoice->sales_invoice_id . '">
                            <a class="detail_button btn-info btn-xs" href="javascript:{}" onclick="document.getElementById(\'detail_' . $invoice->sales_invoice_id . '\').submit();" title="Invoice Detail"><i class="fa fa-eye"></i></a>
                        </form>  &nbsp; ';
            }

            $action .= '&nbsp;<a href="' . base_url('sales/invoice_print_pdf/' . $invoice->sales_invoice_id) . '" target="_blank" title="Invoice Print" class="detail_button btn-info btn-xs"><span class="fa fa-print"></span></a>';

            if($invoice->account_group_id != CASH_IN_HAND_ACC_GROUP_ID) {
            	$action .= '&nbsp;<a href="' . base_url('sales/format_2_invoice_print/' . $invoice->sales_invoice_id) . '" target="_blank" title="Formamt 2 Invoice Print" class="detail_button btn-info btn-xs"><span class="fa fa-print"></span></a>';

            	$action .= '&nbsp;<a href="' . base_url('sales/format_3_2_invoice_print/' . $invoice->sales_invoice_id) . '" target="_blank" title="Formamt 3 Invoice Print" class="detail_button btn-info btn-xs"><span class="fa fa-print"></span></a>';
            }
            
            $action .= '&nbsp;<a href="' . base_url('sales/invoice_print_new_pdf/' . $invoice->sales_invoice_id) . '" target="_blank" title="Tally Invoice Print" class="detail_button btn-info btn-xs"><span class="fa fa-print"></span></a>';

            if($invoice->account_group_id == CASH_IN_HAND_ACC_GROUP_ID) {
            	$action .= '&nbsp;<a href="' . base_url('sales/invoice_miracle_print/' . $invoice->sales_invoice_id) . '" target="_blank" title="Miracle Invoice Print" class="detail_button btn-info btn-xs"><span class="fa fa-print"></span></a>';
            }       
            $action .= ' &nbsp; <input type="checkbox" name="invoice_ids[]" value="'.$invoice->sales_invoice_id.'" style="height:17px;width: 17px;">';
            /*if(!empty($invoice->invoice_type)){
                if($invoice->invoice_type == INVOICE_TYPE_FREIGHT_ID || $invoice->invoice_type == INVOICE_TYPE_INVOICE_ID || $invoice->invoice_type == INVOICE_TYPE_REIMBURSEMENT_ID){
                    $action .= ' &nbsp; <a href="' . base_url('sales/invoice_pdf_new/' . $invoice->sales_invoice_id) . '" target="_blank" title="Invoice Print" class="detail_button btn-info btn-xs" style="background-color: #0f9abc;"><span class="fa fa-print"></span></a>';
                }
            }*/
    		$row[] = $action;
                $this->load->library('applib');
                $sales_invoice_no = $this->applib->format_invoice_number($invoice->sales_invoice_id, $invoice->sales_invoice_date);
                $row[] = $sales_invoice_no;
                if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise'] == '1'){
                    if(isset($invoice->transaction_id)){
                        $row[] = 'Paid';
                    } else {
                        $row[] = 'Unpaid';
                    }
                }
                $row[] = $invoice->account_name;
                $row[] = date('d-m-Y', strtotime($invoice->sales_invoice_date));
                $row[] = number_format($invoice->amount_total, 2, '.', '');
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
	
    function invoice_delete($sales_invoice_id) {
        
        $this->crud->delete('stock_status_change', array('tr_type' => 2, 'tr_id' => $sales_invoice_id));
        
        $this->crud->kasar_entry(0, $sales_invoice_id, 'sales', 0, 'delete');

        $lineitems_res = $this->crud->get_row_by_id('lineitems', array('module' => 2, 'parent_id' => $sales_invoice_id));
        if (!empty($lineitems_res)) {
            foreach ($lineitems_res as $key => $lineitem) {
                $this->crud->update_item_current_stock_qty($lineitem->item_id, $lineitem->parent_id, 'sales', $lineitem->item_qty, 'delete');
            }
            }

        $this->crud->delete('sales_invoice', array('sales_invoice_id' => $sales_invoice_id));
        $this->crud->delete('lineitems', array('module' => 2, 'parent_id' => $sales_invoice_id));
    }

    function invoice_print_pdf($sales_invoice_id, $is_multiple = '') {
        if (!empty($sales_invoice_id) && isset($sales_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
            $this->load->library('numbertowords');
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total);
            }
            $this->load->library('applib');
            $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
            $data = array(
                'sales_invoice_id' => $result->sales_invoice_id,
                'sales_invoice_no' => $sales_invoice_no,
                'prefix' => $result->prefix,
                'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
                'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => $result->amount_total,
                'amount_total_word' => $amount_total_word,
                'shipment_invoice_no' => $result->shipment_invoice_no,
                'shipment_invoice_date' => !empty($result->shipment_invoice_date) ? date('d-m-Y', strtotime($result->shipment_invoice_date)) : '',
                'sbill_no' => $result->sbill_no,
                'sbill_date' => !empty($result->sbill_date) ? date('d-m-Y', strtotime($result->sbill_date)) : '',
                'origin_port' => $result->origin_port,
                'port_of_discharge' => $result->port_of_discharge,
                'container_size' => $result->container_size,
                'container_bill_no' => $result->container_bill_no,
                'container_date' => $result->container_date,
                'container_no' => $result->container_no,
                'vessel_name_voy' => $result->vessel_name_voy,
            );
            $sales_invoice_data = $result;
            $data['sales_invoice_data'] = $sales_invoice_data;
            $data['user_name'] = $user_detail->user_name;
            $data['user_address'] = $user_detail->address;
            $data['user_gst_no'] = $user_detail->gst_no;
            $data['user_country'] = 'India';
            $data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
            $data['user_pan'] = $user_detail->pan;
            $data['user_postal_code'] = $user_detail->postal_code;
            $data['user_phone'] = $user_detail->phone;
            $data['email_ids'] = $user_detail->email_ids;
            $data['logo_image'] = $user_detail->logo_image;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_letter_pad'];

            $data['account_name'] = $account_detail->account_name;
            $cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
            if ($cash_in_hand_acc == true && !empty($result->cash_customer)) {
                $data['account_name'] = $result->cash_customer;
            }

            $data['account_gst_no'] = $account_detail->account_gst_no;
            $data['billing_address'] = $account_detail->account_address;
            if (!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1') {
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
            $data['account_country'] = 'India';
            $data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
            $data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
            $data['account_postal_code'] = $account_detail->account_postal_code;
            $data['account_pan'] = $account_detail->account_pan;
            $data['package_name'] = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'package_name'));
            $data['login_logo'] = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'login_logo'));

            $lineitem_arr = array();
            $where = array('module' => '2', 'parent_id' => $sales_invoice_id);
            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
            foreach ($sales_invoice_lineitems as $sales_invoice_lineitem) {
                $sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);

                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if (!empty($hsn_id)) {
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }

                //$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $sales_invoice_lineitem;
            }
            $data['lineitems'] = $lineitem_arr;
        } else {
            redirect($_SERVER['HTTP_REFERER']);
            $data = array();
        }
        $main_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "' . $this->logged_in_id . '" AND module_name = 1 AND setting_value = 1');
        $invoice_main_fields = array();
        if (!empty($main_fields)) {
            foreach ($main_fields as $value) {
                $invoice_main_fields[] = $value->setting_key;
            }
        }
        $data['invoice_main_fields'] = $invoice_main_fields;

        $line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "' . $this->logged_in_id . '" AND module_name = 2 AND setting_value = 1');
        $invoice_line_item_fields = array();
        if (!empty($line_item_fields)) {
            foreach ($line_item_fields as $value) {
                $invoice_line_item_fields[] = $value->setting_key;
            }
        }
        $data['invoice_line_item_fields'] = $invoice_line_item_fields;
        $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
        $data['letterpad_print'] = $letterpad_print;
//		echo '<pre>'; print_r($data); exit;
        $html_header = $this->load->view('sales/invoice/pdf_header', $data, true);
        $html = $this->load->view('sales/invoice/pdf', $data, true);
        //echo '<pre>'; print_r($html); exit;
        $pdfFilePath = "sales_invoice.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                5, // margin right
                85, // margin top
                15, // margin bottom
                5, // margin header
                5); // margin footer

        /* $this->m_pdf->pdf->SetHTMLHeader('<div style="text-align:left; font-weight: bold;"><h2 style="color:red;">Ajanta</h2></div>
          <div style="text-align:right; font-weight: bold;">Ajanta</div>
          ','O'); */
        $this->m_pdf->pdf->SetHTMLHeader($html_header, 'OE', true);
        //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

        if (in_array('print_date', $invoice_main_fields)) {
            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
        } else {
            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
        }

        //$this->m_pdf->pdf->WriteHTML(file_get_contents(base_url().'assets/bootstrap/css/bootstrap.min.css'), 1);
        $this->m_pdf->pdf->WriteHTML($html);
        if (empty($is_multiple)) {
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
    }

    function invoice_print_new_pdf($sales_invoice_id,$is_multiple = '') {
        if (!empty($sales_invoice_id) && isset($sales_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
//            echo "<pre>"; print_r($result); exit;
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
            $this->load->library('numbertowords');
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total);
            }
            $total_amt = $result->pure_amount_total - $result->discount_total;
            if ($total_amt < 0) {
                $amount_total_amt_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_amt)) . ' Only';
            } else {
                $amount_total_amt_word = $this->numbertowords->convert_number($total_amt) . ' Only';
            }

            $this->load->library('applib');
            $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
            $data = array(
                'sales_invoice_id' => $result->sales_invoice_id,
                'sales_invoice_no' => $sales_invoice_no,
                'prefix' => $result->prefix,
                'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
                'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => $result->amount_total,
                'amount_total_word' => $amount_total_word,
                'amount_total_amt_word' => $amount_total_amt_word,
            );
            $data['sales_invoice_data'] = $result;
            $data['user_name'] = $user_detail->user_name;
            $data['user_address'] = $user_detail->address;
            $data['user_gst_no'] = $user_detail->gst_no;
            $data['user_country'] = 'India';
            $data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
            $data['user_pan'] = $user_detail->pan;
            $data['user_postal_code'] = $user_detail->postal_code;
            $data['user_phone'] = $user_detail->phone;
            $data['email_ids'] = $user_detail->email_ids;
            $data['logo_image'] = $user_detail->logo_image;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_letter_pad'];

            $data['account_name'] = $account_detail->account_name;
            $cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
            if ($cash_in_hand_acc == true && !empty($result->cash_customer)) {
                $data['account_name'] = $result->cash_customer;
            }

            $data['account_gst_no'] = $account_detail->account_gst_no;
            if (!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1') {
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
            $data['account_country'] = 'India';
            $data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
            $data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
            $data['account_postal_code'] = $account_detail->account_postal_code;
            $data['account_pan'] = $account_detail->account_pan;

            $lineitem_arr = array();
            $where = array('module' => '2', 'parent_id' => $sales_invoice_id);
            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
//            echo "<pre>"; print_r($sales_invoice_lineitems); exit;
            foreach ($sales_invoice_lineitems as $sales_invoice_lineitem) {
                $sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);

                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if (!empty($hsn_id)) {
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }

                $sales_invoice_lineitem->rate_unit = $this->crud->get_id_by_val('pack_unit', 'pack_unit_name', 'pack_unit_id', $sales_invoice_lineitem->unit_id);

                //$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $sales_invoice_lineitem;
            }
            $data['lineitems'] = $lineitem_arr;
        } else {
            redirect($_SERVER['HTTP_REFERER']);
            $data = array();
        }
        //~ echo '<pre>'; print_r($data); exit;
        $html = $this->load->view('sales/invoice/pdf_new', $data, true);
        $pdfFilePath = "sales_invoice.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                5, // margin right
                5, // margin top
                15, // margin bottom
                5, // margin header
                5); // margin footer

        /* $this->m_pdf->pdf->SetHTMLHeader('<div style="text-align:left; font-weight: bold;"><h2 style="color:red;">Ajanta</h2></div>
          <div style="text-align:right; font-weight: bold;">Ajanta</div>
          ','O'); */
        /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
          <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
          <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
          <td width="33%" style="text-align:right; ">My document</td></tr>
          </table>','O'); */
        //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

        $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
		<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
		<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
		<td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
        //$this->m_pdf->pdf->WriteHTML(file_get_contents(base_url().'assets/bootstrap/css/bootstrap.min.css'), 1);
        $this->m_pdf->pdf->WriteHTML($html);
        if (empty($is_multiple)) {
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
    }

    function invoice_miracle_print($sales_invoice_id){
		if(!empty($sales_invoice_id) && isset($sales_invoice_id)){
			$result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);
			$user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
			$account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
			$this->load->library('numbertowords');
			if($result->amount_total < 0){
				$amount_total_word = 'Minus '.$this->numbertowords->convert_number(abs($result->amount_total));
			} else {
				$amount_total_word = $this->numbertowords->convert_number($result->amount_total);
			}
                        $this->load->library('applib');
                        $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
			$data = array(
				'sales_invoice_id' => $result->sales_invoice_id,
				'sales_invoice_no' => $sales_invoice_no,
                'prefix' => $result->prefix,
				'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
				'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
				'qty_total' => $result->qty_total,
				'pure_amount_total' => $result->pure_amount_total,
				'discount_total' => $result->discount_total,
				'cgst_amount_total' => $result->cgst_amount_total,
				'sgst_amount_total' => $result->sgst_amount_total,
				'igst_amount_total' => $result->igst_amount_total,
				'amount_total' => $result->amount_total,
				'amount_total_word' => $amount_total_word,
			);
			$data['sales_invoice_data'] = $result;
			$data['user_name'] = $user_detail->user_name;
			$data['user_address'] = $user_detail->address;
			$data['user_gst_no'] = $user_detail->gst_no;
			$data['user_country'] = 'India';
			$usr_city = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $user_detail->city);
			$data['user_city'] = strtolower($usr_city);
			$data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
			$data['user_pan'] = $user_detail->pan;
			$data['user_postal_code'] = $user_detail->postal_code;
			$data['user_phone'] = $user_detail->phone;
			$data['email_ids'] = $user_detail->email_ids;
			$data['logo_image'] = $user_detail->logo_image;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_letter_pad'];
			
			$data['account_name'] = $account_detail->account_name;
			$cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
			if($cash_in_hand_acc == true && !empty($result->cash_customer)) {
				$data['account_name'] = $result->cash_customer;
			}
			$data['account_gst_no'] = $account_detail->account_gst_no;
            if(!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1'){
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
			$data['account_country'] = 'India';
			$data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
			$data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
			$data['account_postal_code'] = $account_detail->account_postal_code;
			$data['account_pan'] = $account_detail->account_pan;
			
			$lineitem_arr = array();
			$where = array('module' => '2', 'parent_id' => $sales_invoice_id);
			$sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
            
			foreach($sales_invoice_lineitems as $sales_invoice_lineitem){
				$sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);
				$hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if(!empty($hsn_id)){
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }
				
				//$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
				$sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
				$sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
				$sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
				$lineitem_arr[] = $sales_invoice_lineitem;
			}
			$data['lineitems'] = $lineitem_arr;
//            echo '<pre>'; print_r($lineitem_arr); exit;
			
		} else {
			redirect($_SERVER['HTTP_REFERER']);
			$data = array();
		}
                $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
                $data['letterpad_print'] = $letterpad_print;
		//~ echo '<pre>'; print_r($data); exit;
		$html = $this->load->view('sales/invoice/invoice_miracle_print', $data, true);
		$pdfFilePath = "sales_invoice_miracle_print.pdf";
		$this->load->library('m_pdf');
		$this->m_pdf->pdf->AddPage('','', '', '', '',
					  5, // margin_left
					  5, // margin right
					  5, // margin top
					  15, // margin bottom
					  5, // margin header
					  5); // margin footer
		
		/*$this->m_pdf->pdf->SetHTMLHeader('<div style="text-align:left; font-weight: bold;"><h2 style="color:red;">Ajanta</h2></div>
		<div style="text-align:right; font-weight: bold;">Ajanta</div>
		','O');*/
		/*$this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
		<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
		<td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
		<td width="33%" style="text-align:right; ">My document</td></tr>
		</table>','O');*/
		//$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

		$this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
		<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
		<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
		<td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
		//$this->m_pdf->pdf->WriteHTML(file_get_contents(base_url().'assets/bootstrap/css/bootstrap.min.css'), 1);
		$this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($pdfFilePath, 'I');
	}

    function format_2_invoice_print($sales_invoice_id, $is_multiple = '') {
        if (!empty($sales_invoice_id) && isset($sales_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
//            echo "<pre>"; print_r($user_detail); exit;
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
//            echo "<pre>"; print_r($account_detail); exit;
            $this->load->library('numbertowords');
            $result->amount_total = round($result->amount_total);
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total);
            }
            $total_gst = $result->cgst_amount_total + $result->sgst_amount_total + $result->igst_amount_total;
            if ($total_gst < 0) {
                $gst_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_gst));
            } else {
                $gst_total_word = $this->numbertowords->convert_number($total_gst);
            }
            $this->load->library('applib');
            $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
            $data = array(
                'sales_invoice_id' => $result->sales_invoice_id,
                'sales_invoice_no' => $sales_invoice_no,
                'prefix' => '',
                'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
                'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => $result->amount_total,
                'amount_total_word' => $amount_total_word,
                'gst_total_word' => $gst_total_word,
            );
            $data['sales_invoice_data'] = $result;
            $data['user_name'] = $user_detail->user_name;
            $data['user_address'] = $user_detail->address;
            $data['user_gst_no'] = $user_detail->gst_no;
            $data['user_country'] = 'India';
            $usr_city = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $user_detail->city);
            $data['user_city'] = $usr_city;
            $data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
            $data['user_pan'] = $user_detail->pan;
            $data['user_postal_code'] = $user_detail->postal_code;
            $data['user_phone'] = $user_detail->phone;
            $data['email_ids'] = $user_detail->email_ids;
            $data['logo_image'] = $user_detail->logo_image;
            $data['bank_name'] = $user_detail->bank_name;
            $data['bank_branch'] = $user_detail->bank_branch;
            $data['bank_ac_no'] = $user_detail->bank_ac_no;
            $data['rtgs_ifsc_code'] = $user_detail->rtgs_ifsc_code;
            $data['bank_acc_name'] = $user_detail->bank_acc_name;
            $data['bank_city'] = $user_detail->bank_city;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_letter_pad'];

            $data['account_name'] = $account_detail->account_name;
            $cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
            if ($cash_in_hand_acc == true && !empty($result->cash_customer)) {
                $data['account_name'] = $result->cash_customer;
            }
            $data['account_gst_no'] = $account_detail->account_gst_no;
            if (!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1') {
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
            $data['account_phone'] = $account_detail->account_phone;
            $data['account_country'] = 'India';
            $data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
            $data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
            $data['account_postal_code'] = $account_detail->account_postal_code;
            $data['account_pan'] = $account_detail->account_pan;
            $data['transport_name'] = $result->transport_name;
            $data['lr_no'] = $result->lr_no;

            $lineitem_arr = array();
            $where = array('module' => '2', 'parent_id' => $sales_invoice_id);
            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);

            foreach ($sales_invoice_lineitems as $key => $sales_invoice_lineitem) {
                $sales_invoice_lineitem->index = $key + 1;
                $sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);
                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if (!empty($hsn_id)) {
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }
                $sales_invoice_lineitem->rate_unit = $this->crud->get_id_by_val('pack_unit', 'pack_unit_name', 'pack_unit_id', $sales_invoice_lineitem->unit_id);
                //$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $sales_invoice_lineitem;
            }

            $data['lineitems'] = $lineitem_arr;
            $tax_items = array();
            foreach ($lineitem_arr as $lineitem) {
                $tax_items[$lineitem->hsn_code][] = $lineitem;
            }
            $tax_line_items = array();
            foreach ($tax_items as $tax_item) {
                $gst_array = array('taxable_value' => 0, 'cgst' => 0, 'sgst' => 0, 'igst' => 0);
                foreach ($tax_item as $item) {
                    if (!empty($item->hsn_code)) {
                        $gst_array['hsn_code'] = $item->hsn_code;
                    } else {
                        $gst_array['hsn_code'] = '';
                    }
                    $gst_array['taxable_value'] = $gst_array['taxable_value'] + $item->discounted_price;
                    $gst_array['cgst'] = $gst_array['cgst'] + $item->cgst_amount;
                    $gst_array['cgst_rate'] = $item->cgst;
                    $gst_array['sgst'] = $gst_array['sgst'] + $item->sgst_amount;
                    $gst_array['sgst_rate'] = $item->sgst;
                    $gst_array['igst'] = $gst_array['igst'] + $item->igst_amount;
                    $gst_array['igst_rate'] = $item->igst;
                }
                $tax_line_items[] = $gst_array;
            }

            $data['tax_line_items'] = $tax_line_items;
//            echo '<pre>'; print_r($lineitem_arr); exit;
        } else {
            redirect($_SERVER['HTTP_REFERER']);
            $data = array();
        }
        $round_off = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'round_off_apply'));
        if (!empty($round_off)) {
            $data['round_off'] = '1';
        } else {
            $data['round_off'] = '0';
        }
        $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
        $data['letterpad_print'] = $letterpad_print;
        $html_header = $this->load->view('sales/invoice/profit_miracle_print_header', $data, true);

        $pdfFilePath = "sales_invoice_miracle_print.pdf";
        $this->load->library('m_pdf');
        
        if (count($lineitem_arr) < 15) {
            $html = $this->load->view('sales/invoice/profit_miracle_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    80, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            $this->m_pdf->pdf->SetHTMLHeader($html_header, 'OE', true);
	        $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
			<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
			<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
			<td width="33%" style="text-align: right; "></td></tr></table>');
            $this->m_pdf->pdf->WriteHTML($html);
        } else {
            $discounted_price_total = 0;
            $other_charges = 0;
            $total_qty = 0;
            foreach ($data['lineitems'] as $lineitem) {
                $discounted_price_total += $lineitem->discounted_price;
                $other_charges += $lineitem->other_charges;
                $total_qty += $lineitem->item_qty;
            }
            $tmp_data_res = array_chunk($data['lineitems'], 30);
            foreach ($tmp_data_res as $key => $tmp_data_row) {
                $data['lineitems'] = $tmp_data_row;
                if (count($tmp_data_res) == ($key + 1)) {
                    $data['is_last_page'] = true;
                    $data['discounted_price_total'] = $discounted_price_total;
                    $data['other_charges'] = $other_charges;
                    $data['total_qty'] = $total_qty;
                } else {
                    $data['is_last_page'] = false;
                }
                $html = $this->load->view('sales/invoice/profit_miracle_print_items', $data, true);
                $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                        5, // margin right
                        80, // margin top
                        15, // margin bottom
                        5, // margin header
                        5); // margin footer

                $this->m_pdf->pdf->SetHTMLHeader($html_header, 'OE', true);
        		$this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
					<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
					<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
					<td width="33%" style="text-align: right; "></td></tr></table>');
        		
                $this->m_pdf->pdf->WriteHTML($html);
            }


            $total_taxable_value = 0;
            $total_cgst_value = 0;
            $total_sgst_value = 0;
            $total_igst_value = 0;
            foreach ($data['tax_line_items'] as $line_items) {
                $total_taxable_value += $line_items['taxable_value'];
                $total_cgst_value += $line_items['cgst'];
                $total_sgst_value += $line_items['sgst'];
                $total_igst_value += $line_items['igst'];
            }
            $tmp_data_res = array_chunk($data['tax_line_items'], 30);
            foreach ($tmp_data_res as $key => $tmp_data_row) {
                $data['tax_line_items'] = $tmp_data_row;

                if (count($tmp_data_res) == ($key + 1)) {
                    $data['is_last_page'] = true;
                    $data['total_taxable_value'] = $total_taxable_value;
                    $data['total_cgst_value'] = $total_cgst_value;
                    $data['total_sgst_value'] = $total_sgst_value;
                    $data['total_igst_value'] = $total_igst_value;
                } else {
                    $data['is_last_page'] = false;
                }
                $html = $this->load->view('sales/invoice/profit_miracle_print_hsn', $data, true);
                $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                        5, // margin right
                        80, // margin top
                        15, // margin bottom
                        5, // margin header
                        5); // margin footer
                $this->m_pdf->pdf->WriteHTML($html);
            }
        }
        if(empty($is_multiple)){
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
    }

    function format_3_invoice_print($sales_invoice_id, $is_multiple = '') {
        if (!empty($sales_invoice_id) && isset($sales_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);            
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
            $this->load->library('numbertowords');
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total);
            }
            $total_gst = $result->cgst_amount_total + $result->sgst_amount_total + $result->igst_amount_total;
			//$total_gst = $result->gst;
            if ($total_gst < 0) {
                $gst_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_gst));
            } else {
                $gst_total_word = $this->numbertowords->convert_number($total_gst);
            }
            $print_type = $result->print_type;
            $this->load->library('applib');
            $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
            $data = array(
                'sales_invoice_id' => $result->sales_invoice_id,
                'sales_invoice_no' => $sales_invoice_no,
                'prefix' => $result->prefix,
                'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
                'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => $result->amount_total,
                'amount_total_word' => $amount_total_word,
                'gst_total_word' => $gst_total_word,
            );
            $data['sales_invoice_data'] = $result;
            $data['user_name'] = $user_detail->user_name;
            $data['user_address'] = $user_detail->address;
            $data['user_gst_no'] = $user_detail->gst_no;
            $data['user_country'] = 'India';
            $usr_city = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $user_detail->city);
            $data['user_city'] = strtolower($usr_city);
            $data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
            $data['user_pan'] = $user_detail->pan;
            $data['user_postal_code'] = $user_detail->postal_code;
            $data['user_phone'] = $user_detail->phone;
            $data['email_ids'] = $user_detail->email_ids;
            $data['logo_image'] = $user_detail->logo_image;
            // $data['bank_name'] = $user_detail->bank_name;
            // $data['bank_branch'] = $user_detail->bank_branch;
            // $data['bank_ac_no'] = $user_detail->bank_ac_no;
            // $data['rtgs_ifsc_code'] = $user_detail->rtgs_ifsc_code;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_letter_pad'];

            $data['account_name'] = $account_detail->account_name;
            $cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
            if ($cash_in_hand_acc == true && !empty($result->cash_customer)) {
                $data['account_name'] = $result->cash_customer;
            }
            $data['account_gst_no'] = $account_detail->account_gst_no;
            if (!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1') {
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
            $data['account_country'] = 'India';
            $data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
            $data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
            $data['account_postal_code'] = $account_detail->account_postal_code;
            $data['account_pan'] = $account_detail->account_pan;
            $data['transport_name'] = $result->transport_name;
            $data['lr_no'] = $result->lr_no;

            $lineitem_arr = array();
            $where = array('module' => '2', 'parent_id' => $sales_invoice_id);
            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
            $total_gst = 0;

            foreach ($sales_invoice_lineitems as $key=>$sales_invoice_lineitem) {
                $sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);
                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if (!empty($hsn_id)) {
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }
                //$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $sales_invoice_lineitem;
                $amt =  $sales_invoice_lineitem->price * $sales_invoice_lineitem->item_qty;
                $gst_amount = $amt * $sales_invoice_lineitem->gst / 100;
                $total_gst += $gst_amount;
                if ($key == 0) {
                    $site_data = $this->crud->get_row_by_id('sites', array('site_id' => $sales_invoice_lineitem->site_id));
                    $data['site_name'] = $site_data[0]->site_name;
                    $data['site_address'] = $site_data[0]->site_address;
                }
            }
//            $no_arr = count($lineitem_arr);
//            if($no_arr < 10){
//                for ($i = $no_arr; $i < 10; $i++) {
//                    $lineitem_arr[$i] = array('');
//                    $lineitem_arr[$i] = (object) $lineitem_arr[$i];
//                }
//            }
            
            $data['lineitems'] = $lineitem_arr;
            $data['total_gst'] = $total_gst;
            if ($total_gst < 0) {
                $gst_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_gst));
            } else {
                $gst_total_word = $this->numbertowords->convert_number($total_gst);
            }
            $data['gst_total_word'] = $gst_total_word;

//            echo '<pre>'; print_r($lineitem_arr); exit;
        } else {
            redirect($_SERVER['HTTP_REFERER']);
            $data = array();
        }
        if (isset($data['amount_total'])) {
            $round_off = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'round_off_apply'));
            if (!empty($round_off)) {
                $amount_total_r = number_format((float) $data['amount_total'], 0, '.', '');
                $data['amount_total'] = number_format((float) $amount_total_r, 2, '.', '');
            }
        }
//		echo '<pre>'; print_r($data); exit;
        $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
        $termsdata = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'sales_terms'));
        $data['terms_data'] = $termsdata;
        $data['letterpad_print'] = $letterpad_print;
        $data['printtype'] = 0;
        $our_bank_label = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $data['sales_invoice_data']->our_bank_id));
        $data['our_bank_label'] = $our_bank_label;

        $bank_details = $this->crud->get_data_row_by_where('account', array('account_id' => $data['sales_invoice_data']->our_bank_id));
        $data['bank_name'] = isset($bank_details->bank_name) ? $bank_details->bank_name : '';
        $data['bank_branch'] = isset($bank_details->bank_branch) ? $bank_details->bank_branch : '';
        $data['bank_ac_no'] = isset($bank_details->bank_ac_no) ? $bank_details->bank_ac_no : '';
        $data['rtgs_ifsc_code'] = isset($bank_details->rtgs_ifsc_code) ? $bank_details->rtgs_ifsc_code : '';
        $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);

        $pdfFilePath = "sales_invoice_miracle_print.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                5, // margin right
                5, // margin top
                15, // margin bottom
                5, // margin header
                5); // margin footer

        /* $this->m_pdf->pdf->SetHTMLHeader('<div style="text-align:left; font-weight: bold;"><h2 style="color:red;">Ajanta</h2></div>
          <div style="text-align:right; font-weight: bold;">Ajanta</div>
          ','O'); */

        /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
          <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
          <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
          <td width="33%" style="text-align:right; ">My document</td></tr>
          </table>','O'); */
        //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

        $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
		<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
		<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
		<td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
        //$this->m_pdf->pdf->WriteHTML(file_get_contents(base_url().'assets/bootstrap/css/bootstrap.min.css'), 1);
        $this->m_pdf->pdf->WriteHTML($html);

        if ($print_type == 2) {
            $data['printtype'] = 1;
            $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
              <td width="33%" style="text-align:right; ">My document</td></tr>
              </table>','O'); */
            //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);

            $data['printtype'] = 2;
            $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
              <td width="33%" style="text-align:right; ">My document</td></tr>
              </table>','O'); */
            //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);
        }
        if ($print_type == 1) {
            $data['printtype'] = 1;
            $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
              <td width="33%" style="text-align:right; ">My document</td></tr>
              </table>','O'); */
            //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);
        }
        if(empty($is_multiple)){
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
    }

    function invoice_pdf_new($sales_invoice_id) {
        if (!empty($sales_invoice_id) && isset($sales_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
            
//            echo "<pre>"; print_r($invoice_main_fields_data); exit;
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
            $this->load->library('numbertowords');
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total);
            }
            $total_gst = $result->cgst_amount_total + $result->sgst_amount_total + $result->igst_amount_total;
            
            if ($total_gst < 0) {
                $gst_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_gst));
            } else {
                $gst_total_word = $this->numbertowords->convert_number($total_gst);
            }
            $this->load->library('applib');
            $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
            $data = array(
                'sales_invoice_id' => $result->sales_invoice_id,
                'sales_invoice_no' => $sales_invoice_no,
                'prefix' => $result->prefix,
                'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
                'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => $result->amount_total,
                'amount_total_word' => $amount_total_word,
                'gst_total_word' => $gst_total_word,
            );
            $data['user_name'] = $user_detail->user_name;
            $data['user_address'] = $user_detail->address;
            $data['user_gst_no'] = $user_detail->gst_no;
            $data['user_country'] = 'India';
            $usr_city = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $user_detail->city);
            $data['user_city'] = strtolower($usr_city);
            $data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
            $data['user_pan'] = $user_detail->pan;
            $data['user_postal_code'] = $user_detail->postal_code;
            $data['user_phone'] = $user_detail->phone;
            $data['email_ids'] = $user_detail->email_ids;
            $data['logo_image'] = $user_detail->logo_image;
            $data['bank_name'] = $user_detail->bank_name;
            $data['bank_branch'] = $user_detail->bank_branch;
            $data['bank_ac_no'] = $user_detail->bank_ac_no;
            $data['rtgs_ifsc_code'] = $user_detail->rtgs_ifsc_code;
            $data['sales_invoice_notes'] = $user_detail->sales_invoice_notes;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_letter_pad'];
            
            $data['account_name'] = $account_detail->account_name;
			$cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
			if($cash_in_hand_acc == true && !empty($result->cash_customer)) {
				$data['account_name'] = $result->cash_customer;
			}
            $data['account_gst_no'] = $account_detail->account_gst_no;
//            $data['account_address'] = $account_detail->account_address;
            if(!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1'){
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
            $data['account_country'] = 'India';
            $data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
            $data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
            $data['account_postal_code'] = $account_detail->account_postal_code;
            $data['account_pan'] = $account_detail->account_pan;
            $data['transport_name'] = $result->transport_name;
            $data['lr_no'] = $result->lr_no;
            $data['invoice_type'] = $result->invoice_type;

            $lineitem_arr = array();
            $where = array('module' => '2', 'parent_id' => $sales_invoice_id);
            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);

            foreach ($sales_invoice_lineitems as $sales_invoice_lineitem) {
                $sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);
                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if(!empty($hsn_id)){
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }

                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $sales_invoice_lineitem;
            }
            $data['lineitems'] = $lineitem_arr;
//            echo '<pre>'; print_r($lineitem_arr); exit;
        } else {
            redirect($_SERVER['HTTP_REFERER']);
            $data = array();
        }
        if (isset($data['amount_total'])) {
            $round_off = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'round_off_apply'));
            if (!empty($round_off)) {
                $amount_total_r = number_format((float) $data['amount_total'], 0, '.', '');
                $data['amount_total'] = number_format((float) $amount_total_r, 2, '.', '');
            }
        }
        $your_invoice_no = '';
        $main_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 1 AND setting_value = 1');
//        echo '<pre>'; print_r($main_fields); exit;
            $invoice_main_fields = array();
            if(!empty($main_fields)) {
                foreach ($main_fields as $value) {
                    $invoice_main_fields[] = $value->setting_key;
                }
            }
            $is_dollar_sign = 0;
            $invoice_main_fields_data = array();
            if(!empty($invoice_main_fields)){
                $is_con_bl_no = 0;
                $is_con_bl_date = 0;
                $con_bill_no = "";
                if(in_array('container_bill_no', $invoice_main_fields)){
                    $is_con_bl_no = 1;
                }
                if(in_array('container_date', $invoice_main_fields)){
                    $is_con_bl_date = 1;
                }
                if(in_array('your_invoice_no', $invoice_main_fields)){
                    $your_invoice_no = $result->your_invoice_no;
                }
                
                foreach ($invoice_main_fields as $f_key => $field){
                    if($field == 'display_dollar_sign'){
                        $is_dollar_sign = 1;
                        unset($invoice_main_fields[$f_key]);
                        continue;
                    }
                    if($field == 'your_invoice_no'){
                        unset($invoice_main_fields[$f_key]);
                    }
                    if($field == 'print_date'){
                        unset($invoice_main_fields[$f_key]);
                    }
                    if($is_con_bl_no == 1 && $is_con_bl_date == 1){
                        if($field == 'container_bill_no'){
                            $con_bill_no = $result->container_bill_no;
                            unset($invoice_main_fields[$f_key]);
                            continue;
                        }
                        if($field == 'container_date'){
                            $invoice_main_fields[$f_key] = 'BL NO / DATE';
                            $invoice_main_fields_data[] = $con_bill_no." ".(!empty($result->container_date) ? "/".date('d-m-Y', strtotime($result->container_date)) : '');
                            continue;
                        }
                    }
                    if($field == 'shipment_invoice_no'){
                        $invoice_main_fields_data[] = $result->shipment_invoice_no;
                    } else if($field == 'shipment_invoice_date'){
                        $invoice_main_fields_data[] = !empty($result->shipment_invoice_date) ? date('d-m-Y', strtotime($result->shipment_invoice_date)) : '';
                    } else if($field == 'sbill_no'){
                        $invoice_main_fields_data[] = $result->sbill_no;
                    } else if($field == 'sbill_date'){
                        $invoice_main_fields_data[] = !empty($result->sbill_date) ? date('d-m-Y', strtotime($result->sbill_date)) : '';
                    } else if($field == 'origin_port'){
                        $invoice_main_fields_data[] = $result->origin_port;
                    } else if($field == 'port_of_discharge'){
                        $invoice_main_fields_data[] = $result->port_of_discharge;
                    } else if($field == 'container_size'){
                        $invoice_main_fields_data[] = $result->container_size;
                    } else if($field == 'container_bill_no'){
                        $invoice_main_fields_data[] = $result->container_bill_no;
                    } else if($field == 'container_date'){
                        $invoice_main_fields_data[] = !empty($result->container_date) ? date('d-m-Y', strtotime($result->container_date)) : '';
                    } else if($field == 'container_no'){
                        $invoice_main_fields_data[] = $result->container_no;
                    } else if($field == 'vessel_name_voy'){
                        $invoice_main_fields_data[] = $result->vessel_name_voy;
                    }
                }
            }
            $invoice_main_fields = array_values($invoice_main_fields);
            $invoice_main_fields_data = array_values($invoice_main_fields_data);
            $data['invoice_main_fields'] = $invoice_main_fields;
            $data['invoice_main_fields_data'] = $invoice_main_fields_data;
            $data['your_invoice_no'] = $your_invoice_no;
            $data['is_dollar_sign'] = $is_dollar_sign;
        $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
        $data['letterpad_print'] = $letterpad_print;
//        echo '<pre>'; print_r($data); exit;
        $html = $this->load->view('sales/invoice/invoice_pdf_new', $data, true);

        $pdfFilePath = "sales_invoice_miracle_print.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('','', '', '', '',
					  5, // margin_left
					  5, // margin right
					  5, // margin top
					  15, // margin bottom
					  5, // margin header
					  5); // margin footer
        /*$this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
            <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
            <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
            <td width="33%" style="text-align:right; ">My document</td></tr>
            </table>', 'O');*/

//        $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
//            <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
//            <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
//            <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
        $this->m_pdf->pdf->WriteHTML($html);
        $this->m_pdf->pdf->Output($pdfFilePath, 'I');
    }

    function get_max_prefix($prefix = ''){
        $sales_invoice_no = $this->crud->get_max_number_where('sales_invoice', 'sales_invoice_no', array('created_by' => $this->logged_in_id, 'prefix' => $prefix));
        if(empty($sales_invoice_no->sales_invoice_no)){
            $sales_invoice_no = $this->crud->get_id_by_val('user', 'invoice_no_start_from', 'prefix', $this->prefix);
        } else {
            $sales_invoice_no = $sales_invoice_no->sales_invoice_no + 1;    
        }
        echo json_encode($sales_invoice_no);
        exit;
    }
    
    function discount(){
        $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
        if ($li_discount == 1) {
            $data = array();
            if(isset($_POST['discount_id'])){
            	if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"edit")) {
	            	$discount_data = $this->crud->get_row_by_id('discount', array('discount_id' => $_POST['discount_id']));
	                $discount_data = $discount_data[0];
	                $data['discount_data'] = $discount_data;
		        	set_page('sales/discount/discount', $data);;
		        } else {
		        	redirect('/');
		        }
            } else {
            	if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"add")) {
	            	$data = array();
		        	set_page('sales/discount/discount', $data);
		        } else {
		        	redirect('/');
		        }
            } 
        } else {
            redirect('/');
        }
    }
    
    function discount_new(){
//        $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
        $li_discount = 1;
        if ($li_discount == 1) {
            $data = array();
            if(isset($_POST['discount_id'])){
            	if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"edit")) {
	            	$discount_data = $this->crud->get_row_by_id('discount', array('discount_id' => $_POST['discount_id']));
	                $discount_data = $discount_data[0];
	                $data['discount_data'] = $discount_data;
		        	set_page('sales/discount/discount_new', $data);;
		        } else {
		        	redirect('/');
		        }
            } else {
            	if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"add")) {
	            	$data = array();
		        	set_page('sales/discount/discount_new', $data);
		        } else {
		        	redirect('/');
		        }
            } 
        } else {
            redirect('/');
        }
    }
    
    function item_group_discount_datatable() {
        $config['select'] = 'item_group_id, item_group_name, discount_on';
        $config['table'] = 'item_group';
        $config['column_order'] = array('item_group_name');
        $config['column_search'] = array('item_group_name');
        $config['order'] = array('item_group_name' => 'desc');
        $config['wheres'][] = array('column_name' => 'created_by', 'column_value' => $this->logged_in_id);
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        foreach ($list as $item_groups) {
            $row = array();
            $row[] = $item_groups->item_group_name;
            $row[] = '<input type="text" data-id="'.$item_groups->item_group_id.'" class="form-control num_only item_group_dis_1" value="">';
            $row[] = '<input type="text" data-id="'.$item_groups->item_group_id.'" class="form-control num_only item_group_dis_2" value="">';
            $row[] = '<input type="text" data-id="'.$item_groups->item_group_id.'" class="form-control num_only item_group_dis_rate" value="">';
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
    
    function item_category_discount_datatable() {
        $config['table'] = 'category c';
        $config['select'] = 'c.cat_id AS cat_id, c.cat_name AS cat_name';
        $config['column_order'] = array('c.cat_name');
        $config['column_search'] = array('c.cat_name');
        $config['order'] = array('c_cat_name' => 'asc');
        $config['wheres'][] = array('column_name' => 'c.created_by', 'column_value' => $this->logged_in_id);
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        foreach ($list as $categorys) {
            $row = array();
            $row[] = $categorys->cat_name;
            $row[] = '<input type="text" data-id="'.$categorys->cat_id.'" class="form-control num_only item_category_dis_1" value="">';
            $row[] = '<input type="text" data-id="'.$categorys->cat_id.'" class="form-control num_only item_category_dis_2" value="">';
            $row[] = '<input type="text" data-id="'.$categorys->cat_id.'" class="form-control num_only item_category_dis_rate" value="">';
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
    
    function item_item_discount_datatable() {
        $config['table'] = 'item i';
        $config['select'] = 'i.item_id, i.item_name';
        $config['column_order'] = array('i.item_name');
        $config['column_search'] = array('i.item_name');
        $config['order'] = array('i.item_name' => 'asc');
        $config['wheres'][] = array('column_name' => 'i.created_by', 'column_value' => $this->logged_in_id);
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        foreach ($list as $item) {
            $row = array();
            $row[] = $item->item_name;
            $row[] = '<input type="text" data-id="'.$item->item_id.'" class="form-control num_only item_item_dis_1" value="">';
            $row[] = '<input type="text" data-id="'.$item->item_id.'" class="form-control num_only item_item_dis_2" value="">';
            $row[] = '<input type="text" data-id="'.$item->item_id.'" class="form-control num_only item_item_dis_rate" value="">';
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

    function save_discount(){
        $return = array();
        $post_data = $this->input->post();
        $result = '';
        $item_group_id = isset($post_data['item_group_id']) ? $post_data['item_group_id'] : NULL;
        $item_id = isset($post_data['item_id']) ? $post_data['item_id'] : NULL;
        if(!empty($post_data['account_id'])){
            foreach($post_data['account_id'] as $account_id) {
                $discount_id = $this->crud->get_column_value_by_id('discount','discount_id',array('account_id' => $account_id, 'item_group_id' => $item_group_id, 'item_id' => $item_id));
                $discount = $this->crud->get_column_value_by_id('discount','discount',array('account_id' => $account_id, 'item_group_id' => $item_group_id, 'item_id' => $item_id));
                if($discount_id) {
                    if($discount != $post_data['discount']) {
                        $update_data = array();
                        $insert_data['account_id'] = $account_id;
                        $update_data['item_group_id'] = $item_group_id;
                        $update_data['item_id'] = $item_id;
                        $update_data['discount'] = $post_data['discount'];
                        $update_data['updated_by'] = $this->logged_in_id;
                        $update_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
                        $update_data['updated_at'] = $this->now_time;
                        $result = $this->crud->update('discount',$update_data, array('discount_id' => $discount_id));
                    }
                } else {
                    $insert_data = array();
                    $insert_data['account_id'] = $account_id;
                    $insert_data['item_group_id'] = $item_group_id;
                    $insert_data['item_id'] = $item_id;
                    $insert_data['discount'] = $post_data['discount'];
                    $insert_data['created_by'] = $this->logged_in_id;
                    $insert_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                    $insert_data['created_at'] = $this->now_time;
                    $result = $this->crud->insert('discount',$insert_data);
                }
            }
        }
        if($result){
            if(isset($post_data['discount_id']) && !empty($post_data['discount_id'])) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Discount Updated Successfully');
            } else {
                $return['success'] = "Added";
                $this->session->set_flashdata('success',true);
                $this->session->set_flashdata('message','Discount Added Successfully');
            }
        } else {
            $return['error'] = "Exist";
            $this->session->set_flashdata('false',true);
            $this->session->set_flashdata('message','Values are alredy Exist');
        }
        print json_encode($return);
        exit;
    }
    
    function save_discount_new(){
        $return = array();
        $post_data = $this->input->post();
        $item_group_table_data = json_decode($post_data['item_group_table_data']);
        $item_category_table_data = json_decode($post_data['item_category_table_data']);
        $item_item_table_data = json_decode($post_data['item_item_table_data']);
        
        $from_date = date('Y-m-d',strtotime($post_data['from_date']));
        $to_date = date('Y-m-d',strtotime($post_data['to_date']));
        
//        echo "<pre>"; print_r($item_group_table_data); exit;
        if(!empty($item_group_table_data)){
            foreach ($item_group_table_data as $item_group){
                $item_group_data = array();
                $item_group_data['from_date'] = $from_date;
                $item_group_data['to_date'] = $to_date;
                $item_group_data['discount_for_id'] = $post_data['discount_for_id'];
                $item_group_data['item_group_id'] = $item_group[0];
                $item_group_data['discount_per_1'] = $item_group[1];
                $item_group_data['discount_per_2'] = $item_group[2];
                $item_group_data['rate'] = $item_group[3];
                $item_group_data['created_by'] = $this->logged_in_id;
                $item_group_data['created_at'] = $this->now_time;
                foreach ($post_data['account_id'] as $account_id){
                    $item_group_data['account_id'] = $account_id;
                    $this->crud->insert('discount_detail',$item_group_data);
                }
            }
        }
        if(!empty($item_category_table_data)){
            foreach ($item_category_table_data as $item_category){
                $item_category_data = array();
                $item_category_data['from_date'] = $from_date;
                $item_category_data['to_date'] = $to_date;
                $item_category_data['discount_for_id'] = $post_data['discount_for_id'];
                $item_category_data['category_id'] = $item_category[0];
                $item_category_data['discount_per_1'] = $item_category[1];
                $item_category_data['discount_per_2'] = $item_category[2];
                $item_category_data['rate'] = $item_category[3];
                $item_category_data['created_by'] = $this->logged_in_id;
                $item_category_data['created_at'] = $this->now_time;
                foreach ($post_data['account_id'] as $account_id){
                    $item_category_data['account_id'] = $account_id;
                    $this->crud->insert('discount_detail',$item_category_data);
                }
            }
        }
        if(!empty($item_item_table_data)){
            foreach ($item_item_table_data as $item_item){
                $item_item_data = array();
                $item_item_data['from_date'] = $from_date;
                $item_item_data['to_date'] = $to_date;
                $item_item_data['discount_for_id'] = $post_data['discount_for_id'];
                $item_item_data['item_id'] = $item_item[0];
                $item_item_data['discount_per_1'] = $item_item[1];
                $item_item_data['discount_per_2'] = $item_item[2];
                $item_item_data['rate'] = $item_item[3];
                $item_item_data['created_by'] = $this->logged_in_id;
                $item_item_data['created_at'] = $this->now_time;
                foreach ($post_data['account_id'] as $account_id){
                    $item_item_data['account_id'] = $account_id;
                    $this->crud->insert('discount_detail',$item_item_data);
                }
            }
        }
        $return['success'] = "Added";
        $this->session->set_flashdata('success',true);
        $this->session->set_flashdata('message','Discount Added Successfully');
        print json_encode($return);
        exit;
    }
    
    function discount_list(){
        $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
        if ($li_discount == 1) {
        	if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"view")) {
            	$data = array();
            	set_page('sales/discount/discount_list', $data);
	        } else {
	        	redirect('/');
	        }
        } else {
            redirect('/');
        }
    }
    
    function discount_datatable(){
        $config['table'] = 'discount d';
        $config['select'] = 'd.*,i.item_group_name,ii.item_name';
        $config['column_order'] = array(null, 'd.account_id', 'ii.item_name', 'd.discount');
        $config['column_search'] = array('d.account_id', 'ii.item_name', 'd.discount');
        $config['joins'][] = array('join_table' => 'item_group i', 'join_by' => 'i.item_group_id = d.item_group_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item ii', 'join_by' => 'ii.item_id = d.item_id', 'join_type' => 'left');
        $config['wheres'][] = array('column_name' => 'd.created_by', 'column_value' => $this->logged_in_id);
        $config['order'] = array('d.created_at' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';

    	$isEdit = $this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID, "edit");
		$isDelete = $this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID, "delete");

        foreach ($list as $discount) {
            $account_name = "";            
            $account_ids = explode(",",$discount->account_id);          
            foreach ($account_ids as $account_id) {
                $acc_name = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $account_id));                
                if(!empty($acc_name)){
                    if($account_name == ""){
                        $account_name = $acc_name;
                    } else {
                        $account_name = $account_name.", ".$acc_name;
                    }
                }
            } 
            $row = array();
            $action = '';
            if($isEdit) {
            	 $action .= '<form id="edit_' . $discount->discount_id . '" method="post" action="' . base_url() . 'sales/discount" style="width: 25px; display: initial;" >
	                <input type="hidden" name="discount_id" id="discount_id" value="' . $discount->discount_id . '">
	                <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $discount->discount_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
	            </form> ';
            }
            if($isDelete) {
            	$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('sales/discount_delete/' . $discount->discount_id) . '"><i class="fa fa-trash"></i></a>';
            }
            
            $row[] = $action;
            $row[] = $account_name;
            if($li_item_group == '1'){
                $row[] = $discount->item_group_name;
            }
            $row[] = $discount->item_name;
            $row[] = $discount->discount;
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
    
    function discount_delete($id){
            $this->crud->delete('discount', array('discount_id' => $id));
    }
    
    function print_multiple_invoice(){
        $post_data = $this->input->post();
        if($post_data['print_type'] == 'invoice_print_pdf'){
            foreach ($post_data['invoice_ids'] as $invoice_id){
                $this->invoice_print_pdf($invoice_id, $is_multiple = '1');
            }
            $pdfFilePath = "sales_invoice.pdf";
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
        if($post_data['print_type'] == 'format_2_invoice_print'){
            foreach ($post_data['invoice_ids'] as $invoice_id){
                $this->format_2_invoice_print($invoice_id, $is_multiple = '1');
            }
            $pdfFilePath = "sales_invoice_miracle_print.pdf";
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
        if($post_data['print_type'] == 'format_3_invoice_print'){
            foreach ($post_data['invoice_ids'] as $invoice_id){
                $this->format_3_invoice_print($invoice_id, $is_multiple = '1');
            }
            $pdfFilePath = "sales_invoice_miracle_print.pdf";
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
        if($post_data['print_type'] == 'invoice_print_new_pdf'){
            foreach ($post_data['invoice_ids'] as $invoice_id){
                $this->invoice_print_new_pdf($invoice_id, $is_multiple = '1');
            }
            $pdfFilePath = "sales_invoice.pdf";
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
//        echo '<pre>'; print_r($post_data); exit;
    }

    function order_invoice_list() {
        if($this->applib->have_access_role(MODULE_ORDER_ID,"view")) {
            $data = array();
            $data['page_title'] = 'Sales Order';
            $data['invoice_type'] = 1;
            set_page('sales/invoice/order_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function format_3_2_invoice_print($sales_invoice_id, $is_multiple = '') {
        if (!empty($sales_invoice_id) && isset($sales_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('sales_invoice', 'sales_invoice_id', $sales_invoice_id);
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
            $this->load->library('numbertowords');
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total + $result->aspergem_service_charge));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total + $result->aspergem_service_charge);
            }
            $total_gst = $result->cgst_amount_total + $result->sgst_amount_total + $result->igst_amount_total;
			//$total_gst = $result->gst;
            if ($total_gst < 0) {
                $gst_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_gst));
            } else {
                $gst_total_word = $this->numbertowords->convert_number($total_gst);
            }
            $print_type = $result->print_type;
            $this->load->library('applib');
            $sales_invoice_no = $this->applib->format_invoice_number($result->sales_invoice_id, $result->sales_invoice_date);
            $data = array(
                'sales_invoice_id' => $result->sales_invoice_id,
                'sales_invoice_no' => $sales_invoice_no,
                'prefix' => $result->prefix,
                'sales_invoice_date' => date('d-m-Y', strtotime($result->sales_invoice_date)),
                'sales_invoice_desc' => nl2br($result->sales_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => ($result->amount_total + $result->aspergem_service_charge),
                'amount_total_word' => $amount_total_word,
                'gst_total_word' => $gst_total_word,
                'sales_subject' => $result->sales_subject,
                'sales_note' => $result->sales_note,
                'total_pf_amount' => $result->total_pf_amount,
                'aspergem_service_charge' => isset($result->aspergem_service_charge) ? $result->aspergem_service_charge:0 ,
                
            );
            $data['sales_invoice_data'] = $result;
            $data['user_name'] = $user_detail->user_name;
            $data['user_address'] = $user_detail->address;
            $data['user_gst_no'] = $user_detail->gst_no;
            $data['user_country'] = 'India';
            $usr_city = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $user_detail->city);
            $data['user_city'] = strtolower($usr_city);
            $data['user_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $user_detail->state);
            $data['user_pan'] = $user_detail->pan;
            $data['user_postal_code'] = $user_detail->postal_code;
            $data['user_phone'] = $user_detail->phone;
            $data['email_ids'] = $user_detail->email_ids;
            $data['logo_image'] = $user_detail->logo_image;
            // $data['bank_name'] = $user_detail->bank_name;
            // $data['bank_branch'] = $user_detail->bank_branch;
            // $data['bank_ac_no'] = $user_detail->bank_ac_no;
            // $data['rtgs_ifsc_code'] = $user_detail->rtgs_ifsc_code;
            $data['is_letter_pad'] = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_letter_pad'];

            $data['account_name'] = $account_detail->account_name;
            $cash_in_hand_acc = $this->applib->is_cash_in_hand_account($result->account_id);
            if ($cash_in_hand_acc == true && !empty($result->cash_customer)) {
                $data['account_name'] = $result->cash_customer;
            }
            $data['account_gst_no'] = $account_detail->account_gst_no;
            if (!empty($result->is_shipping_same_as_billing_address) && $result->is_shipping_same_as_billing_address == '1') {
                $data['account_address'] = $result->shipping_address;
            } else {
                $data['account_address'] = $account_detail->account_address;
            }
            $data['account_country'] = 'India';
            $data['account_state'] = $this->crud->get_id_by_val('state', 'state_name', 'state_id', $account_detail->account_state);
            $data['account_city'] = $this->crud->get_id_by_val('city', 'city_name', 'city_id', $account_detail->account_city);
            $data['account_postal_code'] = $account_detail->account_postal_code;
            $data['account_pan'] = $account_detail->account_pan;
            $data['transport_name'] = $result->transport_name;
            $data['lr_no'] = $result->lr_no;

            $lineitem_arr = array();
            $where = array('module' => '2', 'parent_id' => $sales_invoice_id);
            $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
            /*echo "<pre>";
            print_r( $sales_invoice_lineitems[] );
            die();*/
            $total_gst = 0;

            foreach ($sales_invoice_lineitems as $key=>$sales_invoice_lineitem) {
                $sales_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $sales_invoice_lineitem->item_id);
                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $sales_invoice_lineitem->item_id);
                if (!empty($hsn_id)) {
                    $sales_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $sales_invoice_lineitem->hsn_code = '';
                }
                //$sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->item_qty * $sales_invoice_lineitem->price;
                $sales_invoice_lineitem->pure_amount = $sales_invoice_lineitem->pure_amount;
                $sales_invoice_lineitem->cgst_amt = $sales_invoice_lineitem->cgst_amount;
                $sales_invoice_lineitem->sgst_amt = $sales_invoice_lineitem->sgst_amount;
                $sales_invoice_lineitem->igst_amt = $sales_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $sales_invoice_lineitem;
                $amt =  $sales_invoice_lineitem->price * $sales_invoice_lineitem->item_qty;
                $gst_amount = $amt * $sales_invoice_lineitem->gst / 100;
                $total_gst += $gst_amount;
                if ($key == 0) {
                    $site_data = $this->crud->get_row_by_id('sites', array('site_id' => $sales_invoice_lineitem->site_id));
                    $data['site_name'] = $site_data[0]->site_name;
                    $data['site_address'] = $site_data[0]->site_address;
                }
            }
//            $no_arr = count($lineitem_arr);
//            if($no_arr < 10){
//                for ($i = $no_arr; $i < 10; $i++) {
//                    $lineitem_arr[$i] = array('');
//                    $lineitem_arr[$i] = (object) $lineitem_arr[$i];
//                }
//            }
            
            $data['lineitems'] = $lineitem_arr;
            $data['total_gst'] = $total_gst;
            if ($total_gst < 0) {
                $gst_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($total_gst));
            } else {
                $gst_total_word = $this->numbertowords->convert_number($total_gst);
            }
            $data['gst_total_word'] = $gst_total_word;

//            echo '<pre>'; print_r($lineitem_arr); exit;
        } else {
            redirect($_SERVER['HTTP_REFERER']);
            $data = array();
        }
        if (isset($data['amount_total'])) {
            $round_off = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'round_off_apply'));
            if (!empty($round_off)) {
                $amount_total_r = number_format((float) $data['amount_total'], 0, '.', '');
                $data['amount_total'] = number_format((float) $amount_total_r, 2, '.', '');
            }
        }
//		echo '<pre>'; print_r($data); exit;
        $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
        $termsdata = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'sales_terms'));
        $data['terms_data'] = $termsdata;
        $data['letterpad_print'] = $letterpad_print;
        $data['printtype'] = 0;
        $our_bank_label = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $data['sales_invoice_data']->our_bank_id));
        $data['our_bank_label'] = $our_bank_label;

        $bank_details = $this->crud->get_data_row_by_where('account', array('account_id' => $data['sales_invoice_data']->our_bank_id));
        $data['bank_name'] = isset($bank_details->bank_name) ? $bank_details->bank_name : '';
        $data['bank_branch'] = isset($bank_details->bank_branch) ? $bank_details->bank_branch : '';
        $data['bank_ac_no'] = isset($bank_details->bank_ac_no) ? $bank_details->bank_ac_no : '';
        $data['rtgs_ifsc_code'] = isset($bank_details->rtgs_ifsc_code) ? $bank_details->rtgs_ifsc_code : '';

        $html = $this->load->view('sales/invoice/invoice_3_2_somnath_print', $data, true);
        /*echo $html;exit;*/
        $pdfFilePath = "sales_invoice_miracle_print.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                5, // margin right
                5, // margin top
                15, // margin bottom
                5, // margin header
                5); // margin footer

        /* $this->m_pdf->pdf->SetHTMLHeader('<div style="text-align:left; font-weight: bold;"><h2 style="color:red;">Ajanta</h2></div>
          <div style="text-align:right; font-weight: bold;">Ajanta</div>
          ','O'); */

        /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
          <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
          <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
          <td width="33%" style="text-align:right; ">My document</td></tr>
          </table>','O'); */
        //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

        $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
		<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
		<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
		<td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
        //$this->m_pdf->pdf->WriteHTML(file_get_contents(base_url().'assets/bootstrap/css/bootstrap.min.css'), 1);
        $this->m_pdf->pdf->WriteHTML($html);

        if ($print_type == 2) {
            $data['printtype'] = 1;
            $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
              <td width="33%" style="text-align:right; ">My document</td></tr>
              </table>','O'); */
            //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);

            $data['printtype'] = 2;
            $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
              <td width="33%" style="text-align:right; ">My document</td></tr>
              </table>','O'); */
            //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);
        }
        if ($print_type == 1) {
            $data['printtype'] = 1;
            $html = $this->load->view('sales/invoice/invoice_somnath_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            /* $this->m_pdf->pdf->SetHTMLHeader('<table width="100%" style="vertical-align: bottom; font-family:; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
              <td width="33%" style="text-align:right; ">My document</td></tr>
              </table>','O'); */
            //$this->m_pdf->pdf->SetHTMLHeader('<div style="border-bottom: 1px solid #000000;">My document</div>','E');

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);
        }
        if(empty($is_multiple)){
            $this->m_pdf->pdf->Output($pdfFilePath, 'I');
        }
    }
}

