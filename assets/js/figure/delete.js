$(document).ready(function () {
    $(document).on('click', '.btn-delete-figure', function () {
        let obj = $(this);
        let parent = obj.parent();
        let article, id;
        if (parent.hasClass('card-body')) {
            article = obj.closest('.figure');
            id = article.attr('id').replace('figure-', '');
        } else {
            id = obj.data('figure-id');
        }

        $.confirm({
            icon: "fas fa-exclamation-triangle",
            title: "Suppression d'une figure",
            content: "Êtes-vous sûr de vouloir supprimer cette figure ? Cette action est irréversible !",
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
                        obj.next().removeClass('d-none');
                        obj.remove();
                        $.ajax({
                            url: "/ajax/figure/delete/"+id,
                            method: "DELETE",
                            dataType: "json",
                            success: function (response, status) {
                                if (parent.hasClass('card-body')) {
                                    article.addClass('animate__animated animate__fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                        article.remove();
                                    });
                                } else {
                                    $('#figureDetailsModal').modal('hide');
                                    $('#figure-'+id).addClass('animate__animated animate__fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                        $('#figure-'+id).remove();
                                    });
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
                    }
                }
            }
        });
    });
});