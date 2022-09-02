<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class App
 * &@property AppModel $app_model
 * &@property AppLib $applib
 * &@property Crud $crud
 */
class App extends CI_Controller{
	public $logged_in_id = null;
	public $now_time = null;
	function __construct(){
		parent::__construct();
		$this->load->model('Appmodel', 'app_model');
		$this->load->model('Crud', 'crud');
		if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
			redirect('/auth/login/');
		}
		$this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
		$this->now_time = date('Y-m-d H:i:s');
	}

	/**
     * @param $table_name
     * @param $id_column
     * @param $text_column
     * @param $search
     * @param int $page
     * @param array $where
     * @return array
     */
	function get_select2_data($table_name, $id_column, $text_column, $search, $page = 1, $where = array() , $where_in = array()){
		$party_select2_data = array();
		$resultCount = 10;
		$offset = ($page - 1) * $resultCount;
		$this->db->select("$id_column,$text_column");
		$this->db->from("$table_name");
		if (!empty($where)) {
			$this->db->where($where);
		}
		if(!empty($where_in)){
			$this->db->where_in($where_in['col'], $where_in['values']);
		}
		if ($table_name == 'user') {
			$this->db->where("isActive !=", 0);
			$this->db->like("$text_column", $search);
		} else {
			/*$this->db->like("$text_column", $search, 'after');*/
			$this->db->like("$text_column", $search);
		}
		$this->db->limit($resultCount, $offset);
		$this->db->order_by("$text_column");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $party_row) {
				$party_select2_data[] = array(
					'id' => $party_row->$id_column,
					'text' => $party_row->$text_column,
				);
			}
		}
		return $party_select2_data;
	}
    function get_select2_data_or_where($table_name, $id_column, $text_column, $search, $page = 1, $where = array(), $where1 = array()){
		$party_select2_data = array();
		$resultCount = 10;
		$offset = ($page - 1) * $resultCount;
		$this->db->select("$id_column,$text_column");
		$this->db->from("$table_name");		
		$this->db->where($where);
		$this->db->or_where($where1);
		$this->db->like("$text_column", $search);
		$this->db->limit($resultCount, $offset);
		$this->db->order_by("$text_column");
		$query = $this->db->get();
        
        
        
//        echo $this->db->last_query(); exit;
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $party_row) {
				$party_select2_data[] = array(
					'id' => $party_row->$id_column,
					'text' => $party_row->$text_column,
				);
			}
		}
		return $party_select2_data;
	}

	/**
     * @param $table_name
     * @param $id_column
     * @param $text_column
     * @param $id_column_val
     */
	function get_select2_text_by_id($table_name, $id_column, $text_column, $id_column_val){
		$this->db->select("$id_column,$text_column");
		$this->db->from("$table_name");
		$this->db->where($id_column, $id_column_val);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			echo json_encode(array('success' => true, 'id' => $id_column_val, 'text' => $query->row()->$text_column));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
		exit();
	}


	/**
     * @param $table_name
     * @param $id_column
     * @param $text_column
     * @param $search
     * @param array $where
     * @return mixed
     */
	function count_select2_data($table_name, $id_column, $text_column, $search, $where = array(), $where1 = array(), $where_in = array()){
		$this->db->select("$id_column");
		$this->db->from("$table_name");
		if (!empty($where)) {
			$this->db->where($where);
		}
		if (!empty($where1)) {
			$this->db->or_where($where1);
		}
		if(!empty($where_in)){
			$this->db->where_in($where_in['col'], $where_in['values']);
		}
		$this->db->like("$text_column", $search, 'after');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function vehicle_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('vehicle', 'vehicle_id', 'vehicle_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('vehicle', 'vehicle_id', 'vehicle_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_vehicle_select2_val_by_id($id){
		$this->get_select2_text_by_id('vehicle', 'vehicle_id', 'vehicle_name', $id);
	}

	function location_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('location', 'location_id', 'location_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('location', 'location_id', 'location_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_location_select2_val_by_id($id){
		$this->get_select2_text_by_id('location', 'location_id', 'location_name', $id);
	}

	function category_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
                //$this->db->where('created_by', $this->logged_in_id);
                $where = array('created_by' => $this->logged_in_id);
		$results = array(
			"results" => $this->get_select2_data('category', 'cat_id', 'cat_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('category', 'cat_id', 'cat_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_category_select2_val_by_id($id){
		$this->get_select2_text_by_id('category', 'cat_id', 'cat_name', $id);
	}

	function account_group_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//$where = array('parent_group_id' => 0);
                $where = '';
		$results = array(
			//"results" => $this->get_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $page),
			"results" => $this->get_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $where),
		);
		//echo '<pre>';print_r($results);exit;
		echo json_encode($results);
		exit();
	}
	function account_group_select2_source_for_account($arg = null){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if(is_numeric($arg)){
			$where = array('parent_group_id' => $arg);	
		}else{
			$where = '';	
		}
		
		$results = array(
			"results" => $this->get_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('account_group', 'account_group_id', 'account_group_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_account_group_select2_val_by_id($id){
		$this->get_select2_text_by_id('account_group', 'account_group_id', 'account_group_name', $id);
	}

	function company_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('company', 'id', 'company_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('company', 'id', 'company_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_company_select2_val_by_id($id){
		$this->get_select2_text_by_id('company', 'id', 'company_name', $id);
	}

	function sagement_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('segment', 'segment_id', 'segment_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('segment', 'segment_id', 'segment_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_sagement_select2_val_by_id($id){
		$this->get_select2_text_by_id('segment', 'segment_id', 'segment_name', $id);
	}

	function vehicle_model_select2_source($vehicle_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('vehicle_model', 'vehicle_model_id', 'vehicle_model_name', $search, $page, array('vehicle_id' => $vehicle_id)),
			"total_count" => $this->count_select2_data('vehicle_model', 'vehicle_model_id', 'vehicle_model_name', $search, array('vehicle_id' => $vehicle_id)),
		);
		echo json_encode($results);
		exit();
	}
	function set_vehicle_model_select2_val_by_id($id){
		$this->get_select2_text_by_id('vehicle_model', 'vehicle_model_id', 'vehicle_model_name', $id);
	}

	function plant_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('plant', 'plant_id', 'plant_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('plant', 'plant_id', 'plant_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_plant_select2_val_by_id($id){
		$this->get_select2_text_by_id('plant', 'plant_id', 'plant_name', $id);
	}

	function pack_unit_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('pack_unit', 'pack_unit_id', 'pack_unit_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('pack_unit', 'pack_unit_id', 'pack_unit_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_pack_unit_select2_val_by_id($id){
		$this->get_select2_text_by_id('pack_unit', 'pack_unit_id', 'pack_unit_name', $id);
	}

	function rack_select2_source($location_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('rack', 'rack_id', 'rack_name', $search, $page, array('location_id' => $location_id)),
			"total_count" => $this->count_select2_data('rack', 'rack_id', 'rack_name', $search, array('location_id' => $location_id)),
		);
		echo json_encode($results);
		exit();
	}
	function set_rack_select2_val_by_id($id){
		$this->get_select2_text_by_id('rack', 'rack_id', 'rack_name', $id);
	}

	function state_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('state', 'state_id', 'state_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('state', 'state_id', 'state_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_state_select2_val_by_id($id){
		$this->get_select2_text_by_id('state', 'state_id', 'state_name', $id);
	}

	function city_select2_source($state_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('city', 'city_id', 'city_name', $search, $page, array('state_id' => $state_id)),
			"total_count" => $this->count_select2_data('city', 'city_id', 'city_name', $search, array('state_id' => $state_id)),
		);
		echo json_encode($results);
		exit();
	}
	function set_city_select2_val_by_id($id){
		$this->get_select2_text_by_id('city', 'city_id', 'city_name', $id);
	}

	function related_item_select2_source($id = null){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		// $where = array('created_by' => $this->logged_in_id);
		if(!empty($id) && $id != null){
			$where['item_id != '] =  $id;
		}
		$results = array(
			"results" => $this->get_select2_data('item', 'item_id', 'item_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('item', 'item_id', 'item_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}

	function set_related_item_select2_val_by_id($id){
		$this->db->select('related_item.related_item_id, item.item_name');
		$this->db->from('related_item');
		$this->db->join('item', 'related_item.related_item_id = item.item_id');
		$this->db->where('related_item.item_id', $id);
		$query = $this->db->get();
		$row_data = array();
		if ($query->num_rows() > 0) {
			$inc = 0;
			foreach ($query->result() as $row) {
				$row_data[$inc]['id'] = $row->related_item_id;
				$row_data[$inc]['text'] = $row->item_name;
				$inc++;
			}
		} else {
			//$row_data[] = array('id' => '', 'text' => '--select--');
		}
		echo json_encode(array('success' => true, $row_data));
		exit();
	}
	
    function account_select2_source($arg = null) {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (is_numeric($arg)) {
            // $where = array('a.account_group_id' => $arg, 'a.created_by' => $this->logged_in_id);
            $where = array('a.account_group_id' => $arg);
        } else {
            // $where = array('a.created_by' => $this->logged_in_id);
        }

        $acc_res = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,c.city_name,a.account_gst_no");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        if (!empty($where)) {
                $this->db->where($where);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $acc_res[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name .' - '.$row->city_name .' - '.$row->account_gst_no,
                );
            }
        }

        $this->db->select("a.account_id");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        if (!empty($where)) {
            $this->db->where($where);
        }
        if($search) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }		
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $acc_res,
            "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }
    function gst_account_select2_source($arg = null) {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        // $where = array('a.created_by' => $this->logged_in_id);
        $acc_res = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,c.city_name,a.account_gst_no");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where_in('a.account_type','1,6,11,16');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $acc_res[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name.' - '.$row->account_gst_no,
                );
            }
        }

        $this->db->select("a.account_id");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        if (!empty($where)) {
            $this->db->where($where);
        }
        if($search) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }		
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $acc_res,
            "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }
    
    function account_select2_source_bill_wise($arg = null) {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (is_numeric($arg)) {
            $where = array('a.account_group_id' => $arg, 
			// 'a.created_by' => $this->logged_in_id, 
			'a.is_bill_wise' => '1');
        } else {
            $where = array(
				// 'a.created_by' => $this->logged_in_id,
				 'a.is_bill_wise' => '1');
        }

        $acc_res = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,c.city_name,a.account_gst_no");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        if (!empty($where)) {
                $this->db->where($where);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $acc_res[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name .' - '.$row->city_name .' - '.$row->account_gst_no,
                );
            }
        }

        $this->db->select("a.account_id");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        if (!empty($where)) {
            $this->db->where($where);
        }
        if($search) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }		
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $acc_res,
            "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }

    
	
    function sp_account_select2_source_old($arg = null) {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        if (is_numeric($arg)) {
            // $where = array('a.account_group_id' => $arg, 'a.created_by' => $this->logged_in_id);
			$where = array('a.account_group_id' => $arg);
        } else {
            // $where = array('a.created_by' => $this->logged_in_id);
        }
        $account_group_ids = $this->applib->sundry_creditors_debtors_cash_in_hand_account_group_ids();

        $acc_res = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("a.account_id,a.account_name,c.city_name,a.account_gst_no");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        $this->db->where_in('a.account_group_id',$account_group_ids);
        if (!empty($where)) {
                $this->db->where($where);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $acc_res[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name .' - '.$row->city_name .' - '.$row->account_gst_no,
                );
            }
        }

        $this->db->select("a.account_id");
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        $this->db->where_in('a.account_group_id',$account_group_ids);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if($search) {
            $this->db->group_start();
            $this->db->like("a.account_name", $search);
            $this->db->or_like("c.city_name", $search);
            $this->db->or_like("a.account_gst_no", $search);
            $this->db->group_end();
        }		
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $acc_res,
            "total_count" => $total_count,
        );
        echo json_encode($results);
        exit();
    }

	function sp_account_select2_source() {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $account_group_ids = $this->applib->sundry_creditors_debtors_cash_in_hand_account_group_ids();
        $results = array();
        $results[] = array(
			'id' => '',
			'text' => '',
			'account_name' => '',
			'account_city' => 'City',
			'account_gst_no' => 'GST No',
		);
		$resultCount = 10;
		$offset = ($page - 1) * $resultCount;
		$this->db->select("a.account_id,a.account_name,a.account_gst_no,c.city_name");
		$this->db->from("account a");
		$this->db->join("city c","c.city_id=a.account_city","left");
		// $this->db->where('a.created_by',$this->logged_in_id);
		$this->db->where_in('a.account_group_id',$account_group_ids);
		$this->db->where_not_in('a.account_group_id',array(SALES_BILL_ACCOUNT_GROUP_ID,PURCHASE_BILL_ACCOUNT_GROUP_ID,CREDIT_NOTE_BILL_ACCOUNT_GROUP_ID,DEBIT_NOTE_BILL_ACCOUNT_GROUP_ID));
		if(!empty($search)) {
			$this->db->group_start();
			$this->db->like("a.account_name", $search);	
			$this->db->or_like("a.account_gst_no",$search);
			$this->db->or_like("c.city_name",$search);
			$this->db->group_end();
		}
		$this->db->limit($resultCount, $offset);
		$this->db->order_by("a.account_name");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$results[] = array(
					'id' => $row->account_id,
					'text' => $row->account_name .' - '.$row->city_name .' - '.$row->account_gst_no,
					'account_name' => $row->account_name,
					'account_city' => (!empty($row->city_name)?$row->city_name:''),
					'account_gst_no' => (!empty($row->account_gst_no)?$row->account_gst_no:''),
				);
			}
		}
		
		$this->db->select("a.account_id");
		$this->db->from("account a");
		$this->db->join("city c","c.city_id = a.account_city","left");
		// $this->db->where('a.created_by',$this->logged_in_id);
		$this->db->where_in('a.account_group_id',$account_group_ids);
		$this->db->where_not_in('a.account_group_id',array(SALES_BILL_ACCOUNT_GROUP_ID,PURCHASE_BILL_ACCOUNT_GROUP_ID,CREDIT_NOTE_BILL_ACCOUNT_GROUP_ID,DEBIT_NOTE_BILL_ACCOUNT_GROUP_ID));
		if(!empty($search)) {
			$this->db->group_start();
			$this->db->like("a.account_name", $search);	
			$this->db->or_like("a.account_gst_no", $search);
			$this->db->or_like("c.city_name", $search);
			$this->db->group_end();
		}
		$query = $this->db->get();
		$total_count = $query->num_rows();

        $results = array(
            "results" => $results,
            "total_count" => ($total_count),
        );
        echo json_encode($results);
        exit();
    }

    function set_sp_account_select2_val_by_id($id){
		$this->db->select("a.account_id,a.account_name,a.account_gst_no,c.city_name");
		$this->db->from("account a");
		$this->db->join("city c","c.city_id=a.account_city","left");
		$this->db->where('a.account_id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		$data = array(
			'success' => true, 
			'id' => $query->row()->account_id, 
			'text' => $query->row()->account_id,
			'account_name' => $query->row()->account_name,
			'account_city' => (!empty($query->row()->city_name)?$query->row()->city_name:''),
			'account_gst_no' => (!empty($query->row()->account_gst_no)?$query->row()->account_gst_no:''),
		);
		echo json_encode($data);
		exit();
	}
	
	function bill_account_select2_source($against_account_group_id) {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        
        $where = array(
			'account_group_id' => $against_account_group_id,
			// 'created_by' =>  $this->logged_in_id
	);

        $results = array(
            "results" => $this->get_select2_data('account', 'account_id', 'account_name', $search, $page, $where),
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search, $where),
        );
        echo json_encode($results);
        exit();
    }

    function cash_bank_account_select2_source() {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where_array = array(CASH_IN_HAND_ACC_GROUP_ID,BANK_ACC_GROUP_ID);
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("account_id,account_name");
        $this->db->from("account");
        // $this->db->where('created_by', $this->logged_in_id);
        $this->db->where_in('account_group_id',$where_array);
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("account_id");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->account_id,
                    'text' => $row->account_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search),
        );
        
        echo json_encode($results);
        exit();
    }
        
    function set_account_select2_val_by_id($id){
        $this->db->select('a.account_id,a.account_name,c.city_name,a.account_gst_no');
        $this->db->from("account a");
        $this->db->join("city c","c.city_id=a.account_city","left");
        $this->db->where('a.account_id', $id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            echo json_encode(array('success' => true, 'id' => $query->row()->account_id, 'text' => $query->row()->account_name .' - '.$query->row()->city_name .' - '.$query->row()->account_gst_no));
            exit();
        }
        echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
        exit();
    }
	
	function li_item_count_select2_data($search, $where = array()){
		$this->db->select("item_id");
		$this->db->from("item");
		$this->db->where("(`item_name` LIKE '%$search%' OR `part_number` LIKE '%$search%')");
		$this->db->where($where);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	function li_item_select2_source($company_id = null){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		
		$party_select2_data = array();
		$resultCount = 10;
		$offset = ($page - 1) * $resultCount;
		$this->db->select("item_id,item_name,part_number");
		$this->db->from("item");
		$where = array();
		if (!empty($company_id)) {
			$where['company_id'] = $company_id;
		}
		$this->db->where("(`item_name` LIKE '%$search%' OR `part_number` LIKE '%$search%')");
		$this->db->where($where);
		$this->db->limit($resultCount, $offset);
		$this->db->order_by("item_name");
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $party_row) {
				$party_select2_data[] = array(
					'id' => $party_row->item_id,
					'text' => $party_row->item_name .' - '.$party_row->part_number,
				);
			}
		}
		
		$where = '';	
		$results = array(
			"results" => $party_select2_data,
			//"total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search, $where),
			"total_count" => $this->li_item_count_select2_data($search, $where = array()),
		);
		echo json_encode($results);
		exit();
	}
	function set_li_item_select2_val_by_id($id){
		$separate = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'separate', 'company_id' => $this->logged_in_id, 'module_name' => 2));
		$short_name = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'short_name', 'company_id' => $this->logged_in_id, 'module_name' => 2));

		$this->db->select("i.item_id, i.item_name, i.shortname, i.sales_rate_val, i.current_stock_qty,c.cat_name,sc.sub_cat_name,ig.item_group_name");
		$this->db->from("item i");
		$this->db->join('category c','c.cat_id=i.category_id','left');
    	$this->db->join('sub_category sc','sc.sub_cat_id=i.sub_category_id','left');
    	$this->db->join('item_group ig','ig.item_group_id=i.item_group_id','left');
		$this->db->where('i.item_id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$item_stock_data = $this->crud->get_item_stock_data($id);
			$item_name = '';
        	$closing_stock = $item_stock_data['closing_stock'];

        	if($separate != 1) {
	    		if(!empty($query->row()->item_group_name)) {
	    			$item_name .= $query->row()->item_group_name.' ';
	    		}
	    		if(!empty($query->row()->cat_name)) {
	    			$item_name .= $query->row()->cat_name.' ';
	    		}
	    		if(!empty($query->row()->sub_cat_name)) {
	    			$item_name .= $query->row()->sub_cat_name.' ';
	    		}
	    	}
            if(!empty($short_name) && $short_name == 1 && !empty($query->row()->shortname)) {
                $text = $query->row()->shortname. ' | ' .$query->row()->item_name. ' | ' .number_format((float) $query->row()->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                $item_name .= $query->row()->shortname;
            } else {
                $text = $query->row()->item_name. ' | ' .number_format((float) $query->row()->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                $item_name .= $query->row()->item_name;
            }

			echo json_encode(array('success' => true, 'id' => $id, 'text' => $item_name, 'item_name' => $item_name));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--', 'item_name' => ''));
		exit();
	}
	
	function get_standerd_pack(){
		$res = $this->app_model->get_id_by_val('item','standerd_pack','item_id',$_POST['item_id']);
		echo $res;
	}
	function get_item_detail(){
		$result = (array) $this->crud->get_data_row_by_id('item','item_id',$_POST['item_id']);
        if($result['discount_on'] == '1') {
            $rate = $result['list_price'];
        } else {
            $rate = $result['mrp'];
        }
        $result['mrp'] = $result['mrp'];
        $result['rate'] = $rate;
		$account_detail = (array) $this->crud->get_data_row_by_id('account','account_id',$_POST['account_id']);
		if(empty($account_detail['account_state'])){ $account_detail['account_state'] = 0; }
		$result['account_state'] = $account_detail['account_state'];
		$user_detail = (array) $this->crud->get_data_row_by_id('user','user_id',$this->logged_in_id);
		if(empty($user_detail['state'])){ $user_detail['state'] = 0; } 
		$result['user_state'] = $user_detail['state'];
        $item_id = $_POST['item_id'];
        $account_id = $_POST['account_id'];
        $item_group_id = $_POST['item_group_id'];
        $sale_dis = '';
        if(isset($item_group_id) && !empty($item_group_id)){
            $discount1 = $this->crud->get_column_value_by_id('discount', 'discount', array('account_id' => $account_id, 'item_group_id' => $item_group_id, 'item_id' => $item_id));
            if(!empty($discount1)){
                $sale_dis = $discount1;
            } else {
                $discount3 = $this->crud->get_column_value_by_id('discount', 'discount', array('account_id' => $account_id, 'item_group_id' => $item_group_id));
                if(!empty($discount3)){
                    $sale_dis = $discount3;
                } else {
                    $discount4 = $this->crud->get_column_value_by_id('item', 'discount_per', array('item_group_id' => $item_group_id, 'item_id' => $item_id));
                    if(!empty($discount4)){
                        $sale_dis = $discount4;
                    } else {
                        $sale_dis = '';
                    }
                }
            }
        } else {
            $discount5 = $this->crud->get_column_value_by_id('item', 'discount_per', array('item_id' => $item_id));
            if(!empty($discount5)){
                $sale_dis = $discount5;
            } else {
                $sale_dis = '';
            }
        }
        $result['sales_dis'] = $sale_dis;
		print json_encode($result);
		exit;
	}
	function check_stock(){
		$item_id = $_POST['item_id'];
		$item_qty = $_POST['item_qty'];
		$item_data = $this->crud->get_row_by_id('item', array('item_id' => $item_id));
		$current_stock = $item_data[0]->current_stock;
		$below_stock = $item_data[0]->below_stock;
		$above_stock = $item_data[0]->above_stock;
		$new_qty = $current_stock + $item_qty;
		if($below_stock > $new_qty){
			$return['error'] = $item_data[0]->item_name . " - Below Stock ! ";
		} else if($above_stock < $new_qty){
			$return['error'] = $item_data[0]->item_name . " - Above Stock ! ";
		}
		print json_encode($return);
		exit;
	}
	
	function sub_cat_select2_source($parent_cat_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('category', 'cat_id', 'cat_name', $search, $page, array('parent_cat_id' => $parent_cat_id)),
			"total_count" => $this->count_select2_data('category', 'cat_id', 'cat_name', $search, array('parent_cat_id' => $parent_cat_id)),
		);
		echo json_encode($results);
		exit();
	}
	function set_sub_cat_select2_val_by_id($id){
		$this->get_select2_text_by_id('category', 'cat_id', 'cat_name', $id);
	}
	
	function item_select2_source(){
        $separate = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'separate', 
		'company_id' => $this->logged_in_id,
		 'module_name' => 2));

        $short_name = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'short_name', 'company_id' => $this->logged_in_id, 'module_name' => 2));

        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("i.item_id, i.item_name, i.shortname, i.sales_rate_val, i.current_stock_qty,c.cat_name,sc.sub_cat_name,ig.item_group_name");
        $this->db->from("item i");
        $this->db->join('category c','c.cat_id=i.category_id','left');
    	$this->db->join('sub_category sc','sc.sub_cat_id=i.sub_category_id','left');
    	$this->db->join('item_group ig','ig.item_group_id=i.item_group_id','left');
        // $this->db->where('i.created_by', $this->logged_in_id);
        
        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('i.item_name',$search);
	        if(!empty($short_name) && $short_name == 1) {
	            $this->db->or_like('i.shortname',$search);
	        }
	        if($separate != 1) {
	        	$this->db->or_where('c.cat_name LIKE "%'.$search.'%"');
	        	$this->db->or_where('sc.sub_cat_name LIKE "%'.$search.'%"');
	        	$this->db->or_where('ig.item_group_name LIKE "%'.$search.'%"');
	        }
	        $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_name");
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
//            	$item_stock_data = $this->crud->get_item_stock_data($row->item_id);

