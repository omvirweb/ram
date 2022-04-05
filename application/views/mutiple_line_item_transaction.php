<?php $this->load->view('success_false_notify'); 
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <form id="form_voucher" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">        
        <input type="hidden" name="voucher_type" id="voucher_type" value="<?=$voucher_type?>">
    <section class="content-header">
            <h1>
                <?=$page_title;?>
                <?php if($this->applib->have_access_role($module_id,"view")) { ?>
                <a href="<?=$invoice_list_url;?>" class="btn btn-primary pull-right"><?=$voucher_label?> List</a> &nbsp;
                <?php } ?>
                
                <?php if($voucher_type == "sales" || $voucher_type == "dispatch") { ?>
                    <button type="submit" class="btn btn-primary form_btn pull-right save" id="save_btn"><?=$invoice_id > 0 ? 'Update' : 'Save' ?></button> &nbsp;

                    <?php if($invoice_id == 0) { ?>
                        <button type="submit" class="btn btn-primary form_btn pull-right save_print">Save & Print</button> &nbsp;  
                    <?php } ?>

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
                                <input type="checkbox" name="triplicate" value="2" id="triplicate" class="triplicate" <?php echo isset($invoice_data->print_type) && $invoice_data->print_type == 2 ? 'checked' : ''?>>  &nbsp; Triplicate
                            </label>
                        </div>
                    </span>
                    <span class="pull-right" style="margin-right: 20px;">
                        <div class="form-group">
                            <label for="duplicate" class="col-sm-12 input-sm" style="font-size: 18px; line-height: 25px;">
                                <input type="checkbox" name="duplicate" value="1" id="duplicate" class="duplicate" <?php echo isset($invoice_data->print_type) && ($invoice_data->print_type == 1 || $invoice_data->print_type == 2) ? 'checked' : ''?>>  &nbsp; Duplicate
                            </label>
                        </div>
                    </span>
                <?php } ?>
            </h1>
    </section>
    <!-- Main content -->
    <section class="content">
            <!-- START ALERTS AND CALLOUTS -->
            <div class="row">
                <div class="col-md-12">
                    <?php if($invoice_id > 0){ ?>
                    <input type="hidden" id="invoice_id" name="invoice_id" value="<?=$invoice_id;?>">
                    <?php } ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title form_title">
                                <?=$invoice_id > 0 ? 'Edit' : 'Add' ?>

                                <?php 
                                    if(isset($invoice_data->invoice_no)) {
                                        echo '<span> &nbsp;&nbsp;&nbsp;&nbsp; Invoice No. : '. $invoice_data->invoice_no;
                                    } 
                                    if(isset($invoice_data->credit_note_no)) {
                                        echo '<span> &nbsp;&nbsp;&nbsp;&nbsp; Credit Note No. : '. $invoice_data->credit_note_no;
                                    } 
                                    if(isset($invoice_data->debit_note_no)) {
                                        echo '<span> &nbsp;&nbsp;&nbsp;&nbsp; Debit Note No. : '. $invoice_data->debit_note_no;
                                    } 
                                ?>
                            </h3>&nbsp;&nbsp;&nbsp;&nbsp;

                            <?php if($voucher_type == "sales" || $voucher_type == "purchase"|| $voucher_type == "dispatch") { ?>
                                <?php 
                                if(isset($invoice_data->is_shipping_same_as_billing_address) && $invoice_data->is_shipping_same_as_billing_address == 1){
                                    $checked = '';
                                    $none = '';
                                } else if(isset($invoice_data->is_shipping_same_as_billing_address) && $invoice_data->is_shipping_same_as_billing_address == 0){
                                    $checked = 'checked';
                                    $none = 'none';
                                } else {
                                    $checked = 'checked';
                                    $none = 'none';
                                } ?>
                                <label for="shipping_address_chkbox">
                                    <input type="checkbox" id="shipping_address_chkbox" name="shipping_address_chkbox" <?php echo $checked; ?>>  &nbsp; Shipping Address Same As Biling Addres
                                </label>
                            <?php } ?>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
                                <div class="row">
                                    <div class="<?=$voucher_type == "sales"?"col-md-3":"col-md-6"?>">
                                        <div class="form-group">
                                            <label for="account_id" class="control-label">Account<span class="required-sign">*</span></label>
                                            <a class="btn btn-primary btn-xs pull-right add_account_link" href="javascript:;" data-url= "<?=base_url('account/account/')?>"><i class="fa fa-plus"></i> Add Account</a>
                                            <select name="account_id" id="account_id" class="account_id" required data-index="1"></select>
                                            <b>Current Balance : <span class="account_curr_balance"></span></b>
                                        </div>
                                    </div>
                                    <?php if($voucher_type == "sales") { ?>
                                    <div class="col-md-3 cash_customer_div" style="display: none;">
                                        <div class="form-group">
                                            <label for="cash_customer" class="control-label" id="cash_customer_label">Cash Customer</label>
                                            <input type="text" name="cash_customer" id="cash_customer" class="form-control" data-index="2" value="<?=isset($invoice_data->cash_customer) ? $invoice_data->cash_customer : ''; ?>">
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if($voucher_type != "material_in") { ?> 
                                    <div class="<?=$voucher_type == "sales"?"col-md-2":"col-md-2"?>">
                                    <?php } else { ?>
                                    <div class="col-md-6">
                                    <?php } ?>
                                        <div class="form-group">
                                            <label for="invoice_date" class="control-label"><?=$voucher_label?> Date<span class="required-sign">*</span></label>
                                            <input type="text" name="invoice_date" id="datepicker2" class="form-control date-size" data-index="2" required value="<?=isset($invoice_data->invoice_date) ? date('d-m-Y', strtotime($invoice_data->invoice_date)) : date('d-m-Y', strtotime($transaction_date)); ?>">
                                        </div>
                                    </div>
                                    <?php if($voucher_type != "material_in") { ?> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="our_bank_label" class="control-label">Our Bank Label</label>
                                            <select name="our_bank_label" id="our_bank_label" class="form-control select2 our_bank_label" required data-index="1"></select>
                                            
                                        </div>
                                    </div>
                                <?php } ?>
                                    <?php if($voucher_type != "material_in") { ?> 
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="tax_type" class="control-label">Tax Type<span class="required-sign">*</span></label>
                                                <select  name="tax_type" id="tax_type" class="form-control" data-index="3" required="">
                                                    <option value="1" <?php echo isset($invoice_data->tax_type) && $invoice_data->tax_type == 1 ? 'selected' : ''?>>GST</option>
                                                    <option value="2" <?php echo isset($invoice_data->tax_type) && $invoice_data->tax_type == 2 ? 'selected' : ''?>>IGST</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- here -->
                                    
                                    <?php if($voucher_type == "sales") { ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_type" class="control-label">Invoice Type<span class="required-sign">*</span></label>
                                            <select name="invoice_type" id="invoice_type" class="form-control select2" data-index="4" required=""></select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <?php if($voucher_type == "sales") { ?>
                                        <?php if(isset($company_invoice_prefix) && !empty($company_invoice_prefix)){ ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="prefix" class="control-label">Invoice Prefix</label>
                                                <select name="prefix" id="prefix" class="select2"></select>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <input type="hidden" id="prefix" name="prefix">
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Invoice No</label>
                                                <input type="text" name="invoice_no" id="invoice_no" class="form-control num_only" data-index="4" value="<?=isset($invoice_data->invoice_no) ? $invoice_data->invoice_no : $invoice_no; ?>">
                                            </div>
                                        </div>
                                    <?php } elseif($voucher_type == "purchase") { ?> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bill_no" class="control-label">Bill No.</label>
                                                <input type="text" name="bill_no" id="bill_no" class="form-control" data-index="4" value="<?=isset($invoice_data->bill_no) ? $invoice_data->bill_no : ''; ?>">
                                            </div>
                                        </div>
                                    <?php } elseif($voucher_type == "credit_note" || $voucher_type == "debit_note") { ?> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bill_no" class="control-label">Invoice No.<span class="required-sign">*</span></label>
                                                <input type="text" name="bill_no" id="bill_no" class="form-control" required data-index="3" value="<?=isset($invoice_data->bill_no) ? $invoice_data->bill_no : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="note_date" class="control-label">Invoice Date<span class="required-sign">*</span></label>
                                                <input type="text" name="note_date" id="datepicker3" class="form-control" required data-index="4" value="<?=isset($invoice_data->note_date) ? date('d-m-Y', strtotime($invoice_data->note_date)) : ''; ?>">
                                            </div>
                                        </div>
                                    <?php } elseif($voucher_type == "dispatch") {  ?>
                                        <?php if(isset($company_invoice_prefix) && !empty($company_invoice_prefix)){ ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="prefix" class="control-label">Invoice Prefix</label>
                                                <select name="prefix" id="prefix" class="select2"></select>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                        <input type="hidden" id="prefix" name="prefix">
                                        <?php } ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Invoice No</label>
                                                <input type="text" name="invoice_no" id="invoice_no" class="form-control num_only" data-index="4" value="<?=isset($invoice_data->invoice_no) ? $invoice_data->invoice_no : $invoice_no; ?>">
                                            </div>
                                        </div>
                                    <?php } elseif($voucher_type == "material_in") { ?> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bill_no" class="control-label">Bill No.</label>
                                                <input type="text" name="bill_no" id="bill_no" class="form-control" data-index="4" value="<?=isset($invoice_data->bill_no) ? $invoice_data->bill_no : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="vehicle_no" class="control-label">Vehicle No.</label>
                                                <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" data-index="4" value="<?=isset($invoice_data->vehicle_no) ? $invoice_data->vehicle_no : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="driver_name" class="control-label">Driver Name</label>
                                                <input type="text" name="driver_name" id="driver_name" class="form-control" data-index="4" value="<?=isset($invoice_data->driver_name) ? $invoice_data->driver_name : ''; ?>">
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if($voucher_type == "sales" || $voucher_type == "purchase" || $voucher_type == "dispatch") { ?> 
                                        <?php 
                                            $transport_name_display = 'hidden';
                                            if($this->applib->have_access_role($module_id,"Transport Name")) {
                                                $transport_name_display = '';
                                            }
                                        ?>
                                        <div class="col-md-3 <?=$transport_name_display;?>">
                                            <div class="form-group">
                                                <label for="transport_name" class="control-label">Transport Name</label>
                                                <input type="text" name="transport_name" id="transport_name" class="form-control" data-index="6" value="<?=isset($invoice_data->transport_name) ? $invoice_data->transport_name : ''; ?>">
                                            </div>
                                        </div>
                                        <?php 
                                            $lr_no_display = 'hidden';
                                            if($this->applib->have_access_role($module_id,"LR No")) {
                                                $lr_no_display = '';
                                            }
                                        ?>
                                        <div class="col-md-3 <?=$lr_no_display;?>">
                                            <div class="form-group">
                                                <label for="lr_no" class="control-label">LR No.</label>
                                                <input type="text" name="lr_no" id="lr_no" class="form-control" data-index="7" value="<?=isset($invoice_data->lr_no) ? $invoice_data->lr_no : ''; ?>">
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if($voucher_type == "sales") { ?>
                                    
                                    <?php } elseif($voucher_type == "purchase" || $voucher_type == "material_in") { ?>
                                    <div class="col-md-3 hidden">
                                        <div class="form-group">
                                            <label for="invoice_type" class="control-label">Invoice Type<span class="required-sign">*</span></label>
                                            <select name="invoice_type" id="invoice_type" class="form-control" data-index="8" required="">
                                                <option value='1' <?=$invoice_type == 1?'selected':''?>>Order</option>
                                                <option value='2' <?=$invoice_type == 2?'selected':''?>>Purchase</option>
                                                <option value='3' <?=$invoice_type == 3?'selected':''?>>Sales Order</option>
                                                <option value='4' <?=$invoice_type == 4?'selected':''?>>Material In</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if($voucher_type == "sales" || $voucher_type == "purchase" || $voucher_type == "dispatch") { ?>
                                    <div class="col-md-3 <?php echo $none; ?>" id="shipping">
                                        <div class="form-group">
                                            <label for="shipping_address" class="control-label">Shipping Address</label>
                                            <textarea name="shipping_address" id="shipping_address" class="form-control" data-index="9" placeholder=""><?=isset($invoice_data->shipping_address) ? $invoice_data->shipping_address : '' ?></textarea>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <div class="clearfix"></div>
                                    <?php if(!empty($invoice_main_fields)) { 
                                        $tmp_invoice_main_fields = array('your_invoice_no' => 'Your Invoice No.','shipment_invoice_no' => 'Shipment Invoice No.', 'shipment_invoice_date' => 'Shipment Invoice Date' , 'sbill_no' => 'S/Bill No.',  'sbill_date' => 'S/Bill Date' , 'origin_port' => 'Origin Port' , 'port_of_discharge' => 'Port of Discharge', 'container_size' => 'Container Size', 'container_bill_no' => 'Container Bill No', 'container_date' => 'Container Date' , 'container_no' => 'Container No.' , 'vessel_name_voy' => 'Vessel Name / Voy' ,  'print_date' => 'Print Date');

                                        $data_index = "10";
                                        foreach($invoice_main_fields as $value){
                                            if ($value == 'sbill_date'){
                                                $datepicker_class = 'datepicker';
                                                $sd = 'sbill_date';
                                                $old_value = isset($invoice_data->$sd) && !empty($invoice_data->$sd) ? date('d-m-Y', strtotime($invoice_data->$sd)) : '';
                                            } else if (strpos($value, 'date') !== false || strpos($value, 'date') !== false) {
                                                $datepicker_class = 'datepicker';
                                                $ee = $value;
                                                $old_value = isset($invoice_data->$ee) && !empty($invoice_data->$ee) ? date('d-m-Y', strtotime($invoice_data->$ee)) : '';
                                            } else if ($value == 'sbill_no'){
                                                $datepicker_class = '';
                                                $ss = 'sbill_no';
                                                $old_value = isset($invoice_data->$ss) ? $invoice_data->$ss : '';
                                            } else {
                                                $datepicker_class = '';
                                                $dd = $value;
                                                $old_value = isset($invoice_data->$dd) ? $invoice_data->$dd : '';
                                            }
                                    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="<?php echo $tmp_invoice_main_fields[$value]; ?>" class="control-label"><?php echo $tmp_invoice_main_fields[$value]; ?></label>
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
                                            <label for="invoice_desc" class="control-label">Description</label>
                                            <textarea name="invoice_desc" id="invoice_desc" class="form-control" data-index="5" placeholder=""><?=isset($invoice_data->invoice_desc) ? $invoice_data->invoice_desc : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary form_btn save"><?=$invoice_id > 0 ? 'Update' : 'Save' ?></button>
                            <b style="color: #0974a7">Ctrl + S</b>
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

        ss_data = '<?php echo $line_item_fields_data; ?>';
        ss_data1 = JSON.parse(ss_data);
        cgst_div = 0;
        cgst_amt_div = 0;
        sgst_div = 0;
        sgst_amt_div = 0;
        igst_div = 0;
        igst_amt_div = 0;
        if(ss_data1 != '' || ss_data1 != null){
            $.each(ss_data1, function (index, value) {
                console.log(value.setting_key);
                if(value.setting_key == 'cgst_per'){
                    cgst_div = 1;
                }
                if(value.setting_key == 'cgst_amt'){
                    cgst_amt_div = 1;
                }
                if(value.setting_key == 'sgst_per'){
                    sgst_div = 1;
                }
                if(value.setting_key == 'sgst_amt'){
                    sgst_amt_div = 1;
                }
                if(value.setting_key == 'igst_per'){
                    igst_div = 1;
                }
                if(value.setting_key == 'igst_amt'){
                    igst_amt_div = 1;
                }
            });
        }
        var jsonObj = [];
        $(".dfselect2").select2({
            width:"100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        initAjaxSelect2($("#account_id"),"<?=base_url('app/sp_account_select2_source_old/')?>");
        
        <?php if($voucher_type == "sales" || $voucher_type == "sales2") { ?>
        initAjaxSelect2($("#invoice_type"),"<?=base_url('app/invoice_type_select2_source/')?>");
        <?php if(isset($invoice_data->invoice_type) && !empty($invoice_data->invoice_type)){ ?>
        setSelect2Value($("#invoice_type"),"<?=base_url('app/set_invoice_type_select2_val_by_id/')?>" + <?= $invoice_data->invoice_type; ?>);
        <?php } ?>
        <?php } ?>

        <?php if($voucher_type == "purchase" || $voucher_type == "sales" || $voucher_type == "sales2") { ?>
        initAjaxSelect2($("#our_bank_label"),"<?=base_url('app/our_bank_label_select2_source/')?>");
        <?php if(isset($invoice_data->invoice_type) && !empty($invoice_data->invoice_type)){ ?>
        setSelect2Value($("#our_bank_label"),"<?=base_url('app/set_our_bank_label_select2_val_by_id/')?>" + <?= $invoice_data->our_bank_id; ?>);
        <?php } ?>
        <?php } ?>

        <?php if(isset($company_invoice_prefix) && !empty($company_invoice_prefix)){ ?>
            initAjaxSelect2($("#prefix"),"<?=base_url('app/prefix_select2_source/')?>");
            <?php if(isset($invoice_data->prefix) && !empty($invoice_data->prefix)){ ?>
                setSelect2Value($("#prefix"),"<?=base_url('app/set_prefix_select2_val_by_id/')?>" + <?=$invoice_data->prefix; ?>);
            <?php } else { ?>
                setSelect2Value($("#prefix"),"<?=base_url('app/set_prefix_select2_val_by_id/')?>");
            <?php } ?>
        <?php } ?>

        <?php if(isset($invoice_data->account_id) || isset($order_account_id)){ ?>
            var o_accouont_id = "<?= isset($invoice_data->account_id) ? $invoice_data->account_id : $order_account_id; ?>";
            setSelect2Value($("#account_id"),"<?=base_url('app/set_account_select2_val_by_id/')?>" + o_accouont_id);
            $.ajax({
                url: "<?= base_url('app/get_account_balance/') ?>"+ o_accouont_id,
                type: "GET",
                dataType: 'json',
                success: function (res) {
                    $('.account_curr_balance').html(res.balance);
                    <?php if($voucher_type == "sales") { ?>
                        if(res.cash_in_hand_acc == true) {
                            $(".cash_customer_div").show();
                            $("#cash_customer_label").html('Cash Customer<span class="required-sign">*</span>');
                            $("#cash_customer").prop("required",true);
                        } else {
                            $(".cash_customer_div").hide();
                            $("#cash_customer_label").html("Cash Customer");
                            $("#cash_customer").prop("required",false);
                        }
                    <?php } ?>
                },
            });
        <?php } ?>
                
        shortcut.add("ctrl+s", function() {  
            $("#form_voucher").submit();
        });

        $('#account_id').select2('open');

        $(document).on('change', '#account_id', function(){
            var account_id = $('#account_id').val();
            if(account_id != null) {
                $.ajax({
                    url: "<?= base_url('app/get_account_balance/') ?>" + account_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        $('.account_curr_balance').html(res.balance);
                        <?php if($voucher_type == "sales") { ?>
                            if(res.cash_in_hand_acc == true) {
                                $(".cash_customer_div").show();
                                $("#cash_customer_label").html('Cash Customer<span class="required-sign">*</span>');
                                $("#cash_customer").prop("required",true);
                                $("#datepicker2").datepicker("hide");
                                $("#cash_customer").focus();
                            } else {
                                $(".cash_customer_div").hide();
                                $("#cash_customer_label").html("Cash Customer");
                                $("#cash_customer").prop("required",false);
                                $("#datepicker2").focus();
                            }
                        <?php } else { ?>
                            $("#datepicker2").focus();
                        <?php } ?>
                    },
                });
            } else {
                $('.account_curr_balance').html('');
            }
            if(account_id != null) {
                $.ajax({
                    url: "<?= base_url('account/get_account_state_id/') ?>" + account_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        if(res == '2'){
                            $('#tax_type').val("2");
                            $('#tax_type').change();
                        } else {
                            $('#tax_type').val("1");
                            $('#tax_type').change();
                        }
                    },
                });
            } else {
                $('#tax_type').val("1");
                $('#tax_type').change();
            }
        });
        
        $(document).on('change', '#tax_type', function(){
            if($('#tax_type').val() == '2'){
                $('#cgst').val(0);
                $('#sgst').val(0);
                if(cgst_div == 1){
                    $('.cgst_class').hide();
                }
                if(cgst_amt_div == 1){
                    $('.cgst_class_amt').hide();
                }
                if(sgst_div == 1){
                    $('.sgst_class').hide();
                }
                if(sgst_amt_div == 1){
                    $('.sgst_class_amt').hide();
                }
                if(igst_div == 1){
                    $('.igst_class').show();
                }
                if(igst_amt_div == 1){
                    $('.igst_class_amt').show();
                }
                $('#igst').val(0);
            } else {
                if(cgst_div == 1){
                    $('.cgst_class').show();
                }
                if(cgst_amt_div == 1){
                    $('.cgst_class_amt').show();
                }
                if(sgst_div == 1){
                    $('.sgst_class').show();
                }
                if(sgst_amt_div == 1){
                    $('.sgst_class_amt').show();
                }
                $('#cgst').val(0);
                $('#sgst').val(0);
                $('#igst').val(0);
                if(igst_div == 1){
                    $('.igst_class').hide();
                }
                if(igst_amt_div == 1){
                    $('.igst_class_amt').hide();
                }
            }
        });

        $('#prefix').on("select2:close", function(e) { 
            $("#invoice_no").focus();
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
        
        $(document).on('submit','#form_voucher', function () {
            if(lineitem_objectdata == ''){
                show_notify("Please select any one Product.", false);
                return false;
            }
            $('.overlay').show();
            var postData = new FormData(this);
            var lineitem_objectdata_var = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_var);

            $.ajax({
                url: "<?=base_url('transaction/save_invoice')?>",
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
                        show_notify("Invoice No. Already Exist!", false);
                        $('.overlay').hide();
                        return false;
                    }
                    if (json['error'] == 'BillNoExist'){
                        show_notify("Bill No. Already Exist!", false);
                        $('.overlay').hide();
                        return false;
                    }
                    if (json['send_whatsapp_sms_no']){
                        window.location.href = window.open("https://web.whatsapp.com/send?phone=91"+json['send_whatsapp_sms_no']+"&text="+json['send_whatsapp_sms'], "_blank");
                    }
                    if (json['success'] == 'Added'){
                            var segments = window.location.pathname.split( '/' );
                            var segments0 = segments[1] || 0;
                            var segments1 = segments[2] || 0;
                            var segments2 = segments[3] || 0;
                            var segments3 = segments[4] || 0;
                            var segments4 = segments[5] || 0;
                            if(segments1 == 'transaction' && segments2 == 'sales_purchase_transaction' && segments3 == 'sales' && segments4 != '0'){
                                window.location.href = window.location.origin+"/"+segments0+"/"+segments1+"/"+segments2+"/"+segments3;
                            } else {
                                window.location.reload();
                            }
                            if(json['is_print'] == '1') {
                                <?php if($module_id == MODULE_SALES_INVOICE_ID) { ?>
                                    var href = "<?php echo base_url('sales/invoice_pdf_new/') ?>"+json['invoice_id'];
                                <?php } else { ?>
                                    var href = "<?php echo base_url('purchase/invoice_pdf_new/') ?>"+json['invoice_id'];
                                <?php } ?>
                                window.open(href, '_blank');
                            }
                    }
                    if (json['success'] == 'Updated'){
                            window.location.href = "<?=$invoice_list_url;?>";
                    }
                    $('.overlay').hide();
                    return false;
                },
            });
            return false;
        });

        <?php if($voucher_type == 'sales' && !isset($invoice_data->invoice_no)) { ?>
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
                $('#invoice_no').val(json);
                return false;
            },
        });
    }
</script>