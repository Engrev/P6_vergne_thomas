$(document).ready(function () {
    if ($('#input-edit-figure-picture').length) {
        $('#input-edit-figure-picture').find('label').removeAttr('class');
        $('#figure_creation_picture').removeAttr('required');
        $('#figure_creation_original_picture').val($('#input-edit-figure-picture').parent().prev().find('img').attr('src'));
    }
});