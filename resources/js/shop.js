import $ from "jquery";

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        $("#loader").addClass("d-none");
        $("#loader").removeClass("d-flex");
    }
};
$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;
    const query = "";
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
});
