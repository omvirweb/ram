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
                font-family: sans-serif;
                font-size: 11px;
                color: black;
            }
            .text-bold{
                font-weight:bold;
            }
        </style>
    </head>
    
    <body class="hold-transition skin-blue sidebar-mini" style="padding-top: 0px;">
        <div class="wrapper">
            <table width="100%" border="1" class="no-border-top" style="padding-top: 0px;">
                <tr>
                    <td colspan="15" class="no-border-top" ></td>
                </tr>
                <tr>
                    <td class="text-center"  width="5%">Sr No.</td>
                    <td class="text-center" colspan="5" width="30%">Description Of Goods</td>
                    <td class="text-center" colspan="2" width="10%">HSN / SAC</td>
                    <td class="text-center no-border-right" colspan="2" width="10%">Quantity</td>
                    <td class="text-center" colspan="2" width="15%">Rate</td>
                    <td class="text-center"  width="10%">Per</td>
                    <td class="text-center" colspan="2" width="20%">Amount</td>
                </tr>
                <?php 
					$inc = 1; 
                    $totalAmount = 0;
					foreach($lineitems as $lineitem){  ?>
                        <tr>
                            <td class="no-border-top no-border-bottom" colspan="1" width="50px"><?php echo $inc; ?></td>
                            <td class="no-border-top no-border-bottom text-bold" colspan="5"><b><?php echo isset($lineitem->item_name) ? $lineitem->item_name : $lineitem->line_item_des; ?></b></td>
                            <td class="no-border-top no-border-bottom" colspan="2"><?php echo $lineitem->hsn ?  $lineitem->hsn : $lineitem->hsn_code; ?></td>
                            <td class="no-border-top no-border-bottom no-border-right text-right text-bold" colspan="2"><b><?php echo $lineitem->item_qty; ?> <?php echo $lineitem->rate_unit; ?></b></td>
                            <td class="no-border-top no-border-bottom" colspan="2"><?php echo $lineitem->price; ?></td>
                            <td class="no-border-top no-border-bottom" colspan="1"><?php echo $lineitem->rate_unit; ?></td>
                            <td class="no-border-top no-border-bottom text-right text-bold" colspan="2">
                                <b>
                                <?php echo number_format($lineitem->item_qty *  $lineitem->price, 2); ?></b></td>
                        </tr>  <?php 
							$inc++; 
                            $totalAmount += $lineitem->item_qty *  $lineitem->price;
						} 
                        $totalGst = ($totalAmount*18/100);
                        $sgstAmount = $totalGst/2;
                        $cgstAmount = $totalGst/2;
                        $totalSalesAmount = $totalAmount+$totalGst;
					?>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="5">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-bottom text-right" style="font-size: 12px;padding: 5px;" colspan="2"><?php echo number_format($totalAmount, 2); ?></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="5"><i>CGST OUTPUT</i></td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold padding: 5px;" colspan="2"><b><?php echo number_format($cgstAmount, 2); ?></b></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="5"><i>SGST OUTPUT</i></td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold padding: 5px;" colspan="2"><b><?php echo number_format($sgstAmount, 2); ?></b></td>
                    </tr>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="5">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold padding: 5px;" colspan="2">&nbsp;</td>
                    </tr>
                    <?php for($i=0;$i<33;$i++){ ?>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="5">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom no-border-right" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold" colspan="5"><i>ROUND OFF</i></td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right" colspan="2">&nbsp;</td>
                        <td class="no-border-top no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                        <td class="no-border-top no-border-bottom text-right text-bold padding: 5px;" colspan="2"><b><?php echo number_format($round_off_amount, 2); ?></b></td>
                    </tr>
                

                <!-- 
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
                    </tr>
                -->
                <?php 
                    $totalAmount = $totalAmount + $total_pf_amount;
                    $amount_totalToDisplay = $aspergem_service_charge + $totalAmount + $totalGst + $prof_tax + $round_off_amount;
                ?>
                <tr>
                    <td class="no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <td class="no-border-bottom text-right" colspan="5">Total</td>
                    <td class="no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-bottom text-right text-bold" colspan="2"><?php echo $qty_total; ?></td>
                    <td class="no-border-bottom" colspan="2">&nbsp;</td>
                    <td class="no-border-bottom" colspan="1" width="50px">&nbsp;</td>
                    <?php // echo $total_amt = $pure_amount_total - $discount_total;?>
                    <?php echo $total_amt = $pure_amount_total - $discount_total;?>
                    <td class="no-border-bottom text-right text-bold" colspan="2"><?php echo number_format($amount_totalToDisplay, 2); ?></td>
                </tr>
                <tr>
                    <td class="no-border-bottom no-border-right" colspan="13">Amount Chargeable (In Words)</td>
                    <td class="no-border-bottom no-border-left text-right" colspan="2">E. & O.E</td>
                </tr>
                <tr>
                    <td class="no-border-bottom no-border-top text-bold" colspan="15"><?php echo (isset($amount_totalToDisplay) && $amount_totalToDisplay != '' ) ? $this->numbertowords->convert_number($amount_totalToDisplay) : $amount_total_word; ?></td>
                </tr>
                <tr>
                    <td class="no-border-bottom no-border-top no-border-right" style="padding: 5px;" colspan="3">Company’s PAN</td>
                    <td class="no-border-bottom no-border-top no-border-left text-bold" style="padding: 5px;" colspan="12">: <?=isset($user_pan) ? $user_pan : '-';?></td>
                </tr>

                <!-- <tr>
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
                </tr> -->
            </table>
            <table border="1" style="width: 100%;">
                <tr>
                    <td  style="padding: 3px;border-bottom: 0px;border-top: 0px;" colspan="2">Declaration</td>
                </tr>
                <tr>
                    <td style="padding: 3px;border-bottom: 0px;border-top: 0px;" colspan="2">Above all particulars are true & correct.T&C:</td>
                </tr>
                <tr>
                    <td style="padding: 3px;border-bottom: 0px;border-top: 0px;" colspan="2">1) All payment should be made as per agreed terms.</td>
                </tr>
                <tr>
                    <td style="padding: 3px;border-bottom: 0px;border-top: 0px;" colspan="2">2) Interest to be charged @24% after due date</td>
                </tr>
                <tr>
                    <td style="padding: 3px;border-bottom: 0px;border-top: 0px;" colspan="2">3) No allowance for short for short or measure unless <br/>notice of same given on the receipt of goods.</td>
                </tr>
                <tr>
                    <td style="width: 50%;padding: 3px;border-top: 0px;border-right: 0px;border-bottom: 0px;">4) Our Risk and Responsibility ceases on dispatch of the goods from godown.</td>
                    <td style="width: 50%;padding: 3px;border-top: 0px;border-left: 0px;border-bottom: 0px;">Company’s Second Bank Details :<br/>Bank Name:<?=isset($bank_name) ? $bank_name : '' ?> - <?=isset($bank_ac_no) ? $bank_ac_no : '' ?><br/>A/c No.:<?=isset($bank_ac_no) ? $bank_ac_no : '' ?></td>
                </tr>
                <tr>
                    <td style="width: 50%;padding: 3px;border-top: 0px;border-right: 0px;">5) Goods once sold cannot be return back under any</td>
                    <td style="width: 50%;padding: 3px;border-top: 0px;border-left: 0px;">Branch & IFS Code:UPLETA & HDFC0002644</td>
                </tr>
            </table>
            <table style="width: 100%;" border="1">
                <tr>
                    <td style="width:50%;padding: 5px;padding-left:0px;">
                        <table border="1" style="width: 100%;">
                            <tr>
                                <td colspan="2" style="border:0px;">
                                    Goods Recevied on Behalf of NRUSINH CONSTRUCTION
                                </td>
                            </tr>    
                            <tr><td colspan="2" style="border:0px;">&nbsp;</td></tr>
                            <tr><td style="border:0px;">&nbsp;</td><td style="font-weight: bold;border:0px;">Sign/Thumb</td></tr>
                            <tr>
                                <td style="border:0px;">Place : RAMNAGAR</td>
                                <td style="border:0px;">Date</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border:0px;">Checked By :</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border:0px;">Prepared By :</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:50%;">
                        <table border="1" style="width: 100%;">
                            <tr>
                                <td style="text-align: right; font-size: 13px;border:0px;">
                                    For KAMDHENU PIPE
                                </td>
                            </tr>
                            <tr><td style="border:0px;">&nbsp;</td></tr>
                            <tr><td style="border:0px;">&nbsp;</td></tr>
                            <tr><td style="border:0px;">&nbsp;</td></tr>
                            <tr><td style="border:0px;">&nbsp;</td></tr>
                            <tr><td style="text-align: right;border:0px;">Authorised Signatory</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="no-border text-center" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="no-border text-center" colspan="2">SUBJECT TO UPLETA JURISDICTION</td>
                </tr>
                <tr>
                    <td class="no-border text-center" colspan="2">This is a Computer Generated Invoice</td>
                </tr>
            </table>
        </div>
    </body>
</html>
