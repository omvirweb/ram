<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Rack
			<a href="<?=base_url('master/rack');?>" class="btn btn-primary pull-right">Add New</a>
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
						<table class="table table-striped table-bordered rack-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Location</th>
									<th>Rack</th>
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
				<form id="form_rack" action="" enctype="multipart/form-data" data-parsley-validate="">
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
								<label for="location_id">Location<span class="required-sign">*</span></label><br />
								<select name="location_id" id="location_id" class="location_id" required></select>
							</div>
							<div class="form-group">
								<label for="rack_name">Rack<span class="required-sign">*</span></label>
								<input type="text" name="rack_name" class="form-control" id="rack_name" placeholder="Enter Rack" value="<?=isset($model_name) ? $model_name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
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
		initAjaxSelect2($("#location_id"),"<?=base_url('app/location_select2_source')?>");
		<?php if(isset($location_id) && !empty($location_id)){ ?>
		setSelect2Value($("#location_id"),"<?=base_url('app/set_location_select2_val_by_id/'.$location_id)?>");
		<?php } ?>
		table = $('.rack-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/rack_datatable')?>",
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

		$(document).on('submit', '#form_rack', function () {
			var location_id = $('#location_id').val();
			var rack_name = $('#rack_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_rack') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Rack Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('#form_rack').find('input:text, select, textarea').val('');
						$("#location_id").val(null).trigger("change");
						show_notify('Rack Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('#form_rack').find('input:text, select, textarea').val('');
						$("#location_id").val(null).trigger("change");
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						show_notify('Rack Successfully Updated.', true);
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
					data: 'id_name=rack_id&table_name=rack',
					success: function(data){
						table.draw();
						show_notify('Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
