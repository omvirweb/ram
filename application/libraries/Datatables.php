<?php

/**
 * Class Datatables
 */
class Datatables
{
	var $select = '';
	var $table = '';
	var $joins = array();
	var $wheres = array();
	var $column_search = array();
	var $column_order = array();
	var $order = array();
	var $likes = array();
	var $where_string = array();
	var $custom_where = array();
	var $in_not_in_where = array();
	var $group_by = 0;


	/**
     * Datatables constructor.
     * @param array $config
     */
	function __construct(array $config = array())
	{
		$this->ci =& get_instance();
		$this->ci->load->database();

		if (count($config) > 0)
		{
			$this->initialize($config);
		}
	}

	/**
     * Initialize preferences
     *
     * @param	array
     * @return	CI_Datatables
     */
	public function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
		return $this;
	}

	/**
     *
     */
	private function _get_datatables_query()
	{
		if($this->select != ''){
			$this->ci->db->select($this->select);
		}

		$this->ci->db->from($this->table);

		if(!empty($this->joins)){
			foreach($this->joins as $join){
				if(isset($join['join_table']) &&  isset($join['join_by']) && $join['join_table'] != '' && $join['join_by'] != ''){
					$join_table = $join['join_table'];
					$join_by = $join['join_by'];
					$join_type = isset($join['join_type'])?$join['join_type']:'inner';
					$this->ci->db->join("$join_table","$join_by","$join_type");
				}
			}
		}

		if(!empty($this->custom_where)){
			$this->ci->db->where($this->custom_where);
		}

		if(!empty($this->in_not_in_where)){
			$this->ci->db->where($this->in_not_in_where, NULL, FALSE);
		}

		if(!empty($this->wheres)){
			foreach($this->wheres as $where){
				if(isset($where['column_name']) &&  isset($where['column_value']) && $where['column_name'] != '' && $where['column_value'] != ''){
					$column_name = $where['column_name'];
					$column_value = $where['column_value'];
					$this->ci->db->where($column_name,$column_value);
				} elseif(isset($where['where_str'])){
					$this->ci->db->where($where['where_str']);
				}
			}
		}
		
		if(!empty($this->where_string)){
			$this->ci->db->or_where($this->where_string);
		}

		if(!empty($this->likes)){
			foreach($this->likes as $like){
				$this->ci->db->like(key($like), $like[key($like)]);
			}
		}

		$i = 0;
		foreach ($this->column_search as $item) // loop column
		{
			if(isset($_POST['search']['value'])) // if datatable send POST for search
			{
				if($i===0)
				{
					$this->ci->db->group_start();
					$this->ci->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->ci->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search) - 1 == $i)
					$this->ci->db->group_end();
			}
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			if(isset($this->column_order[$_POST['order']['0']['column']])){
				$this->ci->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			if(isset($order[key($order)])){
				$this->ci->db->order_by(key($order), $order[key($order)]);
			}
		}

		if(!empty($this->group_by)) {
			$this->ci->db->group_by($this->group_by);
		}

	}

	/**
     * @return mixed
     */
	function get_datatables()
	{
		$this->_get_datatables_query();
		if(isset($_POST['length']) && $_POST['length'] != -1)
			$this->ci->db->limit($_POST['length'],$_POST['start']);
		$query = $this->ci->db->get();
		//echo $this->ci->db->last_query();
		return $query->result();
	}

	/**
     * @return mixed
     */
	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->ci->db->get();
		return $query->num_rows();
	}

	/**
     * @return mixed
     */
	public function count_all()
	{
		$this->ci->db->from($this->table);

		if(!empty($this->joins)){
			foreach($this->joins as $join){
				if(isset($join['join_table']) &&  isset($join['join_by']) && $join['join_table'] != '' && $join['join_by'] != ''){
					$join_table = $join['join_table'];
					$join_by = $join['join_by'];
					$join_type = isset($join['join_type'])?$join['join_type']:'inner';
					$this->ci->db->join("$join_table","$join_by","$join_type");
				}
			}
		}

		if(!empty($this->custom_where)){
			$this->ci->db->where($this->custom_where);
		}

		if(!empty($this->in_not_in_where)){
			$this->ci->db->where($this->in_not_in_where, NULL, FALSE);
		}

		if(!empty($this->wheres)){
			foreach($this->wheres as $where){
				if(isset($where['column_name']) &&  isset($where['column_value']) && $where['column_name'] != '' && $where['column_value'] != ''){
					$column_name = $where['column_name'];
					$column_value = $where['column_value'];
					$this->ci->db->where($column_name,$column_value);
				}
			}
		}
		
		if(!empty($this->where_string)){
			$this->ci->db->or_where($this->where_string);
		}



		if(!empty($this->group_by)) {
			$this->ci->db->group_by($this->group_by);
		}

		return $this->ci->db->count_all_results();
	}
}
