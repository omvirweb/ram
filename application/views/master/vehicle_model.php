<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Vehicle Model
			<a href="<?=base_url('master/vehicle_model');?>" class="btn btn-primary pull-right">Add New</a>
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
						<table class="table table-striped table-bordered vehicle-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Vehicle</th>
									<th>Vehicle Model</th>
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
				<form id="form_vehicle_model" action="" enctype="multipart/form-data" data-parsley-validate="">
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
								<label for="vehicle_id">Vehicle<span class="required-sign">*</span></label><br />
								<select name="vehicle_id" id="vehicle_id" class="vehicle_id" required></select>
							</div>
							<div class="form-group">
								<label for="vehicle_name">Vehicle Model<span class="required-sign">*</span></label>
								<input type="text" name="vehicle_model_name" class="form-control" id="vehicle_model_name" placeholder="Enter Vehicle Model" value="<?=isset($model_name) ? $model_name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn"><?=isset($id) ? 'Update' : 'Save' ?></button>
						</div>
					</div>
				</form>
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
		initAjaxSelect2($("#vehicle_id"),"<?=base_url('app/vehicle_select2_source')?>");
		<?php if(isset($vehicle_id) && !empty($vehicle_id)){ ?>
		setSelect2Value($("#vehicle_id"),"<?=base_url('app/set_vehicle_select2_val_by_id/'.$vehicle_id)?>");
		<?php } ?>
		table = $('.vehicle-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/vehicle_model_datatable')?>",
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

		$(document).on('submit', '#form_vehicle_model', function () {
			var vehicle_id = $('#vehicle_id').val();
			var vehicle_name = $('#vehicle_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_vehicle_model') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Vehicle model Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('#form_vehicle_model').find('input:text, select, textarea').val('');
						$("#vehicle_id").val(null).trigger("change");
						show_notify('Vehicle model Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('#form_vehicle_model').find('input:text, select, textarea').val('');
						$("#vehicle_id").val(null).trigger("change");
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						show_notify('Vehicle model Successfully Updated.', true);
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
					data: 'id_name=vehicle_model_id&table_name=vehicle_model',
					success: function(data){
						table.draw();
						show_notify('Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
