$(document).ready(function () {
    $(document).on('click', 'h5.card-title', function () {
        const LIMIT = 10;
        let obj = $(this);
        obj.next().removeClass('d-none');
        let id = obj.closest('.figure').attr('id').replace('figure-', '');
        let user_connected = obj.data('user');
        let href_edit = obj.siblings('.btn-edit-figure').attr('href');

        $.ajax({
            url: "/ajax/figure/show",
            method: "POST",
            data: {figure_id: id, limit: LIMIT},
            dataType: "json",
            success: function (response, status) {
                $('#figureDetailsModal .modal-body section').removeClass('d-none');
                $('#figureDetailsModal .modal-body .alert').addClass('d-none');

                let modal_title = '';
                let btn_comment = '';
                if (user_connected === response.figure.user) {
                    modal_title =
                        '<a href="'+href_edit+'" class="btn-figure btn-edit-figure mx-3" data-toggle="tooltip" data-placement="bottom" title="Modifier"></a>' +
                        '<button type="button" class="btn-figure btn-delete-figure" data-figure-id="'+response.figure.id+'" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></button>';

                    btn_comment =
                        '<button type="button" class="btn-comment btn-delete-comment-figure" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></button>';
                }

                let pictures = '';
                if (response.pictures !== '') {
                    $.each(response.pictures, function (key, picture) {
                        if (picture.path.match(/^uploads/)) {
                            pictures +=
                                '<figure class="pictures-figure-detail" id="picture-'+picture.id+'">' +
                                    '<img src="'+picture.path+'" alt="'+picture.uploaded_name+'">' +
                                    '<div class="pictures-figure-detail-overlay">' +
                                        '<button type="button" class="btn-figure btn-show-picture-figure" data-href="'+picture.path+'" data-toggle="tooltip" data-placement="bottom" title="Agrandir"></button>' +
                                        '<button type="button" class="btn-figure btn-delete-picture-figure" data-id="'+picture.id+'" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></button>' +
                                    '</div>' +
                                '</figure>'
                        } else if (picture.path.match(/^http/)) {
                            pictures +=
                                '<figure class="pictures-figure-detail" id="picture-'+picture.id+'">' +
                                    '<iframe src="'+picture.path+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' +
                                '</figure>'
                        } else if (picture.path.match(/^<iframe/)) {
                            pictures +=
                                '<figure class="pictures-figure-detail" id="picture-'+picture.id+'">' +
                                    picture.path +
                                '</figure>'
                        }
                    });
                }

                let messages = '';
                if (response.messages !== '') {
                    $.each(response.messages, function (key, message) {
                        messages +=
                            '<article class="comment-modal" id="comment-'+message.id+'">' +
                                '<div class="comment-figure-detail-overlay"></div>' +
                                '<table>' +
                                    '<tbody>' +
                                        '<tr>' +
                                            '<td>' +
                                                '<img src="'+message.user.avatar+'" alt="Photo de profil">' +
                                            '</td>' +
                                            '<td>' +
                                                '<p class="text-muted mb-0">'+message.user.username+' &bull; '+message.date+'</p>' +
                                                '<article class="comment-body">'+message.content+'</article>' +
                                            '</td>' +
                                        '</tr>' +
                                    '</tbody>' +
                                '</table>' +
                                btn_comment +
                            '</article>'
                    });
                }

                let description = 'Aucune description.'
                if (response.figure.description !== null) {
                    description = response.figure.description;
                }

                let updated_at = '';
                if (response.figure.created_at !== response.figure.updated_at) {
                    updated_at = ' <span class="updated-figure-detail" data-toggle="tooltip" data-placement="bottom" title="Mise à jour le">' +
                                     ' | <i class="fas fa-history"></i> '+response.figure.updated_at +
                                 '</span>';
                }

                let rotation = '';
                if ($.inArray(response.figure.name, ['180','360','540','720','900','1080'])) {
                    rotation = ' rotations rotation-'+response.figure.name;
                }

                $('#figureDetailsModal .modal-header .modal-title').html(response.figure.name + modal_title);

                $('#figureDetailsModal .modal-body .figure-modal').html(
                    '<img src="'+response.figure.cover+'" class="img-figure-detail'+rotation+'" alt="'+response.figure.name+'">' +

                    '<div class="row bg-darkness my-4 p-3 d-none d-md-flex">' +
                        '<div class="pictures-container mCustomScrollbar">' +
                            pictures +
                        '</div>' +
                    '</div>' +

                    '<button type="button" class="btn btn-theme d-block mx-auto d-md-none" id="btnCollapseFigureMediasDetail" data-toggle="collapse" data-target="#collapseFigureMediasDetail" aria-expanded="false" aria-controls="collapseFigureMediasDetail">' +
                        '<i class="fas fa-photo-video"></i> Voir les photos et vidéos' +
                    '</button>' +
                    '<div class="collapse" id="collapseFigureMediasDetail">' +
                        '<div class="row bg-darkness my-4 p-3">' +
                            '<div class="pictures-container mCustomScrollbar">' +
                                pictures +
                            '</div>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<div class="card">' +
                                '<div class="card-body">' +
                                    description +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +

                    '<div class="row">' +
                        '<div class="col-12">' +
                            '<p class="text-center mt-3 mb-0">' +
                                '<span class="created-figure-detail" data-toggle="tooltip" data-placement="bottom" title="Catégorie">' +
                                    '<i class="fas fa-tag"></i> '+response.figure.categorie +
                                '</span>' +
                                '<span class="created-figure-detail" data-toggle="tooltip" data-placement="bottom" title="Créée le">' +
                                    ' | <i class="fas fa-clock"></i> '+response.figure.created_at +
                                '</span>' +
                                updated_at +
                            '</p>' +
                        '</div>' +
                    '</div>'
                );

                $('#figureDetailsModal .modal-body .figure-comments-modal').append(messages);

                let nbComments = $('#figureDetailsModal .modal-body .figure-comments-modal .comment-modal').length;
                let showBtnLoadmore;
                if (nbComments >= LIMIT) {
                    showBtnLoadmore = 'd-block';
                } else {
                    showBtnLoadmore = 'd-none';
                }
                $('#figureDetailsModal .modal-body .figure-comments-modal').append(
                    '<button type="button" class="btn btn-theme '+showBtnLoadmore+' mx-auto" id="btn-load-more-comments-modal" data-offset="'+nbComments+'" data-limit="'+LIMIT+'">' +
                        '<i class="fas fa-plus"></i> Voir plus <i class="fas fa-spinner fa-spin d-none"></i>' +
                    '</button>'
                );
            },
            error: function (xhr, status, errorThrown) {
                $('#figureDetailsModal .modal-body section').addClass('d-none');
                $('#figureDetailsModal .modal-body .alert').removeClass('d-none');
            },
            complete: function () {
                if ($('#comment-content-modal').length) {
                    tinymce.get('comment-content-modal').setContent('');
                }
                $('#btn-comment-content-modal').attr('data-figure-id', id);

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
});