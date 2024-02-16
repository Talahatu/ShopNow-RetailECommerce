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
    $("#navOrder").addClass("active");
    $("#navOrder > div").addClass("show");
    $("#myorder > a").addClass("active");
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    var DTcolumns = (optionType = "new") => {
        return [
            { data: "orderID" },
            { data: "destination_address" },
            {
                data: "distance",
            },
            { data: "payment_method" },
            {
                data: null,
                render: function (data, type, row) {
                    if (optionType == "new") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle p-2 btnGroupAction" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><button class="btn btn-block btn-icon-text btn-info btn-accept dropdown-item mb-2" data-dia="${data.id}">Accept</button></li>
                            <li><button class="btn btn-block btn-icon-text btn-danger btn-reject dropdown-item" data-dia="${data.id}">Reject</button></li>
                            </ul>
                        </div>
                            <button type="button" class="btn btn-block btn-lg btn-outline-info btn-detail p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Detail</button>
                        </div>`;
                    } else if (optionType == "accepted") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            <button class="btn btn-block btn-lg btn-outline-success btn-send p-2" data-dia="${data.id}">Send Order</button>
                        </div>`;
                    } else if (optionType == "sent") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            On Delivery
                        </div>`;
                    } else if (optionType == "done") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            Rp 10.000
                        </div>`;
                    } else {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <button class="btn btn-block btn-lg btn-outline-success btn-chat p-2" data-dia="${data.id}">Chat</button>
                        </div>`;
                    }
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
            emptyTable: "No orders available",
        },
        rowId: function (row) {
            return "row_" + row.id;
        },
        columns: DTcolumns(),
    });

    $.ajax({
        type: "POST",
        url: "/fetch/order/new",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            const data = response.data;
            table.rows.add(data).draw();
            table.columns.adjust().draw();
        },
        error: function (err) {
            console.log(err);
        },
    });

    $(document).on("click", ".btn-accept", function () {
        const orderID = $(this).attr("data-dia");
        const button = $(this);
        const parent = $("#row_" + orderID);

        $.ajax({
            type: "POST",
            url: "/order/accept",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                if (response) {
                    if ($(parent).hasClass("parent"))
                        $(button)
                            .parent()
                            .parent()
                            .parent()
                            .parent()
                            .parent()
                            .parent()
                            .parent()
                            .parent()
                            .parent()
                            .remove();
                    $(parent).remove();
                } else {
                    alert(
                        "Problem occured when accepting orders, please contact customer support"
                    );
                }
            },
            error: function (err) {
                console.log(err);
            },
        });
    });

    $(document).on("click", ".btn-reject", function () {});

    $(document).on("click", ".btn-detail", function () {
        const orderID = $(this).attr("data-dia");
        const IDOrder = $("#row_" + orderID)
            .find("td.dtr-control")
            .text();
        $("#exampleModalLabel").html(`${IDOrder}'s Details`);
        $("#exampleModal").find(".modal-body").html(``);
    });

    $(document).on("click", ".order-type", function () {
        const type = $(this).attr("data-type");
        console.log(type);
        $.ajax({
            type: "POST",
            url: "/fetch/order/repopulate",
            data: {
                _token: csrfToken,
                type: type,
            },
            success: function (response) {
                const data = response.data;
                table.clear().draw();
                table.destroy();
                table = $("#myTable").DataTable({
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

                                return data
                                    ? $("<table/>").append(data)
                                    : false;
                            },
                        },
                    },
                    language: {
                        emptyTable: "No orders available",
                    },
                    rowId: function (row) {
                        return "row_" + row.id;
                    },
                    columns: DTcolumns(type),
                });
                table.rows.add(data).draw();
                table.columns.adjust().draw();
            },
        });
    });
});
