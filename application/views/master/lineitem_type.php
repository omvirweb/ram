<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Lineitem Type
			<a href="<?=base_url('master/lineitem_type');?>" class="btn btn-primary pull-right">Add New</a>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered lineitem_type-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Lineitem Type</th>
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
			</div>
			<!-- /.col -->

			<div class="col-md-6">
				<form id="form_lineitem_type" action="" enctype="multipart/form-data" data-parsley-validate="">
					<?php if(isset($id) && !empty($id)){ ?>
					<input type="hidden" id="id" name="id" value="<?=$id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title"><?=isset($id) ? 'Edit' : 'Add' ?></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="form-group">
								<label for="lineitem_type_name">Lineitem Type<span class="required-sign">*</span></label>
								<input type="text" name="lineitem_type_name" class="form-control" id="lineitem_type_name" placeholder="Enter Lineitem Type" value="<?=isset($name) ? $name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn"><?=isset($id) ? 'Update' : 'Save' ?></button>
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
	$(document).ready(function(){
		table = $('.lineitem_type-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/lineitem_type_datatable')?>",
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

		$(document).on('submit', '#form_lineitem_type', function () {
			var company_name = $('#lineitem_type_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_lineitem_type') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Lineitem Type Name Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('input[name="id"]').val("");
						$('input[name="lineitem_type_name"]').val("");
						$('.addmorerow').remove();
						show_notify('Lineitem Type Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						$('input[name="lineitem_type_name"]').val("");
						show_notify('Lineitem Type Successfully Updated.', true);
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
					data: 'id_name=lineitem_type_id&table_name=lineitem_type',
					success: function(data){
						table.draw();
						tr.remove();
						show_notify('Deleted Successfully!',true);
						//window.location.href = "<?php echo base_url('item/item_category') ?>";
					}
				});
			}
		});
	});
</script>
