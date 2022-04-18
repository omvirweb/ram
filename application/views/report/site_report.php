<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Trial Balance</h1>
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
						<table class="table table-striped table-bordered" id="site_report_table">
							<thead>
                                <tr>
                                    <th colspan=3> </th>
                                    <th colspan=2>No Effect On Stock</th>
                                    <th colspan=3>Effect On Stock</th>
                                </tr>
								<tr>
									<th>Date</th>
                                    <th>Item</th>
                                    <th>Module</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>In</th>
                                    <th>Out</th>
                                </tr>
							</thead>
							<tbody>
							</tbody>
							<!-- <tfoot>
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
                                    <th colspan=2 id="no_effect_in_out"></th>     
                                    <th colspan=2 id="effect_in_out"></th>
                                    <th></th>     
                                    <th></th>     
                                    <th></th>     
                                </tr>
                            </tfoot> -->
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


    var title = 'Trial Balance (From Date : ' + $('#datepicker1').val() + ' To Date : ' + $('#datepicker2').val() +')';

    // var buttonCommon = {
    //     exportOptions: {
    //         format: { body: function ( data, row, column, node ) { return data.toString().replace(/(&nbsp;|<([^>]+)>)/ig, ""); } },
    //         columns: [0, 1, 2, 3, 4, 5, 6, 7],
    //     }
    // };

	table = $('#site_report_table').DataTable({
            "serverSide": true,
            "ordering": false,
            "searching": false,
            "bInfo" : false,
            "ajax": {
                "url": "<?php echo base_url('report/site_report_datatable')?>",
                "type": "POST",
                "data": function(d){
                	d.from_date = $("#datepicker1").val();
                	d.to_date = $("#datepicker2").val();
                    d.site_id = $("#site_id").val();
                    d.module = $("#module").val();
                },
                'columns': [
                        {data: 'date', name: 'date'},
                        {data: 'item_name', name: 'item_name'},
                        {data: 'module', name: 'module'},
                        {data: 'no_effect_in', name: 'no_effect_in'},
                        {data: 'no_effect_out', name: 'no_effect_out'},
                        {data: 'effect_in', name: 'effect_in'},
                        {data: 'effect_out', name: 'effect_out'}
                    ]
                
            },
            
            // "footerCallback": function ( row, data, start, end, display ) {
            //     var api = this.api(), data;
            //     console.log(data)
            //     // $( api.column( 3 ).footer() ).html(data[4]);
            //     // $( api.column( 4 ).footer() ).html(data);
            //     // $( api.column( 5 ).footer() ).html(data);
            //     // $( api.column( 6 ).footer() ).html(data);
            // }
        });  

        table.draw(); 
	});

	$(document).on('click','#btn_search',function(){
        table.draw();            
    });
</script>
