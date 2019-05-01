(function ($) {
    $(document).ready(function () {
        plyr.setup();

        $('[data-toggle="tooltip"]').tooltip({
            delay: {
                show: 500
            }
        });
    });
})(jQuery);