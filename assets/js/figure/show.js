$(document).ready(function () {
    if ($('textarea#comment-content-modal').length) {
        tinymce.init({
            selector: "textarea#comment-content-modal",
            language: "fr_FR",
            branding: false,
            height: 300,
            plugins: "lists,quickbars,image,imagetools,media,link,code,emoticons",
            toolbar: "undo redo copy cut paste | fontsizeselect forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify bullist numlist | quickimage editimage media | link code emoticons"
        });
    }

    $('.mCustomScrollbar').mCustomScrollbar({
        axis: 'x',
        theme: 'dark-thin',
        autoExpandScrollbar: true,
        advanced: {
            autoExpandHorizontalScroll: true
        }
    });

    $('#figureDetailsModal').modal('show');

    /*$(document).on('shown.bs.modal', '#figureDetailsModal', function () {
        $('.mCustomScrollbar').mCustomScrollbar({
            axis: 'x',
            theme: 'dark-thin',
            autoExpandScrollbar: true,
            advanced: {
                autoExpandHorizontalScroll: true
            }
        });
    });*/

    $(document).on('hide.bs.modal', '#figureDetailsModal', function () {
        let href = $(this).data('redirect-on-close');
        document.location.href=href;
    });

    $(document).on('click', 'button.btn-show-picture-figure', function () {
        let href = $(this).data('href');
        window.open(href, '_blank');
    });
});