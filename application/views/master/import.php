<!-- Content Wrapper. Contains page content -->
<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Import</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
                <form class="form-horizontal" name="import_form" id="import_form" action="<?= base_url('master/save_import') ?>" method="post"  novalidate enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <br><input type="file" name="userfile" id="userfile" class="form-control" required="" autofocus=""><br />
                                    </div>
                                    <div class="col-md-4">
                                        <br><input type="submit" name="submit" class="btn btn-primary btn-sm pull-left module_save_btn import_file_form" value="Upload">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <div class="col-md-4">
                                        <label for="import_radio1" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio1" value="1">  &nbsp; Import Tally Masters &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="import_radio2" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio2" value="2">  &nbsp; Import Accounts (xls/xlsx)  &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/accounts_sample.xlsx?v=1">Download Accounts Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio3" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio3" value="3">  &nbsp; Import Tally Payments &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="import_radio4" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio4" value="4">  &nbsp; Import Items CSV &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/Item_Sample.csv">Download Item CSV Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio5" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio5" value="5">  &nbsp; Import Tally Receipts &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="import_radio6" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio6" value="6">  &nbsp; Import Miracle Exp. Ledger (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_ledger_sample.xlsx">Download Miracle Exp. Ledger Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio7" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio7" value="7">  &nbsp; Import Miracle Client Ledger (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_client_ledger_sample.XLS">Download Miracle Client Ledger Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio8" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio8" value="8">  &nbsp; Import Miracle Sales Data (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_sales_data_sample.XLS">Download Miracle Sales Data Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio9" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio9" value="9">  &nbsp; Import Items (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/import_items.XLS">Download Item Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio10" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio10" value="10">  &nbsp; Import Miracle Purchase Data (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_purchase_sample.xlsx">Download Miracle Purchase Data Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio11" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio11" value="11">  &nbsp; Import Miracle Cashbook Data (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_cashbook_sample.xlsx">Download Miracle Cashbook Data Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio12" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio12" value="12">  &nbsp; Import Miracle Bankbook Data (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_bankbook_sample.xlsx">Download Miracle Bankbook Data Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio13" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio13" value="13">  &nbsp; Import Miracle Journal Data (xls/xlsx) &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/miracle_journal_sample.xlsx">Download Miracle Journal Data Sample</a>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-4">
                                        <label for="import_radio14" class="col-sm-12" style="font-size: 14px;">
                                            <input type="radio" name="import_radio" id="import_radio14" value="14">  &nbsp; Import Bank 1 &nbsp;
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?= base_url();?>uploads/OpTransactionHistory03-01-2023.xls">Download Import Bank 1</a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if(isset($duplicate_parties) && !empty($duplicate_parties)){ ?>
                                    <div class="box-header with-border">
                                        <h3>Duplicate Voucher Remote ID List</h3>
                                    </div>
                                        <?php foreach ($duplicate_parties as $key => $value) {
                                                echo '<ul>'.$value.'</ul>';
                                            } ?>
                                    <?php }?>
                                </div>
                                <div class="col-md-6">
                                    <?php if(isset($master_parties) && !empty($master_parties)){ ?>
                                    <div class="box-header with-border">
                                        <h3>Accounts Not Exist in Master</h3>
                                    </div>
                                        <?php foreach ($master_parties as $key => $value) {
                                                echo '<ul>'.$value.'</ul>';
                                            } ?>
                                    <?php }?>
                                </div>
                                <?php if(isset($import_client_ledger_data) && !empty($import_client_ledger_data)){ ?>
                                    <div class="col-md-6">
                                        <h2 style="color: red;">Not Inserted Data</h2>
                                        <table class="table table-responsive table-striped table-bordered" id="transaction_table" style="width: 100%;">
                                            <tr>
                                                <th>Credit / Debit</th>
                                                <th>Amount</th>
                                                <th>Particulars</th>
                                            </tr>
                                            <?php foreach ($import_client_ledger_data as $value) { ?>
                                                <tr>
                                                    <td><?php echo $value[0] ?></td>
                                                    <td><?php echo $value[1] ?></td>
                                                    <td><?php echo $value[2] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php } ?>

                                <?php if(isset($import_acc_data) && !empty($import_acc_data)){ ?>
                                    <div class="col-md-6">
                                        <h2 style="color: red;">Import Accounts</h2>
                                        <table class="table table-responsive table-striped table-bordered" id="transaction_table" style="width: 100%;">
                                            <tr>
                                                <th>Account</th>
                                                <th>Message</th>
                                            </tr>
                                            <?php foreach ($import_acc_data as $value) { ?>
                                                <tr>
                                                    <td><?php echo $value['account_name'] ?></td>
                                                    <td><?php echo $value['message'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php } ?>

                                <?php if(isset($import_sales_data) && !empty($import_sales_data)){ ?>
                                    <div class="col-md-6">
                                        <h2 style="color: red;">Import Sales Data</h2>
                                        <table class="table table-responsive table-striped table-bordered" id="transaction_table" style="width: 100%;">
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Message</th>
                                            </tr>
                                            <?php foreach ($import_sales_data as $value) { ?>
                                                <tr>
                                                    <td><?php echo $value['invoice_no'] ?></td>
                                                    <td><?php echo $value['message'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php } ?>
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
	$(document).ready(function(){
            $('#userfile').change(function (){
                var radio = $('input[name="import_radio"]:checked').val()
                if(typeof(radio) == 'undefined') {
                    show_notify("First Please Select Import Options");
                    $('#userfile').val('');
                    return false;
                }
                if(radio == 2 || radio == 8){
                    var fileExtension = ['xls','xlsx'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        show_notify("Only .xls or .xlsx format is allowed.");
                        $('#userfile').val('');
                        $('#userfile').focus();
                        return false;
                    }
                }
                if(radio == 3){
                    var fileExtension = ['xml'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        show_notify("Only '.xml' format is allowed.");
                        $('#userfile').val('');
                        $('#userfile').focus();
                        return false;
                    }
                }
                if(radio == 4){
                    var fileExtension = ['csv'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                        show_notify("Only '.csv' format is allowed.");
                        $('#userfile').val('');
                        $('#userfile').focus();
                        return false;
                    }
                }
            });

            $(document).on('click','.import_file_form',function(){ 
                if($('#userfile').val() == ""){
                    show_notify("Please Upload file");
                    $('#userfile').focus();
                    return false;
                }
                var radio = $('input[name="import_radio"]:checked').val()
                if(typeof(radio) == 'undefined') {
                    show_notify("Please Select Import Options");
                    return false;
                }
                $('#import_form').trigger('submit');
            });
	});
</script>
