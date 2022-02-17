<?php $this->load->view('success_false_notify'); ?>
<?php $segment2 = $this->uri->segment(2); ?>
<?php
    $page_parameter = 'gst_payment';
    $page_title = 'GST Payment';
?>
<style>
.content-header {
    padding: 5px 15px 0 15px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h4>
            <a href="<?= base_url('transaction/payment_list'); ?>" class="btn btn-primary pull-right">Payment List</a><br/>
        </h4>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_transaction" class="" action="<?= base_url('transaction/save_gst_payment') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="" >
                    <?php if (isset($transaction_data->transaction_id) && !empty($transaction_data->transaction_id)) { ?>
                        <input type="hidden" name="transaction_id" class="transaction_id" value="<?= $transaction_data->transaction_id ?>">
                    <?php } ?>
                    <input type="hidden" name="transaction_type" class="transaction_id" value="1">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title"><?= isset($transaction_data->transaction_id) ? 'Edit '.$page_title : 'Add '.$page_title ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group" id="">
                                        <label for="bank_id" class="control-label">Bank / Cash<span class="required-sign">*</span></label>
                                        <select name="from_account_id" id="cas_bank_account_id" class="form-control select2" ></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="transaction_date" class="control-label">Date <span class="required-sign">*</span></label>
                                        <input type="text" name="transaction_date" class="form-control input-datepicker" id="datepicker1" value="<?= (isset($transaction_data->transaction_date)) ? date('d-m-Y', strtotime($transaction_data->transaction_date)) : date('d-m-Y',strtotime($transaction_date)); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bank" id="bank">
                                        <label for="account_id" class="control-label">Tax Account <span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account')?>"><i class="fa fa-plus"></i> Add Account</a>
                                            <select name="to_account_id" id="account_id" class="form-control select2" ></select>
                                            <?php if (isset($transaction_data->transaction_id) && !empty($transaction_data->transaction_id)) { ?>
                                                <input type="hidden" name="old_account_id" value="<?= $transaction_data->from_account_id; ?>">
                                            <?php } ?>
                                        <div class="clearfix"></div><br/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount" class="control-label">Tax Amount <span class="required-sign">*</span></label>
                                        <input type="text" name="amount" class="form-control " id="amount" value="<?= (isset($transaction_data->amount)) ? $transaction_data->amount : ''; ?>">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="interest" class="control-label">Interest</label>
                                        <input type="text" name="interest" class="form-control num_only" id="interest" value="<?= (isset($transaction_data->interest)) ? $transaction_data->interest : ''; ?>">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="penalty" class="control-label">Penalty</label>
                                        <input type="text" name="penalty" class="form-control num_only" id="penalty" value="<?= (isset($transaction_data->penalty)) ? $transaction_data->penalty : ''; ?>">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fees" class="control-label">Fees</label>
                                        <input type="text" name="fees" class="form-control num_only" id="fees" value="<?= (isset($transaction_data->fees)) ? $transaction_data->fees : ''; ?>">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="other_charges" class="control-label">Other Charges</label>
                                        <input type="text" name="other_charges" class="form-control num_only" id="other_charges" value="<?= (isset($transaction_data->other_charges)) ? $transaction_data->other_charges : ''; ?>">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_amount" class="control-label">Total Amount</label>
                                        <input type="text" name="total_amount" class="form-control num_only" readonly id="total_amount" >
                                        <div class="clearfix"></div>
                                    </div>
                                </div>                                
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="receipt_no" class="control-label">Cheque / Cash Receipt No.</label>
                                        <input type="text" name="receipt_no" class="form-control" id="receipt_no" value="<?= (isset($transaction_data->receipt_no)) ? $transaction_data->receipt_no : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="note" class="control-label">Note</label>
                                        <textarea name="note" class="form-control" id="note"><?= (isset($transaction_data->note)) ? $transaction_data->note : ''; ?> </textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm form_btn module_save_btn" ><?= isset($transaction_data->transaction_id) ? 'Update' : 'Save' ?></button>
                            <b style="color: #0974a7">Ctrl + S</b>
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<input type="hidden" value="0" id="is_edit">
<!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        initAjaxSelect2($("#cas_bank_account_id"), "<?= base_url('app/cash_bank_account_select2_source') ?>");
        initAjaxSelect2($("#account_id"), "<?= base_url('app/gst_account_select2_source') ?>");
        <?php if (isset($transaction_data->from_account_id) && !empty($transaction_data->from_account_id)) { ?>
            setSelect2Value($("#cas_bank_account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->from_account_id) ?>");
        <?php } ?>     
        
        <?php if (isset($transaction_data->to_account_id) && !empty($transaction_data->to_account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->to_account_id) ?>");
        <?php } ?>

        setTimeout(function(){
            $('#cas_bank_account_id').select2('open');
        },100);

        
        $(document).on('input','#amount, #penalty, #interest, #fees, #other_charges', function() {
            get_total_amt_sum();
        });

        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

        $('#account_id').on("select2:close", function(e) { 
            $("#amount").focus();
        });

        $('#cas_bank_account_id').on("select2:close", function(e) { 
            $("#datepicker1").focus();
        });
        $(document).on('submit', '#form_transaction', function () {
            if ($.trim($("#datepicker1").val()) == '') {
                show_notify('Please Select Date.', false);
                $("#datepicker1").focus();
                return false;
            }
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account.', false);
                $("#account_id").focus();
                return false;
            }
            if ($.trim($("#cas_bank_account_id").val()) == '') {
                show_notify('Please Select Bank / Cash.', false);
                $("#cas_bank_account_id").focus();
                return false;
            }
            if ($.trim($("#amount").val()) == '') {
                show_notify('Please Enter Amount.', false);
                $("#amount").focus();
                return false;
            }
            
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('transaction/save_gst_payment') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.reload();
                    }
                    if (json['success'] == 'Updated') {
                        window.location.reload();
                    }
                    return false;
                },
            });
            return false;
        });
        
//        $(document).on('click', '.view_detail', function () {
//            $('#clicked_item_id').val($(this).attr('data-client_id'));
//            detail_table.draw();
//            $('#myModal_detail').modal('show');
//        });
        
        
    });
    
    function get_total_amt_sum(){
        var amount = $.trim($("#amount").val()) || 0; 
        var interest = $.trim($('#interest').val()) || 0; 
        var penalty = $.trim($('#penalty').val()) || 0; 
        var fees = $.trim($('#fees').val()) || 0; 
        var other_charges = $.trim($('#other_charges').val()) || 0;
        var total_amt = parseInt(amount) + parseInt(interest) + parseInt(penalty) + parseInt(fees) + parseInt(other_charges);
        $('#total_amount').val(total_amt);
    }
    
</script>
