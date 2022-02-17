<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Log in | &#2384; Account</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/square/blue.css');?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/square/blue.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css');?>">
    
    
	
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="hold-transition login-page">
<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Company
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="form_account" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
					<?php if(isset($account_id) && !empty($account_id)){ ?>
					<input type="hidden" id="account_id" name="account_id" value="<?=$account_id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="account_name" class="control-label">Name<span class="required-sign">*</span></label>
										<input type="text" name="account_name" class="form-control" id="account_name" value="<?=isset($account_name) ? $account_name : '' ?>" placeholder="" required>
									</div>
								</div>
								<div class="col-md-6">&nbsp;</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="office_phone" class="control-label">Office Phone</label>
										<input type="number" name="office_phone" class="form-control" id="office_phone" value="<?=isset($office_phone) ? $office_phone : '' ?>" placeholder="" >
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="account_email_ids" class="control-label">Company Email</label>
										<textarea name="account_email_ids" class="form-control" id="account_email_ids" placeholder=""><?=isset($account_email_ids) ? $account_email_ids : '' ?></textarea>
										<small class="">Add multiple email by comma separated.</small>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="box-header with-border">
										<h4 class="box-title form_title">Office Address</h4>
									</div>	
								</div>
								<div class="col-md-6">
									<div class="box-header with-border">
										<h4 class="box-title form_title">Home Address</h4>
									</div>	
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="office_address" class="control-label">Street</label>
										<!--Office -->
										<textarea name="office_address" class="form-control" id="office_address" placeholder=""><?=isset($office_address) ? $office_address : '' ?></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="home_address" class="control-label">Street</label>
										<!--home-->
										<textarea name="home_address" class="form-control" id="home_address" placeholder=""><?=isset($home_address) ? $home_address : '' ?></textarea>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="office_state" class="control-label">State</label>
										<!--Office -->
										<select name="office_state" id="office_state" class="" ></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="home_state" class="control-label">State</label>
										<!--home-->
										<select name="home_state" id="home_state" class="home_state" ></select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="office_city" class="control-label">City</label>
										<!--Office -->
										<select name="office_city" id="office_city" class="select2" ></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="home_city" class="control-label">City</label>
										<!--home-->
										<select name="home_city" id="home_city" class="select2" ></select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="office_postal_code" class="control-label">Postal Code</label>
										<!--Office -->
										<input type="number" name="office_postal_code" class="form-control" id="igst_per" value="<?=isset($office_postal_code) ? $office_postal_code : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="home_postal_code" class="control-label">Postal Code</label>
										<!--home-->
										<input type="number" name="home_postal_code" class="form-control" id="home_postal_code" value="<?=isset($home_postal_code) ? $home_postal_code : '' ?>" placeholder="">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="home_phone" class="control-label">Home Phone</label>
										<!--home-->
										<input type="number" name="home_phone" class="form-control" id="home_phone" value="<?=isset($home_phone) ? $home_phone : '' ?>" placeholder="">
										
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="box-header with-border">
										<h4 class="box-title form_title">More Imformation</h4>
									</div>	
								</div>
								<div class="col-md-6">
									<div class="box-header with-border">
										<h4 class="box-title form_title">Login Details</h4>
									</div>	
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="contect_person_name">Contact Person Name</label>
										<input type="text" name="contect_person_name" class="form-control" id="contect_person_name" value="<?=isset($contect_person_name) ? $contect_person_name : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="email_id" class="control-label">Email / Phone / User Name</label>
										<input type="text" name="email_id" class="form-control" id="email_id" value="<?=isset($email_id) ? $email_id : '' ?>" placeholder="" />
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="contect_person_phone" class="control-label">Contact Person Phone</label>
										<input type="number" name="contect_person_phone" class="form-control" id="contect_person_phone" value="<?=isset($contect_person_phone) ? $contect_person_phone : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="password" class="control-label">Password</label>
										<input type="password" name="" class="form-control"  id="cpassword" value="" placeholder="" autocomplete="off"/>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="gst_no" class="control-label">GST No</label>
										<input type="text" name="gst_no" class="form-control" id="gst_no" value="<?=isset($gst_no) ? $gst_no : '' ?>" placeholder="" autocomplete="off"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="cpassword" class="control-label">Confirm Password</label>
										<input type="password" name="password" class="form-control" data-parsley-equalto="#cpassword" id="password" value="" placeholder="" autocomplete="off"/>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn"><?=isset($account_id) ? 'Update' : 'Save' ?></button>
              &nbsp;&nbsp;<a href="login" class="text-center">Alredy registered User? Click Here!</a>
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
		$(".select2").select2({
			width:"100%",
			placeholder: " --Select-- ",
			allowClear: true,
		});
		<?php if(!isset($account_id) && empty($account_id)){ ?>
		
		<?php } ?>
		initAjaxSelect2($("#sales_person"),"<?=base_url('app/account_select2_source/'.SALES_ACCOUNT_GROUP_ID)?>");
		initAjaxSelect2($("#account_group_id"),"<?=base_url('app/account_group_select2_source_for_account/')?>");
		initAjaxSelect2($("#office_state"),"<?=base_url('app/state_select2_source')?>");
		$('#office_state').on('change', function() {
			$("#office_city").empty().trigger('change');
			var state_office = this.value;
			initAjaxSelect2($('#office_city'),"<?=base_url('app/city_select2_source')?>/"+state_office);
		});
		initAjaxSelect2($("#home_state"),"<?=base_url('app/state_select2_source')?>");
		$('#home_state').on('change', function() {
			$("#home_city").empty().trigger('change');
			var state_home = this.value;
			initAjaxSelect2($('#home_city'),"<?=base_url('app/city_select2_source')?>/"+state_home);
		});
		
		
		<?php if(isset($home_state) && !empty($home_state)){ ?>
		setSelect2Value($("#home_state"),"<?=base_url('app/set_state_select2_val_by_id/'.$home_state)?>");
		initAjaxSelect2($('#home_city'),"<?=base_url('app/city_select2_source/'.$home_state)?>");
		<?php } ?>
		
		<?php if(isset($office_state) && !empty($office_state)){ ?>
		setSelect2Value($("#office_state"),"<?=base_url('app/set_state_select2_val_by_id/'.$office_state)?>");
		initAjaxSelect2($('#office_city'),"<?=base_url('app/city_select2_source/'.$office_state)?>");
		<?php } ?>
		<?php if(isset($office_city) && !empty($office_city)){ ?>
		setSelect2Value($("#office_city"),"<?=base_url('app/set_city_select2_val_by_id/'.$office_city)?>");
		<?php } ?>
		<?php if(isset($home_city) && !empty($home_city)){ ?>
		setSelect2Value($("#home_city"),"<?=base_url('app/set_city_select2_val_by_id/'.$home_city)?>");
		<?php } ?>

		
		<?php if(isset($sales_person) && !empty($sales_person)){ ?>
		setSelect2Value($("#sales_person"),"<?=base_url('app/set_account_select2_val_by_id/'.$sales_person)?>");
		<?php } ?>
		<?php if(isset($account_group_id) && !empty($account_group_id)){ ?>
		setSelect2Value($("#account_group_id"),"<?=base_url('app/set_account_group_select2_val_by_id/'.$account_group_id)?>");
		<?php } ?>
		
		$(document).on('submit', '#form_account', function () {
			
			var account_email_ids = $('#account_email_ids').val();
			var account_email_ids = account_email_ids.split(',');
			var check_duplicate = [];
			var found_duplicate = 0;
			$.each(account_email_ids, function(index, email) {
				if ($.inArray(email, check_duplicate) > -1) {
					found_duplicate = 1;
				} else {
					check_duplicate.push(email);
				}
			});
			if(found_duplicate == 1){
				show_notify('Duplicate Email Exist !',false);
				$("#account_email_ids").focus();
				return false;
			}
			
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('account/save_account') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				fileElementId	:'account_image',
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if(json['error'] == 'emailExist'){
						show_notify('Email/User Name Already Exist !',false);
						jQuery("#email_id").focus();
						return false;
					}
					if (json['success'] == 'Added'){
						window.location.href = "<?php echo base_url('account/account') ?>";
					}
					if(json['error'] == 'errorAdded'){
						show_notify('Some error has occurred !',false);
						return false;
					}
					if(json['error'] == 'accountExist'){
						show_notify('Company Name Already Exist !',false);
						jQuery("#account_name").focus();
						return false;
					}
					if(json['error'] == 'email_error'){
						show_notify(json['msg'],false);
						jQuery("#account_email_ids").focus();
						return false;
					}
					if (json['success'] == 'Updated'){
						window.location.href = "<?php echo base_url('account/account_list') ?>";
					}
					return false;
				},
			});
			return false;
		});

	});
</script>
</body>
</html>
