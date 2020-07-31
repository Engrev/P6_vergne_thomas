$(document).ready(function () {
    if ($('#userCreationForm').hasClass('user-edit')) {
        $('#user_creation_password').prev().removeAttr('class');
        $('#user_creation_password').removeAttr('required');
        $('#user_creation_password').val('nopassword');
    }

    let user_role = $('#userCreationForm').data('user-role');
    $('#user_creation_roles option').each(function (key, option) {
        if (option.value === user_role) {
            option.setAttribute('selected', 'selected');
        }
    });
});