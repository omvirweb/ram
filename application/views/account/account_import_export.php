<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Account Import/Export</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="account_export" class="" action="<?=base_url('account/account_export') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title">
								Account Export
							</h3>
							<a class="btn btn-sm btn-danger uncheck-all pull-right">Un Select ALL</a>
							<a class="btn btn-sm btn-primary check-all pull-right" style="margin-right: 5px;">Select ALL</a>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<?php foreach($account_fields as $account_field){ ?>
									<?php if($account_field != 'account_image'){ ?>
									<div class="col-md-3">
										<div class="form-group">
											<input type="checkbox" name="<?php echo $account_field; ?>" id="<?php echo $account_field; ?>"
											 <?php if($account_field == 'account_name'){ echo 'checked'; } else { echo 'class="chkids"'; } ?> />
											<label for="<?php echo $account_field; ?>" class="control-label">
												<?php 
													$account_field = str_replace('_', ' ', $account_field);
													echo ucwords($account_field);
												?>
											</label>
										</div>
									</div>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" name="action" value="export"  class="btn btn-primary form_btn">Export</button>
							<button type="submit" name="action" value="demo" class="btn btn-info pull-right">Download Demo For Import Accounts</button>
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
		
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="account_import" class="" action="<?=base_url('account/account_import') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title">
								Account Import
							</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="import_file" class="control-label"></label>
										<input type="file" name="import_file" id="import_file" required />
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" name="action" value="export"  class="btn btn-primary">Import</button>
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
    $(document).ready(function(){
        $(".check-all").click(function(){
            $(".chkids").prop("checked",true);
        });
        $(".uncheck-all").click(function(){
            $(".chkids").prop("checked",false);
        });
        
        $(document).on('submit', '#account_export', function () {
			if($('#account_name').is(':checked')){
			} else {
				show_notify('Required Account Name for Export.', false);
				return false;
			}
		});
	});
</script>
