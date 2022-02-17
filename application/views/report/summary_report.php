<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Outstanding - CTRL + F3</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<div class="form-inline">
							<div class="col-md-12">
                                                            <div class="col-md-3">
								<div class="form-group">
                                                                    <label>Up To Date : </label><br>
                                                                    <input type="text" name="daterange_1" id="datepicker1" class="form-control" placeholder="dd/mm/yyyy" value="<?php echo date('d-m-Y') ?>">
								</div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Account</label>
                                                                    <select name="account_id" id="account_id" class="form-control account_id"></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Transaction Type</label><br>
                                                                    <select name="tr_type" id="tr_type" class="form-control select2">
                                                                        <option value="0">All</option>
                                                                        <option value="1">Payable</option>
                                                                        <option value="2">Receivable</option>
                                                                    </select>
                                                                </div>
                                                            </diV>
                                                            <div class="col-md-3">
                                                                <input type="hidden" name="" id="table_draw" value="0">
                                                                <br><button type="button" id="btn_datepicker" class="btn btn-default pull-left">Submit</button>
                                                            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered summary_table" id="summary_table">
							<thead>
								<tr>
									<th>Account</th>
									<th>Credit</th>
									<th>Debit</th>
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
	$(document).ready(function(){
        $('.select2').select2();
        $('input[name="daterange"]').daterangepicker({
                locale: {
                        format: 'DD-MM-YYYY'
                }
        });
        initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");

        var client = 'All'
        $(document).on('change','#account_id', function(){
            client = $("#account_id option:selected").text();
        });

        var tr_type = 'All'
        $(document).on('change','#tr_type', function(){
            tr_type = $("#tr_type option:selected").text();
        });
        
        var title = 'Outstanding Report For '+client+ ' Transaction Type: ' +tr_type+ ' ( Up to Date : ' + $('#datepicker1').val() +' )';

        var buttonCommon = {
            exportOptions: {
                format: { body: function ( data, row, column, node ) { return data.toString().replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
                columns: [0, 1, 2],
            }
        };
        
        table = $('.summary_table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                $.extend( true, {}, buttonCommon, { extend: 'copy', title: function () { return (title)}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'csvHtml5', title: function () { return (title)}, action: newExportAction, customize: function (csv) {
                                            return 'Debit Note Register For '+client+ '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )\n\n'+  csv;
                                        } } ),
                $.extend( true, {}, buttonCommon, { extend: 'pdf', orientation: 'landscape', pageSize: 'LEGAL', title: function () { return (title)}, action: newExportAction,
                    customize : function(doc){
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        doc.content[1].layout = objLayout;
                        var tr_type = $('#tr_type').val();
                        if(tr_type == '0') {
                            doc.content[1].table.widths = ["40%","30%","30%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
                            var rowCount = document.getElementById("summary_table").rows.length;

                            for (i = 1; i < rowCount; i++) {
                                            doc.content[1].table.body[i][1].alignment = 'right';
                                            doc.content[1].table.body[i][2].alignment = 'right';
                            };
                        } else {
                            doc.content[1].table.widths = ["50%","50%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
                            var rowCount = document.getElementById("summary_table").rows.length;

                            for (i = 1; i < rowCount; i++) {
                                            doc.content[1].table.body[i][1].alignment = 'right';
                            };
                        }
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
                        var r2 = Addrow(2, [{ k: 'A', v: 'Transaction Type :' }, { k: 'B', v: tr_type }]);
                        var r3 = Addrow(3, [{ k: 'A', v: 'Up to Date :' }, { k: 'B', v: $('#datepicker1').val() }]);

                        sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + sheet.childNodes[0].childNodes[1].innerHTML;
                    }
                }),
                $.extend( true, {}, buttonCommon, { extend: 'print',  title: function () { return (title)},
                        customize : function(win){
                            $(win.document.body).find('table thead th:nth-child(2)').css('text-align', 'right');
                            $(win.document.body).find('table tbody td:nth-child(2)').css('text-align', 'right');
                            $(win.document.body).find('table tbody td:nth-child(3)').css('text-align', 'right');
                            $(win.document.body).find('table thead th:nth-child(3)').css('text-align', 'right');
                        }, action: newExportAction } ),
            ],
            "serverSide": true,
            "ordering": false,
            "searching": false,
            "bInfo" : false,
            "order": [[0, 'asc']],
            "ajax": {
                "url": "<?php echo base_url('report/summary_datatable')?>",
                "type": "POST",
                "data": function(d){
                    d.daterange_1 = $('#datepicker1').val();
                    d.account_id = $('#account_id').val();
                    d.tr_type = $('#tr_type').val();
                },
            },
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
            "scroller": {
                "loadingIndicator": true
            },
            "columnDefs": [
                {"className": "text-right", "targets": [1] },
                {"className": "text-right", "targets": [2] },
            ]
        });

        $(document).on('click','#btn_datepicker',function(){
                var tr_type = $('#tr_type').val();

                if(tr_type == '1'){
                    table.column( 2 ).visible( false );
                } else {
                    table.column( 2 ).visible( true );
                }
                if(tr_type == '2'){
                    table.column( 1 ).visible( false );
                } else {
                    table.column( 1 ).visible( true );
                }
                table.draw();            
        });
        
	});
</script>
