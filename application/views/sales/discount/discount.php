<?php $this->load->view('success_false_notify'); 
    $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <form id="form_discount" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
        <section class="content-header">
            <h1>
                Discount
                <?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"view")) { ?>
                <a href="<?= base_url('sales/discount_list'); ?>" class="btn btn-primary pull-right">Discount List</a> &nbsp;
                <?php } ?>                
                <button type="submit" class="btn btn-primary form_btn pull-right save"><?= isset($discount_data->sales_invoice_id) ? 'Update' : 'Save' ?></button> &nbsp;
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- START ALERTS AND CALLOUTS -->
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($discount_data->discount_id) && !empty($discount_data->discount_id)) { ?>
                        <input type="hidden" id="discount_id" name="discount_id" value="<?= $discount_data->discount_id; ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title">
                                <?= isset($discount_data->discount_id) ? 'Edit' : 'Add' ?>
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?= base_url('account/account/') ?>"><i class="fa fa-plus"></i> Add Account</a>
                                        <select name="account_id[]" id="account_id" class="select2" required></select>
                                    </div>
                                </div>
                                <?php if($li_item_group == '1'){ ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="item_group_id" class="control-label">Item Group<span class="required-sign">*</span></label>
                                            <select name="item_group_id" id="item_group_id" class="item_group_id select2" required></select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_id" class="control-label">Item</label>
                                        <select name="item_id" id="item_id" class="item_id select2"></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount" class="control-label">Discount<span class="required-sign">*</span></label>
                                        <input type="text" name="discount" id="discount" class="discount form-control num_only" required value="<?php echo isset($discount_data->discount) && !empty($discount_data->discount) ? $discount_data->discount : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn save"><?= isset($discount_data->discount_id) ? 'Update' : 'Save' ?></button>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- END ALERTS AND CALLOUTS -->
        </section>
    </form>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        var jsonObj = [];
        $(".dfselect2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        
        li_item_group = '<?php echo $li_item_group ?>';
        if(li_item_group == '0'){
            initAjaxSelect2($("#item_id"), "<?= base_url('app/item_select2_source_without_item_group/') ?>");
        }
        initAjaxSelect2Mutiple($("#account_id"), "<?= base_url('app/account_select2_source') ?>");
        initAjaxSelect2($("#item_group_id"), "<?= base_url('app/item_group_select2_source/') ?>");
        <?php if (isset($discount_data->account_id)) { ?>
                    setSelect2MultiValue($("#account_id"),"<?=base_url('app/set_account_id_select2_multi_val_by_id/'.$discount_data->account_id)?>");
        <?php } ?>
        <?php if (isset($discount_data->item_group_id) && !empty($discount_data->item_group_id)) { ?>
                    setSelect2Value($("#item_group_id"), "<?= base_url('app/set_item_group_select2_val_by_id/') ?>" + <?= $discount_data->item_group_id; ?>);
                    initAjaxSelect2($("#item_id"), "<?= base_url('app/item_select2_source_from_item_group/') ?>" + <?= $discount_data->item_group_id; ?>);
        <?php } ?>
            <?php if (isset($discount_data->item_id) && !empty($discount_data->item_id)) { ?>
                    setSelect2Value($("#item_id"), "<?= base_url('app/set_item_select2_val_by_id/') ?>" + <?= $discount_data->item_id; ?>);
        <?php } ?>

        shortcut.add("ctrl+s", function () {
            $(".save").click();
        });

        $(document).on('change', '#item_group_id', function () {
            var item_group = $('#item_group_id').val();
            $('#item_id').val(null).trigger('change');
            if(item_group != '' && item_group != null){
                initAjaxSelect2($("#item_id"), "<?= base_url('app/item_select2_source_from_item_group/') ?>" + item_group);
            } else {
                $('#item_id').val(null).trigger('change');
            }
        });

        $(document).on('submit', '#form_discount', function () {
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('sales/save_discount') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Exist') {
                            show_notify("Values Are Already Exist", false);
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('sales/discount/') ?>";
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('sales/discount_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
    });
</script>
