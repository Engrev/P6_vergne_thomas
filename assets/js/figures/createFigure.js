$(document).ready(function () {
    $('#figureCreationForm select option:first-child').prop('disabled', 'disabled');

    $(document).on('click', '#btn-submit-figureCreationForm', function () {
        $(this).closest('form').submit();
    });
});