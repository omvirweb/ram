<?php
/*
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
$is_single_line_item = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_single_line_item'];
//$is_single_line_item = 0;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="treeview <?= ($segment1 == '') ? 'active' : '' ?>">
                <a href="<?= base_url(); ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php
            $isAdmin = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['userType'];
            $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
            $li_category = isset($this->session->userdata()['li_category']) ? $this->session->userdata()['li_category'] : '0';
            $li_sub_category = isset($this->session->userdata()['li_sub_category']) ? $this->session->userdata()['li_sub_category'] : '0';
            $li_unit = isset($this->session->userdata()['li_unit']) ? $this->session->userdata()['li_unit'] : '0';
            $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
            if($this->applib->have_access_role(MASTER_COMPANY_ID,"view") || $this->applib->have_access_role(MASTER_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_COMPANY_ID,"edit")) { ?>
                <li class="treeview <?= ($segment1 == 'user') ? 'active' : '' ?>">
                    <a href="#">
                        <i class="fa fa-user"></i> <span> Company</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if($this->applib->have_access_role(MASTER_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_COMPANY_ID,"edit")) { ?>
                        <li class="<?= ($segment1 == 'user' && $segment2 == 'user') ? 'active' : '' ?>">
                            <a href="<?php echo base_url() ?>user/user/"><i class="fa fa-circle-o"></i> Add Company</a>
                        </li>
                        <?php } ?>
                        <?php if($this->applib->have_access_role(MASTER_COMPANY_ID,"view")) { ?>
                        <li class="<?= ($segment1 == 'user' && $segment2 == 'user_list') ? 'active' : '' ?>">
                            <a href="<?php echo base_url() ?>user/user_list/"><i class="fa fa-circle-o"></i> Company List</a>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"view") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"edit")) { ?>
            <li class="treeview <?= ($segment1 == 'account') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-users"></i> <span> Account</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <?php if($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"add") || $this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"edit")) { ?>
                    <li class="<?= ($segment1 == 'account' && $segment2 == 'account') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>account/account/"><i class="fa fa-circle-o"></i> Add Account</a>
                    </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID,"view")){ ?>
                    <li class="<?= ($segment1 == 'account' && $segment2 == 'account_list') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>account/account_list/"><i class="fa fa-circle-o"></i> Account List</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MASTER_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'master') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-circle"></i> <span> Master</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
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
                    <?php if($this->applib->have_access_role(MASTER_USER_RIGHTS_ID,"view")) { ?>
                    <li class="<?= ($segment1 == 'master' && $segment2 == 'user_rights') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>master/user_rights/"><i class="fa fa-circle-o"></i> User Rights</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php /*<li class="treeview <?= ($segment1 == 'bank') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-bank"></i> <span> Bank</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'bank' && $segment2 == 'bank') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>bank/bank/"><i class="fa fa-circle-o"></i> Add Bank</a>
                    </li>
                    <li class="<?= ($segment1 == 'bank' && $segment2 == 'bank_list') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>bank/bank_list/"><i class="fa fa-circle-o"></i> Bank List</a>
                    </li>
                </ul>
            </li>*//* ?>
            <?php if($this->applib->have_access_role(MASTER_ITEM_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'item') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-square"></i> <span> Item</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?= ($segment1 == 'item' && $segment2 == 'item') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>item/item/"><i class="fa fa-circle-o"></i> Add Item</a>
                    </li>
                    <li class="<?= ($segment1 == 'item' && $segment2 == 'item_list') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>item/item_list/"><i class="fa fa-circle-o"></i> Item List</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_ORDER_PURCHASE_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'purchase') || ($segment2 == 'sales_purchase_transaction' && ($segment3 == 'purchase' || $segment3 == 'order')) ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> <span>Order / Purchase</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if($this->applib->have_access_role(MODULE_ORDER_ID,"add")) { ?>
                        <?php if($is_single_line_item == 1){?>
                            <li class="<?= ($segment3 == 'order') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/sales_purchase_transaction/order"><i class="fa fa-circle-o"></i> Add Purchase Invoice</a>
                            </li>
                        <?php } else { ?>
                            <li class="<?= ($segment2 == 'order') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>purchase/order/"><i class="fa fa-circle-o"></i> Add Order</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_ORDER_ID,"view")) { ?>
                    <li class="<?= ($segment2 == 'order_invoice_list') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>purchase/order_invoice_list/"><i class="fa fa-circle-o"></i> Order List</a>
                    </li>
                    <?php } ?>

                    <?php if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"add")) { ?>
                        <?php if($is_single_line_item == 1){?>
                            <li class="<?= ($segment3 == 'purchase') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/sales_purchase_transaction/purchase"><i class="fa fa-circle-o"></i> Add Purchase Invoice</a>
                            </li>
                        <?php } else { ?>
                            <li class="<?= ($segment2 == 'invoice') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>purchase/invoice/"><i class="fa fa-circle-o"></i> Add Purchase Invoice</a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"view")) { ?>
                    <li class="<?= ($segment2 == 'invoice_list') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>purchase/invoice_list/"><i class="fa fa-circle-o"></i> Purchase Invoice List</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php if($this->applib->have_access_role(MODULE_SALES_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'sales') || ($segment2 == 'sales_purchase_transaction' && $segment3 =='sales')? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-cube"></i> <span>Sales</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
                        <?php if($is_single_line_item == 1){?>
                            <li class="<?= ($segment3 == 'sales') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/sales_purchase_transaction/sales"><i class="fa fa-circle-o"></i> Add Invoice</a>
                            </li>
                        <?php } else { ?>
                            <li class="<?= ($segment2 == 'invoice') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>sales/invoice/"><i class="fa fa-circle-o"></i> Add Invoice</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) { ?>
                        <li class="<?= ($segment2 == 'invoice_list') ? 'active' : '' ?>">
                            <a href="<?php echo base_url() ?>sales/invoice_list/"><i class="fa fa-circle-o"></i> Invoice List</a>
                        </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"view")) { ?>
                    <?php if ($li_discount == 1) { ?>
                        <li class="treeview <?= ($segment1 == 'sales' && $segment2 == 'discount' || $segment2 == 'discount_list') ? 'active' : '' ?>">
                            <a href="#">
                                <i class="fa fa-cube"></i> <span>Discount</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"add")) { ?>
                                <li class="<?= ($segment2 == 'discount') ? 'active' : '' ?>">
                                    <a href="<?php echo base_url() ?>sales/discount/"><i class="fa fa-circle-o"></i> Add Discount</a>
                                </li>
                                <?php } ?>
                                <li class="<?= ($segment2 == 'discount_list') ? 'active' : '' ?>">
                                    <a href="<?php echo base_url() ?>sales/discount_list/"><i class="fa fa-circle-o"></i> Discount List</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'credit_note') || ($segment2 == 'sales_purchase_transaction' && $segment3 =='credit_note') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-cc-visa"></i> <span>Credit Note <br/> (Sales Return)</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"add")) { ?>
                        <?php if($is_single_line_item == 1){?>
                            <li class="<?= ($segment3 == 'credit_note') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/sales_purchase_transaction/credit_note"><i class="fa fa-circle-o"></i> Add Credit Note</a>
                            </li>
                        <?php } else { ?>
                            <li class="<?= ($segment2 == 'add') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>credit_note/add/"><i class="fa fa-circle-o"></i> Add Credit Note</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <li class="<?= ($segment2 == 'list_page') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>credit_note/list_page/"><i class="fa fa-circle-o"></i> Credit Note List</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'debit_note') || ($segment2 == 'sales_purchase_transaction' && $segment3 =='debit_note') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-credit-card"></i> <span>Debit Note <br/> (Purchase Return)</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"add")) { ?>
                        <?php if($is_single_line_item == 1){?>
                            <li class="<?= ($segment3 == 'debit_note') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/sales_purchase_transaction/debit_note"><i class="fa fa-circle-o"></i> Add Debit Note</a>
                            </li>
                        <?php } else { ?>
                            <li class="<?= ($segment2 == 'add') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>debit_note/add/"><i class="fa fa-circle-o"></i> Add Debit Note</a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <li class="<?= ($segment2 == 'list_page') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>debit_note/list_page/"><i class="fa fa-circle-o"></i> Debit Note List</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"view") || $this->applib->have_access_role(MODULE_RECEIPT_ID,"view") || $this->applib->have_access_role(MODULE_CONTRA_ID,"view") || $this->applib->have_access_role(MODULE_JOURNAL_ID,"view")) { ?>
            <li class="treeview <?= ($segment2 == 'payment' || $segment2 == 'payment_list') || ($segment2 == 'receipt' || $segment2 == 'receipt_list') || ($segment1 == 'contra') || ($segment1 == 'journal')? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-cc-visa"></i> <span>Transactions</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"view")) { ?>
                    <li class="treeview <?= ($segment2 == 'payment' || $segment2 == 'payment_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span> Payment</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) { ?>
                            <li class="<?= ($segment1 == 'transaction' && $segment2 == 'payment') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/payment/"><i class="fa fa-circle-o"></i> Add Payment</a>
                            </li>
                            <?php } ?>

                            <li class="<?= ($segment1 == 'transaction' && $segment2 == 'payment_list') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/payment_list/"><i class="fa fa-circle-o"></i> Payment List</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"view")) { ?>
                    <li class="treeview <?= ($segment2 == 'receipt' || $segment2 == 'receipt_list') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span> Receipt</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"add")) { ?>
                            <li class="<?= ($segment1 == 'transaction' && $segment2 == 'receipt') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/receipt/"><i class="fa fa-circle-o"></i> Add Receipt</a>
                            </li>
                            <?php } ?>
                            <li class="<?= ($segment1 == 'transaction' && $segment2 == 'receipt_list') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>transaction/receipt_list/"><i class="fa fa-circle-o"></i> Receipt List</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_CONTRA_ID,"view")) { ?>
                    <li class="treeview <?= ($segment1 == 'contra') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span> Contra</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if($this->applib->have_access_role(MODULE_CONTRA_ID,"add")) { ?>
                            <li class="<?= ($segment1 == 'contra' && $segment2 == 'contra') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>contra/contra/"><i class="fa fa-circle-o"></i> Add Contra</a>
                            </li>
                            <?php } ?>
                            
                            <li class="<?= ($segment1 == 'contra' && $segment2 == 'contra_list') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>contra/contra_list/"><i class="fa fa-circle-o"></i> Contra List</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"view")) { ?>
                    <li class="treeview <?= ($segment1 == 'journal') ? 'active' : '' ?>">
                        <a href="#">
                            <i class="fa fa-circle-o"></i> <span> Journal</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"add")) { ?>
                            <li class="<?= ($segment1 == 'journal' && $segment2 == 'journal') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>journal/journal/"><i class="fa fa-circle-o"></i> Add Journal</a>
                            </li>
                            <?php } ?>
                            <li class="<?= ($segment1 == 'journal' && $segment2 == 'journal_list') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>journal/journal_list/"><i class="fa fa-circle-o"></i> Journal List</a>
                            </li>
                            <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"add")) { ?>
                            <li class="<?= ($segment1 == 'journal' && $segment2 == 'journal_type2') ? 'active' : '' ?>">
                                <a href="<?php echo base_url() ?>journal/journal_type2/"><i class="fa fa-circle-o"></i> Journal Type 2</a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>

            <?php if($this->applib->have_access_role(MODULE_REPORT_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'report') ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-th-list"></i> <span>Report</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
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
                    <?php if($this->applib->have_access_role(MODULE_SALES_REGISTER_ID,"view")) { ?>
                    <li class="<?= ($segment2 == 'sales') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>report/sales/"><i class="fa fa-circle-o"></i> Sales Register</a>
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
                    <?php if($this->applib->have_access_role(MODULE_LEDGER_ID,"view")) { ?>
                    <li class="<?= ($segment2 == 'ledger') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>report/ledger/"><i class="fa fa-circle-o"></i> Ledger</a>
                    </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_SUMMARY_ID,"view")) { ?>
                    <li class="<?= ($segment2 == 'summary') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>report/summary/"><i class="fa fa-circle-o"></i> Summary</a>
                    </li>
                    <?php } ?>
                    <?php if($this->applib->have_access_role(MODULE_BALANCE_SHEET_ID,"view")) { ?>
                    <li class="<?= ($segment2 == 'balance_sheet') ? 'active' : '' ?>">
                        <a href="<?php echo base_url() ?>report/balance_sheet/"><i class="fa fa-circle-o"></i> Balance Sheet</a>
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
                </ul>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_GSTR1_EXCEL_EXPORT_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'gstr1_excel') ? 'active' : '' ?>">
                <a href="<?= base_url() ?>gstr1_excel/">
                    <i class="fa fa-file-excel-o"></i> <span>GSTR1 Excel Export</span>
                </a>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_GSTR2_EXCEL_EXPORT_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'gstr2_excel') ? 'active' : '' ?>">
                <a href="<?= base_url() ?>gstr2_excel/">
                    <i class="fa fa-file-excel-o"></i> <span>GSTR2 Excel Export</span>
                </a>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_GSTR_3B_EXCEL_EXPORT_ID,"view")) { ?>
            <li class="treeview <?= ($segment1 == 'gstr_3b_excel') ? 'active' : '' ?>">
                <a href="<?= base_url() ?>gstr_3b_excel/">
                    <i class="fa fa-file-excel-o"></i> <span>GSTR-3B Excel Export</span>
                </a>
            </li>
            <?php } ?>
            <?php if($this->applib->have_access_role(MODULE_BACKUP_DB_ID,"view")) { ?>
                <li class="treeview <?= ($segment1 == '') ? 'active' : '' ?>">
                    <a href="<?= base_url() ?>backup/">
                        <i class="fa fa-database"></i> <span>Backup DB</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
*/ ?>