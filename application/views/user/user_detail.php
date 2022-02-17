<?php if(isset($account_id) && !empty($account_id)){ } else { ?>
	<script type="text/javascript">
		$(document).ready(function(){ window.location.href = '<?=base_url('account/account_list');?>'; });
	</script>
<?php } ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Account
			<a href="<?=base_url('account/account_list');?>" class="btn btn-primary pull-right">Account List</a>
			<a href="<?=base_url('account/account');?>" class="btn btn-primary pull-right" style="margin-right: 10px;" >Add New</a>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title form_title">
							Detail &nbsp;
							<form id="edit_<?=$account_id; ?>" method="post" action="<?=base_url('account/account');?>" class="pull-right" style="width: 30px; display: initial;" >
								<input type="hidden" name="account_id" id="account_id" value="<?=$account_id; ?>" />
								<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById('edit_<?=$account_id; ?>').submit();" title="Edit Account"><i class="fa fa-edit"></i></a>
							</form> 
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr>
										<th style="width:25%">Account Name:</th>
										<td><?=isset($account_name) ? $account_name : '' ?></td>
										<th style="width:25%">Account_email_ids:</th>
										<td><?=isset($account_email_ids) ? $account_email_ids : '' ?></td>
									</tr>
									<tr>
										<th>Sales Person:</th>
										<td><?=isset($sales_person) ? $sales_person : '' ?></td>
										<th></th>
										<td></td>
									</tr>
									<tr>
										<th>Office Address:</th>
										<td>
											<?=isset($office_address) ? $office_address : '' ?><br />
											State : <?=isset($office_state) ? $office_state : '' ?><br />
											City : <?=isset($office_city) ? $office_city : '' ?><br />
											Postal Code : <?=isset($office_postal_code) ? $office_postal_code : '' ?><br />
											Phone No. : <?=isset($office_phone) ? $office_phone : '' ?>
										</td>
										<th>Home Address:</th>
										<td>
											<?=isset($home_address) ? $home_address : '' ?><br />
											State : <?=isset($home_state) ? $home_state : '' ?><br />
											City : <?=isset($home_city) ? $home_city : '' ?><br />
											Postal Code : <?=isset($home_postal_code) ? $home_postal_code : '' ?><br />
											Phone No. : <?=isset($home_phone) ? $home_phone : '' ?>
										</td>
									</tr>
									<tr>
										<th>contect_person_name:</th>
										<td><?=isset($contect_person_name) ? $contect_person_name : '' ?></td>
										<th>contect_person_phone:</th>
										<td><?=isset($contect_person_phone) ? $contect_person_phone : '' ?></td>
									</tr>
									<tr>
										<th>account_group_id:</th>
										<td><?=isset($account_group_id) ? $account_group_id : '' ?></td>
										<th>email_id:</th>
										<td><?=isset($email_id) ? $email_id : '' ?></td>
									</tr>
									<tr>
										<th>gst_no:</th>
										<td><?=isset($gst_no) ? $gst_no : '' ?></td>
										<th></th>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					</div>
				</div>
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
	$(document).ready(function(){ });
</script>
