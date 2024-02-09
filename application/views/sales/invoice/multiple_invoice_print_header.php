
<table border="0" style="width: 100%;">
    <tr>
        <td style="text-align: center;font-size: 14px;"><?=isset($user_name) ? $user_name : '' ?></td>
    </tr>
</table>
<table border="0" style="width: 100%;">
    <tr>
        <td style="width: 20%;text-align: center;">
            <img src="<?=isset($logo_image) && !empty($logo_image) ? base_url() .'assets/uploads/logo_image/'.$logo_image : '';?>" style="width:150px" class="user-image" alt="User Image">
        </td>
        <td colspan="2" style="width: 60%;padding: 8px;text-align: center;">
            <span class="address" style="font-size: 12px !important;"> 
            <?=isset($user_address) ? $user_address : '' ?>,</span> <span style="font-size: 10px;">State Name : <?=isset($user_state) ? $user_state : '' ?>
                E-Mail : <?=isset($user_state) ? $email_ids : '' ?>,<br/> Web : &nbsp;
            Contact : <?=isset($user_phone) ? $user_phone : '' ?></span>
        </td>
        <td rowspan="2" style="width: 20%;text-align: center;border-bottom: 0px;">
            <img src="<?=isset($barcode) && !empty($barcode) ? base_url() .'assets/uploads/barcode/'.$barcode : '';?>" style="width:140px" class="user-image" alt="User Image">
        </td>
    </tr>
    <tr>
        
        <td colspan="2" style="padding: 8px;width:50%;border-bottom: 0px;" class="np-border-bottom">
            <table border="0" style="width:100%;" >
                <tr>
                    <td style="width:20%;font-size: 12px;word-wrap: break-word;padding-bottom: 3px;">IRN  </td>
                    <td style="width:80%;font-size: 12px;word-wrap: break-word;padding-bottom: 3px;">:&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:20%;font-size: 12px;word-wrap: break-word;padding-bottom: 3px;">Ack No. </td>
                    <td style="width:80%;font-size: 12px;word-wrap: break-word;padding-bottom: 3px;font-weight:bold;">: &nbsp; </td>
                </tr>
                <tr>
                    <td style="width:20%;font-size: 12px;word-wrap: break-word;padding-bottom: 3px;">Ack Date. </td>
                    <td style="width:80%;font-size: 12px;word-wrap: break-word;padding-bottom: 3px;font-weight:bold;">: &nbsp; </td>
                </tr>
            </table>
        </td>
        <td>
            <table style="width:100%;text-align: right;">
                <tr>
                    <td style="font-size: 12px;word-wrap: break-word;padding-bottom: 5px;">Original for Buyer</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;word-wrap: break-word;padding-bottom: 5px;">Duplicate for Transporter</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;word-wrap: break-word;padding-bottom: 5px;">Triplicate for Assessee</td>
                </tr>
            </table>
        </td>
    </tr>
</table>  

