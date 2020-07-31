$(document).ready(function () {
    $('#figureCreationForm select option:first-child').prop('disabled', 'disabled');

    $(document).on('click', '#btn-submit-figureCreationForm', function () {
        $(this).closest('form').submit();
    });

    if ($('#input-edit-figure-picture').length) {
        $('#input-edit-figure-picture').find('label').removeAttr('class');
        $('#figure_creation_picture').removeAttr('required');
        $('#figure_creation_original_picture').val($('#input-edit-figure-picture').parent().prev().find('img').attr('src'));
    }
});