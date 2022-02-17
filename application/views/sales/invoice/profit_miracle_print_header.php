        <table border="0" width="100%">
            <?php if(!empty($letterpad_print)){ ?>
            <tr align="center">
                <td align="center" style="font-size: 14px !important;"><h1><?= isset($user_name) ? $user_name : '' ?></h1>
                    <b>
                    <?=isset($user_address) ? $user_address : '' ?><?=isset($user_postal_code) ? " Pin Code - ".$user_postal_code : '' ?><br/>
                    <?=isset($user_phone) ? "Mo. ".$user_phone : '' ?>
                    <?=isset($email_ids) ? "Email - ".$email_ids : '' ?>
                    </b><br></td>
            </tr>
            <tr><td align="right"><b>GSTIN. : <?=isset($user_gst_no) ? $user_gst_no : '' ?></b></td></tr>
            <?php } else { ?>
                <tr align="center">
                <td align="center" style="font-size: 14px !important;">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <?php }  ?>
        </table>
        <div style="float:left;" id="rcorners2">
            <table width="100%">
                <tbody>
                    <tr>
                        <td class="text-left" style="width: 50%"><b>Invoice Number</b> : <?=isset($sales_invoice_no) ? $sales_invoice_no : '' ?></td>
                        <td rowspan="2" class="text-center"><b>DEBIT</b></td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 100%"><b>Invoice Date &nbsp; &nbsp; &nbsp; </b>: <?=isset($sales_invoice_date) ? $sales_invoice_date : '';?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="float:right;" id="rcorners22">
            <table width="100%">
                <tbody>
                    <tr>
                        <td rowspan="2" class="text-center"><b><br/>
                         <?php
                            $invoice_type = $this->crud->get_id_by_val('invoice_type','invoice_type','invoice_type_id',$sales_invoice_data->invoice_type);
                            echo strtoupper($invoice_type);
                        ?>
                        </b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="float:left;margin-top:5px;" id="add">
            <table width="100%">
                <tbody>
                    <tr>
                        <th class="text-center">Details of Receiver (Buyer Details)</th>
                    </tr>
                    <tr>
                        <td><b>Name &nbsp; &nbsp; &nbsp; &nbsp;: </b><?=isset($account_name) ? $account_name : '&nbsp;' ?> <?=(isset($sales_invoice_desc) && !empty($sales_invoice_desc)) ? ' - '.$sales_invoice_desc : '' ?></td>
                    </tr>
                    <tr>
                        <td><b>Address &nbsp; &nbsp; : </b><?=isset($account_address) ? $account_address : '';?></td>
                    </tr>
                    <tr>
                        <td><b>Phone No. : </b><?=isset($account_phone) ? $account_phone : '';?></td>
                    </tr>
                    <tr>
                        <td class="border-bottom"><b>State. &nbsp; &nbsp; &nbsp; &nbsp;: </b><?=isset($account_state) ? $account_state : '';?><?=isset($account_postal_code) ? " - ".$account_postal_code : '';?></td>
                    </tr>
                    <tr>
                        <td><b>GSTIN / UIN </b>: <?=isset($account_gst_no) ? $account_gst_no : '';?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="float:right; height: 118px;" id="add">
            <table width="100%">
                <tbody>
                    <tr>
                        <th align="center">Details of Consignee (Shipped Details)</th>
                    </tr>
                    <tr>
                        <td><b>Transport Name : </b><?=isset($transport_name) ? $transport_name : '';?></td>
                    </tr>
                    <tr>
                        <td><b>LR No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: </b><?=isset($lr_no) ? $lr_no : '';?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table border="0" width="100%">
            <tr>
                <td></td>
            </tr>
            <tr>
                <td><b>Place Of Supply</b> : <?=isset($account_state) ? $account_state : '';?></td>
            </tr>
        </table>