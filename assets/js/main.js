$(document).ready(function () {
    var scrollTrigger = 500; // px
    backToTop(scrollTrigger);
    $(window).on("scroll", function () {
        backToTop(scrollTrigger);
    });
    $("#back-to-top").on("click", function (e) {
        e.preventDefault();
        $("html,body").animate({
            scrollTop: 0
        }, 700);
    });

    if ($('textarea.tinymce').length) {
        tinymce.init({
            selector: "textarea.tinymce",
            language: "fr_FR",
            branding: false,
            height: 500,
            plugins: "lists,quickbars,image,imagetools,media,link,code,emoticons",
            toolbar: "undo redo copy cut paste | fontsizeselect forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify bullist numlist | quickimage editimage media | link code emoticons"
        });
    }

    if ($('input.fileinput').length) {
        $('input.fileinput').fileinput({
            theme: "fas",
            language: "fr",
            showUpload: false,
            browseOnZoneClick: true,
            allowedPreviewTypes: ["image"],
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            browseClass: "btn btn-theme",
            browseIcon: "<i class='fas fa-image'></i> ",
            removeClass: "btn btn-danger",
            removeIcon: "<i class='fas fa-trash-alt'></i> "
        });
    }

    $(document).find('[data-toggle="tooltip"]').tooltip();
    $(document).on('click', '[data-toggle="tooltip"]', function () {
        $(this).tooltip('hide');
    });
});
function backToTop(scrollTrigger) {
    var scrollTop = $(window).scrollTop();
    if (scrollTop > scrollTrigger) {
        $("#back-to-top").addClass("show");
    } else {
        $("#back-to-top").removeClass("show");
    }
}