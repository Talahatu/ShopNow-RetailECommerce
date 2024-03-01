import $ from "jquery";

import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-fixedcolumns-bs5";
import "datatables.net-fixedheader-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-select-bs5";

$(function () {
    $("#navCourier").addClass("active");
    $("#navCourier > div").addClass("show");
    $("#mycourier > a").addClass("active");

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const baseUrl = window.location.protocol + "//" + window.location.host;

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
});
