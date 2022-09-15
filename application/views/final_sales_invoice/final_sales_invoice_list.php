<?php $this->load->view('success_false_notify'); ?>
<style>
    .doc-modal-details .modal-header{
        display: flex;
        align-items: center;
        width: 100%;
        padding: 5px 15px;
    }
    .doc-modal-details .modal-header .close{
        position: absolute;
        right: 21px;
        font-size: 33px;
    }
    .doc-modal-details .modal-title{
        font-size: 30px;
        font-weight: 600;
    }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php
                echo 'Sales Invoice From Quote List';
                if($this->applib->have_access_role(MODULE_ORDER_TYPE_2_ID,"add")) {
                    echo '<a href="'.base_url('sales/sales_invoice_frmquot_add/').'" class="btn btn-primary pull-right">Add Sales Invoice From Quote</a>';
                }
            ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="form-inline">
                            <div class="col-md-2">
                                <label for="email" class="">Date</label>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>From : </label>
                                    <input type="text" name="daterange_1" id="datepicker1" class="form-control" placeholder="dd-mm-yyyy">
                                </div>
                                <div class="form-group">
                                    <label>To : </label>
                                    <input type="text" name="daterange_2" id="datepicker2" class="form-control" placeholder="dd-mm-yyyy">
                                </div>
                                <button type="button" id="btn_datepicker" class="btn btn-default">Submit</button>
                            </div>
                        </div>
                        <div class="form-inline col-md-12">
                            <div class="col-md-2"><label for="email" class=""></label></div><div class="col-md-4"></div>
                        </div>
                        <div class="form-group">
                            <label for="account_id" class="col-md-2" style="line-height: 30px;">Account</label>
                            <div class="col-md-4">
                                <select name="account_id[]" id="account_id" class="account_id" required multiple="multiple" ></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!---content start --->
                        <table class="table table-striped table-bordered product-table" id="product-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="70px">Action</th>
                                    <th width="100px">Quotation No</th>
                                    <th>Account</th>
                                    <th>GSTIN</th>
                                    <th>Quotation Date</th>
                                    <th class="pull-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
<!-- Model For Docs -->
<div class="modal fade doc-modal-details" id="docModel" tabindex="-1" role="dialog" aria-labelledby="docModelTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Docs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Doc Name</th>
            <th scope="col">Doc Desc</th>
            <th scope="col">Link</th>
            </tr>
        </thead>
        <tbody id="doc_table"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('assets/plugins/jquery.fileDownload.js ');?>" type="text/javascript" language="javascript"></script>

