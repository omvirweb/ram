<?php $this->load->view('success_false_notify');?>
<?php
if(isset($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']) && !empty($this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in'])){
    $logged_in_name = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['user_name'];
    if($this->session->userdata()['userType']) {
        $userType = $this->session->userdata()['userType'];
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?=isset($logged_in_name)?ucwords($logged_in_name):'Admin';?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"add")) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url() ?>sales/invoice/">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-cube"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sales Invoice <br/> (CTRL + F1)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <?php } ?>
            
            <?php if($this->applib->have_access_role(MODULE_CREDIT_NOTE_ID,"add")) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url() ?>credit_note/add/">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-cc-visa"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Sales Return <br/> (CTRL + F2)</span>
                            <!-- <span class="info-box-number">0</span> -->
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <?php } ?>

            <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"add")) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url() ?>transaction/receipt/">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Receipt <br/> (CTRL + F5)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <?php } ?>
            
            <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo base_url() ?>transaction/payment/">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-money"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Payment <br/> (CTRL + F6)</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
            <?php } ?>
            
        </div>
        <?php
        $isAdmin = $this->session->userdata()[PACKAGE_FOLDER_NAME.'is_logged_in']['userType'];
        if($isAdmin == 'Admin'){ ?>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-user-secret"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Need To Verify</span>
                        <span class="info-box-number"><?=isset($needto_varifi) ? $needto_varifi:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-anchor"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Verified Users</span>
                        <span class="info-box-number"><?=isset($varified) ? $varified:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Users</span>
                        <span class="info-box-number"><?=isset($total_user) ? $total_user:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>    
        <?php } ?>
        <?php if($isAdmin == 'Company'){ ?>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Purchase Invoices</span>
                        <span class="info-box-number"><?=isset($purchase_invoices) ? $purchase_invoices:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-cube"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sales Invoices</span>
                        <span class="info-box-number"><?=isset($sales_invoices) ? $sales_invoices:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-cc-visa"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Credit Notes</span>
                        <span class="info-box-number"><?=isset($credit_notes) ? $credit_notes:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-credit-card"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Debit Notes</span>
                        <span class="info-box-number"><?=isset($debit_notes) ? $debit_notes:'0'; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>    
        <?php } ?>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
