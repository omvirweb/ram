<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title> <?='Register'?> | <?php echo $package_name; ?></title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
		<!-- iCheck -->
		<link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/square/blue.css');?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css');?>">
		<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= base_url();?>assets/dist/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?= base_url();?>assets/dist/css/ionicons.min.css">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="<?= base_url();?>assets/plugins/iCheck/all.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css');?>">
		<!----------------Notify---------------->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/notify/jquery.growl.css');?>">
		<!----------------Notify---------------->
		
		
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="<?= base_url();?>assets/plugins/datepicker/datepicker3.css">
		<!-- daterange picker -->
		<link rel="stylesheet" href="<?= base_url();?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css">
		<!-- jQuery 2.2.3 -->
		<script src="<?= base_url('assets/plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
		<!-------- /Parsleyjs --------->
		<link href="<?= base_url('assets/plugins/parsleyjs/src/parsley.css');?>" rel="stylesheet" type="text/css" />
		<!-- select 2 -->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/s2/select2.css');?>">
		<script src="<?=base_url('assets/plugins/s2/select2.full.js');?>"></script>
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css');?>">
		<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/skins/_all-skins.min.css');?>">
		<link href="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.css');?>" rel="stylesheet" type="text/css">
		<link href="<?= base_url('assets/dist/css/custom.css');?>" rel="stylesheet" type="text/css" />
	</head>
	<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
	<body class="hold-transition skin-blue layout-top-nav" >
		<div class="wrapper">
			<!-- Full Width Column -->
			<div class="content-wrapper">
				<div class="container">
					<!-- Content Header (Page header) -->
					<section class="content-header">
						<h1>
							Company
							<small></small>
						</h1>
					</section>
					<!-- Main content -->
					<section class="content">
						<div class="">
							<form id="form_user" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
								<div class="box box-primary">
									<div class="box-header with-border">
										Register
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="name" class="control-label">Name <span class="required-sign">*</span></label>
													<input type="text" name="user_name" class="form-control" id="user_name" value="<?=isset($name) ? $name : '' ?>" placeholder="" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="phone" class="control-label"> Mobile No <span class="required-sign">*</span></label>
                                                    <input type="text" name="phone" class="form-control num_only" id="phone" value="<?=isset($phone) ? $phone : '' ?>" placeholder="" required>
												</div>
											</div>

											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="email_ids" class="control-label">Company Email</label>
													<textarea name="email_ids" class="form-control" id="email_ids" placeholder=""><?=isset($email_ids) ? $email_ids : '' ?></textarea>
													<small class="">Add multiple email by comma separated.</small>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="logo_image">Logo Image</label>
													<input type="file" name="logo_image" id="logo_image">
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="box-header with-border">
													<h4 class="box-title form_title">Address</h4>
												</div>	
											</div>
											<div class="col-md-6">
												<div class="box-header with-border">
													<h4 class="box-title form_title"></h4>
												</div>	
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="address" class="control-label">Street</label>
													<textarea name="address" class="form-control" id="address" placeholder=""><?=isset($address) ? $address : '' ?></textarea>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="aadhaar" class="control-label">Aadhaar</label>
													<input type="text" name="aadhaar" class="form-control" id="aadhaar" value="" placeholder="">
												</div>
											</div>

											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="state" class="control-label">State</label>
													<select name="state" id="state" class="" ></select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="pan" class="control-label">Pan</label>
													<input type="text" name="pan" class="form-control" id="pan" value="" placeholder="">
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="city" class="control-label">City</label>
													<select name="city" id="city" class="select2" ></select>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="postal_code" class="control-label">Postal Code</label>
													<input type="number" name="postal_code" class="form-control" id="igst_per" value="<?=isset($postal_code) ? $postal_code : '' ?>" placeholder="">
												</div>
											</div>
											<div class="clearfix"></div>

											<div class="col-md-6">
												<div class="form-group">
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
													<label for="user" class="control-label">Email / Phone / User Name</label>
													<input type="text" name="user" class="form-control" id="user" value="<?=isset($user) ? $user : '' ?>" placeholder="" />
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="gst_no" class="control-label">GSTIN</label>
													<input type="text" name="gst_no" class="form-control" id="gst_no" value="<?=isset($gst_no) ? $gst_no : '' ?>" placeholder="" autocomplete="off"/>
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
									<input type="hidden" name="isActive" class="" id="" value="0" placeholder=""/>
									<!-- /.box-body -->
									<div class="box-footer">
										<button type="submit" class="btn btn-primary form_btn"><?=isset($user_id) ? 'Update' : 'Save' ?></button>
										&nbsp;&nbsp;<a href="<?=base_url('auth/login');?>" class="text-center">Alredy registered User? Click Here!</a>
									</div>
								</div>
							</form>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</section>
					<!-- /.content -->
				</div>
				<!-- /.container -->
			</div>
			<!-- /.content-wrapper -->
		</div>
		<!-- ./wrapper -->
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			$('form').parsley();
			$('#datepicker1,#datepicker2,#datepicker3').datepicker({
				format: 'dd-mm-yyyy',
				todayBtn: "linked",
				autoclose: true
			});
			//iCheck for checkbox and radio inputs
			$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
				checkboxClass: 'icheckbox_minimal-blue',
				radioClass   : 'iradio_minimal-blue'
			});
			
			$(".select2").select2({
				width:"100%",
				placeholder: " --Select-- ",
				allowClear: true,
			});
			<?php if(!isset($user_id) && empty($user_id)){ ?>

			<?php } ?>
			initAjaxSelect2($("#sales_person"),"<?=base_url('app/user_select2_source/'.SALES_ACCOUNT_GROUP_ID)?>");
			initAjaxSelect2($("#state"),"<?=base_url('app/state_select2_source')?>");
			$('#state').on('change', function() {
				$("#city").empty().trigger('change');
				var state_office = this.value;
				initAjaxSelect2($('#city'),"<?=base_url('app/city_select2_source')?>/"+state_office);
			});
			

			<?php if(isset($state) && !empty($state)){ ?>
			setSelect2Value($("#state"),"<?=base_url('app/set_state_select2_val_by_id/'.$state)?>");
			initAjaxSelect2($('#city'),"<?=base_url('app/city_select2_source/'.$state)?>");
			<?php } ?>
			<?php if(isset($city) && !empty($city)){ ?>
			setSelect2Value($("#city"),"<?=base_url('app/set_city_select2_val_by_id/'.$city)?>");
			<?php } ?>
			
			<?php if(isset($sales_person) && !empty($sales_person)){ ?>
			setSelect2Value($("#sales_person"),"<?=base_url('app/set_user_select2_val_by_id/'.$sales_person)?>");
			<?php } ?>
			<?php if(isset($account_group_id) && !empty($account_group_id)){ ?>
			setSelect2Value($("#account_group_id"),"<?=base_url('app/set_account_group_select2_val_by_id/'.$account_group_id)?>");
			<?php } ?>

			$(document).on('submit', '#form_user', function () {
				
				var password = $('#password').val().length;
				var cpassword = $('#cpassword').val().length;
				if(cpassword != 0 && password < 1){
//					$("#password").prop('required',true);
					show_notify('Please Enter Confirm Password !',false);return false;
				}
				var email_ids = $('#email_ids').val();
				var email_ids = email_ids.split(',');
				var check_duplicate = [];
				var found_duplicate = 0;
				$.each(email_ids, function(index, email) {
					if ($.inArray(email, check_duplicate) > -1) {
						found_duplicate = 1;
					} else {
						check_duplicate.push(email);
					}
				});
				if(found_duplicate == 1){
					show_notify('Duplicate Email Exist !',false);
					$("#email_ids").focus();
					return false;
				}

				var postData = new FormData(this);
				$.ajax({
					url: "<?=base_url('auth/save_user') ?>",
					type: "POST",
					processData: false,
					contentType: false,
					cache: false,
					fileElementId	:'user_image',
					data: postData,
					success: function (response) {
						var json = $.parseJSON(response);
						if(json['error'] == 'emailExist'){
							show_notify('Email/User Name Already Exist !',false);
							jQuery("#email_id").focus();
							return false;
						}
						if (json['success'] == 'Added'){
							$('#form_user').find('input:text, select, textarea').val('');
							show_notify('Registration Complete, Please contect admin to activate your user.',true);
							setTimeout(function () {window.location.href = "<?php echo base_url('auth/login') ?>";}, 3000);
						}
						if(json['error'] == 'errorAdded'){
							show_notify('Some error has occurred !',false);
							return false;
						}
						if(json['error'] == 'userExist'){
							show_notify('Company Name Already Exist !',false);
							jQuery("#name").focus();
							return false;
						}
						if(json['error'] == 'email_error'){
							show_notify(json['msg'],false);
							jQuery("#email_ids").focus();
							return false;
						}
						if (json['success'] == 'Updated'){
							window.location.href = "<?php echo base_url('user/user_list') ?>";
						}
						return false;
					},
				});
				return false;
			});

		});
		/*------------- Check For Unique --------------------*/
		function check_is_unique(table_name,column_name,column_value,id_column_name = '',id_column_value = ''){
			var DataStr = "table_name="+table_name+"&column_name="+column_name+"&column_value="+column_value+"&id_column_name="+id_column_name+"&id_column_value="+id_column_value;
			var response = '1';
			$.ajax({
				url: "<?=base_url()?>master/check_is_unique/",
				type: "POST",
				data: DataStr,
				async:false
			}).done(function(data) {
				response = data;
			});
			return response;
		}
		/*------------- Check For Unique --------------------*/
		function show_notify(notify_msg,notify_type)
		{
			if(notify_type == true){
				$.growl.notice({ title:"Success!",message:notify_msg});
			}else{
				$.growl.error({ title:"False!",message:notify_msg});
			}
		}

		/**
	 * @param $selector
	 * @constructor
     */
		function initAjaxSelect2($selector,$source_url)
		{
			$selector.select2({
				placeholder: " --Select-- ",
				allowClear: true,
				width:"100%",
				ajax: {
					url: $source_url,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page
						};
					},
					processResults: function (data,params) {
						params.page = params.page || 1;
						return {
							results: data.results,
							pagination: {
								more: (params.page * 5) < data.total_count
							}
						};
					},
					cache: true
				}
			});
		}

		function setSelect2Value($selector,$source_url = '')
		{
			if($source_url != '') {
				$.ajax({
					url: $source_url,
					type: "GET",
					data: null,
					contentType: false,
					cache: false,
					async: false,
					processData: false,
					dataType: 'json',
					success: function (data) {
						if (data.success == true) {
							$selector.empty().append($('<option/>').val(data.id).text(data.text)).val(data.id).trigger("change");
						}
					}
				});
			} else {
				$selector.empty().append($('<option/>').val('').text('--select--')).val('').trigger("change");
			}
		}
		function setSelect2MultiValue($selector,$source_url = '')
		{
			if($source_url != '') {
				$.ajax({
					url: $source_url,
					type: "GET",
					data: null,
					contentType: false,
					cache: false,
					processData: false,
					dataType: 'json',
					success: function (data) {
						if (data.success == true) {
							var selectValues = data[0];
							$.each(selectValues, function(key, value) {   
								$selector.select2("trigger", "select", {
									data: value
								});
							});
						}
					}
				});
			} else {
				$selector.empty().append($('<option/>').val('').text('--select--')).val('').trigger("change");
			}
		}
		//Tags
		function initAjaxSelect2Tags($selector,$source_url)
		{
			$selector.select2({
				placeholder: " --Select-- ",
				allowClear: true,
				width:"100%",
				tags: true,
				multiple: true,
				maximumSelectionLength: 1,
				ajax: {
					url: $source_url,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page
						};
					},
					processResults: function (data,params) {
						params.page = params.page || 1;
						return {
							results: data.results,
							pagination: {
								more: (params.page * 5) < data.total_count
							}
						};
					},
					cache: true
				}
			});
		}
        $(document).on('input',".num_only",function(){
			this.value = this.value.replace(/[^\d\.\-]/g,'');
		});
	</script>
	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
	<!-- FastClick -->
	<script src="<?php echo base_url('assets/plugins/fastclick/fastclick.js');?>"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url('assets/dist/js/app.min.js');?>"></script>
	<!-- Sparkline -->
	<script src="<?php echo base_url('assets/plugins/sparkline/jquery.sparkline.min.js');?>"></script>
	<!-- jvectormap -->
	<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
	<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>
	<!-- SlimScroll 1.3.0 -->
	<script src="<?php echo base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js');?>"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?php echo base_url('assets/dist/js/demo.js');?>"></script>
	<!-- AdminLTE JS file -->
	<script src="<?php echo base_url('assets/dist/js/adminlte.min.js');?>"></script>
	<!-------- /Parsleyjs --------->
	<script src="<?= base_url('assets/plugins/parsleyjs/dist/parsley.min.js');?>"></script>
	<!-- notify -->
	<script src="<?php echo base_url('assets/plugins/notify/jquery.growl.js');?>"></script>
	<!-- datepicker -->
	<script src="<?=base_url('assets/plugins/datepicker/bootstrap-datepicker.js');?>"></script>
	<!-- iCheck 1.0.1 -->
	<script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
	<!-- date-range-picker -->
	<script src="<?=base_url('assets/plugins/moment/min/moment.min.js');?>"></script>
	<script src="<?=base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
</html>
