$(document).ready(function () {
    $(document).on('click', '.user-delete', function (e) {
        e.preventDefault();
        let obj = $(this);
        let href = obj.attr('href');
        let tr = obj.closest('tr');
        obj.parent().prev().html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: href,
            dataType: "json",
            method: "DELETE",
            success: function (response, status) {
                tr.addClass('animate__animated animate__fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    tr.remove();
                });
            },
            error: function (xhr, status, errorThrown) {
                obj.parent().prev().html('Actions');
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

    $(document).on('click', '.user-active', function () {
        let obj = $(this);
        let parent = obj.parent();
        let state = obj.data('state').split('#');
        let active = state[0];
        let id = state[1];
        parent.html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "/ajax/user/active",
            dataType: "json",
            method: "POST",
            data: {user_id: id},
            success: function (response, status) {
                switch (active) {
                    case '0':
                        parent.html('<i class="fas fa-check text-success user-active" data-state="1#'+id+'" data-toggle="tooltip" data-placement="bottom" title="Activer ?"></i>');
                        break;
                    case '1':
                        parent.html('<i class="fas fa-times text-danger user-active" data-state="0#'+id+'" data-toggle="tooltip" data-placement="bottom" title="Désactiver ?"></i>');
                        break;
                }
                parent.children().tooltip('enable');
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