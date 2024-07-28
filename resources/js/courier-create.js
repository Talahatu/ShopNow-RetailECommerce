import $ from "jquery";
import "select2/dist/css/select2.min.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.min.css";
import "select2";
import AutoNumeric from "autonumeric";

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        $("#loader").addClass("d-none");
        $("#loader").removeClass("d-flex");
    }
};
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.fn.select2.defaults.set("theme", "bootstrap");
    $("#navCourier").addClass("active");
    $("#navCourier > div").addClass("show");
    $("#newCourier > a").addClass("active");

    $(document).on("submit", "#formcreatecourier", function (e) {
        $("#loader").addClass("d-flex");
        $("#loader").removeClass("d-none");
    });
});
