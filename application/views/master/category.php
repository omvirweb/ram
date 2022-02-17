<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Category
			<?php if($this->applib->have_access_role(MASTER_CATEGORY_ID,"add")) { ?>
			<a href="<?=base_url('master/category');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-6 col-md-push-6">
				<?php if($this->applib->have_access_role(MASTER_CATEGORY_ID,"add") || $this->applib->have_access_role(MASTER_CATEGORY_ID,"edit")) { ?>
				<form id="form_category" action="" enctype="multipart/form-data" data-parsley-validate="">
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
                                <label for="cat_name">Category<span class="required-sign">&nbsp;*</span></label>
								<input type="text" name="cat_name" class="form-control" id="cat_name" placeholder="Enter Category" value="<?=isset($cat_name) ? $cat_name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MASTER_CATEGORY_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_CATEGORY_ID,"add")) { ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Save</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } ?>
						</div>
					</div>
				</form>
				<?php } ?>
			</div>
			<!-- /.col -->

			<div class="col-md-6 col-md-pull-6">
				<?php if($this->applib->have_access_role(MASTER_CATEGORY_ID,"view")) { ?>
                <div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered category-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Category</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<!---content end--->
					</div>
					<!-- /.box-body -->
				</div>
				<?php } ?>
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
		table = $('.category-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/category_datatable')?>",
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

		$(document).on('submit', '#form_category', function () {
			var cat_name = $('#cat_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_category') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Category Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('#form_category').find('input:text, select, textarea').val('');
						show_notify('Category Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('#form_category').find('input:text, select, textarea').val('');
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						show_notify('Category Successfully Updated.', true);
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
					data: 'id_name=cat_id&table_name=category',
					success: function(data){
						table.draw();
						show_notify('Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
