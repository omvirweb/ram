<?php
$array_items = array('name' => '', 'email' => '');
$this->session->unset_userdata($array_items);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Documents
		</h1>

	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
            <div class="col-md-6 col-md-push-6">
            	<?php if($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"add") || $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"edit")) { ?>
				<form id="form_documents" action="" enctype="multipart/form-data" data-parsley-validate="">
					<?php if(isset($id) && !empty($id)){ ?>
					<input type="hidden" id="id" name="id" value="<?=$id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title"><?=isset($id) ? 'Edit' : 'Add' ?></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="form-group">
								<label for="company_id">Branch<span class="required-sign">*</span></label>
                                <select class="form-control" id="company_id" name="company_id" required="">
                                    <option value="">- Select Branch - </option>
                                    <?php foreach($users as $user){?>
                                    <option <?=isset($company_id) && $company_id == $user->user_id ? 'selected="selected"':''; ?> value="<?php echo $user->user_id; ?>"><?php echo $user->user_name; ?></option>
                                    <?php } ?>
                                </select>
							</div>
							<div class="form-group">
								<label for="document_name">Document Name<span class="required-sign">*</span></label>
                                <input type="text" name="document_name" class="form-control" id="document_name" value="<?=isset($document_name) ? $document_name : '' ?>" required>
							</div>
							<div class="form-group">
								<label for="document_link">Document Link<span class="required-sign">*</span></label>
                                <input type="text" name="document_link" class="form-control" id="document_link" value="<?=isset($document_link) ? $document_link : '' ?>" required>
							</div>
							<div class="form-group">
								<label for="remark">Remark</label>
								<textarea name="remark" class="form-control" id="remark"><?=isset($remark) ? $remark : '' ?></textarea>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<?php if(isset($id) && $this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"edit")){ ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Update</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } elseif ($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"add")) { ?>
								<button type="submit" class="btn btn-primary form_btn module_save_btn">Save</button>
								<b style="color: #0974a7">Ctrl + S</b>
							<?php } ?>
						</div>
					</div>
				</form>
				<?php } ?>
				<!-- /.box-body -->
				<!-- /.box -->
			</div>
			<div class="col-md-6 col-md-pull-6">
				<?php if($this->applib->have_access_role(MODULE_COMPANY_DOCUMENT_ID,"view")) { ?>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">List</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="col-md-4">
							<div class="form-group">
								<label for="filter_company_id">Branch</label>
		                        <select class="form-control" id="filter_company_id" name="filter_company_id" required="">
		                            <option value="">ALL</option>
		                            <?php foreach($users as $user){ ?>
		                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->user_name; ?></option>
		                            <?php } ?>
		                        </select>
							</div>
						</div>
						<div class="col-md-4">
							<br/>
							<button type="button" id="btn_filter" class="btn btn-primary">Search</button>
						</div>
						<div class="cleafix"></div>

						<!---content start --->
						<table class="table table-striped table-bordered documents-table" width="100%">
							<thead>
								<tr>
									<th>Action</th>
									<th>Branch Name</th>
									<th>Document Name</th>
									<th>Link</th>
									<th>Remark</th>
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
				<?php } ?>
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
	$(document).ready(function(){
		$('#company_id').select2({width:'100%'});
		$('#filter_company_id').select2({width:'100%'});
		table = $('.documents-table').DataTable({
			"serverSide": true,
			"ordering": false,
			"searching": true,
			"aaSorting": [[1, 'asc']],
			"ajax": {
				"url": "<?php echo base_url('user/documents_datatable')?>",
				"type": "POST",
				"data" : function(d){
					d.company_id = $('#filter_company_id').val();
				}
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"scrollX": true,
		});

		$(document).on('click',"#btn_filter",function(){
			table.draw();
		});
        
        shortcut.add("ctrl+s", function() {  
            $( ".module_save_btn" ).click();
        });

		$(document).on('submit', '#form_documents', function () {
			var document_link = $("#document_link").val();
			if(!isUrlValid(document_link)) {
				show_notify("Enter Valid Document Link", false);
				return false;
			}
			var document_name = $('#document_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('user/save_document') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					
					if (json['success'] == 'Added') {
						table.draw();
						$('input[name="id"]').val("");
						$('#company_id').val(null).trigger('change');
						$('input[name="document_name"]').val("");
						$('input[name="document_link"]').val("");
						$('#remark').val("");
						$('.addmorerow').remove();
						show_notify('Document Successfully Added.', true);
					}
					
					if (json['success'] == 'Updated') {
						table.draw();
						$(".form_btn").html('Save');
						$(".form_title").html('Add');
						$('input[name="id"]').val("");
						$('#company_id').val(null).trigger('change');
						$('input[name="document_name"]').val("");
						$('input[name="document_link"]').val("");
						$('#remark').val("");
						show_notify('Document Successfully Updated.', true);
					}
					return false;
				},
			});
			return false;
		});

		$(document).on("click",".delete_button",function(){
			if(confirm('Are you sure delete this records?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=document_id&table_name=documents',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Document. This Document has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Deleted Successfully!', true);
                        }
                    }
                });
            }
		});
	});
	function isUrlValid(url) {
	    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    	return regexp.test(url);
	}
</script>
