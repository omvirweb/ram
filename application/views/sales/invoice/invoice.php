<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <form id="form_sales_invoice" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
	<section class="content-header">
            <h1>
                Sales Invoice - CTRL + F1
                <?php if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"view")) { ?>
                <a href="<?=base_url('sales/invoice_list');?>" class="btn btn-primary pull-right">Sales Invoice List</a> &nbsp;
                <?php } ?>
                
                <button type="submit" class="btn btn-primary form_btn pull-right save" id="save_btn"><?=isset($sales_invoice_data->sales_invoice_id) ? 'Update' : 'Save' ?></button> &nbsp;
                <?php if(!isset($sales_invoice_data->sales_invoice_id)) {?><button type="submit" class="btn btn-primary form_btn pull-right save_print">Save & Print</button> &nbsp;<?php }?>
                <input type="hidden" name="is_print" id="is_print" value="0">
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="send_whatsapp_sms" class="col-sm-12 input-sm text-green" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="send_whatsapp_sms" id="send_whatsapp_sms" class="send_whatsapp_sms" checked="">  &nbsp; Send<img src="<?php echo base_url(); ?>assets/dist/img/whatsapp_icon.png" style="width:25px;" >
                        </label>
                    </div>
                </span>
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="send_sms" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="send_sms" id="send_sms" class="send_sms" checked="">  &nbsp; Send SMS
                        </label>
                    </div>
                </span>
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="triplicate" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="triplicate" value="2" id="triplicate" class="triplicate" <?php echo isset($sales_invoice_data->print_type) && $sales_invoice_data->print_type == 2 ? 'checked' : ''?>>  &nbsp; Triplicate
                        </label>
                    </div>
                </span>
                <span class="pull-right" style="margin-right: 20px;">
                    <div class="form-group">
                        <label for="duplicate" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                            <input type="checkbox" name="duplicate" value="1" id="duplicate" class="duplicate" <?php echo isset($sales_invoice_data->print_type) && ($sales_invoice_data->print_type == 1 || $sales_invoice_data->print_type == 2) ? 'checked' : ''?>>  &nbsp; Duplicate
                        </label>
                    </div>
                </span>
            </h1>
	</section>
	<!-- Main content -->
	<section class="content">
            <!-- START ALERTS AND CALLOUTS -->
            <div class="row">
                <div class="col-md-12">
                    <?php if(isset($sales_invoice_data->sales_invoice_id) && !empty($sales_invoice_data->sales_invoice_id)){ ?>
                    <input type="hidden" id="sales_invoice_id" name="sales_invoice_id" value="<?=$sales_invoice_data->sales_invoice_id;?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title">
                                <?=isset($sales_invoice_data->sales_invoice_id) ? 'Edit' : 'Add' ?>
                                <?php if(isset($sales_invoice_data->sales_invoice_no)){ echo '<span> &nbsp;&nbsp;&nbsp;&nbsp; Invoice No. : '. $sales_invoice_data->sales_invoice_no . '</span>'; } ?>
                            </h3>&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if(isset($sales_invoice_data->is_shipping_same_as_billing_address) && $sales_invoice_data->is_shipping_same_as_billing_address == 1){
                                $checked = '';
                                $none = '';
                            } else if(isset($sales_invoice_data->is_shipping_same_as_billing_address) && $sales_invoice_data->is_shipping_same_as_billing_address == 0){
                                $checked = 'checked';
                                $none = 'none';
                            } else {
                                $checked = 'checked';
                                $none = 'none';
                            } ?>
                            <label for="shipping_address_chkbox">
                                <input type="checkbox" id="shipping_address_chkbox" name="shipping_address_chkbox" <?php echo $checked; ?>>  &nbsp; Shipping Address Same As Biling Addres
                            </label>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
                                            <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account/')?>"><i class="fa fa-plus"></i> Add Account</a>
                                            <select name="account_id" id="account_id" class="account_id" required data-index="1"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sales_invoice_date" class="control-label">Sales Invoice Date<span class="required-sign">*</span></label>
                                            <input type="text" name="sales_invoice_date" id="datepicker2" class="form-control" data-index="2" required value="<?=isset($sales_invoice_data->sales_invoice_date) ? date('d-m-Y', strtotime($sales_invoice_data->sales_invoice_date)) : date('d-m-Y', strtotime($sales_invoice_date)); ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if(isset($company_invoice_prefix) && !empty($company_invoice_prefix)){ ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="prefix" class="control-label">Invoice Prefix</label>
                                            <select name="prefix" id="prefix" class="select2" data-index="3"></select>
                                            <!--<input type="text" name="prefix" id="prefix" class="form-control" value="<?=isset($sales_invoice_data->prefix) ? $sales_invoice_data->prefix : $prefix; ?>">-->
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <input type="hidden" id="prefix" name="prefix">
                                    <?php } ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sales_invoice_no" class="control-label">Invoice No</label>
                                            <input type="text" name="sales_invoice_no" id="sales_invoice_no" class="form-control num_only" data-index="4" value="<?=isset($sales_invoice_data->sales_invoice_no) ? $sales_invoice_data->sales_invoice_no : $sales_invoice_no; ?>">
                                        </div>
                                    </div>
                                    <?php 
                                        $transport_name_display = 'hidden';
                                        if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"Transport Name")) {
                                            $transport_name_display = '';
                                        }
                                    ?>
                                    <div class="col-md-3 <?=$transport_name_display;?>">
                                        <div class="form-group">
                                            <label for="transport_name" class="control-label">Transport Name</label>
                                            <input type="text" name="transport_name" id="transport_name" class="form-control" data-index="6" value="<?=isset($sales_invoice_data->transport_name) ? $sales_invoice_data->transport_name : ''; ?>">
                                        </div>
                                    </div>
                                    <?php 
                                        $lr_no_display = 'hidden';
                                        if($this->applib->have_access_role(MODULE_SALES_INVOICE_ID,"LR No")) {
                                            $lr_no_display = '';
                                        }
                                    ?>
                                    <div class="col-md-3 <?=$lr_no_display;?>">
                                        <div class="form-group">
                                            <label for="lr_no" class="control-label">LR No.</label>
                                            <input type="text" name="lr_no" id="lr_no" class="form-control" data-index="7" value="<?=isset($sales_invoice_data->lr_no) ? $sales_invoice_data->lr_no : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="invoice_type" class="control-label">Invoice Type<span class="required-sign">*</span></label>
                                            <select name="invoice_type" id="invoice_type" class="form-control select2" data-index="8" required=""></select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 <?php echo $none; ?>" id="shipping">
                                        <div class="form-group">
                                            <label for="shipping_address" class="control-label">Shipping Address</label>
                                            <textarea name="shipping_address" id="shipping_address" class="form-control" data-index="9" placeholder=""><?=isset($sales_invoice_data->shipping_address) ? $sales_invoice_data->shipping_address : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if(!empty($invoice_main_fields)) { 
                                        $sales_invoice_main_fields = array('your_invoice_no' => 'Your Invoice No.','shipment_invoice_no' => 'Shipment Invoice No.', 'shipment_invoice_date' => 'Shipment Invoice Date' , 'sbill_no' => 'S/Bill No.',  'sbill_date' => 'S/Bill Date' , 'origin_port' => 'Origin Port' , 'port_of_discharge' => 'Port of Discharge', 'container_size' => 'Container Size', 'container_bill_no' => 'Container Bill No', 'container_date' => 'Container Date' , 'container_no' => 'Container No.' , 'vessel_name_voy' => 'Vessel Name / Voy' ,  'print_date' => 'Print Date');
//                                        echo '<pre>'; print_r($invoice_main_fields); exit;
                                        $data_index = "10";
                                        foreach($invoice_main_fields as $value){
                                            if ($value == 'sbill_date'){
                                                $datepicker_class = 'datepicker';
                                                $sd = 'sbill_date';
                                                $old_value = isset($sales_invoice_data->$sd) && !empty($sales_invoice_data->$sd) ? date('d-m-Y', strtotime($sales_invoice_data->$sd)) : '';
                                            } else if (strpos($value, 'date') !== false || strpos($value, 'date') !== false) {
                                                $datepicker_class = 'datepicker';
                                                $ee = $value;
                                                $old_value = isset($sales_invoice_data->$ee) && !empty($sales_invoice_data->$ee) ? date('d-m-Y', strtotime($sales_invoice_data->$ee)) : '';
                                            } else if ($value == 'sbill_no'){
                                                $datepicker_class = '';
                                                $ss = 'sbill_no';
                                                $old_value = isset($sales_invoice_data->$ss) ? $sales_invoice_data->$ss : '';
                                            } else {
                                                $datepicker_class = '';
                                                $dd = $value;
                                                $old_value = isset($sales_invoice_data->$dd) ? $sales_invoice_data->$dd : '';
                                            }
                                    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="<?php echo $sales_invoice_main_fields[$value]; ?>" class="control-label"><?php echo $sales_invoice_main_fields[$value]; ?></label>
                                                <input type="text" name="<?php echo $value; ?>" id="<?php echo $value; ?>" data-index="<?php echo $data_index; ?>" class="form-control <?php echo $datepicker_class; ?>" value="<?=$old_value; ?>">
                                            </div>
                                        </div>   
                                        <?php $data_index++; ?>
                                    <?php } }?>
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                        </div>	
                                        <?php $this->load->view('line_items'); ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <br/>
                                        <div class="form-group">
                                            <label for="sales_invoice_desc" class="control-label">Description</label>
                                            <textarea name="sales_invoice_desc" id="sales_invoice_desc" class="form-control" data-index="5" placeholder=""><?=isset($sales_invoice_data->sales_invoice_desc) ? $sales_invoice_data->sales_invoice_desc : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn save"><?=isset($sales_invoice_data->sales_invoice_id) ? 'Update' : 'Save' ?></button>
                            <b style="color: #0974a7">Ctrl + S</b>
                            <?php if(!isset($sales_invoice_data->sales_invoice_id)) {?><button type="submit" class="btn btn-primary form_btn save_print">Save & Print</button> &nbsp;<?php }?>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- END ALERTS AND CALLOUTS -->
	</section>
    </form>
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
		initAjaxSelect2($("#invoice_type"),"<?=base_url('app/invoice_type_select2_source/')?>");
                <?php if(isset($company_invoice_prefix) && !empty($company_invoice_prefix)){ ?>
                    initAjaxSelect2($("#prefix"),"<?=base_url('app/prefix_select2_source/')?>");
                    <?php if(isset($sales_invoice_data->prefix) && !empty($sales_invoice_data->prefix)){ ?>
                            setSelect2Value($("#prefix"),"<?=base_url('app/set_prefix_select2_val_by_id/')?>" + <?=$sales_invoice_data->prefix; ?>);
                    <?php } else { ?>
                            setSelect2Value($("#prefix"),"<?=base_url('app/set_prefix_select2_val_by_id/')?>");
                    <?php } ?>
                <?php } ?>
		<?php if(isset($sales_invoice_data->account_id)){ ?>
			setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/')?>" + <?=$sales_invoice_data->account_id; ?>);
		<?php } ?>
		<?php if(isset($sales_invoice_data->invoice_type) && !empty($sales_invoice_data->invoice_type)){ ?>
			setSelect2Value($("#invoice_type"),"<?=base_url('app/set_invoice_type_select2_val_by_id/')?>" + <?= $sales_invoice_data->invoice_type; ?>);
		<?php } ?>
                <?php if(isset($invoice_type) && !empty($invoice_type)){ ?>
			setSelect2Value($("#invoice_type"),"<?=base_url('app/set_invoice_type_select2_val_by_id/')?>" + <?= $invoice_type; ?>);
		<?php } ?>
                
            shortcut.add("ctrl+s", function() {  
                $( "#save_btn" ).click();
            });

            $('#account_id').select2('open');
            $('#account_id').on("select2:close", function(e) { 
                $("#datepicker2").focus();
            });
            $('#prefix').on("select2:close", function(e) { 
                $("#sales_invoice_no").focus();
            });
            $(document).on('click', '#shipping_address_chkbox', function() {
                if($(this). prop("checked") == false) {
                    $('#shipping').removeClass('none');
                } else {
                    $('#shipping').addClass('none');
                }
            });
            $(document).on('click', '.save', function() {
                $('#is_print').val('0');
            });
            
            $(document).on('click', '.save_print', function() {
                $('#is_print').val('1');
             });
             
		$(document).on('submit', '#form_sales_invoice', function () {
			if(lineitem_objectdata == ''){
				show_notify("Please select any one Product.", false);
				return false;
			}
			$('.overlay').show();
			var postData = new FormData(this);
			var lineitem_objectdata_var = JSON.stringify(lineitem_objectdata);
                        postData.append('line_items_data', lineitem_objectdata_var);
			$.ajax({
				url: "<?=base_url('sales/save_sales_invoice') ?>",
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
                                    if (json['error'] == 'Exist'){
                                        show_notify("Sales Invoice No. Already Exist!", false);
                                        $('.overlay').hide();
                                        return false;
                                    }
                                    if (json['success'] == 'Added'){
                                            window.location.href = "<?php echo base_url('sales/invoice/') ?>";
                                            if(json['is_print'] == '1'){
                                                var href = "<?php echo base_url('sales/invoice_pdf_new/') ?>"+json['invoice_id'];
                                                window.open(href, '_blank');
                                            }
                                    }
                                    if (json['success'] == 'Updated'){
                                            window.location.href = "<?php echo base_url('sales/invoice_list') ?>";
                                    }
                                    $('.overlay').hide();
                                    return false;
                                },
                        });
                        return false;
                    });
        <?php if(!isset($sales_invoice_data->sales_invoice_no)) { ?>
            var prefix = $('#prefix').val();
            get_max_prefix(prefix);
        <?php } ?>
        $(document).on('change', '#prefix', function(){
            var prefix = $('#prefix').val();
            get_max_prefix(prefix);
            
        });
    });

    function get_max_prefix(prefix) {
        $.ajax({
                url: "<?=base_url('sales/get_max_prefix') ?>/" + prefix,
                type: "GET",
                processData: false,
                contentType: false,
                cache: false,
                data: '',
                success: function (response) {
                    var json = $.parseJSON(response);
                    $('#sales_invoice_no').val(json);
                    return false;
                },
        });
    }
</script>
