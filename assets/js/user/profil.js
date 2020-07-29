$(document).ready(function () {
    if ($('#input-user-avatar').length) {
        $('#profil_original_avatar').val($('#collapseEditAvatar').parent().parent().prev().find('img').attr('src'));
    }
});