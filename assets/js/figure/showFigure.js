$(document).ready(function () {
    $(document).on('click', 'h5.card-title', function () {
        let obj = $(this);
        obj.next().removeClass('d-none');
        let id = obj.closest('.figure').attr('id');
        let user_connected = obj.data('user');
        let href_edit = obj.siblings('.btn-edit-figure').attr('href');

        $.ajax({
            url: "/ajax/figure/show",
            method: "POST",
            data: {id_figure: id},
            dataType: "json",
            success: function (response, status) {
                $('#figureDetailsModal .modal-body section').removeClass('d-none');
                $('#figureDetailsModal .modal-body .alert').addClass('d-none');

                let pictures = '';
                if (response.pictures !== '') {
                    $.each(response.pictures, function (key, picture) {
                        pictures +=
                            '<figure class="pictures-figure-detail" id="picture-'+picture.id+'">' +
                                '<img src="'+picture.path+'" alt="'+picture.uploaded_name+'">' +
                                '<div class="pictures-figure-detail-overlay">' +
                                    '<button type="button" class="btn-figure btn-show-picture-figure" data-href="'+picture.path+'" data-toggle="tooltip" data-placement="bottom" title="Agrandir"></button>' +
                                    '<button type="button" class="btn-figure btn-delete-picture-figure" data-id="'+picture.id+'" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></button>' +
                                '</div>' +
                            '</figure>'
                    });
                }

                let description = 'Aucune description.'
                if (response.figure.description !== '') {
                    description = response.figure.description;
                }

                let updated_at = '';
                if (response.figure.created_at !== response.figure.updated_at) {
                    updated_at = ' <span class="updated-figure-detail" data-toggle="tooltip" data-placement="bottom" title="Mise à jour le">' +
                                     '<i class="fas fa-history"></i> '+response.figure.updated_at +
                                 '</span>';
                }

                let modal_title = '';
                if (user_connected === response.figure.user) {
                    modal_title =
                        '<a href="'+href_edit+'" class="btn-figure btn-edit-figure mx-3" data-toggle="tooltip" data-placement="bottom" title="Modifier"></a>' +
                        '<button type="button" class="btn-figure btn-delete-figure" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></button>';
                }
                $('#figureDetailsModal .modal-header .modal-title').html(response.figure.name + modal_title);

                $('#figureDetailsModal .modal-body .figure-modal').html(
                    '<img src="'+response.figure.cover+'" class="img-figure-detail" alt="'+response.figure.name+'">' +
                    //'<img src="uploads/goofy.jpg" class="img-figure-detail" alt="'+response.figure.name+'">' +

                    '<div class="row">' +
                        '<div class="pictures-container mCustomScrollbar">' +
                            pictures +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<div class="card mt-3">' +
                                '<div class="card-body">' +
                                    description +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<p class="text-center my-3">' +
                                '<span class="created-figure-detail" data-toggle="tooltip" data-placement="bottom" title="Créée le">' +
                                    '<i class="fas fa-clock"></i> '+response.figure.created_at +
                                '</span>' +
                                updated_at +
                            '</p>' +
                        '</div>' +
                    '</div>'
                );
            },
            error: function (xhr, status, errorThrown) {
                $('#figureDetailsModal .modal-body section').addClass('d-none');
                $('#figureDetailsModal .modal-body .alert').removeClass('d-none');
            },
            complete: function () {
                $('.mCustomScrollbar').mCustomScrollbar({
                    axis: 'x',
                    theme: 'dark-thin',
                    autoExpandScrollbar: true,
                    advanced: {
                        autoExpandHorizontalScroll: true
                    }
                });
                $('[data-toggle="tooltip"]').tooltip();

                obj.next().addClass('d-none');
                $('#figureDetailsModal').modal('show');
            }
        });
    });

    $(document).on('click', 'button.btn-show-picture-figure', function () {
        let href = $(this).data('href');
        window.open(href, '_blank');
    });
});