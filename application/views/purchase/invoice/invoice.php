<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
            <?php echo isset($type) && $type == 'purchase' ? 'Add Purchase Invoice' : 'Add Order'; ?>
			<!--Purchase Invoice-->
			<?php if($this->applib->have_access_role(MODULE_PURCHASE_INVOICE_ID,"view")) { ?>
			<a href="<?=base_url('purchase/invoice_list');?>" class="btn btn-primary pull-right">Purchase Invoice List</a>
			<?php } ?>
			
			<?php if($this->applib->have_access_role(MODULE_ORDER_ID,"view")) { ?>
            <a href="<?=base_url('purchase/order_invoice_list');?>" class="btn btn-primary pull-right">Order List</a>
            <?php } ?>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- START ALERTS AND CALLOUTS -->
		<div class="row">
			<div class="col-md-12">
				<form id="form_purchase_invoice" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
					<?php if(isset($purchase_invoice_data->purchase_invoice_id) && !empty($purchase_invoice_data->purchase_invoice_id)){ ?>
					<input type="hidden" id="purchase_invoice_id" name="purchase_invoice_id" value="<?=$purchase_invoice_data->purchase_invoice_id;?>">
					<?php } ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title form_title">
								<?=isset($purchase_invoice_data->purchase_invoice_id) ? 'Edit' : 'Add' ?>
								<?php if(isset($purchase_invoice_data->purchase_invoice_no)){ echo '<span> &nbsp;&nbsp;&nbsp;&nbsp; Invoice No. : '. $purchase_invoice_data->purchase_invoice_no . '</span>'; } ?>
							</h3>
                            <div class="col-md-3 pull-right">
                                <label for="invoice_type" class="control-label">Invoice Type</label>
                                <?php 
                                	if(isset($purchase_invoice_data->purchase_invoice_id)) {
                                		?>
                                		<select class="form-control" name="invoice_type">
                                			<?php
                                				if($purchase_invoice_data->invoice_type == 1) {
                                					echo "<option value='1' selected>Order</option>";

                                				} elseif($purchase_invoice_data->invoice_type == 2) {
                                					echo "<option value='2' selected>Purchase</option>";
                                				}
                                			?>
                                		</select>
                                		<?php
                                	} else {
                                		?>
                                		<select class="select2" name="invoice_type">
		                                    <option value="1" <?php echo isset($purchase_invoice_data->invoice_type) && $purchase_invoice_data->invoice_type == 1 ? 'selected' : isset($type) && $type == 'purchase' ? '' : 'selected'; ?>>Order</option>
		                                    <option value="2" <?php echo isset($purchase_invoice_data->invoice_type) && $purchase_invoice_data->invoice_type == 2 ? 'selected' : isset($type) && $type == 'purchase' ? 'selected' : ''; ?>>Purchase</option>
		                                </select>
                                		<?php
                                	}
                                ?>
                            </div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
										<a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account/')?>"><i class="fa fa-plus"></i> Add Account</a>
										<select name="account_id" id="account_id" class="account_id" required data-index="1" ></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="purchase_invoice_date" class="control-label">Purchase Invoice Date<span class="required-sign">*</span></label>
										<input type="text" name="purchase_invoice_date" id="datepicker2" class="form-control" required data-index="2" value="<?=isset($purchase_invoice_data->purchase_invoice_date) ? date('d-m-Y', strtotime($purchase_invoice_data->purchase_invoice_date)) : date('d-m-Y'); ?>">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="bill_no" class="control-label">Bill No.</label>
										<input type="text" name="bill_no" id="bill_no" class="form-control" data-index="3" value="<?=isset($purchase_invoice_data->bill_no) ? $purchase_invoice_data->bill_no : ''; ?>">
									</div>
								</div>
								<?php if (isset($partners)) { ?>
									<div class="col-md-6">
										<div class="form-group">
											<label for="bill_no" class="control-label">Partner</label>
											<select name="partner_sign" id="partner_sign" required class="form-control">
												<?php foreach($partners as $partner) { ?>
													<option value="<?php $partner['partner_sign'] ?>" <?=isset($purchase_invoice_data->partner_sign) && $purchase_invoice_data->partner_sign == $partner['partner_sign'] ? 'selected' : ''; ?>><?php $partner['partner_name'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								<?php } ?>
								<div class="clearfix"></div>
								
								
								<div class="col-md-12">
									<div class="box-header with-border">
									</div>	
									<?php $this->load->view('line_items'); ?>
								</div>
								<div class="clearfix"></div>
								

								<div class="col-md-6">
									<br/>
									<div class="form-group">
										<label for="purchase_invoice_desc" class="control-label">Description</label>
										<textarea name="purchase_invoice_desc" id="purchase_invoice_desc" class="form-control" data-index="4" placeholder=""><?=isset($purchase_invoice_data->purchase_invoice_desc) ? $purchase_invoice_data->purchase_invoice_desc : '' ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-primary form_btn module_save_btn"><?=isset($purchase_invoice_data->purchase_invoice_id) ? 'Update' : 'Save' ?></button>
                                                        <b style="color: #0974a7">Ctrl + S</b>
						</div>
					</div>
				</form>
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
		var jsonObj = [];
		$(".dfselect2").select2({
			width:"100%",
			placeholder: " --Select-- ",
			allowClear: true,
		});
		initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");
		<?php if(isset($purchase_invoice_data->account_id)){ ?>
			setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/')?>" + <?=$purchase_invoice_data->account_id; ?>);
		<?php } ?>
		
                shortcut.add("ctrl+s", function() {  
                    $( ".module_save_btn" ).click();
                });

                $('#account_id').select2('open');
                $('#account_id').on("select2:close", function(e) { 
                    $("#datepicker2").focus();
                });
                $('#item_id').on("select2:close", function(e) { 
                    $("#item_qty").focus();
                });
                $('#unit_id').on("select2:close", function(e) { 
                    $("#price").focus();
                });

		$(document).on('submit', '#form_purchase_invoice', function () {
			if(lineitem_objectdata == ''){
				show_notify("Please select any one Product.", false);
				return false;
			}
			$('.overlay').show();
			var postData = new FormData(this);
			var lineitem_objectdata_var = JSON.stringify(lineitem_objectdata);
                postData.append('line_items_data', lineitem_objectdata_var);
			$.ajax({
				url: "<?=base_url('purchase/save_purchase_invoice') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
                    if (json['error'] == 'Locked_Date'){
                        show_notify("Selected Date Locked, Please Choose Other Date.", false);
                        $('.overlay').hide();
                        return false;
                    }
					if (json['success'] == 'Added'){
						window.location.href = "<?php echo base_url(isset($type) && $type == 'purchase' ? 'purchase/invoice/' : 'purchase/order/') ?>";
					}
					if (json['success'] == 'Updated'){
						window.location.href = "<?php // echo base_url('purchase/invoice_list') ?>";
						window.location.href = "<?php echo base_url(isset($type) && $type == 'purchase' ? 'purchase/invoice_list/' : 'purchase/order_invoice_list/') ?>";
					}
					$('.overlay').hide();
					return false;
				},
			});
			return false;
		});
		
	});
</script>
