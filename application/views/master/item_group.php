<?php
$array_items = array('name' => '', 'email' => '');
$this->session->unset_userdata($array_items);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Item Group
			<?php if($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"add")) { ?>
			<a href="<?=base_url('master/item_group');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <div class="col-md-6 col-md-push-6">
            	<?php if($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"add") || $this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"edit")) { ?>
				<form id="form_item_group" action="" enctype="multipart/form-data" data-parsley-validate="">
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
								<label for="item_group_name">Item Group Name<span class="required-sign">*</span></label>
								<input type="text" name="item_group_name" class="form-control" id="item_group_name" placeholder="Enter Item Group Name" value="<?=isset($name) ? $name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
							</div>
                            <div class="form-group">
                                <label for="discount_on" class="control-label">Discount On</label><br />
                                <select name="discount_on" id="discount_on" class="form-control" >
                                    <option value="1" <?php echo (isset($edit_discount_on) && $edit_discount_on == 1) ? 'selected' : '' ?>>List Price</option>
                                    <option value="2" <?php echo isset($edit_discount_on) ? ($edit_discount_on == 2) ? 'selected' : '' : 'selected' ?>>MRP</option>
                                </select>
                            </div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"add")) { ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Save</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } ?>
						</div>
					</div>
				</form>
				<!-- /.box-body -->
				<!-- /.box -->
				<?php } ?>
			</div>
			<div class="col-md-6 col-md-pull-6">
				<?php if($this->applib->have_access_role(MASTER_ITEM_GROUP_ID,"view")) { ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">List</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<!---content start --->
							<table class="table table-striped table-bordered item_group-table">
								<thead>
									<tr>
										<th>Action</th>
										<th>Item Group Name</th>
										<th>Discount On</th>
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
		table = $('.item_group-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/item_group_datatable')?>",
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

		$(document).on('submit', '#form_item_group', function () {
			var item_type_name = $('#item_group_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_item_group') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Item Group Already Exist", false);
					}
					if (json['success'] == 'Added') {
						window.location.href = "<?php echo base_url('master/item_group') ?>";
					}
					if (json['success'] == 'Updated') {
						window.location.href = "<?php echo base_url('master/item_group') ?>";
					}
					return false;
				},
			});
			return false;
		});

		$(document).on("click",".delete_button",function(){
			if(confirm('Are you sure delete this records?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=item_group_id&table_name=item_group',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Item Group. This Item Group has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Deleted Successfully!', true);
                        }
                    }
                });
            }
		});
	});
</script>
