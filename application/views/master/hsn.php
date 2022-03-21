<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			HSN
			<?php if($this->applib->have_access_role(MASTER_HSN_ID,"add")) { ?>
			<a href="<?=base_url('master/hsn');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <div class="col-md-6 col-md-push-6">
            	<?php if($this->applib->have_access_role(MASTER_HSN_ID,"add") || $this->applib->have_access_role(MASTER_HSN_ID,"edit")) { ?>
				<form id="form_hsn" action="" enctype="multipart/form-data" data-parsley-validate="">
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
								<label for="hsn">HSN<span class="required-sign">*</span></label><br />
                                                                <input type="text" name="hsn" class="form-control" id="hsn" placeholder="Enter HSN" value="<?=isset($hsn) ? $hsn : '' ?>" required autofocus="">
							</div>
							<div class="form-group">
								<label for="gst_per">GST %</label>
								<input type="text" name="gst_per" class="form-control" id="gst_per" placeholder="Enter GST" value="<?=isset($gst_per) ? $gst_per : '' ?>">
							</div>
							<div class="form-group">
								<label for="hsn_discription">HSN Discription</label>
								<input type="text" name="hsn_discription" class="form-control" id="hsn_discription" placeholder="Enter Discription" value="<?=isset($hsn_discription) ? $hsn_discription : '' ?>">
							</div>

							<?php 
								if( isset($hsn) ){?>
									<div style="border: 2px solid green;width: fit-content;padding: 10px;"> 
										<?php 
											$created_user = $this->crud->get_data_row_by_id('user', 'user_id', $created_by);
											$updated_user = $this->crud->get_data_row_by_id('user', 'user_id', $updated_by);
										?>
										<p class="text-success"><b>Add By : <?php echo $created_user->user_name; ?> @<?php echo $created_at; ?></b></p>
										<p class="text-success"><b>Last Edit By : <?php echo $updated_user->user_name; ?> @<?php echo $updated_at; ?></b></p>
										</div>
								<?php }
							?>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MASTER_HSN_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_HSN_ID,"add")) { ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Save</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } ?>
						</div>
					</div>
				</form>
				<!-- /.box -->
				<?php } ?>
			</div>
            
			<div class="col-md-6 col-md-pull-6">
				<?php if($this->applib->have_access_role(MASTER_HSN_ID,"view")) { ?>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered hsn-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>HSN</th>
									<th>GST %</th>
									<th>HSN Discription</th>
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
		table = $('.hsn-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/hsn_datatable')?>",
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
            
		$(document).on('submit', '#form_hsn', function () {
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_hsn') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['success'] == 'Added') {
						$('#form_hsn').find('input:text, select, textarea').val('');
						table.draw();
						show_notify('HSN Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('#form_hsn').find('input:text, select, textarea').val('');
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						show_notify('HSN Successfully Updated.', true);
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
					data: 'id_name=hsn_id&table_name=hsn',
					success: function(data){
						table.draw();
						show_notify('Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
