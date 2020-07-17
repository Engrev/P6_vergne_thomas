$(document).ready(function () {
    $(document).on('click', 'h5.card-title', function () {
        let obj = $(this);
        obj.next().removeClass('d-none');
        let id = obj.closest('.figure').attr('id');

        $.ajax({
            url: "/ajax/figure/load",
            method: "POST",
            data: {id_figure: id},
            dataType: "json",
            success: function (figure, status) {
                console.log(figure);
            },
            error: function (xhr, status, errorThrown) {
                $('#figureDetailsModal').find('.modal-body').html('<div class="alert alert-danger" role="alert">La figure n\'a pas été trouvée.</div>');
            },
            complete: function () {
                obj.next().addClass('d-none');
                $('#figureDetailsModal').modal('show');
            }
        });
    });
});