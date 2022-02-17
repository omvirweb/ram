<?php $this->load->view('success_false_notify'); 
    $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
    $li_category = isset($this->session->userdata()['li_category']) ? $this->session->userdata()['li_category'] : '0';
    $li_sub_category = isset($this->session->userdata()['li_sub_category']) ? $this->session->userdata()['li_sub_category'] : '0';
    $li_short_name = isset($this->session->userdata()['li_short_name']) ? $this->session->userdata()['li_short_name'] : '0';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Items
			<?php if($this->applib->have_access_role(MASTER_ITEM_ID,"add")) { ?>
			<a href="<?=base_url('item/item');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start - -->
                                                <h3 class="box-title">
                                                    <button type="button" class="btn btn-danger multiple_delete">Delete Multiple</button>
                                                </h3>
						<table class="table table-striped table-bordered item-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Item Name</th>
                                    <?php if ($li_short_name == 1) { ?>
                                        <th>Item Short Name</th>
                                    <?php } ?>
									<th>Current Stock Qty.</th>
									<th>Item Type</th>
                                    <?php if($li_item_group == '1'){ ?>
                                        <th>Item Group</th>
                                    <?php } ?>
                                    <?php if($li_category == '1'){ ?>
                                        <th>Category Group</th>
                                    <?php } ?>
                                    <?php if($li_sub_category == '1'){ ?>
                                        <th>Sub Category Group</th>
                                    <?php } ?>
                                                    <th>Unit</th>
                                                    <th>Sell Rate</th>
                                                    <th>Purchase Rate</th>
                                                    <th>List Price</th>
                                                    <th>MRP</th>
                                                </tr>
                                                </thead>
                                                <tbody class="tbody">
                                                </tbody>
                                        </table>
						<!---content end- -->
					</div>
					<!-- /.box-body -->
				</div>
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
<div id="sub_item_modal" class="modal fade myModelClose" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color:#f1e8e1;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Sub Items <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
            </div>
            <div class="modal-body edit-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_id">Item<span class="required-sign">*</span></label>
                            <select name="item_id" id="item_id" ></select>
                            <input type="hidden" id="selected_item_id" value="-1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="item_level">Level<span class="required-sign">*</span></label>
                            <input type="text" name="item_level" id="item_level" class="form-control num_only" value="1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="item_qty">Qty<span class="required-sign">*</span></label>
                            <input type="text" name="item_qty" id="item_qty" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_unit_id">Unit<span class="required-sign">*</span></label>
                            <select name="item_unit_id" id="item_unit_id"></select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sub_item_id">Sub Item<span class="required-sign">*</span></label>
                            <select name="sub_item_id" id="sub_item_id" ></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sub_item_add_less">Add/Less<span class="required-sign">*</span></label>
                            <select name="sub_item_add_less" id="sub_item_add_less" class="form-control">
                            	<option value="<?=QTY_ADD_ID?>">Add</option>
                            	<option value="<?=QTY_LESS_ID?>" selected="">Less</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sub_item_qty">Qty<span class="required-sign">*</span></label>
                            <input type="text" name="sub_item_qty" id="sub_item_qty" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sub_item_unit_id">Unit<span class="required-sign">*</span></label>
                            <select name="sub_item_unit_id" id="sub_item_unit_id"></select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right save_sub_item">Save</button>
                    </div>
                    <div class="col-md-12">
                    	<br/>
                        <table id="sub_item_datatable" class="table table-bordered" style="width:100%;">
                        	<thead>
                        		<tr>
                        			<th>Action</th>
                        			<th>Sub Item</th>
                        			<th>Level</th>
                        			<th>Add / Less</th>
                        			<th>Qty</th>
                        			<th>Unit</th>
                        		</tr>
                        	</thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var table;
        
	$(document).ready(function(){
		$.fn.modal.Constructor.prototype.enforceFocus = function() {};


		table = $('.item-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('item/item_datatable')?>",
				"type": "POST"
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{"targets": 0, "orderable": false },
				{
                    "className": "text-right",
                    "targets": [2]
            	}
			],
		});

		sub_item_datatable = $('#sub_item_datatable').DataTable({
			"serverSide": true,
			"ordering": false,
			"searching": true,
			"ajax": {
				"url": "<?php echo base_url('item/sub_item_datatable')?>",
				"type": "POST",
				data:function(d) {
					d.item_id = $("#selected_item_id").val();
				}
			},
			"scrollY": '175',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
			],
		});

		$(document).on("click",".sub_item_button",function(){
			var item_id = $(this).attr("data-item_id");
			$("#selected_item_id").val(item_id);
			$("#item_level").val('1');

			initAjaxSelect2($("#item_id"), "<?= base_url('app/item_select2_source_without_item_group') ?>");
			setSelect2Value($("#item_id"), "<?= base_url('app/set_item_select2_val_by_id/') ?>" + item_id);

			initAjaxSelect2($("#sub_item_id"), "<?= base_url('app/related_item_select2_source/') ?>" + item_id);
			initAjaxSelect2($("#item_unit_id"), "<?= base_url('app/pack_unit_select2_source') ?>");
			initAjaxSelect2($("#sub_item_unit_id"), "<?= base_url('app/pack_unit_select2_source') ?>");

			$("#sub_item_modal").modal("show");
		});

		$(document).on("click",".sub_item_edit_button",function(){
			var item_id = $(this).attr("data-item_id");
			var item_qty = $(this).attr("data-item_qty");
			var item_unit_id = $(this).attr("data-item_unit_id");
			var sub_item_id = $(this).attr("data-sub_item_id");
			var sub_item_add_less = $(this).attr("data-sub_item_add_less");
			var sub_item_qty = $(this).attr("data-sub_item_qty");
			var sub_item_unit_id = $(this).attr("data-sub_item_unit_id");
			var item_level = $(this).attr("data-item_level");

			$("#item_qty").val(item_qty);
			setSelect2Value($("#item_unit_id"), "<?= base_url('app/set_pack_unit_select2_val_by_id/') ?>" + item_unit_id);
			
			setSelect2Value($("#sub_item_id"), "<?= base_url('app/set_item_select2_val_by_id/') ?>" + sub_item_id);
			$("#sub_item_add_less").val(sub_item_add_less).change();
			$("#sub_item_qty").val(sub_item_qty);
			$("#item_level").val(item_level);
			setSelect2Value($("#sub_item_unit_id"), "<?= base_url('app/set_pack_unit_select2_val_by_id/') ?>" + sub_item_unit_id);
		});

		$(document).on("click",".sub_item_delete_button",function(){
			if(confirm('Are you sure delete this Item?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=id&table_name=sub_item_add_less_settings',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Item. This Item has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            sub_item_datatable.draw();
                            show_notify('Item Deleted Successfully!', true);
                        }
                    }
                });
            }
		});

		$(document).on("change","#item_id",function(){
			var item_id = $(this).val();
			$("#selected_item_id").val(item_id);
			$("#sub_item_id").val(null).change();
			initAjaxSelect2($("#sub_item_id"), "<?= base_url('app/related_item_select2_source/') ?>" + item_id);
			sub_item_datatable.draw();
		});

		$('#sub_item_modal').on('shown.bs.modal', function () {
			sub_item_datatable.draw();
		});

		$('#sub_item_modal').on('hidden.bs.modal', function () {
			$("#selected_item_id").val(-1);
		  	$("#item_qty").val(1);
			$("#sub_item_qty").val(1);
			$("#item_level").val(1);
			$("#sub_item_add_less").val(<?=QTY_LESS_ID;?>).change();
			$("#item_id").val(null).change();
			$("#sub_item_id").val(null).change();
			$("#item_unit_id").val(null).change();
			$("#sub_item_unit_id").val(null).change();
		});

		$(document).on("click",".save_sub_item",function(){
			var item_id = $("#item_id").val();
			var item_qty = $("#item_qty").val();
			var item_unit_id = $("#item_unit_id").val();
			var sub_item_id = $("#sub_item_id").val();
			var sub_item_add_less = $("#sub_item_add_less").val();
			var sub_item_qty = $("#sub_item_qty").val();
			var item_level = $("#item_level").val();
			var sub_item_unit_id = $("#sub_item_unit_id").val();
			if(item_id == '' || item_id == null) {
				show_notify('Please Select Item.', false);
				return false;
			}
			if(item_qty == '' || item_qty == null || item_qty <= 0) {
				show_notify('Please Enter Item Qty.', false);
				return false;
			}
			if(item_unit_id == '' || item_unit_id == null) {
				show_notify('Please Select Item Unit.', false);
				return false;
			}
			if(sub_item_id == '' || sub_item_id == null) {
				show_notify('Please Select Sub Item.', false);
				return false;
			}
			if(sub_item_add_less == '' || sub_item_add_less == null) {
				show_notify('Please Select Add/Less.', false);
				return false;
			}
			if(sub_item_qty == '' || sub_item_qty == null || sub_item_qty <= 0) {
				show_notify('Please Enter Sub Item Qty.', false);
				return false;
			}
			if(sub_item_unit_id == '' || sub_item_unit_id == null) {
				show_notify('Please Select Sub Item Unit.', false);
				return false;
			}

			$.ajax({
                url: "<?=base_url('item/save_sub_item')?>",
                type: "POST",
                data: 'item_id='+item_id+'&item_qty='+item_qty+'&item_unit_id='+item_unit_id+'&sub_item_add_less='+sub_item_add_less+'&sub_item_id='+sub_item_id+'&sub_item_qty='+sub_item_qty+'&item_level='+item_level+'&sub_item_unit_id='+sub_item_unit_id,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Error') {
                        
                    } else if (json['success'] == 'true') {
						$("#item_qty").val(1);
						$("#item_unit_id").val(null).change();
						$("#sub_item_id").val(null).change();
						$("#sub_item_add_less").val(<?=QTY_LESS_ID;?>).change();
						$("#sub_item_qty").val(1);
						$("#item_level").val(1);
						$("#sub_item_unit_id").val(null).change();
						sub_item_datatable.draw();
                        show_notify('Saved Successfully!', true);
                    }
                }
            });
		});

		$(document).on("click",".delete_button",function(){
			if(confirm('Are you sure delete this Item?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=item_id&table_name=item',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Item. This Item has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Item Deleted Successfully!', true);
                        }
                    }
                });
            }
		});
                delete_ids = [];
		$(document).on("click",".multiple_delete",function(){
                    delete_ids = [];
                    $('.item-table .tbody tr').each(function(e) {
                        if($(this).find(".multi_delete").prop("checked") == true){
                            delete_ids.push($(this).find(".multi_delete").val());
                        }

                    });
                    if(delete_ids == ''){
                        show_notify('Select item!', false);
                    } else {
                        objectdata_stringify = JSON.stringify(delete_ids);
                        $.ajax({
                            url: "<?php echo base_url('item/delete_multiple') ?>",
                            method: 'POST',
                            async: false,
                            data: {data: objectdata_stringify},
                            success: function () {
                                objectdata_stringify = '';
                                show_notify('Deleted Successfully.', true);
                                table.draw();
                                delete_ids = [];
                            }
                        });
                    }
                    console.log(delete_ids);
		});
	});
</script>
