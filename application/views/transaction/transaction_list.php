<?php $this->load->view('success_false_notify'); ?>
<?php $segment2 = $this->uri->segment(2); ?>
<?php
$page_parameter = '';
$page_title = '';
if ($segment2 == 'receipt_list') {
    $page_parameter = 'receipt_list';
    $page_title = 'Receipt';
} else if ($segment2 == 'payment_list') {
    $page_parameter = 'payment_list';
    $page_title = 'Payment';
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="box-title">
            <?php if ($segment2 == 'payment_list') { ?>
                Payment List
            <?php } else { ?>   
                Receipt List
            <?php } ?> 
       
            <?php if ($segment2 == 'payment_list') { ?>
                <?php if($this->applib->have_access_role(MODULE_PAYMENT_ID,"add")) { ?>
                <a href="<?= base_url('transaction/payment'); ?>" class="btn btn-primary pull-right">Add New</a>
                <?php } ?>
                
            <?php } else { ?>
                <?php if($this->applib->have_access_role(MODULE_RECEIPT_ID,"add")) { ?>
                <a href="<?= base_url('transaction/receipt'); ?>" class="btn btn-primary pull-right">Add New</a>
                <?php } ?>                
            <?php } ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                 <div class="row">
                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="btn btn-primary btn_print_multiple_transaction">Print</a>
                        <br/>
                        <br/>
                    </div>
                    <div class="col-md-12">
                        <form id="form_print_multiple_transaction" class="" action="<?= base_url('transaction/print_multiple_transaction') ?>" method="post" enctype="multipart/form-data"  target="_blank" >
                            <input type="hidden" name="transaction_type" value="<?=$transaction_type?>">
                        
                            <table class="table table-striped table-bordered transaction-table" id="transaction-table">
                                <thead>
                                    <tr>
                                        <th>Action &nbsp; &nbsp; <input type="checkbox" id="check_all" style="height:17px;width: 17px;"></th>
                                        <th>Date</th>
                                        <th>Account</th>
                                        <th>Bank / Cash</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ALERTS AND CALLOUTS -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        <?php if ($segment2 == 'payment_list') { ?>
                var title = "Payment List";
            <?php } else { ?>   
                var title = "Receipt List";
            <?php } ?> 
                
        var buttonCommon = {
			exportOptions: {
				format: { body: function ( data, row, column, node ) { return data.replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
                columns: [1, 2, 3, 4],
			}
		};
                
        table = $('.transaction-table').DataTable({
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
						doc.content[1].table.widths = ["25%", "25%", "25%", "25%"]; //costringe le colonne ad occupare un dato spazio per gestire il baco del 100% width che non si concretizza mai
						var rowCount = document.getElementById("transaction-table").rows.length;

						for (i = 1; i < rowCount; i++) {
								doc.content[1].table.body[i][3].alignment = 'right';
						};
					}
                } ),
                $.extend( true, {}, buttonCommon, { extend: 'excelHtml5', title: function () { return (title)}, action: newExportAction } ),
                $.extend( true, {}, buttonCommon, { extend: 'print',  title: function () { return (title)},
                    customize : function(win){
                        $(win.document.body).find('table thead th:nth-child(4)').css('text-align', 'right');
                        $(win.document.body).find('table tbody td:nth-child(4)').css('text-align', 'right');
                    }, action: newExportAction } ),
            ],
            "serverSide": false,
            "ordering": true,
            "searching": true,
            "order": [],
            "ajax": {
                "url": "<?php if ($segment2 == 'payment_list') {
                            echo base_url('transaction/payment_datatable');
                        } else {
                            echo base_url('transaction/receipt_datatable');
                        } ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [4],
            },
            {"targets": 0,"orderable": false}
            ],
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT; ?>',
            "scroller": {
                "loadingIndicator": true
            },
        });

        $(document).on("change","#check_all", function () {
            if($(this).is(":checked")) {
                $("[name='transaction_ids[]']").prop('checked',true);
            } else {
               $("[name='transaction_ids[]']").prop('checked',false);
            }
        });

        $(document).on("click", ".btn_print_multiple_transaction", function () {
            if($("[name='transaction_ids[]']:checked").length == 0) {
                show_notify('Please Select Transaction!', false);
            } else {
                /*var transaction_ids = [];
                $("[name='transaction_ids[]']:checked").each(function(index,element){
                    transaction_ids.push($(this).val());
                });*/
                $("#form_print_multiple_transaction").submit();
            }
        });

        $('#form_print_multiple_transaction').on('submit', function(e){
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
