import $ from "jquery";

$(function () {
    $("#btnLogoutSeller").on("click", function () {
        $(this).parent().submit();
    });
});
