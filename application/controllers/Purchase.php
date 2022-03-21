<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Purchase
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Purchase extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;

    function __construct() {
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

    //~ Purchase Invoice
    function invoice() {
        redirect('transaction/sales_purchase_transaction/purchase');
        exit();
        
        if($this->is_single_line_item == 1) {
            redirect('transaction/sales_purchase_transaction/purchase');
            exit();
        }

        $data = array();
        $data['type'] = 'purchase';
        $line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 2 AND setting_value = 1');
        $invoice_line_item_fields = array();
        if(!empty($line_item_fields)) {
            foreach ($line_item_fields as $value) {
                $invoice_line_item_fields[] = $value->setting_key;
            }
        }
        $data['invoice_line_item_fields'] = $invoice_line_item_fields;

        if (isset($_POST['purchase_invoice_id'])) {
            if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"edit")) {
                $where = array('purchase_invoice_id' => $_POST['purchase_invoice_id']);
                $purchase_invoice_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                $data['purchase_invoice_data'] = $purchase_invoice_data[0];
                $lineitems = '';
                $where = array('module' => '1', 'parent_id' => $_POST['purchase_invoice_id']);
                $purchase_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                foreach ($purchase_invoice_lineitems as $purchase_invoice_lineitem) {
                    //$purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->item_qty * $purchase_invoice_lineitem->price;
                    $purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->pure_amount;
                    $purchase_invoice_lineitem->cgst_amt = $purchase_invoice_lineitem->cgst_amount;
                    $purchase_invoice_lineitem->sgst_amt = $purchase_invoice_lineitem->sgst_amount;
                    $purchase_invoice_lineitem->igst_amt = $purchase_invoice_lineitem->igst_amount;
                    $lineitems .= "'" . json_encode($purchase_invoice_lineitem) . "',";
                }
                $data['purchase_invoice_lineitems'] = $lineitems;
                set_page('purchase/invoice/invoice', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"add")) {
                set_page('purchase/invoice/invoice', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }
        
    }

    function order() {
        redirect('transaction/sales_purchase_transaction/order');
        exit();

        if($this->is_single_line_item == 1) {
            redirect('transaction/sales_purchase_transaction/order');
            exit();
        }

        $data = array();
        $data['type'] = 'Order';
        $line_item_fields = $this->crud->getFromSQL('SELECT setting_key FROM company_settings WHERE company_id = "'.$this->logged_in_id.'" AND module_name = 2 AND setting_value = 1');
        $invoice_line_item_fields = array();
        if(!empty($line_item_fields)) {
            foreach ($line_item_fields as $value) {
                $invoice_line_item_fields[] = $value->setting_key;
            }
        }
        $data['invoice_line_item_fields'] = $invoice_line_item_fields;

        if (isset($_POST['purchase_invoice_id'])) {
            if($this->applib->have_access_role(MODULE_ORDER_ID,"edit")) {
                $where = array('purchase_invoice_id' => $_POST['purchase_invoice_id']);
                $purchase_invoice_data = $this->crud->get_row_by_id('purchase_invoice', $where);
                $data['purchase_invoice_data'] = $purchase_invoice_data[0];
                $lineitems = '';
                $where = array('module' => '1', 'parent_id' => $_POST['purchase_invoice_id']);
                $purchase_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                foreach ($purchase_invoice_lineitems as $purchase_invoice_lineitem) {
                    //$purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->item_qty * $purchase_invoice_lineitem->price;
                    $purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->pure_amount;
                    $purchase_invoice_lineitem->cgst_amt = $purchase_invoice_lineitem->cgst_amount;
                    $purchase_invoice_lineitem->sgst_amt = $purchase_invoice_lineitem->sgst_amount;
                    $purchase_invoice_lineitem->igst_amt = $purchase_invoice_lineitem->igst_amount;
                    $lineitems .= "'" . json_encode($purchase_invoice_lineitem) . "',";
                }
                $data['purchase_invoice_lineitems'] = $lineitems;
                set_page('purchase/invoice/invoice', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        } else {
            if($this->applib->have_access_role(MODULE_ORDER_ID,"add")) {
                set_page('purchase/invoice/invoice', $data);
            } else {
                $this->session->set_flashdata('success', false);
                $this->session->set_flashdata('message', 'You have not permission to access this page.');
                redirect('/');
            }
        }
    }

    function save_purchase_invoice() {
        $post_data = $this->input->post();
        //~ echo '<pre>';print_r($post_data); exit;
        $line_items_data = json_decode('[' . $post_data['line_items_data'] . ']');
        //~ print_r($line_items_data); exit;
        $purchase_invoice_data = array();
        $purchase_invoice_data['account_id'] = $post_data['account_id'];
        $purchase_invoice_data['purchase_invoice_date'] = date('Y-m-d', strtotime($post_data['purchase_invoice_date']));
        $purchase_invoice_data['bill_no'] = $post_data['bill_no'];
        $purchase_invoice_data['purchase_invoice_desc'] = $post_data['purchase_invoice_desc'];
        $purchase_invoice_data['qty_total'] = $post_data['qty_total'];
        $purchase_invoice_data['pure_amount_total'] = $post_data['pure_amount_total'];
        $purchase_invoice_data['discount_total'] = $post_data['discount_total'];
        $purchase_invoice_data['cgst_amount_total'] = $post_data['cgst_amount_total'];
        $purchase_invoice_data['sgst_amount_total'] = $post_data['sgst_amount_total'];
        $purchase_invoice_data['igst_amount_total'] = $post_data['igst_amount_total'];
        $purchase_invoice_data['amount_total'] = $post_data['amount_total'];
        $purchase_invoice_data['invoice_type'] = isset($post_data['invoice_type']) ? $post_data['invoice_type'] : 1;

        $dateranges = $this->crud->getFromSQL('SELECT `daterange` FROM `locked_daterange` WHERE `user_id`="' . $this->logged_in_id . '" ');
        if (!empty($dateranges)) {
            foreach ($dateranges as $daterange) {
                $pieces = explode(" ", $daterange->daterange);
                $from = date('d-m-Y', strtotime($pieces[0]));
                $to = date('d-m-Y', strtotime($pieces[2]));
                $purchase_invoice_date = date('d-m-Y', strtotime($post_data['purchase_invoice_date']));
                if (strtotime($purchase_invoice_date) >= strtotime($from) && strtotime($purchase_invoice_date) <= strtotime($to)) {
                    $return['error'] = "Locked_Date";
                    print json_encode($return);
                    exit;
                }
            }
        }
        if (isset($post_data['purchase_invoice_id']) && !empty($post_data['purchase_invoice_id'])) {
            
            $purchase_invoice_data['updated_at'] = $this->now_time;
            $purchase_invoice_data['updated_by'] = $this->logged_in_id;
            $purchase_invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];
            $where_array['purchase_invoice_id'] = $post_data['purchase_invoice_id'];
            $result = $this->crud->update('purchase_invoice', $purchase_invoice_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Purchase Invoice Updated Successfully');
                $parent_id = $post_data['purchase_invoice_id'];
                if (isset($post_data['deleted_lineitem_id']) && !empty($post_data['deleted_lineitem_id'])) {
                    $deleted_lineitem_ids = $post_data['deleted_lineitem_id'];

                    if($purchase_invoice_data['invoice_type'] == 2) {
                        $lineitems_res = $this->crud->get_where_in_result('lineitems', 'id', $deleted_lineitem_ids);
                        if(!empty($lineitems_res)) {
                            foreach ($lineitems_res as $key => $lineitem) {
                                $this->crud->update_item_current_stock_qty($lineitem->item_id,$lineitem->parent_id,'purchase',$lineitem->item_qty,'delete');       
                            }
                        }
                    }

                    $this->crud->delete_where_in('lineitems', 'id', $deleted_lineitem_ids);
                }
                foreach ($line_items_data[0] as $lineitem) {
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
                    $add_lineitem['module'] = 1;
                    $add_lineitem['parent_id'] = $parent_id;
                    $add_lineitem['rate_for_itax'] = $lineitem->rate_for_itax;
                    $add_lineitem['price_for_itax'] = $lineitem->price_for_itax;
                    $add_lineitem['igst_for_itax'] = $lineitem->igst_for_itax;
                    if (isset($lineitem->id) && !empty($lineitem->id)) {

                        if($purchase_invoice_data['invoice_type'] == 2) {
                            $this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'purchase',$lineitem->item_qty,'update');
                        }

                        $add_lineitem['updated_at'] = $this->now_time;
                        $add_lineitem['updated_by'] = $this->logged_in_id;
                        $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                        $where_id['id'] = $lineitem->id;
                        $this->crud->update('lineitems', $add_lineitem, $where_id);


                    } else {
                        if($purchase_invoice_data['invoice_type'] == 2) {
                            $this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'purchase',$lineitem->item_qty,'add');
                        }

                        $add_lineitem['created_at'] = $this->now_time;
                        $add_lineitem['created_by'] = $this->logged_in_id;
                        $add_lineitem['user_created_by'] = $this->session->userdata()['login_user_id'];
                        $add_lineitem['updated_at'] = $this->now_time;
                        $add_lineitem['updated_by'] = $this->logged_in_id;
                        $add_lineitem['user_updated_by'] = $this->session->userdata()['login_user_id'];
                        $this->crud->insert('lineitems', $add_lineitem);
                    }
                }
            }
        } else {
            $purchase_invoice_data['created_at'] = $this->now_time;
            $purchase_invoice_data['created_by'] = $this->logged_in_id;
            $purchase_invoice_data['user_created_by'] = $this->session->userdata()['login_user_id'];
            $purchase_invoice_data['updated_at'] = $this->now_time;
            $purchase_invoice_data['updated_by'] = $this->logged_in_id;
            $purchase_invoice_data['user_updated_by'] = $this->session->userdata()['login_user_id'];

            $purchase_invoice_no = $this->crud->get_max_number('purchase_invoice', 'purchase_invoice_no', $this->logged_in_id);
            $purchase_invoice_data['purchase_invoice_no'] = $purchase_invoice_no->purchase_invoice_no + 1;

            $result = $this->crud->insert('purchase_invoice', $purchase_invoice_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Purchase Invoice Added Successfully');
                $parent_id = $this->db->insert_id();
                foreach ($line_items_data[0] as $lineitem) {
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
                    $add_lineitem['module'] = 1;
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
                    $this->crud->insert('lineitems', $add_lineitem);

                    if($purchase_invoice_data['invoice_type'] == 2) {
                        $this->crud->update_item_current_stock_qty($lineitem->item_id,$parent_id,'purchase',$lineitem->item_qty,'add');
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function invoice_list() {
        if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"view")) {
            $data = array();
            $data['invoice_type'] = 2;
            set_page('purchase/invoice/invoice_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function order_invoice_list() {
        if($this->applib->have_access_role(MODULE_ORDER_ID,"view")) {
            $data = array();
            $data['page_title'] = 'Order';
            $data['invoice_type'] = 1;
            set_page('purchase/invoice/invoice_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function order_type2_invoice_list() {
        if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) {
            $data = array();
            $data['page_title'] = 'Order';
            $data['invoice_type'] = 3;
            set_page('purchase/invoice/invoice_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function invoice_datatable() {
//        echo '<pre>';print_r($_POST); exit;
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
        $config['table'] = 'purchase_invoice pi';
        $config['select'] = 'pi.purchase_invoice_id, pi.purchase_invoice_no, pi.bill_no, pi.purchase_invoice_date, pi.amount_total, pi.data_lock_unlock, a.account_name, a.account_gst_no';
        $config['column_order'] = array(null, 'pi.purchase_invoice_no', 'a.account_name', 'pi.purchase_invoice_date', 'pi.amount_total');
        $config['column_search'] = array('pi.purchase_invoice_no', 'a.account_name', 'DATE_FORMAT(pi.purchase_invoice_date,"%d-%m-%Y")', 'pi.amount_total');
        $config['wheres'][] = array('column_name' => 'pi.created_by', 'column_value' => $this->logged_in_id);
        $config['wheres'][] = array('column_name' => 'pi.invoice_type', 'column_value' => $_POST['invoice_type']);
        if (!empty($account_id)) {
            $config['custom_where'] = 'pi.account_id IN (' . implode(",", $account_id) . ') ';
        }
        if (!empty($from_date) && !empty($to_date)) {
            $config['wheres'][] = array('column_name' => 'pi.purchase_invoice_date >=', 'column_value' => $from_date);
            $config['wheres'][] = array('column_name' => 'pi.purchase_invoice_date <=', 'column_value' => $to_date);
        }
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pi.account_id', 'join_type' => 'left');
        $config['order'] = array('pi.created_at' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();

        if($_POST['invoice_type'] == 1) {
            $isEdit = $this->applib->have_access_role(MODULE_ORDER_ID, "edit");
            $isDelete = $this->applib->have_access_role(MODULE_ORDER_ID, "delete");

        } elseif($_POST['invoice_type'] == 3) {
            $isEdit = $this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID, "edit");
            $isDelete = $this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID, "delete");
        
        } else {
            $isEdit = $this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID, "edit");
            $isDelete = $this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID, "delete");
        }

        $data = array();
        foreach ($list as $order) {
            $row = array();
            $action = '';
            if ($order->data_lock_unlock == 0) {
                if($isEdit) {
                    if($_POST['invoice_type'] == 3) {
                        $action .= '<form id="edit_' . $order->purchase_invoice_id . '" method="post" action="' . base_url('transaction/order_type2') . '" style="width: 25px; display: initial;" >
                                        <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $order->purchase_invoice_id . '">
                                        <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->purchase_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
                                    </form> ';
                    } else if($_POST['invoice_type'] == 4) {
                        $action .= '<form id="edit_' . $order->purchase_invoice_id . '" method="post" action="' . base_url('transaction/sales_purchase_transaction/material_in') . '" style="width: 25px; display: initial;" >
                                        <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $order->purchase_invoice_id . '">
                                        <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->purchase_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
                                    </form> ';
                    } else {
                        $action .= '<form id="edit_' . $order->purchase_invoice_id . '" method="post" action="' . base_url(isset($_POST['invoice_type']) && $_POST['invoice_type'] == '2' ? 'transaction/sales_purchase_transaction/purchase' : 'transaction/sales_purchase_transaction/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $order->purchase_invoice_id . '">
                                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->purchase_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
                                        </form> ';
                        
                    }
                    if($_POST['invoice_type'] == 1) {
                        $action .= ' &nbsp;<a href="' . base_url("transaction/sales_purchase_transaction/sales/" . $order->purchase_invoice_id) . '" title="Transfer To Sales Invoice"><span class="forward_button glyphicon glyphicon-forward data-href="#"" style="color : #419bf4" ></span></a>';
                    }
                    /*if($this->is_single_line_item == 1) {
                        $action .= '<form id="edit_' . $order->purchase_invoice_id . '" method="post" action="' . base_url(isset($_POST['invoice_type']) && $_POST['invoice_type'] == '2' ? 'transaction/sales_purchase_transaction/purchase' : 'transaction/sales_purchase_transaction/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $order->purchase_invoice_id . '">
                                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->purchase_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
                                        </form> ';
                    } else {
                        $action .= '<form id="edit_' . $order->purchase_invoice_id . '" method="post" action="' . base_url(isset($_POST['invoice_type']) && $_POST['invoice_type'] == '2' ? 'transaction/sales_purchase_transaction/purchase' : 'transaction/sales_purchase_transaction/order') . '" style="width: 25px; display: initial;" >
                                            <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $order->purchase_invoice_id . '">
                                            <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $order->purchase_invoice_id . '\').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
                                        </form> ';
                    }*/
                }

                if($isDelete) {
                    $action .= ' &nbsp; <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('purchase/invoice_delete/' . $order->purchase_invoice_id) . '"><i class="fa fa-trash"></i></a>';    
                }
                
                
                
                $action_detail = ' &nbsp; <form id="detail_' . $order->purchase_invoice_id . '" method="post" action="' . base_url() . 'purchase/invoice_detail" style="width: 25px; display: initial;" >
                                        <input type="hidden" name="purchase_invoice_id" id="purchase_invoice_id" value="' . $order->purchase_invoice_id . '">
                                        <a class="detail_button btn-info btn-xs" href="javascript:{}" onclick="document.getElementById(\'detail_' . $order->purchase_invoice_id . '\').submit();" title="Invoice Detail"><i class="fa fa-eye"></i></a>
                                    </form>';
            
            
            }
            
            $action .= '&nbsp;<a href="' . base_url('purchase/format_3_invoice_print/' . $order->purchase_invoice_id) . '" target="_blank" title="Invoice Print" class="detail_button btn-info btn-xs"><span class="fa fa-print"></span></a>';

            $row[] = $action;
            $row[] = $order->bill_no;
            $row[] = $order->account_name;
            $row[] = $order->account_gst_no;
            $row[] = date('d-m-Y', strtotime($order->purchase_invoice_date));
            $row[] = number_format($order->amount_total, 2, '.', '');
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

    function invoice_delete($purchase_invoice_id) {
        
        $this->crud->delete('stock_status_change', array('tr_type' => 1, 'tr_id' => $purchase_invoice_id));
        
        $this->crud->kasar_entry(0,$purchase_invoice_id,'purchase',0,'delete');
        
        $purchase_invoice_row = $this->crud->get_data_row_by_id('purchase_invoice','purchase_invoice_id',$purchase_invoice_id);
        if(!empty($purchase_invoice_row->invoice_type) && $purchase_invoice_row->invoice_type == 2) {
            $lineitems_res = $this->crud->get_row_by_id('lineitems',array('module' => 1, 'parent_id' => $purchase_invoice_id));
            if(!empty($lineitems_res)) {
                foreach ($lineitems_res as $key => $lineitem) {
                    $this->crud->update_item_current_stock_qty($lineitem->item_id,$lineitem->parent_id,'purchase',$lineitem->item_qty,'delete');       
                }
            }
        }
        $this->crud->delete('purchase_invoice', array('purchase_invoice_id' => $purchase_invoice_id));
        $this->crud->delete('lineitems', array('module' => 1, 'parent_id' => $purchase_invoice_id));
    }

    /* function invoice_detail(){
      if(!empty($_POST['purchase_invoice_id']) && isset($_POST['purchase_invoice_id'])){
      $result = $this->crud->get_data_row_by_id('purchase_invoice','purchase_invoice_id',$_POST['purchase_invoice_id']);
      $data = array(
      'purchase_invoice_id' => $result->purchase_invoice_id,
      'purchase_invoice_no' => $result->purchase_invoice_no,
      'purchase_invoice_date' => date('d-m-Y', strtotime($result->purchase_invoice_date)),
      'purchase_invoice_desc' => nl2br($result->purchase_invoice_desc),
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
      $where = array('module' => '1', 'parent_id' => $result->purchase_invoice_id);
      $purchase_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
      $lineitem_arr = array();
      foreach($purchase_invoice_lineitems as $purchase_invoice_lineitem){
      $purchase_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $purchase_invoice_lineitem->item_id);
      $purchase_invoice_lineitem->item_type_name = $this->crud->get_id_by_val('item_type', 'item_type_name', 'item_type_id', $purchase_invoice_lineitem->item_type_id);
      $purchase_invoice_lineitem->location_name = $this->crud->get_id_by_val('location', 'location_name', 'location_id', $purchase_invoice_lineitem->item_location_id);
      $purchase_invoice_lineitem->rack_name = $this->crud->get_id_by_val('rack', 'rack_name', 'rack_id', $purchase_invoice_lineitem->item_rack_id);
      $purchase_invoice_lineitem->price_type = $this->get_price_type_label($purchase_invoice_lineitem->price_type);

      $purchase_invoice_lineitem->purchase_order_no = '';
      $purchase_invoice_lineitem->purchase_challan_no = '';
      if(!empty($purchase_invoice_lineitem->picker_lineitem_id)){
      $result = $this->app_model->get_module_data_by_picker_id($purchase_invoice_lineitem->picker_lineitem_id);
      if(isset($result->purchase_order_no)){
      $purchase_invoice_lineitem->purchase_order_no = $result->purchase_order_no;
      }
      if(isset($result->purchase_challan_no)){
      $purchase_invoice_lineitem->purchase_challan_no = $result->purchase_challan_no;
      }
      }
      $purchase_invoice_lineitem->before_discount_price = $purchase_invoice_lineitem->item_qty * $purchase_invoice_lineitem->price;
      $purchase_invoice_lineitem->cgst_amt = $purchase_invoice_lineitem->cgst_amount;
      $purchase_invoice_lineitem->sgst_amt = $purchase_invoice_lineitem->sgst_amount;
      $purchase_invoice_lineitem->igst_amt = $purchase_invoice_lineitem->igst_amount;
      $discount_id = array();
      $discount_type = array();
      $discount = array();
      $lineitem_discounts = $this->crud->get_row_by_id('lineitem_discount', array('lineitem_id' => $purchase_invoice_lineitem->id));
      foreach($lineitem_discounts as $lineitem_discount){
      $discount_id[] = $lineitem_discount->discount_id;
      $discount_type[] = $lineitem_discount->discount_type;
      $discount[] = $lineitem_discount->discount;
      }
      $purchase_invoice_lineitem->discount_id = $discount_id;
      $purchase_invoice_lineitem->discount_type = $discount_type;
      $purchase_invoice_lineitem->discount = $discount;
      $lineitem_arr[] = $purchase_invoice_lineitem;
      }
      $data['lineitems'] = $lineitem_arr;

      } else {
      $data = array();
      }
      set_page('purchase/invoice/invoice_detail', $data);
      } */

    function material_in_list(){
        if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"view")) {
            $data = array();
            $data['invoice_type'] = 4;
            set_page('purchase/invoice/invoice_list', $data);
        } else {
            $this->session->set_flashdata('success', false);
            $this->session->set_flashdata('message', 'You have not permission to access this page.');
            redirect('/');
        }
    }

    function format_3_invoice_print($purchase_invoice_id, $is_multiple = '') {
        if (!empty($purchase_invoice_id) && isset($purchase_invoice_id)) {
            $result = $this->crud->get_data_row_by_id('purchase_invoice', 'purchase_invoice_id', $purchase_invoice_id);            
            $user_detail = $this->crud->get_data_row_by_id('user', 'user_id', $result->created_by);
            $account_detail = $this->crud->get_data_row_by_id('account', 'account_id', $result->account_id);
            $this->load->library('numbertowords');
            if ($result->amount_total < 0) {
                $amount_total_word = 'Minus ' . $this->numbertowords->convert_number(abs($result->amount_total));
            } else {
                $amount_total_word = $this->numbertowords->convert_number($result->amount_total);
            }
            $print_type = $result->print_type;
            $this->load->library('applib');
            $purchase_invoice_no = $this->applib->format_invoice_number($result->purchase_invoice_id, $result->purchase_invoice_date);
            $data = array(
                'purchase_invoice_id' => $result->purchase_invoice_id,
                'purchase_invoice_no' => $purchase_invoice_no,
                'purchase_invoice_date' => date('d-m-Y', strtotime($result->purchase_invoice_date)),
                'purchase_invoice_desc' => nl2br($result->purchase_invoice_desc),
                'qty_total' => $result->qty_total,
                'pure_amount_total' => $result->pure_amount_total,
                'discount_total' => $result->discount_total,
                'cgst_amount_total' => $result->cgst_amount_total,
                'sgst_amount_total' => $result->sgst_amount_total,
                'igst_amount_total' => $result->igst_amount_total,
                'amount_total' => $result->amount_total,
                'amount_total_word' => $amount_total_word,
            );
            $data['purchase_invoice_data'] = $result;
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
            $where = array('module' => '1', 'parent_id' => $purchase_invoice_id);
            $purchase_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
            
            $total_gst = 0;
            foreach ($purchase_invoice_lineitems as $purchase_invoice_lineitem) {
                $purchase_invoice_lineitem->item_name = $this->crud->get_id_by_val('item', 'item_name', 'item_id', $purchase_invoice_lineitem->item_id);
                $hsn_id = $this->crud->get_id_by_val('item', 'hsn_code', 'item_id', $purchase_invoice_lineitem->item_id);
                if (!empty($hsn_id)) {
                    $purchase_invoice_lineitem->hsn_code = $this->crud->get_id_by_val('hsn', 'hsn', 'hsn_id', $hsn_id);
                } else {
                    $purchase_invoice_lineitem->hsn_code = '';
                }
                
                $purchase_invoice_lineitem->pure_amount = $purchase_invoice_lineitem->pure_amount;
                $purchase_invoice_lineitem->cgst_amt = $purchase_invoice_lineitem->cgst_amount;
                $purchase_invoice_lineitem->sgst_amt = $purchase_invoice_lineitem->sgst_amount;
                $purchase_invoice_lineitem->igst_amt = $purchase_invoice_lineitem->igst_amount;
                $lineitem_arr[] = $purchase_invoice_lineitem;
                $amt =  $purchase_invoice_lineitem->price * $purchase_invoice_lineitem->item_qty;
                $gst_amount = $amt * $purchase_invoice_lineitem->gst / 100;
                $total_gst += $gst_amount;
            }

            
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

        $letterpad_print = $this->crud->get_id_by_val('user', 'is_letter_pad', 'user_id', $this->logged_in_id);
        $data['letterpad_print'] = $letterpad_print;
        $data['printtype'] = 0;
        $html = $this->load->view('purchase/invoice/invoice_print', $data, true);

        $pdfFilePath = "purchase_invoice_miracle_print.pdf";
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                5, // margin right
                5, // margin top
                15, // margin bottom
                5, // margin header
                5); // margin footer

       

        $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
        <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right; "></td></tr></table>'); 
        $this->m_pdf->pdf->WriteHTML($html);

        if ($print_type == 2) {
            $data['printtype'] = 1;
            $html = $this->load->view('purchase/invoice/invoice_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);

            $data['printtype'] = 2;
            $html = $this->load->view('purchase/invoice/invoice_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
           

            $this->m_pdf->pdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
                    <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                    <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                    <td width="33%" style="text-align: right; "></td></tr></table>'); // Note that the second parameter is optional : default = 'O' for ODD
            $this->m_pdf->pdf->WriteHTML($html);
        }
        if ($print_type == 1) {
            $data['printtype'] = 1;
            $html = $this->load->view('purchase/invoice/invoice_print', $data, true);
            $this->m_pdf->pdf->AddPage('', '', '', '', '', 5, // margin_left
                    5, // margin right
                    5, // margin top
                    15, // margin bottom
                    5, // margin header
                    5); // margin footer
            

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
