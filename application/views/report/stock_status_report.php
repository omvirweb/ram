<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Stock Status Report</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="form-inline">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>As On Date</label>
                                    <input type="text" name="daterange_1" id="datepicker1" class="form-control" placeholder="dd-mm-yyyy" value="<?= date("d-m-Y"); ?>">
                                </div>
                                <button type="button" id="search_btn" class="btn btn-default">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table table-striped table-bordered stock-table" id="stock_table">
                            <thead>
<!--                                <tr>
                                    <th width="70px"></th>
                                    <th></th>
                                    <th class="text-center" colspan="3">Qty</th>
                                    <th class="text-center" colspan="3">Amount</th>
                                </tr>-->
                                <tr>
                                    <th width="70px">Sr. No.</th>
                                    <th>Item Name</th>
                                    <th>In Stock</th>
                                    <th>In WIP</th>
                                    <th>As Work Done</th>
<!--                                    <th>In Stock</th>
                                    <th>In WIP</th>
                                    <th>As Work Done</th>-->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
<!--                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>-->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function () {
        
        table = $('#stock_table').DataTable({
            "serverSide": true,
            "ordering": false,
            "searching": false,
            "bInfo" : false,
//            "order": [[0, 'asc']],
            "ajax": {
                "url": "<?php echo base_url('report/stock_status_datatable')?>",
                "type": "POST",
                "data": function(d){
                    d.upto_date = $('#datepicker1').val();
                },
            },
             "scroller": {
                "loadingIndicator": true
            },
            "scrollY": '<?php echo MASTER_LIST_TABLE_HEIGHT;?>',
            "columnDefs": [
                {"className": "text-right", "targets": [2,3,4] },
            ]
        });
        
        $(document).on('click','#search_btn',function(){
            table.draw();
        });
        
    });
</script>
