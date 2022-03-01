<?php
    $this->load->view('success_false_notify');
    $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
    $li_category = isset($this->session->userdata()['li_category']) ? $this->session->userdata()['li_category'] : '0';
    $li_sub_category = isset($this->session->userdata()['li_sub_category']) ? $this->session->userdata()['li_sub_category'] : '0';
    $li_unit = isset($this->session->userdata()['li_unit']) ? $this->session->userdata()['li_unit'] : '0';
    $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
    $li_igst_per = isset($this->session->userdata()['li_igst_per']) ? $this->session->userdata()['li_igst_per'] : '0';
    $li_igst_amt = isset($this->session->userdata()['li_igst_amt']) ? $this->session->userdata()['li_igst_amt'] : '0';
    $li_cgst_per = isset($this->session->userdata()['li_cgst_per']) ? $this->session->userdata()['li_cgst_per'] : '0';
    $li_cgst_amt = isset($this->session->userdata()['li_cgst_amt']) ? $this->session->userdata()['li_cgst_amt'] : '0';
    $li_sgst_per = isset($this->session->userdata()['li_sgst_per']) ? $this->session->userdata()['li_sgst_per'] : '0';
    $li_sgst_amt = isset($this->session->userdata()['li_sgst_amt']) ? $this->session->userdata()['li_sgst_amt'] : '0';
    $li_short_name = isset($this->session->userdata()['li_short_name']) ? $this->session->userdata()['li_short_name'] : '0';
