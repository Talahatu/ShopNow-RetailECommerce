import DataTable from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-fixedcolumns-bs5";
import "datatables.net-fixedheader-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-select-bs5";

$(function () {
    $("#navProduct").addClass("active");
    $("#navProduct > div").addClass("show");
    $("#myproduct > a").addClass("active");
    let table = new DataTable("#myTable", {
        responsive: true,
    });
});
