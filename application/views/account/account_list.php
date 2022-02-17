<?php $this->load->view('success_false_notify'); ?>
<style>
	table.dataTable tr th.select-checkbox.selected::after {
		content: "✔";
		margin-top: -11px;
		margin-left: -4px;
		text-align: center;
		text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Account
			<?php if($this->applib->have_access_role(MASTER_ACCOUNT_COMPANY_ID, "view")){ ?>
                <a href="<?=base_url('account/account');?>" class="btn btn-primary pull-right">Add New</a>
            <?php } ?>			
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered account-table display" id="example">
							<thead>
								<tr>
									<th>Action</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Account Group</th>
									<th>Opening Balance</th>
									<th>Credit / Debit</th>
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
		</div>
		<!-- /.row -->
		<!-- END ALERTS AND CALLOUTS -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
	var table;
	$(document).ready(function (){
		var table = $('#example').DataTable({

			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('account/account_datatable')?>",
				"type": "POST"
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			'columnDefs': [{
				'targets': 0,
				'searchable':false,
				'orderable':false,
			}],
			'order': [1, 'asc'],
			"columnDefs": [{
                    "className": "text-right",
                    "targets": [5]
            }],
		});

		$(document).on("click",".delete_button",function(){
			if(confirm('Are you sure delete this Account?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=account_id&table_name=account',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Account. This Account has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Account Deleted Successfully!', true);
                        }
                    }
                });
            }
		});
	});

	/*$(document).ready(function(){
		table = $('.account-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('account/account_datatable')?>",
				"type": "POST"
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			columnDefs: [{
				orderable: false,
				className: 'select-checkbox',
				targets: 0
			}],
			select: {
				style: 'os',
				selector: 'td:first-child'
			}
		});



	});*/
</script>
