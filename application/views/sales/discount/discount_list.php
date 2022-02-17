<?php $this->load->view('success_false_notify'); 
    $li_item_group = isset($this->session->userdata()['li_item_group']) ? $this->session->userdata()['li_item_group'] : '0';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Discount
			<?php if($this->applib->have_access_role(MODULE_SALES_DISCOUNT_ID,"add")) { ?>
			<a href="<?=base_url('sales/discount');?>" class="btn btn-primary pull-right">Add New</a>
			<?php } ?>			
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<!-- /.box-header -->
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered discount-table" id="discount-table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Account Name</th>
                                                                        <?php if($li_item_group == '1'){ ?>
                                                                            <th>Item Group</th>
                                                                        <?php } ?>
									<th>Items</th>
									<th>Discount</th>
								</tr>
							</thead>
							<tbody></tbody>
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
	var datepicker1 = '';
	var datepicker2 = '';
	$(document).ready(function(){

		table = $('.discount-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'desc']],
			"ajax": {
				"url": "<?php echo base_url('sales/discount_datatable')?>",
				"type": "POST",
				"data": function(d){
				},
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{"targets": 0, "orderable": false },
//				{ className: "text-right", "targets": [4] },
			]
		});
		
		$(document).on('click','#btn_datepicker',function(e){
			e.preventDefault();
			datepicker1 = $('#datepicker1').val();
			datepicker2 = $('#datepicker2').val();
			if(datepicker2 != '' && datepicker1 != ''){
				table.draw();
				return false;
			}
		});
		
		$(document).on('change','#account_id',function(){
			table.draw();
		});

		$(document).on("click",".delete_button",function(){
			var value = confirm('Are you sure delete this Invoice?');
			var tr = $(this).closest("tr");
			if(value){
				$.ajax({
					url: $(this).data('href'),
					type: "POST",
					data: '',
					success: function(data){
						table.draw();
						show_notify('Discount Deleted Successfully!',true);
					}
				});
			}
		});
	});
</script>
