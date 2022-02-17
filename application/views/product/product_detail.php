<?php if(isset($product_id) && !empty($product_id)){ } else { ?>
	<script type="text/javascript">
		$(document).ready(function(){ window.location.href = '<?=base_url('product/product_list');?>'; });
	</script>
<?php } ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Product
			<a href="<?=base_url('product/product_list');?>" class="btn btn-primary pull-right">Product List</a>
			<a href="<?=base_url('product/product');?>" class="btn btn-primary pull-right" style="margin-right: 10px;" >Add New</a>
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
							<form id="edit_<?=$product_id; ?>" method="post" action="<?=base_url('product/product');?>" class="pull-right" style="width: 30px; display: initial;" >
								<input type="hidden" name="product_id" id="product_id" value="<?=$product_id; ?>" />
								<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById('edit_<?=$product_id; ?>').submit();" title="Edit Product"><i class="fa fa-edit"></i></a>
							</form> 
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr>
										<th style="width:25%">Company:</th>
										<td><?=isset($company_name) ? $company_name : '' ?></td>
									</tr>
									
									<tr>
										<th style="width:25%">Category:</th>
										<td><?=isset($category_name) ? $category_name : '' ?></td>
										<th style="width:25%">Sub Category:</th>
										<td><?=isset($sub_category_name) ? $sub_category_name : '' ?></td>
									</tr>
									
									<tr>
										<th>Segment:</th>
										<td><?=isset($segment_name) ? $segment_name : '' ?></td>
										<th>Product Name:</th>
										<td><?=isset($product_name) ? $product_name : '' ?></td>
									</tr>
									<tr>
										<th>Part Number:</th>
										<td><?=isset($part_number) ? $part_number : '' ?></td>
										<th>HSN code:</th>
										<td><?=isset($hsn_code) ? $hsn_code : '' ?></td>
									</tr>
									<tr>
										<th>CGST Percentage:</th>
										<td><?=isset($cgst_per) ? $cgst_per : '' ?></td>
										<th>SGST Percentage:</th>
										<td><?=isset($sgst_per) ? $sgst_per : '' ?></td>
									</tr>
									<tr>
										<th>IGST Percentage:</th>
										<td><?=isset($igst_per) ? $igst_per : '' ?></td>
										<th>Vehicle:</th>
										<td><?=isset($vehicle_name) ? $vehicle_name : '' ?></td>
									</tr>
									<tr>
										<th>Vehicle Model:</th>
										<td><?=isset($vehicle_model_name) ? $vehicle_model_name : '' ?></td>
										<th>Plant:</th>
										<td><?=isset($plant_name) ? $plant_name : '' ?></td>
									</tr>
									<tr>
										<th>Pack Unit:</th>
										<td><?=isset($pack_unit_name) ? $pack_unit_name : '' ?></td>
										<th></th>
										<td></td>
									</tr>
									<tr>
										<th>Warranty:</th>
										<td><?=isset($warranty) ? $warranty : '' ?></td>
										<th>Guarantee:</th>
										<td><?=isset($guarantee) ? $guarantee : '' ?></td>
									</tr>
									<tr>
										<th>Standerd Pack:</th>
										<td><?=isset($standerd_pack) ? $standerd_pack : '' ?></td>
										<th>OEM Reference:</th>
										<td><?=isset($oem_reference) ? $oem_reference : '' ?></td>
									</tr>
									<tr>
										<th>Serial no Mandatory:</th>
										<td><?=isset($serialno_mandatory) ? $serialno_mandatory : '' ?></td>
										<th>Purchase Rate:</th>
										<td><?=isset($purchase_rate) ? $purchase_rate : '' ?></td>
									</tr>
									<tr>
										<th>Sell Rate:</th>
										<td><?=isset($sell_rate) ? $sell_rate : '' ?></td>
										<th>DLP:</th>
										<td><?=isset($dlp) ? $dlp : '' ?></td>
									</tr>
									<tr>
										<th>MRP:</th>
										<td><?=isset($mrp) ? $mrp : '' ?></td>
										<th>Below Stock:</th>
										<td><?=isset($below_stock) ? $below_stock : '' ?></td>
									</tr>
									<tr>
										<th>Above Stock:</th>
										<td><?=isset($above_stock) ? $above_stock : '' ?></td>
										<th>Maintain Stock:</th>
										<td><?=isset($maintain_stock) ? $maintain_stock : '' ?></td>
									</tr>
									<tr>
										<th>Current Stock:</th>
										<td><?=isset($current_stock) ? $current_stock : '' ?></td>
										<th>Location:</th>
										<td><?=isset($location_name) ? $location_name : '' ?></td>
									</tr>
									<tr>
										<th>Rack 1:</th>
										<td><?=isset($rack1_name) ? $rack1_name : '' ?></td>
										<th>Rack 2:</th>
										<td><?=isset($rack2_name) ? $rack2_name : '' ?></td>
									</tr>
									<tr>
										<th>Expected Days:</th>
										<td><?=isset($expected_days) ? $expected_days : '' ?></td>
										<th>Description:</th>
										<td><?=isset($product_desc) ? nl2br($product_desc) : '' ?></td>
									</tr>
									<tr>
										<th>Releted Products:</th>
										<td colspan=3><?=isset($related_product) ? $related_product : '' ?></td>
									</tr>
									<tr>
										<th>Product Image:</th>
										<td colspan=3>
											<?php if(!empty($product_image)){ ?>
												<img src='<?=base_url('assets/uploads/images/products/'.$product_image);?>' alt="Product Image" />
											<?php } ?>
										</td>
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
