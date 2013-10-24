;(function($, settings) {
'use strict';
    $(document).ready( function($) {
        //wptuts_open_pointer(0);
        //function wptuts_open_pointer(i) {
        
        //for (var i = 0; i < settings.pointers.length; i++) {
        var i = 0;
        var f = function() {
            var pointer = settings.pointers[i];
            //console.log(settings.pointers);
            var options = $.extend(pointer.options, {
                close: function(event) {
                    $.post(ajaxurl, {
                        pointer: pointer.pointer_id,
                        action: 'dismiss-wp-pointer'
                    });
                    $(this).pointer('destroy');
                    if (i < settings.pointers.length - 1) {
                        //$(settings.pointers[i].target).pointerdestroy();
                        f(++i);
                    }
                }
            });

            $(pointer.target).pointer(options).pointer('open');
        };
        f(i);
        //}
        //}
    });
})(jQuery, apAdminPointers);