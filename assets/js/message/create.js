$(document).ready(function () {
    $(document).on('click', '#btn-comment-content-modal', function () {
        let obj = $(this);
        let message = tinymce.activeEditor.getContent();
        let id = $('#btn-comment-content-modal').data('figure-id');
        obj.children('.fa-spinner').removeClass('d-none');

        $.ajax({
            url: "/ajax/message/create",
            method: "POST",
            data: {content: message, figure_id: id},
            dataType: "json",
            success: function (response, status) {
                tinymce.activeEditor.setContent('');
                obj.children('.fa-spinner').addClass('d-none');
                obj.parent().siblings('.no-message').remove();
                obj.parent().next().after(
                    '<article class="comment-modal animate__animated animate__fadeIn" id="comment-'+response.message.id+'">' +
                        '<div class="comment-figure-detail-overlay"></div>' +
                        '<table>' +
                            '<tbody>' +
                                '<tr>' +
                                    '<td>' +
                                        '<img src="/'+response.message.user.avatar+'" alt="Photo de profil">' +
                                    '</td>' +
                                    '<td>' +
                                        '<p class="text-muted mb-0">'+response.message.user.username+' &bull; A l\'instant</p>' +
                                        '<article class="comment-body">'+message+'</article>' +
                                    '</td>' +
                                '</tr>' +
                            '</tbody>' +
                        '</table>' +
                        '<button type="button" class="btn-comment btn-delete-comment-figure" data-toggle="tooltip" data-placement="bottom" title="Supprimer"></button>' +
                    '</article>'
                );
            },
            error: function (xhr, status, errorThrown) {
                obj.children('.fa-spinner').addClass('d-none');
                $.alert({
                    icon: "fas fa-exclamation-triangle",
                    title: "Erreur !",
                    content: "Une erreur est survenue.",
                    type: "red",
                    buttons: {
                        cancel: {
                            text: "Ok",
                            btnClass: "btn-default"
                        }
                    }
                });
            }
        });
    });
});