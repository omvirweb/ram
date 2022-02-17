<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Account Group
			<?php if($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"add")) { ?>
			<a href="<?=base_url('master/account_group');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>
			
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <div class="col-md-6 col-md-push-6">
            	<?php if($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"add") || $this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"edit")) { ?>
				<form id="form_account_group" action="" enctype="multipart/form-data" data-parsley-validate="">
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
								<label for="parent_group_id">Parent Account Group</label><br />
								<select name="parent_group_id" id="parent_group_id" class="parent_group_id"></select>
							</div>
							<div class="form-group">
								<label for="account_group_name">Account Group Name<span class="required-sign">*</span></label>
								<input type="text" name="account_group_name" class="form-control" id="account_group_name" placeholder="Enter Account Group" value="<?=isset($account_group_name) ? $account_group_name : '' ?>" pattern="[^'\x22]+" title="Invalid input" required>
							</div>
							<div class="form-group">
								<label for="sequence">Sequence</label>
								<input type="text" name="sequence" class="form-control" id="sequence" placeholder="Enter Sequence" value="<?=isset($sequence) ? $sequence : '' ?>" pattern="[^'\x22]+" title="Invalid input" >
							</div>
							<div class="form-group">
                                <label for="display_in_balance_sheet" class="control-label">
                                    <input type="checkbox" name="display_in_balance_sheet" class="checkbox_ch" id="display_in_balance_sheet" value="1" data-index="14" <?= isset($display_in_balance_sheet) && $display_in_balance_sheet == 0 ? '' : 'checked' ?> /> Display In Balance Sheet?
                                </label>
                            </div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"add")) { ?>
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
				<?php if($this->applib->have_access_role(MASTER_ACCOUNT_GROUP_ID,"view")) { ?>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered account_group-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Parent Account Group</th>
									<th>Account Group</th>
									<th>Sequence</th>
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
		initAjaxSelect2($("#parent_group_id"),"<?=base_url('app/account_group_select2_source')?>");
		<?php if(isset($parent_group_id) && !empty($parent_group_id)){ ?>
		setSelect2Value($("#parent_group_id"),"<?=base_url('app/set_account_group_select2_val_by_id/'.$parent_group_id)?>");
		<?php } ?>
		table = $('.account_group-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('master/account_group_datatable')?>",
				"type": "POST"
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{"targets": 0, "orderable": false },
				{
                    "className": "text-right",
                    "targets": [3]
            	}
			]
		});

        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

        $('#parent_group_id').select2('open');
        $('#parent_group_id').on("select2:close", function(e) { 
            $("#account_group_name").focus();
        });
		$(document).on('submit', '#form_account_group', function () {
			var parent_group_id = $('#parent_group_id').val();
			var account_group_name = $('#account_group_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('master/save_account_group') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if (json['error'] == 'Exist') {
						show_notify("Account Group Already Exist", false);
					}
					if (json['success'] == 'Added') {
						table.draw();
						$('#form_account_group').find('input:text, select, textarea').val('');
						$("#parent_group_id").val(null).trigger("change");
						show_notify('Account Group Successfully Added.', true);
					}
					if (json['success'] == 'Updated') {
						table.draw();
						$('#form_account_group').find('input:text, select, textarea').val('');
						$("#parent_group_id").val(null).trigger("change");
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						show_notify('Account Group Successfully Updated.', true);
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
                    data: 'id_name=account_id&table_name=account',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Account. This Account has been used.', false);
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
