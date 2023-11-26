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
                            <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><button class="btn btn-block btn-icon-text btn-info btn-accept dropdown-item mb-2" data-dia="${data.id}">Accept</button></li>
                            <li><button class="btn btn-block btn-icon-text btn-danger btn-reject dropdown-item" data-dia="${data.id}">Reject</button></li>
                            </ul>
                        </div>
                            <button class="btn btn-block btn-lg btn-outline-info btn-detail p-2" data-dia="${data.id}">Detail</button>
                        </div>`;
                    } else if (optionType == "process") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            <button class="btn btn-block btn-lg btn-outline-success btn-send p-2" data-dia="${data.id}">Send Order</button>
                        </div>`;
                    } else if (optionType == "sent") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            On Delivery
                        </div>`;
                    } else if (optionType == "finish") {
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
        responsive: true,
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
            console.log(data);
            table.rows.add(data).draw();
        },
        error: function (err) {
            console.log(err);
        },
    });

    $(document).on("click", ".order-type", function () {
        const type = $(this).attr("data-type");
        $.ajax({
            type: "POST",
            url: "/fetch/order/repopulate",
            data: {
                _token: csrfToken,
                type: type,
            },
            success: function (response) {
                const data = response.data;
                console.log(data);
                table.clear().draw();
                table.destroy();
                table = $("#myTable").DataTable({
                    responsive: true,
                    language: {
                        emptyTable: "No orders available",
                    },
                    rowId: function (row) {
                        return "row_" + row.id;
                    },
                    columns: DTcolumns(type),
                });
                table.rows.add(data).draw();
            },
        });
    });
});
