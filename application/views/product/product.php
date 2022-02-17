<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Item
			<a href="<?=base_url('product/product_list');?>" class="btn btn-primary pull-right">Item List</a>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="form_product" class="" action="" enctype="multipart/form-data" data-parsley-validate="" >
					<?php if(isset($product_id) && !empty($product_id)){ ?>
					<input type="hidden" id="product_id" name="product_id" value="<?=$product_id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title"><?=isset($product_id) ? 'Edit' : 'Add' ?></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="company_id" class="control-label">Item Type</label>
										<select name="company_id" id="company_id" class="company_id" ></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="product_name" class="control-label">Item Name<span class="required-sign">*</span></label>
										<input type="text" name="product_name" class="form-control" id="product_name" value="<?=isset($product_name) ? $product_name : '' ?>" placeholder="" required>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="hsn_code" class="control-label">HSN / SAC</label>
										<input type="text" name="hsn_code" class="form-control" id="hsn_code" value="<?=isset($hsn_code) ? $hsn_code : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="pack_unit_id" class="control-label">Pack Unit</label>
										<select name="pack_unit_id" id="pack_unit_id" class="pack_unit_id" ></select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="cgst_per" class="control-label">CGST Percentage</label>
										<input type="text" name="cgst_per" class="form-control" id="cgst_per" value="<?=isset($cgst_per) ? $cgst_per : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="sgst_per" class="control-label">SGST Percentage</label>
										<input type="text" name="sgst_per" class="form-control" id="sgst_per" value="<?=isset($sgst_per) ? $sgst_per : '' ?>" placeholder="">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="igst_per" class="control-label">IGST Percentage</label>
										<input type="text" name="igst_per" class="form-control" id="igst_per" value="<?=isset($igst_per) ? $igst_per : '' ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="igst_per" class="control-label">Cess</label>
										<input type="text" name="igst_per" class="form-control" id="igst_per" value="<?=isset($igst_per) ? $igst_per : '' ?>" placeholder="">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="desc" class="control-label">Description</label>
										<textarea name="desc" class="form-control" id="desc" placeholder=""><?=isset($product_desc) ? $product_desc : '' ?></textarea>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn"><?=isset($product_id) ? 'Update' : 'Save' ?></button>
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
	var table;
	$(document).ready(function(){
		<?php if(!isset($product_id) && empty($product_id)){ ?>
		$(".select2").select2({
			width:"100%",
			placeholder: " --Select-- ",
			allowClear: true,
		});
		<?php } ?>
		initAjaxSelect2Tags($("#company_id"),"<?=base_url('app/company_select2_source')?>");
		initAjaxSelect2Tags($("#category_id"),"<?=base_url('app/category_select2_source')?>");
		initAjaxSelect2Tags($('#sub_category_id'),"");
		$('#category_id').on('change', function() {
			$("#sub_category_id").empty().trigger('change');
			if(isNaN(this.value)){
				var category_id = 999999999;	
			}else{
				var category_id = this.value;
			}
			initAjaxSelect2Tags($('#sub_category_id'),"<?=base_url('app/sub_cat_select2_source')?>/"+category_id);
		});
		initAjaxSelect2Tags($("#segment_id"),"<?=base_url('app/sagement_select2_source')?>");
		initAjaxSelect2Tags($("#vehicle_id"),"<?=base_url('app/vehicle_select2_source')?>");
		initAjaxSelect2Tags($('#vehicle_model_id'),"");
		$('#vehicle_id').on('change', function() {
			$("#vehicle_model_id").empty().trigger('change');
			var vehicle_id = this.value;
			initAjaxSelect2Tags($('#vehicle_model_id'),"<?=base_url('app/vehicle_model_select2_source')?>/"+vehicle_id);
		});
		initAjaxSelect2Tags($("#plant_id"),"<?=base_url('app/plant_select2_source')?>");
		initAjaxSelect2Tags($("#pack_unit_id"),"<?=base_url('app/pack_unit_select2_source')?>");
		initAjaxSelect2Tags($("#location_id"),"<?=base_url('app/location_select2_source')?>");
		initAjaxSelect2Tags($('#rack1_id'),"");
		initAjaxSelect2Tags($('#rack2_id'),"");
		$('#location_id').on('change', function() {
			$("#rack1_id").empty().trigger('change');
			$("#rack2_id").empty().trigger('change');
			var location_id = this.value;
			initAjaxSelect2Tags($('#rack1_id'),"<?=base_url('app/rack_select2_source')?>/"+location_id);
			initAjaxSelect2Tags($('#rack2_id'),"<?=base_url('app/rack_select2_source')?>/"+location_id);
		});
		<?php if(isset($product_id) && !empty($product_id)){ ?>
			initAjaxSelect2($("#related_product"),"<?=base_url('app/related_product_select2_source/'.$product_id)?>");
		<?php } else { ?>
			initAjaxSelect2($("#related_product"),"<?=base_url('app/related_product_select2_source')?>");
		<?php } ?>
		
		
		<?php if(isset($company_id) && !empty($company_id)){ ?>
			setSelect2Value($("#company_id"),"<?=base_url('app/set_company_select2_val_by_id/'.$company_id)?>");
		<?php } ?>
		<?php if(isset($category_id) && !empty($category_id)){ ?>
			setSelect2Value($("#category_id"),"<?=base_url('app/set_category_select2_val_by_id/'.$category_id)?>");
		<?php } ?>
		
		<?php if(isset($sub_category_id) && !empty($sub_category_id)){ ?>
		setSelect2Value($("#sub_category_id"),"<?=base_url('app/set_sub_cat_select2_val_by_id/'.$sub_category_id)?>");
		<?php } ?>
		
		<?php if(isset($segment_id) && !empty($segment_id)){ ?>
			setSelect2Value($("#segment_id"),"<?=base_url('app/set_sagement_select2_val_by_id/'.$segment_id)?>");
		<?php } ?>
		<?php if(isset($vehicle_id) && !empty($vehicle_id)){ ?>
			setSelect2Value($("#vehicle_id"),"<?=base_url('app/set_vehicle_select2_val_by_id/'.$vehicle_id)?>");
			initAjaxSelect2Tags($('#vehicle_model_id'),"<?=base_url('app/vehicle_model_select2_source/'.$vehicle_id)?>");
		<?php } ?>
		<?php if(isset($vehicle_model_id) && !empty($vehicle_model_id)){ ?>
			setSelect2Value($("#vehicle_model_id"),"<?=base_url('app/set_vehicle_model_select2_val_by_id/'.$vehicle_model_id)?>");
		<?php } ?>
		<?php if(isset($plant_id) && !empty($plant_id)){ ?>
			setSelect2Value($("#plant_id"),"<?=base_url('app/set_plant_select2_val_by_id/'.$plant_id)?>");
		<?php } ?>
		<?php if(isset($pack_unit_id) && !empty($pack_unit_id)){ ?>
			setSelect2Value($("#pack_unit_id"),"<?=base_url('app/set_pack_unit_select2_val_by_id/'.$pack_unit_id)?>");
		<?php } ?>
		<?php if(isset($location_id) && !empty($location_id)){ ?>
			setSelect2Value($("#location_id"),"<?=base_url('app/set_location_select2_val_by_id/'.$location_id)?>");
			initAjaxSelect2Tags($('#rack1_id'),"<?=base_url('app/rack_select2_source/'.$location_id)?>");
			initAjaxSelect2Tags($('#rack2_id'),"<?=base_url('app/rack_select2_source/'.$location_id)?>");
		<?php } ?>
		<?php if(isset($rack1_id) && !empty($rack1_id)){ ?>
			setSelect2Value($("#rack1_id"),"<?=base_url('app/set_rack_select2_val_by_id/'.$rack1_id)?>");
		<?php } ?>
		<?php if(isset($rack2_id) && !empty($rack2_id)){ ?>
		setSelect2Value($("#rack2_id"),"<?=base_url('app/set_rack_select2_val_by_id/'.$rack2_id)?>");
		<?php } ?>
		<?php if(isset($product_id) && !empty($product_id)){ ?>
		setSelect2MultiValue($("#related_product"),"<?=base_url('app/set_related_product_select2_val_by_id/'.$product_id)?>");
		<?php } ?>
		
		$(document).on('submit', '#form_product', function () {
			var company_name = $('#plant_name').val();
			var postData = new FormData(this);
			$.ajax({
				url: "<?=base_url('product/save_product') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				fileElementId	:'product_image',
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					
					if (json['Uploaderror'] != null){
						//show_notify(json['Uploaderror'],false);
						//return false;
					}
					if (json['success'] == 'Added'){
						window.location.href = "<?php echo base_url('product/product') ?>";
					}
					if(json['error'] == 'errorAdded'){
						show_notify('Some error has occurred !',false);
						return false;
					}
					if(json['error'] == 'productExist'){
						show_notify('Product Already Exist !',false);
						return false;
					}
					if (json['success'] == 'Updated'){
						window.location.href = "<?php echo base_url('product/product_list') ?>";
					}
					return false;
				},
			});
			return false;
		});
		
	});
</script>
