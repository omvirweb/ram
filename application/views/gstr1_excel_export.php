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
        <h1>GSTR1 Excel Export</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if($userType == 'Admin'){ ?>
            <div class="col-md-6">
                <form id="save_lock_unlock" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="daterange" class="col-md-3" style="line-height: 30px;">Date Range</label>
                                <div class="col-md-9">
                                    <input type="text" name="daterange" class="form-control input-sm" id="daterange" placeholder="dd/mm/yyyy - dd/mm/yyyy" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="" class="col-md-3" style="line-height: 30px;">Data</label>
                                <div class="col-md-9">
                                    <div class="clearfix">
                                        <input type="radio" name="data_lock_unlock" id="lock" value="Lock" required/> <label for="lock"  style="line-height: 30px;" >Lock</label> &nbsp;&nbsp;&nbsp;
                                        <!--<input type="radio" name="data_lock_unlock" id="unlock" value="Unlock" checked /> <label for="unlock"  style="line-height: 30px;" >Unlock</label> &nbsp;&nbsp;&nbsp;-->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="box-footer">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-primary form_btn" style="margin-right:5px;" >Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php } ?>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
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
                            <a href="<?=base_url('gstr1_excel/gstr1_excel_export');?>/<?php echo date('d-m-Y').' - '.date('d-m-Y'); ?>" class="btn btn-primary gstr1_excel_export" >GSTR1 Excel Export</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <?php if($userType == 'Admin'){ ?>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Locked Date Range List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!---content start --->
                        <table class="table table-striped table-bordered locked_daterange-table">
                            <thead>
                                <tr>
                                    <th width="100px">Action</th>
                                    <th>Company Name</th>
                                    <th>Locked Date Range</th>
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
            </div>
            <!-- /.col -->
            <?php } ?>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function(){
        $('input[name="daterange"]').daterangepicker({
            locale: { format: 'DD-MM-YYYY' }
        });
        $('input[name="daterange_export"]').daterangepicker({
            locale: { format: 'DD-MM-YYYY' }
        });
        initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");

        $(document).on('change','input[name="daterange_export"]',function(){
            $('.gstr1_excel_export').attr('href', '<?=base_url("gstr1_excel/gstr1_excel_export");?>/' + $("#daterange_export").val());
        });

        table = $('.locked_daterange-table').DataTable({
            "serverSide": true,
            "ordering": true,
            "searching": true,
            "aaSorting": [[0, 'desc']],
            "ajax": {
                "url": "<?php echo base_url('gstr1_excel/locked_daterange_datatable')?>",
                "type": "POST"
            },
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
            "scroller": {
                "loadingIndicator": true
            },
            "columnDefs": [
                {"targets": 0, "orderable": false }
            ]
        });

        $(document).on('submit', '#save_lock_unlock', function () {
            value = $("input[name='data_lock_unlock']:checked").val();
            var value = confirm('Are you sure You want to '+ value +' Data?');
            var tr = $(this).closest("tr");
            if(value){
                var postData = new FormData(this);
                $.ajax({
                    url: "<?=base_url('gstr1_excel/get_pending_locked_dates') ?>",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: postData,
                    success: function (response) {
                        var json = $.parseJSON(response);
                        console.log(json);
                        if (json['dates'] != ''){
                            var output = '';
                            for (var entry in json['dates']) {
                                output += json['dates'][entry] + ',\n';
                            }
                            var conf = confirm('Old Pending Dates For Lock \n'+ output);
                            if(conf){
                                submit_form_data(postData);
                            }
                        }else{
                            submit_form_data(postData);
                        }
                        return false;
                    },
                });
            }
            return false;
        });

        function submit_form_data(postData){
            $('.overlay').show();
            $.ajax({
                url: "<?=base_url('gstr1_excel/save_lock_unlock') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Updated'){
                        window.location.href = "<?php echo base_url('gstr1_excel') ?>";
                    }
                    if (json['error'] == 'Error'){
                        show_notify("Please select Date.", false);
                    }
                    $('.overlay').hide();
                    return false;
                },
            });
        }

    });
    $(document).on("click",".delete_button",function(){
        var value = confirm('Are you sure delete this records?');
        var tr = $(this).closest("tr");
        if(value){
            $.ajax({
                url: $(this).data('href'),
                type: "POST",
                data: 'id_name=id&table_name=locked_daterange',
                success: function(data){
                    table.draw();
                    show_notify('Deleted Successfully!',true);
                }
            });
        }
    });
</script>
