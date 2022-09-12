<?php
if(isset($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']) && !empty($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in'])){
    if($this->session->userdata()['userType']) {
        $userType = $this->session->userdata()['userType'];
    }
    if($userType == USER_TYPE_USER) {
        $logged_in_name = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in_user']['user_name'];
        $logged_in_email = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in_user']['user'];
        $logged_in_image = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in_user']['logo_image'];
    } else {
        $logged_in_name = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['user_name'];
        $logged_in_email = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['user'];
        $logged_in_image = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['logo_image'];    
    }
	
//        echo "<pre>"; print_r($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']); exit;
}
$currUrl = $this->uri->segment(1);
if($currUrl == ''){
	$currUrl = 'Dashboard';
}
if(isset($page_title)) {
    $currUrl = $page_title;
}
$package_name = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'package_name'));
$login_logo = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'login_logo'));

$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
$is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
$is_single_line_item = 1;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> <?=ucwords(str_replace("_", " ", $currUrl))?> | <?php echo $package_name; ?> </title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= base_url();?>assets/dist/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?= base_url();?>assets/dist/css/ionicons.min.css">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="<?= base_url();?>assets/plugins/iCheck/all.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css');?>">
		<!----------------Notify---------------->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/notify/jquery.growl.css');?>">
		<!----------------Notify---------------->
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="<?= base_url();?>assets/plugins/datepicker/datepicker3.css">
		<!-- daterange picker -->
		<link rel="stylesheet" href="<?= base_url();?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css">
		<!-- jQuery 2.2.3 -->
		<script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
		<!-------- /Parsleyjs --------->
		<link href="<?= base_url('assets/plugins/parsleyjs/src/parsley.css');?>" rel="stylesheet" type="text/css" />
		<!-- DataTables -->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables/media/css/jquery.dataTables.min.css');?>">
		<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables/extensions/Scroller/css/scroller.dataTables.min.css');?>">
		<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css');?>">
		<!-- select 2 -->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/s2/select2.css');?>">
		<script src="<?=base_url('assets/plugins/s2/select2.full.js');?>"></script>
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css');?>">
		<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css');?>">
		<link href="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.css');?>" rel="stylesheet" type="text/css">
		<link href="<?= base_url('assets/dist/css/custom.css?v=8');?>" rel="stylesheet" type="text/css" />
	</head>
        <style>
            
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
.navbar-header2{
    background-color:white;
}
        </style>
	<body class="hold-transition skin-blue-light layout-top-nav">
        <div id="ajax-loader" style="display: none;">
            <div style="width:100%; height:100%; position:fixed; opacity:0.2; filter:alpha(opacity=40); background:#000000;z-index:99999;">
            </div>
            <div style="float:left;width:100%; top:40%; text-align:center; position:fixed; padding:0px; z-index:99999;">
                <img src="<?php echo base_url();?>/assets/image/loader.gif" width="150" height="150"><br /> <span style="background-color: #545454; color: #ffffff; padding: 2px 30px;">Loading...</span>
            </div>
        </div>
		<div class="wrapper">
			<header class="main-header">
				<nav class="navbar navbar-static-top">
                    <div class="container" style="width: 100%;background-color:#2B3984;">
                    	<div class="navbar-header">
                            <a href="<?= base_url()?>" class="navbar-brand" style="padding:0px;">
			                    <span class="logo-lg"><b style=""><span style="color: #009ad9;">
                            </span></b></span>
							</a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                            	<?php
					            $isAdmin = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['userType'];
					            $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
					            $li_category = isset($this->session->userdata()['li_category']) ? $this->session->userdata()['li_category'] : '0';
					            $li_sub_category = isset($this->session->userdata()['li_sub_category']) ? $this->session->userdata()['li_sub_category'] : '0';
					            $li_unit = isset($this->session->userdata()['li_unit']) ? $this->session->userdata()['li_unit'] : '0';
					            $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
					            if(($this->applib->have_access_role(MASTER_COMPANY_ID,"view") || $this->applib->have_access_role(MASTER_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_COMPANY_ID,"edit")) || ($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"view") || $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"add")) ) { ?>
                            	<li tabindex="0" class="dropdown <?= ($segment1 == 'user') ? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Company <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                    	<?php if($this->applib->have_access_role(MASTER_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_COMPANY_ID,"edit")) { ?>
                                    	<li class="<?= ($segment1 == 'user' && $segment2 == 'user') ? 'active' : '' ?>">
                                    		<a href="<?php echo base_url() ?>user/user/" ><i class="fa fa-circle-o"></i> Add Company</a>
                                    	</li>
                                    	<?php } ?>
                    					<?php if($this->applib->have_access_role(MASTER_COMPANY_ID,"view")) { ?>
                                    	<li class="<?= ($segment1 == 'user' && $segment2 == 'user_list') ? 'active' : '' ?>">
                                    		<a href="<?php echo base_url() ?>user/user_list/"><i class="fa fa-circle-o"></i> Company List</a>
                                    	</li>
                                    	<?php } ?>
                    					<?php if($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"view") || $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"add")) { ?>
                                    	<li class="<?= ($segment1 == 'user' && $segment2 == 'documents') ? 'active' : '' ?>">
                                    		<a href="<?php echo base_url() ?>user/documents/"><i class="fa fa-circle-o"></i> Documents</a>
                                    	</li>
                                    	<?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if($this->applib->have_access_role(MASTER_ID,"view") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"edit") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"edit") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"view")) { ?>
                                <li tabindex="0" class="dropdown <?= ($segment1 == 'master') ? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php if($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"view") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"edit") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"add") ) { ?>
                                            <li class="">
                                                <a tabindex="-1" href="<?php echo base_url() ?>account/account_list/"><i class="fa fa-circle-o"></i> Account</a>
                                                <?php /*<ul class="dropdown-menu">
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>account/account/"><i class="fa fa-circle-o"></i> Add Account</a></li>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>account/account_list/"><i class="fa fa-circle-o"></i> Account List</a></li>
                                                </ul>*/?>
                                                
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MASTER_USER_ID,"view") || $this->applib->have_access_role(MASTER_USER_ID,"edit") || $this->applib->have_access_role(MASTER_USER_ID,"add") ) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>master/user_list/"><i class="fa fa-circle-o"></i> User</a>
                                                <ul class="dropdown-menu">
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>master/user/"><i class="fa fa-circle-o"></i> Add User</a></li>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>master/user_list/"><i class="fa fa-circle-o"></i> User List</a></li>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>master/user_user_rights/"><i class="fa fa-circle-o"></i> User Rights</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MASTER_ITEM_ID,"view") || $this->applib->have_access_role(MASTER_ITEM_ID,"edit") || $this->applib->have_access_role(MASTER_ITEM_ID,"add") ) { ?>
                                            <li class="">
                                                <a tabindex="-1" href="<?php echo base_url() ?>item/item_list/"><i class="fa fa-circle-o"></i> Item</a>
                                                <?php /*<ul class="dropdown-menu">
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>item/item/"><i class="fa fa-circle-o"></i> Add Item</a></li>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>item/item_list/"><i class="fa fa-circle-o"></i> Item List</a></li>
                                                </ul>*/?>
                                            </li>
                                        <?php } ?>
                                    	<?php if($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"view")) { ?>
					                    <?php if($li_item_group == 1) {?>
					                        <li class="<?= ($segment1 == 'master' && $segment2 == 'item_group') ? 'active' : '' ?>">
					                            <a href="<?php echo base_url() ?>master/item_group/"><i class="fa fa-circle-o"></i> Item Group</a>
					                        </li>
					                    <?php } ?>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_ITEM_TYPE_ID,"view")) { ?>
					                        <li class="<?= ($segment1 == 'master' && $segment2 == 'item_type') ? 'active' : '' ?>">
					                            <a href="<?php echo base_url() ?>master/item_type/"><i class="fa fa-circle-o"></i> Item Type</a>
					                        </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'invoice_type') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/invoice_type/"><i class="fa fa-circle-o"></i> Invoice Type</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_STATE_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'state') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/state/"><i class="fa fa-circle-o"></i> State</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_CITY_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'city') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/city/"><i class="fa fa-circle-o"></i> City</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'account_group') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/account_group/"><i class="fa fa-circle-o"></i> Account Group</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_UNIT_ID,"view")) { ?>
					                    <?php if ($li_unit == 1) { ?>
					                        <li class="<?= ($segment1 == 'master' && $segment2 == 'pack_unit') ? 'active' : '' ?>">
					                            <a href="<?php echo base_url() ?>master/pack_unit/"><i class="fa fa-circle-o"></i> Unit</a>
					                        </li>
					                    <?php } ?>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_IMPORT_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'import') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/import/"><i class="fa fa-circle-o"></i> Import</a>
					                    </li>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'export') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/export/"><i class="fa fa-circle-o"></i> Export</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_HSN_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'hsn') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/hsn/"><i class="fa fa-circle-o"></i> HSN</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_CATEGORY_ID,"view")) { ?>
					                    <?php if ($li_category == 1) { ?>
					                        <li class="<?= ($segment1 == 'master' && $segment2 == 'category') ? 'active' : '' ?>">
					                            <a href="<?php echo base_url() ?>master/category/"><i class="fa fa-circle-o"></i> Category</a>
					                        </li>
					                    <?php } ?>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"view")) { ?>
					                    <?php if ($li_sub_category == 1) { ?>
					                        <li class="<?= ($segment1 == 'master' && $segment2 == 'sub_category') ? 'active' : '' ?>">
					                            <a href="<?php echo base_url() ?>master/sub_category/"><i class="fa fa-circle-o"></i> Sub Category</a>
					                        </li>
					                    <?php } ?>
					                    <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/discount_list/"><i class="fa fa-circle-o"></i> Discount</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"add")) { ?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>sales/discount_new/"><i class="fa fa-circle-o"></i> Add Discount</a></li>
                                                        <?php } ?>
                                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>sales/discount_list/"><i class="fa fa-circle-o"></i> Discount List</a></li>
                                                                    </ul>
                                                                </li>
                                                            <?php } ?>
					                    <?php if($userType == 'Admin' && $this->applib->have_access_role(MASTER_USER_RIGHTS_ID,"view")) { ?>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'user_rights') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/user_rights/"><i class="fa fa-circle-o"></i> User Rights</a>
					                    </li>
					                    <?php } ?>
                                        <li class="<?= ($segment1 == 'master' && $segment2 == 'site') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/site/"><i class="fa fa-circle-o"></i> Site</a>
					                    </li>
					                    <li class="<?= ($segment1 == 'master' && $segment2 == 'pack_unit') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>master/pack_unit/"><i class="fa fa-circle-o"></i> Pack Unit</a>
					                    </li>
                                    </ul>
                                </li>	
                                <?php } ?>

                                <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"view") || $this->applib->have_access_role(MODULE_RECEIPT_ID,"view") || $this->applib->have_access_role(MODULE_CONTRA_ID,"view") || $this->applib->have_access_role(MODULE_JOURNAL_ID,"view")) { ?>
                                <li tabindex="0" class="dropdown <?= ($segment2 == 'payment' || $segment2 == 'payment_list') || ($segment2 == 'receipt' || $segment2 == 'receipt_list') || ($segment1 == 'contra') || ($segment1 == 'journal')? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Purchase <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>quotation/purchase_quotation_list/"><i class="fa fa-circle-o"></i> Quotation</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>quotation/purchase_quotation_add/"><i class="fa fa-circle-o"></i> Add Quotation</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>quotation/purchase_quotation_add/"><i class="fa fa-circle-o"></i> Add Quotation</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>quotation/purchase_quotation_list/"><i class="fa fa-circle-o"></i> Quotation List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_ORDER_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>purchase/order_invoice_list/"><i class="fa fa-circle-o"></i> Order</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_ORDER_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/order"><i class="fa fa-circle-o"></i> Add Order</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>purchase/order/"><i class="fa fa-circle-o"></i> Add Order</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>purchase/order_invoice_list/"><i class="fa fa-circle-o"></i> Order List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>purchase/invoice_list/"><i class="fa fa-circle-o"></i> Purchase Invoice</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/purchase"><i class="fa fa-circle-o"></i> Add Purchase Invoice</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>purchase/invoice/"><i class="fa fa-circle-o"></i> Add Purchase Invoice</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>purchase/invoice_list/"><i class="fa fa-circle-o"></i> Purchase Invoice List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php /*
                                        <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/discount_list/"><i class="fa fa-circle-o"></i> Discount</a>
                                                <ul class="dropdown-menu">
                                                <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"add")) { ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>sales/discount/"><i class="fa fa-circle-o"></i> Add Discount</a></li>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>sales/discount_list/"><i class="fa fa-circle-o"></i> Discount List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        */ ?>
                                        
                                        <?php if($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>debit_note/list_page/"><i class="fa fa-circle-o"></i> Debit Note</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/debit_note"><i class="fa fa-circle-o"></i> Add Debit Note</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>debit_note/add/"><i class="fa fa-circle-o"></i> Add Debit Note</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>debit_note/list_page/"><i class="fa fa-circle-o"></i> Debit Note List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>

                                        <li class="dropdown-submenu">
                                            <a tabindex="-1" href="<?php echo base_url() ?>purchase/material_in_list/"><i class="fa fa-circle-o"></i> Material In</a>
                                            <ul class="dropdown-menu">
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/material_in"><i class="fa fa-circle-o"></i> Add Material In</a></li>
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>purchase/material_in_list/"><i class="fa fa-circle-o"></i> Material In List</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>	
                                <?php } ?>


                                <li tabindex="0" class="dropdown <?= ($segment1 == 'gstr1_excel' || $segment1 == 'gstr2_excel' || $segment1 == 'gstr_3b_excel')? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sales <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>quotation/sales_quotation_list/"><i class="fa fa-circle-o"></i> Quotation</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>quotation/sales_quotation_add/"><i class="fa fa-circle-o"></i> Add Quotation</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>quotation/sales_quotation_add/"><i class="fa fa-circle-o"></i> Add Quotation</a></li>
                                                            <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>quotation/sales_quotation_list/"><i class="fa fa-circle-o"></i> Quotation List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) { ?>
                                        <li class="dropdown-submenu">
                                            <a tabindex="-1" href="<?php echo base_url() ?>quotation/sales_quotation_list/"><i class="fa fa-circle-o"></i> Sales Invoice From Quote</a>
                                            <ul class="dropdown-menu">
                                                <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) { ?>
                                                    <?php if($is_single_line_item == 1){?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>sales/sales_invoice_frmquot_add/"><i class="fa fa-circle-o"></i> Add</a></li>
                                                    <?php } else { ?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>sales/sales_invoice_frmquot_add/"><i class="fa fa-circle-o"></i> Add</a></li>
                                                        <?php } ?>
                                                <?php } ?>
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>sales/sales_invoice_frmquot_list/"><i class="fa fa-circle-o"></i>List</a></li>
                                            </ul>
                                        </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_ORDER_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/order_invoice_list/"><i class="fa fa-circle-o"></i> Order</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_ORDER_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/sales_order"><i class="fa fa-circle-o"></i> Add Order</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>purchase/sales_order/"><i class="fa fa-circle-o"></i> Add Order</a></li>
                                                        <?php } ?>
                                                        <?php } ?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>sales/order_invoice_list/"><i class="fa fa-circle-o"></i> Order List</a></li>
                                                    </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list"><i class="fa fa-circle-o"></i> Dispatch</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/dispatch"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice/"><i class="fa fa-circle-o"></i> Add Dispatch</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list"><i class="fa fa-circle-o"></i> Dispatch List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list"><i class="fa fa-circle-o"></i> Sales Invoice</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/sales"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice/"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list"><i class="fa fa-circle-o"></i> Sales Invoice List</a></li>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list/2"><i class="fa fa-circle-o"></i> Sales Invoice Type2</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/sales2"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice/"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list/2"><i class="fa fa-circle-o"></i> Sales Invoice2 List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list/4"><i class="fa fa-circle-o"></i> Sales Invoice Type3</a>
                                                <ul class="dropdown-menu">

                                                <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/sales4"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice/"><i class="fa fa-circle-o"></i> Add Invoice</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list/4"><i class="fa fa-circle-o"></i> Sales Invoice3 List</a></li>

                                                    
                                                </ul>
                                            </li>
                                        <?php } ?>
                                    <?php if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>credit_note/list_page/"><i class="fa fa-circle-o"></i> Credit Note</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"add")) { ?>
                                                        <?php if($is_single_line_item == 1){?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/credit_note"><i class="fa fa-circle-o"></i> Add Credit Note</a></li>
                                                        <?php } else { ?>
                                                            <li><a tabindex="-1" href="<?php echo base_url() ?>credit_note/add/"><i class="fa fa-circle-o"></i> Add Credit Note</a></li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>credit_note/list_page/"><i class="fa fa-circle-o"></i> Credit Note List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>

                                
                                <li tabindex="0" class="dropdown <?= ($segment1 == 'gstr1_excel' || $segment1 == 'gstr2_excel' || $segment1 == 'gstr_3b_excel')? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">₹ Amount <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>transaction/payment_list"><i class="fa fa-circle-o"></i> Payment</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) { ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/payment/"><i class="fa fa-circle-o"></i> Add Payment</a></li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) { ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/gst_payment/"><i class="fa fa-circle-o"></i> GST Payment</a></li>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/payment_list"><i class="fa fa-circle-o"></i> Payment List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>transaction/receipt_list"><i class="fa fa-circle-o"></i> Receipt</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"add")) { ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/receipt/"><i class="fa fa-circle-o"></i> Add Receipt</a></li>
                                                    <?php } ?>
                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/receipt_list/"><i class="fa fa-circle-o"></i> Receipt List</a></li>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                        <?php if($this->applib->have_access_role(MODULE_CONTRA_ID,"view")) { ?>
                                            <li class="dropdown-submenu">
                                                <a tabindex="-1" href="<?php echo base_url() ?>contra/contra_list"><i class="fa fa-circle-o"></i> Contra</a>
                                                <ul class="dropdown-menu">
                                                    <?php if($this->applib->have_access_role(MODULE_CONTRA_ID,"add")) { ?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>contra/contra/"><i class="fa fa-circle-o"></i> Add Contra</a></li>
                                                        <?php } ?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>contra/contra_list/"><i class="fa fa-circle-o"></i> Contra List</a></li>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"view")) { ?>
                                                    <li class="dropdown-submenu">
                                                        <a tabindex="-1" href="<?php echo base_url() ?>journal/journal_list/"><i class="fa fa-circle-o"></i> Journal</a>
                                                        <ul class="dropdown-menu">
                                                            <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"add")) { ?>
                                                                <li><a tabindex="-1" href="<?php echo base_url() ?>journal/journal"><i class="fa fa-circle-o"></i> Add Journal</a></li>
                                                                <?php } ?>
                                                                <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"journal type 2")) { ?>
                                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>journal/journal_type2"><i class="fa fa-circle-o"></i> Journal Type 2</a></li>
                                                                    <?php } ?>
                                                                    <li><a tabindex="-1" href="<?php echo base_url() ?>journal/journal_list/"><i class="fa fa-circle-o"></i> Journal List</a></li>
                                                                </ul>
                                                            </li>
                                                            <?php } ?>
                                                            <li class="dropdown">
                                                                <a tabindex="-1" href="<?php echo base_url() ?>transaction/day_book/"><i class="fa fa-circle-o"></i> Day Book</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                <?php if($this->applib->have_access_role(MODULE_REPORT_ID,"view")) { ?>
                                <li tabindex="0" class="dropdown <?= ($segment1 == 'report')? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-submenu">
                                            <a tabindex="-1" href=""><i class="fa fa-circle-o"></i> Register</a>
                                                <ul class="dropdown-menu">
                                                    <li class="dropdown-submenu">
                                                        <a tabindex="-1" href=""><i class="fa fa-circle-o"></i> Sales</a>
                                                        <ul class="dropdown-menu">
                                                            <?php if($this->applib->have_access_role(MODULE_SALES_REGISTER_ID,"view")) { ?>
					                    <li class="<?= ($segment2 == 'sales') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>report/sales/"><i class="fa fa-circle-o"></i> Sales Register</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MODULE_SALES_BILL_REGISTER_ID,"view")) { ?>
					                    <li class="<?= ($segment2 == 'sales_bill') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>report/sales_bill/"><i class="fa fa-circle-o"></i> Sales Bill Register</a>
					                    </li>
					                    <?php } ?>
                                                        </ul>
                                                    </li>
                                                    <?php if($this->applib->have_access_role(MODULE_STOCK_REGISTER_ID,"view")) { ?>
                                                        <li class="<?= ($segment2 == 'stock') ? 'active' : '' ?>">
                                                            <a href="<?php echo base_url() ?>report/stock/"><i class="fa fa-circle-o"></i> Stock Register</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MODULE_PURCHASE_REGISTER_ID,"view")) { ?>
                                                        <li class="<?= ($segment2 == 'purchase') ? 'active' : '' ?>">
                                                            <a href="<?php echo base_url() ?>report/purchase/"><i class="fa fa-circle-o"></i> Purchase Register</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MODULE_CREDIT_NOTE_REGISTER_ID,"view")) { ?>
                                                        <li class="<?= ($segment2 == 'credit_note') ? 'active' : '' ?>">
                                                            <a href="<?php echo base_url() ?>report/credit_note/"><i class="fa fa-circle-o"></i> Credit Note Register</a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MODULE_DEBIT_NOTE_REGISTER_ID,"view")) { ?>
                                                        <li class="<?= ($segment2 == 'debit_note') ? 'active' : '' ?>">
                                                            <a href="<?php echo base_url() ?>report/debit_note/"><i class="fa fa-circle-o"></i> Debit Note Register</a>
                                                        </li>
                                                        <?php } ?>
                                                </ul>
                                            </li>
					                    
                                            <?php if($this->applib->have_access_role(MODULE_LEDGER_ID,"view")) { ?>
                                            <li class="<?= ($segment2 == 'ledger') ? 'active' : '' ?>">
                                                <a href="<?php echo base_url() ?>report/ledger/"><i class="fa fa-circle-o"></i> Ledger</a>
                                            </li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(MODULE_SUMMARY_ID,"view")) { ?>
                                            <li class="<?= ($segment2 == 'summary_billwise') ? 'active' : '' ?> dropdown-submenu">
                                                <a href="<?php echo base_url() ?>report/summary_billwise"><i class="fa fa-circle-o"></i> Outstanding</a>

                                            <ul class="dropdown-menu">
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>report/summary_billwise/receivable"><i class="fa fa-circle-o"></i> Receivable</a></li>
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>report/summary_billwise/payable"><i class="fa fa-circle-o"></i> Payable</a></li>
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>report/summary_billwise/billwise_receivable"><i class="fa fa-circle-o"></i> Bill Wise Receivable</a></li>
                                                <li><a tabindex="-1" href="<?php echo base_url() ?>report/summary_billwise/billwise_payable"><i class="fa fa-circle-o"></i> Bill Wise Payable</a></li>
                                            </ul>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MODULE_BALANCE_SHEET_ID,"view")) { ?>
					                    <li class="<?= ($segment2 == 'balance_sheet') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>report/balance_sheet/"><i class="fa fa-circle-o"></i> Balance Sheet</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MODULE_BALANCE_SHEET_ID,"view")) { ?>
					                    <li class="<?= ($segment2 == 'balance_sheet_new') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>report/balance_sheet_new/"><i class="fa fa-circle-o"></i> Balance Sheet 2</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MODULE_PROFIT_LOSS_ID,"view")) { ?>
					                    <li class="<?= ($segment2 == 'profit_loss') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>report/profit_loss/"><i class="fa fa-circle-o"></i> Profit Loss</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MODULE_TRIAL_BALANCE_ID,"view")) { ?>
					                    <li class="<?= ($segment2 == 'trial_balance') ? 'active' : '' ?>">
					                        <a href="<?php echo base_url() ?>report/trial_balance/"><i class="fa fa-circle-o"></i> Trial Balance</a>
					                    </li>
					                    <?php } ?>
					                    <?php if($this->applib->have_access_role(MODULE_USER_LOG_ID,"view")) { ?>
					                        <li class="<?= ($segment2 == 'login_report') ? 'active' : '' ?>">
					                            <a href="<?php echo base_url() ?>report/login_report/"><i class="fa fa-circle-o"></i> User Log Report</a>
					                        </li>
					                    <?php } ?>
					                    <li class="<?= ($segment2 == 'stock_status_report') ? 'active' : '' ?>">
                                            <a href="<?php echo base_url() ?>report/stock_status_report/"><i class="fa fa-circle-o"></i> Stock Status Report </a>
                                        </li>
					                    <li class="<?= ($segment2 == 'pending_bills_report') ? 'active' : '' ?>">
                                            <a href="<?php echo base_url() ?>report/pending_bills_report/"><i class="fa fa-circle-o"></i> Pending Bills Report </a>
                                        </li>
                                        <li class="<?= ($segment2 == 'site_report') ? 'active' : '' ?>">
                                            <a href="<?php echo base_url() ?>report/site_report/"><i class="fa fa-circle-o"></i> Site Report </a>
                                        </li>
                                        <li class="<?= ($segment2 == 'site_wise_expenses_summary_ac') ? 'active' : '' ?>">
                                            <a href="<?php echo base_url() ?>report/site_wise_expenses_summary_ac/"><i class="fa fa-circle-o"></i>Site Wise Expenses Summary AC </a>
                                        </li>
                                        <li class="<?= ($segment2 == 'site_wise_expenses_summary') ? 'active' : '' ?>">
                                            <a href="<?php echo base_url() ?>report/site_wise_expenses_summary/"><i class="fa fa-circle-o"></i>Site Wise Expenses Summary </a>
                                        </li>
                                    </ul>
                                </li>	
                                <?php } ?>

                                <?php if($this->applib->have_access_role(MODULE_GSTR1_EXCEL_EXPORT_ID,"view") || $this->applib->have_access_role(MODULE_GSTR2_EXCEL_EXPORT_ID,"view") || $this->applib->have_access_role(MODULE_GSTR_3B_EXCEL_EXPORT_ID,"view")) { ?>
                                <li tabindex="0" class="dropdown <?= ($segment1 == 'gstr1_excel' || $segment1 == 'gstr2_excel' || $segment1 == 'gstr_3b_excel')? 'active' : '' ?>">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">GST <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <?php if($this->applib->have_access_role(MODULE_GSTR1_EXCEL_EXPORT_ID,"view")) { ?>
                                            <li class="<?= ($segment1 == 'gstr1_excel') ? 'active' : '' ?>">
                                                <a href="<?= base_url() ?>gstr1_excel/"><i class="fa fa-circle-o"></i>  GSTR1 Excel Export</a>
                                            </li>
                                            <?php } ?>
                                            <?php if($this->applib->have_access_role(MODULE_GSTR2_EXCEL_EXPORT_ID,"view")) { ?>
                                                <li class="<?= ($segment1 == 'gstr2_excel') ? 'active' : '' ?>">
                                                    <a href="<?= base_url() ?>gstr2_excel/"><i class="fa fa-circle-o"></i>  GSTR2 Excel Export</a>
                                                </li>
                                                <?php } ?>
                                                <?php if($this->applib->have_access_role(MODULE_GSTR_3B_EXCEL_EXPORT_ID,"view")) { ?>
                                                    <li class="<?= ($segment1 == 'gstr_3b_excel') ? 'active' : '' ?>">
                                                        <a href="<?= base_url() ?>gstr_3b_excel/"><i class="fa fa-circle-o"></i>  GSTR-3B Excel Export</a>
                                                    </li>
                                                    <?php } ?>
                                                    <?php if($this->applib->have_access_role(MODULE_STOCK_STATUS_CHANGE_ID,"view")) { ?>
                                                        <li class="<?= ($segment1 == 'master' && $segment2 == 'stock_status_change') ? 'active' : '' ?>">
                                                            <a href="<?php echo base_url() ?>master/stock_status_change/"><i class="fa fa-circle-o"></i> Stock Status Change</a>
                                                        </li>
                                                        <?php } ?>
                                                        <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
                                                            <?php if($is_single_line_item == 1){?>
                                                                <li><a tabindex="-1" href="<?php echo base_url() ?>transaction/sales_purchase_transaction/sales3"><i class="fa fa-circle-o"></i>Add Invoice 3 Old</a></li>
                                                            <?php } else { ?>
                                                                <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice/"><i class="fa fa-circle-o"></i>Add Invoice 3 Old</a></li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <li><a tabindex="-1" href="<?php echo base_url() ?>sales/invoice_list/3"><i class="fa fa-circle-o"></i>Sales Invoice 3 List Old</a></li>
                                    </ul>
                                </li>
                                <?php } ?>

					            <?php if($this->applib->have_access_role(MODULE_BACKUP_DB_ID,"view")) { ?>
				                <li tabindex="0" class="dropdown <?= ($segment1 == 'backup') ? 'active' : '' ?>">
				                    <a href="<?= base_url() ?>backup/">
				                        <span>Backup DB</span>
				                    </a>
				                </li>
					            <?php } ?>
                            </ul>
                        </div>
                        <div class="navbar-custom-menu">
                        	<ul class="nav navbar-nav">
                        		<!-- <?php
                                    // if($userType == 'Admin') {
                                        ?> -->
                                    <li class="dropdown staff-menu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-users"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <!-- User image -->
                                            <li class="">
                                                <form>
                                                    <div class="form-group">
                                                        <?php $lead_owner = $this->crud->get_select_data_where('user',array('userType !=' => USER_TYPE_USER)); ?>
                                                        <div class="col-sm-12">
                                                            <select name="staff_session_id" id="staff_session_id" class="form-control input-sm select2">
                                                                <option value="">--Select--</option>
                                                                <?php foreach($lead_owner as $lo): ?>
                                                                <option value="<?= $lo->user_id; ?>"<?php if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'] == $lo->user_id){echo 'selected';}?>><?= $lo->user_name; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
								<!-- <?php
                                    // }
                                    ?> -->

								<!-- User Account: style can be found in dropdown.less -->
								<li class="dropdown user user-menu">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<!--<img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg');?>" class="user-image" alt="User Image">-->
										<img src="<?=isset($logged_in_image) && !empty($logged_in_image) ? base_url() .'assets/uploads/logo_image/'.$logged_in_image : base_url() . 'assets/dist/img/default-user.png';?>" class="user-image" alt="User Image">
										<span class="hidden-xs"><?=isset($logged_in_name)?ucwords($logged_in_name):'Admin';?></span>
									</a>
									<ul class="dropdown-menu">
										<!-- User image -->
										<li class="user-header">
											<img src="<?=isset($logged_in_image) && !empty($logged_in_image) ? BASE_URL.'assets/uploads/logo_image/'.$logged_in_image : base_url() . 'assets/dist/img/default-user.png';?>" class="img-circle" alt="User Image">
											<p>
												<?=isset($logged_in_name)?ucwords($logged_in_name):'Admin';?>
												<br/>
												<?=isset($logged_in_email)?$logged_in_email:'';?>
											</p>
										</li>
										<!-- Menu Footer-->
										<li class="user-footer">
											<div class="pull-left">
                                                <?php
                                                if($userType == USER_TYPE_USER) {
                                                    ?>
                                                    <a href="<?= base_url()?>master/user/profile" class="btn btn-default btn-flat">Profile</a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="<?= base_url()?>user/user/1" class="btn btn-default btn-flat">Profile</a>
                                                    <?php
                                                }
                                                ?>
											</div>
											<div class="pull-right">
												<a href="<?= base_url()?>auth/logout" class="btn btn-default btn-flat">Sign out</a>
											</div>
										</li>
									</ul>
								</li>
								<!-- Control Sidebar Toggle Button -->
                        	</ul>
                        </div>
                    </div>
                </nav>
			</header>
			<script>
				$(document).ready(function(){
					$(".select2").select2({
						width:"100%",
						placeholder: " --Select-- ",
						allowClear: true,
					});
					$(document).on('change','#staff_session_id',function(){
						$.ajax({
							type: "POST",
							url: '<?=base_url();?>auth/change_seesion_staff',
							data: { user_id: $(this).val() },
							dataType: 'json',
							success: function(data){
								if(data.success) {
									location.reload();
								} else {
									alert('Something Went Wrong Please Try Again');
								}
							},
						});
					});
				});
			</script>
