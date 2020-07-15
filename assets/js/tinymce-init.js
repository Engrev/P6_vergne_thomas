$(document).ready(function () {
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
});