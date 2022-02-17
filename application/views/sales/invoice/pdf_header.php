<div class="wrapper" style="margin-left: 50px !important;">
<?php
            if(isset($letterpad_print)){
                if($letterpad_print == 1){
            ?>
            <table width="100%" cellpadding="0" style="vertical-align: bottom; font-size: 8pt; color: #000000;">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <th align="left" class="p2" colspan="3" style="color:#d43021; font-size: 16px;"><?=isset($user_name) ? $user_name : '' ?></th>
                            </tr>
                            <tr>
                                <td align="left" class="p2" colspan="2"><?=isset($user_address) ? nl2br($user_address) : '' ?></td>
                                <th align="left" class="p2">&nbsp;</th>
                            </tr>
                            <tr>
                                <th align="left" class="p2">GSTIN</th>
                                <td align="left" class="p2"><?=isset($user_gst_no) ? $user_gst_no : '' ?></td>
                                <th align="left" class="p2">&nbsp;</th>
                            </tr>
                            <tr>
                                <th align="left" class="p2">State</th>
                                <td align="left" class="p2"><?=isset($user_state) ? $user_state : '' ?></td>
                                <th align="left" class="p2">&nbsp;</th>
                            </tr>
                            <tr>
                                <th align="left" class="p2">PAN</th>
                                <td align="left" class="p2"><?=isset($user_pan) ? $user_pan : '' ?></td>
                                <th align="left" class="p2">&nbsp;</th>
                            </tr>
                        </table>
                    </td>
                    <td align="right">
                        <table cellpadding="0">
                            <tr>
                                <th align="right" class="p2" colspan="2" style="color:#d43021;"> Total <?=isset($amount_total) ? $amount_total : '' ?></th>
                            </tr>
                            <tr>
                                <th align="left" class="p2">Invoice Date</th>
                                <td align="right" class="p2"><?=isset($sales_invoice_date) ? $sales_invoice_date : '' ?></td>
                            </tr>
                            <tr>
                                <th align="left" class="p2">Invoice No.</th>
                                <td align="right" class="p2"><?=(isset($sales_invoice_no)?$sales_invoice_no:'');?></td>
                            </tr>
                            <tr>
                                <th align="left" class="p2">&nbsp;</th>
                                <td align="right" class="p2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <?php
                } else {
                    echo '<div style="height: 100px;"></div>';
                }
            }
            ?>
            <hr style="color:#d43021; padding: 0px; margin: 10px;"/>
            <h4 align="center" class="box-title" style="color:#d43021; padding: 0px; margin: 0px;">&nbsp;
                <?php
                $invoice_type = $this->crud->get_id_by_val('invoice_type','invoice_type','invoice_type_id',$sales_invoice_data->invoice_type);
                echo strtoupper($invoice_type);
                ?>
            </h4>
            <hr style="color:#d43021; padding: 0px; margin: 10px;"/>

            <div class="">
                    <table width="100%" style="vertical-align: bottom; font-size: 9pt; color: #000000;">
                            <tr>
                                    <td width="33%" align="left">
                                            <table cellpadding="0">
                                                    <tr><th align="left" class="p2" style="font-weight:bold">Customer Name</th></tr>
                                                    <tr><td align="left" class="p2"><b style="font-weight:bold"><?=isset($account_name) ? $account_name : '&nbsp;' ?></b> <?=(isset($sales_invoice_desc) && !empty($sales_invoice_desc)) ? ' - '.$sales_invoice_desc : '' ?></td></tr>
                                                    <tr><th align="left" class="p2" style="font-weight:bold">Customer GSTIN</th></tr>
                                                    <tr><td align="left" class="p2"><?=isset($account_gst_no) ? $account_gst_no : '&nbsp;' ?></td></tr>
                                            </table>
                                    </td>
                                    <td width="33%" align="center">
                                            <table cellpadding="0">
                                                    <tr><th align="left" class="p2" style="font-weight:bold">Billing Address</th></tr>
                                                    <tr><td align="left" class="p2"><?=isset($billing_address) ? $billing_address : '&nbsp;' ?></td></tr>
                                                    <tr><td align="left" class="p2"><?=isset($account_country) ? $account_country.', ' : '' ?><?=isset($account_state) ? $account_state.', ' : '&nbsp;' ?></td></tr>
                                                    <tr><td align="left" class="p2"><?=isset($account_city) ? $account_city.', ' : '' ?><?=isset($account_postal_code) ? $account_postal_code.' ' : '&nbsp;' ?></td></tr>
                                            </table>
                                    </td>
                                    <td width="33%" align="right">
                                            <table cellpadding="0">
                                                    <tr><th align="left" class="p2" style="font-weight:bold">Shipping Address</th></tr>
                                                    <tr><td align="left" class="p2"><?=isset($account_address) ? $account_address : '&nbsp;' ?></td></tr>
                                                    <tr><td align="left" class="p2"><?=isset($account_country) ? $account_country.', ' : '' ?><?=isset($account_state) ? $account_state.', ' : '&nbsp;' ?></td></tr>
                                                    <tr><td align="left" class="p2"><?=isset($account_city) ? $account_city.', ' : '' ?><?=isset($account_postal_code) ? $account_postal_code.' ' : '&nbsp;' ?></td></tr>
                                            </table>
                                    </td>
                            </tr>
                    </table>
            </div>

            <hr style="color:#d43021; padding: 0px; margin: 10px 0px;"/>

            <div class="">
                    <table width="100%" style="vertical-align: bottom; font-size: 9pt; color: #000000;">
                        <tr>
                            <td width="33%" align="left">
                                    <table cellpadding="0">
                                            <tr>
                                                    <th class="p2" style="font-weight:bold">Country of Supply</th>
                                                    <td class="p2"><?=isset($account_country) ? $account_country : '' ?></td>
                                            </tr>
                                    </table>
                            </td>
                            <td width="33%" align="center">
                                    <table cellpadding="0">
                                            <tr>
                                                    <th class="p2" style="font-weight:bold">Place of Supply</th>
                                                    <td class="p2"><?=isset($account_state) ? $account_state : '' ?></td>
                                            </tr>
                                    </table>
                            </td>
                            <td width="33%" align="right">
                                <?php
                                if(isset($is_letter_pad)){
                                    if($is_letter_pad != 1){
                                ?>
                                <table cellpadding="0">
                                    <tr>
                                        <th class="p2" style="font-weight:bold">Invoice No.</th>
                                        <td class="p2"><?=isset($sales_invoice_no) ? $sales_invoice_no : '' ?></td>
                                        <td class="p2">&nbsp;&nbsp;|&nbsp;&nbsp;<?=isset($sales_invoice_date) ? $sales_invoice_date : '' ?></td>
                                    </tr>
                                </table>
                                <?php } } ?>
                            </td>
                        </tr>
                    </table>
            </div>
            <br />
            <div class="">
                    <table width="100%" style="vertical-align: bottom; font-size: 9pt; color: #000000;">
                            <?php if(!empty($invoice_main_fields)) { 
                                        $sales_invoice_main_fields = array('your_invoice_no' => 'Your Invoice No.','shipment_invoice_no' => 'Shipment Invoice No.', 'shipment_invoice_date' => 'Shipment Invoice Date' , 'sbill_no' => 'S/Bill No.',  'sbill_date' => 'S/Bill Date' , 'origin_port' => 'Origin Port' , 'port_of_discharge' => 'Port of Discharge', 'container_size' => 'Container Size', 'container_bill_no' => 'Container Bill No', 'container_date' => 'Container Date' , 'container_no' => 'Container No.' , 'vessel_name_voy' => 'Vessel Name / Voy' ,  'print_date' => 'Print Date');
                                        $total_length = count($invoice_main_fields);
                                        if($total_length >= 3){
                                            $width = '33%';
                                        } elseif($total_length == 1){
                                            $width = '100%';
                                        }
                                        $i = 1;
                                        foreach($invoice_main_fields as $value){
                                            if($value != 'print_date' && $value != 'display_dollar_sign') {
                                                if (strpos($value, 'date') !== false || strpos($value, 'date') !== false) {
                                                    $ee = $value;
                                                    $field_value = isset($sales_invoice_data->$ee) && !empty($sales_invoice_data->$ee) ? date('d-m-Y', strtotime($sales_invoice_data->$ee)) : '';
                                                } else {
                                                    $dd = $value;
                                                    $field_value = isset($sales_invoice_data->$dd) ? $sales_invoice_data->$dd : '';
                                                }
                                    ?>
                                        <?php if($i == 1 || $i == 4 || $i == 7 || $i == 10 || $i == 13 || $i == 16) { ?>
                                        <tr>
                                        <?php $align = 'left';}
                                            if($i == 3 || $i == 6 || $i == 9 || $i == 12 || $i == 15 || $i == 18) {
                                                $align = 'right';
                                            }
                                            if($i == 2 || $i == 5 || $i == 8 || $i == 11 || $i == 14 || $i == 17) {
                                                $align = 'center';
                                            }
                                        ?>
                                            <td width="33%" align="<?php echo $align; ?>">
                                                    <table cellpadding="0">
                                                            <tr>
                                                                    <th class="p2" style="font-weight:bold"><?php echo $sales_invoice_main_fields[$value]; ?></th>
                                                                    <td class="p2"><?=isset($field_value) ? $field_value : '' ?></td>
                                                            </tr>
                                                    </table>
                                            </td>
                                        <?php if($i == 3 || $i == 6 || $i == 9 || $i == 12 || $i == 15 || $i == 18) { ?>
                                        </tr>
                                        <?php } ?>
                            <?php $i++; } } }?>
                        </tr>
                    </table>
            </div>
</div>