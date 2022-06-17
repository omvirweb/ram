<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Site Wise Expenses Summary</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<div class="form-inline">
							<div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group" style="width:100%">
                                            <label for="site_id" class="control-label"  style="margin-bottom: 3px;">Site</label>
                                            <select name="line_items_data[site_id]" id="site_id" value="" class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>From Date : </label><br>
                                            <input type="text" name="from_date" id="datepicker1" class="form-control" value="<?php echo isset($from_date) ? date('d-m-Y',strtotime($from_date)) : date('d-m-Y',strtotime("-1 year"));  ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>To Date : </label><br>
                                            <input type="text" name="to_date" id="datepicker2" class="form-control" value="<?php echo isset($to_date) ? date('d-m-Y',strtotime($to_date)) :  date('d-m-Y'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group col-md-12">
                                            <label for="account_id">Account</label>
                                            <select name="account_id" id="account_id" class="account_id" required ></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="padding: 5px;">
                                        <input type="hidden" name="" id="table_draw" value="0">
                                        <br><button type="button" id="btn_search" class="btn btn-default pull-left">Submit</button>
                                    </div>
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
									<th>Purchase Invoice</th>
                                    <th>Payment</th>
                                    <th>Receipt</th>
                                    <th>Sales Invoice</th>
                                </tr>
							</thead>
							<tbody>
							</tbody>
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
        initAjaxSelect2($("#account_id"), "<?= base_url('app/account_select2_source_bill_wise/') ?>");
        <?php if (isset($account_id) && !empty($account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_select2_val_by_id/' . $account_id) ?>");
        <?php } ?>

        $("#site_id").select2({
            placeholder:'--All--',
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




        var title = 'Site Wise Expenses Summary (From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() +')';
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
						doc.content[1].table.widths = ["25%","25%","25%", "25%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
						var rowCount = document.getElementById("stock-table").rows.length;

						for (i = 1; i < rowCount; i++) {
								doc.content[1].table.body[i][0].alignment = 'center';
								doc.content[1].table.body[i][1].alignment = 'center';
								doc.content[1].table.body[i][2].alignment = 'center';
								doc.content[1].table.body[i][3].alignment = 'center';
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
                        // var r2 = Addrow(2, [{ k: 'A', v: 'From :' }, { k: 'B', v: $('#datepicker1').val() }]);
                        // var r3 = Addrow(3, [{ k: 'A', v: 'To :' }, { k: 'B', v: $('#datepicker2').val() }]);

                        // sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3  + sheet.childNodes[0].childNodes[1].innerHTML;
                    }
                }),
                $.extend( true, {}, buttonCommon, { extend: 'print',  title: function () { return (title)},
                    customize : function(win){
                        $(win.document.body).find('table thead th:nth-child(0)').css('text-align', 'center');
                        $(win.document.body).find('table tbody td:nth-child(0)').css('text-align', 'center');

                        $(win.document.body).find('table thead th:nth-child(1)').css('text-align', 'center');
                        $(win.document.body).find('table tbody td:nth-child(1)').css('text-align', 'center');
                        
                        $(win.document.body).find('table thead th:nth-child(2)').css('text-align', 'center');
                        $(win.document.body).find('table tbody td:nth-child(2)').css('text-align', 'center');
                        
                        $(win.document.body).find('table thead th:nth-child(3)').css('text-align', 'center');
                        $(win.document.body).find('table tbody td:nth-child(3)').css('text-align', 'center');
                        
                    }, action: newExportAction } ),
            ],
            "serverSide": true,
            "ordering": true,
            "searching": true,
            "aaSorting": [[1, 'desc']],
            "ajax": {
                "url": "<?php echo base_url('report/site_wise_expenses_summary_datatable')?>",
                "type": "POST",
                "data": function(d){
                    d.from_date = $("#datepicker1").val();
                    d.to_date = $("#datepicker2").val();
                    d.site_id = $("#site_id").val();
                    d.account_id = $("#account_id").val();
                },
            },
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
            "scroller": {
                "loadingIndicator": true
            },
            "columnDefs": [
                {"targets": 0, "orderable": false },
                {"className": "text-center", "targets": [0] },
                {"className": "text-center", "targets": [1] },
                {"className": "text-center", "targets": [2] },
                {"className": "text-center", "targets": [3] },
            ],
        });

        $(document).on('change','#site_id',function(){
            if($(this).val() != "")
            {
                table.draw();            
            }
        });
        $(document).on('click','#btn_search',function(){
            if($('#site_id').val() != "")
            {
                table.draw();            
            }
        });
        $(document).on('click','.go_to',function(){
            var site_id = $(this).data('site_id');
            var url = "<?php echo base_url().'report/site_report/'; ?>"+site_id;
            window.location.replace(url);
        })
    });
</script>
<style>
    .dataTables_filter, .dataTables_info { display: none; }
</style>

