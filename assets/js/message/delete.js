$(document).ready(function () {
    $(document).on('click', '.btn-delete-comment-figure', function () {
        let obj = $(this);
        let section = obj.closest('.figure-comments-modal');
        let parent = obj.parent();
        let id = parent.attr('id').replace('comment-', '');

        $.confirm({
            icon: "fas fa-exclamation-triangle",
            title: "Suppression d'un message",
            content: "Êtes-vous sûr de vouloir supprimer cet message ? Cette action est irréversible !",
            columnClass: "medium",
            type: "orange",
            buttons: {
                cancel: {
                    text: "Non",
                    btnClass: "btn-default"
                },
                confirm: {
                    text: "Oui",
                    btnClass: "btn-red",
                    action: function() {
                        obj.siblings('.comment-figure-detail-overlay').html('<i class="fas fa-spinner fa-spin fa-3x"></i>');
                        obj.siblings('.comment-figure-detail-overlay').css('opacity', 1);
                        obj.remove();
                        $.ajax({
                            url: "/ajax/message/delete/"+id,
                            method: "DELETE",
                            data: {message_id: id},
                            dataType: "json",
                            success: function (response, status) {
                                parent.addClass('animate__animated animate__fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                    parent.remove();
                                });
                            },
                            error: function (xhr, status, errorThrown) {
                                parent.append(obj);
                                obj.siblings('.comment-figure-detail-overlay').html('');
                                obj.siblings('.comment-figure-detail-overlay').css('opacity', 0);
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
                            },
                            complete: function () {
                                if (section.children('article').length == 1) {
                                    section.append('<p class="mb-0 text-center no-message">Aucun message.</p>');
                                }
                            }
                        });
                    }
                }
            }
        });
    });
});