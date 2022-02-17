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

    <body class="">
        <table style="width: 100%;border:solid 1px black;">
            <tbody>
                <tr>
                    <td class="text-center" style="padding: 5px;">
                        <h2><strong><?=$company_row->user_name;?></strong></h2>
                        <p><?=$company_row->address;?></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;border-top: solid 1px black;">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="33.33%">Vou. No. : <strong> <?=$transaction_row->receipt_no;?></strong> </td>
                                    <td width="33.33%" class="text-center"><strong>Cash Payment</strong></td>
                                    <td width="33.33%" class="text-right">Date : <strong><?=date('d-m-Y',strtotime($transaction_row->transaction_date));?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px;border-top: solid 1px black;">
                        Paid on account of &nbsp; <strong> <?=$transaction_row->account_name;?></strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="padding: 5px;border-top: solid 1px black;"><strong>Particular</strong></td>
                                    <td width="150" class="text-right" style="padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><strong>Amount</strong></td>
                                </tr>
                                <tr>
                                    <td height="125" style="vertical-align: top;padding: 5px;border-top: solid 1px black;"></td>
                                    <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><?=number_format((float) $transaction_row->amount, 2, '.', '');?></td>
                                </tr>
                                <tr>
                                    <td height="40" style="vertical-align: top;padding: 5px;border-top: solid 1px black;">
                                        <strong>Rs. (in words) :</strong> <i><?=$this->applib->getIndianCurrency($transaction_row->amount);?></i>
                                    </td>
                                    <td class="text-right" style="vertical-align: top;padding: 5px;border-top: solid 1px black;border-left: solid 1px black;"><strong><?=number_format((float) $transaction_row->amount, 2, '.', '');?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;border-top: solid 1px black;">
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
