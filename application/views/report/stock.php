<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Stock Register</h1>
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
								<select name="account_id" id="account_id" class="account_id" required ></select>
							</div>
						</div>
                        <div class="form-group col-md-2" style="display: none;">
                            <input type="checkbox" name="display_amt" id="display_amt">&nbsp;&nbsp;<label for="display_amt">Display Amount</label>
						</div>
                        <div class="form-group col-md-2" style="display: none;">
                            <input type="checkbox" name="purchase_amt" id="purchase_amt">&nbsp;&nbsp;<label for="purchase_amt">Purchase Amount</label>
						</div>
                        <div class="form-group col-md-2" style="display: none;">
                            <input type="checkbox" name="sales_amt" id="sales_amt">&nbsp;&nbsp;<label for="sales_amt">Sales Amount</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered stock-table" id="stock-table">
							<thead>
								<tr>
									<th width="70px">Sr. No.</th>
									<th>Item Name</th>
									<th>Opening Stock</th>
									<th>Opening Amount</th>
									<th>Inward</th>
									<th>Inward Amount</th>
									<th>Out ward</th>
									<th>Out ward Amount</th>
									<th>Closing</th>
									<th>Closing Amount</th>
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
	var datepicker1 = '';
	var datepicker2 = '';
	$(document).ready(function(){
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
            
        var title = 'Stock Register For '+client+ '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )';
        
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.toString().replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
			}
		};
		
		table = $('.stock-table').DataTable({
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
						doc.content[1].table.widths = ["5%","25%","8%", "8%", "8%", "8%", "8%", "8%", "8%", "8%", "8%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
						var rowCount = document.getElementById("stock-table").rows.length;

						for (i = 1; i < rowCount; i++) {
								doc.content[1].table.body[i][0].alignment = 'right';
								doc.content[1].table.body[i][2].alignment = 'right';
								doc.content[1].table.body[i][3].alignment = 'right';
								doc.content[1].table.body[i][4].alignment = 'right';
								doc.content[1].table.body[i][5].alignment = 'right';
								doc.content[1].table.body[i][6].alignment = 'right';
								doc.content[1].table.body[i][7].alignment = 'right';
								doc.content[1].table.body[i][8].alignment = 'right';
								doc.content[1].table.body[i][9].alignment = 'right';
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
                        $(win.document.body).find('table thead th:nth-child(3)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(3)').css('text-align', 'right');

                        $(win.document.body).find('table thead th:nth-child(4)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(4)').css('text-align', 'right');
                        
                        $(win.document.body).find('table thead th:nth-child(5)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(5)').css('text-align', 'right');
                        
                        $(win.document.body).find('table thead th:nth-child(6)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(6)').css('text-align', 'right');
                        
                        $(win.document.body).find('table thead th:nth-child(7)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(7)').css('text-align', 'right');
                        
                        $(win.document.body).find('table thead th:nth-child(8)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(8)').css('text-align', 'right');
                        
                        $(win.document.body).find('table thead th:nth-child(9)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(9)').css('text-align', 'right');
                        
                        $(win.document.body).find('table thead th:nth-child(10)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(10)').css('text-align', 'right');
                    }, action: newExportAction } ),
            ],
            "serverSide": true,
			"ordering": false,
			"searching": true,
			//~ "aaSorting": [[1, 'desc']],
			"ajax": {
				"url": "<?php echo base_url('report/stock_datatable')?>",
				"type": "POST",
				"data": function(d){
                    d.daterange_1 = datepicker1;
					d.daterange_2 = datepicker2;
                    d.account_id = $("#account_id").val();
                },
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
                {"className": "text-right","targets": [2,3,4,5,6,7,8,9]},
                //{"targets": [3,5,7,9],"visible": false,"searchable": false},
            ]
		});
        
        $(document).on('change','#display_amt',function(e){
            if($(this).is(":checked")) {
                table.column(3).visible(true);
                table.column(5).visible(true);
                table.column(7).visible(true);
                table.column(9).visible(true);
            } else {
                table.column(3).visible(false);
                table.column(5).visible(false);
                table.column(7).visible(false);
                table.column(9).visible(false);
            }
        });
        
        $(document).on('change','#purchase_amt',function(e){
            if($(this).is(":checked")) {
                table.column( 4 ).visible( true );
            } else {
                table.column( 4 ).visible( false );
            }
        });
        
        $(document).on('change','#sales_amt',function(e){
            if($(this).is(":checked")) {
                table.column( 6 ).visible( true );
            } else {
                table.column( 6 ).visible( false );
            }
        });
        
		$(document).on('click','#btn_datepicker',function(e){
			e.preventDefault();
			datepicker1 = $('#datepicker1').val();
			datepicker2 = $('#datepicker2').val();
			if(datepicker2 != '' && datepicker1 != ''){
				table.draw();
				return false;
			}
		});
		
		$(document).on('change','#account_id',function(){
			table.draw();
        });
	});
</script>
