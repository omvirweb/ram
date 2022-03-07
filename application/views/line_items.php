<style>
    .none{
        display: none;
    }
</style>
<?php
	$is_view_item_history = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_view_item_history'];

	$segment1 = $this->uri->segment(1);
	$segment2 = $this->uri->segment(2);
	$segment3 = $this->uri->segment(3);
        $single_line_item_fields = array('item_group' , 'unit', 'discount', 'basic_amount' , 'cgst_per' , 'cgst_amt' , 'sgst_per' , 'sgst_amt'  , 'igst_per' , 'igst_amt' , 'other_charges' , 'amount' ,'note');
        $line_item_fields = array('item_group' => 'Show' , 'unit' => 'Show', 'discount' => 'Show', 'basic_amount' => 'Show' , 'cgst_per' => 'Show' , 'cgst_amt' => 'Show' , 'sgst_per' => 'Show' , 'sgst_amt' => 'Show'  , 'igst_per' => 'Show' , 'igst_amt' => 'Show' , 'other_charges' => 'Show' , 'amount' => 'Show' ,'note' => 'Show');
        if(isset($invoice_line_item_fields) && !empty($invoice_line_item_fields)) {
//            echo '<pre>'; print_r($invoice_line_item_fields); exit;
            foreach ($single_line_item_fields as $value) {
                if(!in_array($value, $invoice_line_item_fields)) {
                    $line_item_fields[$value] = 'none';
                }
            }
        }
        //$separate = 
?>
<div class="row line_item_form">
	<input type="hidden" name="line_items_index" id="line_items_index" />
	<input type="hidden" name="account_state" id="account_state" />
	<input type="hidden" name="user_state" id="user_state" />
	<?php if(isset($purchase_invoice_lineitems) || isset($sales_invoice_lineitems) || isset($credit_note_lineitems) || isset($debit_note_lineitems)){ ?>
		<input type="hidden" name="line_items_data[id]" id="lineitem_id" />
	<?php } ?>
	<input type="hidden" name="line_items_data[rate_for_itax]" id="rate_for_itax" />
    <?php if(isset($invoice_line_item_fields) && in_array('separate', $invoice_line_item_fields)){ ?>
        <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
            <div class="col-md-2 pr0">
                <div class="form-group">
                    <label for="item_group_id" class="control-label">Item Group</label>
                    <a class="btn btn-primary btn-xs pull-right add_item_link" href="javascript:;" data-url= "<?=base_url('master/item_group/')?>"><i class="fa fa-plus"></i></a>
                    <select name="line_items_data[item_group_id]" id="item_group_id" class="select2" data-index="26"></select>
                </div>
            </div>
        <?php }?>
        <?php if(isset($invoice_line_item_fields) && in_array('category', $invoice_line_item_fields)){ ?>
            <div class="col-md-2 pr0">
                <div class="form-group">
                    <label for="cat_id" class="control-label">Category</label>
                    <a class="btn btn-primary btn-xs pull-right add_item_link" href="javascript:;" data-url= "<?=base_url('master/category/')?>"><i class="fa fa-plus"></i></a>
                    <select name="line_items_data[cat_id]" id="cat_id" class="cat_id select2" data-index="27"></select>
                </div>
            </div>
        <?php } ?>
        <?php if(isset($invoice_line_item_fields) && in_array('sub_category', $invoice_line_item_fields)){ ?>
        <div class="col-md-2 pr0">
                    <div class="form-group">
                            <label for="sub_cat_id" class="control-label">Sub Category</label>
                            <a class="btn btn-primary btn-xs pull-right add_item_link" href="javascript:;" data-url= "<?=base_url('master/sub_category/')?>"><i class="fa fa-plus"></i></a>
                            <select name="line_items_data[sub_cat_id]" id="sub_cat_id" class="sub_cat_id select2" data-index="28"></select>
                    </div>
            </div>
        <?php } ?>
    <?php } else {
    	if(isset($invoice_line_item_fields)){
    		foreach ($invoice_line_item_fields as $tmpkey => $tmpvalue) {
	    		if(in_array($tmpvalue,array("item_group","category","sub_category"))) {
	    			unset($invoice_line_item_fields[$tmpkey]);
	    		}
	    	}
    	}
    } ?>

	<div class="col-md-3 pr0">
		<div class="form-group">
			<label for="item_id" class="control-label">Item.</label>
			<a class="btn btn-primary btn-xs pull-right add_item_link" href="javascript:;" data-url= "<?=base_url('item/item/')?>"><i class="fa fa-plus"></i></a>
			<?php if($segment3 == 'sales'):  ?>
			<a class="btn btn-primary btn-xs pull-right sub_qty_setting" href="javascript:void(0);" style="margin-right: 5px;"><i class="fa fa-list"></i></a>
			<?php endif;?>	
			<?php if($is_view_item_history == 1 && ($segment3 == 'sales' || $segment3 == 'purchase')):  ?>
			<a class="btn btn-primary btn-xs pull-right" id="btn_view_item_history" href="javascript:void(0);" style="margin-right: 5px;" title="View History"><i class="fa fa-clock-o"></i></a>
			<?php endif;?>			
			<select name="line_items_data[item_id]" id="item_id" class="item_id" data-index="29"></select>
		</div>
	</div>
    <?php if($voucher_type != "material_in") { ?>
	<div class="col-md-1 pr0">
		<div class="form-group">
			<label for="hsn" class="control-label">HSN </label>
			<input type="text" name="line_items_data[hsn]" id="hsn" class="hsn form-control">
		</div>
	</div>
    <?php } ?>
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
    <?php if($voucher_type != "material_in") { ?> 
	<div class="col-md-1 pr0">
		<div class="form-group">
			<label for="gst_rate" class="control-label">GST %</label>
			<input type="text" name="line_items_data[gst_rate]" id="gst_rate" class="gst_rate form-control item_detail num_only" data-index="37">
		</div>
	</div>
    <?php } ?>
    <div class="col-md-2">
        <div class="form-group">
            <label for="site_id" class="control-label">Site</label>
            <select name="line_items_data[site_id]" id="site_id" class="form-control select2"></select>
        </div>
    </div>
	<div class="col-md-2 <?php echo isset($line_item_fields['amount']) ? $line_item_fields['amount'] : ''; ?>">
		<div class="form-group">
			<label for="amount" class="control-label">Amount</label>
			<input type="text" name="line_items_data[amount]" id="amount" class="amount form-control item_detail" readonly data-index="44">
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="col-md-6 pr0 <?php echo isset($line_item_fields['note']) ? $line_item_fields['note'] : ''; ?>">
		<div class="form-group">
			<?php if($segment1 == 'sales' && $segment2 == 'invoice'):  ?>
			<label for="igst_amt" class="control-label">Note </label>
			<input type="text" name="line_items_data[note]" id="note" class="note form-control note" data-index="45">
			<?php endif;?>
		</div>
	</div>
	<div class="col-md-12">
		<div class="form-group">
			<input type="button" id="add_lineitem" class="btn btn-primary pull-right add_lineitem" value="Save Product Line" data-index="46"/>
		</div>
	</div>
