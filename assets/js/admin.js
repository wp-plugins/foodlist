(function(exports, $, undefined){
'use strict';

    var api;
    
    api = {
        init: function() {
            api.handleTagIconUploader();
            api.prepareSelectboxes();
            api.prepareTextareas();
            api.handleSettingsForm();
            api.prepareSortables();
            
            // upload progress variant
            /*
            var self = this;
            this.container = $('#fl-uploader-container');
            this.uploader = {
                container: this.container,
                browser: this.container.find('.upload'),
                dropzone: this.container.find('.upload-dropzone'),
                success: function(attachment){
                    //console.log(attachment);
                    self.container.next().hide();
                    self.container.next().find('div').width(0);
                    self.container.show();
                    //self.container.prev().val(attachment.attributes.sizes.full.url);
                    self.container.prev().val(attachment.attributes.url).animateHighlight('#00dd00', 1000);
                },
                error: function(attachment){
                    alert(attachment);
                },
                added: function(attachment) {
                    console.log(attachment);
                },
                progress: function(attachment) {
                    var queue = exports.Uploader.queue,
                        $bar = self.container.next().find('div'),
                        memo = 0;
                
                    $bar.parent().show();
                    self.container.hide();

                    if ( ! $bar || ! queue.length )
                        return;

                    $bar.width( ( queue.reduce( function( memo, attachment ) {
                        if ( ! attachment.get('uploading') )
                            return memo + 100;

                        var percent = attachment.get('percent');
                        return memo + ( _.isNumber( percent ) ? percent : 100 );
                    }, 0 ) / queue.length ) + '%' );
                },
                init: function( up ) {
                    var fallback, button;

                    if ( this.supports.dragdrop )
                        return;

                    // Maintain references while wrapping the fallback button.
                    fallback = self.container.find('.upload-fallback');
                    button   = fallback.children().detach();

                    this.browser.detach().empty().append( button );
                    fallback.append(this.browser).show();
                },
                plupload: {},
                params: {}
            };
            this.uploader.plupload.filters = [{
                    title: 'Allowed Files',
                    extensions: 'gif,png,jpg,jpeg'
            }];
            this.uploader = new exports.Uploader(this.uploader);
            */
           
        },
                
        handleTagIconUploader: function() {
            $('#set-menu-tag-icon, #set-menu-tag-icon-img').click(function() {
                //window.wpActiveEditor = null;
                var send_attachment_bkp = exports.media.editor.send.attachment;

                exports.media.editor.send.attachment = function(props, attachment) {
                    if (typeof attachment.sizes === 'undefined') {
                        alert('You can use only image for the icon');
                        return;
                    }

                    var iconUrl;
                    if (typeof attachment.sizes.fl_menu_tag_icon === 'undefined') {
                        //alert('Please upload this image again to re-create the icon size');
                        iconUrl = attachment.url;
                        //return;
                    } else {
                        iconUrl = attachment.sizes.fl_menu_tag_icon.url;
                    }

                    //$('.custom_media_image').attr('src', attachment.url);
                    //$('.custom_media_url').val(attachment.url);
                    //$('.custom_media_id').val(attachment.id);
                    $('#fl-image-container').show();
                    $('#fl-image-uploader').hide();
                    $('#set-menu-tag-icon-img img').attr('src', iconUrl);
                    $('#fl-edit-menu-tag input[name="fl_menu_tag[icon]"]').val(iconUrl);

                    exports.media.editor.send.attachment = send_attachment_bkp;
                };

                exports.media.editor.open($(this));

                return false;       
            });

            $('#remove-menu-tag-icon').click(function(){
                $('#fl-image-container').hide();
                $('#fl-image-uploader').show();
                $('#set-menu-tag-icon-img img').attr('src', '');
                $('#fl-edit-menu-tag input[name=icon]').val('');
                
                return false;
            });
            
            $('#the-list').on('click', '.delete', function(e){
                var r = true;
                if ('undefined' != showNotice) {
                    r = showNotice.warn();
                }
                
                if (!r) {
                    return false;
                }
            });
        },
                
        prepareSelectboxes: function(){
            var format = function (tag) {
                //if (!state.id)
                //return state.text; // optgroup
                //return "<img class='flag' src='images/flags/" + state.id.toLowerCase() + ".png'/>" + state.text;
                var result, url = $(tag.element).data('img-url');
                
                result = '';
                
                if (url.length) {
                    result = '<img class="fl-menu-item-tag-icon" src="'+url+'" alt="" />';
                }
                result += tag.text;
                return result;
            };
            
            $('#fl-menu-item-tags-select').width('95%').select2({
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function(m) { return m; }
            });

            var el = $('#metabox-sortable-dropdown');
            var list = $('#fl-menu-sortable-list');
            var tpl = list.data('template');
            var context = list.data('context');
            var nonce = list.data('nonce');
            el.data('placeholder', el.attr('placeholder'));
            el.width('88%').select2({
                //minimumInputLength: 1,
                placeholder: el.attr('placeholder'),
                ajax: {
                    url: ajaxurl,
                    type: 'POST',
                    data: function(term, page) {
                        return {
                            action: context === 'items' ? 'foodlist_section' : 'foodlist_menu',
                            method: 'Get' + context.charAt(0).toUpperCase() + context.slice(1),
                            args: {
                                nonce: $('input[name="'+nonce+'"]').val(),
                                term: term,
                                page: page,
                                page_limit: 10
                            }
                        }
                    },
                    results: function (data, page) {
                        var more = (page * 10) < data.total; // whether or not there are more results available

                        // notice we return the value of more so Select2 knows if more results can be loaded
                        return {results: data.posts, more: more};
                    }
                }
            }).on('change', function(e){
                //e.added.id
                var html = tpl.replace('__title__', e.added.text).replace('__id__', e.added.id);
                $('#fl-menu-sortable-list').append($(html));

                $(this).select2(
                    'val', null
                );
            });

            list.on('click', '.fl-menu-sortable-item-remove', (function(){
                $(this).parent().fadeOut(500, function(){
                    $(this).remove();
                });
            }));
        },
        
        prepareTextareas: function(){
            if (ace === undefined) {
                return;
            }
            $('.fl-code').each(function(i, el) {
                var editor = ace.edit(el);
                if ($(el).data('mode') !== undefined) {
                    editor.getSession().setMode("ace/mode/"+$(el).data('mode'));
                } else {
                    editor.getSession().setMode("ace/mode/html");
                }
                editor.setTheme("ace/theme/crimson_editor");
                var textarea = $($(el).data('textarea')).hide();
                editor.getSession().setValue(textarea.val());
                editor.getSession().on('change', function(){
                    textarea.val(editor.getSession().getValue());
                });
            });
        },

        prepareSortables: function() {
            $('ul#fl-menu-sortable-list').sortable();
        },

        handleSettingsForm: function() {
            $('#fl-delete-demo-data').click(function(){
                $(this).attr('disabled', 'disabled');
                var a = {
                    action: 'foodlist_settings',
                    method: 'DeleteDemoData',
                    args: {
                        nonce: $('input[name="foodlist[nonce]"]').val()
                    }
                };
                var btn = this;
                $.post(ajaxurl, a, function(){
                    $(btn).parent().parent().fadeOut('slow');
                });
                return false;
            });
        }
    };
    
    exports.foodlist = api;

    /*
    $.fn.animateHighlight = function(highlightColor, duration) {
        var highlightBg = highlightColor || "#FFFF9C";
        var animateMs = duration || 1500;
        var originalBg = this.css("backgroundColor");
        this.stop().css("background-color", highlightBg).animate({backgroundColor: originalBg}, animateMs);
    };
    */

    $(exports.foodlist.init);

})(wp, jQuery);
