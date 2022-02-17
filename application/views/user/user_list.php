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
	        Company
	        <?php if($this->applib->have_access_role(MASTER_COMPANY_ID, "add")){ ?>
	            <a href="<?=base_url('user/user');?>" class="btn btn-primary pull-right">Add New</a>
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
						<form id="frm-action" action="" method="POST">
							<div class="btn-group">
								<button type="button" class="btn btn-success anchor-btn activate" data-href="<?=base_url('user/activate_status/')?>" data-name="activate" id="active">Active</button>
								<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="javascript:void(0);" class="anchor-btn activate" data-href="<?=base_url('user/activate_status/')?>" data-name="activate">Active</a></li>
									<li class="divider"></li>
									<li><a href="javascript:void(0);" class="anchor-btn deactivate" data-href="<?=base_url('user/deactivate_status/')?>" data-name="deactivate">InActive</a></li>
									<li class="divider"></li>
									<li><a href="javascript:void(0);" class="anchor-btn delete" data-href="<?=base_url('user/multiple_delete/')?>" data-name="delete">Delete</a></li>
									<input type="hidden" id="actionName" name="actionName" value="" />
								</ul>
							</div>
							<table class="table table-striped table-bordered user-table display" id="example">
								<thead>
									<tr>
										<th><input name="select_all" value="1" class="" id="example-select-all" type="checkbox" /></th>
										<th>Action</th>
										<th>OTP</th>
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Is Active</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</form>
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
			"aaSorting": [[3, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('user/user_datatable')?>",
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
				'className': 'dt-body-center flat-red',
				'render': function (data, type, full, meta){
					return '<input type="checkbox" class="flat-red" name="id[]" value="' 
						+ $('<div/>').text(data).html() + '">';
				}
			}],
			'order': [3, 'asc']
		});

		$('#example-select-all').on('click', function(){
			var rows = table.rows({ 'search': 'applied' }).nodes();
			$('input[type="checkbox"]', rows).prop('checked', this.checked);
		});

		$('#example tbody').on('change', 'input[type="checkbox"]', function(){
			if(!this.checked){
				var el = $('#example-select-all').get(0);
				if(el && el.checked && ('indeterminate' in el)){
					el.indeterminate = true;
				}
			}
		});
		//Flat red color scheme for iCheck
		$('#example tbody input[type="checkbox"].flat-red').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass   : 'iradio_flat-green'
		});
		
		$('.anchor-btn').click(function(e) {
			e.preventDefault();
			$('#actionName').val($(this).data('name'));
			$('#frm-action').submit();
		});
		
		$(document).on('submit', '#frm-action', function () {
			var form = this;
			table.$('input[type="checkbox"]').each(function(){
				if(!$.contains(document, this)){
					if(this.checked){
						$(form).append($('<input>').attr('type', 'hidden').attr('name', this.name).val(this.value));
					}
				} 
			});
			var postData = new FormData(this);
			//console.log($('input#actionName').val());return false;
			var action = $('input#actionName').val();
			if(action == 'activate'){
				var url = '<?=base_url('user/activate_status')?>';
			}else if(action == 'deactivate'){
				var url = '<?=base_url('user/deactivate_status')?>';
			}else if(action == 'delete'){
				var url = '<?=base_url('user/multiple_delete')?>';
			}
			$.ajax({
				url: url,
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if(json['error'] == 'Empty'){
						show_notify('Please Select Atleast 1 checkbox to apply action !',false);
						return false;
					}
					if (json['success'] == 'Activate'){
						show_notify('Accounts Activated Sucessfully.',true);
						table.draw();
					}
					if (json['success'] == 'DeActivate'){
						show_notify('Accounts Inactivated Sucessfully.',true);
						table.draw();
					}
					if (json['success'] == 'Delete'){
						show_notify('Accounts Deleted Sucessfully.',true);
						table.draw();
					}
					return false;
				},
			});
			return false;
		});
		
		$(document).on("click",".change_status",function(){
			var status = $(this).data('status');
			var variable = (status == 'active') ? ('Account Deactivated') : ('Account Activated');
			var value = confirm('Are you sure '+variable+' ?');
			if(value){
				$.ajax({
					url: $(this).data('href'),
					type: "POST",
					data: 'id_name=user_id&table_name=user',	
					success: function(data){
						console.log(data);
						table.draw();
						if(status == 'active'){
							show_notify('Company Account Deactivated Successfully!',true);	
						}else{
							show_notify('Company Account Activated Successfully!',true);
						}
					}
				});
			}
		});
        
		$(document).on("click",".change_otp_status",function(){
			var status = $(this).data('status');
            $.ajax({
                url: $(this).data('href'),
                type: "POST",
                data: 'id_name=user_id&table_name=user',	
                success: function(data){
                    console.log(data);
                    table.draw();
                    if(status == 'enable'){
                        show_notify('Company Account OTP Disabled Successfully!',true);	
                    }else{
                        show_notify('Company Account OTP Enabled Successfully!',true);
                    }
                }
            });
         });
		
		$(document).on("click",".delete_button",function(){
			if(confirm('Are you sure delete this Company?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=user_id&table_name=user',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Company. This Company has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Company Deleted Successfully!', true);
                        }
                    }
                });
            }
		});
	});
	
	/*$(document).ready(function(){
		table = $('.user-table').DataTable({
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('user/user_datatable')?>",
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
