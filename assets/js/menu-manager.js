(function($) {
'use strict';

var context;
var flMenuManager = {

    init : function() {
        var rem, sidebars = $('div.widgets-sortables'), isRTL = !! ( 'undefined' != typeof isRtl && isRtl ),
            margin = ( isRtl ? 'marginRight' : 'marginLeft' ), the_id;

        context = $('#fl-manager-context').data('context');
        
        //$('#widgets-right').children('.widgets-holder-wrap').children('.sidebar-name').click(function(){
        $('#widgets-right .widgets-holder-wrap .sidebar-name').click(function(){
            var c = $(this).siblings('.widgets-sortables'), p = $(this).parent();
            if ( !p.hasClass('closed') ) {
                c.sortable('disable');
                p.addClass('closed');
            } else {
                p.removeClass('closed');
                c.sortable('enable').sortable('refresh');
            }
        });

        $('#widgets-left').children('.widgets-holder-wrap').children('.sidebar-name').click(function() {
            $(this).parent().toggleClass('closed');
        });

        sidebars.each(function(){
            if ( $(this).parent().hasClass('inactive') )
                return true;

            //var h = 50, H = $(this).children('.widget').length;
            //h = h + parseInt(H * 48, 10);
            //$(this).css( 'minHeight', h + 'px' );
        });

        $(document.body).bind('click.widgets-toggle', function(e){
            var target = $(e.target), css = {}, widget, inside, w;

            if ( target.parents('.widget-top').length && ! target.parents('#available-widgets').length ) {
                widget = target.closest('div.widget');
                inside = widget.children('.widget-inside');
                /*
                w = parseInt( widget.find('input.widget-width').val(), 10 );

                if ( inside.is(':hidden') ) {
                    if ( w > 250 && inside.closest('div.widgets-sortables').length ) {
                        css['width'] = w + 30 + 'px';
                        if ( inside.closest('div.widget-liquid-right').length )
                            css[margin] = 235 - w + 'px';
                        widget.css(css);
                    }
                    flMenuManager.fixLabels(widget);
                    inside.slideDown('fast');
                } else {
                    inside.slideUp('fast', function() {
                        widget.css({'width':'', margin:''});
                    });
                }
                */
                e.preventDefault();
            } else if ( target.hasClass('widget-control-save') ) {
                flMenuManager.save( target.closest('div.widget'), 0, 1, 0 );
                e.preventDefault();
            } else if ( target.hasClass('widget-control-remove') ) {
                flMenuManager.save( target.closest('div.widget'), 1, 1, 0 );
                e.preventDefault();
            } else if ( target.hasClass('widget-control-close') ) {
                flMenuManager.close( target.closest('div.widget') );
                e.preventDefault();
            }
        });

        sidebars.children('.widget').each(function() {
            flMenuManager.appendTitle(this);
            if ( $('p.widget-error', this).length )
                $('a.widget-action', this).click();
        });

        $('#widget-list').children('.widget').draggable({
            connectToSortable: 'div.widgets-sortables',
            handle: '> .widget-top > .widget-title',
            distance: 2,
            helper: 'clone',
            zIndex: 100,
            containment: 'document',
            start: function(e,ui) {
                ui.helper.find('div.widget-description').hide();
                the_id = this.id;
            },
            stop: function(e,ui) {
                if ( rem )
                    $(rem).hide();

                rem = '';
            }
        });

        sidebars.sortable({
            placeholder: 'widget-placeholder',
            items: '> .widget',
            handle: '> .widget-top > .widget-title',
            cursor: 'move',
            distance: 2,
            containment: 'document',
            start: function(e,ui) {
                ui.item.children('.widget-inside').hide();
                ui.item.css({margin:'', 'width':''});
                //the_id = this.id;
            },
            stop: function(e,ui) {
                if ( ui.item.hasClass('ui-draggable') && ui.item.data('draggable') )
                    ui.item.draggable('destroy');

                if ( ui.item.hasClass('deleting') ) {
                    flMenuManager.save( ui.item, 1, 0, 1 ); // delete widget
                    ui.item.remove();
                    return;
                }

                var add = ui.item.find('input.add_new').val(),
                    n = ui.item.find('input.multi_number').val(),
                    id = the_id,
                    sb = $(this).attr('id');

                //ui.item.css({margin:'', 'width':''});
                ui.item.attr( 'style', '' ).removeClass('ui-draggable');
                the_id = '';

                if ( add ) {
                    if ( 'multi' == add ) {
                        ui.item.html( ui.item.html().replace(/<[^<>]+>/g, function(m){ return m.replace(/__i__|%i%/g, n); }) );
                        ui.item.attr( 'id', id.replace('__i__', n) );
                        n++;
                        $('div#' + id).find('input.multi_number').val(n);
                    } else if ( 'single' == add ) {
                        ui.item.attr( 'id', 'new-' + id );
                        rem = 'div#' + id;
                    }
                    flMenuManager.save( ui.item, 0, 0, 1 );
                    ui.item.find('input.add_new').val('');
                    ui.item.find('a.widget-action').click();
                    return;
                }
                flMenuManager.saveOrder(sb);
            },
            receive: function(e, ui) {
                var sender = $(ui.sender);

                if ( !$(this).is(':visible') || this.id.indexOf('orphaned_widgets') != -1 )
                    sender.sortable('cancel');

                if ( sender.attr('id').indexOf('orphaned_widgets') != -1 && !sender.children('.widget').length ) {
                    sender.parents('.orphan-sidebar').slideUp(400, function(){ $(this).remove(); });
                }
            }
        }).sortable('option', 'connectWith', 'div.widgets-sortables').parent().filter('.closed').children('.widgets-sortables').sortable('disable');

        $('#available-widgets').droppable({
            tolerance: 'pointer',
            accept: function(o){
                return $(o).parent().attr('id') != 'widget-list';
            },
            drop: function(e,ui) {
                ui.draggable.addClass('deleting');
                $('#removing-widget').hide().children('span').html('');
            },
            over: function(e,ui) {
                ui.draggable.addClass('deleting');
                $('div.widget-placeholder').hide();

                if ( ui.draggable.hasClass('ui-sortable-helper') )
                    $('#removing-widget').show().children('span')
                    .html( ui.draggable.find('div.widget-title').children('h4').html() );
            },
            out: function(e,ui) {
                ui.draggable.removeClass('deleting');
                $('div.widget-placeholder').show();
                $('#removing-widget').hide().children('span').html('');
            }
        });
    },

    saveOrder : function(sb) {
        if ( sb )
            $('#' + sb).closest('div.widgets-holder-wrap').find('.spinner').css('display', 'inline-block');

        var a = {
            action: 'foodlist_'+context,
            method: 'SaveOrder',
            args: {
                nonce: $('#_wpnonce_'+context).val(),
                items: {}
            }
        };

        $('div.widgets-sortables').each( function() {
            if ( $(this).sortable ) {
                //a['sidebars[' + $(this).attr('id') + ']'] = $(this).sortable('toArray').join(',');
                a.args.items[$(this).data('item-id')] = $(this).sortable('toArray').join(',');
            }
        });

        $.post( ajaxurl, a, function() {
            $('.spinner').hide();
        });

        this.resize();
    },

    save : function(widget, del, animate, order) {
        var sb = widget.closest('div.widgets-sortables').data('item-id'),
            data = widget.find('form').serialize(),
            a;
        widget = $(widget);
        $('.spinner', widget).show();

        a = {
            action: 'foodlist_'+context,
            method: 'SaveList',
            args: {
                nonce: $('#_wpnonce_'+context).val(),
                item: sb
            }
        };

        if (del) {
            a['args']['delete_widget'] = 1;
        }

        data += '&' + $.param(a);

        $.post( ajaxurl, data, function(r){
            var id;

            if ( del ) {
                if ( !$('input.widget_number', widget).val() ) {
                    id = $('input.widget-id', widget).val();
                    $('#available-widgets').find('input.widget-id').each(function(){
                        //if ( $(this).val() == id )
                        //    $(this).closest('div.widget').show();
                    });
                }

                if ( animate ) {
                    order = 0;
                    widget.slideUp('fast', function(){
                        $(this).remove();
                        flMenuManager.saveOrder();
                    });
                } else {
                    widget.remove();
                    flMenuManager.resize();
                }
            } else {
                $('.spinner').hide();
                if ( r && r.length > 2 ) {
                    $('div.widget-content', widget).html(r);
                    flMenuManager.appendTitle(widget);
                    flMenuManager.fixLabels(widget);
                }
            }
            
            if (order) {
                flMenuManager.saveOrder();
            }
        });
    },

    appendTitle : function(widget) {
        /*
        var title = $('input[id*="-title"]', widget).val() || '';

        if ( title )
            title = ': ' + title.replace(/<[^<>]+>/g, '').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        $(widget).children('.widget-top').children('.widget-title').children()
                .children('.in-widget-title').html(title);
        */
    },

    resize : function() {
        $('div.widgets-sortables').each(function(){
            if ( $(this).parent().hasClass('inactive') )
                return true;

            //var h = 50, H = $(this).children('.widget').length;
            //h = h + parseInt(H * 48, 10);
            //$(this).css( 'minHeight', h + 'px' );
        });
    },

    fixLabels : function(widget) {
        widget.children('.widget-inside').find('label').each(function(){
            var f = $(this).attr('for');
            if ( f && f == $('input', this).attr('id') )
                $(this).removeAttr('for');
        });
    },

    close : function(widget) {
        widget.children('.widget-inside').slideUp('fast', function(){
            //widget.attr( 'style', '' );
            widget.css({'width':'', margin:''});
        });
    }
};

$(document).ready(function($){ flMenuManager.init(); });

})(jQuery);
