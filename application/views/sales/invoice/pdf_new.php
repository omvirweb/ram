<?php
ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Tally Sales Invoice Print</title>
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
        <style>
            .text-center {
                text-align: center !important;
            }
            .text-right {
                text-align: right !important;
            }
            .text-left {
                text-align: left !important;
            }
            .text-underline {
                text-decoration: underline;
            }
            .section-title{
                font-size: 20px;
                font-weight: 900;
                margin: 15px 10px;
            }
            .no-margin{
                margin: 0;
            }
            .content {
                padding: 10px;
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
            td {
                padding-left: 5px;
                padding-right: 5px;
            }
            .text-bold{
                font-weight:bold;
            }
        </style>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <h3 align="Center" style="font-size:20px;">
                <?php
                $invoice_type = $this->crud->get_id_by_val('invoice_type','invoice_type','invoice_type_id',$sales_invoice_data->invoice_type);
                echo strtoupper($invoice_type);
                ?>
            </h3>   

            <table width="100%" border="1">
                <tr>
                    <td valign="top" class="no-border-bottom" rowspan="5" style="width:50%"><span class="text-bold"><?=isset($user_name) ? $user_name : '' ?></span><br/>
                        <?=isset($user_address) ? nl2br($user_address) : '' ?><br/>
                        GSTIN/UIN: <?=isset($user_gst_no) ? $user_gst_no : '' ?><br/>
                        State Name : <?=isset($user_state) ? $user_state : '' ?>
                    </td>
                    <td style="width:25%;"> Invoice No <br/> <span class="text-bold"><?=isset($sales_invoice_no) ? $sales_invoice_no : '' ?></span></td>
                    <td class="no-border-left" style="width:25%;"> Dated  <br/> <span class="text-bold"><?=isset($sales_invoice_date) ? $sales_invoice_date : '' ?></span></td></td>
                </tr>
                <tr>
                    <td style="width:25%;"> Delivery Note <br/> &nbsp; </td>
                    <td class="no-border-left" style="width:25%;"> Mode/Terms of Payment <br/> &nbsp; </td>
                </tr>
                <tr>
                    <td style="width:25%;"> Supplier’s Ref. <br/> &nbsp; </td>
                    <td class="no-border-left" style="width:25%;"> Other Reference(s) <br/> &nbsp; </td>
                </tr>
                <tr>
                    <td style="width:25%;"> Buyer’s Order No. <br/> &nbsp; </td>
                    <td class="no-border-left" style="width:25%;"> Dated <br/> &nbsp; </td>
                </tr>
                <tr>
                    <td class="no-border-bottom" style="width:25%;"> Despatch Document No. <br/> &nbsp; </td>
                    <td class="no-border-left no-border-bottom" style="width:25%;"> Delivery Note Date <br/> &nbsp; </td>
                </tr>
            </table>
            <table width="100%" border="1" style="border-bottom:none !important;">
                <tr>
                    <td valign="top" class="no-border-bottom" style="width:50%"> Buyer <br/>
                        <span class="text-bold"><?=isset($account_name) ? $account_name : '';?></span>
                    </td>
                    <td style="width:25%;"> Despatched through <br/> &nbsp; </td>
                    <td class="no-border-left" style="width:25%;"> Destination <br/> &nbsp; </td>
                </tr>
                <tr>
                    <td valign="top" class="no-border-bottom no-border-top" style="width:50%">
                        <?=isset($account_address) ? nl2br($account_address) : '';?><br/>
                        GSTIN/UIN  : <?=isset($account_gst_no) ? $account_gst_no : '';?><br/>
                        State Name  : <?=isset($account_state) ? $account_state : '';?><br/></td>
                    <td class="no-border-bottom" valign="top" style="width:50%;" colspan="2" align="left">Terms Of Delivery<br/></td>
                </tr>
            </table>
            
            <table width="100%" border="1">
                <tr>
                    <td colspan="15"></td>
                </tr>
                <tr>
                    <td class="text-center" colspan="1" width="50px">Sr No.</td>
                    <td class="text-center" colspan="5" width="250px">Description Of Goods</td>
                    <td class="text-center" colspan="2" width="80px">HSN / SAC</td>
                    <td class="text-center" colspan="2" width="119px">Quantity</td>
                    <td class="text-center" colspan="2" width="90px">Rate</td>
                    <td class="text-center" colspan="1" width="50px">Per</td>
                    <td class="text-center" colspan="2" width="120px">Amount</td>
                </tr>
                <?php 
					$inc = 1; 
					foreach($lineitems as $lineitem){ 
					?>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px"><?php echo $inc; ?></td>
                        <td class="no-border-top no-border-bottom text-bold" colspan="5"><b><?php echo $lineitem->item_name;?></b></td>
                        <td class="no-border-top no-border-bottom" colspan="2"><?php echo $lineitem->hsn_code; ?></td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="2"><b><?php echo $lineitem->item_qty; ?> <?php echo $lineitem->rate_unit; ?></b></td>
                        <td class="no-border-top no-border-bottom" colspan="2"><?php echo $lineitem->price; ?></td>
                        <td class="no-border-top no-border-bottom" colspan="1"><?php echo $lineitem->rate_unit; ?></td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="2"><b><?php echo $lineitem->item_qty *  $lineitem->price;?></b></td>
                    </tr>
                <?php 
							$inc++; 
						} 
					?>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="5">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="5">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                </tr>
<!--                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right text-bold" colspan="5"><b>CGST @ 1.5%</b></td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right" colspan="2">1.50</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">%</td>
                    <td class="no-border-top no-border-bottom text-right text-bold" colspan="2"><b>4024</b></td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right text-bold" colspan="5"><b>SGST @ 1.5%</b></td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right" colspan="2">1.50</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">%</td>
                    <td class="no-border-top no-border-bottom text-right text-bold" colspan="2"><b>4024</b></td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right text-bold" colspan="5">Less :<strong>Round Off A/c</strong></td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom text-right text-bold" colspan="2"><b>(-)0.59</b></td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="5">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="5">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                </tr>-->
                <tr>
                    <td class="no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-bottom text-right" colspan="5">Total</td>
                    <td class="no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-bottom text-right text-bold" colspan="2"><?php echo $qty_total; ?></td>
                    <td class="no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <?php echo $total_amt = $pure_amount_total - $discount_total;?>
                    <td class="no-border-bottom text-right text-bold" colspan="2"><?php echo $total_amt;?></td>
                </tr>
                <tr>
                    <td class="no-border-bottom no-border-right" colspan="13">Amount Chargeable (In Words)</td>
                    <td class="no-border-bottom no-border-left text-right" colspan="2">E. & O.E</td>
                </tr>
                <tr>
                    <td class="no-border-bottom no-border-top text-bold" colspan="15"><?php echo $amount_total_amt_word; ?></td>
                </tr>

                <tr>
                    <td valign="top" colspan="3" rowspan="2" class="text-center">HSN/SAC</td>
                    <td valign="top" colspan="2" class="text-center no-border-bottom">Taxable Value</td>
                    <td valign="top" colspan="4" align="center">Central Tax</td>
                    <td valign="top" colspan="4" align="center">State Tax</td>
                    <td valign="top" colspan="2" class="no-border-bottom" align="center">Total Tax Amount</td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border-top" align="center"></td>
                    <td colspan="2" align="center">Rate</td>
                    <td colspan="2" align="center">Amount</td>
                    <td colspan="2" align="center">Rate</td>
                    <td colspan="2" align="center">Amount</td>
                    <td colspan="2" class="no-border-top" align="center"></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="3">&nbsp;</td>
                    <td class="text-right" colspan="2">&nbsp;</td>
                    <td class="text-right" colspan="2">&nbsp;</td>
                    <td class="text-right" colspan="2">&nbsp;</td>
                    <td class="text-right" colspan="2">&nbsp;</td>
                    <td class="text-right" colspan="2">&nbsp;</td>
                    <td class="text-right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-bold text-right" colspan="3">Total</td>
                    <td class="text-bold text-right" colspan="2">&nbsp;</td>
                    <td class="text-bold text-right" colspan="2">&nbsp;</td>
                    <td class="text-bold text-right" colspan="2">&nbsp;</td>
                    <td class="text-bold text-right" colspan="2">&nbsp;</td>
                    <td class="text-bold text-right" colspan="2">&nbsp;</td>
                    <td class="text-bold text-right" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="no-border-top no-border-bottom" colspan="15">Tax Amount (in words) : <span class="text-bold"></span></td>
                </tr>
                <br/><br/><br/><br/><br/><br/>
                <tr>
                    <td class="no-border-top no-border-bottom no-border-right" colspan="3">Company’s VAT TIN</td> 
                    <td class="no-border-top no-border-bottom no-border-left text-bold" colspan="12">: </td> 
                </tr>
                <tr>
                    <td colspan="3" class="no-border-top no-border-bottom no-border-right">Company’s CST No. </td> 
                    <td colspan="12" class="no-border-top no-border-bottom no-border-left text-bold">:  </td> 
                </tr>
                <tr>
                    <td colspan="3" class="no-border-top no-border-bottom no-border-right">Company’s PAN </td> 
                    <td colspan="12" class="no-border-top no-border-bottom no-border-left text-bold">: <?=isset($user_pan) ? $user_pan : '' ?></td> 
                </tr>
                <tr>
                    <td colspan="15" class="no-border-bottom no-border-top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="15" class="no-border-bottom no-border-top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="7" class="no-border-top no-border-bottom no-border-right"><span style="text-decoration: underline;">Declaration</span></td> 
                    <td colspan="8" class="text-right no-border-bottom text-bold"> for <?=isset($user_name) ? $user_name : '' ?> &nbsp; &nbsp; &nbsp; &nbsp;</td>
                </tr>
                <tr>
                    <td valign="top" colspan="7" rowspan="2" class="no-border-top">We declare that this invoice shows the actual price of 
                    goods described and that all particulars are true 
                    and correct.</td>
                <td colspan="8" class="no-border-bottom no-border-top">&nbsp;<br/><br/><br/><br/><br/><br/></td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right no-border-top">Authorised Signatory &nbsp; &nbsp; &nbsp; &nbsp;</td> 
                </tr>
                <tr>
                    <td colspan="15" class="no-border">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="15" class="no-border text-center">This is a Computer Generated Invoice</td>
                </tr>
            </table>
        </div>
    </body>
</html>
