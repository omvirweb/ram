<?php if(isset($sales_invoice_id) && !empty($sales_invoice_id)){ } else { ?>
	<script type="text/javascript">
		$(document).ready(function(){ window.location.href = '<?=base_url('sales/invoice_list');?>'; });
	</script>
<?php } ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Sales Invoice
			<a href="<?=base_url('sales/invoice_list');?>" class="btn btn-primary pull-right">Sales Invoice List</a>
			<a href="<?=base_url('sales/invoice');?>" class="btn btn-primary pull-right" style="margin-right: 10px;" >Add New</a>
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
							<form id="edit_<?=$sales_invoice_id; ?>" method="post" action="<?=base_url('sales/invoice');?>" class="pull-right" style="width: 30px; display: initial;" >
								<input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="<?=$sales_invoice_id; ?>" />
								<a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById('edit_<?=$sales_invoice_id; ?>').submit();" title="Edit Invoice"><i class="fa fa-edit"></i></a>
							</form> 
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr>
										<th style="width:25%">Sales Invoice No.:</th>
										<td><?=isset($sales_invoice_no) ? $sales_invoice_no : '' ?></td>
										<th style="width:25%">Sales Invoice Date:</th>
										<td><?=isset($sales_invoice_date) ? $sales_invoice_date : '' ?></td>
									</tr>
									<tr>
										<th>Supplier:</th>
										<td><?=isset($supplier) ? $supplier : '' ?></td>
										<th>Sales Invoice Desc:</th>
										<td><?=isset($sales_invoice_desc) ? $sales_invoice_desc : '' ?></td>
									</tr>
									<tr>
										<th>Transport:</th>
										<td><?=isset($transport) ? $transport : '' ?></td>
										<th>No Of Articles</th>
										<td><?=isset($no_of_articles) ? $no_of_articles : '' ?></td>
									</tr>
									<tr>
										<th>LR No.:</th>
										<td><?=isset($lr_no) ? $lr_no : '' ?></td>
										<th>LR Date</th>
										<td><?=isset($lr_date) ? $lr_date : '' ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12">
							<h4 class="box-title form_title">Line Items</h4>
							<style>
								th, td { white-space: nowrap; }
								.table-wrapper { overflow-x: scroll; width: 100%px; margin: 0 auto; }
							</style>
							<div class="table-wrapper">
							<table class="table">
								<thead>
									<tr>
										<th width="70px">Sr. No.</th>
										<th>Order No.</th>
										<th>Challan No.</th>
										<th>Product</th>
										<th class="text-right">Qty</th>
										<th class="text-right">Qty2</th>
										<th class="text-right">Price</th>
										<th>Type</th>
										<th>Location</th>
										<th>Rack</th>
										<th>Serial No.</th>
										<th class="text-right">Befor Dis. Amt</th>
										<th class="text-right">Discount</th>
										<th class="text-right">Discounted Amount</th>
										<th class="text-right">CGST</th>
										<th class="text-right">SGST</th>
										<th class="text-right">IGST</th>
										<th class="text-right">Amount</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$discounted_price_total = 0;
										$inc = 1; 
										foreach($lineitems as $lineitem){ 
									?>
										<tr>
											<td><?php echo $inc; ?></td>
											<td><?php echo $lineitem->sales_order_no; ?></td>
											<td><?php echo $lineitem->sales_challan_no; ?></td>
											<td><?php echo $lineitem->product_name; ?></td>
											<td class="text-right"><?php echo $lineitem->product_qty; ?></td>
											<td class="text-right"><?php echo $lineitem->product_qty2; ?></td>
											<td class="text-right"><?php echo $lineitem->price_type.' - '.$lineitem->price; ?></td>
											<td><?php echo $lineitem->lineitem_type_name; ?></td>
											<td><?php echo $lineitem->location_name; ?></td>
											<td><?php echo $lineitem->rack_name; ?></td>
											<td><?php echo $lineitem->product_serial_no; ?></td>
											<td class="text-right"><?php echo $lineitem->before_discount_price; ?></td>
											<td class="text-right">
												<?php
													foreach($lineitem->discount_type as $d_k => $discount_type){
														if( $discount_type == 1 ) { $discount_type = 'Pct'; }
														if( $discount_type == 2 ) { $discount_type = 'Amt'; }
														echo $discount_type . ' - ' . $lineitem->discount[$d_k] . ' <br />';
													}
												?>
											</td>
											<td class="text-right"><?php echo $lineitem->discounted_price; ?></td>
											<td class="text-right"><?php echo $lineitem->cgst . '% - ' . $lineitem->cgst_amount . ' Rs.'; ?></td>
											<td class="text-right"><?php echo $lineitem->sgst . '% - ' . $lineitem->sgst_amount . ' Rs.'; ?></td>
											<td class="text-right"><?php echo $lineitem->igst . '% - ' . $lineitem->igst_amount . ' Rs.'; ?></td>
											<td class="text-right"><?php echo $lineitem->amount; ?></td>
										</tr>
									<?php 
											$discounted_price_total += $lineitem->discounted_price;
											$inc++; 
										} 
									?>
								</tbody>
								<tfoot>
									<tr>
										<th>Total</th>
										<th></th>
										<th></th>
										<th></th>
										<th class="text-right"><?php echo $qty_total; ?></th>
										<th class="text-right"></th>
										<th class="text-right"></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th class="text-right"><?php echo $before_discount_total; ?></th>
										<th class="text-right"><?php echo $discount_total; ?></th>
										<th class="text-right"><?php echo $discounted_price_total; ?></th>
										<th class="text-right"><?php echo $cgst_amount_total; ?></th>
										<th class="text-right"><?php echo $sgst_amount_total; ?></th>
										<th class="text-right"><?php echo $igst_amount_total; ?></th>
										<th class="text-right"><?php echo $amount_total; ?></th>
									</tr>
								</tfoot>
							</table>
							</div>
						</div>
						<div class="clearfix"></div>
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