</div><br />
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
            <div class="">
                    <table class="table table-bordered table-striped table-hover" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th class="fix_fcolumn" width="100px">Action</th>
                                    <th width="70px">Sr. No.</th>
                                    <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
                                        <th>Item Group</th>
                                    <?php } ?>
                                    <?php if(isset($invoice_line_item_fields) && in_array('category', $invoice_line_item_fields)){ ?>
                                        <th>Category</th>
                                    <?php } ?>
                                    <?php if(isset($invoice_line_item_fields) && in_array('sub_category', $invoice_line_item_fields)){ ?>
                                        <th>Sub Category</th>
                                    <?php } ?>
                                    <th>Item</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right <?php echo isset($line_item_fields['unit']) ? $line_item_fields['unit'] : ''; ?>">Unit</th>
                                    <th class="text-right <?php echo isset($line_item_fields['Rate']) ? $line_item_fields['Rate'] : ''; ?>">Rate</th>
                                    <th class="fix_lcolumn text-right <?php echo isset($line_item_fields['amount']) ? $line_item_fields['amount'] : ''; ?>" style="width: 150px;">Amount</th>
                                </tr>
                            </thead>

                            <tbody id="lineitem_list"></tbody>
                            <tfoot>
                                <tr>
                                    <th class="fix_fcolumn">Total</th>
                                    <th></th>
                                    <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
                                        <th></th>
                                    <?php } ?>
                                    <?php if(isset($invoice_line_item_fields) && in_array('category', $invoice_line_item_fields)){ ?>
                                        <th></th>
                                    <?php } ?>
                                    <?php if(isset($invoice_line_item_fields) && in_array('sub_category', $invoice_line_item_fields)){ ?>
                                        <th></th>
                                    <?php } ?>
                                    <th></th>
                                    <th class="text-right"><span class="qty_total"></span><input type="hidden" name="qty_total" id="qty_total" /></th>
                                    <th class="<?php echo isset($line_item_fields['unit']) ? $line_item_fields['unit'] : ''; ?>"></th>
                                    <th class="<?php echo isset($line_item_fields['Rate']) ? $line_item_fields['Rate'] : ''; ?>"></th>
                                    <th class="fix_lcolumn text-right <?php echo isset($line_item_fields['amount']) ? $line_item_fields['amount'] : ''; ?>"><span class="amount_total"></span><input type="hidden" name="amount_total" id="amount_total" /></th>
                                </tr>
                            </tfoot>
                    </table>
                    <table class="table table-bordered table-striped table-hover">
                    	<thead>
                            <?php if($voucher_type != "material_in") { ?>
                    		<tr>
                    			<th class="text-right">Round Off</th>
                                <th class="text-right" style="padding: 0;width: 150px;">
                                	<input type="text" name="round_off_amount" id="round_off_amount" class="form-control text-right" style="padding-right: 7px;">
                                </th>
                    		</tr>
                            <?php } ?>
                    		<tr>
                    			<th class="text-right">Total Amount</th>
                    			<th class="fix_lcolumn text-right" style="width: 150px;"><span class="amount_total_after_round_off"></span></th>
                    		</tr>
                    	</thead>
                    </table>
            </div>
    </div>
</div>
<div id="sub_item_modal" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Sub Items <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
            </div>
            <div class="modal-body edit-content">
                <div class="sub_line_item_form sub_item_fields_div">
                    <div class="col-md-4 pull-right">
                        <label for="apply_to_master">Apply To Master</label>
                        <input type="checkbox" name="sub_line_items_data[apply_to_master]" id="apply_to_master" style="height: 18px; width:18px;">
                    </div>
                    <br/>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sub_item_level">Level<span class="required-sign">*</span></label>
                            <input type="text" name="sub_line_items_data[sub_item_level]" id="sub_item_level" class="form-control num_only" value="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_qty">Qty<span class="required-sign">*</span></label>
                            <input type="text" name="sub_line_items_data[sub_item_qty]" id="sub_item_qty" class="form-control num_only" value="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_unit_id">Unit<span class="required-sign">*</span></label>
                            <select name="sub_line_items_data[sub_item_unit_id]" id="sub_item_unit_id"></select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sub_item_id">Sub Item<span class="required-sign">*</span></label>
                            <select name="sub_line_items_data[sub_item_id]" id="sub_item_id" ></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sub_item_add_less">Add/Less<span class="required-sign">*</span></label>
                            <select name="sub_line_items_data[sub_item_add_less]" id="sub_item_add_less" class="form-control">
                            	<option value="<?=QTY_ADD_ID?>">Add</option>
                            	<option value="<?=QTY_LESS_ID?>" selected="">Less</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sub_item_qty">Qty<span class="required-sign">*</span></label>
                            <input type="text" name="sub_line_items_data[sub_sub_item_qty]" id="sub_sub_item_qty" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sub_item_unit_id">Unit<span class="required-sign">*</span></label>
                            <select name="sub_line_items_data[sub_sub_item_unit_id]" id="sub_sub_item_unit_id"></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <input type="button" id="sub_add_lineitem" class="btn btn-info btn-sm sub_add_lineitem" value="Add" style="margin-top: 21px;"/>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br/>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sub_item_level">display Level</label>
                        <input type="text" name="" id="display_level" class="form-control num_only" value="">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <table style="" class="table custom-table item-table">
                        <thead>
                            <tr>
                                <th width="50px">Action</th>
                                <th>Sub Item</th>
                                <th>Level</th>
                                <th class="">Add/Less</th>
                                <th class="">Qty</th>
                                <th class="">Unit</th>
                            </tr>
                        </thead>
                        <tbody id="sub_lineitem_list"></tbody>
                    </table>
                </div>
                <div class="clearfix"></div>
                
            </div>
        </div>
    </div>
