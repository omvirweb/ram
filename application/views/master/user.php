<?php $this->load->view('success_false_notify'); ?>
<style>
    .checkbox_ch{
        height: 20px;
        width: 20px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php 
            if(isset($profile_edit)) {
                echo "Profile";
            } else {
                echo "User";
            }
            ?>
            <?php if($this->applib->have_access_role(MASTER_USER_ID, "view")){ ?>
                <a href="<?= base_url('master/user_list'); ?>" class="btn btn-primary pull-right">User List</a>
            <?php } ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form id="form_user" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
                    <?php if (isset($edit_user_id) && !empty($edit_user_id)) { ?>
                        <input type="hidden" id="user_id" name="user_id" value="<?= $edit_user_id; ?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_name" class="control-label">Name<span class="required-sign">*</span></label>
                                        <input type="text" name="user_name" class="form-control" id="user_name" value="<?= isset($edit_user_name) ? $edit_user_name : '' ?>" data-index="1" placeholder="" required autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone" class="control-label"> Phone</label>
                                        <input type="text" name="phone" class="form-control num_only" id="phone" value="<?= isset($edit_phone) ? $edit_phone : '' ?>" data-index="2" placeholder="" >
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><h4><b>Login Details</b></h4><hr style="margin-top: 0px;"></div>
                                <div class="clearfix"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user" class="control-label">Email / Phone / User Name</label>
                                        <input type="text" name="user" class="form-control" id="user" value="<?= isset($edit_user) ? $edit_user : '' ?>" data-index="16" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password" class="control-label">Password</label>
                                        <input type="password" name="" class="form-control"  id="cpassword" value="" data-index="17" placeholder="" autocomplete="new-password"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cpassword" class="control-label">Confirm Password</label>
                                        <input type="password" name="password" class="form-control" data-parsley-equalto="#cpassword" id="password" value="" data-index="18" placeholder="" autocomplete="new-password"/>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn module_save_btn"><?= isset($edit_user_id) ? 'Update' : 'Save' ?></button>
                            <b style="color: #0974a7">Ctrl + S</b>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {           
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
              e.preventDefault();
              return false;
            }
        });

        shortcut.add("ctrl+s", function () {
            $(".module_save_btn").click();
        });

        <?php if (!isset($edit_user_id)) { ?>
            $('#password').attr("required","required");
            $('label[for="password"]').append('<span class="required-sign">*</span>');
            $('#cpassword').attr("required","required");
            $('label[for="cpassword"]').append('<span class="required-sign">*</span>');
        <?php } ?>
        $(document).on('submit', '#form_user', function () {
            var password = $('#password').val().length;
            var cpassword = $('#cpassword').val().length;
            if (cpassword != 0 && password < 1) {
                show_notify('Please Enter Confirm Password !', false);
                return false;
            }
            
            $('#ajax-loader').show();
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_user') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('#ajax-loader').hide();
                    var json = $.parseJSON(response);
                    if (json['error'] == 'emailExist') {
                        show_notify('Email/User Name Already Exist !', false);
                        jQuery("#email_id").focus();
                        return false;
                    }

                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('master/user') ?>";
                    }

                    if (json['error'] == 'errorAdded') {
                        show_notify('Some error has occurred !', false);
                        return false;
                    }
                    if (json['error'] == 'userExist') {
                        show_notify('Name Already Exist !', false);
                        jQuery("#user_name").focus();
                        return false;
                    }
                    if (json['error'] == 'email_error') {
                        show_notify(json['msg'], false);
                        jQuery("#email_ids").focus();
                        return false;
                    }

                    if (json['success'] == 'Updated') {
                        <?php 
                        if(isset($profile_edit)) {
                            ?>
                            window.location.reload();
                            <?php
                        } else {
                            ?>
                            window.location.href = "<?php echo base_url('master/user_list') ?>";
                            <?php
                        }
                        ?>
                        
                    }
                    return false;
                },
            });
            return false;
        });

    });
</script>
