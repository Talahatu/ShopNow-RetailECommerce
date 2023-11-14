import "select2/dist/css/select2.min.css";
import $ from "jquery";
import "select2";
$(function () {
    $("#navProduct").addClass("active");
    $("#navProduct > div").addClass("show");
    $("#newproduct > a").addClass("active");
    $("#selectCategory").select2();
});
