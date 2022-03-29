<?php

/**
 * Class Crud
 * &@property CI_DB_active_record $db
 */
class Crud extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param $table_name
	 * @param $data_array
	 * @return bool
	 */
	function insert($table_name,$data_array){
		if($this->db->insert($table_name,$data_array))
		{
			return $this->db->insert_id();
		}
		return false;
	}

	function insertFromSql($sql)
	{
		$this->db->query($sql);
		return $this->db->insert_id();
	}

	function execuetSQL($sql){
		$this->db->query($sql);
	}
	function getFromSQL($sql)
	{
		return $this->db->query($sql)->result();
	}

	/**
	 * @param $table_name
	 * @param $order_by_column
	 * @param $order_by_value
	 * @return bool
	 */
	function get_all_records($table_name,$order_by_column,$order_by_value){
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->order_by($order_by_column,$order_by_value);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}

	/**
	 * @param $table_name
	 * @param $order_by_column
	 * @param $order_by_value
	 * @param $where_array
	 * @return bool
	 */
	function get_all_with_where($table_name,$order_by_column,$order_by_value,$where_array)
	{
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->where($where_array);
		$this->db->order_by($order_by_column,$order_by_value);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}else{
			return false;
		}
	}

	/**
	 * @param $tbl_name
	 * @param $column_name
	 * @param $where_id
	 * @return mixed
	 */
	function get_column_value_by_id($tbl_name,$column_name,$where_id)
	{				
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where_id);		
		$this->db->last_query();
		$query = $this->db->get();
		return $query->row($column_name);
	}

	/**
	 * @param $table_name
	 * @param $where_id
	 * @return mixed
	 */
	function get_row_by_id($table_name,$where_id){
		$this->db->select("*");
		$this->db->from($table_name);
		$this->db->where($where_id);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * @param $table_name
	 * @param $where_array
	 * @return mixed
	 */
	function delete_old($table_name,$where_array){
		$result = $this->db->delete($table_name,$where_array);
		return $result;
	}

	/**
	 * @param $table_name
	 * @param $where_array
	 * @return mixed
	 */
	function delete($table_name,$where_array){		
		$result = $this->db->delete($table_name,$where_array);
        $return = array();
        if ($result == '') {
            $return['error'] = "Error";
        } else {
            $return['success'] = 'Deleted';
        }
		return $return;
	}

	/**
	 * @param $table_name
	 * @param $where_id
	 * @param $where_in_array
	 * @return mixed
	 */
	function delete_where_in($table_name, $where_id, $where_in_array){		
		$this->db->where_in($where_id, $where_in_array);
		$result = $this->db->delete($table_name);
		return $result;
	}

	/**
	 * @param $table_name
	 * @param $data_array
	 * @param $where_array
	 * @return mixed
	 */
	function update($table_name,$data_array,$where_array){
		$this->db->where($where_array);
		$rs = $this->db->update($table_name, $data_array);
		return $rs;
	}

	/**
	 * @param $name
	 * @param $path
	 * @return bool
	 */
	function upload_file($name, $path)
	{
		$config['upload_path'] = $path;
		$config ['allowed_types'] = '*';
		$this->upload->initialize($config);
		if($this->upload->do_upload($name))
		{
			$upload_data = $this->upload->data();
			return $upload_data['file_name'];
		}
		return false;
	}

	/**
	 * @param $table
	 * @param $id_column
	 * @param $column
	 * @param $column_val
	 * @return null
	 */
        
        function get_val_by_id($table,$id_column,$column,$column_val){
		$this->db->select($column_val);
		$this->db->from($table);
		$this->db->where($column,$id_column);
		$this->db->limit('1');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$column_val;
		} else {
			return null;
		}
	}
        
	function get_id_by_val($table,$id_column,$column,$column_val){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column,$column_val);
		$this->db->limit('1');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}

	function get_id_by_val_count($table,$id_column,$where_array){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($where_array);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->num_rows();
		} else {
			return null;
		}
	}

	function get_id_by_val_not($table,$id_column,$column,$column_val,$permalink){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column,$column_val);
		$this->db->where_not_in($id_column, $permalink);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}

	function get_same_by_val($table,$id_column,$column1,$column1_val,$column2,$column2_val,$id = null){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($column1,$column1_val);
		$this->db->where($column2,$column2_val);
		$this->db->where($id_column."!=",$id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}


	function limit_words($string, $word_limit=30){
		$words = explode(" ",$string);
		return implode(" ", array_splice($words, 0, $word_limit));
	}
	function limit_character($string, $character_limit=30){
		if (strlen($string) > $character_limit) {
			return substr($string, 0, $character_limit).'...';
		}else{
			return $string;
		}
	}
	//select data 
	function get_select_data($tbl_name)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$query = $this->db->get();
		return $query->result();
	}
	//select data 
	function get_select_data_where($tbl_name,$where)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	// Select data For specific Columns
	// $columns Array
	function get_specific_column_data($tbl_name, $columns)
	{
		$columns = implode(', ', $columns);
		$this->db->select($columns);
		$this->db->from($tbl_name);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * @param $tbl_name
	 * @param $where
	 * @return mixed
	 */
	function get_data_row_by_where($tbl_name,$where)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * @param $tbl_name
	 * @param $where
	 * @param $where_id
	 * @return mixed
	 */
	function get_data_row_by_id($tbl_name,$where,$where_id)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where,$where_id);
		$query = $this->db->get();
		return $query->row();
	}
	function get_result_where($tbl_name,$where,$where_id)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where($where,$where_id);
		$query = $this->db->get();
		return $query->result();
	}

	function get_where_in_result($tbl_name,$where,$where_in)
	{
		$this->db->select("*");
		$this->db->from($tbl_name);
		$this->db->where_in($where,$where_in);
		$query = $this->db->get();
		return $query->result();
	}

	function get_max_number($tbl_name,$column_name, $created_by){
		$this->db->select_max($column_name);
		$this->db->where('created_by', $created_by);
		$result = $this->db->get($tbl_name)->row();  
		return $result;
	}
        
        function get_max_number_from_table($tbl_name,$column_name)
	{
		$this->db->select_max($column_name);
		$result = $this->db->get($tbl_name)->row();  
		return $result;
	}
    
    function get_max_number_where($tbl_name,$column_name,$where_array)
	{
		$this->db->select_max($column_name);
        $this->db->where($where_array);
		$result = $this->db->get($tbl_name)->row();  
		return $result;
	}

	function get_sales_invoice($created_by){
		$this->db->select('sales_invoice.*, DATE_FORMAT(sales_invoice.sales_invoice_date,"%d-%m-%Y") as sales_invoice_date,
							account.account_id, account.account_gst_no, state.state_id, state.state_name');
		$this->db->from('sales_invoice');
		$this->db->join('account', 'sales_invoice.account_id = account.account_id', 'left');
		$this->db->join('state', 'account.account_state = state.state_id', 'left');
		$this->db->where('sales_invoice.created_by',$created_by);
		$this->db->order_by('sales_invoice.sales_invoice_no','ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_sales_invoice_lineitems($created_by, $from_date, $to_date){
		$this->db->select('lineitems.*, sales_invoice.sales_invoice_no, sales_invoice.pure_amount_total, sales_invoice.discount_total, DATE_FORMAT(sales_invoice.sales_invoice_date,"%d-%m-%Y") as sales_invoice_date,
							account.account_id, account.account_gst_no, state.state_id, state.state_name');
		$this->db->from('lineitems');
		$this->db->join('sales_invoice', 'lineitems.parent_id = sales_invoice.sales_invoice_id', 'left');
		$this->db->join('account', 'sales_invoice.account_id = account.account_id', 'left');
		$this->db->join('state', 'account.account_state = state.state_id', 'left');
		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('sales_invoice.sales_invoice_date >=', $from_date);
			$this->db->where('sales_invoice.sales_invoice_date <=', $to_date);
		}
		//~ $this->db->where('sales_invoice.data_lock_unlock', 0);
		$this->db->where('lineitems.module',2);
		$this->db->where('lineitems.created_by',$created_by);
		$this->db->order_by('sales_invoice.sales_invoice_no','ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_credit_note_lineitems($created_by, $from_date, $to_date){
		$this->db->select('lineitems.*, credit_note.credit_note_no, credit_note.bill_no, credit_note.invoice_date, credit_note.pure_amount_total, credit_note.discount_total, DATE_FORMAT(credit_note.credit_note_date,"%d-%m-%Y") as credit_note_date,
							account.account_id, account.account_gst_no, state.state_id, state.state_name');
		$this->db->from('lineitems');
		$this->db->join('credit_note', 'lineitems.parent_id = credit_note.credit_note_id', 'left');
		$this->db->join('account', 'credit_note.account_id = account.account_id', 'left');
		$this->db->join('state', 'account.account_state = state.state_id', 'left');
		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('credit_note.credit_note_date >=', $from_date);
			$this->db->where('credit_note.credit_note_date <=', $to_date);
		}
		//~ $this->db->where('credit_note.data_lock_unlock', 0);
		$this->db->where('lineitems.module',3);
		$this->db->where('lineitems.created_by',$created_by);
		$this->db->order_by('credit_note.credit_note_no','ASC');
		$query = $this->db->get();
		return $query->result();
	}

        function get_purchase_invoice($created_by, $from_date, $to_date){
		$this->db->select('purchase_invoice.*, DATE_FORMAT(purchase_invoice.purchase_invoice_date,"%d-%m-%Y") as purchase_invoice_date,
							account.account_id,account.account_name, account.account_gst_no, state.state_id, state.state_name');
		$this->db->from('purchase_invoice');
		$this->db->join('account', 'purchase_invoice.account_id = account.account_id', 'left');
		$this->db->join('state', 'account.account_state = state.state_id', 'left');
		$this->db->where('purchase_invoice.created_by',$created_by);
                $this->db->where('purchase_invoice.invoice_type',2);
                if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('purchase_invoice.purchase_invoice_date >=', $from_date);
			$this->db->where('purchase_invoice.purchase_invoice_date <=', $to_date);
		}
		$this->db->order_by('purchase_invoice.purchase_invoice_no','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function get_purchase_invoice_lineitems($created_by, $from_date, $to_date){
		$this->db->select('lineitems.*, purchase_invoice.purchase_invoice_no, purchase_invoice.pure_amount_total, purchase_invoice.discount_total, DATE_FORMAT(purchase_invoice.purchase_invoice_date,"%d-%m-%Y") as purchase_invoice_date,
							account.account_id, account.account_gst_no, state.state_id, state.state_name, item.item_type_id');
		$this->db->from('lineitems');
		$this->db->join('purchase_invoice', 'lineitems.parent_id = purchase_invoice.purchase_invoice_id', 'left');
		$this->db->join('account', 'purchase_invoice.account_id = account.account_id', 'left');
		$this->db->join('state', 'account.account_state = state.state_id', 'left');
		$this->db->join('item', 'lineitems.item_id = item.item_id', 'left');
		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('purchase_invoice.purchase_invoice_date >=', $from_date);
			$this->db->where('purchase_invoice.purchase_invoice_date <=', $to_date);
		}
		//~ $this->db->where('purchase_invoice.data_lock_unlock', 0);
		$this->db->where('lineitems.module',1);
		$this->db->where('lineitems.created_by',$created_by);
		$this->db->order_by('purchase_invoice.purchase_invoice_no','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function get_debit_note_lineitems($created_by, $from_date, $to_date){
		$this->db->select('lineitems.*, debit_note.debit_note_no, debit_note.bill_no, debit_note.invoice_date, debit_note.pure_amount_total, debit_note.discount_total, DATE_FORMAT(debit_note.debit_note_date,"%d-%m-%Y") as debit_note_date,
							account.account_id, account.account_gst_no, state.state_id, state.state_name');
		$this->db->from('lineitems');
		$this->db->join('debit_note', 'lineitems.parent_id = debit_note.debit_note_id', 'left');
		$this->db->join('account', 'debit_note.account_id = account.account_id', 'left');
		$this->db->join('state', 'account.account_state = state.state_id', 'left');
		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('debit_note.debit_note_date >=', $from_date);
			$this->db->where('debit_note.debit_note_date <=', $to_date);
		}
		//~ $this->db->where('debit_note.data_lock_unlock', 0);
		$this->db->where('lineitems.module',4);
		$this->db->where('lineitems.created_by',$created_by);
		$this->db->order_by('debit_note.debit_note_no','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function get_text_by_id_multiple($table_name, $id_column, $text_column, $id_column_val){
		$this->db->select("$text_column");
		$this->db->from("$table_name");
		$this->db->where_in($id_column, $id_column_val);
		$query = $this->db->get();
		return $query->result();
	}

	function check_duplicate($table,$id_column,$where_array,$not_in = null){
		$this->db->select($id_column);
		$this->db->from($table);
		$this->db->where($where_array);
		if(isset($not_in) && !empty($not_in)){
			$this->db->where_not_in($id_column, $not_in);	
		}
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row()->$id_column;
		} else {
			return null;
		}
	}

	function where_not_in($table,$id_column,$array,$where_array){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where_not_in($id_column, $array);
		$this->db->where($where_array);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		} else {
			return null;
		}
	}
	
	function update_multiple($table_name, $data_array,$where_in_field, $where_in_array){
		$this->db->where_in($where_in_field,$where_in_array);
		$rs = $this->db->update($table_name, $data_array);
		return $rs;
	}
	
	function delete_multiple($table_name, $where_in_field, $where_in_array){
		$this->db->where_in($where_in_field,$where_in_array);
		$result = $this->db->delete($table_name);
		return $result;
	}
	
	function get_purchase_stock_below_fromdate($item_id, $from_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount,lineitems.item_qty, purchase_invoice.purchase_invoice_id, purchase_invoice.account_id, purchase_invoice.purchase_invoice_date');
		$this->db->from('lineitems');
		$this->db->join('purchase_invoice', 'lineitems.parent_id = purchase_invoice.purchase_invoice_id', 'left');
		$this->db->join('account', 'purchase_invoice.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='1' AND `lineitems`.`item_id`='".$item_id."' AND `purchase_invoice`.`created_by`='".$created_by."' ";
		if(!empty($from_date)){
			$where .= " AND `purchase_invoice`.`purchase_invoice_date` < '".$from_date."' ";
		}
		if(!empty($account_id)){
			$where .= " AND (`purchase_invoice`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		//~ echo $this->db->last_query();
		return $query->result();
	}
	
	function get_sales_stock_below_fromdate($item_id, $from_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount,lineitems.item_qty, sales_invoice.sales_invoice_id, sales_invoice.account_id, sales_invoice.sales_invoice_date');
		$this->db->from('lineitems');
		$this->db->join('sales_invoice', 'lineitems.parent_id = sales_invoice.sales_invoice_id', 'left');
		$this->db->join('account', 'sales_invoice.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='2' AND `lineitems`.`item_id`='".$item_id."' AND `sales_invoice`.`created_by`='".$created_by."' ";
		if(!empty($from_date)){
			$where .= " AND `sales_invoice`.`sales_invoice_date` < '".$from_date."' ";
		}
		if(!empty($account_id)){
			$where .= " AND (`sales_invoice`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_credit_stock_below_fromdate($item_id, $from_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount,lineitems.item_qty, credit_note.credit_note_id, credit_note.account_id, credit_note.credit_note_date');
		$this->db->from('lineitems');
		$this->db->join('credit_note', 'lineitems.parent_id = credit_note.credit_note_id', 'left');
		$this->db->join('account', 'credit_note.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='3' AND `lineitems`.`item_id`='".$item_id."' AND `credit_note`.`created_by`='".$created_by."' ";
		if(!empty($from_date)){
			$where .= " AND `credit_note`.`credit_note_date` < '".$from_date."' ";
		}
		if(!empty($account_id)){
			$where .= " AND (`credit_note`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_debit_stock_below_fromdate($item_id, $from_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount,lineitems.item_qty, debit_note.debit_note_id, debit_note.account_id, debit_note.debit_note_date');
		$this->db->from('lineitems');
		$this->db->join('debit_note', 'lineitems.parent_id = debit_note.debit_note_id', 'left');
		$this->db->join('account', 'debit_note.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='4' AND `lineitems`.`item_id`='".$item_id."' AND `debit_note`.`created_by`='".$created_by."' ";
		if(!empty($from_date)){
			$where .= " AND `debit_note`.`debit_note_date` < '".$from_date."' ";
		}
		if(!empty($account_id)){
			$where .= " AND (`debit_note`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_purchase_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount, lineitems.item_qty, purchase_invoice.purchase_invoice_id, purchase_invoice.account_id, purchase_invoice.purchase_invoice_date');
		$this->db->from('lineitems');
		$this->db->join('purchase_invoice', 'lineitems.parent_id = purchase_invoice.purchase_invoice_id', 'left');
		$this->db->join('account', 'purchase_invoice.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='1' AND `lineitems`.`item_id`='".$item_id."' AND `purchase_invoice`.`created_by`='".$created_by."' ";
		if(!empty($from_date) && !empty($to_date)){
			$where .= " AND (`purchase_invoice`.`purchase_invoice_date` >='".$from_date."' AND `purchase_invoice`.`purchase_invoice_date` <='".$to_date."') ";
		}
		if(!empty($account_id)){
			$where .= " AND (`purchase_invoice`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		//~ echo $this->db->last_query();
		return $query->result();
	}

	function get_sales_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount, lineitems.item_qty, sales_invoice.sales_invoice_id, sales_invoice.account_id, sales_invoice.sales_invoice_date');
		$this->db->from('lineitems');
		$this->db->join('sales_invoice', 'lineitems.parent_id = sales_invoice.sales_invoice_id', 'left');
		$this->db->join('account', 'sales_invoice.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='2' AND `lineitems`.`item_id`='".$item_id."' AND `sales_invoice`.`created_by`='".$created_by."' ";
		if(!empty($from_date) && !empty($to_date)){
			$where .= " AND (`sales_invoice`.`sales_invoice_date` >='".$from_date."' AND `sales_invoice`.`sales_invoice_date` <='".$to_date."') ";
		}
		if(!empty($account_id)){
			$where .= " AND (`sales_invoice`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_credit_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount, lineitems.item_qty, credit_note.credit_note_id, credit_note.account_id, credit_note.credit_note_date');
		$this->db->from('lineitems');
		$this->db->join('credit_note', 'lineitems.parent_id = credit_note.credit_note_id', 'left');
		$this->db->join('account', 'credit_note.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='3' AND `lineitems`.`item_id`='".$item_id."' AND `credit_note`.`created_by`='".$created_by."' ";
		if(!empty($from_date) && !empty($to_date)){
			$where .= " AND (`credit_note`.`credit_note_date` >='".$from_date."' AND `credit_note`.`credit_note_date` <='".$to_date."') ";
		}
		if(!empty($account_id)){
			$where .= " AND (`credit_note`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_debit_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $created_by){
		$this->db->select('lineitems.item_id, lineitems.amount, lineitems.item_qty, debit_note.debit_note_id, debit_note.account_id, debit_note.debit_note_date');
		$this->db->from('lineitems');
		$this->db->join('debit_note', 'lineitems.parent_id = debit_note.debit_note_id', 'left');
		$this->db->join('account', 'debit_note.account_id = account.account_id', 'left');
		$where = "`lineitems`.`module`='4' AND `lineitems`.`item_id`='".$item_id."' AND `debit_note`.`created_by`='".$created_by."' ";
		if(!empty($from_date) && !empty($to_date)){
			$where .= " AND (`debit_note`.`debit_note_date` >='".$from_date."' AND `debit_note`.`debit_note_date` <='".$to_date."') ";
		}
		if(!empty($account_id)){
			$where .= " AND (`debit_note`.`account_id`='".$account_id."') ";
		}
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	function update_item_current_stock_qty($item_id,$invoice_id,$is_sales_or_purchase,$qty,$action)
	{

		$item_row = $this->crud->get_row_by_id('item',array('item_id' => $item_id));
		
		//Purchase => Add QTY
		//Sales => Subtract QTY
		//debit_note => Purchase return => Subtract QTY
		//credit_note => Sales return => Add QTY

		if(!empty($item_row) && in_array($is_sales_or_purchase,array("add_item","update_item","purchase","sales","debit_note","credit_note"))) {
			$module = 1;
			
			if($is_sales_or_purchase == 'purchase') {
				$module = 1;

			} elseif($is_sales_or_purchase == 'sales') {
				$module = 2;
			
			} elseif($is_sales_or_purchase == 'credit_note') {
				$module = 3;
			
			} elseif($is_sales_or_purchase == 'debit_note') {
				$module = 4;
			}

			if(in_array($action,array("delete","update"))) {

				if(in_array($is_sales_or_purchase,array("sales","debit_note"))) {
					$line_item_where = array(
						'item_id' => $item_id,	
						'parent_id' => $invoice_id,
						'module' => $module,
					);
					$line_item_row = $this->crud->get_row_by_id('lineitems',$line_item_where);

					if(!empty($line_item_row[0])) {
						$line_item_row = $line_item_row[0];

						$old_qty = $line_item_row->item_qty;

						$stock_data = array(
							'item_id' => $item_id,
							'invoice_id' => $invoice_id,
							'is_sales_or_purchase' => $is_sales_or_purchase,
							'qty' => $old_qty,
							'action' => $action,
							'note' => 'reverse_sales',
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);
						$this->db->insert('item_current_stock_detail',$stock_data);

						$this->db->where('item_id',$item_id);
						$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$old_qty, FALSE);
						$this->db->update('item');

						if($module == 2) {
							$qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '".$item_id."' ORDER BY id DESC");
					        if(!empty($qty_setting_res)){
					        	foreach ($qty_setting_res as $key => $qty_setting_row) {
					        		if($qty_setting_row->sub_item_qty != 0 && $qty_setting_row->item_qty != 0) {
					        			$sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $old_qty;
					        			
					        			$sub_item_qty = round($sub_item_qty,2);
						        		$stock_data = array(
											'item_id' => $qty_setting_row->sub_item_id,
											'invoice_id' => $invoice_id,
											'is_sales_or_purchase' => $is_sales_or_purchase,
											'qty' => $sub_item_qty,
											'action' => $action,
											'note' => '',
											'created_at' => date("Y-m-d H:i:s"),
											'updated_at' => date("Y-m-d H:i:s"),
										);

										if($qty_setting_row->sub_item_add_less == 1) {
											$stock_data['note'] = 'reverse less sub item';
											$stock_data['qty'] = ($sub_item_qty * -1);
										} else {
											$stock_data['note'] = 'reverse add sub item';
										}
										$this->db->insert('item_current_stock_detail',$stock_data);


										if($qty_setting_row->sub_item_add_less == 1) {
											$this->db->where('item_id',$qty_setting_row->sub_item_id);
											$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$sub_item_qty, FALSE);
											$this->db->update('item');
										} else {
											$this->db->where('item_id',$qty_setting_row->sub_item_id);
											$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$sub_item_qty, FALSE);
											$this->db->update('item');
										}
					        		}
					        	}
					        }
						}
					}

				} elseif (in_array($is_sales_or_purchase,array("purchase","credit_note"))) { //Purchase

					$line_item_where = array(
						'item_id' => $item_id,	
						'parent_id' => $invoice_id,
						'module' => $module,
					);
					$line_item_row = $this->crud->get_row_by_id('lineitems',$line_item_where);
					if(!empty($line_item_row[0])) {
						$line_item_row = $line_item_row[0];

						$old_qty = $line_item_row->item_qty;

						$stock_data = array(
							'item_id' => $item_id,
							'invoice_id' => $invoice_id,
							'is_sales_or_purchase' => $is_sales_or_purchase,
							'qty' => ($old_qty * -1),
							'action' => $action,
							'note' => 'reverse_purchase',
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);
						$this->db->insert('item_current_stock_detail',$stock_data);

						$this->db->where('item_id',$item_id);
						$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$old_qty, FALSE);
						$this->db->update('item');
					}
				
				} elseif (in_array($is_sales_or_purchase,array("update_item"))) { //Purchase

					$item_where = array(
						'item_id' => $item_id,	
					);
					$item_row = $this->crud->get_row_by_id('item',$item_where);
					if(!empty($item_row[0])) {
						$item_row = $item_row[0];

						$old_qty = $item_row->opening_qty;

						$stock_data = array(
							'item_id' => $item_id,
							'invoice_id' => $invoice_id,
							'is_sales_or_purchase' => $is_sales_or_purchase,
							'qty' => ($old_qty * -1),
							'action' => $action,
							'note' => 'update_item',
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);
						$this->db->insert('item_current_stock_detail',$stock_data);

						$this->db->where('item_id',$item_id);
						$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$old_qty, FALSE);
						$this->db->update('item');
					}
				}
			}


			if(in_array($action,array("add","update"))) {
				$stock_data = array(
					'item_id' => $item_id,
					'invoice_id' => $invoice_id,
					'is_sales_or_purchase' => $is_sales_or_purchase,
					'qty' => $qty,
					'action' => $action,
					'note' => '',
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
				);
				if(in_array($is_sales_or_purchase,array("sales","debit_note"))) {
					$stock_data['note'] = 'add_sales';
					$stock_data['qty'] = ($qty * -1);
				} else {
					$stock_data['note'] = 'add_purchase';
				}
				$this->db->insert('item_current_stock_detail',$stock_data);


				if(in_array($is_sales_or_purchase,array("sales","debit_note"))) {
					$this->db->where('item_id',$item_id);
					$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$qty, FALSE);
					$this->db->update('item');
				} else {
					$this->db->where('item_id',$item_id);
					$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$qty, FALSE);
					$this->db->update('item');
				}

				if($module == 2) {
					$qty_setting_res = $this->crud->getFromSQL("SELECT * FROM sub_item_add_less_settings WHERE item_id = '".$item_id."' ORDER BY id DESC");
			        if(!empty($qty_setting_res)){
			        	foreach ($qty_setting_res as $key => $qty_setting_row) {
			        		if($qty_setting_row->sub_item_qty != 0 && $qty_setting_row->item_qty != 0) {
			        			$sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
			        			$sub_item_qty = round($sub_item_qty,2);
				        		$stock_data = array(
									'item_id' => $qty_setting_row->sub_item_id,
									'invoice_id' => $invoice_id,
									'is_sales_or_purchase' => $is_sales_or_purchase,
									'qty' => $sub_item_qty,
									'action' => $action,
									'note' => '',
									'created_at' => date("Y-m-d H:i:s"),
									'updated_at' => date("Y-m-d H:i:s"),
								);

								if($qty_setting_row->sub_item_add_less == 2) {
									$stock_data['note'] = 'less sub item';
									$stock_data['qty'] = ($sub_item_qty * -1);
								} else {
									$stock_data['note'] = 'add sub item';
								}
								$this->db->insert('item_current_stock_detail',$stock_data);


								if($qty_setting_row->sub_item_add_less == 2) {
									$this->db->where('item_id',$qty_setting_row->sub_item_id);
									$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-'.$sub_item_qty, FALSE);
									$this->db->update('item');
								} else {
									$this->db->where('item_id',$qty_setting_row->sub_item_id);
									$this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+'.$sub_item_qty, FALSE);
									$this->db->update('item');
								}
			        		}
			        	}
			        }
				}
			}
		}

		return true;
	}
        
	function update_item_current_stock_qty_by_sales($item_id, $invoice_id, $is_sales_or_purchase, $qty, $action, $sub_item_data = '') {
//            echo "<pre>"; print_r(json_decode($sub_item_data)); exit;
            $item_row = $this->crud->get_row_by_id('item', array('item_id' => $item_id));
            //Purchase => Add QTY
            //Sales => Subtract QTY
            //debit_note => Purchase return => Subtract QTY
            //credit_note => Sales return => Add QTY
            if (!empty($item_row) && in_array($is_sales_or_purchase, array("add_item", "update_item", "purchase", "sales", "debit_note", "credit_note"))) {
                $module = 1;
                if ($is_sales_or_purchase == 'purchase') {
                    $module = 1;
                } elseif ($is_sales_or_purchase == 'sales') {
                    $module = 2;
                } elseif ($is_sales_or_purchase == 'credit_note') {
                    $module = 3;
                } elseif ($is_sales_or_purchase == 'debit_note') {
                    $module = 4;
                }
                if (in_array($action, array("delete", "update"))) {
                    if (in_array($is_sales_or_purchase, array("sales", "debit_note"))) {
                        $line_item_where = array(
                            'item_id' => $item_id,
                            'parent_id' => $invoice_id,
                            'module' => $module,
                        );
                        $line_item_row = $this->crud->get_row_by_id('lineitems', $line_item_where);
                        if (!empty($line_item_row[0])) {
                            $line_item_row = $line_item_row[0];
                            $old_qty = $line_item_row->item_qty;
                            $stock_data = array(
                                'item_id' => $item_id,
                                'invoice_id' => $invoice_id,
                                'is_sales_or_purchase' => $is_sales_or_purchase,
                                'qty' => $old_qty,
                                'action' => $action,
                                'note' => 'reverse_sales',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            );
                            $this->db->insert('item_current_stock_detail', $stock_data);
                            $this->db->where('item_id', $item_id);
                            $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+' . $old_qty, FALSE);
                            $this->db->update('item');
                            if ($module == 2) {
                                $qty_setting_res = $sub_item_data;
                                if (!empty($qty_setting_res)) {
                                    foreach ($qty_setting_res as $key => $qty_setting_row) {
                                        // Start >> insert in stock status change //
//                                        if ($qty_setting_row->sub_item_qty != 0 && $qty_setting_row->item_qty != 0) {
//                                            $stock_s_data = array();
//                                            $stock_s_data['st_change_date'] =  date("Y-m-d");
//                                            $stock_s_data['from_status'] =  IN_WORK_DONE_ID;
//                                            $stock_s_data['to_status'] =  IN_SALE_ID;
//                                            $stock_s_data['item_id'] =  $qty_setting_row->sub_item_id;
//                                            $sub_item_qty1 = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
//                                            $sub_item_qty1 = round($sub_item_qty1, 2);
//                                            $stock_s_data['qty'] =  $sub_item_qty1;
//                                            $stock_s_data['tr_type'] =  '2';
//                                            $stock_s_data['tr_id'] =  $invoice_id;
//                                            $stock_s_data['created_at'] =  date("Y-m-d");
//                                            $stock_s_data['created_by'] =  $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
//                                            $this->db->insert('stock_status_change', $stock_s_data);
//                                        }
                                        // End >> insert in stock status change //
                                        
                                        if ($qty_setting_row->sub_item_qty != 0 && $qty_setting_row->item_qty != 0) {
                                            $sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $old_qty;
                                            $sub_item_qty = round($sub_item_qty, 2);
                                            $stock_data = array(
                                                'item_id' => $qty_setting_row->sub_item_id,
                                                'invoice_id' => $invoice_id,
                                                'is_sales_or_purchase' => $is_sales_or_purchase,
                                                'qty' => $sub_item_qty,
                                                'action' => $action,
                                                'note' => '',
                                                'created_at' => date("Y-m-d H:i:s"),
                                                'updated_at' => date("Y-m-d H:i:s"),
                                            );
                                            if ($qty_setting_row->sub_item_add_less == 1) {
                                                $stock_data['note'] = 'reverse less sub item';
                                                $stock_data['qty'] = ($sub_item_qty * -1);
                                            } else {
                                                $stock_data['note'] = 'reverse add sub item';
                                            }
                                            $this->db->insert('item_current_stock_detail', $stock_data);
                                            if ($qty_setting_row->sub_item_add_less == 1) {
                                                $this->db->where('item_id', $qty_setting_row->sub_item_id);
                                                $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-' . $sub_item_qty, FALSE);
                                                $this->db->update('item');
                                            } else {
                                                $this->db->where('item_id', $qty_setting_row->sub_item_id);
                                                $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+' . $sub_item_qty, FALSE);
                                                $this->db->update('item');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } elseif (in_array($is_sales_or_purchase, array("purchase", "credit_note"))) { //Purchase
                        $line_item_where = array(
                            'item_id' => $item_id,
                            'parent_id' => $invoice_id,
                            'module' => $module,
                        );
                        $line_item_row = $this->crud->get_row_by_id('lineitems', $line_item_where);
                        if (!empty($line_item_row[0])) {
                            $line_item_row = $line_item_row[0];
                            $old_qty = $line_item_row->item_qty;
                            $stock_data = array(
                                'item_id' => $item_id,
                                'invoice_id' => $invoice_id,
                                'is_sales_or_purchase' => $is_sales_or_purchase,
                                'qty' => ($old_qty * -1),
                                'action' => $action,
                                'note' => 'reverse_purchase',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            );
                            $this->db->insert('item_current_stock_detail', $stock_data);
                            $this->db->where('item_id', $item_id);
                            $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-' . $old_qty, FALSE);
                            $this->db->update('item');
                        }
                    } elseif (in_array($is_sales_or_purchase, array("update_item"))) { //Purchase
                        $item_where = array(
                            'item_id' => $item_id,
                        );
                        $item_row = $this->crud->get_row_by_id('item', $item_where);
                        if (!empty($item_row[0])) {
                            $item_row = $item_row[0];
                            $old_qty = $item_row->opening_qty;
                            $stock_data = array(
                                'item_id' => $item_id,
                                'invoice_id' => $invoice_id,
                                'is_sales_or_purchase' => $is_sales_or_purchase,
                                'qty' => ($old_qty * -1),
                                'action' => $action,
                                'note' => 'update_item',
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            );
                            $this->db->insert('item_current_stock_detail', $stock_data);
                            $this->db->where('item_id', $item_id);
                            $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-' . $old_qty, FALSE);
                            $this->db->update('item');
                        }
                    }
                }
                if (in_array($action, array("add", "update"))) {
                    $stock_data = array(
                        'item_id' => $item_id,
                        'invoice_id' => $invoice_id,
                        'is_sales_or_purchase' => $is_sales_or_purchase,
                        'qty' => $qty,
                        'action' => $action,
                        'note' => '',
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    );
                    if (in_array($is_sales_or_purchase, array("sales", "debit_note"))) {
                        $stock_data['note'] = 'add_sales';
                        $stock_data['qty'] = ($qty * -1);
                    } else {
                        $stock_data['note'] = 'add_purchase';
                    }
                    $this->db->insert('item_current_stock_detail', $stock_data);
                    if (in_array($is_sales_or_purchase, array("sales", "debit_note"))) {
                        $this->db->where('item_id', $item_id);
                        $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-' . $qty, FALSE);
                        $this->db->update('item');
                    } else {
                        $this->db->where('item_id', $item_id);
                        $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+' . $qty, FALSE);
                        $this->db->update('item');
                    }
                    if ($module == 2) {
                        $qty_setting_res = $sub_item_data;
                        if (!empty($qty_setting_res)) {
                            foreach ($qty_setting_res as $key => $qty_setting_row) {
                                if ($qty_setting_row->sub_item_qty != 0 && $qty_setting_row->item_qty != 0) {
                                    $sub_item_qty = ($qty_setting_row->sub_item_qty / $qty_setting_row->item_qty) * $qty;
                                    $sub_item_qty = round($sub_item_qty, 2);
                                    $stock_data = array(
                                        'item_id' => $qty_setting_row->sub_item_id,
                                        'invoice_id' => $invoice_id,
                                        'is_sales_or_purchase' => $is_sales_or_purchase,
                                        'qty' => $sub_item_qty,
                                        'action' => $action,
                                        'note' => '',
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    );

                                    if ($qty_setting_row->sub_item_add_less == 2) {
                                        $stock_data['note'] = 'less sub item';
                                        $stock_data['qty'] = ($sub_item_qty * -1);
                                    } else {
                                        $stock_data['note'] = 'add sub item';
                                    }
                                    $this->db->insert('item_current_stock_detail', $stock_data);
                                    if ($qty_setting_row->sub_item_add_less == 2) {
                                        $this->db->where('item_id', $qty_setting_row->sub_item_id);
                                        $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)-' . $sub_item_qty, FALSE);
                                        $this->db->update('item');
                                    } else {
                                        $this->db->where('item_id', $qty_setting_row->sub_item_id);
                                        $this->db->set('current_stock_qty', 'IFNULL(current_stock_qty,0)+' . $sub_item_qty, FALSE);
                                        $this->db->update('item');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        return true;
    }

        function kasar_entry($account_id = 0,$invoice_id = 0,$is_sales_or_purchase = 0,$round_off_amount = 0,$action = '',$other_params = array())
	{
		$kasar_account_id = $this->applib->get_kasar_account_id();
		$logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
        $now_time = date('Y-m-d H:i:s');
        if($other_params['invoice_date']) {
        	$journal_date = date('Y-m-d',strtotime($other_params['invoice_date']));
        } else {
        	$journal_date = date('Y-m-d');
        }

		if(in_array($is_sales_or_purchase,array("purchase","sales","debit_note","credit_note"))) {
			$module = 1;
			
			if($is_sales_or_purchase == 'purchase') {
				$module = 1;

			} elseif($is_sales_or_purchase == 'sales') {
				$module = 2;
			
			} elseif($is_sales_or_purchase == 'credit_note') {
				$module = 3;
			
			} elseif($is_sales_or_purchase == 'debit_note') {
				$module = 4;
			}

			if(in_array($action,array("delete","update"))) {
				$journal_row = $this->crud->get_data_row_by_where('journal',array('invoice_id' => $invoice_id,'module' => $module));
				if(!empty($journal_row)) {
					$where_array = array('journal_id' => $journal_row->journal_id);
	        		$this->crud->delete('journal',$where_array);
	        		$this->crud->delete('transaction_entry',$where_array);				
				}
			}
			
			if(in_array($action,array("add","update")) && $round_off_amount != 0) {
				$journal_data = array();
	            $journal_data['invoice_id'] = $invoice_id;
	            $journal_data['module'] = $module;
	            $journal_data['journal_date'] = $journal_date;
	            $journal_data['created_at'] = $now_time;
	            $journal_data['created_by'] = $logged_in_id;
	            $journal_data['user_created_by'] = $this->session->userdata()['login_user_id'];
	            $journal_id = $this->crud->insert('journal', $journal_data);

	            $contra = $this->crud->get_max_number_from_table('transaction_entry','contra_no');
                $contra_no = 1;
                if($contra->contra_no > 0){
                    $contra_no = $contra->contra_no + 1;
                }

                /*--- Kasar Account ---*/
                $tran_data = array();
                $tran_data['transaction_date'] = $journal_date;
                $tran_data['account_id'] = $kasar_account_id;
                if($round_off_amount > 0) {
                	$is_credit_debit  = 1;
                    $tran_data['from_account_id'] = $kasar_account_id;
                    $tran_data['to_account_id'] = NULL;
                } else {
                	$is_credit_debit  = 2;
                    $tran_data['from_account_id'] = NULL;
                    $tran_data['to_account_id'] = $kasar_account_id;
                }
                $tran_data['transaction_type'] = '4';
                $tran_data['amount'] = abs($round_off_amount);
                $tran_data['contra_no'] = $contra_no;
                $tran_data['note'] = 'Kasar';
                $tran_data['journal_id'] = $journal_id;
                $tran_data['is_credit_debit'] = $is_credit_debit;
                $tran_data['created_at'] = $now_time;
                $tran_data['created_by'] = $logged_in_id;
                $tran_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('transaction_entry', $tran_data);


                $tran_data = array();
                $tran_data['transaction_date'] = $journal_date;
                $tran_data['account_id'] = $account_id;
                if($round_off_amount > 0) {
                	$is_credit_debit = 2;
                	$tran_data['from_account_id'] = NULL;
                    $tran_data['to_account_id'] = $account_id;
                } else {
                	$is_credit_debit = 1;
                    $tran_data['from_account_id'] = $account_id;
                    $tran_data['to_account_id'] = NULL;
                }
                $tran_data['transaction_type'] = '4';
                $tran_data['amount'] = abs($round_off_amount);
                $tran_data['contra_no'] = $contra_no;
                $tran_data['note'] = 'Kasar';
                $tran_data['journal_id'] = $journal_id;
                $tran_data['is_credit_debit'] = $is_credit_debit;
                $tran_data['created_at'] = $now_time;
                $tran_data['created_by'] = $logged_in_id;
                $tran_data['user_created_by'] = $this->session->userdata()['login_user_id'];
                $this->crud->insert('transaction_entry', $tran_data);
			}
		}
		return true;
	}

    function get_account_balance($account_id,$to_date = '', $site_id = '') {
    	if($to_date == '') {
    		$to_date = date("Y-m-d");	
    	}
        $to_date = date("Y-m-d",strtotime($to_date));
        $site_id_check = "";
        if ( $site_id != "") {
        	$site_id_check = " AND site_id = ".$site_id;	
        }
        
        
        $credit_total = 0;
        $debit_total = 0;

        /*----- Opening Balance ----*/
        $account_credit = $this->crud->getFromSQL("SELECT opening_balance FROM account WHERE credit_debit = 1 and account_id=".$account_id);
        if(!empty($account_credit)){
            $account_credit = $account_credit[0]->opening_balance;
            $credit_total += $account_credit; 
        }

        $account_debit = $this->crud->getFromSQL("SELECT opening_balance FROM account WHERE credit_debit = 2 and account_id=".$account_id);
        if(!empty($account_debit)){
            $account_debit = $account_debit[0]->opening_balance;
            $debit_total += $account_debit; 
        }

        /*-------- Payment ------*/
        $from_payment_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 1 AND from_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $from_payment_amt = $from_payment_amt[0]->total_amount;
        if (!empty($from_payment_amt)) {
            $credit_total += $from_payment_amt;
        }


        $to_payment_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 1 AND to_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $to_payment_amt = $to_payment_amt[0]->total_amount;
        if (!empty($to_payment_amt)) {
            $debit_total += $to_payment_amt;
        }

        /*------- Receipt -------*/
        $from_receipt_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 2 AND from_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $from_receipt_amt = $from_receipt_amt[0]->total_amount;
        if (!empty($from_receipt_amt)) {
            $credit_total += $from_receipt_amt;
        }

        $to_receipt_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 2 AND to_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $to_receipt_amt = $to_receipt_amt[0]->total_amount;
        if (!empty($to_receipt_amt)) {
            $debit_total += $to_receipt_amt;
        }

        /*-------- Contra ------*/
        $from_contra_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 3 AND from_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $from_contra_amt = $from_contra_amt[0]->total_amount;
        if (!empty($from_contra_amt)) {
            $credit_total += $from_contra_amt;
        }

        $to_contra_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 3 AND to_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $to_contra_amt = $to_contra_amt[0]->total_amount;
        if (!empty($to_contra_amt)) {
            $debit_total += $to_contra_amt;
        }

        /*------- Journal ------*/
        $from_journal_amt = $this->crud->getFromSQL("SELECT SUM(amount) as total_amount FROM transaction_entry WHERE transaction_type = 4 AND from_account_id=".$account_id." AND transaction_date <= '".$to_date."'".$site_id_check);
        $from_journal_amt = $from_journal_amt[0]->total_amount;
        if (!empty($from_journal_amt)) {
            $credit_total += $from_journal_amt;
        }

        if ( $site_id_check == "" ) {
        	$to_journal_amt = $this->crud->getFromSQL("SELECT SUM(tr.amount) as total_amount FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id WHERE tr.transaction_type = 4 AND tr.to_account_id=".$account_id." AND tr.transaction_date <= '".$to_date."' AND a.is_kasar_account = 0 ");
        } else {
        	$to_journal_amt = $this->crud->getFromSQL("SELECT SUM(tr.amount) as total_amount FROM transaction_entry as tr LEFT JOIN account as a ON a.account_id = tr.from_account_id WHERE tr.transaction_type = 4 AND tr.to_account_id=".$account_id." AND tr.transaction_date <= '".$to_date."' AND a.is_kasar_account = 0 AND tr.site_id = ".$site_id);
        }
        
        $to_journal_amt = $to_journal_amt[0]->total_amount;
        if (!empty($to_journal_amt)) {
            $debit_total += $to_journal_amt;
        }

        /*-------- Purchase ------*/
        $purchase_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_amount FROM purchase_invoice WHERE account_id=".$account_id." AND purchase_invoice_date <= '".$to_date."'");
        $purchase_amt = $purchase_amt[0]->total_amount;
        if (!empty($purchase_amt)) {
            $credit_total += $purchase_amt;
        }

        $against_purchase_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_amount FROM purchase_invoice WHERE against_account_id=".$account_id." AND purchase_invoice_date <= '".$to_date."'");
        $against_purchase_amt = $against_purchase_amt[0]->total_amount;
        if (!empty($against_purchase_amt)) {
            $debit_total += $against_purchase_amt;
        }
        
        /*-------- Sales ------*/
        $sales_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_amount FROM sales_invoice WHERE account_id=".$account_id." AND sales_invoice_date <= '".$to_date."'");
        $sales_amt = $sales_amt[0]->total_amount;
        if (!empty($sales_amt)) {
            $debit_total += $sales_amt;
        }

        $against_sales_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_amount FROM sales_invoice WHERE against_account_id=".$account_id." AND sales_invoice_date <= '".$to_date."'");
        $against_sales_amt = $against_sales_amt[0]->total_amount;
        if (!empty($against_sales_amt)) {
            $credit_total += $against_sales_amt;
        }

        /*-------- Credit Note (Sales Return) ------*/
        $credit_note_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_amount FROM credit_note WHERE account_id=".$account_id." AND credit_note_date <= '".$to_date."'");
        $credit_note_amt = $credit_note_amt[0]->total_amount;
        if (!empty($credit_note_amt)) {
            $credit_total += $credit_note_amt;
        }

        $against_credit_note_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_amount FROM credit_note WHERE against_account_id=".$account_id." AND credit_note_date <= '".$to_date."'");
        $against_credit_note_amt = $against_credit_note_amt[0]->total_amount;
        if (!empty($against_credit_note_amt)) {
            $debit_total += $against_credit_note_amt;
        }

        /*-------- Debit Note (Purchase Return) ------*/
        $debit_note_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_debit_note_amount FROM debit_note WHERE account_id=".$account_id." AND debit_note_date <= '".$to_date."'");
        $debit_note_amt = $debit_note_amt[0]->total_debit_note_amount;
        if (!empty($debit_note_amt)) {
            $debit_total += $debit_note_amt;
        }

        $against_debit_note_amt = $this->crud->getFromSQL("SELECT SUM(amount_total) as total_debit_note_amount FROM debit_note WHERE against_account_id=".$account_id." AND debit_note_date <= '".$to_date."'");
        $against_debit_note_amt = $against_debit_note_amt[0]->total_debit_note_amount;
        if (!empty($against_debit_note_amt)) {
            $credit_total += $against_debit_note_amt;
        }

        $acc_balance = $debit_total - $credit_total;

        return $acc_balance;
    }

    

    function get_item_stock_data($item_id,$from_date = '',$to_date = '',$account_id = 0)
    {
        $response = array();

        if($account_id == 0) {
        	$opening_qty = $this->crud->get_id_by_val('item','opening_qty','item_id',$item_id);
	        if(empty($opening_qty)) {
	        	$opening_qty = 0;
	        }
	        $opening_amount = $this->crud->get_id_by_val('item','opening_amount','item_id',$item_id);
	        if(empty($opening_amount)) {
	        	$opening_amount = 0;
	        }	
        } else {
        	$opening_qty = 0;
        	$opening_amount = 0;
        }
        
        
        if(strtotime($from_date) > 0) {
            //Opening Stock
            $pre_purchase_stock = 0;
            $pre_purchase_stock_amt = 0;
            $pre_purchase_stock_result = $this->crud->get_purchase_stock_below_fromdate($item_id, $from_date, $account_id, $this->logged_in_id);
            foreach ($pre_purchase_stock_result as $pre_purchase_stock_row) {
                $pre_purchase_stock += $pre_purchase_stock_row->item_qty;
                $pre_purchase_stock_amt += $pre_purchase_stock_row->amount;
            }

            $pre_sales_stock = 0;
            $pre_sales_stock_amt = 0;
            $pre_sales_stock_result = $this->crud->get_sales_stock_below_fromdate($item_id, $from_date, $account_id, $this->logged_in_id);
            foreach ($pre_sales_stock_result as $pre_sales_stock_row) {
                $pre_sales_stock += $pre_sales_stock_row->item_qty;
                $pre_sales_stock_amt += $pre_sales_stock_row->amount;
            }

            /*--- Sub Item Is Sub Item Then Fetch Stock ---*/
            $this->db->select('sd.item_id, 0 as amount,SUM(sd.qty) as item_qty, sales_invoice.sales_invoice_id, sales_invoice.account_id, sales_invoice.sales_invoice_date');
			$this->db->from('item_current_stock_detail sd');
			$this->db->join('sales_invoice', 'sd.invoice_id = sales_invoice.sales_invoice_id AND sd.is_sales_or_purchase = "sales"');
			$this->db->join('account', 'sales_invoice.account_id = account.account_id', 'left');
			$where = "sd.is_sales_or_purchase='sales' AND sd.item_id='".$item_id."' AND `sales_invoice`.`created_by`='".$this->logged_in_id."' ";

			if(!empty($from_date)){
				$where .= " AND `sales_invoice`.`sales_invoice_date` < '".$from_date."' ";
			}
			if(!empty($account_id)){
				$where .= " AND (`sales_invoice`.`account_id`='".$account_id."') ";
			}
			$this->db->where($where);
			$query = $this->db->get();
			$pre_sales_stock_result = $query->result();

			foreach ($pre_sales_stock_result as $pre_sales_stock_row) {
				if($pre_sales_stock_row->item_qty != 0) {
					if($pre_sales_stock_row->item_qty < 0) {
						$pre_sales_stock += abs($pre_sales_stock_row->item_qty);
		            	$pre_sales_stock_amt += $pre_sales_stock_row->amount;
					} else {
						$pre_purchase_stock += abs($pre_sales_stock_row->item_qty);
		            	$pre_purchase_stock_amt += $pre_sales_stock_row->amount;
					}
				}
            }
            /*---/Sub Item Is Sub Item Then Fetch Stock ---*/


            $pre_credit_stock = 0;
            $pre_credit_stock_amt = 0;
            $pre_credit_stock_result = $this->crud->get_credit_stock_below_fromdate($item_id, $from_date, $account_id, $this->logged_in_id);
            foreach ($pre_credit_stock_result as $pre_credit_stock_row) {
                $pre_credit_stock += $pre_credit_stock_row->item_qty;
                $pre_credit_stock_amt += $pre_credit_stock_row->amount;
            }

            $pre_debit_stock = 0;
            $pre_debit_stock_amt = 0;
            $pre_debit_stock_result = $this->crud->get_debit_stock_below_fromdate($item_id, $from_date, $account_id, $this->logged_in_id);
            foreach ($pre_debit_stock_result as $pre_debit_stock_row) {
                $pre_debit_stock += $pre_debit_stock_row->item_qty;
                $pre_debit_stock_amt += $pre_debit_stock_row->amount;
            }

            $opening_stock = $opening_qty + (($pre_purchase_stock + $pre_credit_stock) - ($pre_sales_stock + $pre_debit_stock));
            $opening_amt = $opening_amount + (($pre_purchase_stock_amt + $pre_credit_stock_amt) - ($pre_sales_stock_amt + $pre_debit_stock_amt));
        } else {
            $opening_stock = $opening_qty;
            $opening_amt = $opening_amount;
        }


        // Current Stock
        $purchase_stock = 0;
        $purchase_stock_amt = 0;
        $purchase_stock_result = $this->crud->get_purchase_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $this->logged_in_id);
        foreach ($purchase_stock_result as $purchase_stock_row) {
            $purchase_stock += $purchase_stock_row->item_qty;
            $purchase_stock_amt += $purchase_stock_row->amount;
        }

        $sales_stock = 0;
        $sales_stock_amt = 0;
        $sales_stock_result = $this->crud->get_sales_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $this->logged_in_id);
        foreach ($sales_stock_result as $sales_stock_row) {
            $sales_stock += $sales_stock_row->item_qty;
            $sales_stock_amt += $sales_stock_row->amount;
        }

        /*--- Sub Item Is Sub Item Then Fetch Stock ---*/
        $this->db->select('sd.item_id, 0 as amount,SUM(sd.qty) as item_qty, sales_invoice.sales_invoice_id, sales_invoice.account_id, sales_invoice.sales_invoice_date');
		$this->db->from('item_current_stock_detail sd');
		$this->db->join('sales_invoice', 'sd.invoice_id = sales_invoice.sales_invoice_id AND sd.is_sales_or_purchase = "sales"');
		$this->db->join('account', 'sales_invoice.account_id = account.account_id', 'left');
		$where = "sd.note IN('less sub item','add sub item','reverse less sub item','reverse add sub item') AND sd.is_sales_or_purchase='sales' AND sd.item_id='".$item_id."' AND `sales_invoice`.`created_by`='".$this->logged_in_id."' ";

		if(!empty($from_date) && !empty($to_date)){
			$where .= " AND (`sales_invoice`.`sales_invoice_date` >='".$from_date."' AND `sales_invoice`.`sales_invoice_date` <='".$to_date."') ";
		}

		if(!empty($account_id)){
			$where .= " AND (`sales_invoice`.`account_id`='".$account_id."') ";
		}
		$where .= " GROUP BY sd.id";
		$this->db->where($where);
		$query = $this->db->get();		
		if($query->num_rows() > 0) {
			$sales_stock_result = $query->result();
			foreach ($sales_stock_result as $sales_stock_row) {
				if($sales_stock_row->item_qty != 0) {
					if($sales_stock_row->item_qty < 0) {
						$sales_stock += abs($sales_stock_row->item_qty);
		            	$sales_stock_amt += $sales_stock_row->amount;
					} else {
						$purchase_stock += abs($sales_stock_row->item_qty);
		            	$purchase_stock_amt += $sales_stock_row->amount;
					}
				}
	        }
		}
        /*---/Sub Item Is Sub Item Then Fetch Stock ---*/

        $credit_stock = 0;
        $credit_stock_amt = 0;
        $credit_stock_result = $this->crud->get_credit_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $this->logged_in_id);
        foreach ($credit_stock_result as $credit_stock_row) {
            $credit_stock += $credit_stock_row->item_qty;
            $credit_stock_amt += $credit_stock_row->amount;
        }

        $debit_stock = 0;
        $debit_stock_amt = 0;
        $debit_stock_result = $this->crud->get_debit_stock_for_datarange($item_id, $from_date, $to_date, $account_id, $this->logged_in_id);
        foreach ($debit_stock_result as $debit_stock_row) {
            $debit_stock += $debit_stock_row->item_qty;
            $debit_stock_amt += $debit_stock_row->amount;
        }

        
        $inward = $purchase_stock + $credit_stock;
        $inward_amt = $purchase_stock_amt + $credit_stock_amt;

        $outward = $sales_stock + $debit_stock;
        $outward_amt = $sales_stock_amt + $debit_stock_amt;
        
        $closing_stock = $opening_stock + ($inward - $outward);
        $closing_amt = $opening_amt + ($inward_amt - $outward_amt);
        $closing_amt = $purchase_stock_amt;
        if(isset($pre_purchase_stock_amt)) {
        	$closing_amt += $pre_purchase_stock_amt;
        }
        $response['opening_amt'] = $opening_amt;
        $response['opening_stock'] = $opening_stock;
        $response['inward'] = $inward;
        $response['inward_amt'] = $inward_amt;
        $response['outward'] = $outward;
        $response['outward_amt'] = $outward_amt;
        $response['closing_stock'] = $closing_stock;
        $response['closing_amt'] = $closing_amt;
        return $response;
    }
}
