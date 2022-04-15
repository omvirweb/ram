<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php
            if(isset($list_type) && $list_type == 2){
                echo "Sales Invoice 2";
                $add_new_url = base_url('transaction/sales_purchase_transaction/sales2');
            }else if(isset($list_type) && $list_type == 3){
                echo "Sales Invoice 3";
                $add_new_url = base_url('transaction/sales_purchase_transaction/sales3');
            }else if(isset($list_type) && $list_type == 4){
                echo "Sales Invoice 4";
                $add_new_url = base_url('transaction/sales_purchase_transaction/sales4');
            }else{
                echo "Sales Invoice";
                $add_new_url = base_url('transaction/sales_purchase_transaction/sales');
            }
            ?>
            
            <?php if ($this->applib->have_access_role(MODULE_SALES_INVOICE_ID, "add")) { ?>
                <a href="<?= $add_new_url; ?>" class="btn btn-primary pull-right">Add New</a>
            <?php } ?>

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
                                <select name="account_id" id="account_id" class="account_id" required ></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Multiple Print >>
                            <a href="javascript:void(0);" class="btn btn-info btn_print_multiple_invoice" data-print_type = "invoice_print_pdf" title="Invoice Print"><span class="fa fa-print"></span></a>
                            <a href="javascript:void(0);" class="btn btn-info btn_print_multiple_invoice" data-print_type = "format_2_invoice_print" title="Format 2 Invoice Print"><span class="fa fa-print"></span></a>
                            <a href="javascript:void(0);" class="btn btn-info btn_print_multiple_invoice" data-print_type = "format_3_invoice_print" title="Formamt 3 Invoice Print"><span class="fa fa-print"></span></a>
                            <a href="javascript:void(0);" class="btn btn-info btn_print_multiple_invoice" data-print_type = "invoice_print_new_pdf" title="Tally Invoice Print"><span class="fa fa-print"></span></a>
                            <a href="#" title="Download Google Sheet"><img src="<?php echo base_url(); ?>assets/dist/img/google_sheet_icon.png" style="width:25px;margin-left: 25px" ></a>
                            <a href="#" title="Email"> <img src="<?php echo base_url(); ?>assets/dist/img/email_icon.png" style="width:25px;margin-left: 25px" ></a>
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!---content start --->
                        <form id="form_print_multiple_invoice" class="" action="<?= base_url('sales/print_multiple_invoice') ?>" method="post" enctype="multipart/form-data"  target="_blank" >
                            <input type="hidden" name="print_type" id="print_type" value="">
                            <table class="table table-striped table-bordered product-table" id="product-table">
                                <thead>
                                    <tr>
                                        <th width="70px">Action &nbsp; &nbsp; <input type="checkbox" id="check_all" style="height:17px;width: 17px;"></th>
                                        <th width="100px">Invoice No</th>
                                        <?php if ($this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_bill_wise'] == '1') { ?>
                                            <th>Status</th>
                                        <?php } ?>
                                        <th>Account</th>
                                        <th>Invoice Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th></th>
                                        <?php if ($this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['is_bill_wise'] == '1') { ?>
                                            <th></th>
                                        <?php } ?>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
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
		initAjaxSelect2($("#account_id"),"<?=base_url('app/account_select2_source/')?>");

		var client = 'All'
                $(document).on('change','#account_id', function(){
                    client = $("#account_id option:selected").text();
                });
                
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
                columns: [1, 2, 3, 4],
			}
		};
                
		table = $('.product-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                $.extend( true, {}, buttonCommon, { extend: 'copy', footer: true,title: function () { return ('Sales Invoice For '+client+ '( From Date : ' + $('#datepicker1').val() + ' TO Date : ' + $('#datepicker2').val() + ' )')}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'csvHtml5', customize: function (csv) {
                                        return 'Sales Invoice For '+client+ '( From Date : ' + $('#datepicker1').val() + ' TO Date : ' + $('#datepicker2').val() + ' )\n\n'+  csv;
                                    }, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'pdf', orientation: 'landscape', pageSize: 'LEGAL', footer: true,title: function () { return ('Sales Invoice For '+client+ '( From Date : ' + $('#datepicker1').val() + ' TO Date : ' + $('#datepicker2').val() + ' )')}, action: newExportAction,
                    customize : function(doc){
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        doc.content[1].layout = objLayout;
                        doc.content[1].table.widths = ["25%", "25%", "25%", "25%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
                        var rowCount = document.getElementById("product-table").rows.length;

                        for (i = 1; i < rowCount; i++) {
                                doc.content[1].table.body[i][0].alignment = 'right';
                                doc.content[1].table.body[i][3].alignment = 'right';
                        };
                    }
                } ),
                $.extend( true, {}, buttonCommon, { extend: 'excelHtml5', footer: true,title: function () { return ('Sales Invoice For '+client+ '( From Date : ' + $('#datepicker1').val() + ' TO Date : ' + $('#datepicker2').val() + ' )')}, action: newExportAction, 
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
                } ),
                $.extend( true, {}, buttonCommon, { extend: 'print', footer: true,title: function () { return ('Sales Invoice For '+client+ '( From Date : ' + $('#datepicker1').val() + ' TO Date : ' + $('#datepicker2').val() + ' )')},
                    customize : function(win){
                        $(win.document.body).find('table thead th:nth-child(4)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(4)').css('text-align', 'right');
                        $(win.document.body).find('table tfoot th:nth-child(4)').css('text-align', 'right');
                    }, orientation: 'landscape', action: newExportAction } ),
            ],
			"serverSide": true,
			"ordering": true,
			"searching": true,
			"aaSorting": [[1, 'desc']],
			"ajax": {
				"url": "<?php echo base_url('sales/invoice_datatable')?>",
				"type": "POST",
				"data": function(d){
					d.daterange_1 = datepicker1;
					d.daterange_2 = datepicker2;
					d.account_id = $("#account_id").val();
                    d.list_type = "<?php echo (isset($list_type) && $list_type != '') ? $list_type : 0;  ?>"
				},
			},
			"scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
			"scroller": {
				"loadingIndicator": true
			},
			"columnDefs": [
				{"targets": 0, "orderable": false },
				{ className: "text-right", "targets": [4] },
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
                <?php if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['is_bill_wise'] == '1'){ ?>
                var total_amt = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(5).footer()).html(total_amt.toFixed(2));
                <?php } else { ?>
            	var total_amt = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                $(api.column(4).footer()).html(total_amt.toFixed(2));
                <?php } ?>
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

		$(document).on("click",".delete_button",function(){
			var value = confirm('Are you sure delete this Invoice?');
			var tr = $(this).closest("tr");
			if(value){
				$.ajax({
					url: $(this).data('href'),
					type: "POST",
					data: 'id_name=sales_invoice_id&table_name=sales_invoice',
					success: function(data){
						table.draw();
						show_notify('Invoice Deleted Successfully!',true);
					}
				});
			}
		});
                
                
            $(document).on("change","#check_all", function () {
                if($(this).is(":checked")) {
                    $("[name='invoice_ids[]']").prop('checked',true);
                } else {
                   $("[name='invoice_ids[]']").prop('checked',false);
                }
            });

            $(document).on("click", ".btn_print_multiple_invoice", function () {
                if($("[name='invoice_ids[]']:checked").length == 0) {
                    show_notify('Please Select Invoice!', false);
                } else {
                    $("#print_type").val($(this).data('print_type'));
                    $("#form_print_multiple_invoice").submit();
                }
            });

            $('#form_print_multiple_invoice').on('submit', function(e){
               var $form = $(this);
               // Iterate over all checkboxes in the table
               table.$('input[type="checkbox"]').each(function(){
                  // If checkbox doesn't exist in DOM
                  if(!$.contains(document, this)){
                     // If checkbox is checked
                     if(this.checked){
                        // Create a hidden element 
                        $form.append(
                           $('<input>')
                              .attr('type', 'hidden')
                              .attr('name', this.name)
                              .val(this.value)
                        );
                     }
                  } 
               });          
            });
        
	});
</script>
