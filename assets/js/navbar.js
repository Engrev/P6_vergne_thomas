$(document).ready(function () {
    $(document).on("show.bs.dropdown", "#navbarSupportedContent .dropdown", function () {
        $(this).children().addClass("open");
    });
    $(document).on("hide.bs.dropdown", "#navbarSupportedContent .dropdown", function () {
        $(this).children().removeClass("open");
    });
});