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

    var DTcolumns = () => {
        return [
            { data: "id" },
            { data: "name" },
            {
                data: "operationalFee",
            },
            { data: "status" },
            {
                data: null,
                render: function (data, type, row) {
                    const html = ``;
                    return html;
                },
            },
        ];
    };

    var table = $("#myTable").DataTable({
        responsive: {
            details: {
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.hidden
                            ? '<tr data-dt-row="' +
                                  col.rowIndex +
                                  '" data-dt-column="' +
                                  col.columnIndex +
                                  '">' +
                                  (col.title == "Action"
                                      ? ``
                                      : `<td>${col.title}</td>`) +
                                  (col.title == "Action"
                                      ? `<td>${col.data}</td>`
                                      : `<td>: ${col.data}</td>`) +
                                  "</tr>"
                            : "";
                    }).join("");
                    return data ? $("<table/>").append(data) : false;
                },
            },
        },
        language: {
            emptyTable: "No Courier available",
        },
        rowId: function (row) {
            return "row_" + row.id;
        },
        columns: DTcolumns(),
    });
});
