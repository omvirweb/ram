<?php
    $segment1 = $this->uri->segment(1);
?>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <?php $package_name = $this->crud->get_column_value_by_id('settings', 'setting_value', array('setting_key' => 'package_name')); ?>
    <strong>Copyright &copy; <a href="http://jewelbook.in/">Jewel Book</a>.</strong>
</footer>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets/plugins/fastclick/fastclick.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/dist/js/app.min.js');?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('assets/plugins/sparkline/jquery.sparkline.min.js');?>"></script>
<!-- jvectormap -->
<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js');?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/dist/js/demo.js');?>"></script>
<!-- keyboard shortcode key -->
<script src="<?=base_url();?>assets/dist/js/shortcut.js"></script>
<!-- AdminLTE JS file -->
<script src="<?php echo base_url('assets/dist/js/adminlte.min.js?v=1');?>"></script>
<!-------- /Parsleyjs --------->
<script src="<?= base_url('assets/plugins/parsleyjs/dist/parsley.min.js');?>"></script>

<!-- notify -->
<script src="<?php echo base_url('assets/plugins/notify/jquery.growl.js');?>"></script>
<!-- DataTables -->
<script src="<?=base_url('assets/plugins/datatables/media/js/jquery.dataTables.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/buttons.flash.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/buttons.print.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/jszip.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/pdfmake.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Buttons/js/vfs_fonts.js');?>"></script>
<!-- datepicker -->
<script src="<?=base_url('assets/plugins/datepicker/bootstrap-datepicker.js?v=1');?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
<!-- date-range-picker -->
<script src="<?=base_url('assets/plugins/moment/min/moment.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js');?>"></script>

