<?php

/**
 * Class app_model
 * &@property CI_Controller $ci
 */
class Applib
{
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->ci->load->library('session');
        $this->logged_in_id = $this->ci->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
    }

    function format_invoice_number($invoice_id, $invoice_date = '') {
        $this->ci->db->select('sales_invoice_no');
        $this->ci->db->from('sales_invoice');
        $this->ci->db->where('sales_invoice_id', $invoice_id);
        $sales_inv_row = $this->ci->db->get()->row();
        $invoice_no = '';
        if(!empty($sales_inv_row)) {
            $invoice_no = $sales_inv_row->sales_invoice_no;
        }

        // get prefix
        $this->ci->db->select('company_invoice_prefix.prefix_name,sales_invoice.sales_invoice_no');
        $this->ci->db->from('company_invoice_prefix');
        $this->ci->db->join('sales_invoice', 'sales_invoice.prefix = company_invoice_prefix.id');
        $this->ci->db->where('company_invoice_prefix.company_id', $this->logged_in_id);
        $this->ci->db->where('sales_invoice.sales_invoice_id', $invoice_id);
        $result = $this->ci->db->get()->row();

        /*if(empty($result)) {
            $this->ci->db->select('company_invoice_prefix.prefix_name');
            $this->ci->db->from('company_invoice_prefix');
            $this->db->join('sales_invoice', 'sales_invoice.prefix = company_invoice_prefix.id');
            $this->ci->db->where('company_invoice_prefix.company_id', $this->logged_in_id);
            $this->ci->db->where('sales_invoice.sales_invoice_no', $invoice_no);
            $result = $this->ci->db->get()->row();
        }*/
        $prefix = '';
        if(!empty($result)) {
            $prefix = $result->prefix_name;
        }

        // get invoice no digit
        $this->ci->db->select('setting_value');
        $this->ci->db->from('company_settings');
        $this->ci->db->where('setting_key', 'invoice_no_digit');
        $this->ci->db->where('company_id', $this->logged_in_id);
        $this->ci->db->where('module_name', 3);
        $result = $this->ci->db->get()->row();
        $invoice_no_digit = '';
        if(!empty($result)){
            $invoice_no_digit = $result->setting_value;
        }
        $lenght = strlen((string)$invoice_no);
        if($invoice_no_digit != 0 || $invoice_no_digit != '' || $invoice_no_digit > $lenght) {
            $no_of_zeros_to_add = $invoice_no_digit - $lenght;
            if($no_of_zeros_to_add > 0) {
                for($i = 1; $i <= $no_of_zeros_to_add; $i++){
                    $invoice_no = '0'.$invoice_no;
                }
            }
        }
        $invoice_no = (!empty($prefix)) ? $prefix.''.$invoice_no : $invoice_no;
        // get Year Is Finacial Year
        $this->ci->db->select('setting_value');
        $this->ci->db->from('company_settings');
        $this->ci->db->where('setting_key', 'year_post_fix');
        $this->ci->db->where('company_id', $this->logged_in_id);
        $this->ci->db->where('module_name', 3);
        $result = $this->ci->db->get()->row();
        $year_post = 0;
        if(!empty($result)) {
            $year_post = $result->setting_value;
        }
        if($year_post == 1) {
            $current_month = date('m');
            if($current_month <= 3){
                $current_year = date('Y', strtotime($invoice_date));
                $past_year = date('y', strtotime("-1 year", strtotime($invoice_date)));
                $financial_year = $past_year.'-'.$current_year;
            } else {
                $current_year = date('Y', strtotime($invoice_date));
                $next_year = date('y', strtotime("+1 year", strtotime($invoice_date)));
                $financial_year = $current_year.'-'.$next_year;
            }
            $invoice_no = $invoice_no.'/'.$financial_year;
        }
        return $invoice_no;
    }
    
    function have_access_role($module, $role){
        $status = 0;
        $user_roles = $this->ci->session->userdata('user_roles');
        //echo '<pre>';print_r($user_roles);die();
        $role = strtolower($role);
        if(isset($user_roles[$module]) && in_array($role, $user_roles[$module]))
        {
            $status = 1;
        }
        return $status;
    }

    function sundry_creditors_debtors_cash_in_hand_account_group_ids()
    {
        $account_group_ids = array();
        $this->ci->db->select("account_group_id");
        $this->ci->db->from("account_group");
        $this->ci->db->where_in('account_group_id',array(39,40,41,42,26));
        $query = $this->ci->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $account_group_ids[] = $row->account_group_id;
                $sub_account_group_ids = $this->get_sub_account_group_ids($row->account_group_id);
                if(!empty($sub_account_group_ids)) {
                    $account_group_ids = array_merge($sub_account_group_ids,$account_group_ids);
                }
                
            }
        }
        return $account_group_ids;
    }

    function get_sub_account_group_ids($account_group_id)
    {
        $account_group_ids = array();
        $this->ci->db->select("account_group_id");
        $this->ci->db->from("account_group");
        $this->ci->db->where('parent_group_id',$account_group_id);
        $query = $this->ci->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $account_group_ids[] = $row->account_group_id;
                $sub_account_group_ids = $this->get_sub_account_group_ids($row->account_group_id);
                if(!empty($sub_account_group_ids)) {
                    $account_group_ids = array_merge($sub_account_group_ids,$account_group_ids);
                }
            }
        }
        return $account_group_ids;
    }

    function is_cash_in_hand_account($account_id)
    {
        $cash_in_hand_acc_group_ids = array(CASH_IN_HAND_ACC_GROUP_ID);
        $sub_account_group_ids = $this->get_sub_account_group_ids(CASH_IN_HAND_ACC_GROUP_ID);
        if(!empty($sub_account_group_ids)) {
            $cash_in_hand_acc_group_ids = array_merge($sub_account_group_ids,$cash_in_hand_acc_group_ids);
        }

        $account_row = $this->ci->crud->get_data_row_by_where('account',array('account_id' => $account_id));
        if(in_array($account_row->account_group_id,$cash_in_hand_acc_group_ids)) {
            $cash_in_hand_acc = true;
        } else {
            $cash_in_hand_acc = false;
        }
        return $cash_in_hand_acc;
    }

    function get_kasar_account_id()
    {
        $kasar_account_row = $this->ci->crud->get_data_row_by_where('account',array('is_kasar_account' => 1,'created_by' => $this->logged_in_id));
        if(!empty($kasar_account_row->account_id)) {
            return $kasar_account_row->account_id;
        } else {
            $account_data = array(
                'account_name' => 'Kasar Account',
                'account_group_id' => EXPENSE_ACCOUNT_GROUP_ID,
                'is_kasar_account' => 1,
                'consider_in_pl' => 1,
                'created_by' => $this->logged_in_id,
                'user_created_by' => $this->ci->session->userdata()['login_user_id'],
                'created_at' => date("Y-m-d H:i:s"),
            );
            $account_id = $this->ci->crud->insert('account',$account_data);
            return $account_id;
        }
    }

    function getIndianCurrency($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ucwords(($Rupees ? $Rupees . 'Rupees ' : '') . $paise .'Only');
    }
}
