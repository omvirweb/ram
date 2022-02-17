<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Balance Sheet - CTRL + F7</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-body">
						<div class="form-inline">
							<div class="col-md-12">
                                <div class="col-md-3">
									<div class="form-group">
                                        <label>From Date : </label><br>
                                        <input type="text" name="from_date" id="datepicker1" class="form-control" value="<?php echo date('d-m-Y',strtotime($from_date)) ?>">
									</div>
                                </div>
                                <div class="col-md-3">
									<div class="form-group">
                                        <label>To Date : </label><br>
                                        <input type="text" name="to_date" id="datepicker2" class="form-control" value="<?php echo date('d-m-Y',strtotime($to_date)) ?>">
									</div>
                                </div>
                                <div class="col-md-3">
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
                        <div class="row">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-10">
                                <div class="col-md-12 text-center">
                                    <div class="dt-buttons">
                                        <a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="balance_sheet_table" href="<?=base_url('report/balance_sheet_print')?>"  title="PDF" style="border: none;" target="_blank" ><img src="<?=base_url('assets/dist/img/pdf_icon.png')?>" style="width:25px;" alt="PDF" title="PDF"></a>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <h2><strong><?=$company_row->user_name;?></strong></h2>
                                    <p><?=$company_row->address;?></p>
                                </div>
                                <div class="col-md-12">
                                    <h4><strong>Balance Sheet</strong></h4>
                                    <h5 class="balance_sheet_date_range"><strong>From <?php echo date('d-m-Y',strtotime($from_date)) ?> To <?php echo date('d-m-Y',strtotime($to_date)) ?></strong></h5>
                                </div>
                                <div class="col-md-12">
                                    <table class="tblnavigate" id="balance_sheet_table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" style="min-width: 50%;">&nbsp;</th>
                                                <th colspan="2" style="min-width: 50%;">&nbsp;</th>
                                            </tr>
                                            <tr style="border-top: solid 1px;border-bottom: solid 1px;">
                                                <th class="text-right" style="padding-right: 5px;">Amount</th>
                                                <th style="padding-left: 15px;">Particulars</th>
                                                <th class="text-right" style="padding-right: 5px;">Amount</th>
                                                <th style="padding-left: 15px;">Particulars</th>
                                            </tr>
                                        </thead>
                                        <tbody class="balance_sheet_tbody"></tbody>
                                        <tfoot>
                                            <tr style="border-top: solid 1px;border-bottom: solid 1px;">
                                                <th class="total_net_amount text-right"></th>
                                                <th></th>
                                                <th class="total_net_amount text-right"></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<input type="hidden" name="total_net_amount" id="total_net_amount">
