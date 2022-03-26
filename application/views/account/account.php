<style>
    .checkbox_ch{
        height: 20px;
        width: 20px;
    }
</style>
<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Account
            <?php if($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID, "view")){ ?>
                <a href="<?= base_url('account/account_list'); ?>" class="btn btn-primary pull-right">Account List</a>
            <?php } ?>            
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="account_form_account" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
                    <?php if (isset($account_id) && !empty($account_id)) { ?>
                        <input type="hidden" id="account_id" name="account_id" value="<?= $account_id; ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            Register
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_name" class="control-label">Name <span class="required-sign">*</span></label>
                                        <input type="text" name="account_name" class="form-control" id="account_name" value="<?= isset($account_name) ? $account_name : '' ?>" data-index="1" placeholder="" required autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_phone" class="control-label"> Phone</label>
                                        <input type="number" name="account_phone" class="form-control" id="account_phone" value="<?= isset($account_phone) ? $account_phone : '' ?>" data-index="2" placeholder="" >
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_email_ids" class="control-label">Account Email</label>
                                        <textarea name="account_email_ids" class="form-control" id="account_email_ids" data-index="3" placeholder=""><?= isset($account_email_ids) ? $account_email_ids : '' ?></textarea>
                                        <small class="">Add multiple email by comma separated.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_group_id" class="control-label"> Account Group <span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/account_group')?>"><i class="fa fa-plus"></i> Add Account Group</a>
                                        <select name="account_group_id" id="account_group_id" class="account_group_id" data-index="4" required="required"></select>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="box-header with-border">
                                        <h4 class="box-title form_title">Address</h4>
                                    </div>	
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_address" class="control-label">Street</label>
                                        <textarea name="account_address" class="form-control" id="account_address" data-index="5" placeholder=""><?= isset($account_address) ? $account_address : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_postal_code" class="control-label">Postal Code</label>
                                        <input type="number" name="account_postal_code" class="form-control" id="account_postal_code" value="<?= isset($account_postal_code) ? $account_postal_code : '' ?>" data-index="6" placeholder="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_state" class="control-label">State</label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/state')?>"><i class="fa fa-plus"></i> Add State</a>
                                        <select name="account_state" id="account_state" class="" data-index="7" ></select>
                                        <input type="hidden" id="state_code" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_city" class="control-label">City</label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/city')?>"><i class="fa fa-plus"></i> Add City</a>
                                        <select name="account_city" id="account_city" class="select2" data-index="8" ></select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="box-header with-border">
                                        <h4 class="box-title form_title">More Imformation</h4>
                                    </div>	
                                </div>
                                <div class="col-md-6">
                                    <div class="box-header with-border">
                                        <h4 class="box-title form_title"></h4>
                                    </div>	
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_contect_person_name">Contact Person Name</label>
                                        <input type="text" name="account_contect_person_name" class="form-control" id="account_contect_person_name" value="<?= isset($account_contect_person_name) ? $account_contect_person_name : '' ?>" data-index="9" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_contect_person_name">Aadhar</label>
                                        <input type="text" name="account_aadhaar" class="form-control" id="account_aadhaar" value="<?= isset($account_aadhaar) ? $account_aadhaar : '' ?>" data-index="10" placeholder="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_gst_no" class="control-label">GSTIN</label>
                                        <input type="text" name="account_gst_no" class="form-control" id="account_gst_no" maxlength="15" value="<?= isset($account_gst_no) ? $account_gst_no : '' ?>" data-index="11" placeholder="" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_pan" class="control-label">Pan</label>
                                        <input type="text" name="account_pan" class="form-control" id="account_pan" value="<?= isset($account_pan) ? $account_pan : '' ?>" data-index="12" placeholder="" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="opening_balance" class="control-label">Opening Balance</label>
                                        <input type="text" name="opening_balance" class="form-control num_only" id="opening_balance" value="<?= isset($opening_balance) ? $opening_balance : '' ?>" data-index="13" placeholder="" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="credit_debit" class="control-label">Credit / Debit</label>
                                        <select name="credit_debit" id="credit_debit" class="form-control select2" data-index="14">
                                            <option value="1" <?=(isset($credit_debit) && $credit_debit == '1') ? 'selected' : '' ?>>Credit</option>
                                            <option value="2" <?=(isset($credit_debit) && $credit_debit == '2') ? 'selected' : '' ?>>Debit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 pull-right">
                                    <?php if (isset($account_id) && !empty($account_id)) { } else {
                                        if (isset($is_bill_wise_company) && !empty($is_bill_wise_company)) {
                                            $is_bill_wise = 1;
                                        }
                                    } ?>
                                    <div class="form-group"><br />
                                        <label><input type="checkbox" name="is_bill_wise" class="checkbox_ch"  <?php echo (isset($is_bill_wise) ? ($is_bill_wise == 1 ? 'checked' : '') : ''); ?>>&nbsp;&nbsp;&nbsp;Balance Bill Wise</label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><h4><b>Bank Details</b></h4><hr style="margin-top: 0px;"></div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_name" class="control-label">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?= isset($bank_name) ? $bank_name : ''; ?>" data-index="19">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_acc_name" class="control-label">Bank Account Name</label>
                                        <input type="text" name="bank_acc_name" id="bank_acc_name" class="form-control" value="<?= isset($bank_acc_name) ? $bank_acc_name : ''; ?>" data-index="20">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_branch" class="control-label">Bank Branch</label>
                                        <input type="text" name="bank_branch" id="bank_branch" class="form-control" value="<?= isset($bank_branch) ? $bank_branch : ''; ?>" data-index="21">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_city" class="control-label">Bank City</label>
                                        <input type="text" name="bank_city" id="bank_city" class="form-control" value="<?= isset($bank_city) ? $bank_city : ''; ?>" data-index="22">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bank_ac_no" class="control-label">Bank Account No.</label>
                                        <input type="text" name="bank_ac_no" id="bank_ac_no" class="form-control" value="<?= isset($bank_ac_no) ? $bank_ac_no : ''; ?>" data-index="23">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="rtgs_ifsc_code" class="control-label">RTGS / IFSC Code</label>
                                        <input type="text" name="rtgs_ifsc_code" id="rtgs_ifsc_code" class="form-control" value="<?= isset($rtgs_ifsc_code) ? $rtgs_ifsc_code : ''; ?>" data-index="24">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn module_save_btn"><?= isset($account_id) ? 'Update' : 'Save' ?></button>
                            <b style="color: #0974a7">Ctrl + S</b>
                        </div>
                    </div>
                </form>

                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        $(".select2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
<?php if (!isset($account_id) && empty($account_id)) { ?>

<?php } ?>
        //initAjaxSelect2($("#account_sales_person"),"<?= base_url('app/account_select2_source/' . SALES_ACCOUNT_GROUP_ID) ?>");
        initAjaxSelect2($("#account_group_id"), "<?= base_url('app/account_group_select2_source_for_account/') ?>");
        initAjaxSelect2($("#account_state"), "<?= base_url('app/state_select2_source') ?>");
        $('#account_state').on('change', function () {
            $("#account_city").empty().trigger('change');
            var state_home = this.value;
            initAjaxSelect2($('#account_city'), "<?= base_url('app/city_select2_source') ?>/" + state_home);
        });

<?php if (isset($account_state) && !empty($account_state)) { ?>
            setSelect2Value($("#account_state"), "<?= base_url('app/set_state_select2_val_by_id/' . $account_state) ?>");
            initAjaxSelect2($('#account_city'), "<?= base_url('app/city_select2_source/' . $account_state) ?>");
            get_account_state_code(<?php echo $account_state; ?>);
<?php } ?>

<?php if (isset($account_city) && !empty($account_city)) { ?>
            setSelect2Value($("#account_city"), "<?= base_url('app/set_city_select2_val_by_id/' . $account_city) ?>");
<?php } ?>


<?php if (isset($account_sales_person) && !empty($account_sales_person)) { ?>
            setSelect2Value($("#account_sales_person"), "<?= base_url('app/set_account_select2_val_by_id/' . $sales_person) ?>");
<?php } ?>
<?php if (isset($account_group_id) && !empty($account_group_id)) { ?>
            setSelect2Value($("#account_group_id"), "<?= base_url('app/set_account_group_select2_val_by_id/' . $account_group_id) ?>");
<?php } ?>

        $(document).on('keypress', '#account_gst_no', function () {
           var  account_state = $('#account_state').val();
           if(account_state == null || account_state == '') {
                show_notify('Please Select State', false);
                $("#account_state").select2('open');
                return false;
           }
        });

        $(document).on('keydown','#account_email_ids', function(e) {
            if (e.keyCode == 9) {
                $('#account_group_id').select2('open');
            }
        });
        $(document).on('keydown','#account_postal_code', function(e) {
            if (e.keyCode == 13) {
                $('#account_state').select2('open');
            }
        });
        $(document).on('change', '#account_state', function () {
            var  account_state = $('#account_state').val();
            get_account_state_code(account_state);
        });

        $('#account_group_id').on("select2:close", function(e) { 
            $("#account_address").focus();
        });
        $('#account_state').on("select2:close", function(e) { 
            $("#account_city").select2('open');
        });
        $('#account_city').on("select2:close", function(e) { 
            $("#account_contect_person_name").focus();
        });

        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });
        

        $(document).on('submit', '#account_form_account', function () {
            var reggst = /^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([a-zA-Z0-9]){1}?$/;
            var gstinVal = $('#account_gst_no').val();            
            if(gstinVal != '') {
                if (gstinVal.length != '15') {
                    show_notify("Please Enter 15 Digit GSTIN!", false);
                    return false;
                }
                var statecode = $('#account_gst_no').val().substring(0, 2);
                var truestatecode = $('#state_code').val();
                if(parseInt(statecode) != parseInt(truestatecode)) {
                    show_notify('Your state code should be '+truestatecode + ' !', false);
                    $("#account_gst_no").focus();
                    return false;
                }
                
                if(!reggst.test(gstinVal)) {
                    show_notify('GST Identification Number is not valid. It should be in this "11AAAAA1111Z1A1" format !', false);
                    $("#account_gst_no").focus();
                    return false;
                }
            }
            
            //alert('hear');return false;
            var email_ids = $('#account_email_ids').val();
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
                $("#account_email_ids").focus();
                return false;
            }

            var postData = new FormData(this);
            //console.log(postData);return false;
            $.ajax({
                url: "<?= base_url('account/save_account') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                fileElementId: 'account_image',
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'emailExist') {
                        show_notify('Email/User Name Already Exist !', false);
                        jQuery("#email_id").focus();
                        return false;
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('account/account') ?>";
                    }
                    if (json['error'] == 'errorAdded') {
                        show_notify('Some error has occurred !', false);
                        return false;
                    }
                    if (json['error'] == 'accountExist') {
                        show_notify('Account Name Already Exist !', false);
                        jQuery("#account_name").focus();
                        return false;
                    }
                    if (json['error'] == 'email_error') {
                        show_notify(json['msg'], false);
                        jQuery("#email_ids").focus();
                        return false;
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('account/account_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });

    });

    function get_account_state_code(account_state){
        if(account_state != null || account_state != '') {
            $.ajax({
                url: "<?= base_url('account/get_state_code') ?>",
                type: "POST",
                data: {account_state: account_state},
                success: function (response) {
                    var json = $.parseJSON(response);
                    var gstin = $('#account_gst_no').val();
                    if(gstin == null || gstin == '') {
                        $('#account_gst_no').val(json);
                    } else if(gstin.length == 2) {
                        $('#account_gst_no').val(json);
                    }
                    $('#state_code').val(json);
                }
            });
        }
    }
</script>
