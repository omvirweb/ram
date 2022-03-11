<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Log in | <?php echo $package_name; ?></title>
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
  
  <style>
	  html,body,.login-page{
		background-color: #2B3984;
		transition-duration: 0.5s;
	  }

	  .login-box-body{
		  border-radius: 20px;
	  }
	  .login-box-body:hover{
		  box-shadow: 0px 0px 7px white;
	  }

	  .form-control{
		  border-radius : 20px;
	  }

	  .btn-primary{
		  border-radius: 20px;
		  background-color: white;
		  border-color: #2B3984;
		  color: #2B3984;
		  font-weight: bold;
		  width: 100px !important;
	  }

	  .btn-primary:hover{
		  background-color:#2B3984;
		  color:white;
		  box-shadow: 0px 0px 7px highlight;
	  }

	  .bottom-logo{
		  width:50%;
	  }
  </style>

    
	
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<a href="<?=base_url();?>"></a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
    <img src="<?php echo base_url('assets/dist/img/ram-logo.png');?>" class="saas-logo" alt="saas-logo">
	
		
		<label id="username-error" class="text-danger login-box-msg" style="padding-left:80px;" for="invalid"><?php echo isset($errors['invalid'])?$errors['invalid']:''; ?><?php echo isset($errors['approve'])?$errors['approve']:''; ?></label>
		 
		<form action="<?php echo base_url('auth/login');?>" method="post">
			<div class="form-group has-feedback">
				<input type="text" autofocus class="form-control" placeholder="Email"  name="email" id="email" value="<?=set_value('email')?>">
				<span class="glyphicon glyphicon-envelope form-control-feedback" style="color:#2B3984;"></span>
				<label id="username-error" class="text-danger" for="email"><?php echo isset($errors['email'])?$errors['email']:''; ?></label>
				
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" placeholder="Password" name="pass" value="">
				<span class="glyphicon glyphicon-lock form-control-feedback" style="color:#2B3984;"></span>
				<label id="password-error" class="text-danger" for="pass"><?php echo isset($errors['pass'])?$errors['pass']:''; ?></label>
			</div>
			<div class="row">
				<div class="col-xs-0">
					<div class="checkbox icheck">
						<!--<label>
							<input type="checkbox"> Remember Me
						</label>-->
					</div>
				</div>
				<!-- /.col -->
				<center>
				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary">Sign In</button>
				</div></center>
				<!-- /.col -->
			</div>
		</form>

		<div class="social-auth-links text-center">
			<!--<a href="#">I forgot my password</a>-->
		</div>
		<!-- /.social-auth-links -->
		<br>
		<center>Welcome To The New World!</center>
		
	</div>
	<!-- /.login-box-body -->
	<br>
	<center><img src="<?php echo base_url('assets/dist/img/khatri_logo-tra.png');?>" class="bottom-logo" alt="saas-logo"></center>
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?=base_url('assets/plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
<!-- iCheck -->
<script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
<script>
	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
	});
</script>
</body>
</html>
