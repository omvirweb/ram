<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Item
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Item extends CI_Controller
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

	function item(){
        $data = array();
		if(isset($_POST['item_id']) && !empty($_POST['item_id']) ){
			if($this->applib->have_access_role(MASTER_ITEM_ID,"edit")) {
            	$result = $this->crud->get_data_row_by_id('item','item_id',$_POST['item_id']);
				$data = array(
					'item_id' => $result->item_id,
					'item_name' => $result->item_name,
					'hsn_code' => $result->hsn_code,
	                                'list_price' => $result->list_price,
					'mrp' => $result->mrp,
					'opening_qty' => $result->opening_qty,
					'opening_amount' => $result->opening_amount,
					'cgst_per' => $result->cgst_per,
					'sgst_per' => $result->sgst_per,
					'igst_per' => $result->igst_per,
					'cess' => $result->cess,
					'alternate_unit_id' => $result->alternate_unit_id,
					'pack_unit_id' => $result->pack_unit_id,
					'item_type_id' => $result->item_type_id,
	                                'item_group_id' => $result->item_group_id,
					'item_desc' => $result->item_desc,
					'edit_purchase_rate' => $result->purchase_rate,
					'edit_sales_rate' => $result->sales_rate,
					'minimum' => $result->minimum,
					'maximum' => $result->maximum,
					'reorder_stock' => $result->reorder_stock,
					'edit_discount_on' => $result->discount_on,
					'discount_per' => $result->discount_per,
					'exempted_from_gst' => $result->exempted_from_gst,
					'category_id' => $result->category_id,
					'sub_category_id' => $result->sub_category_id,
	                                'purchase_rate_val' => $result->purchase_rate_val,
					'sales_rate_val' => $result->sales_rate_val,
				);
				$data['hsn'] = $this->crud->get_select_data('hsn');
				set_page('item/item', $data);	
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		} else {
			if($this->applib->have_access_role(MASTER_ITEM_ID,"add")) {
            	$data['sales_rate'] = $this->crud->get_column_value_by_id('user','sales_rate',array('user_id'=>$this->logged_in_id));
	            $data['purchase_rate'] = $this->crud->get_column_value_by_id('user','purchase_rate',array('user_id'=>$this->logged_in_id));
	            $data['edit_discount_on'] = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['discount_on'];

	            $data['hsn'] = $this->crud->get_select_data('hsn');
				set_page('item/item', $data);
	        } else {
	        	$this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
	        	redirect('/');
	        }
		}
	}

	function save_item(){
		$return = '';
		$post_data = $this->input->post();
		if(empty($post_data['item_id'])){
			$v_id = $this->crud->get_id_by_val_count('item','item_id',array('item_name' => trim($post_data['item_name']), 'created_by' => $this->logged_in_id));
			if(!empty($v_id)) {
				$return['error'] = "itemExist";
				print json_encode($return);
				exit;
			}	
		}else{
			$v_id = $this->crud->get_id_by_val_count('item','item_id',array('item_name' => trim($post_data['item_name']), 'item_id !=' => $post_data['item_id'], 'created_by' => $this->logged_in_id));
			if(!empty($v_id)) {
				$return['error'] = "itemExist";
				print json_encode($return);
				exit;
			}
		}
		$item_type_id = null;
		if(isset($post_data['item_type_id']) && !empty($post_data['item_type_id'])){
			$item_type = $post_data['item_type_id'];
			if(!empty($item_type)){
				if(is_numeric($item_type)){
					$item_type_id = $item_type;
				} else {
					$item_type = trim($item_type);
					$where_item_type['item_type_name'] = $item_type;
					$res = $this->crud->get_all_with_where('item_type','','',$where_item_type);
					if(empty($res)){
						$insert_item_type['item_type_name'] = $item_type;
						$insert_item_type['created_at'] = $this->now_time;
						$insert_item_type['updated_at'] = $this->now_time;
						$insert_item_type['updated_by'] = $this->logged_in_id;
						$insert_item_type['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_item_type['created_by'] = $this->logged_in_id;
						$insert_item_type['user_created_by'] = $this->session->userdata()['login_user_id'];
						$item_type_id = $this->crud->insert('item_type', $insert_item_type);
						$res = $this->crud->get_all_with_where('item_type','','',$where_item_type);
						$item_type_id = $res[0]->item_type_id;
					} else {
						$item_type_id = $res[0]->item_type_id;
					}
				}
			}
		}
		$data['item_type_id'] = $item_type_id;

                $item_group_id = null;
		if(isset($post_data['item_group_id']) && !empty($post_data['item_group_id'])){
			$item_group = $post_data['item_group_id'];
			if(!empty($item_group)){
				if(is_numeric($item_group)){
					$item_group_id = $item_group;
				} else {
					$item_group = trim($item_group);
					$where_item_group['item_group_name'] = $item_group;
					$res = $this->crud->get_all_with_where('item_group','','',$where_item_group);
					if(empty($res)){
						$insert_item_group['item_group_name'] = $item_group;
						$insert_item_group['created_at'] = $this->now_time;
						$insert_item_group['updated_at'] = $this->now_time;
						$insert_item_group['updated_by'] = $this->logged_in_id;
						$insert_item_group['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_item_group['created_by'] = $this->logged_in_id;
						$insert_item_group['user_created_by'] = $this->session->userdata()['login_user_id'];
						$item_group_id = $this->crud->insert('item_group', $insert_item_group);
						$res = $this->crud->get_all_with_where('item_group','','',$where_item_group);
						$item_group_id = $res[0]->item_group_id;
					} else {
						$item_group_id = $res[0]->item_group_id;
					}
				}
			}
		}
		$data['item_group_id'] = $item_group_id;

		$pack_unit_id = null;
		if(isset($post_data['pack_unit_id']) && !empty($post_data['pack_unit_id'])){
			$pack_unit = $post_data['pack_unit_id'];
			if(!empty($pack_unit)){
				if(is_numeric($pack_unit)){
					$pack_unit_id = $pack_unit;
				} else {
					$pack_unit = trim($pack_unit);
					$where_pack_unit['pack_unit_name'] = $pack_unit;
					$res = $this->crud->get_all_with_where('pack_unit','','',$where_pack_unit);
					if(empty($res)){
						$insert_pack_unit['pack_unit_name'] = $pack_unit;
						$insert_pack_unit['created_at'] = $this->now_time;
						$insert_pack_unit['updated_at'] = $this->now_time;
						$insert_pack_unit['updated_by'] = $this->logged_in_id;
						$insert_pack_unit['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_pack_unit['created_by'] = $this->logged_in_id;
						$insert_pack_unit['user_created_by'] = $this->session->userdata()['login_user_id'];
						$pack_unit_id = $this->crud->insert('pack_unit', $insert_pack_unit);
						$res = $this->crud->get_all_with_where('pack_unit','','',$where_pack_unit);
						$pack_unit_id = $res[0]->pack_unit_id;
					} else {
						$pack_unit_id = $res[0]->pack_unit_id;
					}
				}
			}
		}
		$data['pack_unit_id'] = $pack_unit_id;
		
		
		$alternate_unit_id = null;
		if(isset($post_data['alternate_unit_id']) && !empty($post_data['alternate_unit_id'])){
			$alternate_unit = $post_data['alternate_unit_id'];
			if(!empty($alternate_unit)){
				if(is_numeric($alternate_unit)){
					$alternate_unit_id = $alternate_unit;
				} else {
					$alternate_unit = trim($alternate_unit);
					$where_alternate_unit['pack_unit_name'] = $alternate_unit;
					$res = $this->crud->get_all_with_where('pack_unit','','',$where_alternate_unit);
					if(empty($res)){
						$insert_alternate_unit['pack_unit_name'] = $alternate_unit;
						$insert_alternate_unit['created_at'] = $this->now_time;
						$insert_alternate_unit['updated_at'] = $this->now_time;
						$insert_alternate_unit['updated_by'] = $this->logged_in_id;
						$insert_alternate_unit['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_alternate_unit['created_by'] = $this->logged_in_id;
						$insert_alternate_unit['user_created_by'] = $this->session->userdata()['login_user_id'];
						$alternate_unit_id = $this->crud->insert('pack_unit', $insert_alternate_unit);
						$res = $this->crud->get_all_with_where('pack_unit','','',$where_alternate_unit);
						$alternate_unit_id = $res[0]->pack_unit_id;
					} else {
						$alternate_unit_id = $res[0]->pack_unit_id;
					}
				}
			}
		}
		$data['alternate_unit_id'] = $alternate_unit_id;
		
		
		$data['item_name'] = $post_data['item_name'];
		$data['hsn_code'] = isset($post_data['hsn_code']) ? $post_data['hsn_code'] : null;
		$data['cgst_per'] = isset($post_data['cgst_per']) ? $post_data['cgst_per'] : null;
		$data['sgst_per'] = isset($post_data['sgst_per']) ? $post_data['sgst_per'] : null;
		$data['igst_per'] = isset($post_data['igst_per']) ? $post_data['igst_per'] : null;
		$data['cess'] = isset($post_data['cess']) ? $post_data['cess'] : null;
		$data['item_desc'] = isset($post_data['desc']) ? $post_data['desc'] : null;
		$data['purchase_rate'] = isset($post_data['purchase_rate']) ? $post_data['purchase_rate'] : null;
		$data['sales_rate'] = isset($post_data['sales_rate']) ? $post_data['sales_rate'] : null;
                $data['purchase_rate_val'] = isset($post_data['purchase_rate_val']) ? $post_data['purchase_rate_val'] : null;
		$data['sales_rate_val'] = isset($post_data['sales_rate_val']) ? $post_data['sales_rate_val'] : null;
		$data['minimum'] = isset($post_data['minimum']) ? $post_data['minimum'] : null;
		$data['maximum'] = isset($post_data['maximum']) ? $post_data['maximum'] : null;
		$data['reorder_stock'] = isset($post_data['reorder_stock']) ? $post_data['reorder_stock'] : null;
		$data['shortname'] = isset($post_data['shortname']) ? $post_data['shortname'] : null;
        $data['list_price'] = isset($post_data['list_price']) ? $post_data['list_price'] : null;
        $data['mrp'] = isset($post_data['mrp']) ? $post_data['mrp'] : null;
        $data['opening_qty'] = isset($post_data['opening_qty']) ? $post_data['opening_qty'] : null;
        $data['opening_amount'] = isset($post_data['opening_amount']) ? $post_data['opening_amount'] : null;
        $data['exempted_from_gst'] = isset($post_data['exempted_from_gst']) ? '1' : '0';
        $data['discount_on'] = isset($post_data['discount_on']) ? $post_data['discount_on'] : null;
        $data['discount_per'] = isset($post_data['discount_per']) ? $post_data['discount_per'] : null;
        $data['category_id'] = isset($post_data['category_id']) ? $post_data['category_id'] : null;
        $data['sub_category_id'] = isset($post_data['sub_category_id']) ? $post_data['sub_category_id'] : null;
        $data['item_group_id'] = isset($post_data['item_group_id']) ? $post_data['item_group_id'] : null;
        $data['updated_at'] = $this->now_time;
        $data['updated_by'] = $this->logged_in_id;
        $data['user_updated_by'] = $this->session->userdata()['login_user_id'];
		if(isset($post_data['item_id']) && !empty($post_data['item_id'])){
			$this->crud->update_item_current_stock_qty($post_data['item_id'],'','update_item',$data['opening_qty'],'update');

			$where_array['item_id'] = $post_data['item_id'];
			$result = $this->crud->update('item', $data, $where_array);


			if($result){
				$return['success'] = "Updated";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Item Updated Successfully');
				$last_query_id = $post_data['item_id'];
			} else {
				$return['error'] = "errorUpdated";
			}
		}else{
			$data['created_at'] = $this->now_time;
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('item',$data);
			if($result){
				$return['success'] = "Added";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Item Added Successfully');
				$last_query_id = $this->db->insert_id();

				if($data['opening_qty'] > 0) {
                	$this->crud->update_item_current_stock_qty($last_query_id,'','add_item',$data['opening_qty'],'add');
				}
			}else{
				$return['error'] = "errorAdded";
			}
		}
		/*if($result){
			$file_element_name = 'item_image';
			$config['upload_path'] = './assets/uploads/images/items/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite'] = TRUE;
			$config['encrypt_name'] = FALSE;
			$config['remove_spaces'] = TRUE;
			$newFileName = $_FILES['item_image']['name'];
			$tmp = explode('.', $newFileName);
			$file_extension = end($tmp);
			$filename = $last_query_id."_".time().".".$file_extension;
			$config['file_name'] = $filename;
			if(!is_dir($config['upload_path'])){
				mkdir($config['upload_path'],0777,TRUE);
			}
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload($file_element_name)){
				$return['Uploaderror'] = $this->upload->display_errors();
			}
			$file_data = $this->upload->data();
			if(!empty($file_data['file_name']) && !empty($file_extension)){
				$f_data['item_image'] = $file_data['file_name'];
				$f_where_array['item_id'] = $last_query_id;
				$file_id = $this->crud->update('item', $f_data, $f_where_array);	
			}
			@unlink($_FILES[$file_element_name]);
		}*/
		//echo '<pre>';print_r($data);exit;
		print json_encode($return);
		exit;
	}

	function save_sub_item()
	{
		$post_data = $this->input->post();
		
		$sub_item_data = array(
			'item_id' => $post_data['item_id'],
			'item_qty' => $post_data['item_qty'],
			'item_unit_id' => $post_data['item_unit_id'],
			'sub_item_id' => $post_data['sub_item_id'],
			'sub_item_add_less' => $post_data['sub_item_add_less'],
			'sub_item_qty' => $post_data['sub_item_qty'],
			'item_level' => $post_data['item_level'],
			'sub_item_unit_id' => $post_data['sub_item_unit_id'],
			'created_by' => $this->logged_in_id,
			'user_created_by' => $this->session->userdata()['login_user_id'],
			'created_at' => $this->now_time,	
			'updated_by' => $this->logged_in_id,	
			'user_updated_by' => $this->session->userdata()['login_user_id'],	
			'updated_at' => $this->now_time,	
		);
		$sub_item_setting_id = $this->crud->get_column_value_by_id('sub_item_add_less_settings', 'id', array('item_id' => $_POST['item_id'],'sub_item_id' => $_POST['sub_item_id']));
        if($sub_item_setting_id > 0) {
        	unset($sub_item_data['created_by']);
        	unset($sub_item_data['user_created_by']);
        	unset($sub_item_data['created_at']);
        	$this->crud->update('sub_item_add_less_settings',$sub_item_data,array('id' => $sub_item_setting_id));
        } else {
        	unset($sub_item_data['updated_by']);
        	unset($sub_item_data['user_updated_by']);
        	unset($sub_item_data['updated_at']);
        	$this->crud->insert('sub_item_add_less_settings',$sub_item_data);	
        }
        echo json_encode(array("success" => "true","message" => "Sub Item Added Successfully"));
        exit();
	}

	function sub_item_datatable(){
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
		$isEdit = $this->applib->have_access_role(MASTER_ITEM_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_ITEM_ID, "delete");
        
        
        foreach ($list as $items) {
			$row = array();
			$action = '';
			
			$action .= ' <a href="javascript:void(0);" class="sub_item_edit_button btn-primary btn-xs"
						data-item_id="'.$items->item_id.'"
						data-item_qty="'.$items->item_qty.'"
						data-item_unit_id="'.$items->item_unit_id.'"
						data-sub_item_id="'.$items->sub_item_id.'" 
						data-sub_item_add_less="'.$items->sub_item_add_less.'"
						data-sub_item_qty="'.$items->sub_item_qty.'"
						data-item_level="'.$items->item_level.'"
						data-sub_item_unit_id="'.$items->sub_item_unit_id.'"
						><i class="fa fa-pencil"></i></a>';

			$action .= ' &nbsp; <a href="javascript:void(0);" class="sub_item_delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $items->id) . '"><i class="fa fa-trash"></i></a>';
			
			$row[] = $action;
			$row[] = $items->sub_item_name;
			$row[] = $items->item_level;
			$row[] = ($items->sub_item_add_less == QTY_ADD_ID?"Add":"Less");
			$row[] = $items->sub_item_qty;
			$row[] = $items->sub_item_unit;
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

	function item_list(){
		if($this->applib->have_access_role(MASTER_ITEM_ID,"view")) {
        	$data = array();
			set_page('item/item_list', $data);
        } else {
        	$this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
        	redirect('/');
        }
	}

	function item_datatable(){
		$config['table'] = 'item i';
		$config['select'] = 'i.item_id, i.item_name,i.current_stock_qty,i.shortname, it.item_type_name, ig.item_group_name, pu.pack_unit_name, cat.cat_name, sub_cat.sub_cat_name,i.sales_rate_val,i.purchase_rate_val,i.list_price,i.mrp';
		$config['column_order'] = array(null, 'i.item_name', 'i.current_stock_qty');
		$config['column_search'] = array('i.item_name','i.current_stock_qty','i.shortname', 'it.item_type_name','pu.pack_unit_name','i.sales_rate_val','i.purchase_rate_val','i.list_price','i.mrp');
		$config['wheres'][] = array('column_name' => 'i.created_by', 'column_value' => $this->logged_in_id);
		$config['joins'][] = array('join_table' => 'item_type it', 'join_by' => 'it.item_type_id = i.item_type_id', 'join_type' => 'left');
                $config['joins'][] = array('join_table' => 'item_group ig', 'join_by' => 'ig.item_group_id = i.item_group_id', 'join_type' => 'left');
                $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.cat_id = i.category_id', 'join_type' => 'left');
                $config['joins'][] = array('join_table' => 'sub_category sub_cat', 'join_by' => 'sub_cat.sub_cat_id = i.sub_category_id', 'join_type' => 'left');
		$config['joins'][] = array('join_table' => 'pack_unit pu', 'join_by' => 'pu.pack_unit_id = i.pack_unit_id', 'join_type' => 'left');
		$config['order'] = array('i.item_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		//echo $this->db->last_query();exit;
		$data = array();
		$isEdit = $this->applib->have_access_role(MASTER_ITEM_ID, "edit");
		$isDelete = $this->applib->have_access_role(MASTER_ITEM_ID, "delete");
        
        $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
        $li_category = isset($this->session->userdata()['li_category']) ? $this->session->userdata()['li_category'] : '0';
        $li_sub_category = isset($this->session->userdata()['li_sub_category']) ? $this->session->userdata()['li_sub_category'] : '0';
        $li_short_name = isset($this->session->userdata()['li_short_name']) ? $this->session->userdata()['li_short_name'] : '0';
        foreach ($list as $items) {
			$row = array();
			$action = '';
			if($isEdit) {
				$action .= '<form id="edit_' . $items->item_id . '" method="post" action="' . base_url() . 'item/item" style="width: 25px; display: initial;" >
                            <input type="hidden" name="item_id" id="item_id_' . $items->item_id . '" value="' . $items->item_id . '">
                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $items->item_id . '\').submit();" title="Edit Item"><i class="fa fa-edit"></i></a>
                        </form> ';
			}
			if($isDelete) {
				$action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $items->item_id) . '"><i class="fa fa-trash"></i></a>';
                                $action .= ' &nbsp; <input type="checkbox" value="'.$items->item_id.'" class="multi_delete" style="height:17px;width: 17px;">';
			}
			
			$action .= ' &nbsp; <a href="javascript:void(0);" class="sub_item_button btn-primary btn-xs" data-item_id="'.$items->item_id.'"><i class="fa fa-list"></i></a>';
			
			$row[] = $action;
			$row[] = $items->item_name;
            if($li_short_name == '1'){
                $row[] = $items->shortname;
            }
			$row[] = $items->current_stock_qty;
			$row[] = $items->item_type_name;
            if($li_item_group == '1'){
                $row[] = $items->item_group_name;
            }
            if($li_category == '1'){
                $row[] = $items->cat_name;
            }
            if($li_sub_category == '1'){
                $row[] = $items->sub_cat_name;
            }
			$row[] = $items->pack_unit_name;
			$row[] = $items->sales_rate_val;
			$row[] = $items->purchase_rate_val;
			$row[] = $items->list_price;
			$row[] = $items->mrp;
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
        
    function delete_multiple(){
        $data = json_decode($_POST['data']);
        if(!empty($data)){
            foreach ($data as $item){
                $this->crud->delete('item',array('item_id'=>$item));
            }
        }
//        echo '<pre>';print_r($_POST);exit;    
        exit;
    }
    
    function item_group_discount_on(){
        $return = array();
        $discount_on = $this->crud->get_column_value_by_id('item_group', 'discount_on', array('item_group_id' => $_POST['item_group_id']));
        if(!empty($discount_on)){
            $return['discount_on'] = $discount_on;
        } else {
            $return['discount_on'] = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['discount_on'];
        }
        echo json_encode($return);
        exit;
    }
	
}
