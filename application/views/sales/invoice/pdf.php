<?php
ob_start(); 
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sales Invoice Print</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= base_url();?>assets/dist/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?= base_url();?>assets/dist/css/ionicons.min.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="<?= base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css');?>">
		<style>
			td,th{padding:5px}.table thead tr>th.small{background-color:#dff0d8!important}.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9{float:left}.col-md-12{width:100%}.col-md-11{width:91.66666667%}.col-md-10{width:83.33333333%}.col-md-9{width:75%}.col-md-8{width:66.66666667%}.col-md-7{width:58.33333333%}.col-md-6{width:50%}.col-md-5{width:41.66666667%}.col-md-4{width:33.33333333%}.col-md-3{width:25%}.col-md-2{width:16.66666667%}.col-md-1{width:8.33333333%}.col-md-pull-12{right:100%}.col-md-pull-11{right:91.66666667%}.col-md-pull-10{right:83.33333333%}.col-md-pull-9{right:75%}.col-md-pull-8{right:66.66666667%}.col-md-pull-7{right:58.33333333%}.col-md-pull-6{right:50%}.col-md-pull-5{right:41.66666667%}.col-md-pull-4{right:33.33333333%}.col-md-pull-3{right:25%}.col-md-pull-2{right:16.66666667%}.col-md-pull-1{right:8.33333333%}.col-md-pull-0{right:auto}.col-md-push-12{left:100%}.col-md-push-11{left:91.66666667%}.col-md-push-10{left:83.33333333%}.col-md-push-9{left:75%}.col-md-push-8{left:66.66666667%}.col-md-push-7{left:58.33333333%}.col-md-push-6{left:50%}.col-md-push-5{left:41.66666667%}.col-md-push-4{left:33.33333333%}.col-md-push-3{left:25%}.col-md-push-2{left:16.66666667%}.col-md-push-1{left:8.33333333%}.col-md-push-0{left:auto}.col-md-offset-12{margin-left:100%}.col-md-offset-11{margin-left:91.66666667%}.col-md-offset-10{margin-left:83.33333333%}.col-md-offset-9{margin-left:75%}.col-md-offset-8{margin-left:66.66666667%}.col-md-offset-7{margin-left:58.33333333%}.col-md-offset-6{margin-left:50%}.col-md-offset-5{margin-left:41.66666667%}.col-md-offset-4{margin-left:33.33333333%}.col-md-offset-3{margin-left:25%}.col-md-offset-2{margin-left:16.66666667%}.col-md-offset-1{margin-left:8.33333333%}.col-md-offset-0{margin-left:0}
			thead {
				background-color: blue !important;
			}
			.td_bold{
				font-weight:bold;
				text-align: center;
				border-bottom:0.1mm solid #999;
				border-top:0.1mm solid #999;
			}
			.center {text-align: center;}
			.right {text-align: right;}
			body, div, p {
				font-family: \'DejaVu Sans Condensed\';
                                font-size: 11pt;
			}
			@page *{
				margin-top: 0px;
				margin-bottom: 0px;
				margin-left: 0px;
				margin-right: 0px;
			}
			.signature{
				bottom:0;
				height:60px;
				float:right;
				width:18%;
				margin-top: 100px;
				border-top:1px solid black;
				border-style: solid;
				font-size: 8pt;
				text-align: center;
			}
			.p2{
				padding: 2px;
			}
		</style>
	</head>
        <body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper" style="margin-left: 50px !important;">
            <table border="0" cellpadding="10" cellspacing="1" style="font-size: 8pt; width:100%;">
                <thead style="" >
                    <tr>
                        <td class="td_bold" bgcolor="#f0f2ff">Sr.No.</td>
                        <td class="td_bold" bgcolor="#f0f2ff">Item</td>
                        <td class="td_bold" bgcolor="#f0f2ff">HSN / SAC</td>
                        <td class="td_bold" bgcolor="#f0f2ff">Quantity</td>
                        <td class="td_bold" bgcolor="#f0f2ff">Rate / Item</td>

                        <?php if(in_array('discount', $invoice_line_item_fields)) { ?><td class="td_bold" bgcolor="#f0f2ff">Discount</td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                        <td class="td_bold" bgcolor="#f0f2ff">Taxable Value</td>
                        <?php if(in_array('cgst_per', $invoice_line_item_fields)) { ?><td class="td_bold" bgcolor="#f0f2ff">CGST</td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                        <?php if(in_array('sgst_per', $invoice_line_item_fields)) { ?><td class="td_bold" bgcolor="#f0f2ff">SGST</td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                        <?php if(in_array('igst_per', $invoice_line_item_fields)) { ?><td class="td_bold" bgcolor="#f0f2ff">IGST</td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                        <td class="td_bold" bgcolor="#f0f2ff">Total</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $discounted_price_total = 0;
                    $discount_amt_total = 0;
                    $other_charges = 0;
                    $total_qty = 0;
                    $inc = 1; 
                    foreach($lineitems as $lineitem){ 
                    ?>
                        <tr>
                                <td class="center"><?php echo $inc; ?></td>
                                <td class="left"><?php echo isset($lineitem->item_name) ? $lineitem->item_name : $lineitem->line_item_des; ?></td>
                                <td class="center"><?php echo $lineitem->hsn ?  $lineitem->hsn : $lineitem->hsn_code; ?></td>
                                <td class="right"><?php echo $lineitem->item_qty; ?></td>
                                <td class="right"><?php echo $lineitem->price; ?></td>
                                <?php if(in_array('discount', $invoice_line_item_fields)) { ?>
                                <td class="right">
                                    <?php
                                        $pure_amt =  $lineitem->price * $lineitem->item_qty;
                                        if( $lineitem->discount_type == 1 ) { 
                                                $discount_amt = $pure_amt * $lineitem->discount / 100;
                                                echo number_format((float)$discount_amt, 2, '.', '') . '<br /> @' . $lineitem->discount . '%';
                                        }
                                        if( $lineitem->discount_type == 2 ) { 
                                                $discount_amt = $lineitem->discount;
                                                echo number_format((float)$discount_amt, 2, '.', '') . '<br /> @' . $lineitem->discount . ' Rs.';
                                        }
                                    ?>
                                </td>
                                <?php } else { ?><td></td><?php }?>
                                <td class="right"><?php echo $lineitem->discounted_price; ?></td>
                                <?php if(in_array('cgst_per', $invoice_line_item_fields)) { ?><td class="right"><?php echo $lineitem->cgst_amount . '<br /> @' . $lineitem->cgst . '%'; ?></td><?php } else { ?><td></td><?php }?>
                                <?php if(in_array('sgst_per', $invoice_line_item_fields)) { ?><td class="right"><?php echo $lineitem->sgst_amount . '<br /> @' . $lineitem->sgst . '%'; ?></td><?php } else { ?><td></td><?php }?>
                                <?php if(in_array('igst_per', $invoice_line_item_fields)) { ?><td class="right"><?php echo $lineitem->igst_amount . '<br /> @' . $lineitem->igst . '%'; ?></td><?php } else { ?><td></td><?php }?>
                                <td class="right"><?php echo $lineitem->amount; ?></td>
                        </tr>
                        <?php 
                                        $discounted_price_total += $lineitem->discounted_price;
                                        if(in_array('discount', $invoice_line_item_fields)) { $discount_amt_total += $discount_amt; }
                                        $other_charges += $lineitem->other_charges;
                                        $total_qty += $lineitem->item_qty;
                                        $inc++; 
                                } 
                        ?>
                    </tbody>
                    <tr>
                            <td class="td_bold right" bgcolor="#f0f2ff">Total</td>
                            <td class="td_bold" bgcolor="#f0f2ff"></td>
                            <td class="td_bold" bgcolor="#f0f2ff"></td>
                            <td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$total_qty, 2, '.', ''); ?></td>
                            <td class="td_bold" bgcolor="#f0f2ff"></td>
                            <?php if(in_array('discount', $invoice_line_item_fields)) { ?><td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$discount_amt_total, 2, '.', ''); ?></td><?php } else { ?><td class="td_bold"  bgcolor="#f0f2ff"></td><?php }?>
                                <td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$discounted_price_total, 2, '.', ''); ?></td>
                            <?php if(in_array('cgst_per', $invoice_line_item_fields)) { ?><td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$cgst_amount_total, 2, '.', ''); ?></td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                            <?php if(in_array('sgst_per', $invoice_line_item_fields)) { ?><td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$sgst_amount_total, 2, '.', ''); ?></td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                            <?php if(in_array('igst_per', $invoice_line_item_fields)) { ?><td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$igst_amount_total, 2, '.', ''); ?></td><?php } else { ?><td class="td_bold" bgcolor="#f0f2ff"></td><?php }?>
                            <td class="td_bold right" bgcolor="#f0f2ff"><?php echo number_format((float)$amount_total, 2, '.', ''); ?></td>
                    </tr>
                    <tr>
                            <th colspan="9" class="right" style="font-size:9pt;">Taxable Amount</th>
                            <th colspan="2" class="right" style="font-size:9pt;"><?php echo number_format((float)$discounted_price_total, 2, '.', ''); ?></th>
                    </tr>
                    <tr>
                            <th colspan="9" class="right" style="font-size:9pt;">Total Tax</th>
                            <th colspan="2" class="right" style="font-size:9pt;"><?php echo number_format((float)$cgst_amount_total + $sgst_amount_total + $igst_amount_total, 2, '.', ''); ?></th>
                    </tr>
                    <?php if(in_array('other_charges', $invoice_line_item_fields)) { ?>
                    <tr>
                            <th colspan="9" class="right" style="font-size:9pt;">Other Charges</th>
                            <th colspan="2" class="right" style="font-size:9pt;"><?php echo number_format((float)$other_charges, 2, '.', ''); ?></th>
                    </tr>
                    <?php } ?>
                    <tr>
                            <th colspan="9" class="right" style="font-size:9pt;">Invoice Total</th>
                            <th colspan="2" class="right" style="font-size:9pt;"><?php echo number_format((float)$amount_total, 2, '.', ''); ?></th>
                    </tr>
                    <tr>
                            <th colspan="11" class="right" style="font-size:9pt;">Invoice Total In Words : <label style="font-size:9pt;font-weight:bold;"><?php echo $amount_total_word; ?></label></th>
                    </tr>
            </table>
            
            <?php
            if(isset($is_letter_pad)){
                if($is_letter_pad == 1){
            ?>
			<div class="signature" style="">
				For <?=isset($user_name) ? $user_name : '' ?> <br /> <span class="font-weight:bold;">(Signature)</span>
			</div>
			<div style="clear: both;"></div>
			<div class="">
				<table width="100%" style="vertical-align: bottom; color: #000000;">
					<tr>
						<td width="33%" align="left">
							<table cellpadding="0">
								<tr><th align="left" class="p2" style="color:#d43021; font-size: 10pt; font-weight:bold;">Thank you for your business.</th></tr>
							</table>
						</td>
						<td width="33%" align="center"></td>
						<td width="33%" align="right">
							<table cellpadding="0">
								<tr><th align="left" class="p2" style="font-size: 8pt;">Powered by <?php echo $package_name; ?></th></tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<hr style="color:#d43021; padding: 0px; margin: 1px 0px;"/>
			<div style="text-align: center; font-size: 8pt;">
				<?=isset($user_name) ? $user_name.', ' : '' ?><?=isset($user_country) ? $user_country.', ' : '' ?><?=isset($user_state) ? $user_state.' ' : '' ?><?=isset($user_postal_code) ? $user_postal_code.', ' : '' ?> <br />
				<?=isset($user_phone) ? $user_phone.', ' : '' ?><?=isset($email_ids) ? $email_ids.', ' : '' ?>
			</div>
            <?php
                }
            }
            ?>
		</div>
	</body>
</html>