<table width="100%" border="1">
    <tr>
        <td class="no-border-left no-border-top no-border-right" colspan="3" style="text-align: center;font-weight: bold;font-size: 12px;padding-top: 15px;"> <?php
            $invoice_type = $this->crud->get_id_by_val('invoice_type','invoice_type','invoice_type_id',$sales_invoice_data->invoice_type);
            echo strtoupper($invoice_type);
            ?>
        </td>
    </tr>
    <tr>
        <td valign="top" class="no-border-bottom" rowspan="5" style="width:50%;padding: 5px;">
            <span style="font-size: 13px;font-weight: bold;padding-top: 5px;"><?=isset($user_name) ? $user_name : '' ?></span><br/>
            <span style="word-wrap: break-word;font-family: sans-serif;font-size: 13px;"><?=isset($user_address) ? nl2br($user_address) : '' ?></span><br/>
            <span style="font-size: 12px;">GSTIN/UIN:: <?=isset($user_gst_no) ? $user_gst_no : '' ?></span><br/>
            <span style="font-size: 12px;">State Name : <?=isset($user_state) ? $user_state : '' ?></span><br/>
            <span style="font-size: 12px;letter-spacing: -1px;">E-Mail : <?=isset($email_ids) ? $email_ids : '' ?></span>
        </td>
        <td style="width:25%;">
            <table style="width: 100%;" border="1">
                    <tr>
                        <td style="width: 50%;border: 0px;">
                            Invoice No  <br><span class="text-bold"><?=isset($sales_invoice_no) ? $sales_invoice_no : '' ?></span>
                        </td>
                        <td style="width: 50%;border: 0px;text-align: right;">
                            <span style="letter-spacing: -1px;">e-Way Bill No</span>  <br/> 
                            <span style="letter-spacing: -1px;font-size: 10px;font-weight:bold;">&nbsp;</span>
                        </td>
                    </tr>
            </table>
        </td>
        <td class="no-border-left" style="width:25%;"> Dated  <br/> <span class="text-bold"><?=isset($sales_invoice_date) ? $sales_invoice_date : '' ?></span></td></td>
    </tr>
    <tr>
        <td style="width:25%;"> Delivery Note <br/> &nbsp; </td>
        <td class="no-border-left" style="width:25%;"> Mode/Terms of Payment <br/> &nbsp; </td>
    </tr>
    <tr>
        <td style="width:25%;"> Buyer’s Order No. <br/> &nbsp; </td>
        <td class="no-border-left" style="width:25%;"> Dated <br/> &nbsp; </td>
    </tr>
    <tr>
        <td class="no-border-top" style="width:25%;"> Dispatch Doc No. <br/> &nbsp; </td>
        <td class="no-border-left " style="width:25%;"> Delivery Note Date <br/> &nbsp; </td>
    </tr>
    <tr>
        <td class="no-border-top" style="width:25%;"> Despatched through <br/> &nbsp; </td>
        <td class="no-border-left " style="width:25%;"> Destination <br/> &nbsp; </td>
    </tr>
    
    
</table>
<table width="100%" border="1" style="border-bottom:none !important;">
    <tr>
        <td valign="top" class="no-border-bottom" style="width:50%"> Consignee (Ship to) <br/>
            <span class="text-bold" style="font-size: 12px;"><?=isset($account_name) ? $account_name : '';?></span><br/>
            <span style="font-size: 12px;"><?=isset($account_addresss) ? $account_addresss : '';?></span>

        </td>
        <td style="width:25%;" class="no-border-top"> Bill of Lading/LR-RR No. <br/> 
        <span style="letter-spacing: -1px;font-size: 12px;font-weight:bold;"><?=isset($lr_no) ? $lr_no : '';?></span> </td>
        <td class="no-border-left no-border-top" style="width:25%;"> Motor Vehicle No. <br/> &nbsp; </td>
    </tr>
    <tr>
        <td valign="top" class="no-border-bottom no-border-top" style="width:50%">
            <table style="width: 100%;" border="1" >
                <tr>
                    <td style="font-size: 12px;width: 25%;border:0px;padding-top:10px;padding-left: 0px;">GSTIN/UIN</td>
                    <td style="font-size: 12px;width: 75%;border:0px;padding-top:10px;">: <?=isset($account_gst_no) ? $account_gst_no : ''; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;width: 25%;border:0px;padding-left: 0px;">State Name</td>
                    <td style="font-size: 12px;width: 75%;border:0px;">: <?=isset($account_state) ? $account_state : ''; ?>,Code : <?=isset($user_postal_code) ? $user_postal_code : '';?></td>
                </tr>
            </table>
        </td>
        <td class="no-border-bottom no-border-top" valign="top" style="width:50%;" colspan="2" align="left">Terms Of Delivery<br/>
        <span style="font-size: 10px;font-weight:bold;word-wrap: break-word;"><?=isset($sales_note) ? $sales_note : ''; ?></span>
        </td>
    </tr>
</table>
<table width="100%" border="1">
    <tr>
        <td valign="top" class="" style="width:50%;padding-top: 5px;"> Buyer (Bill to) <br/>
            <span class="text-bold" style="font-size: 12px;"><?=isset($account_name) ? $account_name : '';?></span><br/>
            <span><?=isset($account_addresss) ? $account_addresss : '';?></span>
                GSTIN/UIN  : <?=isset($account_gst_no) ? $account_gst_no : '';?><br/>
                State Name  : <?=isset($account_state) ? $account_state : '';?>,Code : <?=isset($user_postal_code) ? $user_postal_code : '';?><br/>
                Place of Supply   : <?=isset($account_state) ? $account_state : '';?><br/>
        </td>
        <td style="width:25%;" class="no-border-top no-border-right "> &nbsp; </td>
        <td class="no-border-left no-border-top " style="width:25%;"> &nbsp; </td>
    </tr>
</table>