<script type="text/javascript">
    var table;
    var datepicker1 = '';
    var datepicker2 = '';
    $(document).ready(function () {
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_select2_source/') ?>");
        var type = $('#type').val();
        var client = 'All';
        $(document).on('change', '#account_id', function () {
            client = $("#account_id option:selected").text();
        });

        var buttonCommon = {
            exportOptions: {
                format: {body: function (data, row, column, node) {
                        return data.replace(/(&nbsp;|<([^>]+)>)/ig, "");
                    }},
                columns: [1, 2, 3, 4, 5],
            }
        };

        var title = type + ' Invoice For ' + client + '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )';

        table = $('.product-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                $.extend(true, {}, buttonCommon, {extend: 'copy', footer: true,title: function () {
                        return (title)
                    }, action: newExportAction}),
                $.extend(true, {}, buttonCommon, {extend: 'csvHtml5', footer: true,customize: function (csv) {
                        return type + ' Invoice For ' + client + '( From Date : ' + $('#datepicker1').val() + ' TO Date : ' + $('#datepicker2').val() + ' )\n\n' + csv;
                    }, action: newExportAction}),
                $.extend(true, {}, buttonCommon, {extend: 'pdf', orientation: 'landscape', pageSize: 'LEGAL', footer: true,title: function () {
                        return (title)
                    }, action: newExportAction,
                    customize: function (doc) {
                        var objLayout = {};
                        objLayout['hLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['vLineWidth'] = function (i) {
                            return .5;
                        };
                        doc.content[1].layout = objLayout;
                        doc.content[1].table.widths = ["20%", "20%", "20%", "20%", "20%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
                        var rowCount = document.getElementById("product-table").rows.length;
                        for (i = 1; i < rowCount - 1; i++) {
                            doc.content[1].table.body[i][4].alignment = 'right';
                        }
                        ;
                    }
                }),
                $.extend(true, {}, buttonCommon, {extend: 'excelHtml5', footer: true,title: function () {
                        return (title)
                    }, action: newExportAction,
                    customize: function (xlsx) {

                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                        var downrows = 4;
                        var clRow = $('row', sheet);
                        //update Row
                        clRow.each(function () {
                            var attr = $(this).attr('r');
                            var ind = parseInt(attr);
                            ind = ind + downrows;
                            $(this).attr("r", ind);
                        });

                        // Update  row > c
                        $('row c ', sheet).each(function () {
                            var attr = $(this).attr('r');
                            var pre = attr.substring(0, 1);
                            var ind = parseInt(attr.substring(1, attr.length));
                            ind = ind + downrows;
                            $(this).attr("r", pre + ind);
                        });

                        sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + sheet.childNodes[0].childNodes[1].innerHTML;
                        function Addrow(index, data) {
                            msg = '<row r="' + index + '">'
                            for (i = 0; i < data.length; i++) {
                                var key = data[i].k;
                                var value = data[i].v;
                                msg += '<c t="inlineStr" r="' + key + index + '" s="42">';
                                msg += '<is>';
                                msg += '<t>' + value + '</t>';
                                msg += '</is>';
                                msg += '</c>';
                            }
                            msg += '</row>';
                            return msg;
                        }
                        //insert
                        var r1 = Addrow(1, [{k: 'A', v: 'Client :'}, {k: 'B', v: client}]);
                        var r2 = Addrow(2, [{k: 'A', v: 'From :'}, {k: 'B', v: $('#datepicker1').val()}]);
                        var r3 = Addrow(3, [{k: 'A', v: 'To :'}, {k: 'B', v: $('#datepicker2').val()}]);

                        sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + sheet.childNodes[0].childNodes[1].innerHTML;
                    }
                }),
                $.extend(true, {}, buttonCommon, {extend: 'print', footer: true,title: function () {
                        return (title)
                    },
                    customize : function(win){
                        $(win.document.body).find('table thead th:nth-child(5)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(5)').css('text-align', 'right');
                        $(win.document.body).find('table tfoot th:nth-child(5)').css('text-align', 'right');
                    }, orientation: 'landscape', action: newExportAction}),
            ],
            "serverSide": true,
            "ordering": true,
            "searching": true,
            "aaSorting": [[1, 'desc']],
            "ajax": {
                "url": "<?php echo base_url('sales/invoice_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.daterange_1 = datepicker1;
                    d.daterange_2 = datepicker2;
                    d.account_id = $("#account_id").val();
                    d.quotation_type = 1;
                },
            },
            "scrollX": true,
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT; ?>',
            "scroller": {
                "loadingIndicator": true
            },
            "columnDefs": [
                {"targets": 0, "orderable": false},
                {className: "	text-right", "targets": [5]},
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };
                var total_amt = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(5).footer()).html(total_amt.toFixed(2));
            }
        });

        $(document).on('click', '#btn_datepicker', function (e) {
            e.preventDefault();
            datepicker1 = $('#datepicker1').val();
            datepicker2 = $('#datepicker2').val();
            if (datepicker2 != '' && datepicker1 != '') {
                table.draw();
                return false;
            }
        });

        $(document).on('change', '#account_id', function () {
            //assemble the regex expression for multiple select values
            var regEx = $(this).find(':selected').map(function () {
                return $(this).text();
            }).get().join("|");
            table.column(2).search(regEx, true, false).draw();
        });

        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this Invoice?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=purchase_invoice_id&table_name=purchase_invoice',
                    success: function (data) {
                        table.draw();
                        show_notify('Invoice Deleted Successfully!', true);
                    }
                });
            }
        });

        $(document).on('click','.open_doc_model',function(){
            var id = $(this).data('id');
            if(id != ''){
            $.ajax({
                url:"<?php echo base_url('quotation/getQuotationDocs') ?>",
                type: "POST",
                dataType: 'json',
                data: {
                    id:id
                },
                success: function (data) {
                    var doc_data = '';
                    $('#doc_table').html('');

                    if(data.status){
                        console.log(data.data)
                        var i = 1;
                        var link = "<?php echo base_url('assets/uploads/quotation_docs') ?>";
                        $.each(data.data , function(key,val){
                            doc_data +='<tr>';
                            doc_data +='<td>'+i+'</td>';
                            doc_data +='<td>'+val.doc_name+'</td>';
                            doc_data +='<td>'+val.doc_desc+'</td>';
                            doc_data +='<td><a href="'+ link +'/'+val.doc_name+'" class="doc_download" download>Download</a>';
                            doc_data +='</tr>';
                            i++;
                        });

                        $('#docModel').modal('show');
                    }else{
                        doc_data += '<tr><th colspan="4" style="text-align:center;">Data Not Found</th></tr>';
                        $('#docModel').modal('show');
                    }
                    $('#doc_table').append(doc_data);
                }
            });
            }
        })

    });
</script>
