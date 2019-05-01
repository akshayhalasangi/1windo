(function ($) {
    $(document).ready(function () {
        $('#ks-izi-modal-large').iziModal({
            autoOpen: false,
            padding: 20,
            headerColor: '#3a529b',
            restoreDefaultContent: true,
            title: "Following",
            fullscreen: true,
            subtitle: '',
            transitionIn: 'fadeInDown'
        });

        $('#ks-followers').iziModal({
            autoOpen: false,
            padding: 20,
            headerColor: '#3a529b',
            restoreDefaultContent: true,
            title: "Following",
            fullscreen: true,
            subtitle: '',
            transitionIn: 'fadeInDown'
        });


 
        $('.ks-izi-modal-trigger').on('click', function (e) {
            $($(this).data('target')).iziModal('open');
        });
    });
})(jQuery);