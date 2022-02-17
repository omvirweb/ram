<?php $this->load->view('success_false_notify'); ?>
<?php $segment2 = $this->uri->segment(2); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form id="form_transaction" class="" action="<?= base_url('transaction/save_sales_purchase_transaction') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="" >
                    <input type="hidden" name="invoice_type" id="invoice_type" value="<?=$invoice_type_value?>">
                    <?php if(isset($invoice_id)){?>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="<?=$invoice_id?>" >
                    <?php }?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title"><?= isset($transaction_data->transaction_id) ? 'Edit' : 'Add' ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transaction_date" class="control-label">Date <span class="required-sign">*</span></label>
                                        <input type="text" name="transaction_date" class="form-control input-datepicker" id="datepicker1" value="<?= isset($transaction_date)?date("d-m-Y",strtotime($transaction_date)):date('d-m-Y') ?>" autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount" class="control-label">Amount <span class="required-sign">*</span></label>
                                        <input type="text" name="amount" class="form-control num_only" id="amount" value="<?=isset($amount)?$amount:''?>">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="bank" id="bank">
                                        <label for="account_id" class="control-label">Account <span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account')?>"><i class="fa fa-plus"></i> Add Account</a>
                                        <select name="account_id" id="account_id" class="form-control select2"></select>
                                        <div class="clearfix"></div><br/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="" id="">
                                        <label for="against_account_id" class="control-label">Against Account<span class="required-sign">*</span></label>
                                        <select name="against_account_id" id="against_account_id" class="form-control select2"></select>
                                        <div class="clearfix"></div><br/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note" class="control-label">Note</label>
                                        <textarea name="note" class="form-control" id="note"><?=isset($note)?$note:''?></textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm form_btn module_save_btn" >Save</button>
                            <b style="color: #0974a7">Ctrl + S</b>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div>
<input type="hidden" value="0" id="is_edit">
<!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {
        initAjaxSelect2($("#account_id"), "<?= base_url('app/sp_account_select2_source') ?>");
        initAjaxSelect2($("#against_account_id"), "<?= base_url('app/bill_account_select2_source/'.$against_account_group_id) ?>");

        <?php if(isset($account_id)){?>
        setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/'.$account_id)?>");
        <?php }?>
        <?php if(isset($against_account_id)){?>
        setSelect2Value($("#against_account_id"),"<?=base_url('app/set_account_select2_val_by_id/'.$against_account_id)?>");
        <?php }?>

        $(document).on('keydown','#amount', function(e) {
            if (e.keyCode == 13) {
                $('#account_id').select2('open');
            }
        });

        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

        $('#against_account_id').on("select2:close", function(e) { 
            $("#note").focus('');
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

            if ($.trim($("#against_account_id").val()) == '') {
                show_notify('Please Select Against Account.', false);
                $("#bank_id").focus();
                return false;
            }
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('transaction/save_sales_purchase_transaction') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                dataType: 'json',
                success: function (res) {
                    <?php if(isset($invoice_id)){?>
                        var invoice_type = $("#invoice_type").val();
                        if(invoice_type == "<?=SALES_INVOICE_ITEM_ID?>") {
                        window.location.href = "<?=base_url('sales/invoice_list')?>";

                        } else if(invoice_type == "<?=PURCHASE_INVOICE_ITEM_ID?>") {
                        window.location.href = "<?=base_url('purchase/invoice_list')?>";
                        
                        } else if(invoice_type == "<?=CREDIT_NOTE_ITEM_ID?>") {
                        window.location.href = "<?=base_url('credit_note/list_page')?>";
                       
                        } else if(invoice_type == "<?=DEBIT_NOTE_ITEM_ID?>") {
                        window.location.href = "<?=base_url('debit_note/list_page')?>";
                        } else {
                        window.location.reload();        
                        
                        }
                    <?php } else {?>
                        window.location.reload();
                    <?php }?>
                    return false;
                },
            });
            return false;
        });
    });
</script>
