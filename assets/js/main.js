$(document).ready(function () {
    var scrollTrigger = 500; // px
    backToTop(scrollTrigger);
    $(window).on("scroll", function () {
        backToTop(scrollTrigger);
    });
    $("#back-to-top").on("click", function (e) {
        e.preventDefault();
        $("html,body").animate({
            scrollTop: 0
        }, 700);
    });
});
function backToTop(scrollTrigger) {
    var scrollTop = $(window).scrollTop();
    if (scrollTop > scrollTrigger) {
        $("#back-to-top").addClass("show");
    } else {
        $("#back-to-top").removeClass("show");
    }
}