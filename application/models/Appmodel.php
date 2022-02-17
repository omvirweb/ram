<?php
ini_set('max_execution_time', 3000);

/**
 * Class AppModel
 * @property CI_DB_active_record $db
 */
class AppModel extends CI_Model
{
    protected $currCompetition = 0;
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ------------------- Required Methods
     */

    /**
     * @param $username
     * @param $pass
     * @return bool
     */
    function login($username,$pass){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('user',$username);
        $this->db->where('password',md5($pass));
        $this->db->limit('1');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return false;
        }
    }

    
    public function check_party_email_validation($email, $id)
    {
        $duplicate = 0;
        if($id > 0)
        {
            $email = trim(strtolower($email));
            $sql_list_query = "SELECT * FROM party WHERE TRIM(LOWER(email_id)) LIKE '%".addslashes($email)."%' AND party_id != $id";
            $query = $this->db->query($sql_list_query);
            $rows = $query->result_array();
            if(count($rows) > 0)
            {
                $duplicate = 1;
            }                  
        }   
        else
        {
            $email = trim(strtolower($email));
            $sql_list_query = "SELECT * FROM party WHERE TRIM(LOWER(email_id)) LIKE '%".addslashes($email)."%'";
            $query = $this->db->query($sql_list_query);
            $rows = $query->result_array();
            if(count($rows) > 0)
            {
                $duplicate = 1;
            }                  
        } 

        return $duplicate;     
    }

    /**
     * ------------------- Required Methods
     */

    /**
     * @param $table
     * @param $column_order
     * @param $column_search
     * @param int $user_type
     */

    private function get_datatables_query($table,$column_order,$column_search,$user_type = 3)
    {
        $this->db->where('competition_id =',$this->currCompetition);
        $this->db->where('user_type !=',1);
        if($user_type == 2){
            $this->db->where('user_type !=',3);
        }else{
            $this->db->where('user_type !=',2);
        }
        $this->db->from($table);
        $i = 0;
        foreach ($column_search as $item) // loop column
        {
            if(isset($_POST['search']) && $_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($column_order))
        {
            $order = $column_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

    }
    function get_datatables($table,$column_order,$column_search,$user_type = 3)
    {
        $this->get_datatables_query($table,$column_order,$column_search,$user_type);
        if(isset($_POST['length']) && $_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table,$column_order,$column_search,$user_type = 3)
    {
        $this->get_datatables_query($table,$column_order,$column_search,$user_type);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table,$user_type = 3)
    {
        $this->db->from($table);
        $this->db->where('user_type',$user_type);
        $this->db->where('competition_id',$this->currCompetition);
        return $this->db->count_all_results();
    }

    /**
     * @param $table
     * @param $column
     * @param $search_value
     * @return mixed
     */
    function getAutoCompleteData($table,$column,$search_value){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->like($column,$search_value);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * @param $table
     * @param $id_column
     * @param $column
     * @param $column_val
     * @return int
     */
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
        
        /* else{
            $this->db->insert($table,array($column=>$column_val));
            return $this->db->insert_id();
        } */
    }


    /**
     * @param $table_name
     * @param $column_nm
     * @param $column_val
     * @return bool
     */
    function check_val_exist($table_name,$column_nm,$column_val){
        $this->db->where($column_nm,$column_val);
        $query = $this->db->get($table_name);
        return $query->num_rows() > 0;
    }

    /**
     * @return mixed
     */
    public function get_config()
    {
        return $this->db->get('config');
    }
    
    function getUserRoleIDS($user_id)
    {
        $array = array();
        $sql = "SELECT * FROM user_roles WHERE user_id='" . $user_id . "'";
        $rows = $this->db->query($sql)->result();
        $i = 0;
        foreach ($rows as $row) {
            //$array[$i] = $row->website_module_id;
            $array[$i] = $row->role_type_id;
            $i++;
        }
        return $array;
    }

    public function getModuleRoles()
    {
        $array = array();
        $sql = "SELECT * FROM website_modules ORDER BY INET_ATON(REPLACE(TRIM(RPAD(main_module,8,' 0')),' ','.'))";
        $rows = $this->db->query($sql)->result();

        $i = 0;
        foreach ($rows as $row) {
            $sql = "SELECT * FROM module_roles WHERE website_module_id='" . $row->website_module_id . "'";
            $roles = $this->db->query($sql)->result_array();

            $array[$i]['id'] = $row->website_module_id;
            $array[$i]['title'] = $row->title;
            $array[$i]['main_module'] = $row->main_module;
            $array[$i]['roles'] = $roles;

            $i++;
        }
//        echo "<pre>"; print_r($array); exit;
        return $array;
    }
    
}