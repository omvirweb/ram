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
        <table border="1" style="width: 100%; font-size: 13px">
            <?php if(!empty($letterpad_print)){ ?>
            <tr align="center" class="border1">
                <td align="center" colspan="16" class="border1" style="background-color: #EAEAEA;"><h2><?= isset($user_name) ? $user_name : '' ?></h2></td>
            </tr>
            <tr align="center" style="border: 1px 0px 1px 0px;">
                <td align="center" colspan="16"  class="">
                    <?=isset($user_address) ? $user_address : '' ?><br/>
                   <?=isset($user_phone) ? "Mo. ".$user_phone : '' ?> <?=isset($email_ids) ? "Email - ".$email_ids : '' ?>
                </td>
            </tr>
            <?php } else { ?>
            <tr align="center" class="border1">
                <td align="left" colspan="16" class="no-border-right">
                    <img style="height: 76px;" src="<?php if(isset($logo_image) && $logo_image != '') { echo base_url('assets/uploads/logo_image/'.$logo_image); } else {  echo base_url('assets/dist/img/ram-logo.png'); } ?>" class="saas-logo" alt="saas-logo">
                </td>
            </tr>
            <tr align="center" style="border: 1px 0px 1px 0px;">
                <td align="left" colspan="16">
                    <?=isset($user_address) ? $user_address : '' ?>&nbsp;&nbsp;
                    Mobile No. <?=isset($user_phone) ? $user_phone : '' ?>
                </td>
            </tr>
            <?php } ?>
            <tr class="border1">
                <td colspan="12" class="text_bold no-border-right" style="text-align: left;">Bill No.: <?=isset($sales_invoice_no) ?$sales_invoice_no : '' ?></td>
                <td colspan="4" class="text_bold text_right no-border-left no-border-right" style="text-align: center;">Date: <?=isset($sales_invoice_date) ? $sales_invoice_date : '';?>
                </td>
            </tr>
            <tr class="border1">
                <td colspan="16" class="text_bold no-border-right text_center"><u>INVOICE</u></td>
                </td>
            </tr>
            <tr class="">
                <td colspan="16" class="text_bold text_left no-border-bottom">To,</td>
            </tr>
            <tr class="">
                <td colspan="16" class="text_bold text_left no-border-bottom no-border-top"><?=isset($account_name) ? $account_name : '&nbsp;' ?></td>
            </tr>
            <tr class="">
                <td colspan="16" class="text_bold text_left no-border-bottom no-border-top"><?=isset($account_address) ? nl2br($account_address) : '';?></td>
            </tr>
            <tr class="">
                <td colspan="16" class="text_bold text_left no-border-bottom no-border-top"><?=isset($account_city) ? nl2br($account_city) : '';?></td>
            </tr>
            <tr class="">
                 <td align="center" colspan="16" class="text_bold no-border-bottom">Sub. : <?=isset($sales_subject)?$sales_subject:'';?></td>   
            </tr>
            <tr class=""></tr>
            <tr class="border1">
                <td colspan="1" width="50px" class="text_bold text_center">Sr No</td>
                <td colspan="9" class="text_bold text_center" width="250px">Particular</td>
                <!-- <td colspan="2" class="text_bold text_center">HSN/SAC</td> -->
                <td colspan="2" class="text_bold text_center">Qty</td>
                <!-- <td colspan="1" class="text_bold text_center">Unit</td> -->
                <td colspan="2" class="text_bold text_center">Rate</td>
                <!-- <td colspan="1" class="text_bold text_center">GST%</td> -->
                <td colspan="2" class="text_bold text_center" width="100px">Amount</td>
            </tr>
            <?php 
                $inc = 1;
                $discounted_price_total = 0;
                $other_charges = 0;
                $dis_total_amt = 0;
                $pure_total_amt = 0;
                $gst_amt = 0;
                // $row_count = count($lineitems);
                $row_count = 0;
                foreach($lineitems as $lineitem){ 
                    $row_count += (substr_count($lineitem->line_item_des, "<br>" ) + 1);
            ?>
            <tr>
                <td valign="top" colspan="1" class="text_center"><?php echo $inc; ?></td>
                <td valign="top" colspan="9" align="left"><?php echo $lineitem->line_item_des;?><br><?php echo $lineitem->note;?></td>
                <!-- <td valign="top" colspan="2" align="center"><?php //echo $lineitem->hsn_code; ?></td> -->
                <?php 
                    $unit = $this->crud->get_column_value_by_id('pack_unit', 'pack_unit_name', array('pack_unit_id' => $lineitem->unit_id));
                ?>
                <td valign="top" colspan="2" class="divRight"><?php echo $lineitem->item_qty; ?></td>
                <!-- <td valign="top" colspan="1" class="divRight"><?php //echo $unit; ?></td> -->
                <td valign="top" colspan="2" class="divRight"><?php echo $lineitem->price; ?></td>
                <?php //$gst = $lineitem->cgst +  $lineitem->sgst + $lineitem->igst?>
                <!-- <td valign="top" colspan="1" class="divRight"><?php //echo $lineitem->gst . '%'; ?></td> -->
                <?php
                    $pure_amt =  $lineitem->price * $lineitem->item_qty;

                    ?>
                <td valign="top" colspan="2" class="divRight"><?php echo number_format((float)$pure_amt, 2, '.', ''); ?></td>
            </tr>
            <?php
                if( $lineitem->discount_type == 1) {
                    $discount_amt = $pure_amt * $lineitem->discount / 100;
                }
                if ($lineitem->discount_type == 2) {
                    $discount_amt = $lineitem->discount;
                }

                ?>
            <?php 
                $discounted_price_total += $lineitem->discounted_price;
                $other_charges += $lineitem->other_charges;
                $dis_total_amt += $discount_amt;
                $pure_total_amt += $pure_amt;
                
                $inc++; 
                }
                $row_inc = 11 - $row_count;
                for($i = 1; $i <= $row_inc; $i++){
            ?>
                    <tr>
                        <td colspan="1"> &nbsp;</td>
                        <td colspan="9">&nbsp;</td>
                        <!-- <td colspan="2">&nbsp;</td> -->
                        <td colspan="2" class="divRight">&nbsp;</td>
                        <!-- <td colspan="1" class="divRight">&nbsp;</td> -->
                        <td colspan="2" class="divRight">&nbsp;</td>
                        <!-- <td colspan="1" class="divRight">&nbsp;</td> -->
                        <td colspan="2" class="divRight">&nbsp;</td>
                    </tr>
            <?php } ?>
            <tr class="border1" style="background-color: #EAEAEA;">
                <td colspan="11" class="text_bold">GSTIN No. : <?=isset($user_gst_no) ? $user_gst_no : '' ?></td>
                <td colspan="3" class="text_bold no-border-right">Total PF Amount </td>
                <td colspan="2" align="right" class="text_bold no-border-left"><?php echo number_format((float)$total_pf_amount, 2, '.', ''); ?></td>
            </tr>
            <tr class="">
                <td colspan="4" class="no-border-right no-border-bottom text_bold">Bank Name</td>
                <td colspan="7" class="no-border-left no-border-right no-border-bottom"> : <?=isset($bank_name) ? $bank_name : '' ?></td>
                <td colspan="3" class="no-border-top no-border-bottom no-border-right">Service Charge</td>
                <td colspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)($aspergem_service_charge), 2, '.', ''); ?></td>

            </tr>
            <tr class="">
                <td colspan="4" class="no-border-right no-border-top no-border-bottom text_bold">Branch</td>
                <td colspan="7" class="no-border"> : <?=isset($bank_branch) ? $bank_branch : '' ?></td>
                <td colspan="3" class="text_bold no-border-right">SUB TOTAL </td>
                <?php 
                    $pure_total_amt = $pure_total_amt + $total_pf_amount + $aspergem_service_charge;
                ?>
                <td colspan="2" align="right" class="text_bold no-border-left"><?php echo number_format((float)$pure_total_amt, 2, '.', ''); ?></td>

            </tr>
            <tr class="">
                <td colspan="4" class="no-border-right no-border-bottom no-border-top text_bold">Bank A/c. No.</td>
                <td colspan="7" class="no-border"> : <?=isset($bank_ac_no) ? $bank_ac_no : '' ?></td>
                <td colspan="3" class="no-border-top no-border-bottom no-border-right">CGST</td>
                <td colspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)($total_gst/2), 2, '.', ''); ?></td>

            </tr>
            <tr class="">
                <td colspan="4" class="no-border-right no-border-bottom no-border-top text_bold">RTGS/IFSC Code</td>
                <td colspan="7" class="no-border"> : <?=isset($rtgs_ifsc_code) ? $rtgs_ifsc_code : '' ?></td>
                <td colspan="3" class="no-border-top no-border-bottom no-border-right">SGST</td>
                <?php // $discounted_amt_total = $pure_total_amt + $dis_total_amt;?>
                <td colspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)($total_gst/2), 2, '.', ''); ?></td>
            </tr>
            <tr class="">
                <td colspan="4" rowspan="2" class="border1 no-border-right no-border-bottom text_bold">Total GST</td>
                <td colspan="7" rowspan="2" class=" border1 no-border-left no-border-bottom no-border-right"> : <?php echo $gst_total_word; ?></td>
                <td colspan="3" class="no-border-top no-border-bottom no-border-right">Prof. Tax</td>
                <td colspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)($prof_tax), 2, '.', ''); ?></td><br>
            </tr>
            <tr class="">
                <td colspan="3" rowspan="2" class="no-border-top no-border-bottom no-border-right">Round Off</td>
                <td colspan="2" rowspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)($round_off_amount), 2, '.', ''); ?></td>
            </tr>
            <tr class="border1 no-border-top no-border-bottom text_bold">
                <td colspan="16" class="no-border-right text_bold">&nbsp;</td>
            </tr>
            
            <tr class="border1" style="background-color: #EAEAEA;">
                <td colspan="4" class="no-border-right text_bold">Bill Amount</td>
                <td colspan="7" class="no-border-left no-border-right no-border-bottom"> : <?php echo $amount_total_word; ?></td>
                <td colspan="3" class="text_bold no-border-right ">Total Due</td>                
                <td colspan="2" align="right" class="text_bold no-border-left"><?php echo number_format((float)$amount_total, 2, '.', ''); ?></td>
            </tr>
            <tr class="border1">
                <td valign="top" colspan="11" class="no-border-right" style="font-size: 12px;"><span class="text_bold" >Note : </span><br/>
                    <?=isset($sales_note)?$sales_note:'';?>
                </td>
                <td valign="top" colspan="5" class="no-border-left text_bold">For, <?= isset($user_name) ? $user_name : '' ?><br/>
                    <br/>
                    <br/>
                    <br/>
                    <span style="text-align: right;">(Authorised Signatory)</span>
                </td>
            </tr>
        </table>

    </body>
</html>
