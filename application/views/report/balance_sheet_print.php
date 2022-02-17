<!DOCTYPE html>
<html>
    <head>
        <title>Balance Sheet</title>
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
        </style>
    </head>
    <body class="">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 style="margin: 0;"><strong><?=$company_row->user_name;?> (From <?=date('d-m-Y',strtotime($from_date));?>)</strong></h2>
                <p style="margin: 0;"><?=$company_row->address;?></p>
            </div>
            <br/>
            <div class="col-md-12">
                <h4 style="margin: 0;"><strong>Balance Sheet</strong></h4>
                <h5  style="margin: 0;" class="balance_sheet_date_range"><strong><?php echo $date_range ?></strong></h5>
            </div>
            <div class="col-md-12">
                <table class="" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-right border-top border-bottom" style="width: 90px;">Liability</th>
                            <th class="text-left border-top border-bottom" style="width: 265px;padding-left: 15px;">Particulars</th>
                            <th class="text-right border-top border-bottom" style="width: 90px;">Asset</th>
                            <th class="text-left border-top border-bottom" style="padding-left: 15px;">Particulars</th>
                        </tr>
                    </thead>
                    <tbody class="balance_sheet_tbody">
                        <?php
                            if(!empty($cr_account_arr)) {
                                foreach ($cr_account_arr as $key => $value) {
                                    ?>
                                    <tr>
                                        <?php if(isset($cr_account_arr[$key]['is_group_total'])) { ?>
                                            <td class="text-right border-top"><strong><?=$cr_account_arr[$key]['amount']?></strong></td>
                                        <?php } else { ?>
                                            <td class="text-right"><?=$cr_account_arr[$key]['amount']?></td>
                                        <?php } ?>
                                        <td style="padding-left: 15px;"><?=$cr_account_arr[$key]['particular']?></td>

                                        <?php if(isset($dr_account_arr[$key]['is_group_total'])) { ?>
                                            <td class="text-right border-top"><strong><?=$dr_account_arr[$key]['amount']?></strong></td>
                                        <?php } else { ?>
                                            <td class="text-right"><?=$dr_account_arr[$key]['amount']?></td>
                                        <?php } ?>
                                        <td style="padding-left: 15px;"><?=$dr_account_arr[$key]['particular']?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th  class="total_net_amount text-right border-top border-bottom"><?=$total_net_amount?></th>
                            <th class=" border-top border-bottom"></th>
                            <th  class="total_net_amount text-right border-top border-bottom"><?=$total_net_amount?></th>
                            <th class=" border-top border-bottom"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>