</div>
<div id="item_history_modal" class="modal fade myModelClose" tabindex="-1" data-keyboard="true" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <h4 class="modal-title">Item History<button type="button" class="close" data-dismiss="modal">&times;</button></h4>
            </div>
            <div class="modal-body edit-content">
            	<div class="row">
            		<div class="col-md-12">
	                	<input type="hidden" id="item_history_item_id" value="-1">
	                    <table id="item_history_table" class="table table-striped table-bordered">
	                        <thead>
	                            <tr>
	                                <th>Account</th>
	                                <th>Bill No</th>
	                                <th>Date</th>
	                                <th>Qty</th>
	                                <th>Rate</th>
	                                <th>Total Amount</th>
	                            </tr>
	                        </thead>
	                    </table>
	                </div>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>
<script type="text/javascript">
        sub_lineitem_objectdata = [];
	var edit_lineitem_inc = 0;
	var lineitem_objectdata = [];
	//~ var lineitem_objectdata = ['{"item_company_id":"1","item_id":"1","item_qty":"3","item_qty2":"15","price":"100","amount":"300"}', '{"item_company_id":"1","item_id":"2","item_qty":"4","item_qty2":"40","price":"200","amount":"800"}', '{"item_company_id":"1","item_id":"3","item_qty":"5","item_qty2":"75","price":"300","amount":"1500"}'];
	<?php if(isset($purchase_invoice_lineitems)){ ?>
		var li_lineitem_objectdata = [<?php echo $purchase_invoice_lineitems; ?>];
		var lineitem_objectdata = [];
		$.each(li_lineitem_objectdata, function (index, value) {
			value = JSON.parse(value);
			lineitem_objectdata.push(value);
		});
	<?php } else if(isset($sales_invoice_lineitems)){ ?>
		var li_lineitem_objectdata = [<?php echo $sales_invoice_lineitems; ?>];
		var lineitem_objectdata = [];
		$.each(li_lineitem_objectdata, function (index, value) {
			value = JSON.parse(value);
			lineitem_objectdata.push(value);
		});
	<?php } else if(isset($credit_note_lineitems)){ ?>
		var li_lineitem_objectdata = [<?php echo $credit_note_lineitems; ?>];
		var lineitem_objectdata = [];
		$.each(li_lineitem_objectdata, function (index, value) {
			value = JSON.parse(value);
			lineitem_objectdata.push(value);
		});
	<?php } else if(isset($debit_note_lineitems)){ ?>
		var li_lineitem_objectdata = [<?php echo $debit_note_lineitems; ?>];
		var lineitem_objectdata = [];
		$.each(li_lineitem_objectdata, function (index, value) {
			value = JSON.parse(value);
			lineitem_objectdata.push(value);
		});
	<?php } else if(isset($order_lineitems)){ ?>
		var li_lineitem_objectdata = [<?php echo $order_lineitems; ?>];
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
        initAjaxSelect2($("#site_id"), "<?= base_url('app/sites_select2_source') ?>");
		//initAjaxSelect2($("#item_id"),"<?=base_url('app/li_item_select2_source/')?>");
		initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
		initAjaxSelect2($("#unit_id"),"<?=base_url('app/unit_select2_source_by_item_id/')?>");
        initAjaxSelect2($("#item_group_id"),"<?=base_url('app/item_group_select2_source')?>");
        
        initAjaxSelect2($("#cat_id"),"<?=base_url('app/category_select2_source')?>");
            
		$(".noinput_select2").select2({
			width:"100%",
			placeholder: " --Select-- ",
			minimumResultsForSearch : -1
		});
        
        $(document).on('focus', '.add_item_link', function(){
            $(this).next().focus();
        });

		$('#item_qty,#price,#gst_rate').on('keyup', function() {
            var amount = 0;
            var qty = ($('#item_qty').val()) ? $('#item_qty').val() : 0;
            var rate = ($('#price').val()) ? $('#price').val() : 0;
            var gst = ($('#gst_rate').val()) ? $('#gst_rate').val() : 0;
            var amount = (qty * rate) + (qty * rate * gst / 100);
            $('#amount').val(parseFloat(amount).toFixed(2));
        });

        $('#item_group_id').on('change', function() {
            var item_group_id = $(this).val();
            $('#cat_id').val(null).trigger('change');
            $('#sub_cat_id').val(null).trigger('change');
            $('#item_id').val(null).trigger('change');
            if(item_group_id != '' || item_group_id != null){
                initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_item_group/')?>" + item_group_id);
            }
            if(item_group_id == '' || item_group_id == null){
                //$('#item_id').val(null).trigger('change');
                initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
            }
        });

        $('#cat_id').on('change', function() {
            var cat_id = $(this).val();
            $('#sub_cat_id').val(null).trigger('change');
            if(cat_id != '' || cat_id != null){
                initAjaxSelect2($("#sub_cat_id"),"<?=base_url('app/sub_category_select2_source/')?>" + cat_id);
                initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_category/')?>" + cat_id);
            }
            if(cat_id == '' || cat_id == null){
                //$('#item_id').val(null).trigger('change');
                initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source/')?>");
                $('#sub_cat_id').val(null).trigger('change');
            }
        });
    
    var item_history_table = $('#item_history_table').DataTable({
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "ajax": {
            "url": "<?= base_url('report/item_history_datatable')?>",
            "type": "POST",
            "data" : function(d) {
            	d.item_id = $("#item_history_item_id").val();
            },
        },
        "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT; ?>',
        "scroller": {
            "loadingIndicator": true
        },
        "columnDefs": [{
            "className": "text-right",
            "targets": [3,4,5],
        }]
    });

    $(document).on('click','#btn_view_item_history',function() {
    	var item_id = $('#item_history_item_id').val();
    	if(item_id == '' || item_id == '-1') {
    		show_notify('Please Select Item',false);
    	} else {
    		$("#item_history_modal").modal('show');
    	}	    
	});

    $(document).on('shown.bs.modal','#item_history_modal', function() {
	    item_history_table.draw();
	});

	$(document).on('hide.bs.modal','#item_history_modal', function () {
        go_to_back_page = false;
	});

    $('#item_id, #account_id, #item_group_id').on('change', function() {
        var item_id = $('#item_id').val();
        /// get discount data : Start //
        var item_group_id = $('#item_group_id').val();
        var cat_id = $('#cat_id').val();
        if(item_id !== ''){
            $.ajax({
                url: "<?=base_url('transaction/get_discount_data/') ?>",
                type: "POST",
                cache: false,
                async: false,
                dataType: 'json',
                data: {item_id: item_id, item_group_id: item_group_id, cat_id: cat_id, sales_date : $('#datepicker2').val(), acc_id: $('#account_id').val()},
                success: function (response) {
                    console.log(response);
                    if(response.discount_type){     
                        if(response.discount_type == 1){
                            $("#discount_type").val('1').trigger("change");
                            $('#discount').val(response.discount_per_1);
                            $('#discount_2').val(response.discount_per_2);
                        } else {
                            $("#discount_type").val('2').trigger("change");
                            $('#discount').val(response.discount_rate);
                        }
                    }
                },
            });
        }
        /// get discount data : End //
//        if($('#line_items_index').val() == '' || $('#line_items_index').val() == null){
//                $(".item_detail").val('');
//                $("#discount_type").val('1');
//                $("#discount").val('0');
//                $("#other_charges").val('0');
//        }
        
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
            if($("#cat_id").val() == null){
                initAjaxSelect2($("#cat_id"),"<?=base_url('app/category_from_item_select2_source/')?>" + item_id + '/' + item_group_id);
                setSelect2Value($("#cat_id"),"<?=base_url('app/set_category_from_item_select2_source/')?>" + item_id + '/' + item_group_id);
            }
            var cat_id = $('#cat_id').val();
            if(cat_id == null){
                cat_id = '';
            }
            if($("#sub_cat_id").val() == null){
                initAjaxSelect2($("#sub_cat_id"),"<?=base_url('app/sub_category_from_item_select2_source/')?>" + item_id + '/' + cat_id + '/' + item_group_id);
                setSelect2Value($("#sub_cat_id"),"<?=base_url('app/set_sub_category_from_item_select2_source/')?>" + item_id + '/' + item_group_id);
            }

            $("#item_history_item_id").val(item_id);

            $.ajax({
                url: "<?=base_url('app/get_item_detail') ?>",
                type: "POST",
                cache: false,
                async: false,
                dataType: 'json',
                data: 'item_id='+ item_id + '&account_id='+ account_id + '&item_group_id='+ item_group_id,
                success: function (response) {
                    initAjaxSelect2($("#unit_id"),"<?=base_url('app/unit_select2_source_by_item_id/')?>" + item_id);
                    setSelect2Value($("#unit_id"),"<?=base_url('app/set_pack_unit_select2_val_by_id/')?>" + response.pack_unit_id);
                    <?php if(($segment1 == 'purchase' && $segment2 == 'invoice') || ($segment1 == 'credit_note' && $segment2 == 'add')){ ?>
                            $('#rate_for_itax').val(response.purchase_rate);
                            response.rate_for_itax = response.purchase_rate;
                    <?php } ?>
                    <?php if(($segment1 == 'sales' && $segment2 == 'invoice') || ($segment1 == 'debit_note' && $segment2 == 'add')){ ?>
                            $('#rate_for_itax').val(response.sales_rate);
                            response.rate_for_itax = response.sales_rate;
                    <?php } ?>
//                    var discount_type = $('#discount_type').val();
//                    if(discount_type == '1'){
//                        $('#discount').val(response.sales_dis);
//                    }
                    $('#price').val(response.rate);
                    $('#account_state').val(response.account_state);
                    $('#user_state').val(response.user_state);
                    if(response.rate_for_itax == 1){
                        $("#cgst").val('');
                        $("#cgst_amt").val('');
                        $("#sgst").val('');
                        $("#sgst_amt").val('');
                        $("#igst").val('');
                        $("#igst_amt").val('');
                        $('#cgst').attr('readonly', 'readonly');
                        $('#sgst').attr('readonly', 'readonly');
                        $('#igst').attr('readonly', 'readonly');
                        $('#cgst').val(response.cgst_per);
                        $('#sgst').val(response.sgst_per);
                        $('#igst').val(response.igst_per);
                        $('#igst_for_itax').val(response.igst_per);
                    } else {
                        $('#cgst').removeAttr('readonly', 'readonly');
                        $('#sgst').removeAttr('readonly', 'readonly');
                        $('#igst').removeAttr('readonly', 'readonly');

                        if($('#tax_type').val() == '2'){
                            $('#cgst').val(0);
                            $('#sgst').val(0);
                            $('#cgst').hide();
                            $('#sgst').hide();
                            $('#igst').show();
                            $('#igst').val(response.igst_per);
                            
                        } else {
                            $('#cgst').show();
                            $('#sgst').show();
                            $('#cgst').val(response.cgst_per);
                            $('#sgst').val(response.sgst_per);
                            $('#igst').val(0);
                            $('#igst').hide();
                        }
                    }
                    apply_discount_tax_get_amount();
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
			var amount_total = parseFloat($("#amount_total").val()) || 0;
			var amount_total_after_round_off = parseFloat(amount_total) + parseFloat(round_off_amount);
			$('.amount_total_after_round_off').html(parseF(amount_total_after_round_off));
		});
		
		$(document).on('input','#item_qty',function () {
			input_qty_or_price();
		});
		$(document).on('input','#price',function () {
			input_qty_or_price();
		});
		$(document).on('input','#pure_amount',function () {
			apply_discount_tax_get_amount();
		});
		$(document).on('input','#other_charges',function () {
			apply_discount_tax_get_amount();
		});
		
		$(document).on('change','.discount_type',function () {
			apply_discount_tax_get_amount();
		});
		$(document).on('input','.discount',function () {
			apply_discount_tax_get_amount();
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
			var rate_for_itax = $('#rate_for_itax').val();
			if(rate_for_itax == 1){
				var item_price = $('#price').val();
				var item_qty = $('#item_qty').val();
				var price = lineitem['price_for_itax'] = $('#price').val();
				var igst = $('#igst_for_itax').val();
				price = lineitem['price'] = parseF(parseInt(price) - parseInt(price) * parseInt(igst)/( 100 + parseInt(igst)));
                var pure_price = parseF(item_price) / ((parseF(igst) /100) + 1 );
                var pure_amount =  item_qty * pure_price;
//				var pure_amount = lineitem['pure_amount'];
				
                var discount_type = $("#discount_type").val();
				var discount = $("#discount").val();
				var discount_amt = 0;
				if(discount_type == '1'){
					discount_amt = (parseF(pure_amount) * discount)/100;
				} else if(discount_type == '2'){
					discount_amt = parseF(discount);
				}
				discounted_price = parseFloat(pure_amount) + parseFloat(discount_amt);
//                if(discount_type == '2'){
//                    discounted_price = parseF(discounted_price) - (parseF(discounted_price) * parseInt(igst) /(100 + parseInt(igst)) + 1);
//                } else {
//                    discounted_price = parseF(discounted_price) - (parseF(discounted_price) * parseInt(igst) /(100 + parseInt(igst)));
//                }
                lineitem['discounted_price'] = parseF(discounted_price);
				var c_s_per = igst / 2;
				var c_s_amt = (parseF(discounted_price) * c_s_per)/100;
				
				var account_state = $('#account_state').val();
				var user_state = $('#user_state').val();
				if(account_state != 0 && user_state != 0 && account_state != user_state){
					var igst_amt = (parseF(discounted_price) * igst)/100;
					lineitem['cgst'] = 0;
					lineitem['sgst'] = 0;
					lineitem['cgst_amt'] = 0;
					lineitem['sgst_amt'] = 0;
					lineitem['igst'] = igst;
					lineitem['igst_amt'] = parseF(igst_amt);
				} else {
					lineitem['cgst'] = c_s_per;
					lineitem['sgst'] = c_s_per;
					lineitem['cgst_amt'] = parseF(c_s_amt);
					lineitem['sgst_amt'] = parseF(c_s_amt);
					lineitem['igst'] = 0;
					lineitem['igst_amt'] = 0;
				}
				
				var amount = parseInt(discounted_price) + parseInt(parseF(cgst_amt)) + parseInt(parseF(sgst_amt));
				var other_charges = $("#other_charges").val();
				var amount = parseInt(amount) + parseFloat(other_charges);
				// $("#amount").val(parseF(amount));

				$('#cgst').removeAttr('readonly', 'readonly');
				$('#sgst').removeAttr('readonly', 'readonly');
				$('#igst').removeAttr('readonly', 'readonly');
			}
                        console.log(sub_lineitem_objectdata);
                        lineitem['sub_item_data'] = JSON.parse(JSON.stringify(sub_lineitem_objectdata));
                        if ($('#apply_to_master').prop('checked')==true){ 
                            lineitem['apply_to_master'] = '1';
                        } else {
                            lineitem['apply_to_master'] = '0';
                        }
                        sub_lineitem_objectdata = [];
                        console.log(lineitem);
			var new_lineitem = JSON.parse(JSON.stringify(lineitem));
			var line_items_index = $("#line_items_index").val();
			if(line_items_index != ''){
				lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
			} else {
				lineitem_objectdata.push(new_lineitem);
			}
			$('#item_serial_no').siblings('.parsley-errors-list.filled').hide();
			display_lineitem_html(lineitem_objectdata);
			$('#lineitem_id').val('');
			<?php if(($segment1 == 'purchase' && $segment2 == 'invoice')){ ?>
				$('#form_purchase_invoice').find('input[name^="line_items_index"], input[name^="line_items_data"], select[name^="line_items_data"], textarea[name^="line_items_data"]').val('');
			<?php } ?>
			<?php if(($segment1 == 'sales' && $segment2 == 'invoice')){ ?>
				$('#form_sales_invoice').find('input[name^="line_items_index"], input[name^="line_items_data"], select[name^="line_items_data"], textarea[name^="line_items_data"]').val('');
			<?php } ?>
			<?php if(($segment1 == 'credit_note' && $segment2 == 'add')){ ?>
				$('#form_credit_note').find('input[name^="line_items_index"], input[name^="line_items_data"], select[name^="line_items_data"], textarea[name^="line_items_data"]').val('');
			<?php } ?>
			<?php if(($segment1 == 'debit_note' && $segment2 == 'add')){ ?>
				$('#form_debit_note').find('input[name^="line_items_index"], input[name^="line_items_data"], select[name^="line_items_data"], textarea[name^="line_items_data"]').val('');
			<?php } ?>
			edit_lineitem_inc = 0;
                        $("#item_group_id").val(null).trigger("change");
			$("#item_id").val(null).trigger("change");
			$("#cat_id").val(null).trigger("change");
			$("#sub_cat_id").val(null).trigger("change");
			$("#unit_id").val(null).trigger("change");
			$("#item_qty").val('');
			$("#price").val('');
			$("#price_for_itax").val('');
			$("#pure_amount").val('');
			$("#discount").val('');
			$("#discount_2").val('');
			$("#discounted_price").val('');
			$('#cgst').val('');
			$('#cgst_amt').val('');
			$('#sgst').val('');
			$('#sgst_amt').val('');
			$('#igst').val('');
			$('#igst_amt').val('');
			$("#other_charges").val('');
			$("#amount").val('');
			$("#line_items_index").val('');
                        
		});
                
            $(document).on("click",".sub_qty_setting",function(){
                if ($.trim($("#item_id").val()) == '') {
                    $("#item_id").select2('open');
                    show_notify("Please select Item !", false);
                    return false;
                }
                var item_id = $('#item_id').val();
                $("#selected_item_id").val(item_id);
                
                initAjaxSelect2($("#sub_item_id"), "<?= base_url('app/related_item_select2_source/') ?>" + item_id);
                initAjaxSelect2($("#sub_item_unit_id"), "<?= base_url('app/pack_unit_select2_source') ?>");
                initAjaxSelect2($("#sub_sub_item_unit_id"), "<?= base_url('app/pack_unit_select2_source') ?>");

                $("#sub_item_modal").modal("show");
            });
            
            $('#item_id').on('change', function() {
                sub_lineitem_objectdata = [];
                $('#apply_to_master').prop('checked', false);
                if ($.trim($("#item_id").val()) != '') {
                    var item_id = $('#item_id').val();
                    sub_lineitem_objectdata = [];
                    $.ajax({
                        url: "<?=base_url('transaction/get_sub_item_data') ?>",
                        type: "POST",
                        cache: false,
                        async: false,
                        dataType: 'json',
                        data: {item_id: item_id},
                        success: function (response) {
    //                        console.log(response);
                            sub_lineitem_objectdata = response;
                            sub_display_lineitem_html(sub_lineitem_objectdata);
                        },
                    });
                }
            });
            
            $('#display_level').on('keyup', function () {
                sub_display_lineitem_html(sub_lineitem_objectdata);
            });
            
            $('#sub_add_lineitem').on('click', function () {
                var item_qty = $("#sub_item_qty").val();
                if (item_qty == '' || item_qty == null) {
                    $("#sub_item_qty").focus();
                    show_notify("Please enter Qty !", false);
                    return false;
                }
                var item_unit_id = $("#sub_item_unit_id").val();
                if (item_unit_id == '' || item_unit_id == null) {
                    $("#sub_item_unit_id").select2('open');
                    show_notify("Please select Unit !", false);
                    return false;
                }
                var sub_item_id = $("#sub_item_id").val();
                if (sub_item_id == '' || sub_item_id == null) {
                    $("#sub_item_id").select2('open');
                    show_notify("Please select Sub Item !", false);
                    return false;
                }
                var sub_item_qty = $("#sub_sub_item_qty").val();
                if (sub_item_qty == '' || sub_item_qty == null) {
                    $("#sub_sub_item_qty").focus();
                    show_notify("Please enter sub Qty !", false);
                    return false;
                }
                var sub_item_unit_id = $("#sub_sub_item_unit_id").val();
                if (sub_item_unit_id == '' || sub_item_unit_id == null) {
                    $("#sub_sub_item_unit_id").select2('open');
                    show_notify("Please select sub Unit !", false);
                    return false;
                }
                save_sub_lineitem();
            });
            
            
            
	});
        
        function save_sub_lineitem(){
            var skey = '';
            var svalue = '';
            var slineitem = {};
            var is_validate = '0';
            $('select[name^="sub_line_items_data"]').each(function (e) {
                skey = $(this).attr('name');
                skey = skey.replace("sub_line_items_data[", "");
                skey = skey.replace("]", "");
                svalue = $(this).val();
                slineitem[skey] = svalue;
            });
            $('input[name^="sub_line_items_data"]').each(function () {
                skey = $(this).attr('name');
                skey = skey.replace("sub_line_items_data[", "");
                skey = skey.replace("]", "");
                svalue = $(this).val();
                slineitem[skey] = svalue;
            });
            var sub_item_id = $("#sub_item_id").val();
            $('select[name^="sub_line_items_data"]').each(function (index) {
                skey = $(this).attr('name');
                skey = skey.replace("sub_line_items_data[", "");
                skey = skey.replace("]", "");
                $.each(sub_lineitem_objectdata, function (index, value) {
                    if (value.type == sub_item_id) {
                        is_validate = '1';
                        show_notify("You cannot Add this Item. This Item has been used!", false);
//                        $("#add_lineitem").removeAttr('disabled', 'disabled');
                        return false;
                    }
                });
                if (is_validate == '1') {
                    return false;
                }
            });
            if (is_validate != '1') {
                slineitem['sub_item_name'] = $('#sub_item_id option:selected').html();
                slineitem['sub_add_less_name'] = $('#sub_item_add_less option:selected').html();
                slineitem['sub_unit_name'] = $('#sub_sub_item_unit_id option:selected').html();
                var snew_lineitem = JSON.parse(JSON.stringify(slineitem));
                sub_lineitem_objectdata.push(snew_lineitem);
                console.log(sub_lineitem_objectdata);
                sub_display_lineitem_html(sub_lineitem_objectdata);
                $('#lineitem_id').val('');
                $("#item_qty").val('');
                $("#item_unit_id").val(null).trigger("change");
                $("#sub_item_id").val(null).trigger("change");
                $("#sub_item_unit_id").val(null).trigger("change");
                $("#sub_item_qty").val('');
            }
        }
        
        function sub_display_lineitem_html(sub_lineitem_objectdata) {
            var sub_new_lineitem_html = '';
            var display_level = $('#display_level').val();
            $.each(sub_lineitem_objectdata, function (index, value) {
                var sub_lineitem_edit_btn = '';
                var sub_lineitem_delete_btn = '';   
                if(display_level != ''){
                    if(display_level == value.sub_item_level){
                        sub_lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item sub_delete_item" href="javascript:void(0);" onclick="sub_remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
                        var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                            sub_lineitem_delete_btn +
                            '</td>' +
                            '<td>' + value.sub_item_name + '</td>'+
                            '<td>' + value.sub_item_level + '</td>'+
                            '<td>' + value.sub_add_less_name + '</td>'+
                            '<td>' + value.sub_sub_item_qty + '</td>'+
                            '<td>' + value.sub_unit_name + '</td>';
                            sub_new_lineitem_html += row_html;
                    }
                } else {
                    sub_lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item sub_delete_item" href="javascript:void(0);" onclick="sub_remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
                    var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                        sub_lineitem_delete_btn +
                        '</td>' +
                        '<td>' + value.sub_item_name + '</td>'+
                        '<td>' + value.sub_item_level + '</td>'+
                        '<td>' + value.sub_add_less_name + '</td>'+
                        '<td>' + value.sub_sub_item_qty + '</td>'+
                        '<td>' + value.sub_unit_name + '</td>';
                        sub_new_lineitem_html += row_html;
                }
                
            });
            $('tbody#sub_lineitem_list').html(sub_new_lineitem_html);
        }
        
        function sub_remove_lineitem(index) {
            if (confirm('Are you sure ?')) {
                value = sub_lineitem_objectdata[index];
                sub_lineitem_objectdata.splice(index, 1);
                sub_display_lineitem_html(sub_lineitem_objectdata);
            }
        }
	
	function input_qty_or_price(){
		var item_qty = $("#item_qty").val() || 0;
		var price = $("#price").val() || 0;
		var pure_amount = item_qty * price;
		$("#pure_amount").val(parseF(pure_amount));
		apply_discount_tax_get_amount();
	}	
		
	function apply_discount_tax_get_amount(){
		var pure_amount = $("#pure_amount").val() || 0;
		var discount_type = $("#discount_type").val() || 0;
		var discount = $("#discount").val() || 0;
		var discount_2 = $("#discount_2").val() || 0;
		var discount_amt = 0;
		if(discount_type == '1'){
			discount_amt = (parseF(pure_amount) * discount)/100;
		} else if(discount_type == '2'){
			discount_amt = parseF(discount);
		}
		discounted_price = parseFloat(pure_amount) - parseFloat(discount_amt);
                if ($.trim(discount_2) != '' && discount_type == '1') {
                    discount_amt = (parseF(discounted_price) * discount_2)/100;
                    var new_d_amt = discounted_price;
                    discounted_price = parseFloat(new_d_amt) - parseFloat(discount_amt);
                }
		$("#discounted_price").val(parseF(discounted_price));
		var cgst = $("#cgst").val() || 0;
		var cgst_amt = (discounted_price * cgst)/100;
		$("#cgst_amt").val(parseF(cgst_amt));
		var sgst = $("#sgst").val() || 0;
		var sgst_amt = (discounted_price * sgst)/100;
		$("#sgst_amt").val(parseF(sgst_amt));
		var igst = $("#igst").val() || 0;
		var igst_amt = (discounted_price * igst)/100;
		$("#igst_amt").val(parseF(igst_amt));
		
		var amount = parseFloat(discounted_price) + parseFloat(cgst_amt) + parseFloat(sgst_amt) + parseFloat(igst_amt);
		var other_charges = $("#other_charges").val() || 0;
		var amount = parseFloat(amount) + parseFloat(other_charges);
//                alert(other_charges);
		// $("#amount").val(parseF(amount));
	}
	
	function display_lineitem_html(lineitem_objectdata,is_use_db_round_off_amount){
        console.log(lineitem_objectdata);
		
		if(typeof(is_use_db_round_off_amount) != "undefined" && is_use_db_round_off_amount !== null) {
			var use_db_round_off_amount = true;
		} else {
			var use_db_round_off_amount = false;
		}
		var new_lineitem_html = '';
		var qty_total = 0;
		var pure_amount_total = 0;
		var discounted_price_total = 0;
		var cgst_amount_total = 0;
		var sgst_amount_total = 0;
		var igst_amount_total = 0;
		var other_charges_total = 0;
		var amount_total = 0;
		$.each(lineitem_objectdata, function (index, value) {
            var value_cat_name;
			$.ajax({
				url: "<?=base_url('app/set_category_select2_val_by_id/') ?>" + value.cat_id,
				type: "POST",
				dataType: 'json',
				async: false,
				cache: false,
				success: function (response) {
					if(response.text == '--select--'){ response.text = ''; }
					value_cat_name = response.text;
				},
			});
    
            var value_sub_cat_name;
			$.ajax({
				url: "<?=base_url('app/set_sub_category_select2_val_by_id/') ?>" + value.sub_cat_id,
				type: "POST",
				dataType: 'json',
				async: false,
				cache: false,
				success: function (response) {
					if(response.text == '--select--'){ response.text = ''; }
					value_sub_cat_name = response.text;
				},
			});
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

			var value_unit_name = '';
			if( value.unit_id != ''){
				$.ajax({
					url: "<?=base_url('app/set_pack_unit_select2_val_by_id/') ?>" + value.unit_id,
					type: "POST",
					dataType: 'json',
					async: false,
					cache: false,
					success: function (response) {
						if(response.text == '--select--'){ response.text = ''; }
						value_unit_name = response.text;
					},
				});
			}
			
			var value_discount_type = '';
			if( value.discount_type == 1 ) { value_discount_type = 'Pct'; }
			if( value.discount_type == 2 ) { value_discount_type = 'Amt'; }
			var discount_data = value_discount_type + ' : ' + value.discount;
			if(value.note != '' && value.note != null){
                item_name = value_item_name + '<br> - '+ value.note;
            } else {
                item_name = value_item_name;
            }
			var lineitem_edit_btn = '';
			lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
			var row_html = '<tr class="lineitem_index_' + index + '"><td class="fix_fcolumn">' +
			lineitem_edit_btn +
			' <a class="btn btn-xs btn-danger btn-delete-item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a> ' +
			'</td>' +
			'<td>' + (index+1) + '</td>' +
            <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
            '<td>' + value_item_group_name + '</td>' + 
            <?php } ?>
            <?php if(isset($invoice_line_item_fields) && in_array('category', $invoice_line_item_fields)){ ?>
            '<td>' + value_cat_name + '</td>' +
            <?php } ?>
            <?php if(isset($invoice_line_item_fields) && in_array('sub_category', $invoice_line_item_fields)){ ?>
            '<td>' + value_sub_cat_name + '</td>' +
            <?php } ?>
            '<td>' + item_name + '</td>' +
            '<td class="text-right">' + value.item_qty + '</td>' +
			'<td class="<?php echo isset($line_item_fields['unit']) ? $line_item_fields['unit'] : ''; ?>">' + value_unit_name + '</td>' +
			'<td class="text-right <?php echo isset($line_item_fields['Rate']) ? $line_item_fields['Rate'] : ''; ?>">' + value.price + '</td>' +
			'<td class="fix_lcolumn text-right <?php echo isset($line_item_fields['amount']) ? $line_item_fields['amount'] : ''; ?>">' + parseF(value.amount) + '</td></tr>';
			new_lineitem_html += row_html;
			qty_total += parseInt(value.item_qty);
			pure_amount_total += parseInt(value.pure_amount);
            discounted_price_total += parseFloat(value.discounted_price);
            cgst_amount_total += parseFloat(value.cgst_amt);
			sgst_amount_total += parseFloat(value.sgst_amt);
			igst_amount_total += parseFloat(value.igst_amt);
			other_charges_total += parseFloat(value.other_charges);
			amount_total += parseFloat(value.amount);

		});
		$('tfoot span.qty_total').html(qty_total); $('#qty_total').val(qty_total);
		$('tfoot span.pure_amount_total').html(pure_amount_total); $('#pure_amount_total').val(pure_amount_total);
		var discount_total = parseFloat(pure_amount_total) - parseFloat(discounted_price_total)
        
		$('tfoot span.discount_total').html(''); $('#discount_total').val(discount_total);
		$('tfoot span.discounted_price_total').html(parseF(discounted_price_total)); $('#discounted_price_total').val(discounted_price_total);
		
		$('tfoot span.cgst_amount_total').html(parseF(cgst_amount_total)); $('#cgst_amount_total').val(parseF(cgst_amount_total));
		$('tfoot span.sgst_amount_total').html(parseF(sgst_amount_total)); $('#sgst_amount_total').val(parseF(sgst_amount_total));
		$('tfoot span.igst_amount_total').html(parseF(igst_amount_total)); $('#igst_amount_total').val(parseF(igst_amount_total));
		
		$('tfoot span.other_charges_total').html(other_charges_total); $('#other_charges_total').val(other_charges_total);
		$('tfoot span.amount_total').html(parseF(amount_total));$('#amount_total').val(parseF(amount_total));
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
		//if(edit_lineitem_inc == 0){  edit_lineitem_inc = 1; $('.edit_lineitem_'+ index).click(); }
		value = lineitem_objectdata[index];
		$("#line_items_index").val(index);
//        console.log(value);
        if(typeof(value.item_group_id) !== "undefined" && value.item_group_id !== null && value.item_group_id !== 0) {
        	setSelect2Value($("#item_group_id"),"<?=base_url('app/set_item_group_select2_val_by_id/')?>"+ value.item_group_id);	
            initAjaxSelect2($("#item_id"),"<?=base_url('app/item_select2_source_from_item_group/')?>"+ value.item_group_id);
        }
        if(typeof(value.item_id) !== "undefined") {
        	setSelect2Value($("#item_id"),"<?=base_url('app/set_li_item_select2_val_by_id/')?>" + value.item_id);
        }
        if(typeof(value.cat_id) !== "undefined" && value.cat_id !== null && value.cat_id !== 0) {
        	setSelect2Value($("#cat_id"),"<?=base_url('app/set_category_select2_val_by_id/')?>" + value.cat_id);
        }
        if(typeof(value.sub_cat_id) !== "undefined" && value.sub_cat_id !== null && value.sub_cat_id !== 0) {
        	setSelect2Value($("#sub_cat_id"),"<?=base_url('app/set_sub_category_select2_val_by_id/')?>" + value.sub_cat_id);
        }

        initAjaxSelect2($("#unit_id"),"<?=base_url('app/unit_select2_source_by_item_id/')?>" + value.item_id);
        if(typeof(value.unit_id) !== "undefined" && value.unit_id !== null && value.unit_id !== 0) {
        	setSelect2Value($("#unit_id"),"<?=base_url('app/set_pack_unit_select2_val_by_id/')?>" + value.unit_id);
        }
        

		if(typeof(value.id) != "undefined" && value.id !== null) {
			$("#lineitem_id").val(value.id);
		}
		if(value.rate_for_itax == 1){
			$("#item_qty").val(value.item_qty);
			$("#price").val(value.price_for_itax);
			$("#igst_for_itax").val(value.igst_for_itax);
			//~ var pure_amount = value.item_qty * value.price_for_itax;
			//~ $("#pure_amount").val(pure_amount);
			$("#pure_amount").val(value.pure_amount);
			$("#discount_type").val(value.discount_type);
			$("#discount").val(value.discount);
			
			var discount_amt = 0;
			if(value.discount_type == '1'){
				discount_amt = (pure_amount * value.discount)/100;
			} else if(value.discount_type == '2'){
				discount_amt = value.discount;
			}
			discounted_price = parseInt(pure_amount) + parseInt(discount_amt);
			$("#discounted_price").val(parseF(discounted_price));
			$("#cgst").val('');
			$("#cgst_amt").val('');
			$("#sgst").val('');
			$("#sgst_amt").val('');
			$("#igst").val('');
			$("#igst_amt").val('');
			$("#other_charges").val(value.other_charges);
			$("#amount").val(value.amount);
			$("#note").val(value.note);
		} else {
			$("#item_qty").val(value.item_qty);
			$("#price").val(value.price);
			$("#pure_amount").val(value.pure_amount);
			$("#discount_type").val(value.discount_type);
			$("#discount").val(value.discount);
			$("#discounted_price").val(value.discounted_price);
			$("#cgst").val(value.cgst);
			$("#cgst_amt").val(value.cgst_amt);
			$("#sgst").val(value.sgst);
			$("#sgst_amt").val(value.sgst_amt);
			$("#igst").val(value.igst);
			$("#igst_amt").val(value.igst_amt);
			$("#other_charges").val(value.other_charges);
			$("#amount").val(value.amount);
			$("#note").val(value.note);
		}
		$('#ajax-loader').hide();
	}
	
	function remove_lineitem(index){
		if(confirm('Are you sure?')) {
			value = lineitem_objectdata[index];
			if(typeof(value.id) != "undefined" && value.id !== null) {
				$('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.id + '" />');
			}
			lineitem_objectdata.splice(index,1);
			display_lineitem_html(lineitem_objectdata);	
		} else {
			return false;
		}
		
	}
	
	function parseF(value, decimal) {
		decimal = 2;
		return value ? parseFloat(value).toFixed(decimal) : 0;
	}
	
</script>
