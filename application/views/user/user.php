<?php $this->load->view('success_false_notify'); ?>
<style>
    .checkbox_ch{
        height: 20px;
        width: 20px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Company
            <?php if (isset($profile)) { ?>
                <a href="<?= base_url('auth/profile'); ?>" class="btn btn-primary pull-right" target="_blank">Change Password</a>
            <?php } else { ?>
                <?php if($this->applib->have_access_role(MASTER_COMPANY_ID, "view")){ ?>
                    <a href="<?= base_url('user/user_list'); ?>" class="btn btn-primary pull-right">Company List</a>
                <?php } ?>
            <?php } ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_user" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
                    <input type="hidden" name="is_company" value="1">
                    <?php if (isset($edit_user_id) && !empty($edit_user_id)) { ?>
                        <input type="hidden" id="user_id" name="user_id" value="<?= $edit_user_id; ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_name" class="control-label">Name<span class="required-sign">*</span></label>
                                        <input type="text" name="user_name" class="form-control" id="user_name" value="<?= isset($edit_user_name) ? $edit_user_name : '' ?>" data-index="1" placeholder="" required autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone" class="control-label"> Phone</label>
                                        <input type="text" name="phone" class="form-control num_only" id="phone" value="<?= isset($edit_phone) ? $edit_phone : '' ?>" data-index="2" placeholder="" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="logo_image">Logo Image</label>
                                                <input type="file" name="logo_image" id="logo_image" data-index="3">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if (!empty($edit_logo_image)) { ?>
                                                <img src="<?php echo base_url('assets/uploads/logo_image/' . $edit_logo_image); ?>" width="100px" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_ids" class="control-label">Company Email</label>
                                        <textarea name="email_ids" class="form-control" id="email_ids" data-index="4" placeholder=""><?= isset($edit_email_ids) ? $edit_email_ids : '' ?></textarea>
                                        <small class="">Add multiple email by comma separated.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address" class="control-label">Address Street</label>
                                        <textarea name="address" class="form-control" id="address" data-index="5" placeholder=""><?= isset($edit_address) ? $edit_address : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="city" class="control-label">City</label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/city')?>"><i class="fa fa-plus"></i> Add City</a>
                                        <select name="city" id="city" class="select2" data-index="8" ></select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group"><br>
                                        <label><input type="checkbox" name="is_letter_pad" class="checkbox_ch" data-index="6" <?php echo (isset($edit_is_letter_pad) ? ($edit_is_letter_pad == 1 ? 'checked' : '') : ''); ?>>&nbsp;&nbsp;&nbsp;Letter Pad Print</label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state" class="control-label">State</label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/state')?>"><i class="fa fa-plus"></i> Add State</a>
                                        <select name="state" id="state" class="" data-index="7" ></select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="postal_code" class="control-label">Postal Code</label>
                                        <input type="number" name="postal_code" class="form-control" id="igst_per" value="<?= isset($edit_postal_code) ? $edit_postal_code : '' ?>" data-index="9" placeholder="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="aadhaar" class="control-label">Aadhaar</label>
                                        <input type="text" name="aadhaar" class="form-control" id="aadhaar" value="<?= isset($edit_aadhaar) ? $edit_aadhaar : '' ?>" data-index="10" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pan" class="control-label">Pan</label>
                                        <input type="text" name="pan" class="form-control" id="pan" value="<?= isset($edit_pan) ? $edit_pan : '' ?>" data-index="11" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="invoice_no_start_from" class="control-label">Invoice Start From</label>
                                        <input type="text" name="invoice_no_start_from" class="form-control" id="invoice_no_start_from" value="<?= isset($edit_invoice_no_start_from) ? $edit_invoice_no_start_from : '' ?>" data-index="12" placeholder="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contect_person_name">Contact Person Name</label>
                                        <input type="text" name="contect_person_name" class="form-control" id="contect_person_name" value="<?= isset($edit_contect_person_name) ? $edit_contect_person_name : '' ?>" data-index="13" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="gst_no" class="control-label">GSTIN</label>
                                        <input type="text" name="gst_no" class="form-control" id="gst_no" maxlength="15" value="<?= isset($edit_gst_no) ? $edit_gst_no : '' ?>" data-index="14" placeholder="" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="userType" class="control-label">User Type</label>
                                        <select name="userType" id="userType" class="selectSearch" data-index="15" >
                                            <?php
                                            if (isset($edit_userType)) {
                                                if ($edit_userType == 'Company') {
                                                    $Company = '1';
                                                    $Admin = null;
                                                } elseif ($edit_userType == 'Admin') {
                                                    $Admin = '1';
                                                    $Company = null;
                                                }
                                            }
                                            ?>
                                            <option value="Company" <?= isset($Company) ? 'selected' : '' ?>>Company</option>
                                            <option value="Admin" <?= isset($Admin) ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><h4><b>Login Details</b></h4><hr style="margin-top: 0px;"></div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user" class="control-label">Email / Phone / User Name</label>
                                        <input type="text" name="user" class="form-control" id="user" value="<?= isset($edit_user) ? $edit_user : '' ?>" data-index="16" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password" class="control-label">Password</label>
                                        <input type="password" name="" class="form-control"  id="cpassword" value="" data-index="17" placeholder="" autocomplete="new-password"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpassword" class="control-label">Confirm Password</label>
                                        <input type="password" name="password" class="form-control" data-parsley-equalto="#cpassword" id="password" value="" data-index="18" placeholder="" autocomplete="new-password"/>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><h4><b>Bank Details</b></h4><hr style="margin-top: 0px;"></div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_name" class="control-label">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?= isset($edit_bank_name) ? $edit_bank_name : ''; ?>" data-index="19">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_acc_name" class="control-label">Bank Account Name</label>
                                        <input type="text" name="bank_acc_name" id="bank_acc_name" class="form-control" value="<?= isset($edit_bank_acc_name) ? $edit_bank_acc_name : ''; ?>" data-index="20">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_branch" class="control-label">Bank Branch</label>
                                        <input type="text" name="bank_branch" id="bank_branch" class="form-control" value="<?= isset($edit_bank_branch) ? $edit_bank_branch : ''; ?>" data-index="21">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_city" class="control-label">Bank City</label>
                                        <input type="text" name="bank_city" id="bank_city" class="form-control" value="<?= isset($edit_bank_city) ? $edit_bank_city : ''; ?>" data-index="22">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_ac_no" class="control-label">Bank Account No.</label>
                                        <input type="text" name="bank_ac_no" id="bank_ac_no" class="form-control" value="<?= isset($edit_bank_ac_no) ? $edit_bank_ac_no : ''; ?>" data-index="23">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rtgs_ifsc_code" class="control-label">RTGS / IFSC Code</label>
                                        <input type="text" name="rtgs_ifsc_code" id="rtgs_ifsc_code" class="form-control" value="<?= isset($edit_rtgs_ifsc_code) ? $edit_rtgs_ifsc_code : ''; ?>" data-index="24">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><h4><b>Item Setting</b></h4><hr style="margin-top: 0px;"></div>
                                <div class="clearfix"></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchase_rate" class="control-label">Purchase Rate</label>
                                        <select class="select2" id="purchase_rate" name="purchase_rate" data-index="25">
                                            <option value="1" <?php echo (isset($edit_purchase_rate) && $edit_purchase_rate == 1) ? 'selected' : 'selected' ?>>Including Tax</option>
                                            <option value="2" <?php echo (isset($edit_purchase_rate) && $edit_purchase_rate == 2) ? 'selected' : '' ?>>Excluding Tax</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sales_rate" class="control-label">Sales Rate</label>
                                        <select class="select2" id="sales_rate" name="sales_rate" data-index="26">
                                            <option value="1" <?php echo (isset($edit_sales_rate) && $edit_sales_rate == 1) ? 'selected' : 'selected' ?>>Including Tax</option>
                                            <option value="2" <?php echo (isset($edit_sales_rate) && $edit_sales_rate == 2) ? 'selected' : '' ?>>Excluding Tax</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="discount_on" class="control-label">Discount On</label>
                                        <select name="discount_on" id="discount_on" class="select2" data-index="27">
                                            <option value="1" <?php echo (isset($edit_discount_on) && $edit_discount_on == 1) ? 'selected' : '' ?>>List Price</option>
                                            <option value="2" <?php echo isset($edit_discount_on) ? ($edit_discount_on == 2) ? 'selected' : '' : 'selected' ?>>MRP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <br/>
                                        <label for="is_single_line_item" class="control-label">
                                            <input type="checkbox" name="is_single_line_item" id="is_single_line_item" value="1" class="checkbox_ch" <?php echo ((isset($edit_is_single_line_item) && $edit_is_single_line_item == 1) ? 'checked' : '') ?>> Is Single Line Item?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <br/>
                                        <label for="is_view_item_history" class="control-label">
                                            <input type="checkbox" name="is_view_item_history" id="is_view_item_history" value="1" class="checkbox_ch" <?php echo ((isset($edit_is_view_item_history) && $edit_is_view_item_history == 1) ? 'checked' : '') ?>> Is View Item History?
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><h4><b>Invoice Setting</b></h4><hr style="margin-top: 0px;"></div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="invoice_no_digit" class="control-label">Invoice No. Digit</label>
                                            <input type="text" name="prefix[invoice_no_digit]" class="form-control" value="<?php echo isset($invoice_no_digit) ? $invoice_no_digit : ''; ?>" data-index="28">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><br />
                                                <label><input type="checkbox" name="prefix[year_post_fix]" value="year_post_fix" class="checkbox_ch" data-index="29" <?php echo isset($year_post_fix) && $year_post_fix == 1 ? 'checked' : '' ?>>&nbsp;&nbsp;&nbsp;Year Post Fix&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <div class="form-group"><br />
                                                <label><input type="checkbox" name="is_bill_wise" class="checkbox_ch" data-index="30" <?php echo (isset($edit_is_bill_wise) ? ($edit_is_bill_wise == 1 ? 'checked' : '') : ''); ?>>&nbsp;&nbsp;&nbsp;Balance Bill Wise</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sales_invoice_notes" class="control-label">Sales Invoice Notes</label>
                                                <textarea name="sales_invoice_notes" id="sales_invoice_notes" class="form-control" data-index="31" style="height: 100px">
                                                    <?= isset($edit_sales_invoice_notes) ? $edit_sales_invoice_notes : ''; ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="clearfix"></div>
                                        <label class="control-label">Company Prefix Setting</label><br>
                                        <input type="hidden" name="line_items_index" id="line_items_index">
                                        <input type="hidden" name="line_items_data[id]" id="id">
                                        <input type="hidden" id="index" value="">
                                        <div class="col-md-6">
                                            <label for="line_item_fields" class="control-label">Invoice Prefix</label>
                                            <input type="textbox" name="line_items_data[company_invoice_prefix]" id="company_invoice_prefix" class="form-control" value="" data-index="32">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="line_item_fields" class="control-label">Is Default</label><br>
                                            <input type="checkbox" name="line_items_data[default_prefix]" id="default_prefix" class="checkbox_ch" value="" data-index="33">
                                        </div>
                                        <div class="col-md-2 pull-left"><br>
                                            <a href="javascript:void(0);" class="btn btn-primary" id="add_lineitem">Add</a>
                                        </div>
                                        <div class="clearfix"></div><br />
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Default Prefix</th>
                                                        <th>Prefix</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lineitem_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="main_fields" class="control-label">Main Fields</label><br>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="shipment_invoice_no" id="shipment_invoice_no" class="checkbox_ch" data-index="34" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('shipment_invoice_no', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Shipment Invoice No.</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="shipment_invoice_date" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('shipment_invoice_date', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Shipment Invoice Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="sbill_no" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('sbill_no', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;S/Bill No.</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="sbill_date" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('sbill_date', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;S/Bill Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="origin_port" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('origin_port', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Origin Port</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="port_of_discharge" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('port_of_discharge', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Port of Discharge</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="container_size" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('container_size', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Container Size</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="container_bill_no" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('container_bill_no', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Container Bill No</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="container_date" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('container_date', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Container Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="container_no" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('container_no', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Container No.</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="vessel_name_voy" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('vessel_name_voy', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Vessel Name / Voy</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="print_date" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('print_date', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Print Date</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="your_invoice_no" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('your_invoice_no', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Your Invoice No.</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="main_fields[]" value="display_dollar_sign" class="checkbox_ch" <?php echo isset($invoice_main_fields) && !empty($invoice_main_fields) && in_array('display_dollar_sign', $invoice_main_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Display Dollar Sign</label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br/>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="line_item_fields" class="control-label">Line Item Fields</label><br>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="item_group" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)) ? 'checked' : '' : ''; ?>>&nbsp;&nbsp;&nbsp;Item Group</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="category" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) && !empty($invoice_line_item_fields) && in_array('category', $invoice_line_item_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Category</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="sub_category" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) && !empty($invoice_line_item_fields) && in_array('sub_category', $invoice_line_item_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Sub Category</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="short_name" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) && !empty($invoice_line_item_fields) && in_array('short_name', $invoice_line_item_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Short Name</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="unit" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) && !empty($invoice_line_item_fields) && in_array('unit', $invoice_line_item_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Unit</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="discount" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('discount', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;Discount</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="basic_amount" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('basic_amount', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;Basic Amount</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="cgst_per" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('cgst_per', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;CGST %</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="cgst_amt" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('cgst_amt', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;CGST Amt (Rs.)</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="sgst_per" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('sgst_per', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;SGST %</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="sgst_amt" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('sgst_amt', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;SGST Amt (Rs.)</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="igst_per" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('igst_per', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;IGST %</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="igst_amt" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('igst_amt', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;IGST Amt (Rs.)</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="other_charges" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) && !empty($invoice_line_item_fields) && in_array('other_charges', $invoice_line_item_fields) ? 'checked' : ''; ?>>&nbsp;&nbsp;&nbsp;Other Charges</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="amount" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('amount', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;Amount</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="note" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('note', $invoice_line_item_fields)) ? 'checked' : '' : 'checked'; ?>>&nbsp;&nbsp;&nbsp;Note</label>
                                        </div>
                                        <div class="col-md-3">
                                            <label><input type="checkbox" name="line_item_fields[]" value="separate" class="checkbox_ch" <?php echo isset($invoice_line_item_fields) ? (!empty($invoice_line_item_fields) && in_array('separate', $invoice_line_item_fields)) ? 'checked' : '' : ''; ?>>&nbsp;&nbsp;&nbsp;Separate</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br />
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="invoice_type" class="control-label">Invoice Type</label>
                                        <select name="invoice_type" id="invoice_type" class="form-control select2"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn module_save_btn"><?= isset($edit_user_id) ? 'Update' : 'Save' ?></button>
                            <b style="color: #0974a7">Ctrl + S</b>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="<?= BASE_URL('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>
<script type="text/javascript">
    var table;
    var first_time_edit_mode = 1;
    var on_save_add_edit_item = 0;
    var edit_lineitem_inc = 0;
    var lineitem_objectdata = [];
    <?php if (isset($prefix_lineitem_details)) { ?>
        var li_lineitem_objectdata = [<?php echo $prefix_lineitem_details; ?>];
        first_time_edit_mode = 0;
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
        display_lineitem_html(lineitem_objectdata);
    <?php } ?>
    $(document).ready(function () {
        $(".select2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });

        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);
        CKEDITOR.replace('sales_invoice_notes', {
            removePlugins: 'forms,iframe,preview,a11yhelp,stylescombo,div,showblocks,about,link,pagebreak,flash,smiley,templates,save,print,newpage,table,tabletools,indentblock,htmlwriter,htmldataprocessor,htmlwriter,floatingspace'
        });
        
        <?php if (!isset($edit_user_id) && empty($edit_user_id)) { ?>
        <?php } ?>
        
        initAjaxSelect2($("#state"), "<?= base_url('app/state_select2_source') ?>");
        <?php if (isset($edit_state) && !empty($edit_state)) { ?>
            setSelect2Value($("#state"), "<?= base_url('app/set_state_select2_val_by_id/' . $edit_state) ?>");
            initAjaxSelect2($('#city'), "<?= base_url('app/city_select2_source/' . $edit_state) ?>");
        <?php } ?>
            
        $('#state').on('change', function () {
            $("#city").empty().trigger('change');
            var state_home = this.value;
            initAjaxSelect2($('#city'), "<?= base_url('app/city_select2_source') ?>/" + state_home);
        });
        <?php if (isset($edit_city) && !empty($edit_city)) { ?>
            setSelect2Value($("#city"), "<?= base_url('app/set_city_select2_val_by_id/' . $edit_city) ?>");
        <?php } ?>

        initAjaxSelect2($("#invoice_type"), "<?= base_url('app/invoice_type_select2_source/') ?>");
        <?php if (isset($edit_invoice_type) && !empty($edit_invoice_type)) { ?>
            setSelect2Value($("#invoice_type"), "<?= base_url('app/set_invoice_type_select2_val_by_id/') ?>" + <?= $edit_invoice_type; ?>);
        <?php } ?>
           
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
              e.preventDefault();
              return false;
            }
        });
        shortcut.add("ctrl+s", function () {
            $(".module_save_btn").click();
        });

        $('#state').on("select2:close", function (e) {
            $("#pan").focus();
        });
        $('#city').on("select2:close", function (e) {
            $("#invoice_no_start_from").focus();
        });
        $('#userType').on("select2:close", function (e) {
            $("#password").focus();
        });
        $('#purchase_rate').on("select2:close", function (e) {
            $("#sales_rate").select2('open');
        });
        $('#sales_rate').on("select2:close", function (e) {
            $("#shipment_invoice_no").focus();
        });

        $('#add_lineitem').on('click', function () {
            var index_val = $('#index').val();
            var company_invoice_prefix = $('#company_invoice_prefix').val();
            if ($.trim($('#company_invoice_prefix').val()) == '') {
                $("#company_invoice_prefix").focus();
                show_notify('Please Enter Company Invoice Prefix.', false);
                return false;
            } else {
                var is_same = 0;
                $.each(lineitem_objectdata, function (index, value) {
                    if (value.company_invoice_prefix == company_invoice_prefix && parseInt(index) != parseInt(index_val)) {
                        is_same = 1;
                    }
                });
                if (is_same == 1) {
                    $("#company_invoice_prefix").focus();
                    show_notify("Company Invoice Prefix already exist!", false);
                    return false;
                }
            }

            var key = '';
            var keys = '';
            var value = '';
            var lineitem = {};
            var is_validate = '0';

            $('input[name^="line_items_data"]').each(function (index) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");

                if (key == 'default_prefix') {
                    if ($(this).prop("checked") == true) {
                        $.each(lineitem_objectdata, function (index, value) {
                            if (value.default_prefix == '1' && index_val != '') {
                                if (parseInt(index) != parseInt(index_val)) {
                                    is_validate = '1';
                                    show_notify('You Have Selected Default Prefix.', false);
                                    return false;
                                }
                            } else if (value.default_prefix == '1') {
                                is_validate = '1';
                                show_notify('You Have Selected Default Prefix.', false);
                                return false;
                            }
                        });
                    }
                }
            });
            if (is_validate == '1') {
                return false;
            }

            $('input[name^="line_items_data"]').each(function () {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                if (key == 'default_prefix') {
                    if ($(this).prop("checked") == true) {
                        lineitem[key] = '1';
                    } else if ($(this).prop("checked") == false) {
                        lineitem[key] = '0';
                    }
                } else {
                    lineitem[key] = value;
                }
            });

            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            var line_items_index = $("#line_items_index").val();
            if (line_items_index != '') {
                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
            } else {
                lineitem_objectdata.push(new_lineitem);
            }
            display_lineitem_html(lineitem_objectdata);
            $('#id').val('');
            $("#company_invoice_prefix").val('');
            $('#default_prefix').prop('checked', false);
            $("#line_items_index").val('');
            edit_lineitem_inc = 0;
        });

        $(document).on('submit', '#form_user', function () {
            var reggst = /^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/;
            var gstinVal = $('#gst_no').val();
            if (gstinVal != '') {
                if (gstinVal.length != '15') {
                    show_notify("Please Enter 15 Digit GSTIN!", false);
                    return false;
                }
//                if (!reggst.test(gstinVal)) {
//                    show_notify('GST Identification Number is not valid. It should be in this "11AAAAA1111Z1A1" format !', false);
//                    $("#gst_no").focus();
//                    return false;
//                }
            }
            var password = $('#password').val().length;
            var cpassword = $('#cpassword').val().length;
            if (cpassword != 0 && password < 1) {
//                $("#password").prop('required',true);
                show_notify('Please Enter Confirm Password !', false);
                return false;
            }
            var email_ids = $('#email_ids').val();
            var email_ids = email_ids.split(',');
            var check_duplicate = [];
            var found_duplicate = 0;
            $.each(email_ids, function (index, email) {
                if ($.inArray(email, check_duplicate) > -1) {
                    found_duplicate = 1;
                } else {
                    check_duplicate.push(email);
                }
            });
            if (found_duplicate == 1) {
                show_notify('Duplicate Email Exist !', false);
                $("#email_ids").focus();
                return false;
            }
            
            $('#ajax-loader').show();
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            //console.log(postData);return false;
            $.ajax({
                url: "<?= base_url('auth/save_user') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                fileElementId: 'logo_image',
                data: postData,
                success: function (response) {
                    $('#ajax-loader').hide();
                    var json = $.parseJSON(response);
                    if (json['error'] == 'emailExist') {
                        show_notify('Email/User Name Already Exist !', false);
                        jQuery("#email_id").focus();
                        return false;
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('user/user') ?>";
                    }
                    if (json['error'] == 'errorAdded') {
                        show_notify('Some error has occurred !', false);
                        return false;
                    }
                    if (json['error'] == 'userExist') {
                        show_notify('Name Already Exist !', false);
                        jQuery("#user_name").focus();
                        return false;
                    }
                    if (json['error'] == 'email_error') {
                        show_notify(json['msg'], false);
                        jQuery("#email_ids").focus();
                        return false;
                    }
                    if (json['success'] == 'Updated') {
                        <?php if (isset($profile)) { ?>
                            window.location.href = "<?php echo base_url('/') ?>";
                        <?php } else { ?>
                            window.location.href = "<?php echo base_url('user/user_list') ?>";
                        <?php } ?>
                    }
                    return false;
                },
            });
            return false;
        });

    });

    function display_lineitem_html(lineitem_objectdata) {
        var new_lineitem_html = '';

        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            if(typeof(value.delete_not_allow) !== "undefined" && value.delete_not_allow == 1) {

            } else {
                lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';    
            }            
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>';
            if (value.default_prefix == '1') {
                row_html += '<td style="text-align: center;"><i class="fa fa-check"></td>';
            } else {
                row_html += '<td style="text-align: center;"><i class="fa fa-remove"></td>';
            }
            row_html += '<td>' + value.company_invoice_prefix + '</td>';
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#index').val('');
    }

    function edit_lineitem(index) {
        $('#default_bank').prop('checked', false);
//        $("html, body").animate({scrollTop: 880}, "slow");
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];

        $("#line_items_index").val(index);
        if (typeof (value.id) != "undefined" && value.id !== null) {
            $("#id").val(value.id);
        }

        $("#company_invoice_prefix").val(value.company_invoice_prefix);
        if (value.default_prefix == '1') {
            $('#default_prefix').prop('checked', true);
        }
        $('#index').val(index);
    }

    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            if (typeof (value.lineitem_id) != "undefined" && value.lineitem_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.lineitem_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }
</script>
