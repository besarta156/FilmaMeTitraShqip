(function($) {

    $(document).ready(function($) {


        /* Image select option */

        $('body').on('click', 'img.gridlove-img-select', function(e) {
            e.preventDefault();
            $(this).closest('ul').find('img.gridlove-img-select').removeClass('selected');
            $(this).addClass('selected');
            $(this).closest('ul').find('input').removeAttr('checked');
            $(this).closest('li').find('input').attr('checked', 'checked');

        });

        
        /* Color picker metabox handle */

        if ($('.gridlove-colorpicker').length) {
            $('.gridlove-colorpicker').wpColorPicker();

            $('a.gridlove-rec-color').click(function(e) {
                e.preventDefault();
                $('.gridlove-colorpicker').val($(this).attr('data-color'));
                $('.gridlove-colorpicker').change();
            });
        }

        
        /* Color picker toggle */
        
        gridlove_toggle_color_picker();
        
        $("body").on("click", "input.color-type", function(e) {
            gridlove_toggle_color_picker();
        });


        
        /* Layout toggle */

        gridlove_toggle_category_layout();

        $("body").on("click", "input.layout-type", function(e) {
            gridlove_toggle_category_layout();
        });


        gridlove_toggle_category_layout_type();

        function gridlove_toggle_category_layout_type(){
            $('.posts-layout-type').change(function (){
                $('.gridlove-layouts-type').hide();
                $('.gridlove-layouts-type-' + $(this).val()).show();
            })

        }
        function gridlove_toggle_color_picker() {
            var picker_value = $('input.color-type:checked').val();
            if (picker_value == 'custom') {
                $('#gridlove-color-wrap').show();
            } else {
                $('#gridlove-color-wrap').hide();
            }

        }

        function gridlove_toggle_category_layout() {
            var layout_type = $('input.layout-type:checked').val();

            if (layout_type == 'custom') {
                $('.gridlove-layout-opt:not(.inactive)').show();
            } else {
                $('.gridlove-layout-opt').hide();
            }

        }

        /* Image upload */
        var meta_image_frame;

        $('body').on('click', '#gridlove-image-upload', function(e) {

            e.preventDefault();

            if (meta_image_frame) {
                meta_image_frame.open();
                return;
            }

            meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                title: 'Choose your image',
                button: {
                    text: 'Set Category image'
                },
                library: {
                    type: 'image'
                }
            });

            meta_image_frame.on('select', function() {

                var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
                $('#gridlove-image-url').val(media_attachment.url);
                $('#gridlove-image-preview').attr('src', media_attachment.url);
                $('#gridlove-image-preview').show();
                $('#gridlove-image-clear').show();

            });

            meta_image_frame.open();
        });


        $('body').on('click', '#gridlove-image-clear', function(e) {
            $('#gridlove-image-preview').hide();
            $('#gridlove-image-url').val('');
            $(this).hide();
        });

    });

})(jQuery);
