<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Day Book</h1>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="datepicker1" class="control-label">From Date</label>
                            <input type="text" name="daterange_1" id="datepicker1" value="<?php echo date('d-m-Y') ?>" class="form-control" placeholder="dd-mm-yyyy">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="datepicker2" class="control-label">To Date</label>
                            <input type="text" name="daterange_2" id="datepicker2" value="<?php echo date('d-m-Y') ?>" class="form-control" placeholder="dd-mm-yyyy">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="site_id" class="control-label">Site</label>
                            <select name="site_id" id="site_id" class="form-control select2"></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <br>  
                        <button type="button" id="btn_datepicker" class="btn btn-default">Submit</button> 
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!---content start --->
                        <table class="table table-striped table-bordered contra-table" id="contra-table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Bank / Cash</th>
                                    <th>Account</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total</th>
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
<script type="text/javascript">
    var table;
    var datepicker1 = $('#datepicker1').val();
    var datepicker2 = $('#datepicker2').val();
    $(document).ready(function () {
        initAjaxSelect2($("#site_id"), "<?= base_url('app/sites_select2_source') ?>");
        var title = 'Day Book'
        
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
                columns: [1, 2, 3, 4, 5, 6],
			}
		};
        
        table = $('.contra-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                $.extend( true, {}, buttonCommon, { extend: 'copy', title: function () { return (title)}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'csvHtml5', title: function () { return (title)}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'pdf', orientation: 'landscape', pageSize: 'LEGAL', title: function () { return (title)}, action: newExportAction,
                    customize : function(doc){
						var objLayout = {};
						objLayout['hLineWidth'] = function(i) { return .5; };
						objLayout['vLineWidth'] = function(i) { return .5; };
						doc.content[1].layout = objLayout;
						doc.content[1].table.widths = ["10%", "18%", "18%", "18%", "18%", "18%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
						var rowCount = document.getElementById("contra-table").rows.length;

						for (i = 1; i < rowCount; i++) {
								doc.content[1].table.body[i][0].alignment = 'right';
								doc.content[1].table.body[i][4].alignment = 'right';
						};
					}
                } ),
                $.extend( true, {}, buttonCommon, { extend: 'excelHtml5', title: function () { return (title)}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'print',  title: function () { return (title)}, action: newExportAction } ),
            ],
            "serverSide": true,
            "ordering": true,
            "searching": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('transaction/day_book_datatable')?>",
                "type": "POST",
                "data": function (d) {
                    d.daterange_1 = datepicker1;
                    d.daterange_2 = datepicker2;
                    d.site_id = $('#site_id').val();
                },
            },
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT; ?>',
            "scroller": {
                "loadingIndicator": true
            },
            "columnDefs": [{
                "className": "text-right",
                "targets": [5],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                var debit = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                }, 0 );
                var credit = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                }, 0 );
                $( api.column( 5 ).footer() ).html(debit);
                $( api.column( 6 ).footer() ).html(credit);
            },
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

        $(document).on("click", ".delete_transaction", function () {
            var value = confirm('Are you sure delete this Transaction?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=transaction_id&table_name=transaction_entry',
                    success: function (data) {
                        table.draw();
                        show_notify('Transaction Deleted Successfully!', true);
                    }
                });
            }
        });
    });
</script>
