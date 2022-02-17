<?php $this->load->view('success_false_notify'); ?>
<?php
    if($this->session->userdata()['userType']) {
            $userType = $this->session->userdata()['userType'];
    }
    isset($userType) ? $userType : null;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>GSTR2 Excel Export</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group row">
                            <label for="month" class="col-md-3">Month</label>
                            <div class="col-md-9">
                                <input type="text" name="month" value="" id="month" class="form-control datepicker_month" placeholder="Month" data-parsley-id="6" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="daterange" class="col-md-3" style="line-height: 30px;">Date Range</label>
                            <div class="col-md-9">
                                <input type="text" name="daterange_export" class="form-control input-sm" id="daterange_export" placeholder="dd/mm/yyyy - dd/mm/yyyy" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <a href="<?=base_url('gstr2_excel/gstr2_excel_export');?>/<?php echo date('d-m-Y').' - '.date('d-m-Y'); ?>" class="btn btn-primary gstr2_excel_export" >GSTR2 Excel Export</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function(){
        
        $(".datepicker_month").datepicker( {
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            var lastDay = new Date(minDate.getFullYear(), minDate.getMonth() + 1, 0);
            $('input[name="daterange_export"]').data('daterangepicker').setStartDate(minDate);
            $('input[name="daterange_export"]').data('daterangepicker').setEndDate(lastDay);
        });

        $('input[name="daterange_export"]').daterangepicker({
            locale: { format: 'DD-MM-YYYY' }
        });
        
        $(document).on('change','input[name="daterange_export"]',function(){
            $('.gstr2_excel_export').attr('href', '<?=base_url("gstr2_excel/gstr2_excel_export");?>/' + $("#daterange_export").val());
        });
    });
</script>
