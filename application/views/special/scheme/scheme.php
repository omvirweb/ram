<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Scheme
			<a href="<?=base_url('scheme/scheme_list');?>" class="btn btn-primary pull-right">Scheme List</a>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="form_scheme" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
					<?php if(isset($scheme_id) && !empty($scheme_id)){ ?>
					<input type="hidden" id="scheme_id" name="scheme_id" value="<?=$scheme_id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-bdiscount">
							<h3 class="box-title form_title"><?=isset($scheme_id) ? 'Edit' : 'Add' ?></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="name" class="control-label">Scheme Criteria Name<span class="required-sign">*</span></label>
										<input type="text" name="name" class="form-control" id="name" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="scheme_group_id">Between Date</label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
											<input type="text" class="form-control pull-right" id="reservation">
										</div>
										<!-- /.input group -->
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="sales_person" 	class="control-label">Supplier</label>
										<select name="supplier" id="supplier" class="supplier"></select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="office_phone" class="control-label">Company</label>
										<select name="company" id="company" class="company" ></select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="office_phone" class="control-label">Product</label>
										<select name="company" id="company" class="company" ></select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="name" class="control-label">Discount %</label>
										<input type="text" name="name" class="form-control" id="name" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="contect_person_name">Or Fix Discount</label>
										<input type="text" name="contect_person_name" class="form-control" id="contect_person_name" value="<?=isset($contect_person_name) ? $contect_person_name : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="name" class="control-label">Discount Quantity</label>
										<input type="text" name="name" class="form-control" id="name" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="contect_person_phone" class="control-label">Apply VAT</label>
									</div>
									<div class="form-group">
										<input type="radio" name="r2" class="minimal" checked> &nbsp;Before
										<input type="radio" name="r2" class="minimal">&nbsp;After
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="scheme_email_ids" class="control-label">Description</label>
										<textarea name="scheme_email_ids" class="form-control" id="scheme_email_ids" placeholder=""><?=isset($scheme_email_ids) ? $scheme_email_ids : '' ?></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="scheme_group_id">Product Discount</label>
									</div>
									<div class="form-group">
										<input type="radio" name="r1" class="minimal" checked> &nbsp;Per Product Discount
										<input type="radio" name="r1" class="minimal">&nbsp;Whole Invoice Discount
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn module_save_btn"><?=isset($scheme_id) ? 'Update' : 'Save' ?></button>
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
		$('#reservation').daterangepicker();
		$(".dfselect2").select2({
			width:"100%",
			placeholder: " --Select-- ",
			allowClear: true,
		});
		<?php if(!isset($scheme_id) && empty($scheme_id)){ ?>

		<?php } ?>
		initAjaxSelect2($("#supplier"),"<?=base_url('app/account_select2_source/'.SUPPLIER_ACCOUNT_GROUP_ID)?>");
		initAjaxSelect2($(".company"),"<?=base_url('app/company_select2_source/')?>");

		initAjaxSelect2($("#office_state"),"<?=base_url('app/state_select2_source')?>");
		$('#office_state').on('change', function() {
			$("#office_city").empty().trigger('change');
			var state_office = this.value;
			initAjaxSelect2($('#office_city'),"<?=base_url('app/city_select2_source')?>/"+state_office);
		});


		<?php if(isset($sales_person) && !empty($sales_person)){ ?>
		setSelect2Value($("#sales_person"),"<?=base_url('app/set_scheme_group_select2_val_by_id/'.$sales_person)?>");
		<?php } ?>
		<?php if(isset($scheme_group_id) && !empty($scheme_group_id)){ ?>
		setSelect2Value($("#scheme_group_id"),"<?=base_url('app/set_scheme_group_select2_val_by_id/'.$scheme_group_id)?>");
		<?php } ?>
            
        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

		$(document).on('submit', '#form_scheme', function () {
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('scheme/save_scheme') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				fileElementId	:'scheme_image',
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if(json['error'] == 'emailExist'){
						show_notify('Email/User Name Already Exist !',false);
						jQuery("#email_id").focus();
						return false;
					}
					if (json['success'] == 'Added'){
						window.location.href = "<?php echo base_url('scheme/scheme') ?>";
					}
					if(json['error'] == 'errorAdded'){
						show_notify('Some error has occurred !',false);
						return false;
					}
					if(json['error'] == 'schemeExist'){
						show_notify('Scheme Name Already Exist !',false);
						jQuery("#scheme_name").focus();
						return false;
					}
					if(json['error'] == 'email_error'){
						show_notify(json['msg'],false);
						jQuery("#scheme_email_ids").focus();
						return false;
					}
					if (json['success'] == 'Updated'){
						window.location.href = "<?php echo base_url('scheme/scheme_list') ?>";
					}
					return false;
				},
			});
			return false;
		});

	});
</script>
