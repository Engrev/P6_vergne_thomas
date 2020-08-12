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

    $(document).on('click', '#btn-add-more-videolink-figure', function () {
        let obj = $(this);
        let nb_input = $('#collapseEditFigureVideosLink .form-group [id^="figure_creation_videolink"]').length;
        let one_more_input = nb_input+1;
        obj.parent().parent().append('<input type="text" name="figure_creation_videolink[]" class="custom-input-theme mt-2" id="figure_creation_videolink_'+one_more_input+'" placeholder="Collez le lien de la vidéo">');
    });
    $(document).on('click', '#btn-add-more-videocode-figure', function () {
        let obj = $(this);
        let nb_textarea = $('#collapseEditFigureVideosCode .form-group [id^="figure_creation_videocode"]').length;
        let one_more_textarea = nb_textarea+1;
        obj.parent().parent().append('<textarea class="form-control mt-2" name="figure_creation_videocode[]" id="figure_creation_videocode_'+one_more_textarea+'" placeholder="Collez le code de la vidéo"></textarea>');
    });

    $(document).on('click', '.btn-add-picture', function () {
        scrollTo($('#collapseEditFigureFiles'));
    });
    $(document).on('click', '.btn-add-video', function () {
        scrollTo($('#collapseEditFigureVideosLink'));
    });
});
function scrollTo(target) {
    if (target.length) {
        $('html, body').stop().animate({
            scrollTop: target.offset().top
        }, 500);
    }
}