<script type="text/javascript">
    var active = 0;
	$(document).ready(function(){
        lod_balance_sheet_data();
        $(document).on('click','#btn_search',function(){
            lod_balance_sheet_data();
        });

        
        //$('.tblnavigate td').each(function(idx){$(this).html(idx);});
        rePosition();

        $(document).keydown(function(e) {
            var inp = String.fromCharCode(e.keyCode);
            if ((!(/[a-zA-Z0-9-_ ]/.test(inp) || e.keyCode == 96)) && e.keyCode != 8){
              reCalculate(e);
              rePosition();
              if (e.keyCode > 36 && e.keyCode < 41) {
                return false;
              }
            }
        });
        
        $('td').click(function() {
            active = $('table td').index(this);
            rePosition();
        });


    

    
        /*$(document).keydown(function(e) {
            switch(e.which) {
                case 37: // left
                break;

                case 38: // up
                if($("tr.selected").length > 0) {
                    var cur_tr = $("tr.selected");
                    if(cur_tr.prev('tr').length > 0) {
                        cur_tr.prev('tr').addClass('selected');
                        cur_tr.removeClass('selected')    
                    }                    
                } else {
                    $(".balance_sheet_tbody tr:first").addClass('selected');
                }
                break;

                case 39: // right
                break;

                case 40: // down
                if($("tr.selected").length > 0) {
                    var cur_tr = $("tr.selected");
                    if(cur_tr.next('tr').length > 0) {
                        cur_tr.next('tr').addClass('selected').focus();
                        cur_tr.removeClass('selected')    
                    }                    
                } else {
                    $(".balance_sheet_tbody tr:first").addClass('selected');
                }
                break;

                default: return; // exit this handler for other keys
            }
            //e.preventDefault(); // prevent the default action (scroll / move caret)
        });*/
	});

    function reCalculate(e) {
        var rows = $('.tblnavigate tbody tr').length;
        var columns = $('.tblnavigate tbody tr:eq(0) td').length;
        var temp;

        if (e.keyCode == 37) { //move left or wrap
            temp = active;
            while (temp > 0) {
                temp = temp - 1;
                // only advance if there is an input field in the td
                active = temp;
                break;
            }
        }
        if (e.keyCode == 38) { // move up
            temp = active;
            while (temp - columns >= 0) {
                temp = temp - columns;
                // only advance if there is an input field in the td
                active = temp;
                break;
            }
        }
        if (e.keyCode == 39) { // move right or wrap
            temp = active;
            while (temp < (columns * rows) - 1) {
                temp = temp + 1;
                // only advance if there is an input field in the td
                active = temp;
                break;
            }
        }
        if (e.keyCode == 40) { // move down
            temp = active;
            while (temp + columns <= (rows * columns) - 1) {
                temp = temp + columns;
                // only advance if there is an input field in the td
                active = temp;
                break;
            }
        }
    }

    function rePosition() {
        console.log(active);
        $('.active').removeClass('active');
        $('.tblnavigate tbody tr td').eq(active).addClass('active');
        scrollInView();
    }

    function scrollInView() {
        var target = $('.tblnavigate tbody tr td:eq(' + active + ')');
        if (target.length) {
            var top = target.offset().top;
            $('html,body').stop().animate({
                scrollTop: top - 100
            }, 400);
            return false;
        }
    }

    function lod_balance_sheet_data()
    {
        $("#ajax-loader").show();
        var postData = new FormData();
        postData.append('from_date',$("#datepicker1").val());
        postData.append('to_date',$("#datepicker2").val());
        $.ajax({
            url: "<?= base_url('report/balance_sheet_new_datatable') ?>",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            data: postData,
            dataType: 'json',
            success: function (res) {
                $("#ajax-loader").hide();
                var cr_account_arr = res.cr_account_arr;
                var dr_account_arr = res.dr_account_arr;

                var row_html = '';
                $.each(cr_account_arr,function(index,row){
                    row_html += '<tr>';
                        if(typeof(cr_account_arr[index].is_group_total) !== "undefined") {
                            row_html += '<td class="text-right" style="border-top: solid 1px;padding-right: 5px;">'+cr_account_arr[index].amount+'</td>';
                        } else {
                            row_html += '<td class="text-right" style="padding-right: 5px;">'+cr_account_arr[index].amount+'</td>';    
                        }                        
                        row_html += '<td style="padding-left: 15px;">'+cr_account_arr[index].particular+'</td>';

                        if(typeof(dr_account_arr[index].is_group_total) !== "undefined") {
                            row_html += '<td class="text-right" style="border-top: solid 1px;padding-right: 5px;">'+dr_account_arr[index].amount+'</td>';
                        } else {
                            row_html += '<td style="padding-right: 5px;" class="text-right">'+dr_account_arr[index].amount+'</td>';    
                        }
                        row_html += '<td style="padding-left: 15px;">'+dr_account_arr[index].particular+'</td>';

                    row_html += '</tr>';
                });
                $(".balance_sheet_tbody").html(row_html);

                $(".total_net_amount").html(res.total_net_amount);
                $(".balance_sheet_date_range").html(res.date_range);

            }
        });
    }
</script>