//            	$closing_stock = $item_stock_data['closing_stock'];
            	$text = '';
            	if($separate != 1) {
            		if(!empty($row->item_group_name)) {
            			$text .= $row->item_group_name.' ';
            		}
            		if(!empty($row->cat_name)) {
            			$text .= $row->cat_name.' ';
            		}
            		if(!empty($row->sub_cat_name)) {
            			$text .= $row->sub_cat_name.' ';
            		}
            	}
                if(!empty($short_name) && $short_name == 1 && !empty($row->shortname)) {
					//$text = $row->shortname. ' | ' .$row->item_name. ' | ' .number_format((float) $row->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                    $text .= $row->shortname;
                } else {
					//$text = $row->item_name. ' | ' .number_format((float) $row->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                    $text .= $row->item_name;
                }
                $select2_data[] = array(
                    'id' => $row->item_id,
                    'text' => $text,
                );
            }
        }


        $this->db->select("i.item_id");
        $this->db->from("item i");
        $this->db->join('category c','c.cat_id=i.category_id','left');
    	$this->db->join('sub_category sc','sc.sub_cat_id=i.sub_category_id','left');
    	$this->db->join('item_group ig','ig.item_group_id=i.item_group_id','left');
        // $this->db->where('i.created_by',$this->logged_in_id);
        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('i.item_name',$search);
	        if(!empty($short_name) && $short_name == 1) {
	            $this->db->or_like('i.shortname',$search);
	        }
	        if($separate != 1) {
	        	$this->db->or_where('c.cat_name LIKE "%'.$search.'%"');
	        	$this->db->or_where('sc.sub_cat_name LIKE "%'.$search.'%"');
	        	$this->db->or_where('ig.item_group_name LIKE "%'.$search.'%"');
	        }
	        $this->db->group_end();
        }
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $select2_data,
            "total_count" => $total_count,
        );
        
		echo json_encode($results);
		exit();
	}
    
	function set_item_select2_val_by_id($id){
		$this->get_select2_text_by_id('item', 'item_id', 'item_name', $id);
	}
	
	function item_type_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('item_type', 'item_type_id', 'item_type_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('item_type', 'item_type_id', 'item_type_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_item_type_select2_val_by_id($id){
		$this->get_select2_text_by_id('item_type', 'item_type_id', 'item_type_name', $id);
	}
		
	function user_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('user', 'user_id', 'user_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('user', 'user_id', 'user_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
        
	function invoice_type_select2_source(){
            $search = isset($_GET['q']) ? $_GET['q'] : '';
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $where = '';
            $results = array(
                    "results" => $this->get_select2_data('invoice_type', 'invoice_type_id', 'invoice_type', $search, $page, $where),
                    "total_count" => $this->count_select2_data('invoice_type', 'invoice_type_id', 'invoice_type', $search, $where),
            );
            echo json_encode($results);
            exit();
	}
        
	function set_invoice_type_select2_val_by_id($id){
		$this->get_select2_text_by_id('invoice_type', 'invoice_type_id', 'invoice_type', $id);
	}

	function set_our_bank_label_select2_val_by_id($id){
		$this->get_select2_text_by_id('account', 'account_id', 'account_name', $id);
	}
        
	function set_user_select2_val_by_id($id){
		$this->get_select2_text_by_id('user', 'user_id', 'user_name', $id);
	}

	function unit_select2_source_by_item_id($item_id = null){
		$results = array();
		if(!empty($item_id)){
			$item_detail = $this->crud->get_row_by_id('item', array('item_id' => $item_id));
			$options = array();
			$count = 0;
			if(!empty($item_detail[0]->alternate_unit_id)){
				$unit_detail = $this->crud->get_row_by_id('pack_unit', array('pack_unit_id' => $item_detail[0]->alternate_unit_id));
				$options[] = array('id' => $item_detail[0]->alternate_unit_id, 'text' => $unit_detail[0]->pack_unit_name);
				$count += 1;
			}
			if(!empty($item_detail[0]->pack_unit_id)){
				$unit_detail = $this->crud->get_row_by_id('pack_unit', array('pack_unit_id' => $item_detail[0]->pack_unit_id));
				$options[] = array('id' => $item_detail[0]->pack_unit_id, 'text' => $unit_detail[0]->pack_unit_name);
				$count += 1;
			}
			$results['results'] = $options;
			$results['total_count'] = $count;
		}
		echo json_encode($results);
		exit();
	}



        function item_group_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		// $where = array('created_by' => $this->logged_in_id);
		$results = array(
			"results" => $this->get_select2_data('item_group', 'item_group_id', 'item_group_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('item_group', 'item_group_id', 'item_group_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_item_group_select2_val_by_id($id){
		$this->get_select2_text_by_id('item_group', 'item_group_id', 'item_group_name', $id);
	}
    
    function item_select2_source_from_item_group($item_group_id){
    	$short_name = $this->crud->get_column_value_by_id('company_settings', 'setting_value', 
		array(
			'setting_key' => 'short_name',
			//  'company_id' => $this->logged_in_id,
		 'module_name' => 2));
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("item_id, item_name, shortname, sales_rate_val, current_stock_qty");
        $this->db->from("item");
        // $this->db->where('created_by', $this->logged_in_id);
        if(!empty($item_group_id) && $item_group_id != 'null'){
            $this->db->where('item_group_id', $item_group_id);
        }

        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('item_name',$search);
	        if(!empty($short_name) && $short_name == 1) {
	            $this->db->or_like('shortname',$search);
	        }
	        $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_name");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
            	$item_stock_data = $this->crud->get_item_stock_data($row->item_id);

            	$closing_stock = $item_stock_data['closing_stock'];

                if(!empty($short_name) && $short_name == 1 && !empty($row->shortname)) {
//                    $text = $row->shortname. ' | ' .$row->item_name. ' | ' .number_format((float) $row->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                    $text = $row->shortname;
                } else {
//                    $text = $row->item_name. ' | ' .number_format((float) $row->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                    $text = $row->item_name;
                }
                $select2_data[] = array(
                    'id' => $row->item_id,
                    'text' => $text,
                );
            }
        }


        $this->db->select("item_id");
        $this->db->from("item");
        // $this->db->where('created_by',$this->logged_in_id);
        $this->db->where('item_group_id', $item_group_id);

        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('item_name',$search);
	        if(!empty($short_name) && $short_name == 1) {
	            $this->db->or_like('shortname',$search);
	        }
	        $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_id");
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $select2_data,
            "total_count" => $total_count,
        );
        
		echo json_encode($results);
		exit();
	}
    
    function prefix_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = array('company_id' => $this->logged_in_id);
		$results = array(
			"results" => $this->get_select2_data('company_invoice_prefix', 'id', 'prefix_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('company_invoice_prefix', 'id', 'prefix_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_prefix_select2_val_by_id($id = ''){
                if(empty($id)){
                    $id = $this->crud->get_column_value_by_id('company_invoice_prefix', 'id',array(
						'company_id'=>$this->logged_in_id,
						 'is_default' => 1));
                }
		$this->get_select2_text_by_id('company_invoice_prefix', 'id', 'prefix_name', $id);
	}
    
    function get_select2_text_by_id_multiple($table_name, $id_column, $text_column, $id_column_val){
		$this->db->select("$id_column,$text_column");
		$this->db->from("$table_name");
		$this->db->where_in($id_column, $id_column_val);
		$query = $this->db->get();
		$row_data = array();
		if ($query->num_rows() > 0) {
			$inc = 0;
			foreach ($query->result() as $row) {
				$row_data[$inc]['id'] = $row->$id_column;
				$row_data[$inc]['text'] = $row->$text_column;
				$inc++;
			}
		} else {
			//$row_data[] = array('id' => '', 'text' => '--select--');
		}
		echo json_encode(array('success' => true, $row_data));
		exit();
	}
    
    function set_account_id_select2_multi_val_by_id($ids){
        $myArray = explode(',', $ids);
        $this->get_select2_text_by_id_multiple('account', 'account_id', 'account_name', $myArray);
    }
    
    function sub_category_select2_source($cat_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('cat_id' => $cat_id);
		$results = array(
			"results" => $this->get_select2_data('sub_category', 'sub_cat_id', 'sub_cat_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('sub_category', 'sub_cat_id', 'sub_cat_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
    
	function set_sub_category_select2_val_by_id($id){
		$this->get_select2_text_by_id('sub_category', 'sub_cat_id', 'sub_cat_name', $id);
	}
        
    function item_select2_source_without_item_group() {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        // $where = array('created_by' => $this->logged_in_id);
        $results = array(
            "results" => $this->get_select2_data('item', 'item_id', 'item_name', $search, $page, $where),
            "total_count" => $this->count_select2_data('item', 'item_id', 'item_name', $search, $where),
        );
        echo json_encode($results);
        exit();
    }
    
    function set_category_from_item_select2_source($item_id, $item_group_id = ''){
        $this->db->select("cat_id, cat_name");
        $this->db->from("item i");
        $this->db->join('category c', 'c.cat_id = i.category_id');
        if($item_group_id != ''){
            $this->db->where('i.item_group_id', $item_group_id);
        }
        $this->db->where('i.item_id', $item_id);
        $this->db->where('c.created_by', $this->logged_in_id);
		$query = $this->db->get();
        if ($query->num_rows() > 0) {
			echo json_encode(array('success' => true, 'id' => $query->row()->cat_id, 'text' => $query->row()->cat_name));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
		exit();
	}
    
    function set_sub_category_from_item_select2_source($item_id, $item_group_id = ''){
        $this->db->select("sub_cat_id, sub_cat_name");
        $this->db->from("item i");
        $this->db->join('sub_category c', 'c.sub_cat_id = i.sub_category_id');
        if($item_group_id != ''){
            $this->db->where('i.item_group_id', $item_group_id);
        }
        $this->db->where('i.item_id', $item_id);
        $this->db->where('c.created_by', $this->logged_in_id);
		$query = $this->db->get();
        if ($query->num_rows() > 0) {
			echo json_encode(array('success' => true, 'id' => $query->row()->sub_cat_id, 'text' => $query->row()->sub_cat_name));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--'));
		exit();
	}
    
    function category_from_item_select2_source($item_id, $item_group_id = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("cat_id, cat_name");
        $this->db->from("item i");
        $this->db->join('category c', 'c.cat_id = i.category_id');
        if($item_group_id != ''){
            $this->db->where('i.item_group_id', $item_group_id);
        }
        $this->db->where('i.item_id', $item_id);
        $this->db->where('c.created_by', $this->logged_in_id);
        $this->db->group_start();
            $this->db->like('cat_name', $search);
        $this->db->group_end();
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("cat_id");
        $query = $this->db->get();
//        echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->cat_id,
                    'text' => $row->cat_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('category', 'cat_id', 'cat_name', $search),
        );
		echo json_encode($results);
		exit();
	}
    
    function sub_category_from_item_select2_source($item_id, $cat_id = '', $item_group_id = ''){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("sub_cat_id, sub_cat_name");
        $this->db->from("item i");
        $this->db->join('sub_category c', 'c.sub_cat_id = i.category_id');
        if($item_group_id != ''){
            $this->db->where('i.item_group_id', $item_group_id);
        }
        $this->db->where('i.item_id', $item_id);
        $this->db->where('c.created_by', $this->logged_in_id);
        $this->db->group_start();
            $this->db->like('sub_cat_name', $search);
        $this->db->group_end();
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("sub_cat_id");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $select2_data[] = array(
                    'id' => $row->sub_cat_id,
                    'text' => $row->sub_cat_name,
                );
            }
        }
        $results = array(
            "results" => $select2_data,
            "total_count" => $this->count_select2_data('sub_category', 'sub_cat_id', 'sub_cat_name', $search),
        );
        if(empty($results['results'])){
            $where = array();
            if($cat_id != ''){
                $where = array('cat_id' => $cat_id);
            }
            $results = array(
                "results" => $this->get_select2_data('sub_category', 'sub_cat_id', 'sub_cat_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('sub_category', 'sub_cat_id', 'sub_cat_name', $search, $where),
            );
        }
		echo json_encode($results);
		exit();
	}
        
    function item_select2_source_from_category($cat_id){
		$short_name = $this->crud->get_column_value_by_id('company_settings', 'setting_value', array('setting_key' => 'short_name', 'company_id' => $this->logged_in_id, 'module_name' => 2));
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("item_id, item_name, shortname, sales_rate_val, current_stock_qty");
        $this->db->from("item");
        $this->db->where('created_by', $this->logged_in_id);
        $this->db->where('category_id', $cat_id);
        
        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('item_name',$search);
	        if(!empty($short_name) && $short_name == 1) {
	            $this->db->or_like('shortname',$search);
	        }
	        $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_name");
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
            	$item_stock_data = $this->crud->get_item_stock_data($row->item_id);

            	$closing_stock = $item_stock_data['closing_stock'];

                if(!empty($short_name) && $short_name == 1 && !empty($row->shortname)) {
                    $text = $row->shortname. ' | ' .$row->item_name. ' | ' .number_format((float) $row->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                } else {
                    $text = $row->item_name. ' | ' .number_format((float) $row->sales_rate_val, 2, '.', ''). ' | ' .$closing_stock;
                }
                $select2_data[] = array(
                    'id' => $row->item_id,
                    'text' => $text,
                );
            }
        }


        $this->db->select("item_id");
        $this->db->from("item");
        // $this->db->where('created_by',$this->logged_in_id);
        $this->db->where('category_id', $cat_id);
        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('item_name',$search);
	        if(!empty($short_name) && $short_name == 1) {
	            $this->db->or_like('shortname',$search);
	        }
	        $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_id");
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $select2_data,
            "total_count" => $total_count,
        );
        
		echo json_encode($results);
		exit();
	}
        
        function item_select2_source_from_sub_category($sub_cat_id){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = array(
			'sub_category_id' => $sub_cat_id,
			// 'created_by' => $this->logged_in_id
		);
		$results = array(
			"results" => $this->get_select2_data('item', 'item_id', 'item_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('item', 'item_id', 'item_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}

        function hsn_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('hsn', 'hsn_id', 'hsn', $search, $page, $where),
			"total_count" => $this->count_select2_data('hsn', 'hsn_id', 'hsn', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_hsn_select2_val_by_id($id){
		$this->get_select2_text_by_id('hsn', 'hsn_id', 'hsn', $id);
	}

	function get_account_balance($account_id){
		$balance = $this->crud->get_account_balance($account_id);
		if($balance > 0) {
			$balance = number_format((float)$balance, 2, '.', '');
			$balance .= ' Debit';
		} else {
			if($balance != 0) {
				$balance = abs($balance);
				$balance = number_format((float)$balance, 2, '.', '');
				$balance .= ' Credit';
			}
		}
		$cash_in_hand_acc = $this->applib->is_cash_in_hand_account($account_id);
		echo json_encode(array('balance' => $balance,'cash_in_hand_acc' => $cash_in_hand_acc));
		exit();
	}

	function get_item_stock_data_by_status($id){
		$in_stock_qty = 0;
		$in_wip_qty = 0;
		$in_work_done_qty = 0;
		$item_minimum_stock = 0;

		$minimum_qty = $this->crud->get_val_by_id('item',$id,'item_id','minimum');
		if(!empty($minimum_qty)) {
			$item_minimum_stock = $minimum_qty;
		}
		$current_stock_qty = $this->crud->get_val_by_id('item',$id,'item_id','current_stock_qty');
		if(!empty($current_stock_qty)) {
			$in_stock_qty = $current_stock_qty;
		}

		$this->db->select('SUM(qty) as total_qty');
		$this->db->from('stock_status_change');
		$this->db->where('item_id',$id);
		$this->db->where('to_status',IN_WIP_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$in_wip_qty = $query->row()->total_qty;
		}

		$this->db->select('SUM(qty) as total_qty');
		$this->db->from('stock_status_change');
		$this->db->where('item_id',$id);
		$this->db->where('to_status',IN_WORK_DONE_ID);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$in_work_done_qty = $query->row()->total_qty;
		}

		$this->db->select('*');
		$this->db->from('sub_item_add_less_settings');
		$this->db->where('sub_item_id',$id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach ($query->result() as $key => $row) {
				$this->db->select('SUM(qty) as total_qty');
				$this->db->from('stock_status_change');
				$this->db->where('item_id',$row->item_id);
				$this->db->where('to_status',IN_WIP_ID);
				$query = $this->db->get();
				if($query->num_rows() > 0) {
					$tmp_qty = $query->row()->total_qty;
					if($row->sub_item_add_less == QTY_LESS_ID) {
						$tmp_qty = $tmp_qty * -1;
					}
					$in_wip_qty += ($row->sub_item_qty / $row->item_qty) * $tmp_qty;
				}

				$this->db->select('SUM(qty) as total_qty');
				$this->db->from('stock_status_change');
				$this->db->where('item_id',$row->item_id);
				$this->db->where('to_status',IN_WORK_DONE_ID);
				$query = $this->db->get();
				if($query->num_rows() > 0) {
					$tmp_qty = $query->row()->total_qty;
					if($row->sub_item_add_less == QTY_LESS_ID) {
						$tmp_qty = $tmp_qty * -1;
					}
					$in_work_done_qty += ($row->sub_item_qty / $row->item_qty) * $tmp_qty;
				}
			}
		}

		echo json_encode(array(
			'in_stock_qty' => (int) $in_stock_qty,
			'in_wip_qty' => (int) $in_wip_qty,
			'in_work_done_qty' => (int) $in_work_done_qty,
			'item_minimum_stock' => (int) $item_minimum_stock,
		));
		exit();
	}

	function group_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$results = array(
			"results" => $this->get_select2_data('groups', 'id', 'group_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('groups', 'id', 'group_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}
	function set_group_select2_val_by_id($id){
		$this->get_select2_text_by_id('groups', 'id', 'group_name', $id);
	}

	function new_item_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $select2_data = array();
        $resultCount = 10;
        $offset = ($page - 1) * $resultCount;
        $this->db->select("i.item_id, i.item_name, g.group_name, c.company_name, pu.pack_unit_name");
        $this->db->from("item i");
        $this->db->join('company c','c.id=i.company_id','left');
		$this->db->join('groups g','g.id=i.group_id','left');
		$this->db->join('pack_unit pu','pu.pack_unit_id=i.alternate_unit_id','left');
        // $this->db->where('i.created_by', $this->logged_in_id);
		if(isset($_GET['item_code'])){
			$this->db->where('i.item_code', $_GET['item_code']);
		}
        
        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('i.item_name',$search);
	        $this->db->group_end();
        }
        $this->db->limit($resultCount, $offset);
        $this->db->order_by("item_name");
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
            	$text = '';
				if(!empty($row->company_name)) {
					$text .= $row->company_name;
				}
				if(!empty($row->group_name)) {
					$text .= ' - '.$row->group_name;
				}
				if(!empty($row->item_name)) {
					$text .= ' - '.$row->item_name;
				}
				if(!empty($row->pack_unit_name)) {
					$text .= ' - '.$row->pack_unit_name;
				}
                $select2_data[] = array(
                    'id' => $row->item_id,
                    'text' => $text,
                );
            }
        }

        $this->db->select("i.item_id");
        $this->db->from("item i");
        $this->db->join('company c','c.id=i.company_id','left');
		$this->db->join('groups g','g.id=i.group_id','left');
		$this->db->join('pack_unit pu','pu.pack_unit_id=i.alternate_unit_id','left');
        // $this->db->where('i.created_by', $this->logged_in_id);
		if(isset($_GET['item_code'])){
			$this->db->where('i.item_code', $_GET['item_code']);
		}
        if(!empty($search)) {
        	$this->db->group_start();
        	$this->db->like('i.item_name',$search);
	        $this->db->group_end();
        }
        $query = $this->db->get();
        $total_count = $query->num_rows();

        $results = array(
            "results" => $select2_data,
            "total_count" => $total_count,
        );
        
		echo json_encode($results);
		exit();
	}

	function set_li_item_select2_val_by_rafrence(){
		$this->db->select("i.item_id, i.item_name, g.group_name, c.company_name, pu.pack_unit_name");
		$this->db->from("item i");
		$this->db->join('company c','c.id=i.company_id','left');
		$this->db->join('groups g','g.id=i.group_id','left');
		$this->db->join('pack_unit pu','pu.pack_unit_id=i.alternate_unit_id','left');
		if(isset($_GET['item_code'])){
			$this->db->where('i.item_code',$_GET['item_code']);
		}
		if(isset($_GET['internal_code'])){
			$this->db->where('i.internal_code',$_GET['internal_code']);
		}
		if(isset($_GET['id'])){
			$this->db->where('i.item_id',$_GET['id']);
		}
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$text = '';
			if(!empty($query->row()->company_name)) {
				$text .= $query->row()->company_name;
			}
			if(!empty($query->row()->group_name)) {
				$text .= ' - '.$query->row()->group_name;
			}
			if(!empty($query->row()->item_name)) {
				$text .= ' - '.$query->row()->item_name;
			}
			if(!empty($query->row()->pack_unit_name)) {
				$text .= ' - '.$query->row()->pack_unit_name;
			}
			echo json_encode(array('success' => true, 'id' => $query->row()->item_id, 'text' => $text, 'item_name' => $text));
			exit();
		}
		echo json_encode(array('success' => true, 'id' => '', 'text' => '--select--', 'item_name' => ''));
		exit();
	}

	function sites_select2_source(){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = array('created_by'=>$this->logged_in_id);
		$results = array(
			"results" => $this->get_select2_data('sites', 'site_id', 'site_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('sites', 'site_id', 'site_name', $search, $where),
		);
		echo json_encode($results);		
		exit();
	}
	function sites_group_select2_val_by_id($id){
		$this->get_select2_text_by_id('sites', 'site_id', 'site_name', $id);
	}

	function our_bank_label_select2_source(){
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = array('account_group_id'=>21);
        $results = array(
                "results" => $this->get_select2_data('account', 'account_id', 'account_name', $search, $page, $where),
                "total_count" => $this->count_select2_data('account', 'account_id', 'account_name', $search, $where),
        );

        echo json_encode($results);
        exit();
	}

	function unit_select2_source($arg = null){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';	
		
		$results = array(
			"results" => $this->get_select2_data('pack_unit', 'pack_unit_id', 'pack_unit_name', $search, $page, $where),
			"total_count" => $this->count_select2_data('pack_unit', 'pack_unit_id', 'pack_unit_name', $search, $where),
		);
		echo json_encode($results);
		exit();
	}

	function set_unit_select2_val_by_id($id){
		$this->get_select2_text_by_id('pack_unit', 'pack_unit_id', 'pack_unit_name', $id);
	}

	function item_select2_source_from_account_and_site_and_quatation($account_id = '',$site_id = ''){
		$search = isset($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$where = '';
		$where_in = [];

		$results = [];
		if(isset($account_id) && isset($site_id) && $account_id != '' && $site_id != ''){
			$get_quotation_id = $this->crud->getFromSQL('SELECT quotation_id FROM `quotation` WHERE `account_id` = '.$account_id.' AND `site_id` = '.$site_id.' AND `quotation_type` = 1');
			if(isset($get_quotation_id[0]) && $get_quotation_id[0]->quotation_id != ''){
				$get_item_ids = $this->crud->getFromSQL('SELECT `item_id` FROM `lineitems` WHERE `module`=5 AND `parent_id` ='.$get_quotation_id[0]->quotation_id);
				$get_item_ids = json_decode(json_encode ( $get_item_ids ) , true);
				$get_item_ids = array_column($get_item_ids, 'item_id');
				if(isset($get_item_ids) && !empty($get_item_ids)){
					$where_in['col'] = 'item_id';
					$where_in['values'] = $get_item_ids;	
					$results = array(
						"results" => $this->get_select2_data('item', 'item_id', 'item_name', $search, $page, $where ,$where_in),
						"total_count" => $this->count_select2_data('item', 'item_id', 'item_name', $search, $where , array(), $where_in),
					);
				}
			}
		}
		echo json_encode($results);
		exit();
	}
}
