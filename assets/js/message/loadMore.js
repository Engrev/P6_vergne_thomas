$(document).ready(function () {
    $(document).on('click', '#btn-load-more-comments-modal', function () {
        let obj = $(this);
        obj.children('.fa-spinner').removeClass('d-none');
        let id = $('#btn-comment-content-modal').data('figure-id');
        let offset = obj.data('offset');
        let limit = obj.data('limit');

        $.ajax({
            url: "/ajax/message/loadmore",
            method: "POST",
            data: {figure_id: id, offset: offset, limit: limit},
            dataType: "json",
            success: function (response, status) {
                obj.children('.fa-spinner').addClass('d-none');

                let messages = '';
                $.each(response.messages, function (key, message) {
                    messages +=
                        '<article class="comment-modal animate__animated animate__fadeIn" id="comment-'+message.id+'">' +
                            '<table>' +
                                '<tbody>' +
                                    '<tr>' +
                                        '<td>' +
                                            '<img src="/'+message.user.avatar+'" alt="Photo de profil">' +
                                        '</td>' +
                                        '<td>' +
                                            '<p class="text-muted mb-0">'+message.user.username+' &bull; '+message.date+'</p>' +
                                            '<article class="comment-body">'+message.content+'</article>' +
                                        '</td>' +
                                    '</tr>' +
                                '</tbody>' +
                            '</table>' +
                        '</article>'
                });
                obj.before(messages);

                let nbComments = $('#figureDetailsModal .modal-body .figure-comments-modal .comment-modal').length;
                obj.attr('data-offset', nbComments).attr('data-limit', response.limit);

                if (response.hideBtn === true) {
                    obj.removeClass('d-block').addClass('d-none');
                }
            },
            error: function (xhr, status, errorThrown) {
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