<?php $this->load->view('success_false_notify'); 
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
                                        <label for="from_date" class="control-label">From Date<span class="required-sign">*</span></label>
                                        <input type="text" name="from_date" class="form-control input-datepicker" id="datepicker1" value="">
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="to_date" class="control-label">To Date<span class="required-sign">*</span></label>
                                        <input type="text" name="to_date" class="form-control input-datepicker" id="datepicker2" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_for_id" class="control-label">Discount For<span class="required-sign">*</span></label>
                                        <select name="discount_for_id" id="discount_for_id" class="select2" required>
                                            <option value="1">Sales</option>
                                            <option value="2">Purchase</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?= base_url('account/account/') ?>"><i class="fa fa-plus"></i> Add Account</a>
                                        <select name="account_id[]" id="account_id" class="select2" ></select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
<!--                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_id" class="control-label">Item</label>
                                        <select name="item_id" id="item_id" class="item_id select2"></select>
                                    </div>
                                </div>-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br/>
                                        <button type="button" class="btn btn-primary item_group_btn">Item Group</button>
                                        <button type="button" class="btn btn-primary item_cat_btn">Item Category</button>
                                        <button type="button" class="btn btn-primary item_item_btn">Item</button>
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
<div id="myModal" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 95%;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item Group</h4>
            </div>
            <div class="modal-body">
                <div class="box-body table-responsive">
                    <table id="item_group_table" class="display table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Item Group</th>
                            <th>Discount(%)</th>
                            <th>Discount(%)Type 2</th>
                            <th id="item_group_rate_id">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="item_group_table_data"></tbody>
                </table>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="myModal_2" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 95%;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item Category</h4>
            </div>
            <div class="modal-body">
                <table id="item_category_table" class="table row-border table-bordered table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Item Category</th>
                            <th>Discount(%)</th>
                            <th>Discount(%)Type 2</th>
                            <th id="item_group_rate_id">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="item_category_table_data"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="myModal_3" class="modal fade" role="dialog" style="width:100%">
    <div class="modal-dialog" style="width: 95%;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item</h4>
            </div>
            <div class="modal-body">
                <table id="item_item_table" class="table row-border table-bordered table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Discount(%)</th>
                            <th>Discount(%)Type 2</th>
                            <th id="item_group_rate_id">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="item_item_table_data"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        var jsonObj = [];
        $(".dfselect2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        
         item_group_table = $('#item_group_table').DataTable({
            "serverSide": true,
            "scrollX": true,
            "scrollY": true,
            //"bAutoWidth": false,
            "scrollCollapse": true,
            "paging": false,

            "ajax": {
                "url": "<?php echo site_url('sales/item_group_discount_datatable/'); ?>",
                "type": "POST",
                "data": function (d) {
                },
            },
//            "columnDefs": [
//                {
//                    "className": "dt-right",
//                    "targets": [7],
//                },
//            ],
        });
//        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();

        
         item_category_table = $('#item_category_table').DataTable({
            "serverSide": true,
            "scrollX": true,
            "scrollY": true,
            //"bAutoWidth": false,
            "scrollCollapse": true,
            "paging": false,

            "ajax": {
                "url": "<?php echo site_url('sales/item_category_discount_datatable/'); ?>",
                "type": "POST",
                "data": function (d) {
                },
            },
//            "columnDefs": [
//                {
//                    "className": "dt-right",
//                    "targets": [7],
//                },
//            ],
        });
        
         item_item_table = $('#item_item_table').DataTable({
            "serverSide": true,
            "scrollX": true,
            "scrollY": true,
            //"bAutoWidth": false,
            "scrollCollapse": true,
            "paging": false,

            "ajax": {
                "url": "<?php echo site_url('sales/item_item_discount_datatable/'); ?>",
                "type": "POST",
                "data": function (d) {
                },
            },
//            "columnDefs": [
//                {
//                    "className": "dt-right",
//                    "targets": [7],
//                },
//            ],
        });
        
        $(document).on('click', '.item_group_btn', function () {
            $('#myModal').modal('show');
        });
        $(document).on('click', '.item_cat_btn', function () {
            $('#myModal_2').modal('show');
        });
        $(document).on('click', '.item_item_btn', function () {
            $('#myModal_3').modal('show');
        });
        
        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_select2_source_without_item_group/') ?>");
        initAjaxSelect2Mutiple($("#account_id"), "<?= base_url('app/account_select2_source') ?>");
//        initAjaxSelect2($("#item_group_id"), "<?= base_url('app/item_group_select2_source/') ?>");
        <?php if (isset($discount_data->account_id)) { ?>
                    setSelect2MultiValue($("#account_id"),"<?=base_url('app/set_account_id_select2_multi_val_by_id/'.$discount_data->account_id)?>");
        <?php } ?>
        <?php if (isset($discount_data->item_group_id) && !empty($discount_data->item_group_id)) { ?>
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
            if ($.trim($("#datepicker1").val()) == '') {
                show_notify('Please Enter From Date.', false);
                $("#datepicker1").focus();
                return false;
            }
            if ($.trim($("#datepicker2").val()) == '') {
                show_notify('Please Enter To Date.', false);
                $("#datepicker2").focus();
                return false;
            }
            if ($.trim($("#discount_for_id").val()) == '') {
                show_notify('Please Select Discount For.', false);
                $("#discount_for_id").focus();
                return false;
            }
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account.', false);
                $("#account_id").focus();
                return false;
            }
            item_group_table_data = [];
            $('.item_group_table_data tr').each(function() {
                in_arr = [];
                if(($.trim($(this).find(".item_group_dis_1").val()) !== '') || ($.trim($(this).find(".item_group_dis_2").val()) !== '') || ($.trim($(this).find(".item_group_dis_rate").val()) !== '')){
                    in_arr.push($(this).find(".item_group_dis_1").data("id"));
                    in_arr.push($(this).find(".item_group_dis_1").val());
                    in_arr.push($(this).find(".item_group_dis_2").val());
                    in_arr.push($(this).find(".item_group_dis_rate").val());
                    item_group_table_data.push(in_arr);
                }

            });
            item_category_table_data = [];
            $('.item_category_table_data tr').each(function() {
                in_arr = [];
                if(($.trim($(this).find(".item_category_dis_1").val()) !== '') || ($.trim($(this).find(".item_category_dis_2").val()) !== '') || ($.trim($(this).find(".item_category_dis_rate").val()) !== '')){
                    in_arr.push($(this).find(".item_category_dis_1").data("id"));
                    in_arr.push($(this).find(".item_category_dis_1").val());
                    in_arr.push($(this).find(".item_category_dis_2").val());
                    in_arr.push($(this).find(".item_category_dis_rate").val());
                    item_category_table_data.push(in_arr);
                }

            });
            item_item_table_data = [];
            $('.item_item_table_data tr').each(function() {
                in_arr = [];
                if(($.trim($(this).find(".item_item_dis_1").val()) !== '') || ($.trim($(this).find(".item_item_dis_2").val()) !== '') || ($.trim($(this).find(".item_item_dis_rate").val()) !== '')){
                    in_arr.push($(this).find(".item_item_dis_1").data("id"));
                    in_arr.push($(this).find(".item_item_dis_1").val());
                    in_arr.push($(this).find(".item_item_dis_2").val());
                    in_arr.push($(this).find(".item_item_dis_rate").val());
                    item_item_table_data.push(in_arr);
                }

            });
            
            console.log(item_group_table_data);
            console.log(item_category_table_data);
            console.log(item_item_table_data);
//            return false;
            
            var postData = new FormData(this);
            postData.append('item_group_table_data', JSON.stringify(item_group_table_data));
            postData.append('item_category_table_data', JSON.stringify(item_category_table_data));
            postData.append('item_item_table_data', JSON.stringify(item_item_table_data));
            $.ajax({
                url: "<?= base_url('sales/save_discount_new') ?>",
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
                        window.location.href = "<?php echo base_url('sales/discount_new/') ?>";
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('sales/discount_new') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
    });
</script>
