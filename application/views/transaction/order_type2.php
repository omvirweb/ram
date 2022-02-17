<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Order Type 2

            <?php if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"view")) { ?>
            <a href="<?=base_url('purchase/order_type2_invoice_list');?>" class="btn btn-primary pull-right">Order List</a>
            <?php } ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_purchase_invoice" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
                    <?php if(isset($invoice_id) && !empty($invoice_id)){ ?>
                    <input type="hidden" id="invoice_id" name="invoice_id" value="<?=$invoice_id;?>">
                    <?php } ?>
                    <input type="hidden" id="invoice_type" name="invoice_type" value="3">

                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
                                        <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account/')?>"><i class="fa fa-plus"></i> Add Account</a>
                                        <select name="account_id" id="account_id" class="account_id" required data-index="1" ></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_date" class="control-label">Order Date<span class="required-sign">*</span></label>
                                        <input type="text" name="invoice_date" id="datepicker2" class="form-control" required data-index="2" value="<?=isset($invoice_data->invoice_date) ? date('d-m-Y', strtotime($invoice_data->invoice_date)) : date('d-m-Y'); ?>">
                                    </div>
                                </div>

                                <div class="clearfix"></div>                                
                                
                                <div class="col-md-12">
                                    <div class="box-header with-border">
                                    </div>  
                                    <div class="row line_item_form">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <?php if(isset($invoice_lineitems)){ ?>
                                            <input type="hidden" name="line_items_data[id]" id="lineitem_id" />
                                        <?php } ?>

                                        <?php if(isset($invoice_line_item_fields) && in_array('separate', $invoice_line_item_fields)){ ?>
                                            <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)) { ?>
                                                <div class="col-md-2 ">
                                                    <div class="form-group">
                                                        <label for="item_group_id" class="control-label">Item Group</label>
                                                        <select name="line_items_data[item_group_id]" id="item_group_id" class="select2" data-index="26"></select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="item_code" class="control-label">Item Code </label>
                                                <input type="text" name="line_items_data[item_code]" id="item_code" class="item_code form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="internal_code" class="control-label">Internal Code </label>
                                                <input type="text" name="line_items_data[internal_code]" id="internal_code" class="internal_code form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="item_id" class="control-label">Item.</label>         
                                                <select name="line_items_data[item_id]" id="item_id" class="item_id" data-index="29"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="hsn" class="control-label">HSN </label>
                                                <input type="text" name="line_items_data[hsn]" id="hsn" class="hsn form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="item_qty" class="control-label">Quantity </label>
                                                <input type="text" name="line_items_data[item_qty]" id="item_qty" class="item_qty form-control item_detail num_only" data-index="30">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="free_qty" class="control-label">Free Qty </label>
                                                <input type="text" name="line_items_data[free_qty]" id="free_qty" class="free_qty form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0 <?php echo isset($line_item_fields['unit']) ? $line_item_fields['unit'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="unit" class="control-label">Unit</label>
                                                            <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('master/pack_unit')?>"><i class="fa fa-plus"></i></a>
                                                <select name="line_items_data[unit_id]" id="unit_id" class="unit_id" data-index="31"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="no1" class="control-label">No. 1 </label>
                                                <input type="text" name="line_items_data[no1]" id="no1" class="no1 form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="no2" class="control-label">No. 2 </label>
                                                <input type="text" name="line_items_data[no2]" id="no2" class="no2 form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2 pr0 <?php echo isset($line_item_fields['Rate']) ? $line_item_fields['Rate'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="price" class="control-label">Rate</label>
                                                <input type="text" name="line_items_data[price]" id="price" class="price form-control item_detail num_only" data-index="32">
                                                <input type="hidden" name="line_items_data[price_for_itax]" id="price_for_itax" class="price_for_itax form-control item_detail" >
                                            </div>
                                        </div>
                                        <div class="col-md-2 pr0 ">
                                            <div class="form-group">
                                                <label for="pure_amount" class="control-label">Pure Amount </label>
                                                <input type="text" name="line_items_data[pure_amount]" id="pure_amount" class="pure_amount form-control item_detail num_only" data-index="33">
                                            </div>
                                        </div>
                                        <div class="col-md-4 <?php echo isset($line_item_fields['discount']) ? $line_item_fields['discount'] : ''; ?>">
                                            <div class="discount_fields" style="">
                                                <div class="col-md-4">
                                                    <label for="discount_type" class="control-label">Discount Type</label>
                                                    <select name="line_items_data[discount_type]" id="discount_type" class="discount_type form-control pull-left" data-index="34">
                                                            <option value="1">Pct</option>
                                                            <option value="2">Amt</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4" style="padding: 0px; padding-bottom: 10px;">
                                                    <label for="discount" class="control-label">Discount 1</label>
                                                    <input type="text" name="line_items_data[discount]" id="discount" class="discount form-control item_detail num_only" style="width: 65%;" value="0" data-index="35">
                                                </div>
                                                <div class="col-md-4" style="padding: 0px;">
                                                    <label for="discount_2" class="control-label">Discount 2</label>
                                                    <input type="text" name="line_items_data[discount_2]" id="discount_2" class="discount form-control item_detail num_only" style="width: 65%;" value="0" data-index="35">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pr0">
                                            <div class="form-group">
                                                <label for="net_rate" class="control-label">Net Rate </label>
                                                <input type="text" name="line_items_data[net_rate]" id="net_rate" class="net_rate form-control">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-2 pr0 <?php echo isset($line_item_fields['basic_amount']) ? $line_item_fields['basic_amount'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="discounted_price" class="control-label">Basic Amount</label>
                                                <input type="text" name="line_items_data[discounted_price]" id="discounted_price" class="discounted_price form-control item_detail" readonly data-index="36">
                                            </div>
                                        </div>
                                        <div class="cgst_class col-md-1 pr0 <?php echo isset($line_item_fields['cgst_per']) ? $line_item_fields['cgst_per'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="cgst" class="control-label">CGST %</label>
                                                <input type="text" name="line_items_data[cgst]" id="cgst" class="cgst form-control item_detail num_only" data-index="37">
                                            </div>
                                        </div>
                                        <div class="cgst_class_amt col-md-1 pr0 <?php echo isset($line_item_fields['cgst_amt']) ? $line_item_fields['cgst_amt'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="cgst_amt" class="control-label">Amt (Rs.)</label>
                                                <input type="text" name="line_items_data[cgst_amt]" id="cgst_amt" class="cgst_amt form-control item_detail" readonly data-index="38">
                                            </div>
                                        </div>
                                        <div class="sgst_class col-md-1 pr0 <?php echo isset($line_item_fields['sgst_per']) ? $line_item_fields['sgst_per'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="sgst" class="control-label">SGST %</label>
                                                <input type="text" name="line_items_data[sgst]" id="sgst" class="sgst form-control item_detail num_only" data-index="39">
                                            </div>
                                        </div>
                                        <div class="sgst_class_amt col-md-1 pr0 <?php echo isset($line_item_fields['sgst_amt']) ? $line_item_fields['sgst_amt'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="sgst_amt" class="control-label">Amt (Rs.)</label>
                                                <input type="text" name="line_items_data[sgst_amt]" id="sgst_amt" class="sgst_amt form-control item_detail" readonly data-index="40">
                                            </div>
                                        </div>
                                        <div class="igst_class col-md-1 pr0 <?php echo isset($line_item_fields['igst_per']) ? $line_item_fields['igst_per'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="igst" class="control-label">IGST %</label>
                                                <input type="text" name="line_items_data[igst]" id="igst" class="igst form-control item_detail num_only" data-index="41">
                                                <input type="hidden" name="line_items_data[igst_for_itax]" id="igst_for_itax" class="igst_for_itax form-control item_detail" >
                                            </div>
                                        </div>
                                        <div class="igst_class_amt col-md-1 pr0 <?php echo isset($line_item_fields['igst_amt']) ? $line_item_fields['igst_amt'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="igst_amt" class="control-label">Amt (Rs.)</label>
                                                <input type="text" name="line_items_data[igst_amt]" id="igst_amt" class="igst_amt form-control item_detail" readonly data-index="42">
                                            </div>
                                        </div>
                                        <div class="col-md-2 pr0 <?php echo isset($line_item_fields['other_charges']) ? $line_item_fields['other_charges'] : ''; ?>">
                                            <div class="form-group">
                                                <label for="other_charges" class="control-label">Other Charges</label>
                                                <input type="text" name="line_items_data[other_charges]" id="other_charges" class="other_charges form-control item_detail num_only" data-index="43">
                                            </div>
                                        </div>
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
                                                    <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
                                                        <th></th>
                                                    <?php } ?>
                                                    <th></th>
                                                    <th class="text-right"><span class="qty_total"></span><input type="hidden" name="qty_total" id="qty_total" /></th>
                                                    <th class="mrp"></th>
                                                    <th class="rate"></th>
                                                    <th class="text-right"><span class="pure_amount_total"></span><input type="hidden" name="pure_amount_total" id="pure_amount_total" /></th>
                                                    <th class="text-right"></th>
                                                    <th class="text-right"><span class="discounted_price_total"></span><input type="hidden" name="discounted_price_total" id="discounted_price_total" /></th>
                                                </tr>
                                                <tr>
                                                    <th class="fix_fcolumn" width="100px">Action</th>
                                                    <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
                                                        <th>Item Group</th>
                                                    <?php } ?>
                                                    <th>Item</th>
                                                    <th class="text-right">Qty</th>
                                                    <th class="text-right">MRP</th>
                                                    <th class="text-right">Rate</th>
                                                    <th class="text-right">Pure Amount</th>
                                                    <th class="text-right">Discount</th>
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
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn module_save_btn"><?=isset($invoice_id) ? 'Update' : 'Save' ?></button>
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
<script type="text/javascript">
    var lineitem_objectdata = [];
    var edit_lineitem_inc = 0;

    <?php if(isset($invoice_lineitems)) { ?>
        var li_lineitem_objectdata = [<?php echo $invoice_lineitems; ?>];
        var lineitem_objectdata = [];
        $.each(li_lineitem_objectdata, function (index, value) {
            value = JSON.parse(value);
            lineitem_objectdata.push(value);
        });
    <?php } ?>

    <?php if(isset($invoice_data->round_off_amount)){ ?>
    display_lineitem_html(lineitem_objectdata,true);
    <?php } else { ?>
    display_lineitem_html(lineitem_objectdata);
    <?php } ?>

    $(document).ready(function(){
        initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");
        <?php if(isset($invoice_data->account_id)){ ?>
            setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/')?>" + <?=$invoice_data->account_id; ?>);
        <?php } ?>

        initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
        initAjaxSelect2($("#item_group_id"),"<?=base_url('app/item_group_select2_source')?>");
        initAjaxSelect2($("#unit_id"),"<?=base_url('app/unit_select2_source_by_item_id/')?>");

        $('#item_group_id').on('change', function() {
            var item_group_id = $(this).val();
            $('#item_id').val(null).trigger('change');
            if(item_group_id != '' || item_group_id != null){
                initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_item_group/')?>" + item_group_id);
            }
            if(item_group_id == '' || item_group_id == null){
                initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
            }
        });

        $('#item_id').on('change', function() {
            var item_id = $('#item_id').val();

            if(edit_lineitem_inc == 0 && item_id != null){
                var account_id = $('#account_id').val();
                var item_group_id = $('#item_group_id').val();

                if(account_id == null){
                    $("#item_id").val(null).trigger("change");
                    show_notify("Please select Account.", false);
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
            $('#form_purchase_invoice').find('input[name^="line_items_index"], input[name^="line_items_data"], select[name^="line_items_data"], textarea[name^="line_items_data"]').val('');

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

            <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
            '<td>' + value_item_group_name + '</td>' + 
            <?php } ?>

            '<td>' + item_name + '</td>' +
            '<td class="text-right">' + value.item_qty + '</td>' +
            '<td class="text-right">' + (value.item_mrp != null && value.item_mrp != 0?value.item_mrp:'') + '</td>' +
            '<td class="text-right">' + value.price + '</td>' +
            '<td class="text-right">' + value.pure_amount + '</td>' +
            '<td class="text-right ">' + discount_data + '</td>' + 
            '<td class="text-right ">' + value.discounted_price + '</td></tr>';

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
            <?php if(isset($invoice_data->round_off_amount)){ ?>
                var round_off_amount = parseFloat(<?=$invoice_data->round_off_amount;?>);
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
            initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_item_group/')?>"+ value.item_group_id);
        }
        if(typeof(value.item_id) !== "undefined") {
            setSelect2Value($("#item_id"),"<?=base_url('app/set_li_item_select2_val_by_id/')?>" + value.item_id);
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
</script>
<script type="text/javascript">
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

        $(document).on('submit', '#form_purchase_invoice', function () {
            if(lineitem_objectdata == ''){
                show_notify("Please select any one Product.", false);
                return false;
            }
            $('.overlay').show();
            var postData = new FormData(this);
            var lineitem_objectdata_var = JSON.stringify(lineitem_objectdata);
                postData.append('line_items_data', lineitem_objectdata_var);
            $.ajax({
                url: "<?=base_url('transaction/save_order_type2') ?>",
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
                        window.location.href = "<?php echo base_url('transaction/order_type2'); ?>";
                    }
                    if (json['success'] == 'Updated'){
                        window.location.href = "<?php echo base_url('purchase/order_type2_invoice_list'); ?>";
                    }
                    $('.overlay').hide();
                    return false;
                },
            });
            return false;
        });
        
    });
</script>