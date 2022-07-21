(function($) {
    $(document).ready(function() {

        /* Initialize sortable options */
        gridlove_opt_sortable();

        $(document).on('widget-added', function(e) {
            gridlove_opt_sortable();


        });

        $(document).on('widget-updated', function(e) {
            gridlove_opt_sortable();

        });

        /* Show/hide slider widget options */
        $("body").on("change", ".gridlove-opt-slider input", function(e) {
            var per_slide = $(this).closest('.widget-content').find('.gridlove-opt-per-slide');
            if($(this).is(':checked')){                
                per_slide.show();
            } else {
                 per_slide.hide();
            }
        });

        /* Make some options sortable */
        function gridlove_opt_sortable() {
            $(".gridlove-widget-content-sortable").sortable({
                revert: false,
                cursor: "move"
            });
        }


    });

})(jQuery);
