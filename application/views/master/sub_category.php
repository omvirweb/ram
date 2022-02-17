<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Sub Category
			<a href="<?=base_url('master/sub_category');?>" class="btn btn-primary pull-right">Add New</a>
			<?php if($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"add")) { ?>
			<a href="<?=base_url('master/item_group');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-6 col-md-push-6">
				<?php if($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"add") || $this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"edit")) { ?>
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
                                <label for="cat_id">Category<span class="required-sign">&nbsp;*</span></label><br />
                                <select name="cat_id" id="cat_id" class="cat_id" required=""></select>
                            </div>
                            <div class="form-group">
								<label for="sub_cat_name">Sub Category<span class="required-sign">&nbsp;*</span></label>
								<input type="text" name="sub_cat_name" class="form-control" id="sub_cat_name" placeholder="Enter Category" value="<?=isset($sub_cat_name) ? $sub_cat_name : '' ?>" title="Invalid input" required>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"add")) { ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Save</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } ?>
						</div>
					</div>
				</form>
				<!-- /.box -->
				<?php } ?>
			</div>
			<!-- /.col -->

			<div class="col-md-6 col-md-pull-6">
				<?php if($this->applib->have_access_role(MASTER_SUB_CATEGORY_ID,"view")) { ?>
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
									<th>Sub Category</th>
								</tr>
							</thead>
							<tbody></tbody>
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
        
        initAjaxSelect2($("#cat_id"),"<?=base_url('app/category_select2_source')?>");
        <?php if(isset($cat_id) && !empty($cat_id)){ ?>
            setSelect2Value($("#cat_id"),"<?=base_url('app/set_category_select2_val_by_id/'.$cat_id)?>");
        <?php } ?>
        
		table = $('.category-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/sub_category_datatable')?>",
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
				url: "<?=base_url('master/save_sub_category') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Sub Category Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('#form_category').find('input:text, select, textarea').val('');
                        $("#cat_id").val(null).trigger("change");
						show_notify('Sub Category Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('#form_category').find('input:text, select, textarea').val('');
                        $("#cat_id").val(null).trigger("change");
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						show_notify('Sub Category Successfully Updated.', true);
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
					data: 'id_name=sub_cat_id&table_name=sub_category',
					success: function(data){
						table.draw();
						show_notify('Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
