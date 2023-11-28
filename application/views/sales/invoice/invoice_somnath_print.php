<?php
ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Format 2223 Sales Invoice Print</title>
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
            <tr align="center" class="border1">
                <td align="left" colspan="16" class="no-border-right">
                    <?=isset($user_address) ? $user_address : '' ?>&nbsp;&nbsp;
                    Mobile No. <?=isset($user_phone) ? $user_phone : '' ?>
                </td>
            </tr>
            <?php } ?>
            <tr class="border1">
                <td colspan="6" class="text_bold no-border-right" style="text-align: left;">Debit Memo</td>
                <td colspan="5" class="text_bold text_center no-border-left no-border-right" style="text-align: center;">
                    <?php
                        $invoice_type = $this->crud->get_id_by_val('invoice_type','invoice_type','invoice_type_id',$sales_invoice_data->invoice_type);
                        echo strtoupper($invoice_type);
                    ?>
                </td>
                <?php
                    // if($printtype == 0) {
                    //     $print = 'Original';
                    // } elseif ($printtype == 1) {
                    //     $print = 'Duplicate';
                    // } elseif ($printtype == 2) {
                    //     $print = 'Triplicate';
                    // }
                ?>
                <td colspan="6" class="text_bold text_center no-border-left" style="text-align: right;"><?php //echo $print; ?></td>
            </tr>
            <tr class="">
                <td colspan="2" class="text_bold text_left no-border-bottom no-border-right">M/s &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
                <td colspan="8" class="text_bold text_left no-border-left no-border-bottom"><?=isset($account_name) ? $account_name : '&nbsp;' ?></td>
                <td colspan="3" class="text_bold text_left no-border-right no-border-bottom">Invoice No.</td>
                <td colspan="3" class="text_bold text_left no-border-left no-border-bottom"> : <?=isset($sales_invoice_no) ?$sales_invoice_no : '' ?></td>
            </tr>
            <tr class="">
                <td colspan="2" rowspan="3" valign="top" class="text_bold text_left no-border-bottom no-border-right no-border-top">Address &nbsp;&nbsp;<b class="text_bold"> : </b></td>
                <td colspan="8" rowspan="3" valign="top" class=" text_left no-border-bottom no-border-left no-border-top"><span><?=isset($account_address) ? nl2br($account_address) : '';?></span></td>
                <td colspan="3" valign="top" class="text_bold text_left no-border-bottom no-border-right no-border-top">Date</td>
                <td colspan="3" valign="top" class="text_bold text_left no-border-bottom no-border-left no-border-top"><b> : </b><?=isset($sales_invoice_date) ? $sales_invoice_date : '';?></td>
            </tr>
            <tr class=""></tr>
            <tr class=""></tr>
            <tr class="">
                <td colspan="2" class="text_bold text_left no-border-bottom no-border-right no-border-top" style="white-space: nowrap;">GSTIN No. : </td>
                <td colspan="8" class=" text_left no-border-bottom no-border-left no-border-top"><?=isset($account_gst_no) ? $account_gst_no : '';?></td>
                <td colspan="3" class="text_bold text_left no-border-bottom no-border-right no-border-top">Place Of Supply</td>
                <td colspan="3" class=" text_left no-border-bottom no-border-left no-border-top"><b class="text_bold"> : </b><?=isset($account_state) ? $account_state : '';?></td>
            </tr>
            <tr class="">
                <td colspan="2" class="text_bold text_left no-border-bottom no-border-right no-border-top" style="white-space: nowrap;">Site Name:</td>
                <td colspan="8" class=" text_left no-border-bottom no-border-left no-border-top">
                    <?= (isset($site_name)) ? $site_name.'<br/>' : ''; ?>
                </td>
                <td colspan="6" class="text_bold text_left no-border-bottom no-border-top">&nbsp;</td>
            </tr>
            <tr class="border1">
                <td colspan="1" width="50px" class="text_bold text_center">Sr No</td>
                <td colspan="7" class="text_bold text_center" width="250px">Product Name</td>
                <td colspan="2" class="text_bold text_center">HSN/SAC</td>
                <td colspan="1" class="text_bold text_center">Qty</td>
                <td colspan="1" class="text_bold text_center">Unit</td>
                <td colspan="1" class="text_bold text_center">Rate</td>
                <td colspan="1" class="text_bold text_center">GST%</td>
                <td colspan="2" class="text_bold text_center" width="100px">Amount</td>
            </tr>
            <?php 
                $inc = 1;
                $discounted_price_total = 0;
                $other_charges = 0;
                $dis_total_amt = 0;
                $pure_total_amt = 0;
                $gst_amt = 0;
                $row_count = count($lineitems);
                foreach($lineitems as $lineitem){ 
            ?>
            <tr>
                <td valign="top" colspan="1" class="text_center"><?php echo $inc; ?></td>
                <td valign="top" colspan="7" align="left"><?php echo isset($lineitem->item_name) ? $lineitem->item_name : $lineitem->line_item_des;?><br><?php echo $lineitem->note;?></td>
                <td valign="top" colspan="2" align="center"><?php echo $lineitem->hsn ?  $lineitem->hsn : $lineitem->hsn_code; ?></td>
                <?php 
                    $unit = $this->crud->get_column_value_by_id('pack_unit', 'pack_unit_name', array('pack_unit_id' => $lineitem->unit_id));
                ?>
                <td valign="top" colspan="1" class="divRight"><?php echo $lineitem->item_qty; ?></td>
                <td valign="top" colspan="1" align="center"><?php echo $unit; ?></td>
                <td valign="top" colspan="1" class="divRight"><?php echo number_format((float)$lineitem->price,2,'.',''); ?></td>
                <!-- <td valign="top" colspan="1" class="divRight"><?php echo round($lineitem->price); ?>.00</td> -->
                <?php //$gst = $lineitem->cgst +  $lineitem->sgst + $lineitem->igst?>
                <td valign="top" colspan="1" class="divRight"><?php echo $lineitem->gst . '%'; ?></td>
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
                $row_inc = 15 - $row_count;
                for($i = 1; $i <= $row_inc; $i++){
            ?>
                    <tr>
                        <td colspan="1"> &nbsp;</td>
                        <td colspan="7">&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                        <td colspan="1" class="divRight">&nbsp;</td>
                        <td colspan="1" class="divRight">&nbsp;</td>
                        <td colspan="1" class="divRight">&nbsp;</td>
                        <td colspan="1" class="divRight">&nbsp;</td>
                        <td colspan="2" class="divRight">&nbsp;</td>
                    </tr>
            <?php } ?>
            <tr class="border1" style="background-color: #EAEAEA;">
                <td colspan="11" class="text_bold">GSTIN No. : <?=isset($user_gst_no) ? $user_gst_no : '' ?></td>
                <td colspan="3" class="text_bold no-border-right">Sub Total </td>
                <td colspan="2" align="right" class="text_bold no-border-left"><?php echo number_format((float)$pure_total_amt, 2, '.', ''); ?></td>
            </tr>
            <tr class="">
                <td colspan="4" class="no-border-right no-border-bottom text_bold">Bank Name</td>
                <td colspan="7" class="no-border-left no-border-right no-border-bottom"> : <?=isset($bank_name) ? $bank_name : '' ?></td>
                <td colspan="3"  class="no-border-bottom no-border-right">Discount</td>
                <td colspan="2" align="right" class="no-border-left no-border-bottom"><?php echo number_format((float)$dis_total_amt, 2, '.', ''); ?></td>
            </tr>
            <tr class="">
                <td colspan="4" class="no-border-right no-border-top no-border-bottom text_bold">Branch</td>
                <td colspan="7" class="no-border"> : <?=isset($bank_branch) ? $bank_branch : '' ?></td>
                <td colspan="3" class="no-border-top no-border-bottom no-border-right">Taxable Amount</td>
                <?php // $discounted_amt_total = $pure_total_amt + $dis_total_amt;?>
                <td colspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)$discounted_price_total, 2, '.', ''); ?></td>
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
                <td colspan="2" align="right" class="no-border-left no-border-top no-border-bottom"><?php echo number_format((float)($total_gst/2), 2, '.', ''); ?></td>
            </tr>
            <tr class="">
                <td colspan="4" rowspan="2" class="border1 no-border-right no-border-bottom text_bold">Total GST</td>
                <td colspan="7" rowspan="2" class=" border1 no-border-left no-border-bottom no-border-right"> : <?php echo $gst_total_word; ?></td>
            </tr>
            <tr class="border1 no-border-top no-border-bottom text_bold">
                <td colspan="5" class="no-border-right text_bold">&nbsp;</td>
            </tr>
            
            <tr class="border1" style="background-color: #EAEAEA;">
                <td colspan="4" class="no-border-right text_bold">Bill Amount</td>
                <td colspan="7" class="no-border-left no-border-right no-border-bottom"> : <?php echo $amount_total_word; ?></td>
                <td colspan="3" class="text_bold no-border-right ">Grand Total</td>
                <td colspan="2" align="right" class="text_bold no-border-left"><?php echo number_format((float)$amount_total, 2, '.', ''); ?></td>
            </tr>
            <tr class="border1">
                <td valign="top" colspan="11" class="no-border-right" style="font-size: 12px;"><span class="text_bold" >Terms & Condition : </span><br/>
                    <?php echo $terms_data; ?>
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
