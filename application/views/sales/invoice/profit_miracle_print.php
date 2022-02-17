<?php
ob_start();
    $li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
    $li_igst_per = isset($this->session->userdata()['li_igst_per']) ? $this->session->userdata()['li_igst_per'] : '0';
    $li_igst_amt = isset($this->session->userdata()['li_igst_amt']) ? $this->session->userdata()['li_igst_amt'] : '0';
    $li_cgst_per = isset($this->session->userdata()['li_cgst_per']) ? $this->session->userdata()['li_cgst_per'] : '0';
    $li_cgst_amt = isset($this->session->userdata()['li_cgst_amt']) ? $this->session->userdata()['li_cgst_amt'] : '0';
    $li_sgst_per = isset($this->session->userdata()['li_sgst_per']) ? $this->session->userdata()['li_sgst_per'] : '0';
    $li_sgst_amt = isset($this->session->userdata()['li_sgst_amt']) ? $this->session->userdata()['li_sgst_amt'] : '0';
    $li_amount = isset($this->session->userdata()['li_amount']) ? $this->session->userdata()['li_amount'] : '0';
    $li_other_charges = isset($this->session->userdata()['li_other_charges']) ? $this->session->userdata()['li_other_charges'] : '0';
//    echo "<pre>"; print_r($this->session->userdata()); exit;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Format 2 Sales Invoice Print</title>
        <style>
            @media print {
                table{
                    border-spacing: 0;
                }
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
                .no-margin{
                    margin: 0;
                }
            }
            table{
                border-spacing: 0;
            }
            table tr td {
                padding-left: 5px;
                padding-right: 5px;
            }
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
                font-size: 12px;
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
            }
            .border-bottom {
                border-bottom: 1px solid black;
            }
            .border-top {
                border-top: 1px solid black;
            }
            .border1{
                border: 1px solid #000;
            }
            #rcorners2 {
                border-radius: 8px;
                border: 1px solid #000;
                padding: 9px; 
                width: 46.5%;
                height: 30px; 
            }
            #rcorners22 {
                border-radius: 8px;
                border: 1px solid #000;
                padding: 10px; 
                width: 46.5%;
                height: 30px; 
            }
            #add {
                border-radius: 8px;
                border: 1px solid #000;
                padding: 5px; 
                width: 48%;
                height: 100px; 
            }
            #footer {
                border-radius: 8px;
                border: 1px solid #000;
                /*padding: 10px;*/ 
                width: 100%;
                margin-top:5px;
            }
            #descrip{
                border-radius: 8px;
                border: 1px solid #000;
                /*padding: 5px;*/ 
                width: 100%;
                height: 100px; 
                margin-top:5px;
                
            }
            .table-border{
                border-right: 1px solid #000 !important;
                border-left: 1px solid #000 !important; 
            }
            .text-center {
                    text-align: center !important;
                }
            #tax{
                border-radius: 8px;
                border: 1px solid #000;
                /*padding: 5px;*/ 
                width: 10%;
                height: 120px;
                margin-top:5px;
            }
            #total{
                border-radius: 8px;
                border: 1px solid #000;
                padding: 2px; 
                width: 100%;
                height: 10px;
            }
            #tax_amt{
                border-radius: 8px;
                border: 1px solid #000;
                padding: 0px; 
                width: 100%;
                border-collapse: all;
            }
            .text_bold{
                font-weight: bold;
            }
            td {
                font-size: 12px;
            }
            th {
                font-size: 12px;
            }
        </style>
            
	</head>
	<body>
        <div id="descrip">
        <table width="100%">
            <thead>
            <tr>
                <th align="center" class="border-right border-bottom">Sr.</th>
                <th align="center" class="border-right border-bottom">Descriptions of Goods</th>
                <th align="center" class="border-right border-bottom">HSN/SAC</th>
                <th align="center" class="border-right border-bottom">Qty</th>
                <th align="center" class="border-right border-bottom">UOM</th>
                <th align="center" class="border-right border-bottom">Rate</th>
                <?php if($li_discount == '1'){ ?>
                <th align="center" class="border-right border-bottom">Disc. Amt</th>
                <?php } ?>
                <?php if($li_amount == '1'){ ?>
                <th align="center" class="border-bottom">Amount</th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
                <?php 
                    $inc = 1;
                    $discounted_price_total = 0;
                    $other_charges = 0;
                    $total_qty = 0;
                    $row_count = count($lineitems);
                    foreach($lineitems as $lineitem){ 
                ?>
                    <tr>
                        <td valign="top" class="text-left border-right"><?php echo $inc; ?></td>
                        <td valign="top" class="text-left border-right"><?php echo $lineitem->item_name;?><br><?php echo $lineitem->note;?></td>
                        <td valign="top" class="text-left border-right"><?php echo $lineitem->hsn_code; ?></td>
                        <td valign="top" class="text-right border-right"><?php echo $lineitem->item_qty; ?></td>
                        <td valign="top" class="text-left border-right"><?php echo $lineitem->rate_unit; ?></td>
                        <td valign="top" class="text-right border-right"><?php echo $lineitem->price; ?></td>
                        <?php
                                $pure_amt =  $lineitem->price * $lineitem->item_qty;
								if( $lineitem->discount_type == 1 ) { 
									$discount_amt = $pure_amt * $lineitem->discount / 100;
								}
								if( $lineitem->discount_type == 2 ) { 
									$discount_amt = $lineitem->discount;;
								}
							?>
                        <?php if($li_discount == '1'){ ?>
                        <td valign="top" class="text-right border-right"><?php echo number_format((float)$discount_amt, 2, '.', ''); ?></td>
                        <?php } ?>
                        <?php if($li_amount == '1'){ ?>
                        <td valign="top" class="text-right"><?php echo number_format((float)$lineitem->discounted_price, 2, '.', ''); ?></td>
                        <?php } ?>
                    </tr>
                <?php 
                    $discounted_price_total += $lineitem->discounted_price;
                    $other_charges += $lineitem->other_charges;
                    $total_qty += $lineitem->item_qty;
                    $inc++; 
                    }
                ?>
                <tr>
                    <td class="text-left border-right">&nbsp;</td>
                    <td class="text-left border-right">&nbsp;</td>
                    <td class="text-left border-right">&nbsp;</td>
                    <td class="text-right border-right">&nbsp;</td>
                    <td class="text-left border-right">&nbsp;</td>
                    <td class="text-left border-right">&nbsp;</td>
                    <?php if($li_discount == '1'){ ?>
                    <td class="text-right border-right">&nbsp;</td>
                    <?php } ?>
                    <?php if($li_amount == '1'){ ?>
                    <td class="text-right border-top"><?php echo number_format((float)$discounted_price_total, 2, '.', ''); ?></td>
                    <?php } ?>
                </tr>
                <?php
                    $tax_line_inc = count($tax_line_items);
                    $row_inc = 18- $tax_line_inc - $row_count;
                    for($i = 1; $i <= $row_inc; $i++){
                ?>
                    <tr>
                        <td class="text-left border-right">&nbsp;</td>
                        <td class="text-right border-right"><b>&nbsp;</b></td>
                        <td class="text-left border-right">&nbsp;</td>
                        <td class="text-right border-right">&nbsp;</td>
                        <td class="text-left border-right">&nbsp;</td>
                        <td class="text-right border-right">&nbsp;</td>
                        <?php if($li_discount == '1'){ ?>
                        <td class="text-right border-right">&nbsp;</td>
                        <?php } ?>
                        <?php if($li_amount == '1'){ ?>
                        <td class="text-right"><b>&nbsp;</b></td>
                        <?php } ?>
                    </tr>
            <?php } ?>
            <?php if($li_cgst_amt == '1' || $li_cgst_per == '1'){ ?>
                <?php if($cgst_amount_total > 0){ ?>
                    <tr>
                        <td class="text-left border-right">&nbsp;</td>
                        <td class="text-right border-right"><b>Central Tax</b></td>
                        <td class="text-left border-right"></td>
                        <td class="text-right border-right"></td>
                        <td class="text-left border-right"></td>
                        <td class="text-right border-right"></td>
                        <?php if($li_discount == '1'){ ?>
                        <td class="text-right border-right"></td>
                        <?php } ?>
                        <?php if($li_amount == '1'){ ?>
                        <td class="text-right"><b><?php echo number_format((float)$cgst_amount_total, 2, '.', ''); ?></b></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } ?>
                <?php if($sgst_amount_total > 0){ ?>
                    <tr>
                        <td class="text-left border-right">&nbsp;</td>
                        <td class="text-right border-right"><b>State / UT Tax</b></td>
                        <td class="text-left border-right"></td>
                        <td class="text-right border-right"></td>
                        <td class="text-left border-right"></td>
                        <td class="text-right border-right"></td>
                        <?php if($li_discount == '1'){ ?>
                        <td class="text-right border-right"></td>
                        <?php } ?>
                        <?php if($li_amount == '1'){ ?>
                        <td class="text-right"><b><?php echo number_format((float)$sgst_amount_total, 2, '.', ''); ?></b></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                <?php if($li_igst_amt == '1'){ ?>
                    <?php if($igst_amount_total > 0){ ?>
                        <tr>
                            <td class="text-left border-right">&nbsp;</td>
                            <td class="text-right border-right"><b>IGST</b></td>
                            <td class="text-left border-right"></td>
                            <td class="text-right border-right"></td>
                            <td class="text-left border-right"></td>
                            <td class="text-right border-right"></td>
                            <?php if($li_discount == '1'){ ?>
                                <td class="text-right border-right"></td>
                            <?php } ?>
                            <?php if($li_amount == '1'){ ?>
                            <td class="text-right"><b><?php echo number_format((float)$igst_amount_total, 2, '.', ''); ?></b></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <?php if($li_other_charges == '1'){ ?>
                    <tr>
                        <td class="text-left border-right">&nbsp;</td>
                        <td class="text-right border-right"><b>Other Charges</b></td>
                        <td class="text-left border-right"></td>
                        <td class="text-right border-right"></td>
                        <td class="text-left border-right"></td>
                        <td class="text-right border-right"></td>
                        <?php if($li_discount == '1'){ ?>
                            <td class="text-right border-right"></td>
                        <?php } ?>
                        <?php if($li_amount == '1'){ ?>
                            <td class="text-right"><b><?php echo number_format((float)$other_charges, 2, '.', ''); ?></b></td>
                        <?php } ?>
                    </tr> 
                <?php } ?>
                    <?php $pure_amt = $discounted_price_total + $cgst_amount_total + $sgst_amount_total + $igst_amount_total + $other_charges;?>
                    <?php $pure_amtt = number_format((float)$pure_amt, 2, '.', '');?>
                    <?php $pure_amt1 = $pure_amt - $pure_amtt;?>
                    <?php $pure_amt11 = number_format($pure_amt1, 2);?>
                    <?php 
                        if(!empty($round_off)){
                            $amount_total_r = number_format((float)$pure_amt, 0, '.', '');
                            $pure_amt_t = number_format((float)$amount_total_r, 2, '.', '');
                        } else {
                            $pure_amt_t = number_format((float)round($pure_amt), 2, '.', '');
                        }
                    ?>
                <tr>
                    <th align="center" class="border-right border-top">&nbsp;</th>
                    <th align="" class="border-right border-top"><b>Total</b></th>
                    <th align="center" class="border-right border-top">&nbsp;</th>
                    <th align="" class="border-right border-top text-right"><?php echo number_format((float)$total_qty, 2, '.', ''); ?></th>
                    <th align="center" class="border-right border-top">&nbsp;</th>
                    <th align="center" class="border-right border-top">&nbsp;</th>
                    <?php if($li_discount == '1'){ ?>
                    <th align="center" class="border-right border-top">&nbsp;</th>
                    <?php } ?>
                    <?php if($li_amount == '1'){ ?>
                    <th align="" class="border-top text-right"><?php echo $pure_amt_t; ?></th>
                    <?php } ?>
                </tr>
            </tbody>
            </table>
        </div>
        
        
        <div style="width: 100%; margin-top:5px;" id="total">
            <table>
                <tbody>
                    <tr>
                        <td>Amount Chargable In Words : <br/><b> <?php echo $amount_total_word." Only."; ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="width: 100%; margin-top:5px;" id="tax_amt">
            <table border="0">
                <thead>
                <tr>
                    <th width="170px" colspan="3" rowspan="2" class="text_bold text-center border-bottom border-right" style="vertical-align: middle;">HSN/SAC</th>
                    <th width="100px" colspan="3" rowspan="2" class="text_bold text-center border-bottom border-right" style="vertical-align: middle;">Taxable Value</th>
                    <?php if($li_cgst_amt == '1' || $li_cgst_per == '1'){ ?>
                        <th valign="top" width="105px" colspan="3" align="center" class="text_bold border-bottom border-right">Central Tax</th>
                    <?php } ?>
                    <?php if($li_sgst_amt == '1' || $li_sgst_per == '1'){ ?>
                        <th valign="top" width="105px" colspan="3" align="center" class="text_bold border-bottom border-right">State Tax</th>
                    <?php } ?>
                    <?php if($li_igst_amt == '1' || $li_igst_per == '1'){ ?>
                        <th valign="top" width="105px" colspan="3" align="center" class="text_bold border-bottom <?php echo $li_cgst_amt == 0 || $li_cgst_per == 0 || $li_sgst_amt == 0 || $li_sgst_per == 0 || $li_igst_amt == 0 || $li_igst_per == 0 ? 'border-right' : ''; ?>">IGST</th>
                    <?php } ?>
                </tr>
                <tr>
                    <?php if($li_cgst_amt == 0 && $li_cgst_per == 0 && $li_sgst_amt == 0 && $li_sgst_per == 0 && $li_igst_amt == 0 && $li_igst_per == 0) { ?>
                        <th colspan="1" ></th>
                    <?php } else { ?> 
                        <?php if($li_cgst_per == '0' && $li_cgst_amt == '0'){ ?>
                            <!--<th colspan="1" ></th>-->
                        <?php } else { ?>
                            <?php if($li_cgst_per == '1'){ ?>
                                <th colspan="1" align="center" class="text_bold border-bottom <?php echo $li_cgst_amt != '0' ? 'border-right' : ''; ?>">Rate</th>
                            <?php } else if(($li_cgst_per == '0' || $li_cgst_amt == '0') && $li_cgst_per == '0') { ?>
                                <th colspan="1" class="border-bottom"></th>
                            <?php } ?>
                            <?php if($li_cgst_amt == '1'){ ?>
                                <th colspan="2" align="center" class="text_bold border-bottom border-right">Amount</th>
                            <?php } else if(($li_cgst_per == '0' || $li_cgst_amt == '0') && $li_cgst_amt == '0') { ?>
                                <th colspan="2" class="border-bottom border-right" ></th>
                            <?php } ?>
                        <?php } ?>
                        <?php if($li_sgst_per == '0' && $li_sgst_amt == '0'){ ?>
                            <!--<th colspan="1" ></th>-->
                        <?php } else { ?>
                            <?php if($li_sgst_per == '1'){ ?>
                                <th colspan="1" align="center" class="text_bold border-bottom <?php echo $li_sgst_amt != '0' ? 'border-right' : ''; ?>">Rate</th>
                            <?php } else if(($li_sgst_per == '0' || $li_sgst_amt == '0') && $li_sgst_per == '0') { ?>
                                <th colspan="1" class="border-bottom"></th>
                            <?php } ?>
                            <?php if($li_sgst_amt == '1'){ ?>
                                <th colspan="2" align="center" class="text_bold border-bottom border-right">Amount</th>
                            <?php } else if(($li_sgst_per == '0' || $li_sgst_amt == '0') && $li_sgst_amt == '0') { ?>
                                <th colspan="2" class="border-bottom border-right"></th>
                            <?php } ?>
                        <?php } ?>
                        <?php if($li_igst_per == '0' && $li_igst_amt == '0'){ ?>
                            <!--<th colspan="1" ></th>-->
                        <?php } else { ?>
                            <?php if($li_igst_per == '1'){ ?>
                                <th colspan="1" align="center" class="text_bold border-bottom <?php echo $li_igst_amt != '0' ? 'border-right' : ''; ?>">Rate</th>
                            <?php } else if(($li_igst_per == '0' || $li_igst_amt == '0') && $li_igst_per == '0') { ?>
                                <th colspan="1" class="border-bottom"></th>
                            <?php } ?>
                            <?php if($li_igst_amt == '1'){ ?>
                                <th colspan="2" align="center" class="text_bold border-bottom <?php echo $li_cgst_amt == 0 || $li_cgst_per == 0 || $li_sgst_amt == 0 || $li_sgst_per == 0 || $li_igst_amt == 0 || $li_igst_per == 0 ? 'border-right' : ''; ?>">Amount</th>
                            <?php } else if(($li_igst_per == '0' || $li_igst_amt == '0') && $li_igst_amt == '0') { ?>
                                <th colspan="2" class="border-bottom border-right"></th>
                            <?php } ?>
                        <?php } ?>
                        <?php if($li_cgst_amt == 0 || $li_cgst_per == 0 || $li_sgst_amt == 0 || $li_sgst_per == 0 || $li_igst_amt == 0 || $li_igst_per == 0) { ?>
                            <th colspan="1" ></th>
                        <?php } ?>
                    <?php } ?>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $total_taxable_value = 0;
                    $total_cgst_value = 0;
                    $total_sgst_value = 0;
                    $total_igst_value = 0;
                    foreach($tax_line_items as $line_items){ 
                ?>
                    <tr>
                        <td class="text-center border-right" colspan="3"><?php echo $line_items['hsn_code']; ?></td>
                        <td class="text-right border-right" colspan="3"><?php echo number_format((float)$line_items['taxable_value'], 2, '.', ''); ?></td>
                        <?php if($li_cgst_amt == 0 && $li_cgst_per == 0 && $li_sgst_amt == 0 && $li_sgst_per == 0 && $li_igst_amt == 0 && $li_igst_per == 0) { ?>
                            <td colspan="1" ></td>
                        <?php } else { ?>
                            <?php if($li_cgst_per == '0' && $li_cgst_amt == '0'){ } else { ?>
                                <?php if($li_cgst_per == '1'){ ?>
                                    <td class="text-right <?php echo $li_cgst_amt != '0' ? 'border-right' : ''; ?>" colspan="1"><?php echo $line_items['cgst_rate']; ?></td>
                                <?php } else if(($li_cgst_per == '0' || $li_cgst_amt == '0') && $li_cgst_per == '0') { ?>
                                    <td colspan="1"></td>
                                <?php } ?>
                                <?php if($li_cgst_amt == '1'){ ?>
                                    <td class="text-right border-right" colspan="2"><?php echo number_format((float)$line_items['cgst'], 2, '.', ''); ?></td>
                                <?php } else if(($li_cgst_per == '0' || $li_cgst_amt == '0') && $li_cgst_amt == '0') { ?>
                                    <td colspan="2" class="border-right"></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($li_sgst_per == '0' && $li_sgst_amt == '0'){  } else { ?>
                                <?php if($li_sgst_per == '1'){ ?>
                                    <td class="text-right <?php echo $li_sgst_amt != '0' ? 'border-right' : ''; ?>" colspan="1"><?php echo $line_items['sgst_rate']; ?></td>
                                <?php } else if(($li_sgst_per == '0' || $li_sgst_amt == '0') && $li_sgst_per == '0') { ?>
                                    <td colspan="1"></td>
                                <?php } ?>
                                <?php if($li_sgst_amt == '1'){ ?>
                                    <td class="text-right border-right" colspan="2"><?php echo number_format((float)$line_items['sgst'], 2, '.', ''); ?></td>
                                <?php } else if(($li_sgst_per == '0' || $li_sgst_amt == '0') && $li_sgst_amt == '0') { ?>
                                    <td colspan="2" class="border-right"></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if($li_igst_per == '0' && $li_igst_amt == '0'){ } else { ?>
                                <?php if($li_igst_per == '1'){ ?>
                                    <td class="text-right <?php echo $li_igst_amt != '0' ? 'border-right' : ''; ?>" colspan="1"><?php echo $line_items['igst_rate']; ?></td>
                                <?php } else if(($li_igst_per == '0' || $li_igst_amt == '0') && $li_igst_per == '0') { ?>
                                    <td colspan="1"></td>
                                <?php } ?>
                                <?php if($li_igst_amt == '1'){ ?>
                                    <td class="text-right <?php echo $li_cgst_amt == 0 || $li_cgst_per == 0 || $li_sgst_amt == 0 || $li_sgst_per == 0 || $li_igst_amt == 0 || $li_igst_per == 0 ? 'border-right' : ''; ?>" colspan="2"><?php echo number_format((float)$line_items['igst'], 2, '.', ''); ?></td>
                                <?php } else if(($li_igst_per == '0' || $li_igst_amt == '0') && $li_igst_amt == '0') { ?>
                                    <td colspan="2" class="border-right"></td>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                   </tr>
                <?php 
                    $total_taxable_value += $line_items['taxable_value'];
                    $total_cgst_value += $line_items['cgst'];
                    $total_sgst_value += $line_items['sgst'];
                    $total_igst_value += $line_items['igst'];
                    }
                ?>
                 <tr>
                    <td class="text-right border-right border-top" colspan="3">&nbsp;</td>
                    <td class="text_bold text-right border-right border-top" colspan="3"><?php echo number_format((float)$total_taxable_value, 2, '.', ''); ?></td>
                    <?php if($li_cgst_amt == 0 && $li_cgst_per == 0 && $li_sgst_amt == 0 && $li_sgst_per == 0 && $li_igst_amt == 0 && $li_igst_per == 0) { ?>
                        <td colspan="1" ></td>
                    <?php } else { ?>
                        <?php if($li_cgst_per == '0' && $li_cgst_amt == '0'){ } else { ?>
                            <?php if($li_cgst_per == '1'){ ?>
                                <td class="text-right <?php echo $li_cgst_amt != '0' ? 'border-right' : ''; ?> border-top" colspan="1">&nbsp;</td>
                            <?php } else if(($li_cgst_per == '0' || $li_cgst_amt == '0') && $li_cgst_per == '0') { ?>
                                <td colspan="1" class="border-top"></td>
                            <?php } ?>
                            <?php if($li_cgst_amt == '1'){ ?>
                                <td class="text_bold text-right border-right border-top" colspan="2"><?php echo number_format((float)$total_cgst_value, 2, '.', ''); ?></td>
                            <?php } else if(($li_cgst_per == '0' || $li_cgst_amt == '0') && $li_cgst_amt == '0') { ?>
                                <td colspan="2" class="border-top border-right"></td>
                            <?php } ?>
                        <?php } ?>
                        <?php if($li_sgst_per == '0' && $li_sgst_amt == '0'){ } else { ?>
                            <?php if($li_sgst_per == '1'){ ?>
                                <td class="text-right <?php echo $li_sgst_amt != '0' ? 'border-right' : ''; ?> border-top" colspan="1">&nbsp;</td>
                            <?php } else if(($li_sgst_per == '0' || $li_sgst_amt == '0') && $li_sgst_per == '0') { ?>
                                <td colspan="1" class="border-top"></td>
                            <?php } ?>
                            <?php if($li_sgst_amt == '1'){ ?>
                                <td class="text_bold text-right border-right border-top" colspan="2"><?php echo number_format((float)$total_sgst_value, 2, '.', ''); ?></td>
                            <?php } else if(($li_sgst_per == '0' || $li_sgst_amt == '0') && $li_sgst_amt == '0') { ?>
                                <td colspan="2" class="border-top border-right"></td>
                            <?php } ?>
                        <?php } ?>
                        <?php if($li_igst_per == '0' && $li_igst_amt == '0'){ } else { ?>
                            <?php if($li_igst_per == '1'){ ?>
                                <td class="text-right <?php echo $li_igst_amt != '0' ? 'border-right' : ''; ?> border-top" colspan="1">&nbsp;</td>
                            <?php } else if(($li_igst_per == '0' || $li_igst_amt == '0') && $li_igst_per == '0') { ?>
                                <td colspan="1" class="border-top"></td>
                            <?php } ?>
                            <?php if($li_igst_amt == '1'){ ?>
                                <td class="text_bold text-right border-top <?php echo $li_cgst_amt == 0 || $li_cgst_per == 0 || $li_sgst_amt == 0 || $li_sgst_per == 0 || $li_igst_amt == 0 || $li_igst_per == 0 ? 'border-right' : ''; ?>" colspan="2"><?php echo number_format((float)$total_igst_value, 2, '.', ''); ?></td>
                            <?php } else if(($li_igst_per == '0' || $li_igst_amt == '0') && $li_igst_amt == '0') { ?>
                                <td colspan="2" class="border-top border-right"></td>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tr>
                </tbody>
            </table>
        </div>
            <div style="width: 100%; margin-top:5px;" id="total">
            <table>
                <tbody>
                    <tr>
                        <td>Tax Amount In Words : <b> <?php echo $gst_total_word; ?> Only.</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="footer">
            <table>
                <tbody>
                    <tr>
                        <td>Bank Name: <?=isset($bank_name) ? $bank_name : '';?><br>
                            Branch: <?=isset($bank_branch) ? $bank_branch : '';?> &nbsp; &nbsp; &nbsp; &nbsp;
                            City: <?=isset($bank_city) ? $bank_city : '';?><br>
                            Account Name: <?=isset($bank_acc_name) ? $bank_acc_name : '';?><br>
                            A/C No. : <?=isset($bank_ac_no) ? $bank_ac_no : '';?> &nbsp; &nbsp; &nbsp; &nbsp;  IFSC : <?=isset($rtgs_ifsc_code) ? $rtgs_ifsc_code : '';?></td>  
                    </tr>
                </tbody>
            </table>
        </div>
        <table width="100%">
            <tr>
                <td>&nbsp;</td>
                <td class="text-right"><b>For, <?= isset($user_name) ? $user_name : '' ?></b></td>
            </tr>
            <tr>
                <td><br /><br /><br /><br /></td>
                <td class="text-right">&nbsp;</td>
            </tr>
            <tr>
                <td><b>Subject To <?php echo $user_city; ?> Jurisdiction Only   E.&.O.E </b></td>
                <td class="text-right border-top"><b>Authorised Signatory</b></td>
            </tr>
        </table>
    </body>
</html>
