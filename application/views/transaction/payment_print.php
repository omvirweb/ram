<!DOCTYPE html>
<html>
    <head>
        <title>Cash Payment</title>
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
            .table-border{
                border-right: 1px solid #000 !important;
                border-left: 1px solid #000 !important; 
            }
            .text-center {
                text-align: center !important;
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
            td {
                font-family: "Times New Roman", Times, serif;
            }
        </style>
    </head>

    <?php
    // echo $transaction_row->invoice_no;
    $invoiceId = json_decode(str_replace('"', '', $transaction_row->invoice_no), true);

            // echo base_url().'assets/uploads/payment/'.$transaction_row->document; die;
    /* echo "<pre>";
        print_r($transaction_row);
        echo "</pre>";
        die; */
    
    ?>
    <body class="">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td class="text-center" style="padding: 5px;border:1px solid black;">
                        <h2 style="font-weight: bold; font-size: 18px;font-family: 'Times New Roman', Times, serif;"><strong><?=$company_row->user_name;?></strong></h2>
                        <p><?=$company_row->address;?></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;border: solid 1px black;border-top:0px;">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="33.33%" >Check No / FTO : <span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;"> <?=$transaction_row->receipt_no;?></span> </td>
                                    <td width="33.33%" class="text-center"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;">Bill No : <?php echo implode(',',$invoiceId); ?></span></td>
                                    <td width="33.33%" class="text-right">Received Date : <span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;"><?=date('d-m-Y',strtotime($transaction_row->transaction_date));?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;border-bottom: 0px;border-left: 1px solid black;border-right: 1px solid black;">
                        Payment Received From &nbsp; <span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;"> <?=$transaction_row->account_name;?></span>
                    </td>
                </tr>
                <tr>
                    <td style="border-left: solid 1px black;border-right:1px solid  black;padding:0px;">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="padding: 5px;border-top: solid 1px black; width:5%"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;">No</span></td>
                                    <td style="padding: 5px;border-top: solid 1px black;border-left: solid 1px black;width:45%"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;">Particular</span></td>
                                    <td class="text-right" style="padding: 5px;border-top: solid 1px black;border-left: solid 1px black;width:15%"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;">GST Amount</span></td>
                                    <td class="text-right" style="padding: 5px;border-top: solid 1px black;border-left: solid 1px black;width:15%"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;">Bill Amount</span></td>
                                    <td class="text-right" style="padding: 5px;border-top: solid 1px black;border-left: solid 1px black;width:20%"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;">Received Amount</span></td>
                                </tr>
                                <?php 
                                $totalRecive = $totalDue = 0;
                                for($i=0;$i<count($invoiceId);$i++){
                                    
                                    // $this->db->select('*');
                                    $this->db->select('sales_invoice_id,aspergem_service_charge,sales_subject');
                                    $this->db->from('sales_invoice');
                                    $this->db->where('sales_invoice_no', $invoiceId[$i]);
                                    $query = $this->db->get();
                                    
                                    if ($query->num_rows() > 0) {
                                        $transaction =$query->row();

                                        /* echo "<pre>";
                                        print_r($transaction);
                                        echo "</pre>";
                                        die; */

                                        $totalDueAmount = 0;
                                        $where = array('module' => '2', 'parent_id' => $transaction->sales_invoice_id);
                                        $sales_invoice_lineitems = $this->crud->get_row_by_id('lineitems', $where);
                                        foreach($sales_invoice_lineitems as $sales_invoice_lineitem){
                                            $totalDueAmount += $sales_invoice_lineitem->price*$sales_invoice_lineitem->item_qty;
                                        }
                                        $gstAmount = ($totalDueAmount*0.18);
                                        $service_charge = number_format((float) $transaction->aspergem_service_charge, 2, '.', '');
                                        $totalAmount = $totalDueAmount+$gstAmount+$service_charge;

                                        $totalRecive += $transaction_row->amount;
                                        $totalDue += $totalAmount;
                                        // echo $gstAmount; die;
                                      ?>
                                    <tr>
                                        <td height="125" style="vertical-align: top;padding: 5px;border-top: solid 1px black;"><?php echo $i+1; ?></td>
                                        <td style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><?= $transaction->sales_subject; ?></td>
                                        <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><?=number_format((float) $gstAmount, 2, '.', '');?></td>
                                        <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><?=number_format((float) $totalAmount, 2, '.', '');?></td>
                                        <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><?=number_format((float) $transaction_row->amount, 2, '.', '');?></td>
                                    </tr>
                                <?php }
                                } ?>
                                <tr>
                                    <td colspan="4" style="vertical-align: top;padding: 5px;border-top: solid 1px black;text-align:right;">
                                    Difference amount
                                    </td>
                                    <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><span style="font-weight: bold; font-family: 'Times New Roman', Times, serif;"><?=number_format((float) $totalDue-$totalRecive, 2, '.', '');?></span></td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="4" style="vertical-align: top;padding: 5px;border-top: solid 1px black;">
                                        <strong>Rs. (in words) :</strong> <i><?=$this->applib->getIndianCurrency($transaction_row->amount);?></i>
                                    </td>
                                    <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><strong><?=number_format((float) $transaction_row->amount, 2, '.', '');?></strong></td>
                                </tr> -->
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;border: solid 1px black;">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="175">
                                        Receiver's Signature 
                                    </td>
                                    <td class="text-right">
                                        For, <?=$company_row->user_name;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="50" style="border-bottom: solid 1px black;">
                                    </td>
                                    <td class="text-right" style="vertical-align: bottom;">
                                        <i><small>(Authorised Signatory)</small></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </body>
</html>