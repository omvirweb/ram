<?php $this->load->view('success_false_notify'); 
$li_discount = isset($this->session->userdata()['li_discount']) ? $this->session->userdata()['li_discount'] : '0';
$li_igst_per = isset($this->session->userdata()['li_igst_per']) ? $this->session->userdata()['li_igst_per'] : '0';
$li_igst_amt = isset($this->session->userdata()['li_igst_amt']) ? $this->session->userdata()['li_igst_amt'] : '0';
$li_cgst_per = isset($this->session->userdata()['li_cgst_per']) ? $this->session->userdata()['li_cgst_per'] : '0';
$li_cgst_amt = isset($this->session->userdata()['li_cgst_amt']) ? $this->session->userdata()['li_cgst_amt'] : '0';
$li_sgst_per = isset($this->session->userdata()['li_sgst_per']) ? $this->session->userdata()['li_sgst_per'] : '0';
$li_sgst_amt = isset($this->session->userdata()['li_sgst_amt']) ? $this->session->userdata()['li_sgst_amt'] : '0';
$li_amount = isset($this->session->userdata()['li_amount']) ? $this->session->userdata()['li_amount'] : '0';
$li_other_charges = isset($this->session->userdata()['li_other_charges']) ? $this->session->userdata()['li_other_charges'] : '0';
$li_basic_amount = isset($this->session->userdata()['li_basic_amount']) ? $this->session->userdata()['li_basic_amount'] : '0';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Sales Bill Register </h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<form id="sales_export" method="post" action="<?=base_url('report/sales_export') ?>" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
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
									<select name="account_id" id="account_id" class="account_id" ></select>
								</div>
								<div class="col-md-6">
									<button type="submit" class="btn btn-primary pull-right form_btn" style="margin-right:5px;" >Export Data</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-body">
						<!---content start --->
						<table class="table table-striped table-bordered sales-table" id="sales-table">
							<thead>
								<tr>
									<th width="70px">Sr. No.</th>
									<th>Date</th>
									<th>Inv.No.</th>
									<th>Prdocuts Name</th>
									<th>HSN Code</th>
									<th>Account</th>
									<th>GSTN No.</th>
									<th>Basic Amount</th>
									<th>Charges</th>
									<th>CGST Amount</th>
									<th>SGST Amount</th>
									<th>IGST Amount</th>
									<th>Other Charges</th>
									<th>Grand Total</th>
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

		//~ $(document).on('submit', '#sales_export', function () {
		//~ $('.overlay').show();
		//~ var postData = new FormData(this);
		//~ $.ajax({
		//~ url: "<?=base_url('report/sales_export') ?>",
		//~ type: "POST",
		//~ processData: false,
		//~ contentType: false,
		//~ cache: false,
		//~ data: postData,
		//~ success: function (response) {
		//~ var json = $.parseJSON(response);
		//~ if (json['success'] == 'Updated'){
		//~ window.location.href = "<?php echo base_url('gstr1_excel') ?>";
		//~ }
		//~ if (json['error'] == 'Error'){
		//~ show_notify("Please select Date.", false);
		//~ }
		//~ $('.overlay').hide();
		//~ return false;
		//~ },
		//~ });
		//~ return false;
		//~ });

        var client = 'All'
        $(document).on('change','#account_id', function(){
            client = $("#account_id option:selected").text();
        });
        
        var title = 'Sales Register For  '+client+ '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )';
        
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.toString().replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
//                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                columns: ':visible',
			}
		};
        
		table = $('.sales-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                $.extend( true, {}, buttonCommon, { extend: 'copy', title: function () { return (title)}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'csvHtml5', title: function () { return (title)}, action: newExportAction, customize: function (csv) {
                                            return 'Sales Register For '+client+ '( From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() + ' )\n\n'+  csv;
                                        } } ),
                $.extend( true, {}, buttonCommon, { extend: 'pdf', orientation: 'landscape', pageSize: 'LEGAL', title: function () { return (title)}, action: newExportAction,
                    customize : function(doc){
						var objLayout = {};
						objLayout['hLineWidth'] = function(i) { return .5; };
						objLayout['vLineWidth'] = function(i) { return .5; };
						doc.content[1].layout = objLayout;
						doc.content[1].table.widths = ["2%", "10%", "3%", "10%", "5%", "10%", "5%", "10%", "10%", "10%", "5%", "5%", "5%", "8%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
						var rowCount = document.getElementById("sales-table").rows.length;

						for (i = 1; i < rowCount; i++) {
								doc.content[1].table.body[i][0].alignment = 'right';
								doc.content[1].table.body[i][2].alignment = 'right';
                                <?php if($li_basic_amount == '1') { ?>
                                    doc.content[1].table.body[i][7].alignment = 'right';
                                <?php } ?>
								doc.content[1].table.body[i][8].alignment = 'right';
                                <?php if($li_cgst_amt == '1') { ?>
                                    doc.content[1].table.body[i][9].alignment = 'right';
                                <?php } ?>
                                <?php if($li_sgst_amt == '1') { ?>
                                    doc.content[1].table.body[i][10].alignment = 'right';
                                <?php } ?>
                                <?php if($li_igst_amt == '1') { ?>
                                    doc.content[1].table.body[i][11].alignment = 'right';
                                <?php } ?>
                                <?php if($li_other_charges == '1') { ?>
                                    doc.content[1].table.body[i][12].alignment = 'right';
                                <?php } ?>
								doc.content[1].table.body[i][rowCount].alignment = 'right';
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
                        $(win.document.body).find('table thead th:nth-child(8)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(8)').css('text-align', 'right');
                        $(win.document.body).find('table thead th:nth-child(9)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(9)').css('text-align', 'right');
                    }, action: newExportAction } ),
            ],
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'desc']],
			"ajax": {
				"url": "<?php echo base_url('report/sales_datatable')?>",
				"type": "POST",
				"data": function(d){
					d.daterange_1 = datepicker1;
					d.daterange_2 = datepicker2;
					d.account_id = $("#account_id").val();
				},
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
            "scrollX": true,
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{"targets": 0, "orderable": false },
				{"className": "text-right", "targets": [7] },
				{"className": "text-right", "targets": [8] },
				{"className": "text-right", "targets": [9] },
				{"className": "text-right", "targets": [10] },
				{"className": "text-right", "targets": [11] },
				{"className": "text-right", "targets": [12] },
				{"className": "text-right", "targets": [13] },
                <?php if($li_basic_amount == '0') { ?>
                    {"targets": [ 7 ],"visible": false },
                <?php } ?>
                <?php if($li_cgst_amt == '0') { ?>
                    {"targets": [ 9 ],"visible": false },
                <?php } ?>
                <?php if($li_sgst_amt == '0') { ?>
                    {"targets": [ 10 ],"visible": false },
                <?php } ?>
                <?php if($li_igst_amt == '0') { ?>
                    {"targets": [ 11 ],"visible": false },
                <?php } ?>
                <?php if($li_other_charges == '0') { ?>
                    {"targets": [ 12 ],"visible": false },
                <?php } ?>
			]
		});
        <?php if($li_other_charges == '0') { ?>
            table.columns([12]).visible(false);
        <?php } ?>
		
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
