<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Contra List
            <?php if($this->applib->have_access_role(MODULE_CONTRA_ID,"add")) { ?>
            <a href="<?= base_url('contra/contra'); ?>" class="btn btn-primary pull-right">Add New</a>
            <?php } ?>
            
        </h1>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site_id" class="control-label">Site</label>
                                <select name="site_id" id="site_id" class="form-control select2"></select>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!---content start --->
                        <table class="table table-striped table-bordered contra-table" id="contra-table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Contra No</th>
                                    <th>Date</th>
                                    <th>From Bank / Cash</th>
                                    <th>To Bank / Cash</th>
                                    <th>Amount</th>
                                    <th>Note</th>
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
        initAjaxSelect2($("#site_id"), "<?= base_url('app/sites_select2_source') ?>");
        var title = 'Contra List'
        
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
                columns: [1, 2, 3, 4],
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

						for (i = 1; i < rowCount - 1; i++) {
								doc.content[1].table.body[i][1].alignment = 'right';
								doc.content[1].table.body[i][3].alignment = 'right';
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
                "url": "<?= base_url('contra/contra_datatable')?>",
                "type": "POST",
                "data": function (d) {
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
            }]
        });

        $(document).on("change","#site_id", function () {
            table.draw();
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
                        show_notify('Contra Deleted Successfully!', true);
                    }
                });
            }
        });
    });
</script>
