<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			State
			<?php if($this->applib->have_access_role(MASTER_STATE_ID,"add")) { ?>
			<a href="<?=base_url('master/state');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <div class="col-md-6 col-md-push-6">
            	<?php if($this->applib->have_access_role(MASTER_STATE_ID,"add") || $this->applib->have_access_role(MASTER_STATE_ID,"edit")) { ?>
				<form id="form_state" action="" enctype="multipart/form-data" data-parsley-validate="">
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
								<label for="state_name">State Name<span class="required-sign">*</span></label>
                                                                <input type="text" name="state_name" class="form-control" id="state_name" placeholder="Enter State" value="<?=isset($name) ? $name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required autofocus="">
							</div>
                                                        <div class="form-group">
								<label for="state_code">State Code<span class="required-sign">*</span></label>
                                                                <input type="text" name="state_code" class="form-control num_only" id="state_code" placeholder="Enter State Code" value="<?=isset($code) ? $code : '' ?>" maxlength="2" required autofocus="">
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MASTER_STATE_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_STATE_ID,"add")) { ?>
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
				<?php if($this->applib->have_access_role(MASTER_STATE_ID,"view")) { ?>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered state-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>State Name</th>
                                                                        <th>State Code</th>
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
            $('#state_name').on('keyup kedown',function(){
                $(this).css('text-transform','capitalize');
            })
		table = $('.state-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/state_datatable')?>",
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

		$(document).on('submit', '#form_state', function () {
			var company_name = $('#state_name').val();
                        var state_code = $('#state_code').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_state') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("State Name Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('input[name="id"]').val("");
						$('input[name="state_name"]').val("");
                                                $('input[name="state_code"]').val("");
						$('.addmorerow').remove();
						show_notify('State Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						$('input[name="state_name"]').val("");
                                                $('input[name="state_code"]').val("");
						show_notify('State Successfully Updated.', true);
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
                    data: 'id_name=state_id&table_name=state',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this State. This State has been used.', false);
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