//    echo "<pre>"; print_r($this->session->userdata()); exit;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Item
            <?php if($this->applib->have_access_role(MASTER_ITEM_ID,"view")) { ?>
            <a href="<?= base_url('item/item_list'); ?>" class="btn btn-primary pull-right">Item List</a>
            <?php } ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_item" class="" action="" enctype="multipart/form-data" data-parsley-validate="" >
                    <?php if (isset($item_id) && !empty($item_id)) { ?>
                        <input type="hidden" id="item_id" name="item_id" value="<?= $item_id; ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title"><?= isset($item_id) ? 'Edit' : 'Add' ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="item_name" class="control-label">Item Name<span class="required-sign">*</span></label>
                                        <input type="text" name="item_name" class="form-control" id="item_name" value="<?= isset($item_name) ? $item_name : '' ?>" placeholder="" data-index="5" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="item_type_id" class="control-label">Item Type</label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?= base_url('master/item_type') ?>"><i class="fa fa-plus"></i> Add Item Type</a>
                                        <select name="item_type_id" id="item_type_id" class="item_type_id" data-index="1"></select>
                                    </div>
                                </div>
                                <?php if ($li_item_group == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="item_group_id" class="control-label">Item Group</label>
                                            <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?= base_url('master/item_group') ?>"><i class="fa fa-plus"></i> Add Item Group</a>
                                            <select name="item_group_id" id="item_group_id" class="item_group_id" data-index="2"></select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($li_category == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cat_id" class="control-label">Category</label>
                                            <a class="btn btn-primary btn-xs pull-right add_item_link" href="javascript:;" data-url= "<?= base_url('master/category/') ?>"><i class="fa fa-plus"></i></a>
                                            <select name="category_id" id="cat_id" class="cat_id" data-index="3" ></select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($li_sub_category == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sub_cat_id" class="control-label">Sub Category</label>
                                            <a class="btn btn-primary btn-xs pull-right add_item_link" href="javascript:;" data-url= "<?= base_url('master/sub_category/') ?>"><i class="fa fa-plus"></i></a>
                                            <select name="sub_category_id" id="sub_cat_id" class="sub_cat_id select2" data-index="4" ></select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($li_short_name == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shortname" class="control-label">Item Short Name</label>
                                            <input type="text" name="shortname" class="form-control" id="shortname" value="<?= isset($reorder_stock) ? $reorder_stock : '' ?>" data-index="6" placeholder="">
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($li_unit == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pack_unit_id" class="control-label">Unit</label>
                                            <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?= base_url('master/pack_unit') ?>"><i class="fa fa-plus"></i> Add Unit</a>
                                            <select name="pack_unit_id" id="pack_unit_id" class="pack_unit_id" data-index="7" ></select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="alternate_unit_id" class="control-label">Alternate Unit</label>
                                        <select name="alternate_unit_id" id="alternate_unit_id" class="alternate_unit_id" data-index="8" ></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hsn_code" class="control-label">HSN / SAC</label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?= base_url('master/hsn') ?>"><i class="fa fa-plus"></i> Add HSN / SAC</a>
                                        <select id="hsn_code" name="hsn_code" class="select2" data-index="9">
                                            <?php //foreach ($hsn as $hsc_code) { ?>
                                                <!--<option value="<?php echo $hsc_code->hsn_id; ?>"><?php echo $hsc_code->hsn ?></option>-->
                                            <?php //} ?>
                                        </select>
                                        <!--<input type="text" name="hsn_code" class="form-control" id="hsn_code" value="<?= isset($hsn_code) ? $hsn_code : '' ?>" placeholder="">-->
                                    </div>
                                </div>
                                <?php if ($li_igst_per == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="igst_per" class="control-label">IGST Percentage</label>
                                            <input type="text" name="igst_per" class="form-control" id="igst_per" value="<?= isset($igst_per) ? $igst_per : '' ?>" data-index="10" placeholder="">
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($li_cgst_per == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cgst_per" class="control-label">CGST Percentage</label>
                                            <input type="text" name="cgst_per" class="form-control" id="cgst_per" value="<?= isset($cgst_per) ? $cgst_per : '' ?>" data-index="11" placeholder="">
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($li_sgst_per == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sgst_per" class="control-label">SGST Percentage</label>
                                            <input type="text" name="sgst_per" class="form-control" id="sgst_per" value="<?= isset($sgst_per) ? $sgst_per : '' ?>" data-index="12" placeholder="">
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="list_price" class="control-label">List Price</label>
                                        <input type="text" name="list_price" class="form-control num_only" id="list_price" value="<?= isset($list_price) ? $list_price : '' ?>" data-index="13" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mrp" class="control-label">MRP</label>
                                        <input type="text" name="mrp" class="form-control num_only" id="mrp" value="<?= isset($mrp) ? $mrp : '' ?>" data-index="14" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="discount_on" class="control-label">Discount On</label>
                                        <select name="discount_on" id="discount_on" class="selectSearch" data-index="15" >
                                            <option value="1" <?php echo (isset($edit_discount_on) && $edit_discount_on == 1) ? 'selected' : '' ?>>List Price</option>
                                            <option value="2" <?php echo isset($edit_discount_on) ? ($edit_discount_on == 2) ? 'selected' : '' : 'selected' ?>>MRP</option>
                                        </select>
                                    </div>
                                </div>
                                <?php if ($li_discount == 1) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="discount_per" class="control-label">Discount Percentage</label>
                                            <input type="text" name="discount_per" class="form-control num_only" id="discount_per" value="<?= isset($discount_per) ? $discount_per : '' ?>" data-index="16" placeholder="">
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="opening_qty" class="control-label">Opening Qty</label>
                                        <input type="text" name="opening_qty" class="form-control num_only" id="opening_qty" value="<?= isset($opening_qty) ? $opening_qty : '' ?>" data-index="17" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="opening_amount" class="control-label">Opening Amount</label>
                                        <input type="text" name="opening_amount" class="form-control num_only" id="opening_amount" value="<?= isset($opening_amount) ? $opening_amount : '' ?>" data-index="18" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cess" class="control-label">Cess</label>
                                        <input type="text" name="cess" class="form-control" id="cess" value="<?= isset($cess) ? $cess : '' ?>" data-index="19" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_rate" class="control-label">Purchase Rate</label>
                                        <select name="purchase_rate" id="purchase_rate" class="selectSearch" data-index="20" >
                                            <option value="1" <?php echo (isset($edit_purchase_rate) && $edit_purchase_rate == 1) ? 'selected' : '' ?>>Including Tax</option>
                                            <option value="2" <?php echo isset($edit_purchase_rate) ? ($edit_purchase_rate == 2) ? 'selected' : '' : 'selected' ?>>Excluding Tax</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sales_rate" class="control-label">Sales Rate</label>
                                        <select name="sales_rate" id="sales_rate" class="selectSearch" data-index="21" >
                                            <option value="1" <?php echo isset($edit_sales_rate) ? ($edit_sales_rate == 1) ? 'selected' : '' : 'selected' ?>>Including Tax</option>
                                            <option value="2" <?php echo (isset($edit_sales_rate) && $edit_sales_rate == 2) ? 'selected' : ''; ?>>Excluding Tax</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_rate" class="control-label">Purchase Rate Value</label>
                                        <input type="text" name="purchase_rate_val" class="form-control num_only" id="purchase_rate_val" value="<?= isset($purchase_rate_val) ? $purchase_rate_val : '' ?>" data-index="22" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sales_rate_val" class="control-label">Sales Rate Value</label>
                                        <input type="text" name="sales_rate_val" class="form-control num_only" id="sales_rate_val" value="<?= isset($sales_rate_val) ? $sales_rate_val : '' ?>" data-index="23" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="minimum" class="control-label">Minimum</label>
                                        <input type="text" name="minimum" class="form-control num_only" id="minimum" value="<?= isset($minimum) ? $minimum : '' ?>" data-index="24" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="maximum" class="control-label">Maximum</label>
                                        <input type="text" name="maximum" class="form-control num_only" id="maximum" value="<?= isset($maximum) ? $maximum : '' ?>" data-index="25" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reorder_stock" class="control-label">Reorder Stock</label>
                                        <input type="text" name="reorder_stock" class="form-control num_only" id="reorder_stock" value="<?= isset($reorder_stock) ? $reorder_stock : '' ?>" data-index="26" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_id" class="control-label">Company</label>
                                        <select name="company_id" id="company_id" class="company_id" data-index="27" ></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="group_id" class="control-label">Group</label>
                                        <select name="group_id" id="group_id" class="group_id" data-index="28" ></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="item_code" class="control-label">Item Code</label>
                                        <input type="text" name="item_code" class="form-control" id="item_code" value="<?= isset($item_code) ? $item_code : '' ?>" data-index="29" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="internal_code" class="control-label">Internal Code</label>
                                        <input type="text" name="internal_code" class="form-control" id="internal_code" value="<?= isset($internal_code) ? $internal_code : '' ?>" data-index="30" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="desc" class="control-label">Description</label>
                                        <textarea name="desc" class="form-control" id="desc" data-index="31" placeholder=""><?= isset($item_desc) ? $item_desc : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exempted_from_gst" class="control-label">Exempted from GST?</label>
                                        <input type="checkbox" name="exempted_from_gst" class="" id="exempted_from_gst" value="" <?= (isset($exempted_from_gst)) ? $exempted_from_gst == '1' ? 'checked' : '' : '' ?> data-index="32" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn module_save_btn"><?= isset($item_id) ? 'Update' : 'Save' ?></button>
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
    var table;
    $(document).ready(function () {
        <?php if (!isset($item_id) && empty($item_id)) { ?>
            $(".select2").select2({
                width: "100%",
                placeholder: " --Select-- ",
                allowClear: true,
            });
        <?php } ?>
        initAjaxSelect2($("#item_type_id"), "<?= base_url('app/item_type_select2_source') ?>");
        initAjaxSelect2($("#hsn_code"), "<?= base_url('app/hsn_select2_source') ?>");
        initAjaxSelect2($("#item_group_id"), "<?= base_url('app/item_group_select2_source') ?>");
        initAjaxSelect2($("#pack_unit_id"), "<?= base_url('app/pack_unit_select2_source') ?>");
        initAjaxSelect2($("#alternate_unit_id"), "<?= base_url('app/pack_unit_select2_source') ?>");
        initAjaxSelect2($("#cat_id"),"<?=base_url('app/category_select2_source')?>");
        initAjaxSelect2($("#company_id"), "<?= base_url('app/company_select2_source') ?>");
        initAjaxSelect2($("#group_id"), "<?= base_url('app/group_select2_source') ?>");

        <?php if(isset($category_id) && !empty($category_id)){ ?>
            setSelect2Value($("#cat_id"),"<?=base_url('app/set_category_select2_val_by_id/'.$category_id)?>");
            get_sub_category();
        <?php } ?>
        <?php if(isset($sub_category_id) && !empty($sub_category_id)){ ?>
            setSelect2Value($("#sub_cat_id"),"<?=base_url('app/set_sub_category_select2_val_by_id/'.$sub_category_id)?>");
        <?php } ?>

        <?php if (isset($item_type_id) && !empty($item_type_id)) { ?>
            setSelect2Value($("#item_type_id"), "<?= base_url('app/set_item_type_select2_val_by_id/' . $item_type_id) ?>");
        <?php } ?>
        <?php if (isset($hsn_code) && !empty($hsn_code)) { ?>
            setSelect2Value($("#hsn_code"), "<?= base_url('app/set_hsn_select2_val_by_id/' . $hsn_code) ?>");
        <?php } ?>
        <?php if (isset($item_group_id) && !empty($item_group_id)) { ?>
            setSelect2Value($("#item_group_id"), "<?= base_url('app/set_item_group_select2_val_by_id/' . $item_group_id) ?>");
        <?php } ?>
        <?php if (isset($pack_unit_id) && !empty($pack_unit_id)) { ?>
            setSelect2Value($("#pack_unit_id"), "<?= base_url('app/set_pack_unit_select2_val_by_id/' . $pack_unit_id) ?>");
        <?php } ?>
        <?php if (isset($alternate_unit_id) && !empty($alternate_unit_id)) { ?>
            setSelect2Value($("#alternate_unit_id"), "<?= base_url('app/set_pack_unit_select2_val_by_id/' . $alternate_unit_id) ?>");
        <?php } ?>
        <?php if (isset($company_id) && !empty($company_id)) { ?>
            setSelect2Value($("#company_id"), "<?= base_url('app/set_company_select2_val_by_id/' . $company_id) ?>");
        <?php } ?>
        <?php if (isset($group_id) && !empty($group_id)) { ?>
            setSelect2Value($("#group_id"), "<?= base_url('app/set_group_select2_val_by_id/' . $group_id) ?>");
        <?php } ?>

        $(document).on('input', '#igst_per', function () {
            var igst = $("#igst_per").val();
            var c_s_per = igst / 2;
            $("#cgst_per").val(c_s_per);
            $("#sgst_per").val(c_s_per);
            //$("#sgst_amt").val(parseF(c_s_per));
        });
        
        $('#cat_id').on('change', function() {
            get_sub_category();
        });
        
        shortcut.add("ctrl+s", function () {
            $(".module_save_btn").click();
        });

        $('#item_name').focus();
        $(document).on('change', '#item_group_id', function () {
            var item_group_id = $('#item_group_id').val();
            $.ajax({
                url: "<?=base_url('item/item_group_discount_on') ?>",
                type: "POST",
                data: {item_group_id : item_group_id},
                success: function (response) {
                    var json = $.parseJSON(response);
                    $('#discount_on').val(json.discount_on).trigger('change');
                    return false;
                },
            });
        });
        $(document).on('submit', '#form_item', function () {
            var company_name = $('#plant_name').val();
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('item/save_item') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                fileElementId: 'item_image',
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);

                    if (json['Uploaderror'] != null) {
                        //show_notify(json['Uploaderror'],false);
                        //return false;
                    }
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('item/item') ?>";
                    }
                    if (json['error'] == 'errorAdded') {
                        show_notify('Some error has occurred !', false);
                        return false;
                    }
                    if (json['error'] == 'itemExist') {
                        show_notify('Product Already Exist !', false);
                        return false;
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('item/item_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });

    });
    
    function get_sub_category(){
        $('#sub_cat_id').val(null).trigger('change');
        var cat_id = $('#cat_id').val();
        if(cat_id != '' || cat_id != null){
            initAjaxSelect2($("#sub_cat_id"),"<?= base_url('app/sub_category_select2_source/') ?>" + cat_id);
        } else {
            $('#sub_cat_id').val(null).trigger('change');
        }
    }
</script>
