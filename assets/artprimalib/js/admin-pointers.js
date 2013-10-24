;(function($, settings) {
'use strict';
    $(document).ready( function($) {
        //wptuts_open_pointer(0);
        //function wptuts_open_pointer(i) {
        
        for (var i = 0; i < settings.pointers.length; i++) {
            var pointer = settings.pointers[i];
            console.log(settings.pointers);
            var options = $.extend(pointer.options, {
                close: function() {
                    $.post(ajaxurl, {
                        pointer: pointer.pointer_id,
                        action: 'dismiss-wp-pointer'
                    });
                }
            });

            $(pointer.target).pointer(options).pointer('open');
        }
        //}
    });
})(jQuery, apAdminPointers);