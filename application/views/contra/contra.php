<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<style>
.content-header {
    padding: 5px 15px 0 15px;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h4>
            Contra : 
            <a href="<?= base_url('contra/contra'); ?>"><span class="label label-primary">Contra - CTRL + F8</span></a>
            <a href="<?= base_url('transaction/receipt'); ?>"><span class="label label-success">Receipt - CTRL + F5</span></a>
            <a href="<?= base_url('transaction/payment'); ?>"><span class="label label-warning">Payment - CTRL + F6</span></a>
            <a href="<?= base_url('journal/journal_type2'); ?>"><span class="label label-info">Journal Type 2</span></a>

            <?php if($this->applib->have_access_role(MODULE_CONTRA_ID,"view")) { ?>
            <a href="<?= base_url('contra/contra_list'); ?>" class="btn btn-primary pull-right">Contra List</a><br/>
            <?php } ?>            
        </h4>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_transaction" class="" action="<?= base_url('transaction/save_transaction') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="" >
                    <?php if (isset($transaction_data->transaction_id) && !empty($transaction_data->transaction_id)) { ?>
                        <input type="hidden" name="transaction_id" class="transaction_id" value="<?= $transaction_data->transaction_id ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title"><?= isset($transaction_data->transaction_id) ? 'Edit' : 'Add' ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transaction_date" class="control-label">Date <span class="required-sign">*</span></label>
                                        <input type="text" name="transaction_date" class="form-control input-datepicker" id="datepicker1" value="<?= (isset($transaction_data->transaction_date)) ? date('d-m-Y', strtotime($transaction_data->transaction_date)) : date('d-m-Y'); ?>" autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount" class="control-label">Amount <span class="required-sign">*</span></label>
                                        <input type="text" name="amount" class="form-control num_only" id="amount" value="<?= (isset($transaction_data->amount)) ? $transaction_data->amount : ''; ?>">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="bank" id="bank">
                                        <label for="from_account_id" class="control-label">From Bank / Cash<span class="required-sign">*</span></label>
                                        <select name="from_account_id" id="from_account_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bank" id="bank">
                                        <label for="to_account_id" class="control-label">To Bank / Cash<span class="required-sign">*</span></label>
                                        <select name="to_account_id" id="to_account_id" class="form-control select2" ></select>
                                        <div class="clearfix"></div><br/>
                                    </div>
                                </div>
                               
                                <div class="clearfix"></div>
                                <div class="col-md-6">
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
<!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {       
        initAjaxSelect2($("#from_account_id"), "<?= base_url('app/cash_bank_account_select2_source') ?>");
        initAjaxSelect2($("#to_account_id"), "<?= base_url('app/cash_bank_account_select2_source') ?>");
        <?php if (isset($transaction_data->from_account_id) && !empty($transaction_data->from_account_id)) { ?>
                setSelect2Value($("#from_account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->from_account_id) ?>");
        <?php } ?>     

        <?php if (isset($transaction_data->to_account_id) && !empty($transaction_data->to_account_id)) { ?>
                setSelect2Value($("#to_account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->to_account_id) ?>");
        <?php } ?>
            
        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

        $(document).on('keydown','#amount', function(e) {
            if (e.keyCode == 13) {
                $('#from_account_id').select2('open');
            }
        });

        $('#from_account_id').on("select2:close", function(e) { 
            $("#to_account_id").select2('open');
        });
        $('#to_account_id').on("select2:close", function(e) { 
            $("#note").focus();
        });

        $(document).on('submit', '#form_transaction', function () {
            if ($.trim($("#datepicker1").val()) == '') {
                show_notify('Please Select Date.', false);
                $("#datepicker1").focus();
                return false;
            }
            if ($.trim($("#amount").val()) == '') {
                show_notify('Please Enter Amount.', false);
                $("#amount").focus();
                return false;
            }
            if ($.trim($("#from_account_id").val()) == '') {
                show_notify('Please Select From Bank / Cash.', false);
                $("#from_account_id").focus();
                return false;
            }
            if ($.trim($("#to_account_id").val()) == '') {
                show_notify('Please Select To Bank / Cash.', false);
                $("#to_account_id").focus();
                return false;
            }           
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('contra/save_contra') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('contra/contra_list') ?>";
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('contra/contra_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
    });
</script>
