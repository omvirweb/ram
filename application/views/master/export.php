<!-- Content Wrapper. Contains page content -->
<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Export
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- START ALERTS AND CALLOUTS -->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" name="export_form" id="export_form" action="<?= base_url('master/export_data') ?>" method="post"  novalidate enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <div class="col-md-4">
                                        <label for="export_radio1" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio1" value="1">  &nbsp; Export Tally Masters &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="export_radio2" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio2" value="2">  &nbsp; Export Accounts (xls/xlsx)  &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="export_radio7" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio7" value="7">  &nbsp; Export Miracle Client Ledger (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="export_radio3" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio3" value="3">  &nbsp; Export Tally Payments &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="export_radio4" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio4" value="4">  &nbsp; Export Items CSV &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="export_radio8" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio8" value="8">  &nbsp; Export Miracle Sales Data (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="export_radio5" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio5" value="5">  &nbsp; Export Tally Receipts &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="export_radio9" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio9" value="9">  &nbsp; Export Items (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="export_radio6" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="export_radio" id="export_radio6" value="6">  &nbsp; Export Miracle Exp. Ledger (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" name="submit" class="btn btn-primary btn-sm pull-left module_save_btn export_file_form" value="Export">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>  
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
    $(document).ready(function () {
        $(document).on('click', '.export_file_form', function () {
            var radio = $('input[name="export_radio"]:checked').val()
            if (typeof (radio) == 'undefined') {
                show_notify("Please Select Export Options");
                return false;
            }
            $('#export_form').trigger('submit');
        });
    });
</script>
