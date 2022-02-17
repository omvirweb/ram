<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Pending Bills Report</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="account_id">Account</label>
                                    <select name="account_id" id="account_id" class="account_id" required ></select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Due Date upto Date</label><br/>
                                    <input type="text" name="daterange_3" id="" class="form-control datepicker" value="<?php echo date('d-m-Y') ; ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input type="hidden" name="" id="table_draw" value="0"><br/>
                                <button type="button" id="btn_datepicker" class="btn btn-default">Submit</button>
                            </div>
                        </div>
                        <div class="form-inline col-md-12">
                            <div class="col-md-2"><label for="email" class=""></label></div><div class="col-md-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <!---content start --->
                        <table class="table table-striped table-bordered ledger_table" id="ledger_table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Bill No</th>
                                    <th>Type</th>
                                    <th>Due Days</th>
                                    <th>Bill Amt</th>
                                    <th>Received Amt</th>
                                    <th>Pending Amt</th>
                                    <th>Balance Amt</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!---content end--->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        $('input[name="daterange"]').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_select2_source_bill_wise/') ?>");
        <?php if (isset($account_id) && !empty($account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $account_id) ?>");
            //$("#btn_datepicker").trigger('click');
            setTimeout(function(){ $('#btn_datepicker').click()}, 20);
            //$('#btn_datepicker').click();
        <?php } ?>

        var client = 'All'
        $(document).on('change','#account_id', function(){
            client = $("#account_id option:selected").text();
        });
        
        var title = 'Ledger For '+client+ '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )';

        var buttonCommon = {
            exportOptions: {
                format: { body: function ( data, row, column, node ) { return data.toString().replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
                columns: [1, 2, 3, 4, 5, 6],
            }
        };
        $(document).on('click', '#btn_datepicker', function () {
            
            if ($('#table_draw').val() == '0') {
                $('#table_draw').val('1');
                table = $('.ledger_table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        $.extend( true, {}, buttonCommon, { extend: 'copy', title: function () { return (title)}, action: newExportAction } ),
                        $.extend( true, {}, buttonCommon, { extend: 'csvHtml5', title: function () { return (title)}, action: newExportAction, customize: function (csv) {
                                                    return 'Ledger For '+client+ '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )\n\n'+  csv;
                                                } } ),
                        $.extend( true, {}, buttonCommon, { extend: 'pdf', orientation: 'landscape', pageSize: 'LEGAL', title: function () { return (title)}, action: newExportAction,
                            customize : function(doc){
                                var objLayout = {};
//                                objLayout['hLineWidth'] = function(i) { return .5; };
//                                objLayout['vLineWidth'] = function(i) { return .5; };
                                doc.content[1].layout = objLayout;
                                doc.content[1].table.widths = ["15%", "20%", "20%", "15%", "15%", "15%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
                                var rowCount = document.getElementById("ledger_table").rows.length;

                                for (i = 1; i < rowCount; i++) {
                                                doc.content[1].table.body[i][4].alignment = 'right';
                                                doc.content[1].table.body[i][5].alignment = 'right';
                                                doc.content[1].table.body[i][3].alignment = 'right';
                                                text_name = '';
                                                text_o = doc.content[1].table.body[i][1].text;
                                                text_name = $.trim(text_o);;
                                                doc.content[1].table.body[i][1].text = text_name;
//                                                console.log(doc.content[1].table.body[i][1].text);
                                };
                            }
                        } ),
                        $.extend( true, {}, buttonCommon, { extend: 'excelHtml5', title: function () { return (title)}, action: newExportAction ,

                            customize : function (xlsx) {

                                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                                var downrows = 4;
                                var clRow = $('row', sheet);
                                //update Row
                                clRow.each(function () {
                                    var attr = $(this).attr('r');
                                    var ind = parseInt(attr);
                                    ind = ind + downrows;
                                    $(this).attr("r",ind);
                                });

                                // Update  row > c
                                $('row c ', sheet).each(function () {
                                    var attr = $(this).attr('r');
                                    var pre = attr.substring(0, 1);
                                    var ind = parseInt(attr.substring(1, attr.length));
                                    ind = ind + downrows;
                                    $(this).attr("r", pre + ind);
                                });

                                function Addrow(index,data) {
                                    msg='<row r="'+index+'">'
                                    for(i=0;i<data.length;i++){
                                        var key=data[i].k;
                                        var value=data[i].v;
                                        msg += '<c t="inlineStr" r="' + key + index + '" s="42">';
                                        msg += '<is>';
                                        msg +=  '<t>'+value+'</t>';
                                        msg+=  '</is>';
                                        msg+='</c>';
                                    }
                                    msg += '</row>';
                                    return msg;
                                }
                                //insert
                                var r1 = Addrow(1, [{ k: 'A', v: 'Client :' }, { k: 'B', v: client }]);
                                var r2 = Addrow(2, [{ k: 'A', v: 'From :' }, { k: 'B', v: $('#datepicker1').val() }]);
                                var r3 = Addrow(3, [{ k: 'A', v: 'To :' }, { k: 'B', v: $('#datepicker2').val() }]);

                                sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3  + sheet.childNodes[0].childNodes[1].innerHTML;
                            }
                        }),
                        $.extend( true, {}, buttonCommon, { extend: 'print',  title: function () { return (title)},
                        customize : function(win){
                            $(win.document.body).find('table thead th:nth-child(4)').css('text-align', 'right');
                            $(win.document.body).find('table tbody td:nth-child(4)').css('text-align', 'right');
                            $(win.document.body).find('table thead th:nth-child(5)').css('text-align', 'right');
                            $(win.document.body).find('table tbody td:nth-child(5)').css('text-align', 'right');
                            $(win.document.body).find('table thead th:nth-child(6)').css('text-align', 'right');
                            $(win.document.body).find('table tbody td:nth-child(6)').css('text-align', 'right');
                        }, action: newExportAction } ),
                    ],
                    "serverSide": true,
                    "ordering": false,
                    "searching": false,
                    "bInfo": false,
                    "order": [[0, 'asc']],
                    "ajax": {
                        "url": "<?php echo base_url('report/pending_bills_datatable') ?>",
                        "type": "POST",
                        "data": function (d) {
                            d.due_date = $('.datepicker').val();
                            d.account_id = $("#account_id").val();
                        },
                    },
                    "dataSrc" : function (json) {
                        console.log(json);
                    },
                    "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT; ?>',
                    "scroller": {
                        "loadingIndicator": true
                    },
                    "columnDefs": [

                        {"className": "text-right", "targets": [4,5,6,7]},

                    ]
                });
            } else {
                table.draw();                
            }
            data_table_export_icon_style();
        });

        $(document).on("click",".delete_button",function(){
            var value = confirm('Are you sure delete this record?');
            var tr = $(this).closest("tr");
            if(value){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function(data){
                        table.draw();
                        show_notify('Deleted Successfully!',true);
                    }
                });
            }
        });
    });
</script>
