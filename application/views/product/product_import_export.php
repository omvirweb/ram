<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Product Import/Export</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="product_export" class="" action="<?=base_url('product/product_export') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title">
								Product Export
							</h3>
							<a class="btn btn-sm btn-danger uncheck-all pull-right">Un Select ALL</a>
							<a class="btn btn-sm btn-primary check-all pull-right" style="margin-right: 5px;">Select ALL</a>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<?php foreach($product_fields as $product_field){ ?>
									<?php if($product_field != 'product_image'){ ?>
									<div class="col-md-3">
										<div class="form-group">
											<input type="checkbox" name="<?php echo $product_field; ?>" id="<?php echo $product_field; ?>"
											 <?php if($product_field == 'product_name'){ echo 'checked'; } else { echo 'class="chkids"'; } ?> />
											<label for="<?php echo $product_field; ?>" class="control-label">
												<?php 
													if($product_field == 'company_id' || $product_field == 'category_id' || $product_field == 'segment_id' 
													|| $product_field == 'vehicle_id' || $product_field == 'vehicle_model_id' || $product_field == 'plant_id' 
													|| $product_field == 'pack_unit_id' || $product_field == 'location_id' || $product_field == 'rack1_id' 
													|| $product_field == 'rack2_id'){
														$product_field = str_replace('id', ' ', $product_field);
													}
													$product_field = str_replace('_', ' ', $product_field);
													echo ucwords($product_field);
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
							<button type="submit" name="action" value="demo" class="btn btn-info pull-right">Download Demo For Import Products</button>
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
				<form id="product_import" class="" action="<?=base_url('product/product_import') ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title">
								Product Import
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
        
        $(document).on('submit', '#product_export', function () {
			if($('#product_name').is(':checked')){
			} else {
				show_notify('Required Product Name for Export.', false);
				return false;
			}
			//~ var postData = new FormData(this);
			//~ $.ajax({
				//~ url: "<?=base_url('product/product_export') ?>",
				//~ type: "POST",
				//~ processData: false,
				//~ contentType: false,
				//~ cache: false,
				//~ data: postData,
				//~ success: function (response) {
					//~ var json = $.parseJSON(response);
					//~ if (json['success'] == 'export') {
						//~ show_notify('Product Successfully Export.', true);
					//~ } else { 
						//~ show_notify('Please Select any checkbox for Export.', false);
					//~ }
					//~ return false;
				//~ },
			//~ });
			//~ return false;
		});
	});
</script>
