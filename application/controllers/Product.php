<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Product
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Product extends CI_Controller
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
	
	function product(){
		if(!empty($_POST['product_id']) && isset($_POST['product_id'])){
			$result = $this->crud->get_data_row_by_id('product','product_id',$_POST['product_id']);
			$data = array(
				'product_id' => $result->product_id,
				'company_id' => $result->company_id,
				'category_id' => $result->category_id,
				'sub_category_id' => $result->sub_category_id,
				'segment_id' => $result->segment_id,
				'product_name' => $result->product_name,
				'part_number' => $result->part_number,
				'hsn_code' => $result->hsn_code,
				'cgst_per' => $result->cgst_per,
				'sgst_per' => $result->sgst_per,
				'igst_per' => $result->igst_per,
				'vehicle_id' => $result->vehicle_id,
				'vehicle_model_id' => $result->vehicle_model_id,
				'plant_id' => $result->plant_id,
				'pack_unit_id' => $result->pack_unit_id,
				'product_image' => $result->product_image,
				'warranty' => $result->warranty,
				'guarantee' => $result->guarantee,
				'standerd_pack' => $result->standerd_pack,
				'oem_reference' => $result->oem_reference,
				'serialno_mandatory' => $result->serialno_mandatory,
				'purchase_rate' => $result->purchase_rate,
				'sell_rate' => $result->sell_rate,
				'dlp' => $result->dlp,
				'mrp' => $result->mrp,
				'below_stock' => $result->below_stock,
				'above_stock' => $result->above_stock,
				'maintain_stock' => $result->maintain_stock,
				'current_stock' => $result->current_stock,
				'location_id' => $result->location_id,
				'rack1_id' => $result->rack1_id,
				'rack2_id' => $result->rack2_id,
				'expected_days' => $result->expected_days,
				'product_desc' => $result->product_desc,
			);
			$related_products = $this->crud->get_result_where('related_product','product_id',$_POST['product_id']);
			$related_product_ids = array();
			foreach($related_products as $related_product){
				$related_product_ids[] = $related_product->related_product_id;
			}
			$data['related_product_ids'] = $related_product_ids;	
		}else{
			$data = array();	
		}
		//echo '<pre>';print_r($data);exit;
		set_page('product/product', $data);
	}
	
	function save_product(){
		$return = '';
		$post_data = $this->input->post();
		if(empty($post_data['product_id'])){
			$v_id = $this->crud->get_id_by_val('product','product_id','product_name',trim($post_data['product_name']));
			if(!empty($v_id)) {
				$return['error'] = "productExist";
				print json_encode($return);
				exit;
			}	
		}else{
			$v_id = $this->crud->get_id_by_val_not('product','product_id','product_name',trim($post_data['product_name']),$post_data['product_id']);
			if(!empty($v_id)) {
				$return['error'] = "productExist";
				print json_encode($return);
				exit;
			}
		}
		$company_id = null;
		if(isset($post_data['company_id']) && !empty($post_data['company_id'])){
			$company = $post_data['company_id'];
			if(!empty($company)){
				if(is_numeric($company)){
					$company_id = $company;
				} else {
					$company = trim($company);
					$where_company['company_name'] = $company;
					$res = $this->crud->get_all_with_where('company','','',$where_company);
					if(empty($res)){
						$insert_company['company_name'] = $company;
						$insert_company['created_at'] = $this->now_time;
						$insert_company['updated_at'] = $this->now_time;
						$insert_company['updated_by'] = $this->logged_in_id;
						$insert_company['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_company['created_by'] = $this->logged_in_id;
						$insert_company['user_created_by'] = $this->session->userdata()['login_user_id'];
						$company_id = $this->crud->insert('company', $insert_company);
						$res = $this->crud->get_all_with_where('company','','',$where_company);
						$company_id = $res[0]->company_id;
					} else {
						$company_id = $res[0]->company_id;
					}
				}
			}
		}
		$data['company_id'] = $company_id;
		
		$category_id = null;
		if(isset($post_data['category_id']) && !empty($post_data['category_id'])){
			$category = $post_data['category_id'];
			if(!empty($category)){
				if(is_numeric($category)){
					$category_id = $category;
				} else {
					$category = trim($category);
					$where_category['cat_name'] = $category;
					$res = $this->crud->get_all_with_where('category','','',$where_category);
					if(empty($res)){
						$insert_category['cat_name'] = $category;
						$insert_category['created_at'] = $this->now_time;
						$insert_category['updated_at'] = $this->now_time;
						$insert_category['updated_by'] = $this->logged_in_id;
						$insert_category['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_category['created_by'] = $this->logged_in_id;
						$insert_category['user_created_by'] = $this->session->userdata()['login_user_id'];
						$category_id = $this->crud->insert('category', $insert_category);
						$res = $this->crud->get_all_with_where('category','','',$where_category);
						$category_id = $res[0]->cat_id;
					} else {
						$category_id = $res[0]->cat_id;
					}
				}
			}
		}
		$data['category_id'] = $category_id;
		
		$sub_category_id = null;
		if(isset($post_data['sub_category_id']) && !empty($post_data['sub_category_id'])){
			$sub_category = $post_data['sub_category_id'];
			if(!empty($sub_category)){
				if(is_numeric($sub_category)){
					$sub_category_id = $sub_category;
				} else {
					$sub_category = trim($sub_category);
					$where_sub_category['cat_name'] = $sub_category;
					$res = $this->crud->get_all_with_where('category','','',$where_sub_category);
					if(empty($res)){
						$insert_sub_category['parent_cat_id'] = $category_id;
						$insert_sub_category['cat_name'] = $sub_category;
						$insert_sub_category['created_at'] = $this->now_time;
						$insert_sub_category['updated_at'] = $this->now_time;
						$insert_sub_category['updated_by'] = $this->logged_in_id;
						$insert_sub_category['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_sub_category['created_by'] = $this->logged_in_id;
						$insert_sub_category['user_created_by'] = $this->session->userdata()['login_user_id'];
						$sub_category_id = $this->crud->insert('category', $insert_sub_category);
						$res = $this->crud->get_all_with_where('category','','',$where_sub_category);
						$sub_category_id = $res[0]->cat_id;
					} else {
						$sub_category_id = $res[0]->cat_id;
					}
				}
			}
		}
		$data['sub_category_id'] = $sub_category_id;
		
		
		$segment_id = null;
		if(isset($post_data['segment_id']) && !empty($post_data['segment_id'])){
			$sagement = $post_data['segment_id'];
			if(!empty($sagement)){
				if(is_numeric($sagement)){
					$segment_id = $sagement;
				} else {
					$sagement = trim($sagement);
					$where_sagement['segment_name'] = $sagement;
					$res = $this->crud->get_all_with_where('segment','','',$where_sagement);
					if(empty($res)){
						$insert_segment['segment_name'] = $sagement;
						$insert_segment['created_at'] = $this->now_time;
						$insert_segment['updated_at'] = $this->now_time;
						$insert_segment['updated_by'] = $this->logged_in_id;
						$insert_segment['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_segment['created_by'] = $this->logged_in_id;
						$insert_segment['user_created_by'] = $this->session->userdata()['login_user_id'];
						$segment_id = $this->crud->insert('segment', $insert_segment);
						$res = $this->crud->get_all_with_where('segment','','',$where_sagement);
						$segment_id = $res[0]->segment_id;
					} else {
						$segment_id = $res[0]->segment_id;
					}
				}
			}
		}
		$data['segment_id'] = $segment_id;
		$vehicle_id = null;
		if(isset($post_data['vehicle_id']) && !empty($post_data['vehicle_id'])){
			$vehicle = $post_data['vehicle_id'];
			if(!empty($vehicle)){
				if(is_numeric($vehicle)){
					$vehicle_id = $vehicle;
				} else {
					$vehicle = trim($vehicle);
					$where_vehicle['vehicle_name'] = $vehicle;
					$res = $this->crud->get_all_with_where('vehicle','','',$where_vehicle);
					if(empty($res)){
						$insert_vehicle['vehicle_name'] = $vehicle;
						$insert_vehicle['created_at'] = $this->now_time;
						$insert_vehicle['updated_at'] = $this->now_time;
						$insert_vehicle['updated_by'] = $this->logged_in_id;
						$insert_vehicle['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_vehicle['created_by'] = $this->logged_in_id;
						$insert_vehicle['user_created_by'] = $this->session->userdata()['login_user_id'];
						$vehicle_id = $this->crud->insert('vehicle', $insert_vehicle);
						$res = $this->crud->get_all_with_where('vehicle','','',$where_vehicle);
						$vehicle_id = $res[0]->vehicle_id;
					} else {
						$vehicle_id = $res[0]->vehicle_id;
					}
				}
			}
		}
		$data['vehicle_id'] = $vehicle_id;
		$vehicle_model_id = null;
		if(isset($post_data['vehicle_model_id']) && !empty($post_data['vehicle_model_id'])){
			$vehicle_model = $post_data['vehicle_model_id'];
			if(!empty($vehicle_model)){
				if(is_numeric($vehicle_model)){
					$vehicle_model_id = $vehicle_model;
				} else {
					$vehicle_model = trim($vehicle_model);
					$where_vehicle_model['vehicle_model_name'] = $vehicle_model;
					$res = $this->crud->get_all_with_where('vehicle_model','','',$where_vehicle_model);
					if(empty($res)){
						$insert_vehicle_model['vehicle_id'] = $vehicle_id;
						$insert_vehicle_model['vehicle_model_name'] = $vehicle_model;
						$insert_vehicle_model['created_at'] = $this->now_time;
						$insert_vehicle_model['updated_at'] = $this->now_time;
						$insert_vehicle_model['updated_by'] = $this->logged_in_id;
						$insert_vehicle_model['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_vehicle_model['created_by'] = $this->logged_in_id;
						$insert_vehicle_model['user_created_by'] = $this->session->userdata()['login_user_id'];
						$vehicle_model_id = $this->crud->insert('vehicle_model', $insert_vehicle_model);
						$res = $this->crud->get_all_with_where('vehicle_model','','',$where_vehicle_model);
						$vehicle_model_id = $res[0]->vehicle_model_id;
					} else {
						$vehicle_model_id = $res[0]->vehicle_model_id;
					}
				}
			}
		}
		$data['vehicle_model_id'] = $vehicle_model_id;
		$plant_id = null;
		if(isset($post_data['plant_id']) && !empty($post_data['plant_id'])){
			$plant = $post_data['plant_id'];
			if(!empty($plant)){
				if(is_numeric($plant)){
					$plant_id = $plant;
				} else {
					$plant = trim($plant);
					$where_plant['plant_name'] = $plant;
					$res = $this->crud->get_all_with_where('plant','','',$where_plant);
					if(empty($res)){
						$insert_plant['plant_name'] = $plant;
						$insert_plant['created_at'] = $this->now_time;
						$insert_plant['updated_at'] = $this->now_time;
						$insert_plant['updated_by'] = $this->logged_in_id;
						$insert_plant['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_plant['created_by'] = $this->logged_in_id;
						$insert_plant['user_created_by'] = $this->session->userdata()['login_user_id'];
						$plant_id = $this->crud->insert('plant', $insert_plant);
						$res = $this->crud->get_all_with_where('plant','','',$where_plant);
						$plant_id = $res[0]->plant_id;
					} else {
						$plant_id = $res[0]->plant_id;
					}
				}
			}
		}
		$data['plant_id'] = $plant_id;
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
		$location_id = null;
		if(isset($post_data['location_id']) && !empty($post_data['location_id'])){
			$location = $post_data['location_id'];
			if(!empty($location)){
				if(is_numeric($location)){
					$location_id = $location;
				} else {
					$location = trim($location);
					$where_location['location_name'] = $location;
					$res = $this->crud->get_all_with_where('location','','',$where_location);
					if(empty($res)){
						$insert_location['location_name'] = $location;
						$insert_location['created_at'] = $this->now_time;
						$insert_location['updated_at'] = $this->now_time;
						$insert_location['updated_by'] = $this->logged_in_id;
						$insert_location['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_location['created_by'] = $this->logged_in_id;
						$insert_location['user_created_by'] = $this->session->userdata()['login_user_id'];
						$location_id = $this->crud->insert('location', $insert_location);
						$res = $this->crud->get_all_with_where('location','','',$where_location);
						$location_id = $res[0]->location_id;
					} else {
						$location_id = $res[0]->location_id;
					}
				}
			}
		}
		$data['location_id'] = $location_id;
		$rack1_id = null;
		if(isset($post_data['rack1_id']) && !empty($post_data['rack1_id'])){
			$rack1 = $post_data['rack1_id'];
			if(!empty($rack1)){
				if(is_numeric($rack1)){
					$rack1_id = $rack1;
				} else {
					$rack1 = trim($rack1);
					$where_rack1['rack_name'] = $rack1;
					$res = $this->crud->get_all_with_where('rack','','',$where_rack1);
					if(empty($res)){
						$insert_rack1['location_id'] = $location_id;
						$insert_rack1['rack_name'] = $rack1;
						$insert_rack1['created_at'] = $this->now_time;
						$insert_rack1['updated_at'] = $this->now_time;
						$insert_rack1['updated_by'] = $this->logged_in_id;
						$insert_rack1['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_rack1['created_by'] = $this->logged_in_id;
						$insert_rack1['user_created_by'] = $this->session->userdata()['login_user_id'];
						$rack1_id = $this->crud->insert('rack', $insert_rack1);
						$res = $this->crud->get_all_with_where('rack','','',$where_rack1);
						$rack1_id = $res[0]->rack_id;
					} else {
						$rack1_id = $res[0]->rack_id;
					}
				}
			}
		}
		$data['rack1_id'] = $rack1_id;
		$rack2_id = null;
		if(isset($post_data['rack2_id']) && !empty($post_data['rack2_id'])){
			$rack2 = $post_data['rack2_id'];
			if(!empty($rack2)){
				if(is_numeric($rack2)){
					$rack2_id = $rack2;
				} else {
					$rack2 = trim($rack2);
					$where_rack2['rack_name'] = $rack2;
					$res = $this->crud->get_all_with_where('rack','','',$where_rack2);
					if(empty($res)){
						$insert_rack2['location_id'] = $location_id;
						$insert_rack2['rack_name'] = $rack2;
						$insert_rack2['created_at'] = $this->now_time;
						$insert_rack2['updated_at'] = $this->now_time;
						$insert_rack2['updated_by'] = $this->logged_in_id;
						$insert_rack2['user_updated_by'] = $this->session->userdata()['login_user_id'];
						$insert_rack2['created_by'] = $this->logged_in_id;
						$insert_rack2['user_created_by'] = $this->session->userdata()['login_user_id'];
						$rack2_id = $this->crud->insert('rack', $insert_rack2);
						$res = $this->crud->get_all_with_where('rack','','',$where_rack2);
						$rack2_id = $res[0]->rack_id;
					} else {
						$rack2_id = $res[0]->rack_id;
					}
				}
			}
		}
		$data['rack2_id'] = $rack2_id;
		$data['product_name'] = $post_data['product_name'];
		$data['part_number'] = isset($post_data['part_number']) ? $post_data['part_number'] : null;
		$data['hsn_code'] = isset($post_data['hsn_code']) ? $post_data['hsn_code'] : null;
		$data['cgst_per'] = isset($post_data['cgst_per']) ? $post_data['cgst_per'] : null;
		$data['sgst_per'] = isset($post_data['sgst_per']) ? $post_data['sgst_per'] : null;
		$data['igst_per'] = isset($post_data['igst_per']) ? $post_data['igst_per'] : null;
		$data['warranty'] = isset($post_data['warranty']) ? $post_data['warranty'] : null;
		$data['guarantee'] = isset($post_data['guarantee']) ? $post_data['guarantee'] : null;
		$data['standerd_pack'] = isset($post_data['standerd_pack']) ? $post_data['standerd_pack'] : null;
		$data['oem_reference'] = isset($post_data['oem_reference']) ? $post_data['oem_reference'] : null;
		$data['serialno_mandatory'] = isset($post_data['serialno_mandatory']) ? $post_data['serialno_mandatory'] : null;
		$data['purchase_rate'] = isset($post_data['purchase_rate']) ? $post_data['purchase_rate'] : null;
		$data['sell_rate'] = isset($post_data['sell_rate']) ? $post_data['sell_rate'] : null;
		$data['dlp'] = isset($post_data['dlp']) ? $post_data['dlp'] : null;
		$data['mrp'] = isset($post_data['mrp']) ? $post_data['mrp'] : null;
		$data['below_stock'] = isset($post_data['below_stock']) ? $post_data['below_stock'] : null;
		$data['above_stock'] = isset($post_data['above_stock']) ? $post_data['above_stock'] : null;
		$data['maintain_stock'] = isset($post_data['maintain_stock']) ? $post_data['maintain_stock'] : null;
		$data['current_stock'] = isset($post_data['current_stock']) ? $post_data['current_stock'] : null;
		$data['expected_days'] = isset($post_data['expected_days']) ? $post_data['expected_days'] : null;
		$data['product_desc'] = isset($post_data['desc']) ? $post_data['desc'] : null;
		
		if(isset($post_data['product_id']) && !empty($post_data['product_id'])){
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$where_array['product_id'] = $post_data['product_id'];
			$result = $this->crud->update('product', $data, $where_array);
			if($result){
				$return['success'] = "Updated";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Product Updated Successfully');
				$last_query_id = $post_data['product_id'];
				$this->crud->delete('related_product', array('product_id' => $last_query_id));
				if(!empty($post_data['related_product'])){
					$related_product = $post_data['related_product'];
					$rp_data['product_id'] = $last_query_id;
					$rp_data['created_at'] = $this->now_time;
					$rp_data['updated_at'] = $this->now_time;
					$rp_data['updated_by'] = $this->logged_in_id;
					$rp_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
					$rp_data['created_by'] = $this->logged_in_id;
					$rp_data['user_created_by'] = $this->session->userdata()['login_user_id'];
					foreach($related_product as $value){
						$rp_data['related_product_id'] = $value;
						$this->crud->insert('related_product',$rp_data);
					}
				}
			}else{
				$return['error'] = "errorUpdated";
			}
		}else{
			$data['created_at'] = $this->now_time;
			$data['updated_at'] = $this->now_time;
			$data['updated_by'] = $this->logged_in_id;
			$data['user_updated_by'] = $this->session->userdata()['login_user_id'];
			$data['created_by'] = $this->logged_in_id;
			$data['user_created_by'] = $this->session->userdata()['login_user_id'];
			$result = $this->crud->insert('product',$data);
			if($result){
				$return['success'] = "Added";
				$this->session->set_flashdata('success',true);
				$this->session->set_flashdata('message','Product Added Successfully');
				$last_query_id = $this->db->insert_id();
				if(!empty($post_data['related_product'])){
					$related_product = $post_data['related_product'];
					$rp_data['product_id'] = $last_query_id;
					$rp_data['created_at'] = $this->now_time;
					$rp_data['updated_at'] = $this->now_time;
					$rp_data['updated_by'] = $this->logged_in_id;
					$rp_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
					$rp_data['created_by'] = $this->logged_in_id;
					$rp_data['user_created_by'] = $this->session->userdata()['login_user_id'];
					foreach($related_product as $value){
						$rp_data['related_product_id'] = $value;
						$this->crud->insert('related_product',$rp_data);
					}
				}
			}else{
				$return['error'] = "errorAdded";
			}
		}
		if($result){
			$file_element_name = 'product_image';
			$config['upload_path'] = './assets/uploads/images/products/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['overwrite'] = TRUE;
			$config['encrypt_name'] = FALSE;
			$config['remove_spaces'] = TRUE;
			$newFileName = $_FILES['product_image']['name'];
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
				$f_data['product_image'] = $file_data['file_name'];
				$f_where_array['product_id'] = $last_query_id;
				$file_id = $this->crud->update('product', $f_data, $f_where_array);	
			}
			@unlink($_FILES[$file_element_name]);
		}
		//echo '<pre>';print_r($data);exit;
		print json_encode($return);
		exit;
	}
	
	function product_list(){
		$data = array();
		set_page('product/product_list', $data);
	}
	
	function product_datatable(){
		$config['table'] = 'product p';
		$config['select'] = 'p.product_id, p.product_name, co.company_name, p.part_number, cat.cat_name as p_cat_name, scat.cat_name as s_cat_name, p.sell_rate, p.dlp, p.mrp, p.current_stock';
		$config['column_order'] = array(null, 'p.product_name', 'co.company_name', 'p.part_number', 'cat.cat_name', 'p.sell_rate', 'p.dlp', 'p.mrp', 'p.current_stock');
		//$config['column_order'] = array(null, 'v.vehicle_name', 'vm.vehicle_model_name');
		//$config['column_search'] = array('v.vehicle_name', 'vm.vehicle_model_name');
		$config['column_search'] = array('p.product_name', 'co.company_name', 'p.part_number', 'cat.cat_name', 'p.sell_rate', 'p.dlp', 'p.mrp', 'p.current_stock');
		$config['joins'][] = array('join_table' => 'company co', 'join_by' => 'co.company_id = p.company_id', 'join_type' => 'left');
		$config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.cat_id = p.category_id', 'join_type' => 'left');
		$config['joins'][] = array('join_table' => 'category scat', 'join_by' => 'scat.cat_id = p.sub_category_id', 'join_type' => 'left');
		$config['order'] = array('p.product_name' => 'desc');
		$this->load->library('datatables', $config, 'datatable');
		$list = $this->datatable->get_datatables();
		$data = array();
		//$isEdit = $this->app_model->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "edit");
		//$isDelete = $this->app_model->have_access_role(MASTER_ITEM_MASTER_MENU_ID, "delete");
		foreach ($list as $products) {
			$row = array();
			$action = '';
			$action .= '<form id="edit_' . $products->product_id . '" method="post" action="' . base_url() . 'product/product" style="width: 25px; display: initial;" >
                            <input type="hidden" name="product_id" id="product_id" value="' . $products->product_id . '">
                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $products->product_id . '\').submit();" title="Edit Product"><i class="fa fa-edit"></i></a>
                        </form> ';
			//$action .= ' | <a href="' . base_url('master/vehicle/' . $vehicles->vehicle_id) . '" class="edit_button btn-primary btn-xs" data-href="#"><i class="fa fa-edit"></i></a>';
			$action .= ' | <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete/' . $products->product_id) . '"><i class="fa fa-trash"></i></a>';
			$action .= ' | <form id="detail_' . $products->product_id . '" method="post" action="' . base_url() . 'product/product_detail" style="width: 25px; display: initial;" >
                            <input type="hidden" name="product_id" id="product_id" value="' . $products->product_id . '">
                            <a class="detail_button btn-info btn-xs" href="javascript:{}" onclick="document.getElementById(\'detail_' . $products->product_id . '\').submit();" title="Product Detail"><i class="fa fa-eye"></i></a>
                        </form>';
			$row[] = $action;
			$row[] = $products->product_name;
			$row[] = $products->company_name;
			$row[] = $products->part_number;
			$row[] = $products->p_cat_name;
			$row[] = $products->s_cat_name;
			$row[] = $products->sell_rate;
			$row[] = $products->dlp;
			$row[] = $products->mrp;
			$row[] = $products->dlp;
			$row[] = $products->current_stock;
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

	function product_import_export(){
		$data = array();
		$data['product_fields'] = $this->db->list_fields('product');  // 'product' is Table name
		set_page('product/product_import_export', $data);
	}
	function product_export(){
		$output_head = array();
		$wheres = array();
		$output = '';
		$fome_action = '';
		if($this->input->post() != null){
			$product_fields = $this->input->post();
			if($product_fields['action'] == 'demo'){
				$fome_action = 'demo';
			}
			unset($product_fields['action']);
			
			$product_field_arr = array();
			foreach($product_fields as $product_field => $val){
				$product_field_arr[] = $product_field;
				$product_field = str_replace('_', ' ', $product_field);
				$output_head[] = ucwords($product_field);
			}
			
			if($fome_action == 'demo'){
				if(($key = array_search('Created By', $output_head)) !== false) { unset($output_head[$key]); }
				if(($key = array_search('Created At', $output_head)) !== false) { unset($output_head[$key]); }
				if(($key = array_search('Updated By', $output_head)) !== false) { unset($output_head[$key]); }
				if(($key = array_search('Updated At', $output_head)) !== false) { unset($output_head[$key]); }
				$filename = "Product_import_demo.csv";
				header("Content-type: application/csv");
				header('Content-Disposition: attachment; filename='.$filename);
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen('php://output', 'w');
				fputcsv($handle, $output_head);
				fclose($handle);
				exit;
			}
			
			$products = $this->crud->get_specific_column_data('product', $product_field_arr);  // 'product' is Table name
			$field_total = count($product_field_arr);
			
			$filename = "Product.csv";
			header("Content-type: application/csv");
			header('Content-Disposition: attachment; filename='.$filename);
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen('php://output', 'w');
			fputcsv($handle, $output_head);
			foreach ($products as $product) {
				$output_fields = array();
				foreach($product_fields as $product_field => $val){
					$master_rack1 = strpos($product_field, '1_id');
					$master_rack2 = strpos($product_field, '2_id');
					$master_field = strpos($product_field, '_id');
					if ($master_rack1 !== false){
						$table_name = str_replace('1_id', '', $product_field);
						if(!empty($product->$product_field)){
							$product->$product_field = $this->crud->get_id_by_val($table_name, $table_name.'_name', $table_name.'_id', $product->$product_field);
						}
					} else if ($master_rack2 !== false){
						$table_name = str_replace('2_id', '', $product_field);
						if(!empty($product->$product_field)){
							$product->$product_field = $this->crud->get_id_by_val($table_name, $table_name.'_name', $table_name.'_id', $product->$product_field);
						}
					} else if ($master_field !== false){
						if($product_field !== 'product_id'){
							$table_name = str_replace('_id', '', $product_field);
							if(!empty($product->$product_field)){
								if($table_name == 'category'){
									$product->$product_field = $this->crud->get_id_by_val($table_name, 'cat_name', 'cat_id', $product->$product_field);
								} else {
									$product->$product_field = $this->crud->get_id_by_val($table_name, $table_name.'_name', $product_field, $product->$product_field);
								}
							}
						}
					}
					$output_fields[] = $product->$product_field;
				}
				fputcsv($handle, $output_fields);
			}
			fclose($handle);
			exit;
			//~ // Get The Field Name
			//~ $output .= '"'.implode('", "', $output_head).'"';
			//~ $output .="\n";
//~ 
			//~ // Get Records from the table
			//~ foreach($products as $product){
				//~ $product =  (array) $product;
				//~ $product =  array_values($product);
				//~ for ($i = 0; $i < $field_total; $i++) {
					//~ $output .='"'.$product["$i"].'",';
				//~ }
				//~ $output .="\n";
			//~ }
//~ 
			//~ // Download the file
			//~ $filename = "Product.csv";
			//~ header('Content-Description: File Transfer');
			//~ header('Content-Type: application/csv');
			//~ header('Content-Disposition: attachment; filename='.$filename);
			//~ header('Expires: 0');
			//~ header('Cache-Control: must-revalidate');
			//~ header('Pragma: public');
			//~ echo $output;
			exit;
		}
	}
	
	function product_import(){
		$data['product_fields'] = $this->db->list_fields('product');  // 'product' is Table name
		$count = 0;
		$import_fields = array();
		$i_data = array();
		$fp = fopen($_FILES['import_file']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024)){
			$count++;
            if($count == 1){
				for($i = 0, $j = count($csv_line); $i < $j; $i++){
					$field = strtolower($csv_line[$i]);
					$import_fields[] = str_replace(' ', '_', $field);
				}
                continue;
            }//keep this if condition if you want to remove the first row
            $insert_csv = array();
            $vehicle_id = 0;
            $location_id = 0;
            for($i = 0, $j = count($csv_line); $i < $j; $i++){
				$field_key = $import_fields[$i];
				$field_value = trim($csv_line[$i]);
                $master_rack1 = strpos($field_key, '1_id');
				$master_rack2 = strpos($field_key, '2_id');
				$master_field = strpos($field_key, '_id');
				if ($master_rack1 !== false){
					$table_name = str_replace('1_id', '', $field_key);
					if(!empty($field_value)){
						$field_value_id = $this->crud->get_id_by_val($table_name, $table_name.'_id', $table_name.'_name', $field_value);
						if(empty($field_value_id)){
							$insert_new = array();
							$insert_new['location_id'] = $location_id;
							$insert_new[$table_name.'_name'] = $field_value;
							$insert_new['created_at'] = $this->now_time;
							$insert_new['updated_at'] = $this->now_time;
							$insert_new['updated_by'] = $this->logged_in_id;
							$insert_new['user_updated_by'] = $this->session->userdata()['login_user_id'];
							$insert_new['created_by'] = $this->logged_in_id;
							$insert_new['user_created_by'] = $this->session->userdata()['login_user_id'];
							$result = $this->crud->insert($table_name, $insert_new);
							$field_value_id = $this->db->insert_id();
						}
						$field_value = $field_value_id;
					} else {
						$field_value = '';
					}
				} else if ($master_rack2 !== false){
					$table_name = str_replace('2_id', '', $field_key);
					if(!empty($field_value)){
						$field_value_id = $this->crud->get_id_by_val($table_name, $table_name.'_id', $table_name.'_name', $field_value);
						if(empty($field_value_id)){
							$insert_new = array();
							$insert_new['location_id'] = $location_id;
							$insert_new[$table_name.'_name'] = $field_value;
							$insert_new['created_at'] = $this->now_time;
							$insert_new['updated_at'] = $this->now_time;
							$insert_new['updated_by'] = $this->logged_in_id;
							$insert_new['user_updated_by'] = $this->session->userdata()['login_user_id'];
							$insert_new['created_by'] = $this->logged_in_id;
							$insert_new['user_created_by'] = $this->session->userdata()['login_user_id'];
							$result = $this->crud->insert($table_name, $insert_new);
							$field_value_id = $this->db->insert_id();
						}
						$field_value = $field_value_id;
					} else {
						$field_value = '';
					}
				} else if ($master_field !== false){
					if($field_key !== 'product_id'){
						$table_name = str_replace('_id', '', $field_key);
						if(!empty($field_value)){
							if($table_name == 'category'){
								$field_value_id = $this->crud->get_id_by_val($table_name, 'cat_id', 'cat_name', $field_value);
								if(empty($field_value_id)){
									$insert_new = array();
									$insert_new['cat_name'] = $field_value;
									$insert_new['created_at'] = $this->now_time;
									$insert_new['updated_at'] = $this->now_time;
									$insert_new['updated_by'] = $this->logged_in_id;
									$insert_new['user_updated_by'] = $this->session->userdata()['login_user_id'];
									$insert_new['created_by'] = $this->logged_in_id;
									$insert_new['user_created_by'] = $this->session->userdata()['login_user_id'];
									$result = $this->crud->insert($table_name, $insert_new);
									$field_value_id = $this->db->insert_id();
								}
								$field_value = $field_value_id;
							} else {
								$field_value_id = $this->crud->get_id_by_val($table_name, $field_key, $table_name.'_name', $field_value);
								if(empty($field_value_id)){
									$insert_new = array();
									if($table_name == 'vehicle_model'){ 
										$insert_new['vehicle_id'] = $vehicle_id;
									}
									$insert_new[$table_name.'_name'] = $field_value;
									$insert_new['created_at'] = $this->now_time;
									$insert_new['updated_at'] = $this->now_time;
									$insert_new['updated_by'] = $this->logged_in_id;
									$insert_new['user_updated_by'] = $this->session->userdata()['login_user_id'];
									$insert_new['created_by'] = $this->logged_in_id;
									$insert_new['user_created_by'] = $this->session->userdata()['login_user_id'];
									$result = $this->crud->insert($table_name, $insert_new);
									$field_value_id = $this->db->insert_id();
								}
								if($table_name == 'vehicle'){ $vehicle_id = $field_value_id; }
								if($table_name == 'location'){ $location_id = $field_value_id; }
								$field_value = $field_value_id;
							}
						} else {
							$field_value = '';
						}
					}
				}
                $insert_csv[$field_key] = $field_value;
            }
            $i++;
            if(isset($insert_csv['product_id'])){
				$insert_csv['updated_at'] = $this->now_time;
				$insert_csv['updated_by'] = $this->logged_in_id;
				$insert_csv['user_updated_by'] = $this->session->userdata()['login_user_id'];
				$where_array['product_id'] = $insert_csv['product_id'];
				unset($insert_csv['product_id']);
				$result = $this->crud->update('product', $insert_csv, $where_array);
			} else {
				$insert_csv['created_at'] = $this->now_time;
				$insert_csv['updated_at'] = $this->now_time;
				$insert_csv['updated_by'] = $this->logged_in_id;
				$insert_csv['user_updated_by'] = $this->session->userdata()['login_user_id'];
				$insert_csv['created_by'] = $this->logged_in_id;
				$insert_csv['user_created_by'] = $this->session->userdata()['login_user_id'];
				$this->crud->insert('product', $insert_csv);
			}
            //~ $i_data[] = $insert_csv;
        }
        //~ echo '<pre>';
		//~ print_r($i_data);
		//~ exit;
        fclose($fp) or die("can't close file");
		$this->session->set_flashdata('success',true);
		$this->session->set_flashdata('message','Product Import Successfully');
		set_page('product/product_import_export', $data);
	}
	
	function product_detail(){
		if(!empty($_POST['product_id']) && isset($_POST['product_id'])){
			$result = $this->crud->get_data_row_by_id('product','product_id',$_POST['product_id']);
			$data = array(
				'product_id' => $result->product_id,
				'company_name' => $this->crud->get_id_by_val('company', 'company_name', 'company_id', $result->company_id),
				'category_name' => $this->crud->get_id_by_val('category', 'cat_name', 'cat_id', $result->category_id),
				'sub_category_name' => $this->crud->get_id_by_val('category', 'cat_name', 'cat_id', $result->sub_category_id),
				'segment_name' => $this->crud->get_id_by_val('segment', 'segment_name', 'segment_id', $result->segment_id),
				'product_name' => $result->product_name,
				'part_number' => $result->part_number,
				'hsn_code' => $result->hsn_code,
				'cgst_per' => $result->cgst_per,
				'sgst_per' => $result->sgst_per,
				'igst_per' => $result->igst_per,
				'vehicle_name' => $this->crud->get_id_by_val('vehicle', 'vehicle_name', 'vehicle_id', $result->vehicle_id),
				'vehicle_model_name' => $this->crud->get_id_by_val('vehicle_model', 'vehicle_model_name', 'vehicle_model_id', $result->vehicle_model_id),
				'plant_name' => $this->crud->get_id_by_val('plant', 'plant_name', 'plant_id', $result->plant_id),
				'pack_unit_name' => $this->crud->get_id_by_val('pack_unit', 'pack_unit_name', 'pack_unit_id', $result->pack_unit_id),
				'product_image' => $result->product_image,
				'warranty' => $result->warranty,
				'guarantee' => $result->guarantee,
				'standerd_pack' => $result->standerd_pack,
				'oem_reference' => $result->oem_reference,
				'serialno_mandatory' => $result->serialno_mandatory,
				'purchase_rate' => $result->purchase_rate,
				'sell_rate' => $result->sell_rate,
				'dlp' => $result->dlp,
				'mrp' => $result->mrp,
				'below_stock' => $result->below_stock,
				'above_stock' => $result->above_stock,
				'maintain_stock' => $result->maintain_stock,
				'current_stock' => $result->current_stock,
				'location_name' => $this->crud->get_id_by_val('location', 'location_name', 'location_id', $result->location_id),
				'rack1_name' => $this->crud->get_id_by_val('rack', 'rack_name', 'rack_id', $result->rack1_id),
				'rack2_name' => $this->crud->get_id_by_val('rack', 'rack_name', 'rack_id', $result->rack2_id),
				'expected_days' => $result->expected_days,
				'product_desc' => $result->product_desc,
				'product_image' => $result->product_image,
			);
			$related_products = $this->crud->get_result_where('related_product','product_id',$_POST['product_id']);
			$related_product_ids = array();
			foreach($related_products as $related_product){
				$related_product_ids[] = $this->crud->get_id_by_val('product', 'product_name', 'product_id', $related_product->related_product_id);
			}
			$data['related_product'] = implode(', ',$related_product_ids);	
		}else{
			$data = array();	
		}
		set_page('product/product_detail', $data);
	}
	
}
