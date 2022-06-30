(function($) {
    $(document).ready(function() {

        /* Image opts selection */
        $('body').on('click', 'img.gridlove-img-select', function(e) {
            e.preventDefault();
            $(this).closest('ul').find('img.gridlove-img-select').removeClass('selected');
            $(this).addClass('selected');
            $(this).closest('ul').find('input').removeAttr('checked');
            $(this).closest('li').find('input').attr('checked', 'checked');

            if ($(this).attr('data-step') !== undefined) {

                var step = parseInt($(this).attr('data-step'));
                var min = parseInt($(this).attr('data-min'));
                var current = parseInt($(this).attr('data-default'));

                $(this).closest('.gridlove-tab').find('.gridlove-input-slider').attr('step', step).attr('min', min).attr('value', current).next().text(current);

            }
        });

        /* Dynamicaly apply select value */
        $('body').on('change', '.gridlove-opt-select', function(e) {
            //e.preventDefault();
            var sel = $(this).val();
            $(this).find('option').removeAttr('selected');
            $(this).find('option[value=' + sel + ']').attr('selected', 'selected');
        });

        /* Dynamicaly change span text based on slider input value  */
        $('body').on("input", '.gridlove-input-slider', function(e) {
            $(this).next().text($(this).val());
        });



        /* Module form tabs */
        $('body').on('click', '.gridlove-opt-tabs a', function(e) {
            e.preventDefault();
            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
            $(this).closest('.gridlove-module-form').find('.gridlove-tab').hide();
            $(this).closest('.gridlove-module-form').find('.gridlove-tab').eq($(this).index()).show();

        });


        /* Module layout types switch */

        $('body').on('click', '.gridlove-module-layout-switch', function(e) {

            var container = $(this).closest('.gridlove-opt-content').find('.gridlove-module-layouts');
            container.find('.gridlove-module-layout').removeClass('active');
            container.find('.gridlove-module-layout.' + $(this).val()).addClass('active').find('img.gridlove-img-select.selected').click();

            if ($(this).val() == 'slider') {
                $(this).closest('.gridlove-tab').find('.gridlove-autoplay-opt').removeClass('gridlove-hidden');
            } else {
                $(this).closest('.gridlove-tab').find('.gridlove-autoplay-opt').addClass('gridlove-hidden');
            }

        });

        $('body').on('click', '.gridlove-module-cats-layout-switch', function(e) {

            var container = $(this).closest('.gridlove-opt-content').find('.gridlove-module-layouts');
            container.find('.gridlove-module-layout').removeClass('active');
            container.find('.gridlove-module-layout.' + $(this).val()).addClass('active').find('img.gridlove-img-select.selected').click();

            if ($(this).val() == 'slider') {
                $(this).closest('.gridlove-tab').find('.gridlove-autoplay-opt').closest('.gridlove-opt').removeClass('gridlove-hidden');
            } else {
                $(this).closest('.gridlove-tab').find('.gridlove-autoplay-opt').closest('.gridlove-opt').addClass('gridlove-hidden');
            }

        });


        /* Show/hide module more button link option */
        $('body').on('change', '.gridlove-more-button-switch', function(e) {
            $(this).closest('.gridlove-opt').find('.gridlove-more-button-opt').toggleClass('gridlove-hidden');
        });

        /* Show/hide module more button link option */
        $('body').on('change', '.gridlove-content-inject', function(e) {
            $(this).closest('.gridlove-opt').find('.gridlove-custom').toggleClass('gridlove-hidden');
        });

        /* Show/hide module custom cuntent inject option */
        $('body').on('change', '.gridlove-module-layout-switch', function(e) {
            var _this = $(this);
            var val = _this.val();
            if (val == 'slider') {
                _this.closest('.gridlove-tab').find('.gridlove-custom').addClass('gridlove-hidden-custom');
            } else {
                _this.closest('.gridlove-tab').find('.gridlove-custom').removeClass('gridlove-hidden-custom');
            }
        });


        /* Make modules sortable */
        $(".gridlove-modules").sortable({
            revert: false,
            cursor: "move",
            placeholder: "gridlove-module-drop"
        });


        var gridlove_current_module;
        var gridlove_module_type;


        /* Add new module */
        $('body').on('click', '.gridlove-add-module', function(e) {
            e.preventDefault();
            gridlove_module_type = $(this).attr('data-type');
            var $modal = $($.parseHTML('<div class="gridlove-module-form">' + $('#gridlove-module-clone .' + gridlove_module_type + ' .gridlove-module-form').html() + '</div>'));
            gridlove_dialog($modal, 'Add New Module', 'gridlove-save-module');

            /* Make some options sortable */
            gridlove_sort_items($(".gridlove-opt-content .sortable"));
            gridlove_sort_searched_items();

        });

        /* Edit module */
        $('body').on('click', '.gridlove-edit-module', function(e) {
            e.preventDefault();
            gridlove_current_module = parseInt($(this).closest('.gridlove-module').attr('data-module'));
            var $modal = $(this).closest('.gridlove-module').find('.gridlove-module-form').clone();
            gridlove_dialog($modal, 'Edit Module', 'gridlove-save-module');

            /* Make some options sortable */
            gridlove_sort_items($(".gridlove-opt-content .sortable"));
            gridlove_sort_searched_items();

        });

        /* Remove module */
        $('body').on('click', '.gridlove-remove-module', function(e) {
            e.preventDefault();
            remove = gridlove_confirm();
            if (remove) {
                $(this).closest('.gridlove-module').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });

        /* Deactivate/Activate module */
        $('body').on('click', '.gridlove-deactivate-module', function(e) {
            e.preventDefault();
            var _self = $(this);
            var parent_el = _self.closest('.gridlove-module');
            var h_data = parent_el.find('.gridlove-module-deactivate').val();

            _self.find('span').toggleClass('gridlove-hidden');

            if (h_data == 1) {
                parent_el.find('.gridlove-module-deactivate').val('0');
                parent_el.addClass('gridlove-module-disabled');
            } else {
                parent_el.find('.gridlove-module-deactivate').val('1');
                parent_el.removeClass('gridlove-module-disabled');
            }

        });

        /* Save module */

        $('body').on('click', 'button.gridlove-save-module', function(e) {

            e.preventDefault();

            var $gridlove_form = $(this).closest('.wp-dialog').find('.gridlove-module-form').clone();

            /* Nah, jQuery clone bug, clone text area manually */
            var txt_content = $(this).closest('.wp-dialog').find('.gridlove-module-form').find("textarea").first().val();
            if (txt_content !== undefined) {
                $gridlove_form.find("textarea").first().val(txt_content);
            }

            if ($gridlove_form.hasClass('edit')) {
                $gridlove_form = gridlove_fill_form_fields($gridlove_form);
                var $module = $('.gridlove-module-' + gridlove_current_module);
                $module.find('.gridlove-module-form').html($gridlove_form.html());
                $module.find('.gridlove-module-title').text($gridlove_form.find('.mod-title').val());
                $module.find('.gridlove-module-columns').text($gridlove_form.find('.mod-columns:checked').closest('li').find('span').text());
            } else {
                var count = $('.gridlove-modules-count').attr('data-count');
                $gridlove_form = gridlove_fill_form_fields($gridlove_form, 'gridlove[modules][' + count + ']');
                $('.gridlove-modules').append($('#gridlove-module-clone .' + gridlove_module_type).html());
                var $new_module = $('.gridlove-modules .gridlove-module').last();
                $new_module.addClass('gridlove-module-' + parseInt(count)).attr('data-module', parseInt(count)).find('.gridlove-module-form').addClass('edit').html($gridlove_form.html());
                $new_module.find('.gridlove-module-title').text($gridlove_form.find('.mod-title').val());
                $new_module.find('.gridlove-module-columns').text($gridlove_form.find('.mod-columns:checked').closest('li').find('span').text());
                $('.gridlove-modules-count').attr('data-count', parseInt(count) + 1);
                $('.gridlove-empty-modules').hide();
            }

        });

        /* Open our dialog modal */
        function gridlove_dialog(obj, title, action) {

            obj.dialog({
                'dialogClass': 'wp-dialog',
                'appendTo': false,
                'modal': true,
                'autoOpen': false,
                'closeOnEscape': true,
                'draggable': false,
                'resizable': false,
                'width': 800,
                'height': $(window).height() - 60,
                'title': title,
                'close': function(event, ui) {
                    $('body').removeClass('modal-open');
                },
                'buttons': [{
                    'text': "Save",
                    'class': 'button-primary ' + action,
                    'click': function() {
                        $(this).dialog('close');
                    }
                }]
            });

            obj.dialog('open');

            $('body').addClass('modal-open');
        }


        /* Fill form fields dynamically */
        function gridlove_fill_form_fields($obj, name) {

            $obj.find('.gridlove-count-me').each(function(index) {

                if (name !== undefined && !$(this).is('option')) {
                    $(this).attr('name', name + $(this).attr('name'));
                }

                if ($(this).is('textarea')) {
                    $(this).html($(this).val());
                }


                if (!$(this).is('select')) {
                    $(this).attr('value', $(this).val());
                }



                if ($(this).is(":checked")) {
                    $(this).attr('checked', 'checked');
                } else {
                    $(this).removeAttr('checked');
                }

            });

            return $obj;
        }

        function gridlove_confirm() {
            var ret_val = confirm("Are you sure?");
            return ret_val;
        }


        /* Metabox switch - do not show every metabox for every template */

        var gridlove_is_gutenberg = gridlove_js_settings.is_gutenberg && typeof wp.apiFetch !== 'undefined';
        var gridlove_template_selector = gridlove_js_settings.is_gutenberg ? '.editor-page-attributes__template select' : '#page_template';

        if (gridlove_is_gutenberg) {

            var post_id = $('#post_ID').val();
            wp.apiFetch({ path: '/wp/v2/pages/' + post_id, method: 'GET' }).then(function(obj) {
                gridlove_template_metaboxes(false, obj.template);
            });

        } else {
            gridlove_template_metaboxes(false);
        }

        $('body').on('change', gridlove_template_selector, function(e) {
            gridlove_template_metaboxes(true);
        });

        function gridlove_template_metaboxes(scroll, t) {

            var template = t ? t : $(gridlove_template_selector).val();

            if (template == 'template-modules.php') {
                $('#gridlove_page_sidebar').fadeOut(300);
                $('#gridlove_page_layout').fadeOut(300);
                $('#gridlove_modules').fadeIn(300);
                $('#gridlove_pagination').fadeIn(300);
                $('#gridlove_cover').fadeIn(300);
                $('#gridlove_author_options').fadeOut(300);
                if (scroll) {
                    var target = $('#gridlove_cover').attr('id');
                    $('html, body').stop().animate({
                        'scrollTop': $('#' + target).offset().top
                    }, 900, 'swing', function() {
                        window.location.hash = target;
                    });
                }
            } else if (template == 'template-full-width.php') {
                $('#gridlove_page_sidebar').fadeOut(300);
                $('#gridlove_page_layout').fadeOut(300);
                $('#gridlove_modules').fadeOut(300);
                $('#gridlove_pagination').fadeOut(300);
                $('#gridlove_cover').fadeOut(300);
                $('#gridlove_author_options').fadeOut(300);
            } else if (template == 'template-authors.php') {
                $('#gridlove_modules').fadeOut(300);
                $('#gridlove_pagination').fadeOut(300);
                $('#gridlove_cover').fadeOut(300);
                $('#gridlove_page_sidebar').fadeIn(300);
                $('#gridlove_page_layout').fadeIn(300);
                $('#gridlove_author_options').fadeIn(300);
            } else {
                $('#gridlove_page_sidebar').fadeIn(300);
                $('#gridlove_page_layout').fadeIn(300);
                $('#gridlove_modules').fadeOut(300);
                $('#gridlove_pagination').fadeOut(300);
                $('#gridlove_cover').fadeOut(300);
                $('#gridlove_author_options').fadeOut(300);
            }

        }

        /* Cover switch - do not show every metabox for every Layout */
        gridlove_cover_options('#gridlove_cover');

        function gridlove_cover_options(wrap) {

            var hidden_class = $(wrap).find('.gridlove-show-hide');
            var show_class = $(wrap).find('.gridlove-show-hide-custom');
            var imgs = $(wrap).find('.gridlove-img-select-wrap li img');

            imgs.each(function() {

                var _this = $(this);
                var val = _this.siblings('input').val();

                _this.on('click', function() {

                    if (val != 'none' && val != 'custom') {
                        hidden_class.each(function() {
                            $(this).removeClass('gridlove-hidden-custom');
                        });
                        show_class.addClass('gridlove-hidden-custom').removeClass('gridlove-show-custom');
                    } else if (val == 'custom') {
                        hidden_class.each(function() {
                            $(this).addClass('gridlove-hidden-custom');
                        });
                        show_class.removeClass('gridlove-hidden-custom').addClass('gridlove-show-custom');
                    } else if (val == 'none') {
                        hidden_class.each(function() {
                            $(this).addClass('gridlove-hidden-custom');
                        });
                        show_class.addClass('gridlove-hidden-custom').removeClass('gridlove-show-custom');
                    }
                });
            });
        }

        /* Add background image on custom cover area layout from media file */
        var thumbImage;
        $("body").on("click", "a.gridlove-select-bg-image", function(e) {
            e.preventDefault();
            var this_btn = $(this);
            var image = wp.media({
                    title: 'Upload Image',
                }).open()
                .on('select', function(e) {
                    var uploaded_image = image.state().get('selection').first();
                    var thumbImage = uploaded_image.toJSON().url;
                    this_btn.siblings('input').val(thumbImage);
                });
        });


        /* Call live search */
        gridlove_live_search('gridlove_ajax_search');

        /* Live search functionality */
        function gridlove_live_search(search_ajax_action) {

            $('body').on('focus', '.gridlove-live-search', function() {

                var $this = $(this),
                    get_module_type = 'posts';

                if ($this.hasClass('gridlove-live-search-with-cpts')) {
                    get_module_type = $this.closest('.gridlove-opt-box').find('.gridlove-fa-post-type').val();
                    if (get_module_type === 'post') {
                        get_module_type = 'cover';
                    }
                } else {
                    get_module_type = $this.closest('.gridlove-live-search-opt').find('.gridlove-live-search-hidden').data('type');
                }

                $this.autocomplete({
                    source: function(req, response) {
                        $.getJSON(gridlove_js_settings.ajax_url + '?callback=?&action=' + search_ajax_action + '&type=' + get_module_type, req, response);
                    },
                    delay: 300,
                    minLength: 4,
                    select: function(event, ui) {

                        var $this = $(this);
                        var wrap = $this.closest('.gridlove-live-search-opt');

                        wrap.find('.gridlove-live-search-items').append('<span><button type="button" class="ntdelbutton" data-id="' + ui.item.id + '"><span class="remove-tag-icon"></span></button><span class="gridlove-searched-title">' + ui.item.label + '</span></span>');
                        gridlove_update_items($this);
                        wrap.find('.gridlove-live-search').val('');

                        return false;
                    }
                });

            });

            gridlove_sort_searched_items();
            gridlove_remove_all_search_items_on_post_type_change();
            gridlove_remove_searched_items();


        }

        /**
         * Sort/reorder searched items from list 
         */
        function gridlove_sort_searched_items() {
            $('.gridlove-live-search-items.tagchecklist').sortable({
                revert: false,
                cursor: "move",
                containment: "parent",
                opacity: 0.8,
                update: function(event, ui) {
                    gridlove_update_items($(this));
                }
            });
        }

        /**
         * Remove searched item from list 
         */
        function gridlove_remove_searched_items() {
            $('body').on('click', '.gridlove-live-search-opt .ntdelbutton', function(e) {
                var $this = $(this);
                var parent = $this.closest('.gridlove-live-search-items');
                $this.parent().remove();
                gridlove_update_items(parent);
            });
        }

        /**
         * Sync/update hander function for list items on add, reorder or remove actions
         */
        function gridlove_update_items(object) {

            var wrapper = object.closest('.gridlove-live-search-opt');
            var hidden_field = wrapper.find('.gridlove-live-search-hidden');
            var hidden_val = [];

            wrapper.find('.ntdelbutton').each(function() {
                hidden_val.push($(this).attr('data-id'));
            });

            hidden_field.val(hidden_val.toString());
        }

        /**
         * Remove searched item from list
         */
        function gridlove_remove_all_search_items_on_post_type_change() {
            $('body').on('change', '.gridlove-fa-post-type', function() {
                var $searched_items = $('.gridlove-live-search-items'),
                    $search = $('.gridlove-live-search-hidden');

                $searched_items.html('');
                $search.val('');
            });
        }

        /* Sortable functionality */
        function gridlove_sort_items(object) {
            object.sortable({
                revert: false,
                cursor: "move",
                placeholder: 'gridlove-fields-placeholder',
                opacity: 0.8
            });
        }

        var gridlove_watch_for_changes = {

            init: function() {
                var $watchers = $('.gridlove-watch-for-changes');

                if (gridlove_empty($watchers)) {
                    return;
                }

                $watchers.each(this.initWatching);
            },

            initWatching: function(i, elem) {
                var $elem = $(elem),
                    watchedElemClass = $elem.data('watch'),
                    showOnValue = $elem.data('show-on-value'),
                    hideOnValue = $elem.data('hide-on-value');

                if (!gridlove_empty(showOnValue)) {
                    $('body').on('change', '.' + watchedElemClass, showByValue);
                } else {
                    $('body').on('change', '.' + watchedElemClass, hideByValue);
                }

                function hideByValue() {
                    var $this = $(this);

                    if (!$this.hasClass(watchedElemClass)) {
                        $this = $('.' + watchedElemClass + ':checked, ' + '.' + watchedElemClass + ':checked, ' + '.' + watchedElemClass + ':selected');
                    }

                    if (gridlove_empty($this)) {
                        return false;
                    }

                    var val = $this.val();

                    if (val === hideOnValue) {
                        $elem.hide();
                        return true;
                    }

                    $elem.show();
                    return false;
                }

                function showByValue() {
                    var $this = $(this);

                    if (!$this.hasClass(watchedElemClass)) {
                        $this = $('.' + watchedElemClass + ':checked, ' + '.' + watchedElemClass + ':checked, ' + '.' + watchedElemClass + ' > option:selected');
                    }

                    if (gridlove_empty($this)) {
                        return false;
                    }

                    var val = $this.val();

                    if (val === showOnValue) {
                        $elem.show();
                        return true;
                    }

                    $elem.hide();
                    return false;
                }

                showByValue();
                hideByValue();
            }

        };

        gridlove_watch_for_changes.init();
        /**
         * Checks if variable is empty or not
         *
         * @param variable
         * @returns {boolean}
         */
        function gridlove_empty(variable) {

            if (typeof variable === 'undefined') {
                return true;
            }

            if (variable === 0 || variable === '0') {
                return true;
            }

            if (variable === null) {
                return true;
            }

            if (variable.length === 0) {
                return true;
            }

            if (variable === "") {
                return true;
            }

            if (variable === false) {
                return true;
            }

            if (typeof variable === 'object' && $.isEmptyObject(variable)) {
                return true;
            }

            return false;
        }

    });
})(jQuery);
