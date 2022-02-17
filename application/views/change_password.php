<?php if ($this->session->flashdata('success') == true) { ?>
<script>
    $( document ).ready(function() {
        show_notify('<?php echo $this->session->flashdata('message'); ?>',true);    
    });
</script>
<?php } ?>
<?php $this->load->library('form_validation');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Change Password

        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">  <div class="col-md-12 display_alert"></div></div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body table-responsive">
                        <form action="<?=base_url();?>auth/change_password/" method="post" id="frm_cpwd">
                            <input type="hidden" name="user_id" value="<?=isset($user_id)?$user_id:'';?>">

                            <div class="form-group">
                                <label class="control-labelsm">Old Password</label>
                                <input type="password" name="old_pass" class="form-control" parsley-trigger="change"  autofocus>
                                <?php if(isset($errors['old_pass'])){?><label id="name-error" class="control-label input-sm text-danger" for="old_pass"><?=$errors['old_pass']?></label><?php } ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">New Password</label>
                                <input type="password" name="new_pass" id="new_pass" class="form-control" parsley-trigger="change"   >
                                <?php if(isset($errors['new_pass'])){?><label id="name-error" class="control-label input-sm text-danger" for="old_pass"><?=$errors['new_pass']?></label><?php } ?>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Confirm Password</label>
                                <input type="password" name="confirm_pass"  class="form-control" parsley-trigger="change" >
                                <?php if(isset($errors['confirm_pass'])){?><label id="name-error" class="control-label input-sm text-danger" for="confirm_pass"><?=$errors['confirm_pass']?></label><?php } ?>
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Change</button>
                                <button class="btn  waves-effect " type="reset">Reset</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
<script>

    $("#frm_cpwd").validate({
        rules: {
            old_pass: "required",
            new_pass: "required",
            confirm_pass: {
                equalTo: "#new_pass",
                required: true
            }
        },
        messages: {
            old_pass: "Please provide a old password",
            new_pass: "Please provide a new password",
            confirm_pass: {
                required: "Please provide a conform password",
                equalTo: "Please enter the same password as above."
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

</script>

