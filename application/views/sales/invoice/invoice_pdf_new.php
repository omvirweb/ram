<?php
ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Format 3 Sales Invoice Print</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/ionicons.min.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <style>
            td,th{padding:5px}
            td{border-bottom-style: none;border-top-style:none;}
            .center {text-align: center;}
            .right {text-align: right;}
            h2{ font-family: "Impact", Charcoal, sans-serif;}
            .border1{border:1;}
            .divRight{text-align: right;}
            .text_bold{
                font-weight: bold;
            }
            .text_center{
                text-align: center;
            }
            .text_left{
                text-align: left;
            }
            .text_right{
                text-align: right;
            }
            .no-border-top{
                border-top:0;
            }
            .no-border-bottom{
                border-bottom:0 !important;
            }
            .no-border-left{
                border-left:0;
            }
            .no-border-right{
                border-right:0;
            }
            .no-border {
                border: 0;
            }
            .border-right {
                border-right: 1px solid black;
                border-left: 0;
                border-top: 0;
                border-bottom: 0;
            }
        </style>

    </head>
    <body>
        <table border="1" style="width: 94%; font-size: 13px" align="right">
            <?php if($invoice_type == INVOICE_TYPE_INVOICE_ID && $is_letter_pad == '1'){ ?>
                <tr align="center" class="">
                    <td align="left" colspan="50%" class="no-border-left no-border-right">
                        <span class="text_bold text_left"><?= isset($user_name) ? $user_name : '' ?></span><br/>
                        <?= isset($user_address) ? nl2br($user_address) : '' ?><br/>
                        <?= '<b><span class="text_bold">'.$user_city.'-'.$user_postal_code.'('.$user_state.' - '.$user_country. ')</span></b>'; ?><br/>
                        <?= '<b><span class="text_bold">GSTIN : '.$user_gst_no. '</span></b>'; ?>
                        <br/>
                        <br/>
                        <br/>
                    </td>
                    <td align="right" colspan="50%" class="no-border-left no-border-right">
                        <img src="<?php echo base_url('assets/uploads/logo_image/' . $logo_image); ?>" height="100px" />
                    </td>
                </tr>
                
            <?php } else { ?>
                <tr align="center" class="">
                    <td align="center" colspan="100%" class="no-border-left no-border-right">&nbsp;</td>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </tr>
            <?php } ?>
            
        <?php if($invoice_type == INVOICE_TYPE_REIMBURSEMENT_ID){
            $colspan_1 = '50%';
            $colspan_2 = '50%';
            $width = '';
        } else if($invoice_type == INVOICE_TYPE_INVOICE_ID){
            $colspan_1 = '90%';
            $colspan_2 = '10%';
            $width = '';
        } else {
            $colspan_1 = '86%';
            $colspan_2 = '14%';
            $width = '300px';
            $width2 = '90px';
        } ?>
        <?php
            $total_lines = 12;
            $invoice_type_name = '';
            $detail_name = '';
            if ($invoice_type == INVOICE_TYPE_FREIGHT_ID) {
                $invoice_type_name = 'FREIGHT INVOICE';
                $detail_name = 'SHIPMENT DETAILS';
                $total_lines = 9;
            } else if ($invoice_type == INVOICE_TYPE_REIMBURSEMENT_ID) {
                $invoice_type_name = 'REIMBURSEMENT - INVOICE';
                $detail_name = 'YOUR INVOICE NO. : '.$your_invoice_no;
            } else if ($invoice_type == INVOICE_TYPE_INVOICE_ID) {
                $invoice_type_name = 'INVOICE';
                $detail_name = 'YOUR INVOICE NO. : '.$your_invoice_no;
            }
            ?>
        <tr class="border1">
            <td colspan="<?=$colspan_1;?>"  width="<?=$width;?>" class="text_bold" style="text-align: left;">Invoice : <b><?=isset($sales_invoice_no) ? isset($prefix) ? $sales_invoice_no : $sales_invoice_no : '' ?></b></td>
            <td colspan="<?=$colspan_2;?>"  class="text_bold" style="text-align: left;">Date : <b><?= isset($sales_invoice_date) ? $sales_invoice_date : '' ?></b></td>
        </tr>
        <tr class="">
            <td colspan="100%" class="text_bold text_left no-border text_center text_bold" style="font-size: 20px; color:#d43021;"><?php echo $invoice_type_name; ?></td>
        </tr>
        
        <tr class="border1">
            <td colspan="<?=$colspan_1;?>" class="text_bold text_left" >BILL TO</td>
            <td colspan="<?=$colspan_2;?>" class="text_bold text_left" ><?php echo $detail_name; ?></td>
        </tr>
        <?php
