<?php
$array_items = array('name' => '', 'email' => '');
$this->session->unset_userdata($array_items);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Invoice Type
			<?php if($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"add")) { ?>
			<a href="<?=base_url('master/invoice_type');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>  
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <div class="col-md-6 col-md-push-6">
            	<?php if($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"add") || $this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"edit")) { ?>
				<form id="form_invoice_type" action="" enctype="multipart/form-data" data-parsley-validate="">
					<?php if(isset($invoice_type_id) && !empty($invoice_type_id)){ ?>
					<input type="hidden" id="invoice_type_id" name="invoice_type_id" value="<?=$invoice_type_id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title"><?=isset($invoice_type_id) ? 'Edit' : 'Add' ?></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="form-group">
								<label for="invoice_type">Invoice Type<span class="required-sign">*</span></label>
                                                                <input type="text" name="invoice_type" class="form-control" id="invoice_type" placeholder="Enter Invoice Type" value="<?=isset($invoice_type) ? $invoice_type : '' ?>" pattern="[^'\x22]+" title="Invalid input" required autofocus="">
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($invoice_type_id) && $this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"add")) { ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Save</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } ?>                                                        
						</div>
					</div>
				</form>
				<?php } ?>
				<!-- /.box-body -->
				<!-- /.box -->
			</div>
			<div class="col-md-6 col-md-pull-6">
				<?php if($this->applib->have_access_role(MASTER_INVOICE_TYPE_ID,"view")) { ?>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered invoice_type-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Invoice Type</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<!---content end--->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				<?php } ?>
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
	$(document).ready(function(){
		table = $('.invoice_type-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/invoice_type_datatable')?>",
				"type": "POST"
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{"targets": 0, "orderable": false }
			]
		});
        
        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

		$(document).on('submit', '#form_invoice_type', function () {
			var item_type_name = $('#invoice_type').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_invoice_type') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Invoice Type Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('input[name="invoice_type_id"]').val("");
						$('input[name="invoice_type"]').val("");
						$('.addmorerow').remove();
						show_notify('Invoice Type Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="invoice_type_id"]').val("");
						$('input[name="invoice_type"]').val("");
						show_notify('Invoice Type Successfully Updated.', true);
					}
					return false;
				},
			});
			return false;
		});

		$(document).on("click",".delete_button",function(){
			var value = confirm('Are you sure delete this records?');
			var tr = $(this).closest("tr");
			if(value){
				$.ajax({
					url: $(this).data('href'),
					type: "POST",
					data: 'id_name=invoice_type_id&table_name=invoice_type',
					success: function(data){
						table.draw();
						tr.remove();
						show_notify('Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
