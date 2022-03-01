<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('journal/save_journal') ?>" method="post" id="save_journal" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($journal_data->journal_id) && !empty($journal_data->journal_id)) { ?>
            <input type="hidden" name="journal_id" class="journal_id" value="<?= $journal_data->journal_id ?>">
        <?php } ?>
            <input type="hidden" name="total_credit" class="total_credit" id="total_credit_db" value="0">
            <input type="hidden" name="total_debit" class="total_debit" id="total_debit_db" value="0">
            <input type="hidden" name="deleted_transaction_ids" id="deleted_transaction_ids">
            <input type="hidden" id="credit_limit" value="0">
     
        <section class="content-header">
            <h1>
                Add Journal
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn"><?= isset($journal_data->journal_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                <?php if($this->applib->have_access_role(MODULE_JOURNAL_ID,"view")) { ?>
                <a href="<?= base_url('journal/journal_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin-right: 5px;">Journal List</a>
                <?php } ?>
                
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="date">Date</label>
                                        <input type="text" name="journal_date" id="datepicker2" class="form-control input-datepicker" value="<?= (isset($journal_data->journal_date)) ? date('d-m-Y', strtotime($journal_data->journal_date)) : date('d-m-Y',strtotime($journal_date)); ?>">
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="line_item_form item_fields_div">
                                        <input type="hidden" name="line_items_index" id="line_items_index" />
                                        <input type="hidden" name="line_items_data[transaction_id]" id="transaction_id" value="0" />                                        
                                        <div class="col-md-2">
                                            <label for="is_credit_debit">Select Type<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[is_credit_debit]" class="form-control is_credit_debit" id="is_credit_debit">
                                                <option value=""> - Select - </option>
                                                <option value="1">Credit</option>
                                                <option value="2">Debit</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="account_id">Select Account<span class="required-sign">&nbsp;*</span></label>
                                            <select name="line_items_data[account_id]" class="form-control account_id select2" id="account_id">
                                            </select>
                                            <b>Current Balance : <span class="account_curr_balance"></span></b>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="amount">Amount<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="line_items_data[amount]" class="form-control num_only amount" id="amount"  placeholder="" value=""><br />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="site_id" class="control-label">Sites <span class="required-sign">*</span></label>
                                                <select name="line_items_data[site_id]" id="site_id" class="form-control select2"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-2"  >
                                            <label for="note">Note</label>
                                            <textarea name="line_items_data[note]" class="form-control" id="note" placeholder=""></textarea><br />
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <input type="button" id="add_lineitem" class="btn btn-primary btn-sm add_lineitem" value="Add" style="margin-top: 21px;"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-10">
                                        <table style="" class="table custom-table item-table">
                                            <thead>
                                                <tr>
                                                    <th width="100px">Action</th>
                                                    <th>Account Name</th>
                                                    <th class="text-right">Credit</th>
                                                    <th class="text-right">Debit</th>
                                                    <th class="text-right">Note</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lineitem_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total:</th>
                                                    <th></th>
                                                    <th class="text-right" id="total_credit"></th>
                                                    <th class="text-right" id="total_debit"></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    $("#datepicker2").focus();
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    
    var first_time_edit_mode = 1;
    var on_save_add_edit_item = 0;
    var edit_lineitem_inc = 0;
    var lineitem_objectdata = [];
    var journal_index = '';
    <?php if (isset($journal_detail)) { ?>
        var li_lineitem_objectdata = [<?php echo $journal_detail; ?>];
        first_time_edit_mode = 0;
        var lineitem_objectdata = [];
        if (li_lineitem_objectdata != '') {
            $.each(li_lineitem_objectdata, function (index, value) {
                lineitem_objectdata.push(value);
            });
        }
    <?php } ?>
    display_lineitem_html(lineitem_objectdata);
    $(document).ready(function () {
        $('#datepicker2').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            maxDate: 0,
        })

        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_select2_source') ?>");
        initAjaxSelect2($("#site_id"), "<?= base_url('app/sites_select2_source') ?>");
        <?php if (isset($journal_detail->account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $journal_detail->account_id) ?>");
        <?php } ?>
        
        $("#is_credit_debit").select2();

        $(document).on('change', '#account_id', function(){
            var account_id = $('#account_id').val();
            if(account_id != null) {
                $.ajax({
                    url: "<?= base_url('app/get_account_balance/') ?>" + account_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        $('.account_curr_balance').html(res.balance);
                    },
                });
            } else {
                $('.account_curr_balance').html('');
            }
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#save_journal").submit();
                return false;
            }
        });

        $(document).on('submit', '#save_journal', function () {
            $(window).unbind('beforeunload');
            if (lineitem_objectdata == '') {
                show_notify("Please Add Item.", false);
                return false;
            }
            var total_credit = $('#total_credit_db').val();
            var total_debit = $('#total_debit_db').val();

            total_credit = round(total_credit);
            total_debit = round(total_debit);
            
            if(total_credit != total_debit){
                show_notify('Total Naam And Total Jama Should be same.', false);
                return false;
            }

            $("#ajax-loader").show();
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            var lineitem_objectdata_stringify = JSON.stringify(lineitem_objectdata);
            postData.append('line_items_data', lineitem_objectdata_stringify);
            $.ajax({
                url: "<?= base_url('journal/save_journal_type2') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                dataType: 'json',
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    if (response.status == 'success') {
                        window.location.href = "<?php echo base_url('journal/journal_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });

        $('#add_lineitem').on('click', function () {
            var is_credit_debit = $("#is_credit_debit").val();
            
            if (is_credit_debit == '' || is_credit_debit == null) {
                $("#is_credit_debit").select2('open');
                show_notify("Please select Type!", false);
                return false;
            }
            var account_id = $("#account_id").val();
            if (account_id == '' || account_id == null) {
                $("#account_id").select2('open');
                show_notify("Please select Account!", false);
                return false;
            }
            var amount = $("#amount").val();
            if (amount == '' || amount == null) {
                $("#amount").focus();
                show_notify('Amount is required!', false);
                return false;
            }
            save_lineitem();

            /*var credit_limit = $('#credit_limit').val();
            var amount = $("#amount").val();
            var is_credit_debit = $('#is_credit_debit').val();
            var is_grater = 0;

            if((parseFloat(amount) > parseFloat(credit_limit)) && is_credit_debit == 1) {
                is_grater = 1;
            }
            if(is_grater == 1){
                if (confirm('Amount Exceed Credit Limit. Are you sure you want to save?')) {
                    save_lineitem();
                }
            } else {
                save_lineitem();
            }*/
        });
        
    });
    
    function display_lineitem_html(lineitem_objectdata) {
        $('#ajax-loader').show();
        var new_lineitem_html = '';
        var total_credit = 0;
        var total_debit = 0;

        $.each(lineitem_objectdata, function (index, value) {
            var lineitem_edit_btn = '';
            var lineitem_delete_btn = '';   
            if(value.is_credit_debit == '1'){
                total_credit = total_credit + parseFloat(value.amount);
            }
            if(value.is_credit_debit == '2'){
                    total_debit = total_debit + parseFloat(value.amount); 
            }
            lineitem_edit_btn = '<a class="btn btn-xs btn-primary btn-edit-item edit_lineitem_' + index + '" href="javascript:void(0);" onclick="edit_lineitem(' + index + ')"><i class="fa fa-edit"></i></a> ';
            lineitem_delete_btn = '<a class="btn btn-xs btn-danger btn-delete-item delete_j_item" href="javascript:void(0);" onclick="remove_lineitem(' + index + ')"><i class="fa fa-remove"></i></a>';
            var row_html = '<tr class="lineitem_index_' + index + '"><td class="">' +
                    lineitem_edit_btn +
                    lineitem_delete_btn +
                    '</td>' +
                    '<td>' + value.account_name + '</td>';
            
                    if(value.is_credit_debit == '1'){
                        row_html += '<td class="text-right">' + value.amount + '</td>';
                    }
                    else{
                        row_html += '<td></td>';
                    }
                    if(value.is_credit_debit == '2'){
                        row_html += '<td class="text-right">' + value.amount + '</td>';
                    }
                    else{
                        row_html += '<td></td>';
                    }
                    row_html += '<td>' + value.note + '</td>';
            new_lineitem_html += row_html;
        });
        $('tbody#lineitem_list').html(new_lineitem_html);
        $('#total_credit_db').val(total_credit);
        $('#total_debit_db').val(total_debit);
        $('#total_credit').html(total_credit);
        $('#total_debit').html(total_debit);
        $('#ajax-loader').hide();
    }

    function edit_lineitem(index) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $('#ajax-loader').show();
        $(".delete_j_item").addClass('hide');
        journal_index = index;
        if (edit_lineitem_inc == 0) {
            edit_lineitem_inc = 1;
            $(".add_lineitem").removeAttr("disabled");
        }
        var value = lineitem_objectdata[index];

        var transaction_id = value.transaction_id;
        $('#transaction_id').val(transaction_id);
        $("#line_items_index").val(index);
        if(typeof(value.id) != "undefined" && value.id !== null) {
                $("#lineitem_id").val(value.id);
        }
        $("#is_credit_debit").val(value.is_credit_debit).trigger("change");
        $("#account_id").val(null).trigger("change");
        setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/') ?>" + value.account_id);
        $("#amount").val(value.amount);
        $("#note").val(value.note);
        $('#ajax-loader').hide();
    }

    function remove_lineitem(index) {
        if (confirm('Are you sure ?')) {
            value = lineitem_objectdata[index];
            var transaction_id = value.transaction_id;
            var deleted_transaction_ids = $('#deleted_transaction_ids').val();
            if(deleted_transaction_ids != ''){
                deleted_transaction_ids += ', '+transaction_id;
                $('#deleted_transaction_ids').val(deleted_transaction_ids);
            }
            else{
                $('#deleted_transaction_ids').val(transaction_id);
            }
            if (typeof (value.lineitem_id) != "undefined" && value.lineitem_id !== null) {
                $('.line_item_form').append('<input type="hidden" name="deleted_lineitem_id[]" id="deleted_lineitem_id" value="' + value.lineitem_id + '" />');
            }
            lineitem_objectdata.splice(index, 1);
            display_lineitem_html(lineitem_objectdata);
        }
    }

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
          return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
          return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }
    
    function save_lineitem(){
        $("#add_lineitem").attr('disabled', 'disabled');
        var key = '';
        var value = '';
        var lineitem = {};
        var is_validate = '0';
        $('select[name^="line_items_data"]').each(function (e) {
            key = $(this).attr('name');
            key = key.replace("line_items_data[", "");
            key = key.replace("]", "");
            value = $(this).val();
            lineitem[key] = value;
        });
        $('input[name^="line_items_data"]').each(function () {
            key = $(this).attr('name');
            key = key.replace("line_items_data[", "");
            key = key.replace("]", "");
            value = $(this).val();
            lineitem[key] = value;
        });
        $('textarea').each(function (e) {
            key = $(this).attr('name');
            key = key.replace("line_items_data[", "");
            key = key.replace("]", "");
            value = $(this).val();
            lineitem[key] = value;
        });
        
        if (is_validate != '1') {
            var account_data = $('#account_id option:selected').html();

            lineitem['account_name'] = account_data;
            var transaction_id = $('#transaction_id').val();
            lineitem['transaction_id'] = transaction_id;
            $('#transaction_id').val('');
            var new_lineitem = JSON.parse(JSON.stringify(lineitem));

            var line_items_index = $("#line_items_index").val();
            if (line_items_index != '') {
                lineitem_objectdata.splice(line_items_index, 1, new_lineitem);
            } else {
                lineitem_objectdata.push(new_lineitem);
            } 
            display_lineitem_html(lineitem_objectdata);
            $('#lineitem_id').val('');
            $("#account_id").val(null).trigger("change");
            $("#is_credit_debit").val(null).trigger("change");
            $("#amount").val('');
            $("#note").val('');
            $('.account_curr_balance').html('');
            $("#line_items_index").val('');
            if (on_save_add_edit_item == 1) {
                on_save_add_edit_item == 0;
                $('#save_journal').submit();
            }
            edit_lineitem_inc = 0;
        }
        $("#add_lineitem").removeAttr('disabled', 'disabled');
    }
    
</script>
