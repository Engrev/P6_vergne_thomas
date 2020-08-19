$(document).ready(function () {
    $(document).on('click', '.btn-delete-picture-figure', function () {
        let obj = $(this);
        let picture_id = obj.data('id');
        let parent = obj.parent();
        let html = parent.html();
        let tr, figure;
        // Si, dans la page de modification
        if (obj.hasClass('btn-danger')) {
            tr = obj.parent().parent();
        // Sinon, dans la page de visualisation d'une figure
        } else {
            figure = obj.closest('.pictures-figure-detail');
        }

        $.confirm({
            icon: "fas fa-exclamation-triangle",
            title: "Suppression d'une photo",
            content: "Êtes-vous sûr de vouloir supprimer cette photo ? Cette action est irréversible !",
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
                        obj.parent().css('opacity', 1);
                        parent.html('<i class="fas fa-spinner fa-spin fa-3x"></i>');
                        $.ajax({
                            url: "/ajax/picture/delete/"+picture_id,
                            method: "DELETE",
                            dataType: "json",
                            success: function (response, status) {
                                // Si, dans la page de modification
                                if (obj.hasClass('btn-danger')) {
                                    tr.addClass('animate__animated animate__fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                        tr.remove();
                                    });
                                // Sinon, dans la page de visualisation d'une figure
                                } else {
                                    figure.addClass('animate__animated animate__fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                                        figure.remove();
                                    });
                                }
                            },
                            error: function (xhr, status, errorThrown) {
                                parent.html(html);
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