<script type="text/javascript">
    var go_to_back_page = true;
    window.onkeyup = function(e) {
        if (e.keyCode == 27) {
            if(go_to_back_page ==  true) {
                if(window.opener === null) {
                    window.history.back();     
                } else {
                    window.close();
                }
            } else {
                go_to_back_page = true;
            }
        }

        if (e.keyCode == 39) {
            if($('li.dropdown.open').length > 0) {
                $('li.dropdown.open').next().focus();
                $("li.dropdown").removeClass('open');
                $("li.dropdown:focus").addClass('open');
            }

        } else if (e.keyCode == 37) {      
            if($('li.dropdown.open').length > 0) {
                $('li.dropdown.open').prev().focus();
                $("li.dropdown").removeClass('open');
                $("li.dropdown:focus").addClass('open');
            }
        } else if (e.keyCode == 40) {      
            if($('li.dropdown.open').length > 0) {
                if(!$('li.dropdown.open li a').is(":focus")) {
                    $('li.dropdown.open li a:first').focus();
                }
            }
                        
        }
    }

    $(document).ready(function(){
        shortcut.add("Ctrl+m",function() {
            if($('li.dropdown.open').length > 0) {
                $('li.dropdown.open').blur();
                $('li.dropdown.open').removeClass('open');
            } else {
                $("#navbar-collapse li.dropdown:first-child").focus();
                $("li.dropdown").removeClass('open');
                $("#navbar-collapse li.dropdown:first-child").addClass('open');
            }
            
        });
        shortcut.add("Ctrl+F1",function() {
            window.location.href = "<?php echo base_url() ?>sales/invoice/";
        });
        shortcut.add("Ctrl+F2",function() {
            window.location.href = "<?php echo base_url() ?>credit_note/add/";
        });
        shortcut.add("Ctrl+F3",function() {
            window.location.href = "<?php echo base_url() ?>report/summary/";
        });
        shortcut.add("Ctrl+F7",function() {
            window.location.href = "<?php echo base_url() ?>report/balance_sheet_new/";
        });
        shortcut.add("Ctrl+F5",function() {
            window.location.href = "<?php echo base_url() ?>transaction/receipt/";
        });
        shortcut.add("Ctrl+F6",function() {
            window.location.href = "<?php echo base_url() ?>transaction/payment/";
        });
        shortcut.add("Ctrl+F8",function() {
            window.location.href = "<?php echo base_url() ?>contra/contra/";
        });
        $('.selectSearch').select2({
            width:"100%",
            minimumResultsForSearch: -1
        });
        $(document).on('focusout', '.selectSearch', function(){
            $(this).select2('close');
        });
        $('form').parsley();
        
        $(document).on('input',".num_only",function(){
			this.value = this.value.replace(/[^\d\.\-]/g,'');
		});
        
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
              e.preventDefault();
              return false;
            }
        });
        
        $('form').on('keydown', 'input,select', function (event) {
            if (event.which == 13) {
                event.preventDefault();
                if($(this).hasClass('add_lineitem')){
                    $(this).click();
                    <?php if(isset($invoice_line_item_fields) && in_array('item_group', $invoice_line_item_fields)){ ?>
                        $('[data-index="26"]').select2('open');
                    <?php } else if(isset($invoice_line_item_fields) && in_array('category', $invoice_line_item_fields)){ ?>
                        $('[data-index="27"]').select2('open');
                    <?php } else if(isset($invoice_line_item_fields) && in_array('sub_category', $invoice_line_item_fields)){ ?>
                        $('[data-index="28"]').select2('open');
                    <?php } else { ?>
                        $('[data-index="29"]').select2('open');
                    <?php } ?>
                } else {
                    event.preventDefault();
                    var $this = $(event.target);
                    var index = parseFloat($this.attr('data-index'));
                    var segment1 = "<?php echo $segment1; ?>";
                    if((segment1 == 'purchase' || segment1 == 'credit_note' || segment1 == 'debit_note')  && $(this).attr('id') == 'amount'){
                        $('[data-index="' + (index + 2).toString() + '"]').focus();
                    } else {
                        $('[data-index="' + (index + 1).toString() + '"]').focus();
                    }
                }
            }
        });

        $('#datepicker1,#datepicker3,.datepicker').datepicker({  // ,#datepicker2
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            autoclose: true,
            constrainInput: false
        });
        
        

        $(document).on('focus',"#datepicker1,#datepicker2,#datepicker3,.datepicker",function () {
           $(this).select();
        });

        $('#datepicker1,#datepicker2,#datepicker3,.datepicker').on('show', function(e){
            if ( e.date ) {
                 $(this).data('stickyDate', e.date);
            }
            else {
                 $(this).data('stickyDate', null);
            }
        });

        $('#datepicker1,#datepicker2,#datepicker3,.datepicker').on('hide', function(e){
            var stickyDate = $(this).data('stickyDate');
            if ( !e.date && stickyDate ) {
                $(this).datepicker('setDate', stickyDate);
                $(this).data('stickyDate', null);
            }
        });
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        })
        
        $(document).on('click', '.add_account_link', function (e) {
			var myUrl = $(this).attr('data-url');
			e.preventDefault();
			window.open(myUrl, 'add_account', 'width=700,height=600,_blank');
		});
		
        $(document).on('click', '.add_item_link', function (e) {
			var myUrl = $(this).attr('data-url');
			e.preventDefault();
			window.open(myUrl, 'add_item', 'width=700,height=600,_blank');
		});
        
        data_table_export_icon_style();

        $('body').on('keydown', 'input,select,.select2-search__field, textarea', function(e) {
            var self = $(this)
              , form = self.parents('form:eq(0)')
              , focusable
              , next
              , prev
              ;
            

            var id = $(this).attr('id');
            console.log(id);
            if (e.shiftKey) {
                if (e.keyCode == 13 && $(this).is("textarea") == false) {
                    focusable =   form.find('input,select,.select2-search__field,button,textarea').filter(':visible');
                    prev = focusable.eq(focusable.index(this)-1); 

                    if (prev.length) {
                       prev.focus();
                    } else {
                        form.submit();
                    }
                }
            } else if (e.keyCode == 13 && $(this).is("textarea") == false) {
                //focusable = form.find('input,a,select,.select2-search__field,button,textarea').filter(':visible');
                focusable = form.find('input,select,.select2-search__field,button,textarea').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                } else {
                    form.submit();
                }
                return false;
            }


        });

        /*$(document).on('focus', '.select2', function (e) {
            //alert(e.originalEvent);
            if (e.originalEvent) {
                var s2element = $(this).siblings('select');
                s2element.select2('open');
                s2element.on('select2:closing', function (e) {
                    s2element.select2('focus');
                });
            }
        });*/
    });
    
    function data_table_export_icon_style(){
        $('.dt-button.buttons-copy').css('border', 'none');
        $('.dt-button.buttons-csv').css('border', 'none');
        $('.dt-button.buttons-excel').css('border', 'none');
        $('.dt-button.buttons-pdf').css('border', 'none');
        $('.dt-button.buttons-print').css('border', 'none');
        $('.dt-button.buttons-copy').html('<img src="<?php echo base_url(); ?>assets/dist/img/copy_icon.png" style="width:25px;" alt="Copy" title="Copy" >');
        $('.dt-button.buttons-csv').html('<img src="<?php echo base_url(); ?>assets/dist/img/csv_icon.png" style="width:25px;" alt="CSV" title="CSV" >');
        $('.dt-button.buttons-excel').html('<img src="<?php echo base_url(); ?>assets/dist/img/excel_icon.png" style="width:25px;" alt="Excel" title="Excel" >');
        $('.dt-button.buttons-pdf').html('<img src="<?php echo base_url(); ?>assets/dist/img/pdf_icon.png" style="width:25px;" alt="PDF" title="PDF" >');
        $('.dt-button.buttons-print').html('<img src="<?php echo base_url(); ?>assets/dist/img/print_icon.png" style="width:25px;" alt="Print" title="Print" >');
    }
    
    var oldExportAction = function (self, e, dt, button, config) {
        if (button[0].className.indexOf('buttons-copy') >= 0) {
            $.fn.dataTable.ext.buttons.copyHtml5.action(e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
            $.fn.dataTable.ext.buttons.pdfHtml5.action(e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-csv') >= 0) {
            $.fn.dataTable.ext.buttons.csvHtml5.action(e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-excel') >= 0) {
            if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
            }
            else {
                $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            }
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
    };

    var newExportAction = function (e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
//            $(".box-body").addClass('overlay clearfix');
//            $(".box-body").append('<i class="fa fa-refresh fa-spin"></i>');
        $('#ajax-loader').show();
        dt.one('preXhr', function (e, s, data) {
            // Just this once, load all data from the server...
            data.start = 0;
            data.length = 2147483647;

            dt.one('preDraw', function (e, settings) {
                // Call the original action function
                oldExportAction(self, e, dt, button, config);

                dt.one('preXhr', function (e, s, data) {
                    // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                    // Set the property to what it was before exporting.
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });

                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
//
//                    $(".box-body").removeClass('overlay');
//                    $(".box-body i.fa.fa-refresh.fa-spin").remove();
                $('#ajax-loader').hide();
                // Prevent rendering of the full data to the DOM
                return false;
            });
        });

        // Requery the server with the new one-time export settings
        dt.ajax.reload();
    };
    
    /*------------- Check For Unique --------------------*/
    function check_is_unique(table_name,column_name,column_value,id_column_name = '',id_column_value = ''){	
        var DataStr = "table_name="+table_name+"&column_name="+column_name+"&column_value="+column_value+"&id_column_name="+id_column_name+"&id_column_value="+id_column_value;
        var response = '1';
        $.ajax({
            url: "<?=base_url()?>master/check_is_unique/",
            type: "POST",
            data: DataStr,
            async:false
        }).done(function(data) {
            response = data;
        });
        return response;
    }
    /*------------- Check For Unique --------------------*/
    function show_notify(notify_msg,notify_type)
    {
        if(notify_type == true){
            $.growl.notice({ title:"Success!",message:notify_msg});
        }else{
            $.growl.error({ title:"False!",message:notify_msg});
        }
    }
    
    /**
	 * @param $selector
	 * @constructor
     */
    function initAjaxSelect2($selector,$source_url)
    {
        $selector.select2({
            placeholder: " --Select-- ",
            allowClear: true,
            width:"100%",
            ajax: {
                url: $source_url,
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
    }
    
    /**
     * @param $selector
     * @constructor
     */
    function initAjaxSelect2Mutiple($selector,$source_url)
    {
        $selector.select2({
            placeholder: " --Select-- ",
            allowClear: true,
            width:"100%",
            multiple: true,
            tags: false,
            ajax: {
                url: $source_url,
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
    }

    function setSelect2Value($selector,$source_url = '')
    {
        if($source_url != '') {
            $.ajax({
                url: $source_url,
                type: "GET",
                data: null,
                contentType: false,
                cache: false,
                async: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
                        $selector.empty().append($('<option/>').val(data.id).text(data.text)).val(data.id).trigger("change");
                    }
                }
            });
        } else {
            $selector.empty().append($('<option/>').val('').text('--select--')).val('').trigger("change");
        }
    }
    function setSelect2MultiValue($selector,$source_url = '')
    {
        if($source_url != '') {
            $.ajax({
                url: $source_url,
                type: "GET",
                data: null,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
						var selectValues = data[0];
						$.each(selectValues, function(key, value) {   
							$selector.select2("trigger", "select", {
								data: value
							});
						});
                    }
                }
            });
        } else {
            $selector.empty().append($('<option/>').val('').text('--select--')).val('').trigger("change");
        }
    }
    //Tags
    function initAjaxSelect2Tags($selector,$source_url)
    {
        $selector.select2({
            placeholder: " --Select-- ",
            allowClear: true,
            width:"100%",
            tags: true,
            multiple: true,
            maximumSelectionLength: 1,
            ajax: {
                url: $source_url,
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
    }
    
    $(document).ready(function(){
        /**
        * WARNING: untested using Select2's option ['selectOnClose'=>true]
        *
        * This code was written because the Select2 widget does not handle
        * tabbing from one form field to another.  The desired behavior is that
        * the user can use [Enter] to select a value from Select2 and [Tab] to move
        * to the next field on the form.
        *
        * The following code moves focus to the next form field when a Select2 'close'
        * event is triggered.  If the next form field is a Select2 widget, the widget
        * is opened automatically.
        *
        * Users that click elsewhere on the document will cause the active Select2
        * widget to close.  To prevent the code from overriding the user's focus choice
        * a flag is added to each element that the users clicks on.  If the flag is
        * active, then the automatic focus script does not happen.
        *
        * To prevent conflicts with multiple Select2 widgets opening at once, a second
        * flag is used to indicate the open status of a Select2 widget.  It was
        * necessary to use a flag instead of reading the class '--open' because using the
        * class '--open' as an indicator flag caused timing/bubbling issues.
        *
        * To simulate a Shift+Tab event, a flag is recorded every time the shift key
        * is pressed.
        */
        var docBody = $(document.body);
        var shiftPressed = false;
        var clickedOutside = false;
        //var keyPressed = 0;

        docBody.on('keydown', function(e) {
            var keyCaptured = (e.keyCode ? e.keyCode : e.which);
            //shiftPressed = keyCaptured == 16 ? true : false;
            if (keyCaptured == 16) { shiftPressed = true; }
        });
        docBody.on('keyup', function(e) {
            var keyCaptured = (e.keyCode ? e.keyCode : e.which);
            //shiftPressed = keyCaptured == 16 ? true : false;
            if (keyCaptured == 16) { shiftPressed = false; }
        });

        docBody.on('mousedown', function(e){
            // remove other focused references
            clickedOutside = false;
            // record focus
            if ($(e.target).is('[class*="select2"]')!=true) {
                clickedOutside = true;
            }
        });

        docBody.on('select2:opening', function(e) {
            // this element has focus, remove other flags
            clickedOutside = false;
            // flag this Select2 as open
            $(e.target).attr('data-s2open', 1);
        });
        docBody.on('select2:closing', function(e) {
            // remove flag as Select2 is now closed
            $(e.target).removeAttr('data-s2open');
        });

        docBody.on('select2:close', function(e) {
            var elSelect = $(e.target);
            elSelect.removeAttr('data-s2open');
            var currentForm = elSelect.closest('form');
            var othersOpen = currentForm.has('[data-s2open]').length;
            if (othersOpen == 0 && clickedOutside==false) {
                /* Find all inputs on the current form that would normally not be focus`able:
                 *  - includes hidden <select> elements whose parents are visible (Select2)
                 *  - EXCLUDES hidden <input>, hidden <button>, and hidden <textarea> elements
                 *  - EXCLUDES disabled inputs
                 *  - EXCLUDES read-only inputs
                 */
                var inputs = currentForm.find(':input:enabled:not([readonly], input:hidden, button:hidden, textarea:hidden)')
                    .not(function () {   // do not include inputs with hidden parents
                        return $(this).parent().is(':hidden');
                    });
                var elFocus = null;
                $.each(inputs, function (index) {
                    var elInput = $(this);
                    if (elInput.attr('id') == elSelect.attr('id')) {
                        if ( shiftPressed) { // Shift+Tab
                            elFocus = inputs.eq(index - 1);
                        } else {
                            elFocus = inputs.eq(index + 1);
                        }
                        return false;
                    }
                });
                if (elFocus !== null) {
                    // automatically move focus to the next field on the form
                    var isSelect2 = elFocus.siblings('.select2').length > 0;
                    if (isSelect2) {
                        elFocus.select2('open');
                    } else {
                        elFocus.focus();
                    }
                }
            }
        });

        /**
         * Capture event where the user entered a Select2 control using the keyboard.
         * http://stackoverflow.com/questions/20989458
         * http://stackoverflow.com/questions/1318076
         */
        docBody.on('focus', '.select2', function(e) {
            var elSelect = $(this).siblings('select');
            var test1 = elSelect.is('[disabled]');
            var test2 = elSelect.is('[data-s2open]');
            var test3 = $(this).has('.select2-selection--single').length;
            if (elSelect.is('[disabled]')==false && elSelect.is('[data-s2open]')==false
                && $(this).has('.select2-selection--single').length>0) {
                elSelect.attr('data-s2open', 1);
                elSelect.select2('open');
            }
        });
    });
    
</script>
</body>
</html>
