$(document).ready(function () {
    $('#scrollDown-to-figures').on('click', function (e) {
        e.preventDefault();
        scrollTo($('.container-fluid'));
    });
    $('#scrollUp-to-figures').on('click', function (e) {
        e.preventDefault();
        scrollTo($('.container-fluid'));
    });

    $('#collapseFiguresList').on('show.bs.collapse', function () {
        $('#btn-show-more-figures').html('<i class="fas fa-minus"></i> Voir moins')
    });
    $('#collapseFiguresList').on('hide.bs.collapse', function () {
        $('#btn-show-more-figures').html('<i class="fas fa-plus"></i> Voir plus')
    });

    $(document).on('click', 'h5.card-title', function () {
        $('#figureDetailsModal').modal('show')
    });
});
function scrollTo(target) {
    if (target.length) {
        $('html, body').stop().animate({
            scrollTop: target.offset().top
        }, 700);
    }
}