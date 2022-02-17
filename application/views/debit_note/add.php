<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Debit Note (Purchase Return)
			<?php if($this->applib->have_access_role(MODULE_DEBIT_NOTE_ID,"view")) { ?>
			<a href="<?=base_url('debit_note/list_page');?>" class="btn btn-primary pull-right">Debit Note List</a>
			<?php } ?>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="form_debit_note" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
					<?php if(isset($debit_note_data->debit_note_id) && !empty($debit_note_data->debit_note_id)){ ?>
					<input type="hidden" id="debit_note_id" name="debit_note_id" value="<?=$debit_note_data->debit_note_id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title">
								<?=isset($debit_note_data->debit_note_id) ? 'Edit' : 'Add' ?>
								<?php if(isset($debit_note_data->debit_note_no)){ echo '<span> &nbsp;&nbsp;&nbsp;&nbsp; Debit Note No. : '. $debit_note_data->debit_note_no . '</span>'; } ?>
							</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
										<a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account/')?>"><i class="fa fa-plus"></i> Add Account</a>
										<select name="account_id" id="account_id" class="account_id" required data-index="1" ></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="debit_note_date" class="control-label">Debit Note Date<span class="required-sign">*</span></label>
										<input type="text" name="debit_note_date" id="datepicker2" class="form-control" required data-index="2" value="<?=isset($debit_note_data->debit_note_date) ? date('d-m-Y', strtotime($debit_note_data->debit_note_date)) : date('d-m-Y'); ?>">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="bill_no" class="control-label">Invoice No.<span class="required-sign">*</span></label>
												<input type="text" name="bill_no" id="bill_no" class="form-control" required data-index="3" value="<?=isset($debit_note_data->bill_no) ? $debit_note_data->bill_no : ''; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="invoice_date" class="control-label">Invoice Date<span class="required-sign">*</span></label>
												<input type="text" name="invoice_date" id="datepicker3" class="form-control" required data-index="4" value="<?=isset($debit_note_data->invoice_date) ? date('d-m-Y', strtotime($debit_note_data->invoice_date)) : ''; ?>">
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								
								
								<div class="col-md-12">
									<div class="box-header with-border">
									</div>	
									<?php $this->load->view('line_items'); ?>
								</div>
								<div class="clearfix"></div>
								
								<div class="col-md-6">
									<br/>
									<div class="form-group">
										<label for="debit_note_desc" class="control-label">Description</label>
										<textarea name="debit_note_desc" id="debit_note_desc" class="form-control" data-index="5" placeholder=""><?=isset($debit_note_data->debit_note_desc) ? $debit_note_data->debit_note_desc : '' ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn module_save_btn"><?=isset($debit_note_data->debit_note_id) ? 'Update' : 'Save' ?></button>
                                                        <b style="color: #0974a7">Ctrl + S</b>
						</div>
					</div>
				</form>
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
		var jsonObj = [];
		$(".dfselect2").select2({
			width:"100%",
			placeholder: " --Select-- ",
			allowClear: true,
		});
		initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");
		<?php if(isset($debit_note_data->account_id)){ ?>
			setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/')?>" + <?=$debit_note_data->account_id; ?>);
		<?php } ?>
            
                shortcut.add("ctrl+s", function() {  
                    $( ".module_save_btn" ).click();
                });

                $('#account_id').select2('open');
                $('#account_id').on("select2:close", function(e) { 
                    $("#datepicker2").focus();
                });
                $('#item_id').on("select2:close", function(e) { 
                    $("#item_qty").focus();
                });
                $('#unit_id').on("select2:close", function(e) { 
                    $("#price").focus();
                });
		
		$(document).on('submit', '#form_debit_note', function () {
			if(lineitem_objectdata == ''){
				show_notify("Please select any one Product.", false);
				return false;
			}
			$('.overlay').show();
			var postData = new FormData(this);
			var lineitem_objectdata_var = JSON.stringify(lineitem_objectdata);
                postData.append('line_items_data', lineitem_objectdata_var);
			$.ajax({
				url: "<?=base_url('debit_note/save_debit_note') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['error'] == 'Locked_Date'){
                        show_notify("Selected Date Locked, Please Choose Other Date.", false);
                        $('.overlay').hide();
                        return false;
                    }
					if (json['success'] == 'Added'){
						window.location.href = "<?php echo base_url('debit_note/add/') ?>";
					}
					if (json['success'] == 'Updated'){
						window.location.href = "<?php echo base_url('debit_note/list_page') ?>";
					}
					$('.overlay').hide();
					return false;
				},
			});
			return false;
		});
		
	});
</script>
