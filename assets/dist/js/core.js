(function ($) {

    var _init = [];

    $.core = {
        init: function (f) {

            if (f) {
                _init.push(f);
            }
            else {
                $.each(_init, function (idx, f) {
                    f();
                });
            }
        },
        keys: function (o) {

            var keys = [];

            $.each(o, function (k, v) {
                keys.push(k);
            });

            return keys;
        },
        trim: function (string) {

            return $.trim(string);
        },
        ajax: function (method, url, params, callback, error) {

            if (typeof params == 'function') {
                error = callback;
                callback = params;
                params = {};
            }

            callback = callback || function () {};
            error = error || function () {};

            $.ajax({
                type: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                data: params,
                dataType: 'json',
                error: function() {
                    swal({
                        title: "Oops...",
                        text: "Something went wrong!",
                        confirmButtonColor: "#EF5350",
                        type: "error"
                    });
                    $('.loader').hide();
                },
                success: function (result) {
                    callback(result);
                }
            });
        },
        get: function (controller, action, params, callback, error) {

            $.core.ajax('get', controller, action, params, callback, error);
        },
        post: function (controller, action, params, callback, error) {

            $.core.ajax('post', controller, action, params, callback, error);
        },
        urls: function (value) {

            var data = $.parseJSON(urls);

            switch (value) {
                case 'js':
                    return data.assets.js;

                case 'css':
                    return data.assets.css;

                default:
                    break;
            }

            return data.base;
        },
        url: function (value) {

            return $.core.urls('base') + '/' + value;
        },
        delete_confirm:function(element,url,msg){
            swal({
                title: "Are you sure?",
                text: msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#EF5350",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    element.closest('form').submit();
                }
            });
        }
    };

    $(document).ready(function () {
        $.core.init();
    });
})(jQuery);
