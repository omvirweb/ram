<?php $this->load->view('success_false_notify'); ?>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Sales Invoice From Quote <?php if(isset($sales_invoice_data->sales_invoice_id) && !empty($sales_invoice_data->sales_invoice_id)){ echo 'Edit'; }else{ echo 'Add'; }  ?>
            <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) { ?>
            <a href="<?=base_url('sales/sales_invoice_frmquot_list');?>" class="btn btn-primary pull-right">Sales Invoice From Quote List</a>
            <?php } ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_sales_quotation" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
                    <?php if(isset($sales_invoice_data->sales_invoice_id) && !empty($sales_invoice_data->sales_invoice_id)){ ?>
                    <input type="hidden" id="sales_invoice_id" name="sales_invoice_id" value="<?=$sales_invoice_data->sales_invoice_id;?>">
                    <?php } ?>
                    <!-- <input type="hidden" id="quotation_type" name="quotation_type" value="1"> -->

                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account/')?>"><i class="fa fa-plus"></i> Add Account</a>
                                        <select name="account_id" id="account_id" class="account_id" required data-index="1" ></select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="sales_invoice_date" class="control-label">Sales Invoice Date<span class="required-sign">*</span></label>
                                        <input type="text" style="width:200px;" name="sales_invoice_date" id="datepicker2" class="form-control" required data-index="2" value="<?=isset($sales_invoice_data->sales_invoice_date) ? date('d-m-Y', strtotime($sales_invoice_data->sales_invoice_date)) : date('d-m-Y'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="our_bank_label" class="control-label">Our Bank Label</label>
                                        <select name="our_bank_label" id="our_bank_label" class="form-control select2 our_bank_label" data-index="1"></select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tax_type" class="control-label">Tax Type<span class="required-sign">*</span></label>
                                        <select  name="tax_type" id="tax_type" class="form-control" data-index="3" required="">
                                            <option value="1" <?php echo isset($sales_invoice_data->tax_type) && $sales_invoice_data->tax_type == 1 ? 'selected' : ''?>>GST</option>
                                            <option value="2" <?php echo isset($sales_invoice_data->tax_type) && $sales_invoice_data->tax_type == 2 ? 'selected' : ''?>>IGST</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="invoice_type" class="control-label">Invoice Type<span class="required-sign">*</span></label>
                                        <select name="invoice_type" id="invoice_type" class="form-control select2" data-index="4"  >
                                            <option value='1' <?=(isset($sales_invoice_data->invoice_type) && $sales_invoice_data->invoice_type == 1)?'selected':''?>>Order</option>
                                            <option value='2' <?=(isset($sales_invoice_data->invoice_type) && $sales_invoice_data->invoice_type == 2)?'selected':''?>>Purchase</option>
                                            <option value='3' <?=(isset($sales_invoice_data->invoice_type) && $sales_invoice_data->invoice_type == 3)?'selected':''?>>Sales Order</option>
                                            <option value='4' <?=(isset($sales_invoice_data->invoice_type) && $sales_invoice_data->invoice_type == 4)?'selected':''?>>Material In</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sales_invoice_no" class="control-label">Invoice No</label>
                                        <input type="text" name="sales_invoice_no" id="sales_invoice_no" class="form-control num_only" data-index="4" value="<?php echo isset($sales_invoice_data->sales_invoice_no) && $sales_invoice_data->sales_invoice_no != '' ? $sales_invoice_data->sales_invoice_no : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="site_id" class="control-label">Site</label>
                                        <!-- <select name="line_items_data[site_id]" id="site_id" class="form-control select2"></select> -->
                                        <select name="site_id" id="site_id" class="form-control select2"></select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sales_rate_type" class="control-label">Rate :</label>
                                            <select name="sales_rate_type" id="sales_rate_type" class="select2">
                                                <option <?=(isset($sales_invoice_data->sales_rate_type) && $sales_invoice_data->sales_rate_type == 1) ?'selected' : ''; ?> value="1">Excluding GST</option>
                                                <option <?=(isset($sales_invoice_data->sales_rate_type) && $sales_invoice_data->sales_rate_type == 2) ?'selected' : ''; ?> value="2">Including GST</option>
                                            </select>
                                        </div>
                                    </div>
                                <div class="clearfix"></div>                                
                                
                                <div class="col-md-12">
                                    <div class="box-header with-border">
                                    </div>  
                                    <div class="row line_item_form">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <?php if(isset($quotation_lineitems)){ ?>
                                            <input type="hidden" name="line_items_data[id]" id="lineitem_id" />
                                        <?php } ?>

                                        <?php if(isset($quotation_line_item_fields) && in_array('separate', $quotation_line_item_fields)){ ?>
                                            <?php if(isset($quotation_line_item_fields) && in_array('item_group', $quotation_line_item_fields)) { ?>
                                                <div class="col-md-2 ">
                                                    <div class="form-group">
                                                        <label for="item_group_id" class="control-label">Item Group</label>
                                                        <select name="line_items_data[item_group_id]" id="item_group_id" class="select2" data-index="26"></select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="item_id" class="control-label">Item.</label>         
                                                <select name="line_items_data[item_id]" id="item_id" class="item_id" data-index="29"></select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="hsn" class="control-label">HSN </label>
                                                <input type="text" name="line_items_data[hsn]" id="hsn" class="hsn form-control">
                                            </div>
                                        </div> -->
                                        <div class="col-md-2">
                                            <div class="col-md-4 pr0">
                                                <div class="form-group">
                                                    <label for="l" class="control-label">L </label>
                                                    <input type="text" name="line_items_data[l]" id="l" class="l form-control item_detail num_only" data-index="30">
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr0">
                                                <div class="form-group">
                                                    <label for="b" class="control-label">B </label>
                                                    <input type="text" name="line_items_data[b]" id="b" class="b form-control item_detail num_only" data-index="30">
                                                </div>
                                            </div>
                                            <div class="col-md-4 pr0">
                                                <div class="form-group">
                                                    <label for="d" class="control-label">D </label>
                                                    <input type="text" name="line_items_data[d]" id="d" class="d form-control item_detail num_only" data-index="30">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="item_qty" class="control-label">Quantity </label>
                                                <input type="text" name="line_items_data[item_qty]" id="item_qty" class="item_qty form-control item_detail num_only" data-index="30">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0 <?php echo isset($line_item_fields['unit']) ? $line_item_fields['unit'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="unit" class="control-label">Unit</label>
                                                            <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/pack_unit')?>"><i class="fa fa-plus"></i></a>
                                                <select name="line_items_data[unit_id]" id="unit_id" class="unit_id" data-index="31"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0 <?php echo isset($line_item_fields['Rate']) ? $line_item_fields['Rate'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="price" class="control-label">Rate</label>
                                                <input type="text" name="line_items_data[price]" id="price" class="price form-control item_detail num_only" data-index="32">
                                                <input type="hidden" name="line_items_data[price_for_itax]" id="price_for_itax" class="price_for_itax form-control item_detail" >
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="gst_rate" class="control-label">GST %</label>
                                                <input type="text" name="line_items_data[gst_rate]" id="gst_rate" class="gst_rate form-control item_detail num_only" data-index="37">
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="site_id" class="control-label">Site</label>
                                                <select name="line_items_data[site_id]" id="site_id" class="form-control select2"></select>
                                            </div>
                                        </div> -->
                                        <div class="col-md-2 <?php echo isset($line_item_fields['amount']) ? $line_item_fields['amount'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="amount" class="control-label">Amount</label>
                                                <input type="text" name="line_items_data[amount]" id="amount" class="amount form-control item_detail" readonly data-index="44">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="button" id="add_lineitem" class="btn btn-primary add_lineitem" style="float: right;" value="Add" data-index="46"/>
                                        </div>
                                    </div>
                                </div>
                                <style>
                                    th, td { white-space: nowrap; }
                                    .fix_fcolumn { }
                                    .table-wrapper { overflow-x: scroll; width: auto;
                                    top:0;
                                    bottom:0;
                                    left:0;
                                    right:0; }
                                </style>
                                <div class="clearfix"></div>
                                <div class="col-md-12 container ">
                                    <div class="table-wrapper">
                                        <table class="table table-bordered table-striped table-hover" style="margin-bottom: 0px;">
                                            <thead>
                                                <tr>
                                                    <th class="fix_fcolumn">Total</th>
                                                    <?php if(isset($quotation_line_item_fields) && in_array('item_group', $quotation_line_item_fields)){ ?>
                                                        <th></th>
                                                    <?php } ?>
                                                    <th></th>
                                                    <th class="text-right"><span class="qty_total"></span><input type="hidden" name="qty_total" id="qty_total" /></th>
                                                    <!-- <th class="mrp"></th> -->
                                                    <th class="rate"></th>
                                                    <th class="text-right"><span class="pure_amount_total"></span><input type="hidden" name="pure_amount_total" id="pure_amount_total" /></th>
                                                    <!-- <th class="text-right"></th> -->
                                                    <!-- <th class="text-right"><span class="discounted_price_total"></span><input type="hidden" name="discounted_price_total" id="discounted_price_total" /></th> -->
                                                    <input type="hidden" name="discounted_price_total" id="discounted_price_total" />
                                                </tr>
                                                <tr>
                                                    <th class="fix_fcolumn" width="100px">Action</th>
                                                    <?php if(isset($quotation_line_item_fields) && in_array('item_group', $quotation_line_item_fields)){ ?>
                                                        <th>Item Group</th>
                                                    <?php } ?>
                                                    <th>Sr.No</th>
                                                    <th>Item</th>
                                                    <th class="text-right">Qty</th>
                                                    <th class="text-left">Unit</th>
                                                    <th class="text-right">Rate</th>
                                                    <th class="text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list"></tbody>
                                    </table>
                                </div>
                                <div class="table-wrapper" >
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-right">Round Off</th>
                                                <th class="text-right" style="padding: 0;width: 150px;">
                                                    <input type="text" name="round_off_amount" id="round_off_amount" class="form-control text-right" style="padding-right: 7px;">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Total Amount</th>
                                                <th class="fix_lcolumn text-right"><span class="amount_total_after_round_off"></span></th>
                                            </tr>
                                        </thead>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <br/>
                            <div class="form-group">
                                <label for="sales_invoice_desc" class="control-label">Description</label>
                                <textarea name="sales_invoice_desc" id="sales_invoice_desc" class="form-control" data-index="5" placeholder=""><?=isset($sales_invoice_data->sales_invoice_desc) ? $sales_invoice_data->sales_invoice_desc : '' ?></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn module_save_btn"><?=isset($sales_invoice_data->sales_invoice_id) ? 'Update' : 'Save' ?></button>
                                                        <b style="color: #0974a7">Ctrl + S</b>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- <script src="<?php echo base_url('assets/plugins/multifile-master/jquery.MultiFile.min.js');?>" type="text/javascript" language="javascript"></script> -->
<script src="<?php echo base_url('assets/plugins/multifile-master/jquery.MultiFile.js');?>" type="text/javascript" language="javascript"></script>
<!-- <script src="<?php echo base_url('assets/plugins/5x5jqpi.min.js');?>" type="text/javascript" language="javascript"></script> -->
<!-- <script src="<?php echo base_url('assets/plugins/test.js');?>" type="text/javascript" language="javascript"></script> -->

<script type="text/javascript">

$(document).on('keydown', function(event) {
       if (event.key == "Escape") {
            if (confirm('Are You Sure To Leave This Page Without Save Data')) {
                    return true;                
            } else {
                $( ".module_save_btn" ).click();
            }
       }
   });
    var lineitem_objectdata = [];
    var edit_lineitem_inc = 0;

    <?php if(isset($sales_invoice_lineitems)) { ?>
        var li_lineitem_objectdata = [<?php echo $sales_invoice_lineitems; ?>];
        var lineitem_objectdata = [];
        $.each(li_lineitem_objectdata, function (index, value) {
            value = JSON.parse(value);
            lineitem_objectdata.push(value);
        });
    <?php } ?>

    <?php if(isset($sales_invoice_data->round_off_amount)){ ?>
    display_lineitem_html(lineitem_objectdata,true);
    <?php } else { ?>
    display_lineitem_html(lineitem_objectdata);
    <?php } ?>
    $('#site_id').on('change', function() {
            var account_id = $('#account_id').val();
            var site_id = $('#site_id').val();
            if(account_id == null){
                    $("#item_id").val(null).trigger("change");
                    show_notify("Please select Account.", false);
                    return false;
            }
            if(site_id == null){
                    $("#item_id").val(null).trigger("change");
                    show_notify("Please select Site.", false);
                    return false;
            }
            initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_account_and_site_and_quatation/')?>" + account_id + "/" + site_id );   
        });

    $(document).ready(function(){
        initAjaxSelect2($("#site_id"), "<?= base_url('app/sites_select2_source') ?>");
        initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");
        initAjaxSelect2($("#unit_id"),"<?=base_url('app/unit_select2_source/')?>");
        initAjaxSelect2($("#our_bank_label"),"<?=base_url('app/our_bank_label_select2_source/')?>");

        <?php if(isset($sales_invoice_data->account_id)){ ?>
            setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/')?>" + <?=$sales_invoice_data->account_id; ?>);
        <?php } ?>
        <?php if(isset($sales_invoice_data->site_id)){ ?>
            setSelect2Value($("#site_id"),"<?=base_url('app/sites_group_select2_val_by_id/')?>" + <?=$sales_invoice_data->site_id; ?>);
            $('#site_id').trigger('change');
        <?php } ?>

        <?php if(isset($sales_invoice_data->our_bank_id)){ ?>
            setSelect2Value($("#our_bank_label"),"<?=base_url('app/set_our_bank_label_select2_val_by_id/')?>" + <?= $sales_invoice_data->our_bank_id; ?>);
        <?php } ?>

        // initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
        initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_account_and_site_and_quatation/')?>" );  
        initAjaxSelect2($("#item_group_id"),"<?=base_url('app/item_group_select2_source')?>");

        $('#item_qty,#price,#gst_rate').on('keyup', function() {
            var amount = 0;
            var qty = ($('#item_qty').val()) ? $('#item_qty').val() : 0;
            var rate = ($('#price').val()) ? $('#price').val() : 0;
            var gst = ($('#gst_rate').val()) ? $('#gst_rate').val() : 0;
            var amount = (qty * rate) + (qty * rate * gst / 100);
            $('#amount').val(parseFloat(amount).toFixed(2));
        });

        // $('#item_group_id').on('change', function() {
        //     var item_group_id = $(this).val();
        //     $('#item_id').val(null).trigger('change');
        //     if(item_group_id != '' || item_group_id != null){
        //         initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_item_group/')?>" + item_group_id);
        //     }
        //     if(item_group_id == '' || item_group_id == null){
        //         initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
        //     }
        // });

        $('#item_id').on('change', function() {
            var item_id = $('#item_id').val();

            if(edit_lineitem_inc == 0 && item_id != null){
                var account_id = $('#account_id').val();
                var site_id = $('#site_id').val();
                var item_group_id = $('#item_group_id').val();

                if(account_id == null){
                    $("#item_id").val(null).trigger("change");
                    show_notify("Please select Account.", false);
                    return false;
                }

                if(site_id == null){
                    $("#item_id").val(null).trigger("change");
                    show_notify("Please select Site.", false);
                    return false;
                }

                if(item_group_id == null){
                    item_group_id = '';
                }

                $.ajax({
                    url: "<?=base_url('app/get_item_detail') ?>",
                    type: "POST",
                    cache: false,
                    async: false,
                    dataType: 'json',
                    data: 'item_id='+ item_id + '&account_id='+ account_id + '&item_group_id='+ item_group_id,
                    success: function (response) {
                        $('#item_mrp').val(response.mrp);
                        $('#price').val(response.rate);
                        if(response.alternate_unit_id != ''){
                            setSelect2Value($("#unit_id"),"<?=base_url('app/set_unit_select2_val_by_id/')?>"+ response.alternate_unit_id); 
                        }
                    },
                });
            }
        });

        $('#item_id').on('change', function() {
            var item_id = $('#item_id').val();
            if (item_id) {
                $.ajax({
                    url: "<?=base_url('transaction/get_item_hsn_data') ?>",
                    type: "POST",
                    dataType: 'json',
                    data: {item_id: item_id},
                    success: function (response) {
                        $('#hsn').val(response.hsn);
                    },
                });
            } 
        });

        $(document).on('input','#round_off_amount',function () {
            var round_off_amount = parseFloat($("#round_off_amount").val()) || 0;
            var amount_total = parseFloat($("#discounted_price_total").val()) || 0;
            var amount_total_after_round_off = parseFloat(amount_total) + parseFloat(round_off_amount);
            $('.amount_total_after_round_off').html(parseF(amount_total_after_round_off));
        });
		
		$(document).on('input','#cgst',function () {
			var discounted_price = $("#discounted_price").val();
			var cgst = $("#cgst").val();
			var cgst_amt = (discounted_price * cgst)/100;
			$("#cgst_amt").val(parseF(cgst_amt));
			
			var sgst_amt = 0;
			if($("#sgst_amt").val()){ sgst_amt = $("#sgst_amt").val(); }
			var igst_amt = 0;
			if($("#igst_amt").val()){ igst_amt = $("#igst_amt").val(); }
			
			var amount = parseInt(discounted_price) + parseInt(parseF(cgst_amt)) + parseInt(parseF(sgst_amt)) + parseInt(parseF(igst_amt));
			$("#amount").val(parseF(amount));
		});
        
		$(document).on('input','#sgst',function () {
			var discounted_price = $("#discounted_price").val();
			var sgst = $("#sgst").val();
			var sgst_amt = (discounted_price * sgst)/100;
			$("#sgst_amt").val(parseF(sgst_amt));
			
			var cgst_amt = 0;
			if($("#cgst_amt").val()){ cgst_amt = $("#cgst_amt").val(); }
			var igst_amt = 0;
			if($("#igst_amt").val()){ igst_amt = $("#igst_amt").val(); }
			
			var amount = parseInt(discounted_price) + parseInt(parseF(cgst_amt)) + parseInt(parseF(sgst_amt)) + parseInt(parseF(igst_amt));
			$("#amount").val(parseF(amount));
		});
        
		$(document).on('input','#igst',function () {
			var discounted_price = $("#discounted_price").val();
			var igst = $("#igst").val();
			var igst_amt = (discounted_price * igst)/100;
			$("#igst_amt").val(parseF(igst_amt));
			
			var cgst_amt = 0;
			if($("#cgst_amt").val()){ cgst_amt = $("#cgst_amt").val(); }
			var sgst_amt = 0;
			if($("#sgst_amt").val()){ sgst_amt = $("#sgst_amt").val(); }
			
			var amount = parseInt(discounted_price) + parseInt(parseF(cgst_amt)) + parseInt(parseF(sgst_amt)) + parseInt(parseF(igst_amt));
			$("#amount").val(parseF(amount));
		});

        $(document).on('input','#item_qty',function () {
            input_qty_or_price();
        });

        $(document).on('input','#price',function () {
            input_qty_or_price();
        });

        $(document).on('input','#discount',function () {
            input_qty_or_price();
        });

        $('#add_lineitem').on('click', function() {
            var item_id = $("#item_id").val();
            if(item_id == '' || item_id == null){
                show_notify("Please select Product.", false);
                return false;
            }
            var item_qty = $("#item_qty").val();
            if(item_qty == '' || item_qty == null){
                show_notify("Please enter Product Qty.", false);
                return false;
            }
            
            var key = '';
            var value = '';
            var lineitem = {};
            $('select[name^="line_items_data"]').each(function(e) {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });

            $('input[name^="line_items_data"]').each(function() {
                key = $(this).attr('name');
                key = key.replace("line_items_data[", "");
                key = key.replace("]", "");
                value = $(this).val();
                lineitem[key] = value;
            });

            var item_price = $('#price').val();
            var item_qty = $('#item_qty').val();
            var pure_amount =  item_qty * item_price;

            var discount_type = $("#discount_type").val();
            var discount = $("#discount").val();
            var discount_amt = 0;
            if(discount_type == '1'){
                discount_amt = (parseF(pure_amount) * discount)/100;
            } else if(discount_type == '2'){
                discount_amt = parseF(discount);
            }
            discounted_price = parseFloat(pure_amount) - parseFloat(discount_amt);

            lineitem['discount_amt'] = parseF(discount_amt);
            lineitem['pure_amount'] = parseF(pure_amount);
            lineitem['discounted_price'] = parseF(discounted_price);

            var new_lineitem = JSON.parse(JSON.stringify(lineitem));
            var line_items_index = $("#line_items_index").val();
            if(line_items_index != ''){
                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
            } else {
                lineitem_objectdata.push(new_lineitem);
            }
            
            display_lineitem_html(lineitem_objectdata);
            $('#lineitem_id').val('');
            $('#form_sales_quotation').find('input[name^="line_items_index"], input[name^="line_items_data"], select[name^="line_items_data"], textarea[name^="line_items_data"]').val('');

            edit_lineitem_inc = 0;
            $("#item_group_id").val(null).trigger("change");
            $("#item_id").val(null).trigger("change");
            $("#item_qty").val('');
            $("#item_mrp").val('');
            $("#price").val('');
            $("#pure_amount").val('');
            $("#discount_type").val(1);
            $("#discount").val('');
            $("#discounted_price").val('');
            $("#line_items_index").val('');
        });
    });

    function input_qty_or_price(){
        var item_qty = $("#item_qty").val();
        var price = $("#price").val();
        var pure_amount = item_qty * price;
        $("#pure_amount").val(parseF(pure_amount));

        var discount_type = $("#discount_type").val();
        var discount = $("#discount").val();
        var discount_amt = 0;
        if(discount_type == '1'){
            discount_amt = (parseF(pure_amount) * discount)/100;
        } else if(discount_type == '2'){
            discount_amt = parseF(discount);
        }
        discounted_price = parseFloat(pure_amount) - parseFloat(discount_amt);

        $("#discounted_price").val(parseF(discounted_price));
    }

    function display_lineitem_html(lineitem_objectdata,is_use_db_round_off_amount){
        if(typeof(is_use_db_round_off_amount) != "undefined" && is_use_db_round_off_amount !== null) {
            var use_db_round_off_amount = true;
        } else {
            var use_db_round_off_amount = false;
        }
        var new_lineitem_html = '';
        var qty_total = 0;
        var pure_amount_total = 0;
        var discounted_price_total = 0;
        var amount_total = 0;
        var unit_name = '-';

        $.each(lineitem_objectdata, function (index, value) {    
            var value_item_group_name = '';
            if( value.item_group_id != ''){
                $.ajax({
                    url: "<?=base_url('app/set_item_group_select2_val_by_id/') ?>" + value.item_group_id,
                    type: "POST",
                    dataType: 'json',
                    async: false,
                    cache: false,
                    success: function (response) {
                        if(response.text == '--select--'){ response.text = ''; }
                        value_item_group_name = response.text;
                    },
                });
            }
            var value_item_name;
            $.ajax({
                url: "<?=base_url('app/set_li_item_select2_val_by_id/') ?>" + value.item_id,
                type: "POST",
                dataType: 'json',
                async: false,
                cache: false,
                success: function (response) {
                    if(response.text == '--select--'){ response.text = ''; }
                    value_item_name = response.item_name;
                },
            });

            $.ajax({
                url:"<?=base_url('app/set_unit_select2_val_by_id/')?>"+value.unit_id,
                type:'get',
                dataType:'json',
                async: false,
                cache: false,
                data : {
                },
                success:function(response){
                    if(response.success){
                        unit_name=response.text;
                    }else{
                        unit_name='-';
                    }
                }
            });

            item_name = value_item_name;

            var value_discount_type = '';
            if( value.discount_type == 1 ) { value_discount_type = 'Pct'; }
            if( value.discount_type == 2 ) { value_discount_type = 'Amt'; }
            if(value.discount == null || value.discount == '') {
                value.discount = 0;
            }
            var discount_data = value_discount_type + ' : ' + value.discount;

            var lineitem_edit_btn = '';
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="fix_fcolumn">' +
            lineitem_edit_btn +
            ' <a class="btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a> ' +
            '</td>' +

            <?php if(isset($quotation_line_item_fields) && in_array('item_group', $quotation_line_item_fields)){ ?>
            '<td>' + value_item_group_name + '</td>' + 
            <?php } ?>
            '<td>' + (index+1) + '</td>'+
            '<td>' + item_name + '</td>' +
            '<td class="text-right">' + value.item_qty + '</td>' +
            '<td>' + unit_name + '</td>' +
            '<td class="text-right">' + value.price + '</td>' +
            '<td class="text-right">' + value.pure_amount + '</td>' +
            '</tr>';

            new_lineitem_html += row_html;
            qty_total += parseInt(value.item_qty);
            pure_amount_total += parseFloat(value.pure_amount);
            discounted_price_total += parseFloat(value.discounted_price);
        });

        amount_total = discounted_price_total;

        $('thead span.qty_total').html(qty_total); $('#qty_total').val(qty_total);
        $('thead span.pure_amount_total').html(parseF(pure_amount_total)); 
        $('#pure_amount_total').val(pure_amount_total);
        $('thead span.discounted_price_total').html(parseF(discounted_price_total)); 
        $('#discounted_price_total').val(discounted_price_total);

        var amount_total_after_round_off =  Math.round(amount_total);
        if(use_db_round_off_amount == true) {
            <?php if(isset($sales_invoice_data->round_off_amount)){ ?>
                var round_off_amount = parseFloat(<?=$sales_invoice_data->round_off_amount;?>);
                var amount_total_after_round_off = parseFloat(amount_total) + parseFloat(round_off_amount);
            <?php } ?>
        } else {
            var round_off_amount = parseFloat(amount_total_after_round_off) - parseFloat(amount_total);
        }
        $("#round_off_amount").val(parseF(round_off_amount));
        $('.amount_total_after_round_off').html(parseF(amount_total_after_round_off));
        $('tbody#lineitem_list').html(new_lineitem_html);
    }
    
    function edit_lineitem(index){
        $('#ajax-loader').show();
        edit_lineitem_inc = 1;
        $("html, body").animate({ scrollTop: $(".line_item_form").offset().top }, "slow");
        
        value = lineitem_objectdata[index];
        $("#line_items_index").val(index);

        if(typeof(value.item_group_id) !== "undefined") {
            setSelect2Value($("#item_group_id"),"<?=base_url('app/set_item_group_select2_val_by_id/')?>"+ value.item_group_id); 
            // initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_item_group/')?>"+ value.item_group_id);
        }
        if(typeof(value.item_id) !== "undefined") {
            setSelect2Value($("#item_id"),"<?=base_url('app/set_li_item_select2_val_by_rafrence/?id=')?>" + value.item_id);
        }        

        if(typeof(value.id) != "undefined" && value.id !== null) {
            $("#lineitem_id").val(value.id);
        }

        $("#item_qty").val(value.item_qty);
        $("#price").val(value.price);
        if(value.item_mrp != null && value.item_mrp != 0) {
            $("#item_mrp").val(value.item_mrp);
        } else {
            $("#item_mrp").val('');
        }

        $("#item_code").val(value.item_code);
        $("#internal_code").val(value.internal_code);
        $("#hsn").val(value.hsn);
        $("#free_qty").val(value.free_qty);
        $("#no1").val(value.no1);
        $("#no2").val(value.no2);
        $("#net_rate").val(value.net_rate);
        $("#discount_2").val(value.discount_2);
        $("#cgst").val(value.cgst);
        $("#cgst_amt").val(value.cgst_amt);
        $("#sgst").val(value.sgst);
        $("#sgst_amt").val(value.sgst_amt);
        $("#igst").val(value.igst);
        $("#igst_amt").val(value.igst_amt);
        $("#other_charges").val(value.other_charges);
        $("#amount").val(value.amount);
        $("#pure_amount").val(value.pure_amount);
        $("#discount_type").val(value.discount_type);
        $("#discount").val(value.discount);
        $("#discounted_price").val(value.discounted_price);
        $('#ajax-loader').hide();
    }
    
    function remove_lineitem(index){
        if(confirm('Are you sure delete this item?')){
            value = lineitem_objectdata[index];
            if(typeof(value.id) != "undefined" && value.id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.id + '" />');
            }
            lineitem_objectdata.splice(index,1);
            display_lineitem_html(lineitem_objectdata);
        }
    }

    function parseF(value, decimal) {
        decimal = 2;
        return value ? parseFloat(value).toFixed(decimal) : 0;
    }

    var table;
    $(document).ready(function(){
        var jsonObj = [];
        $(".dfselect2").select2({
            width:"100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        
        
        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });
    

        $('#account_id').select2('open');
        $('#account_id').on("select2:close", function(e) { 
            $("#datepicker2").focus();
        });

        $('#item_id').on("select2:close", function(e) { 
            $("#item_qty").focus();
        });

        $(document).on('submit', '#form_sales_quotation', function () {
            if(lineitem_objectdata == ''){
                show_notify("Please select any one Product.", false);
                return false;
            }
            $('.overlay').show();
            var postData = new FormData(this);
            var lineitem_objectdata_var = JSON.stringify(lineitem_objectdata);
            // var docs = $('.multi')[0].files;
                postData.append('line_items_data', lineitem_objectdata_var);
                // postData.append('docs', docs); 
            $.ajax({
                url: "<?=base_url('sales/save_sales_invoice1') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Locked_Date'){
                        show_notify("Selected Date Locked, Please Choose Other Date.", false);
                        $('.overlay').hide();
                        return false;
                    }
                    if (json['success'] == 'Added'){
                        window.location.href = "<?php echo base_url('sales/sales_invoice_frmquot_add/'); ?>";
                    }
                    if (json['success'] == 'Updated'){
                        window.location.href = "<?php echo base_url('sales/sales_invoice_frmquot_list/'); ?>";
                    }
                    $('.overlay').hide();
                    return false;
                },
            });
            return false;
        });
        
        function get_max_prefix(prefix) {
        $.ajax({
            url: "<?=base_url('sales/get_invoice_no') ?>/" + prefix, 
            type: "GET",
            processData: false,
            contentType: false,
            cache: false,
            data: '',
            success: function (response) {
                var json = $.parseJSON(response);
                $('#sales_invoice_no').val(json);
                return false;
            },
        });
    }
    get_max_prefix('');
    });
</script>