//        echo '<pre>'; print_r($invoice_type); exit;
        if($invoice_type == INVOICE_TYPE_FREIGHT_ID){
            $row_span = 1;
            $field_1 = '';
            $field_1_data = '';
            $field_2 = '';
            $field_2_data = '';
            $sales_invoice_main_fields = array('your_invoice_no' => 'Your Invoice No.','shipment_invoice_no' => 'Shipment Invoice No.', 'shipment_invoice_date' => 'Shipment Invoice Date' , 'sbill_no' => 'S/Bill No.',  'sbill_date' => 'S/Bill Date' , 'origin_port' => 'Origin Port' , 'port_of_discharge' => 'Port of Discharge', 'container_size' => 'Container Size', 'container_bill_no' => 'Container Bill No', 'BL NO / DATE' => 'BL NO / DATE' , 'container_no' => 'Container No.' , 'vessel_name_voy' => 'Vessel Name / Voy' ,  'print_date' => 'Print Date');
            if (!empty($invoice_main_fields)) {
                $row_span = count($invoice_main_fields);
                $field_1 = $invoice_main_fields[0];
                $field_1_data = $invoice_main_fields_data[0];
                unset($invoice_main_fields[0]);
                unset($invoice_main_fields_data[0]);
            }
            if (!empty($invoice_main_fields)) {
                $field_2 = $invoice_main_fields[1];
                $field_2_data = $invoice_main_fields_data[1];
                unset($invoice_main_fields[1]);
                unset($invoice_main_fields_data[1]);
            }
            if (!empty($invoice_main_fields)) {
                $row_span = count($invoice_main_fields) + 1;
            }
            ?>
            <tr class="">
                <td colspan="86%"  class="text_bold text_bold text_left no-border-bottom no-border-top"><?= isset($account_name) ? $account_name : '&nbsp;' ?></td>
                <td colspan="5%" width="<?=$width2;?>"  class="text_left border1"><?= isset($sales_invoice_main_fields[$field_1]) ? strtoupper($sales_invoice_main_fields[$field_1]) : '&nbsp;' ?></td>
                <td colspan="9%"  class="text_left border1"><?= isset($field_1_data) ? $field_1_data : '&nbsp;' ?></td>
            </tr>
            <tr class="">
                <td colspan="86%"   rowspan="<?php echo $row_span ?>" valign="top" class="border1 text_left no-border-top">
                    <?= isset($account_address) ? nl2br($account_address) : '&nbsp;' ?>
                    <?= '<br/><b><span class="text_bold">'.$account_city.'-'.$account_postal_code.'('.$account_state.' - '.$account_country. ')</span></b>'; ?>
                    
                </td>
                <td colspan="5%" width="<?=$width2;?>"  class="text_left border1 no-border-top"><?= isset($sales_invoice_main_fields[$field_2]) ? strtoupper($sales_invoice_main_fields[$field_2]) : '&nbsp;' ?></td>
                <td colspan="9%"  class="text_left border1"><?= isset($field_2_data) ? $field_2_data : '&nbsp;' ?></td>
            </tr>
            <?php 
            if(!empty($invoice_main_fields)) {
            foreach ($invoice_main_fields as $fi_key => $invoice_main_fi) { ?>
                <tr class="">
                    <td colspan="5%" width="<?=$width2;?>"  class="text_left border1"><?= strtoupper($sales_invoice_main_fields[$invoice_main_fi]); ?></td>
                    <td colspan="9%"  class="text_left border1"><?= $invoice_main_fields_data[$fi_key]; ?></td>
                </tr>
            <?php } ?>
            <?php } ?>
        <?php } ?>
        <?php if($invoice_type == INVOICE_TYPE_REIMBURSEMENT_ID || $invoice_type == INVOICE_TYPE_INVOICE_ID){ ?>
            <tr class="">
                <td colspan="<?=$colspan_1;?>"  class="text_bold text_bold text_left no-border-bottom no-border-top"><?= isset($account_name) ? $account_name : '&nbsp;' ?></td>
                <td colspan="<?=$colspan_2;?>"  class="text_left border1 no-border-bottom no-border-top">&nbsp;</td>
            </tr>
            <tr class="">
                <td colspan="<?=$colspan_1;?>"  rowspan="" valign="top" class="border1 text_left no-border-top">
                    <?= isset($account_address) ? nl2br($account_address) : '&nbsp;' ?>
                    <?= '<br/><b><span class="text_bold">'.$account_city.'-'.$account_postal_code.'('.$account_state.' - '.$account_country. ')</span></b>'; ?>
                </td>
                <td colspan="<?=$colspan_2;?>"  valign="top" class="text_left border1 no-border-top">&nbsp;</td>
            </tr>
        <?php } ?>
    </table>
    <br/>
    <table border="1" style="width: 94%; font-size: 13px" align="right">
        <tr class="border1">
            <td colspan="" width="60px" style="white-space: nowrap;" class="text_center text_bold" >SR.&nbsp;NO</td>
            <td colspan="38%" class="text_center text_bold" >DESCRIPTION</td>
            <td colspan="20%" class="text_center text_bold" >QUANTITY</td>
            <td colspan="20%" class="text_center text_bold text_right" >RATE</td>
            <td colspan="20%" class="text_center text_bold text_right" >Total Rs.</td>
        </tr>
        <?php if($invoice_type == INVOICE_TYPE_REIMBURSEMENT_ID || $invoice_type == INVOICE_TYPE_INVOICE_ID){ ?>
            <tr class="">
                <td colspan="" width="60px" style="white-space: nowrap;" class="text_center text_bold" >&nbsp;</td>
                <td colspan="38%" class="text_center text_bold" ><?= isset($sales_invoice_desc) ? nl2br($sales_invoice_desc) : '&nbsp;' ?></td>
                <td colspan="20%" class="text_center text_bold" >&nbsp;</td>
                <td colspan="20%" class="text_center text_bold text_right" >&nbsp;</td>
                <td colspan="20%" class="text_center text_bold text_right" >&nbsp;</td>
            </tr>
        <?php  } ?>
        <?php  if (!empty($lineitems)) { ?>
            <?php $inc = 1; ?>
            <?php foreach ($lineitems as $item) { ?>
                <tr class="">
                    <td colspan="" class="text_center text_bold" ><?php echo $inc; ?></td>
                    <td colspan="38%" class="text_center text_bold" ><?php echo $item->item_name; ?></td>
                    <td colspan="20%" class="text_center text_bold" ><?= !empty($is_dollar_sign) ? '$' : ''; ?><?php echo $item->item_qty; ?></td>
                    <td colspan="20%" class="text_center text_bold text_right" ><?php echo number_format((float) $item->price_for_itax, 2, '.', ''); ?></td>
                    <td colspan="20%" class="text_center text_bold text_right" ><?php echo number_format((float) $item->pure_amount, 2, '.', ''); ?></td>
                </tr>
                <?php $inc++; $total_lines--; ?>
            <?php } ?>
        <?php } ?>
        <?php if($total_lines > 0){ ?>
        <?php for($x = 1; $x <= $total_lines; $x++){ ?>
        <tr class="">
            <td colspan="" class="text_center text_bold" >&nbsp;</td>
            <td colspan="38%" class="text_center text_bold" >&nbsp;</td>
            <td colspan="20%" class="text_center text_bold" >&nbsp;</td>
            <td colspan="20%" class="text_center text_bold" >&nbsp;</td>
            <td colspan="20%" class="text_center text_bold" >&nbsp;</td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr class="border1">
            <td colspan="" style="white-space: nowrap;" class="text_center text_bold" >Rupees</td>
            <td colspan="98%" class="text_left text_bold" ><?php echo $amount_total_word.' Only.'; ?></td>
        </tr>
    </table>
    <br/>
    <div>
        <div style="float: left; width: 50%">
        <div style="float: left; width: 12%">
        </div>
        <div style="float: right; width: 88%">
            <table border="0" style="width: 100%; font-size: 13px">
                <tr class="">
                    <td colspan="100%" class="text_left text_bold"><?= isset($sales_invoice_notes) ? $sales_invoice_notes : '&nbsp;' ?></td>
                </tr>
            </table>
        </div>
        </div>
        <div style="float: right; width: 40%">
            <table border="1" style="width: 100%; font-size: 13px">
                <tr class="border1">
                    <td colspan="20%" class="text_center text_bold" >Total</td>
                    <td colspan="80%" class="text_right text_bold" >Rs.<?php echo number_format((float) $amount_total, 2, '.', ''); ?></td>
                </tr>
                <tr class="border1">
                    <td colspan="100%"  class="text_right">For, <span class="text_bold"><?php echo $user_name; ?></span> <br/><br/><br/> Authorized Signatory.</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
