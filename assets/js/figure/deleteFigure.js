$(document).ready(function () {
    $(document).on('click', '.btn-delete-figure', function () {
        let obj = $(this);
        let article = obj.closest('.figure');
        let id = article.attr('id');

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
                                article.remove();
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