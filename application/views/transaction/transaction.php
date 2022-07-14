<?php $this->load->view('success_false_notify'); ?>
<?php $segment2 = $this->uri->segment(2); ?>
<?php
    $page_parameter = '';
    $page_title = '';
    if ($segment2 == 'receipt') {
        $page_parameter = 'receipt';
        $page_title = 'Receipt : ';
    } else if ($segment2 == 'payment') {
        $page_parameter = 'payment';
        $page_title = 'Payment : ';
    }
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
            <?php echo $page_title; ?>
            <a href="<?= base_url('transaction/receipt'); ?>"><span class="label label-success">Receipt - CTRL + F5</span></a>
            <a href="<?= base_url('transaction/payment'); ?>"><span class="label label-warning">Payment - CTRL + F6</span></a>
            <a href="<?= base_url('contra/contra'); ?>"><span class="label label-primary">Contra - CTRL + F8</span></a>
            <a href="<?= base_url('journal/journal_type2'); ?>"><span class="label label-info">Journal Type 2</span></a>
            <?php if($segment2 == 'payment') { ?>
                <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"view")) { ?>
                <a href="<?= base_url('transaction/payment_list'); ?>" class="btn btn-primary pull-right">Payment List</a>
                <?php } ?>
            <?php } else { ?>
                <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"view")) { ?>
                <a href="<?= base_url('transaction/receipt_list'); ?>" class="btn btn-primary pull-right">Receipt List</a>
                <?php } ?>                
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
                    <?php if($segment2 == 'payment') { ?>
                        <input type="hidden" name="transaction_type" class="transaction_id" value="1">
                    <?php } else { ?>
                        <input type="hidden" name="transaction_type" class="transaction_id" value="2">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title"><?= isset($transaction_data->transaction_id) ? 'Edit '.$page_title : 'Add '.$page_title ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group" id="">
                                        <label for="bank_id" class="control-label">Bank / Cash<span class="required-sign">*</span></label>
                                         <?php if($segment2 == 'payment') { ?>
                                            <select name="from_account_id" id="cas_bank_account_id" class="form-control select2" ></select>
                                        <?php } else { ?>
                                            <select name="to_account_id" id="cas_bank_account_id" class="form-control select2" ></select>
                                        <?php } ?>                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transaction_date" class="control-label">Date <span class="required-sign">*</span></label>
                                        <input type="text" name="transaction_date" class="form-control input-datepicker" id="datepicker1" value="<?= (isset($transaction_data->transaction_date)) ? date('d-m-Y', strtotime($transaction_data->transaction_date)) : date('d-m-Y',strtotime($transaction_date)); ?>">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="bank" id="bank">
                                        <label for="account_id" class="control-label">Account <span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account')?>"><i class="fa fa-plus"></i> Add Account</a>
                                        <?php if($segment2 == 'payment') { ?>
                                            <select name="to_account_id" id="account_id" class="form-control select2" ></select>
                                            <?php if (isset($transaction_data->transaction_id) && !empty($transaction_data->transaction_id)) { ?>
                                                <input type="hidden" name="old_account_id" value="<?= $transaction_data->from_account_id; ?>">
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise'] == '1'){ ?>
                                                <a class="btn btn-primary btn-xs select_invoice">Select Invoice</a>
                                            <?php } ?>
                                            <select name="from_account_id" id="account_id" class="form-control select2 from_account_id select_invoice"></select>
                                            <?php if (isset($transaction_data->transaction_id) && !empty($transaction_data->transaction_id)) { ?>
                                                <input type="hidden" name="old_account_id" value="<?= $transaction_data->from_account_id; ?>">
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="clearfix"></div><br/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="amount" class="control-label">Amount <span class="required-sign">*</span></label>
                                        <?php 
                                            $readonly = '';
                                            if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise'] == '1'){
                                                $readonly = '';
                                            }
                                        ?>
                                        <input type="text" name="amount" class="form-control num_only" <?= $readonly ?> id="amount" value="<?= (isset($transaction_data->amount)) ? $transaction_data->amount : ''; ?>">
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="site_id" class="control-label">Site</label>
                                        <select name="site_id" id="site_id" class="form-control select2"></select>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receipt_no" class="control-label">Cheque / Cash Receipt No.</label>
                                        <input type="text" name="receipt_no" class="form-control" id="receipt_no" value="<?= (isset($transaction_data->receipt_no)) ? $transaction_data->receipt_no : ''; ?>">
                                    </div>
                                </div>
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
                    <div id="myModal_detail" class="modal fade" role="dialog">
                        <div class="modal-dialog" style="width: 80%;">
                            <div class="modal-content" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Sales Invoices</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="box-body table-responsive">
                                        <table id="detail_table" class="table table-bordered table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Invoice Date</th>
                                                    <th style="text-align: right;">Invoice Amount</th>
                                                    <th style="text-align: right;">Paid Amount</th>
                                                    <th style="text-align: right;">Pending Amount</th>
                                                    <th style="text-align: right;">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="body_detail_table">

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: right;"><b>Total</b></td>
                                                    <td style="text-align: right; font-weight: 600;" class="foot"><b>0</b></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
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
        var checked_invoice_ids = [];
        var unchecked_invoice_ids = [];
        initAjaxSelect2($("#cas_bank_account_id"), "<?= base_url('app/cash_bank_account_select2_source') ?>");
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_select2_source') ?>");
        initAjaxSelect2($("#site_id"), "<?= base_url('app/sites_select2_source') ?>");
        <?php if (isset($transaction_data->from_account_id) && !empty($transaction_data->from_account_id)) { ?>
            <?php if($segment2 == 'payment') { ?>
                setSelect2Value($("#cas_bank_account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->from_account_id) ?>");
            <?php } else { ?> 
                $('#is_edit').val('1');
                setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->from_account_id) ?>");
            <?php } ?>
        <?php } ?>     
        
        <?php if (isset($transaction_data->to_account_id) && !empty($transaction_data->to_account_id)) { ?>
            <?php if($segment2 == 'payment') { ?>
                setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->to_account_id) ?>");
            <?php } else { ?> 
                setSelect2Value($("#cas_bank_account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $transaction_data->to_account_id) ?>");
            <?php } ?>
        <?php } ?>

        setTimeout(function(){
            $('#cas_bank_account_id').select2('open');
        },100);

        $(document).on('click', '.select_invoice', function() {
            get_invoice_data();
        });

        $(document).on('change', '.select_invoice', function() {
            get_invoice_data();
        });

        $(document).on('keydown','#amount', function(e) {
            if (e.keyCode == 13) {
                $('#account_id').select2('open');
            }
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
            if ($.trim($("#amount").val()) == '') {
                show_notify('Please Enter Amount.', false);
                $("#amount").focus();
                return false;
            }
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account.', false);
                $("#account_id").focus();
                return false;
            }
            if ($.trim($("#cas_bank_account_id").val()) == '') {
                show_notify('Please Select Bank / Cash.', false);
                $("#bank_id").focus();
                return false;
            }
            var is_billwise = "<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise']; ?>";
            var is_segment2 = "<?php echo $segment2; ?>";
            if((is_segment2 == 'receipt') && (is_billwise == '1')) {
                bill_val = $('.foot').text();
                amt_val = $("#amount").val();
                if(parseInt(bill_val) != parseInt(amt_val)){
                    show_notify('Bill amount and Receipt amount not matched.', false);
                    $("#amount").focus();
                    return false;
                }
            }
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('transaction/save_transaction') ?>",
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
                        window.location.href = "<?php if($segment2 == 'payment') { echo base_url('transaction/payment_list'); } else { echo base_url('transaction/receipt_list'); } ?>";
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
        
        $(document).on('input', '.invoice_check_box', function(){
            get_sum_selected();
        });
        
    });
    
    function get_invoice_data(){
        $('.foot').text('0');
            var bill_wise = "<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise']; ?>";
            var tr_id = "<?= (isset($transaction_data->transaction_id)) ? $transaction_data->transaction_id : ''; ?>";
            if(bill_wise == '1'){
                var from_account_id = $('.from_account_id').val();
                $.ajax({
                    url: "<?= base_url('transaction/get_invoice_data') ?>",
                    type: "POST",
                    data: {account_id: from_account_id, tr_id: tr_id, rec_amount: $('#amount').val()},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['invoice_data'] != '') {
                            if($('#is_edit').val() == '0'){
                                $('#myModal_detail').modal('show');
                            } else {
                                $('#is_edit').val('0');
                            }
                            var row_html = "";
                            $.each(json['invoice_data'], function (index, value) {
                                new_amt = '';
                                if(value.new_amount){
                                    new_amt = value.new_amount;
                                }
                                row_html += "<tr class='tr_row'>\n\
                                        <td>"+value.sales_invoice_no+"</td>\n\
                                        <td>"+value.sales_invoice_date+"</td>\n\
                                        <td class='' style='text-align: right;'>"+value.bill_amount+"</td>\n\
                                        <td class='' style='text-align: right;'>"+value.paid_amount+"</td>\n\
                                        <td class='' style='text-align: right;'>"+value.pending_amount+"</td>\n\
                                        <td>\n\
                                            <input name='invoice_amount["+value.sales_invoice_id+"]' type='text' data-max_amt='"+value.pending_amount+"' class='num_only in_amount invoice_check_box pull-right' value='"+new_amt+"'>\n\
                                        </td>\n\
                                    </tr>";
                            });
                            $('#myModal_detail').modal('show');
                            $('.body_detail_table').html(row_html);
                            get_sum_selected();
                        }
                        return false;
                    },
                });
            }
    }
    
    function get_sum_selected(){
        var total_amt = 0;
        $('.body_detail_table .tr_row').each(function() {
            if(typeof $(this) === 'undefined'){ } else {
                var value_a = $(this).find("input").val();
                if(typeof value_a === 'undefined'){ } else {
                    if(value_a == ''){ } else {
                        if(parseInt($(this).find("input").val()) > parseInt($(this).find("input").data('max_amt'))){
                            show_notify('Amount can not greater than Pending Amount.', false);
                            $(this).find("input").val('');
                        } else {
                            total_amt = total_amt + parseInt($(this).find("input").val());
                        }
                    }
                }
            }
        });
        $('.foot').text(total_amt);
//        $('#amount').val(total_amt);
    }
</script>
