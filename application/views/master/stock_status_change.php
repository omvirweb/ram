<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Stock Status Change
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="form_stock_status_change" action="" enctype="multipart/form-data">
					<input type="hidden" id="item_minimum_stock" value="0">
					<?php if(isset($id) && !empty($id)){ ?>
						<input type="hidden" id="id" name="id" value="<?=$id;?>">
					<?php } ?>
					<div class="box box-primary">
	                    <div class="box-body">
	                    	<div class="row">
	                    		<div class="col-md-3">
	                    			<div class="form-group">
						                <label for="st_change_date" class="control-label">Date<span class="required-sign">*</span></label>
						                <input type="text" name="st_change_date" id="datepicker1" class="form-control" required value="<?=isset($st_change_date)?date("d-m-Y",strtotime($st_change_date)):date("d-m-Y")?>">
						            </div>
	                    		</div>
	                    		<div class="clearfix"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="from_status" class="control-label">From Status<span class="required-sign">*</span></label>
                                                <select name="from_status" id="from_status" class="form-control" required="">
                                                    <option value="">Select</option>
                                                    <option value="<?= IN_ORDER_ID; ?>" <?= isset($from_status) && $from_status == IN_ORDER_ID ? 'selected' : '' ?>>Order</option>
                                                    <option value="<?= IN_WIP_ID; ?>" <?= isset($from_status) && $from_status == IN_WIP_ID ? 'selected' : '' ?> >In WIP</option>
                                                    <option value="<?= IN_WORK_DONE_ID; ?>" <?= isset($from_status) && $from_status == IN_WORK_DONE_ID ? 'selected' : '' ?>>In Work Done</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="to_status" class="control-label">To Status<span class="required-sign">*</span></label>
                                                <select name="to_status" id="to_status" class="form-control" required="" disabled="">
                                                    <option value="">Select</option>
                                                    <option value="<?= IN_STOCK_ID; ?>" <?= isset($to_status) && $to_status == IN_STOCK_ID ? 'selected' : '' ?>>In Stock</option>
                                                    <option value="<?= IN_WIP_ID; ?>" <?= isset($to_status) && $to_status == IN_WIP_ID ? 'selected' : '' ?> >In WIP</option>
                                                    <option value="<?= IN_WORK_DONE_ID; ?>" <?= isset($to_status) && $to_status == IN_WORK_DONE_ID ? 'selected' : '' ?>>In Work Done</option>
                                                </select>
                                            </div>
                                        </div>
	                    		<div class="clearfix"></div>
	                    		<div class="col-md-3">
	                    			<div class="form-group">
						                <label for="item_id">Item Name<span class="required-sign">*</span></label>
			                            <select name="item_id" id="item_id" required></select>
						            </div>
	                    		</div>
	                    		<div class="col-md-3">
	                    			<div class="form-group">
						                <label for="qty" class="control-label">Qty<span class="required-sign">*</span></label>
						                <input type="text" name="qty" id="qty" class="form-control" required value="<?=isset($qty)?$qty:'1' ?>" <?=isset($qty)?'readonly':''?>>
						            </div>
	                    		</div>
	                    		<div class="col-md-1 col-xs-4">
	                    			<br/>
	                    			<button type="submit" class="btn btn-primary save_sub_item"><?=isset($id) ? 'Edit' : 'Add' ?></button>
	                    		</div>
	                    		<div class="col-md-5 col-xs-6">
	                    			<table>
	                    				<tr>
	                    					<th >In Stock :</th>
	                    					<td id="in_stock_qty" style="padding-left: 10px;"></th>
	                    				</tr>
	                    				<tr>
	                    					<th>In WIP :</th>
	                    					<td id="in_wip_qty" style="padding-left: 10px;"></th>
	                    				</tr>
	                    				<tr>
	                    					<th>In Work Done :</th>
	                    					<td id="in_work_done_qty" style="padding-left: 10px;"></th>
	                    				</tr>
	                    			</table>
	                    		</div>
	                    		<div class="clearfix"></div>
	                    		<div class="col-md-6">
			                    	<br/>
			                        <table id="stock_status_change_table" class="table table-bordered" style="width:100%;">
			                        	<thead>
			                        		<tr>
			                        			<th>Action</th>
			                        			<th>Date</th>
			                        			<th>Item Name</th>
			                        			<th>Qty</th>
			                        			<th>Current Status</th>
			                        		</tr>
			                        	</thead>
			                        	<tbody>
			                        	</tbody>
			                        </table>
			                    </div>
	                    	</div>
	                    </div>
	                </div>
            	</form>
	        </div>
		</div>
		<!-- /.row -->
		<!-- END ALERTS AND CALLOUTS -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
	var table;
	$(document).ready(function(){
        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_select2_source_without_item_group') ?>");
        <?php if(isset($item_id) && !empty($item_id)){ ?>
			setSelect2Value($("#item_id"), "<?= base_url('app/set_item_select2_val_by_id/'.$item_id)?>");
			get_item_stock_data_by_status(<?=$item_id;?>);
		<?php } ?>
		
		$(document).on('change', '#from_status', function () {
                    if($(this).val() == <?= IN_ORDER_ID ?>){
                        $("#to_status").val(<?= IN_WIP_ID ?>);
                    } else if($(this).val() == <?= IN_WIP_ID ?>){
                        $("#to_status").val(<?= IN_WORK_DONE_ID ?>);
                    } else if($(this).val() == <?= IN_WORK_DONE_ID ?>){
                        $("#to_status").val(<?= IN_STOCK_ID ?>);
                    } else {
                        $("#to_status").val('');
                    } 
		});
                
		$(document).on('change', '#item_id', function () {
			var item_id = $(this).val();
			get_item_stock_data_by_status(item_id);
		});

        table = $('#stock_status_change_table').DataTable({
			"serverSide": true,
			"ordering": false,
			"searching": true,
			"ajax": {
				"url": "<?php echo base_url('master/stock_status_change_datatable')?>",
				"type": "POST"
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{
                    "className": "text-right",
                    "targets": [3]
            	}
			]
		});

        $(document).on('submit', '#form_stock_status_change', function () {
        	var item_minimum_stock = $("#item_minimum_stock").val();
        	var in_stock_qty = $("#in_stock_qty").html();
        	var qty = $("#qty").val();
        	if(item_minimum_stock > 0 && (in_stock_qty - qty) < item_minimum_stock) {
        		alert("Item stock is less then minimum stock.");
        	}
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_stock_status_change') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('input[name="id"]').val("");
						$('#datepicker1').datepicker('setDate', '<?=date('d-m-Y')?>');
						$('#from_status').val(null).trigger("change");
						$('#to_status').val(null).trigger("change");
						$('#item_id').val(null).trigger("change");
						$('#qty').val(1);
						$('#qty').removeAttr('readonly');
						show_notify('Stock Status Change Successfully Added.', true);

						$(".parsley-error").removeClass("parsley-error");
						$(".parsley-success").removeClass("parsley-success");
						$(".parsley-errors-list li").remove();
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('input[name="id"]').val("");
						$('#datepicker1').datepicker('setDate', '<?=date('d-m-Y')?>');
						$('#from_status').val(null).trigger("change");
						$('#to_status').val(null).trigger("change");
						$('#item_id').val(null).trigger("change");
						$('#qty').val(1);
						$('#qty').removeAttr('readonly');
						$('.save_sub_item').html('Add');
						show_notify('Stock Status Change Successfully Updated.', true);

						$(".parsley-error").removeClass("parsley-error");
						$(".parsley-success").removeClass("parsley-success");
						$(".parsley-errors-list li").remove();
					}
					return false;
				},
			});
			return false;
		});

        shortcut.add("ctrl+s", function() {  
            $(".module_save_btn").click();
        });

		$(document).on("click",".delete_button",function(){
			if(confirm('Are you sure delete this records?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Stock Status Change. This Item has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Deleted Successfully!', true);

                            window.location.href = "<?=base_url('master/stock_status_change')?>";
                        }
                    }
                });
            }
		});
	});

	function get_item_stock_data_by_status(item_id)
	{
		if(item_id > 0) {
			$.ajax({
                url: "<?=base_url('master/get_current_item_stock_data_by_status/')?>" + item_id,
                type: "GET",
                dataType: "json",
                success: function (res) {
            		$("#in_stock_qty").html(res.in_stock_qty);
					$("#in_wip_qty").html(res.in_wip_qty);
					$("#in_work_done_qty").html(res.in_work_done_qty); 
					$("#item_minimum_stock").val(res.item_minimum_stock); 
                }
            });
		} else {
			$("#in_stock_qty").html('');
			$("#in_wip_qty").html('');
			$("#in_work_done_qty").html('');
			$("#item_minimum_stock").val(0);
		}
	}
</script>
