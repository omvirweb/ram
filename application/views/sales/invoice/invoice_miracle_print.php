<?php
ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Miracle Sales Invoice Print</title>
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
        </style>

    </head>
    <body>

        <table border="1" width="100%">
            <?php if(!empty($letterpad_print)){ ?>
                <tr align="center" class="border1">
                    <td align="center" colspan="2" class="border1"><h2><?= isset($user_name) ? $user_name : '' ?></h2>
                       <?=isset($user_address) ? nl2br($user_address) : '' ?><br/>
                       <?=isset($user_phone) ? "Mo. ".$user_phone : '' ?></td>
                </tr>
            <?php } else { ?>
                <tr align="center" class="border1">
                    <td align="center" colspan="2">
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                    <br/>
                    </td>
                </tr>
            <?php } ?>
            <tr height="100px">
                <th colspan="2">
                    <table width="100%">
                        <tr>
                            <th align="left" width="33.33%">Cash Memo</th>
                            <th align="center" width="33.33%"><?php
                $invoice_type = $this->crud->get_id_by_val('invoice_type','invoice_type','invoice_type_id',$sales_invoice_data->invoice_type);
                echo strtoupper($invoice_type);
                ?></th>
                            <th align="right" width="33.33%">Original</th>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr style="border: 1px solid;">
                <td>Details of Receiver (Billed To) <br> <b style="font-weight:bold">M/s.<?=isset($account_name) ? $account_name : '';?></b><br>
                    Address : <?=isset($account_address) ? nl2br($account_address) : '';?> <br>
                    City : <?=isset($account_city) ? $account_city : '';?> &nbsp; &nbsp;
                    State : <?=isset($account_state) ? $account_state : '';?> &nbsp; &nbsp;
                    <b style="font-weight:bold">GST IN : <?=isset($account_gst_no) ? $account_gst_no : '';?></b></td>
                <td style="padding-bottom: 40px;font-weight:bold;">Bill No.: <?=isset($sales_invoice_no) ? $sales_invoice_no : '' ?> <br>
                    Date: <?=isset($sales_invoice_date) ? $sales_invoice_date : '';?></td>
            </tr>
            <tr>
                <td colspan="2" class="border1">
                    <table border="1" margin="10px">
                        <tr>
                            <th rowspan="2" align="center">Sr.</th>
                            <th rowspan="2" align="center">Product Name</th>
                            <th rowspan="2" align="center">HSN</th>
                            <th rowspan="2" align="center">SHADE</th>
                            <th rowspan="2" align="center">Qty.</th>
                            <th rowspan="2" align="center">Rate</th>
                            <th rowspan="2" align="center">Taxable Value</th>
                            <th colspan="2" align="center">&nbsp;CGST</th>
                            <th colspan="2" align="center">&nbsp;SGST</th>
                            <th rowspan="2" align="center">Net Amount</th>
                        </tr>
                        <tr>
                            <th align="center">Rate</th>
                            <th align="center">Amt.</th>
                            <th align="center">Rate</th>
                            <th align="center">Amt.</th>
                        </tr>
                        <?php 
                            $inc = 1;
                            $discounted_price_total = 0;
                            foreach($lineitems as $lineitem){ 
                        ?>
                            <tr>
                                <td><?php echo $inc; ?></td>
                                <td><?php echo $lineitem->item_name;?></td>
                                <td><?php echo $lineitem->hsn_code; ?></td>
                                <td>&nbsp;</td>
                                <td class="divRight"><?php echo $lineitem->item_qty; ?></td>
                                <td class="divRight"><?php echo $lineitem->price; ?></td>
                                <td class="divRight"><?php echo $lineitem->discounted_price; ?></td>
                                <td class="divRight"><?php echo $lineitem->cgst . '%'; ?></td>
                                <td class="divRight"><?php echo $lineitem->cgst_amount; ?></td>
                                <td class="divRight"><?php echo $lineitem->sgst . '%'; ?></td>
                                <td class="divRight"><?php echo $lineitem->sgst_amount; ?></td>
                                <td class="divRight"><?php echo $lineitem->amount; ?></td>
                            </tr>
                        <?php 
                            $discounted_price_total += $lineitem->discounted_price;
							$inc++; 
                            } 
                        ?>
                            <tr class="border1">
                            <td>&nbsp;</td>
                            <td align="center">Subtotal </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="divRight"><?php echo $qty_total; ?></td>
                            <td class="divRight">&nbsp;</td>
                            <td class="divRight"><?php echo $discounted_price_total; ?></td>
                            <td class="divRight">&nbsp;</td>
                            <td class="divRight"><?php echo $cgst_amount_total; ?></td>
                            <td class="divRight">&nbsp;</td>
                            <td class="divRight"><?php echo $sgst_amount_total; ?></td>
                            <td class="divRight"><?php echo $amount_total; ?></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="border1"> <b style="font-weight:bold">Total Invoice Value(In Words): <br/></b> <?php echo $amount_total_word; ?></td>
                            <td colspan="5" rowspan="2">CGST <p>SGST </p></td>
                        </tr>
                        <tr>
                            <td colspan="7"><b style="font-weight:bold">TIN No.&emsp;&emsp;&nbsp;&nbsp;:</b> <br> </td>
                        </tr>
                        <tr>
                            <td colspan="7"><b style="font-weight:bold">GSTIN No.&emsp;&nbsp;: </b><?=isset($user_gst_no) ? $user_gst_no : '' ?></td>
                            <td colspan="4" class="border1">Total Invoice Value(In figure): </td>
                            <td style="font-weight:bold;text-align: right;" class="border1"><?php echo $amount_total; ?></td>
                        </tr>
                        <tr>
                            <td colspan="7" class="border1"><strong style="font-weight:bold">Terms & Condition :</strong>

                                <p>1. Goods Once Sold Will Not Be Accepted.</p>
                                <p>2. <?php echo ucfirst($user_city); ?> Jurisdiction. E & O.E*</p>
                                <p>3. Test Test Test Test Test</p></td>

                            <th colspan="5" align="center"><strong>For,<?= isset($user_name) ? $user_name : '' ?> <br><br><br><br> (Authorised Signatory)</strong></th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </body>
</html>
