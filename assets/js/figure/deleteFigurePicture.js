$(document).ready(function () {
    $(document).on('click', '.btn-delete-picture-figure', function () {
        let obj = $(this);
        let picture_id = obj.data('id');
        let parent = obj.parent();
        let tr, figure;
        if (obj.hasClass('btn-danger')) {
            tr = obj.parent().parent();
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
                        parent.html('<i class="fas fa-spinner fa-spin fa-3x"></i>');
                        $.ajax({
                            url: "/ajax/picture/delete/"+picture_id,
                            method: "DELETE",
                            dataType: "json",
                            success: function (response, status) {
                                if (obj.hasClass('btn-danger')) {
                                    tr.remove();
                                } else {
                                    figure.remove();
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