<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Site Report</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<div class="form-inline">
							<div class="col-md-12">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date : </label><br>
                                        <input type="text" name="from_date" id="datepicker1" class="form-control" value="<?php echo isset($from_date) ? date('d-m-Y',strtotime($from_date)) : date('d-m-Y');  ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date : </label><br>
                                        <input type="text" name="to_date" id="datepicker2" class="form-control" value="<?php echo isset($to_date) ? date('d-m-Y',strtotime($to_date)) :  date('d-m-Y'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" style="width:100%">
                                        <label for="site_id" class="control-label"  style="margin-bottom: 3px;">Site</label>
                                        <select name="line_items_data[site_id]" id="site_id" class="form-control select2">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" style="width:100%">
                                        <label for="module" class="control-label"  style="margin-bottom: 3px;">Module</label>
                                        <select name="line_items_data[module]" id="module" class="form-control select2">
                                            <?php for($i=0; $i<=8; $i++) { ?>
                                                <option value="<?php echo $i ?>"><?php echo $module_option[$i] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="" id="table_draw" value="0">
                                    <br><button type="button" id="btn_search" class="btn btn-default pull-left">Submit</button>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
                        <table class="table table-striped table-bordered sales-table" id="sales-table">
							<thead>
                                <tr>
                                    <th colspan=3> </th>
                                    <th colspan=4>No Effect On Stock</th>
                                    <th colspan=4>Effect On Stock</th>
                                </tr>
								<tr>
									<th>Date</th>
                                    <th>Item</th>
                                    <th>Module</th>
                                    <th>In</th>
                                    <th>In Amount</th>
                                    <th>Out</th>
                                    <th>Out Amount</th>
                                    <th>In</th>
                                    <th>In Amount</th>
                                    <th>Out</th>
                                    <th>Out Amount</th>
                                </tr>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Total : </th>
                                    <th></th>     
                                    <th></th>     
                                    <th></th>     
                                    <th></th>     
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>In - Out</th>
                                    <th colspan=2></th>     
                                    <th colspan=2></th>
                                </tr>
                            </tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<input type="hidden" name="total_net_amount" id="total_net_amount">
<script type="text/javascript">
	$(document).ready(function(){
        $("#site_id").select2({
            placeholder: " --ALL-- ",
            allowClear: true,
            width:"100%",
            ajax: {
                url: "<?= base_url('app/sites_select2_source') ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data,params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 5) < data.total_count
                        }
                    };
                },
                cache: true
            }
        });

        var title = 'Site Report (From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() +')';
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.toString().replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
			}
		};
        table = $('.sales-table').DataTable({
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
            "ordering": true,
            "searching": true,
            "aaSorting": [[1, 'desc']],
            "ajax": {
                "url": "<?php echo base_url('report/site_report_datatable')?>",
                "type": "POST",
                "data": function(d){
                    d.from_date = $("#datepicker1").val();
                    d.to_date = $("#datepicker2").val();
                    d.site_id = $("#site_id").val();
                    d.module = $("#module").val();
                },
            },
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
            "scroller": {
                "loadingIndicator": true
            },
            "columnDefs": [
                {"targets": 0, "orderable": false },
                {"className": "text-right", "targets": [0] },
                {"className": "text-right", "targets": [1] },
                {"className": "text-right", "targets": [2] },
                {"className": "text-right", "targets": [3] },
                {"className": "text-right", "targets": [4] },
                {"className": "text-right", "targets": [5] },
                {"className": "text-right", "targets": [6] },
                {"className": "text-right", "targets": [7] },
                {"className": "text-right", "targets": [8] },
                {"className": "text-right", "targets": [9] },
                {"className": "text-right", "targets": [10] }
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
                var nein = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(3).footer()).html(nein.toFixed(2));
                var neout = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(4).footer()).html(neout.toFixed(2));
                var efin = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(5).footer()).html(efin.toFixed(2));
                var efout = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(6).footer()).html(efout.toFixed(2));
                var total_ne = (nein - neout);
                $('tr:eq(1) th:eq(3)', api.table().footer()).html(total_ne.toFixed(2));
                var total_ef = (efin - efout);
                $('tr:eq(1) th:eq(4)', api.table().footer()).html(total_ef.toFixed(2));
            }
        });

        $(document).on('click','#btn_search',function(){
            table.draw();            
        });
    });
</script>
