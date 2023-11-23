import $ from "jquery";

$(document).ready(function () {
    $(document).on("click", "#logout", function () {
        $(this).parent().submit();
    });

    $("#alertClose").on("click", function () {
        $(this).parent()[0].remove();
    });
    $("#btnSearch").on("click", function () {
        let query = $("#searchInput").val() ? $("#searchInput").val() : "all";
        $(this).attr("href", "/search/" + query);
    });
});
