<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Credit_note
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Credit_note extends CI_Controller
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
		$this->is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
		$this->now_time = date('Y-m-d H:i:s');
	}
	//~ Credit Note
	function add(){
		redirect('transaction/sales_purchase_transaction/credit_note');
		exit();
		
		if($this->is_single_line_item == 1) {
			redirect('transaction/sales_purchase_transaction/credit_note');
			exit();
		}

		$data = array();

		$credit_note_date = $this->crud->get_column_value_by_id('company_settings','setting_value',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date'));
        if(!empty($credit_note_date) && strtotime($credit_note_date) > 0) {
        	$data['credit_note_date'] = date('d-m-Y',strtotime($credit_note_date));
        } else {
        	$data['credit_note_date'] = date('d-m-Y');
        }

		$line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 2 AND setting_value = 1');
        $invoice_line_item_fields = array();
        if(!empty($line_item_fields)) {
            foreach ($line_item_fields as $value) {
                $invoice_line_item_fields[] = $value->setting_key;
            }
        }
        $data['invoice_line_item_fields'] = $invoice_line_item_fields;

		if(isset($_POST['credit_note_id'])){
			if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"edit")) {
            	$where = array('credit_note_id' => $_POST['credit_note_id']);
				$credit_note_data = $this->crud->get_row_by_id('credit_note', $where);
				$data['credit_note_data'] = $credit_note_data[0];
				$lineitems = '';
				$where = array('module' => '3', 'parent_id' => $_POST['credit_note_id']);
				$credit_note_lineitems = $this->crud->get_row_by_id('lineitems', $where);
				foreach($credit_note_lineitems as $credit_note_lineitem){
					//$credit_note_lineitem->pure_amount = $credit_note_lineitem->item_qty * $credit_note_lineitem->price;
	                $credit_note_lineitem->pure_amount = $credit_note_lineitem->pure_amount;
					$credit_note_lineitem->cgst_amt = $credit_note_lineitem->cgst_amount;
					$credit_note_lineitem->sgst_amt = $credit_note_lineitem->sgst_amount;
					$credit_note_lineitem->igst_amt = $credit_note_lineitem->igst_amount;
					$lineitems .= "'".json_encode($credit_note_lineitem)."',";
				}
				$data['credit_note_lineitems'] = $lineitems;
				set_page('credit_note/add', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		} else {
			if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"add")) {
            	set_page('credit_note/add', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }			
		}	
	}
	
	function save_credit_note(){
		$post_data = $this->input->post();
		$line_items_data = json_decode('['.$post_data['line_items_data'].']'); 
		$credit_note_data = array();
		$credit_note_data['account_id'] = $post_data['account_id'];
		$credit_note_data['credit_note_date'] = date('Y-m-d', strtotime($post_data['credit_note_date']));
		$credit_note_data['bill_no'] = $post_data['bill_no'];
		$credit_note_data['invoice_date'] = date('Y-m-d', strtotime($post_data['invoice_date']));
		$credit_note_data['credit_note_desc'] = $post_data['credit_note_desc'];
		$credit_note_data['qty_total'] = $post_data['qty_total'];
		$credit_note_data['pure_amount_total'] = $post_data['pure_amount_total'];
		$credit_note_data['discount_total'] = $post_data['discount_total'];
		$credit_note_data['cgst_amount_total'] = $post_data['cgst_amount_total'];
		$credit_note_data['sgst_amount_total'] = $post_data['sgst_amount_total'];
		$credit_note_data['igst_amount_total'] = $post_data['igst_amount_total'];
		$credit_note_data['amount_total'] = $post_data['amount_total'];
        
        $dateranges = $this->crud->getFromSQL('SELECT `daterange` FROM `locked_daterange` WHERE `user_id`="'.$this->logged_in_id.'" ');
        if(!empty($dateranges)){
            foreach($dateranges as $daterange){
                $pieces = explode(" ", $daterange->daterange);
                $from = date('d-m-Y', strtotime($pieces[0]));
                $to = date('d-m-Y', strtotime($pieces[2]));
                $credit_note_date = date('d-m-Y', strtotime($post_data['credit_note_date']));
                if(strtotime($credit_note_date) >= strtotime($from) && strtotime($credit_note_date) <= strtotime($to)){
                    $return['error'] = "Locked_Date";
                    print json_encode($return);
                    exit;
                }
            }    
        }
        if(isset($post_data['credit_note_id']) && !empty($post_data['credit_note_id'])){
			$credit_note_data['updated_at'] = $this->now_time;
			$credit_note_data['updated_by'] = $this->logged_in_id;
			$credit_note_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			
			$where_array['credit_note_id'] = $post_data['credit_note_id'];
			$result = $this->crud->update('credit_note', $credit_note_data, $where_array);
			if($result){
				$return['success'] = "Updated";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Credit Note Updated Successfully');
				$parent_id = $post_data['credit_note_id'];
				if(isset($post_data['deleted_lineitem_id']) && !empty($post_data['deleted_lineitem_id'])){
					$deleted_lineitem_ids = $post_data['deleted_lineitem_id'];

					$lineitems_res = $this->crud->get_where_in_result('lineitems', 'id', $deleted_lineitem_ids);
                    if(!empty($lineitems_res)) {
                        foreach ($lineitems_res as $key => $lineitem) {
                            $this->crud->update_item_current_stock_qty($lineitem->item_id,$lineitem->parent_id,'credit_note',$lineitem->item_qty,'delete');       
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
					$add_lineitem['module'] = 3;
					$add_lineitem['parent_id'] = $parent_id;
					$add_lineitem['rate_for_itax'] = $lineitem->rate_for_itax;
					$add_lineitem['price_for_itax'] = $lineitem->price_for_itax;
					$add_lineitem['igst_for_itax'] = $lineitem->igst_for_itax;
					if(isset($lineitem->id) && !empty($lineitem->id)){

						$this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'credit_note',$lineitem->item_qty,'update');

						$add_lineitem['updated_at'] = $this->now_time;
						$add_lineitem['updated_by'] = $this->logged_in_id;
						$add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$where_id['id'] = $lineitem->id;
						$this->crud->update('lineitems', $add_lineitem, $where_id);
					} else {
						
						$this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'credit_note',$lineitem->item_qty,'add');

						$add_lineitem['created_at'] = $this->now_time;
						$add_lineitem['created_by'] = $this->logged_in_id;
						$add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
						$add_lineitem['updated_at'] = $this->now_time;
						$add_lineitem['updated_by'] = $this->logged_in_id;
						$add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$this->crud->insert('lineitems',$add_lineitem);
					}
				}
			}
			
		} 
		else {
			$credit_note_data['created_at'] = $this->now_time;
			$credit_note_data['created_by'] = $this->logged_in_id;
			$credit_note_data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$credit_note_data['updated_at'] = $this->now_time;
			$credit_note_data['updated_by'] = $this->logged_in_id;
			$credit_note_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			
			$credit_note_no = $this->crud->get_max_number('credit_note', 'credit_note_no', $this->logged_in_id);
			$credit_note_data['credit_note_no'] = $credit_note_no->credit_note_no + 1;
			
			$result = $this->crud->insert('credit_note',$credit_note_data);
			if($result){
				$return['success'] = "Added";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Credit Note Added Successfully');
				$parent_id = $this->db->insert_id();

				$company_settings_id = $this->crud->get_column_value_by_id('company_settings','company_settings_id',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date'));
                if(!empty($company_settings_id)) {
                    $this->crud->update('company_settings',array("setting_value" => $credit_note_data['credit_note_date'],'updated_at' => $this->now_time,'updated_by' => $this->logged_in_id),array('company_settings_id'=>$company_settings_id));
                } else {
                    $this->crud->insert('company_settings',array('company_id' => $this->logged_in_id,'setting_key' => 'credit_note_date',"setting_value" => $credit_note_data['credit_note_date'],'created_at' => $this->now_time,'created_by' => $this->logged_in_id));
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
					$add_lineitem['module'] = 3;
					$add_lineitem['parent_id'] = $parent_id;
					$add_lineitem['rate_for_itax'] = $lineitem->rate_for_itax;
					$add_lineitem['price_for_itax'] = $lineitem->price_for_itax;
					$add_lineitem['igst_for_itax'] = $lineitem->igst_for_itax;
					$add_lineitem['created_at'] = $this->now_time;
					$add_lineitem['updated_at'] = $this->now_time;
					$add_lineitem['updated_by'] = $this->logged_in_id;
					$add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
					$add_lineitem['created_by'] = $this->logged_in_id;
					$add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
					$this->crud->insert('lineitems',$add_lineitem);

					$this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'credit_note',$lineitem->item_qty,'add');
				}
			}
		}
		print json_encode($return);
		exit;
	}
	
	function list_page(){
		if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"view")) {
        	$data = array();
			set_page('credit_note/list_page', $data);
        } else {
        	$this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');			
        	redirect('/');
        }
		
	}
	
	function credit_note_datatable(){
        $from_date = '';
        $to_date = '';
        $account_id = '';
        if( isset($_POST['daterange_1']) && !empty($_POST['daterange_1']) && isset($_POST['daterange_2']) && !empty($_POST['daterange_2'])){
            $from_date = trim($_POST['daterange_1']);
            $from_date = substr($from_date, 6, 4).'-'.substr($from_date, 3, 2).'-'.substr($from_date, 0, 2);
            $to_date = trim($_POST['daterange_2']);
            $to_date = substr($to_date, 6, 4).'-'.substr($to_date, 3, 2).'-'.substr($to_date, 0, 2);
        }
        if(isset($_POST['account_id'])){
            $account_id = $_POST['account_id'];
        }
		$config['table'] = 'credit_note cn';
		$config['select'] = 'cn.credit_note_id, cn.credit_note_no, cn.credit_note_date, cn.amount_total, cn.data_lock_unlock, a.account_name';
		$config['column_order'] = array(null, 'cn.credit_note_no', 'a.account_name', 'cn.credit_note_date', 'cn.amount_total');
		$config['column_search'] = array('cn.credit_note_no', 'a.account_name', 'DATE_FORMAT(cn.credit_note_date,"%d-%m-%Y")', 'cn.amount_total');
		$config['wheres'][] = array('column_name' => 'cn.created_by', 'column_value' => $this->logged_in_id);
        if (!empty($account_id)) {
            $config['wheres'][] = array('column_name' => 'cn.account_id', 'column_value' => $account_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'cn.credit_note_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'cn.credit_note_date <=', 'column_value' => $to_date);
        }
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = cn.account_id', 'join_type' => 'left');
		$config['order'] = array('cn.created_at' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();

		$isEdit = $this->applib->have_access_role(MODULE_CREDIT_NOTE_ID, "edit");
		$isDelete = $this->applib->have_access_role(MODULE_CREDIT_NOTE_ID, "delete");

		$data = array();
		foreach ($list as $order) {
			$row = array();
			$action = '';
			if($order->data_lock_unlock == 0){
				if($isEdit) {
					if($this->is_single_line_item == 1) {
						$action .= '<form id="edit_' . $order->credit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/credit_note" style="width: 25px; display: initial;" >
	                            <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $order->credit_note_id . '">
	                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->credit_note_id . '\').submit();" title="Edit Credit Note"><i class="fa fa-edit"></i></a>
	                        </form> ';
					} else {
						$action .= '<form id="edit_' . $order->credit_note_id . '" method="post" action="' . base_url() . 'transaction/sales_purchase_transaction/credit_note" style="width: 25px; display: initial;" >
	                            <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $order->credit_note_id . '">
	                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->credit_note_id . '\').submit();" title="Edit Credit Note"><i class="fa fa-edit"></i></a>
	                        </form> ';
					}
				}
				if($isDelete) {
					$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('credit_note/credit_note_delete/' . $order->credit_note_id) . '"><i class="fa fa-trash"></i></a>';
				}
				
				$action_detail = ' &nbsp; <form id="detail_' . $order->credit_note_id . '" method="post" action="' . base_url() . 'credit_note/credit_note_detail" style="width: 25px; display: initial;" >
                            <input type="hidden" name="credit_note_id" id="credit_note_id" value="' . $order->credit_note_id . '">
                            <a class="detail_button btn-info btn-xs" href="javascript:{}" onclick="document.getElementById(\'detail_' . $order->credit_note_id . '\').submit();" title="Credit Note Detail"><i class="fa fa-eye"></i></a>
                        </form>';
            }
			$row[] = $action;
			$row[] = $order->credit_note_no;
			$row[] = $order->account_name;
			$row[] = date('d-m-Y', strtotime($order->credit_note_date));
			$row[] = $order->amount_total;
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
	
	function credit_note_delete($credit_note_id){
		$this->crud->kasar_entry(0,$credit_note_id,'credit_note',0,'delete');
		
		$lineitems_res = $this->crud->get_row_by_id('lineitems',array('module' => 3, 'parent_id' => $credit_note_id));
        if(!empty($lineitems_res)) {
            foreach ($lineitems_res as $key => $lineitem) {
                $this->crud->update_item_current_stock_qty($lineitem->item_id,$lineitem->parent_id,'credit_note',$lineitem->item_qty,'delete');       
            }
        }

		$this->crud->delete('credit_note', array('credit_note_id' => $credit_note_id));
		$this->crud->delete('lineitems', array('module' => 3, 'parent_id' => $credit_note_id));
	}
	
	/*function credit_note_detail(){
		if(!empty($_POST['credit_note_id']) && isset($_POST['credit_note_id'])){
			$result = $this->crud->get_data_row_by_id('credit_note','credit_note_id',$_POST['credit_note_id']);
			$data = array(
				'credit_note_id' => $result->credit_note_id,
				'credit_note_no' => $result->credit_note_no,
				'credit_note_date' => date('d-m-Y', strtotime($result->credit_note_date)),
				'credit_note_desc' => nl2br($result->credit_note_desc),
				'bill_no' => $result->bill_no,
				'qty_total' => $result->qty_total,
				'before_discount_total' => $result->before_discount_total,
				'discount_total' => $result->discount_total,
				'cgst_amount_total' => $result->cgst_amount_total,
				'sgst_amount_total' => $result->sgst_amount_total,
				'igst_amount_total' => $result->igst_amount_total,
				'amount_total' => $result->amount_total,
				'account' => $this->crud->get_id_by_val('account', 'account_name', 'account_id', $result->account_id),
			);
			$where = array('module' => '3', 'parent_id' => $result->credit_note_id);
			$credit_note_lineitems = $this->crud->get_row_by_id('lineitems', $where);
			$lineitem_arr = array();
			foreach($credit_note_lineitems as $credit_note_lineitem){
				$credit_note_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $credit_note_lineitem->item_id);
				$credit_note_lineitem->item_type_name = $this->crud->get_id_by_val('item_type', 'item_type_name', 'item_type_id', $credit_note_lineitem->item_type_id);
				$credit_note_lineitem->location_name = $this->crud->get_id_by_val('location', 'location_name', 'location_id', $credit_note_lineitem->item_location_id);
				$credit_note_lineitem->rack_name = $this->crud->get_id_by_val('rack', 'rack_name', 'rack_id', $credit_note_lineitem->item_rack_id);
				$credit_note_lineitem->price_type = $this->get_price_type_label($credit_note_lineitem->price_type);
				
				$credit_note_lineitem->purchase_order_no = '';
				$credit_note_lineitem->purchase_challan_no = '';
				if(!empty($credit_note_lineitem->picker_lineitem_id)){
					$result = $this->app_model->get_module_data_by_picker_id($credit_note_lineitem->picker_lineitem_id);
					if(isset($result->purchase_order_no)){
						$credit_note_lineitem->purchase_order_no = $result->purchase_order_no;
					}
					if(isset($result->purchase_challan_no)){
						$credit_note_lineitem->purchase_challan_no = $result->purchase_challan_no;
					}
				}
				$credit_note_lineitem->before_discount_price = $credit_note_lineitem->item_qty * $credit_note_lineitem->price;
				$credit_note_lineitem->cgst_amt = $credit_note_lineitem->cgst_amount;
				$credit_note_lineitem->sgst_amt = $credit_note_lineitem->sgst_amount;
				$credit_note_lineitem->igst_amt = $credit_note_lineitem->igst_amount;
				$discount_id = array();
				$discount_type = array();
				$discount = array();
				$lineitem_discounts = $this->crud->get_row_by_id('lineitem_discount', array('lineitem_id' => $credit_note_lineitem->id));
				foreach($lineitem_discounts as $lineitem_discount){
					$discount_id[] = $lineitem_discount->discount_id;
					$discount_type[] = $lineitem_discount->discount_type;
					$discount[] = $lineitem_discount->discount;
				}
				$credit_note_lineitem->discount_id = $discount_id;
				$credit_note_lineitem->discount_type = $discount_type;
				$credit_note_lineitem->discount = $discount;
				$lineitem_arr[] = $credit_note_lineitem;
			}
			$data['lineitems'] = $lineitem_arr;
			
		} else {
			$data = array();	
		}
		set_page('purchase/invoice/invoice_detail', $data);
	}*/
	
}

