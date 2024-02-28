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
    const baseUrl = window.location.protocol + "//" + window.location.host;

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

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
                            <button type="button" class="btn btn-block btn-lg btn-outline-success btn-send p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Send Product</button>
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

            // datatable.net type column somehow not working, this is a makeshift solution
            $("td")
                .filter(function () {
                    return $(this).children("div").length === 0;
                })
                .on("click", function () {
                    const parent = $(this).parent();
                    const firstChild = $(parent).children().first();
                    if (!$(this).hasClass("dtr-control")) $(firstChild).click();
                });
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

    $(document).on("click", ".btn-reject", function () {
        const orderID = $(this).attr("data-dia");
        const button = $(this);
        const parent = $("#row_" + orderID);

        $.ajax({
            type: "POST",
            url: "/order/reject",
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
                        "Problem occured when rejecting orders, please contact customer support"
                    );
                }
            },
            error: function (err) {
                console.log(err);
            },
        });
    });

    $(document).on("click", ".btn-detail", function () {
        const orderID = $(this).attr("data-dia");
        const IDOrder = $("#row_" + orderID)
            .find("td.dtr-control")
            .text();
        $("#exampleModalLabel").html(`${IDOrder}'s Details`);
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        `);
        $.ajax({
            type: "POST",
            url: "/order/detail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                const ordersInfo = response.info;
                const products = response.products;
                let tableRow = "";
                for (const item of products) {
                    tableRow += `
                    <tr>
                        <td class="text-center"><img src="${baseUrl}/productimages/${item.iname}"></img></td>
                        <td>${item.pname}</td>
                        <td>${item.sku}</td>
                        <td>${item.qty}</td>
                        <td>${item.price}</td>
                        <td>${item.subtotal}</td>
                    </tr>
                    `;
                }
                $("#exampleModal").find(".modal-body").html(`
                <div class="orderDetails">
                    <div class="forms-sample">
                        <div class="form-group row">
                            <label for="orderDate" class="col-sm-3 col-form-label">Order Date</label>
                            <div class="col-sm-9">
                                <label for="orderDate" class="col-form-label">:&nbsp;${
                                    ordersInfo.order_date
                                }</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="orderStatus" class="col-sm-3 col-form-label">Order Status</label>
                            <div class="col-sm-9">
                                <label for="orderStatus" class="col-form-label">:&nbsp;${
                                    ordersInfo.orderStatus
                                }</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="destination" class="col-sm-3 col-form-label">Destination Address</label>
                            <div class="col-sm-9">
                                <label for="destination" class="col-form-label">:&nbsp;${
                                    ordersInfo.destination_address
                                }&nbsp;
                                    <span class="text-muted">&lpar;Estimated ${Math.round(
                                        products[0].distance
                                    )} KM&rpar;</span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="paymentMethod" class="col-sm-3 col-form-label">Payment Method</label>
                            <div class="col-sm-9">
                                <label for="paymentMethod" class="col-form-label">:&nbsp;${
                                    ordersInfo.payment_method
                                }</label>
                            </div>
                        </div>
                        <hr>
                        <h3>Product Ordered</h3>
                        <div class="card table-responsive">
                            <table class="table table-hover sortable-table">
                                <thead>
                                    <tr>
                                        <th>Product Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                        <th>Price/pcs</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    ${tableRow}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="footer mt-1 text-end" style="padding-bottom:0px;">
                        <h4 class="subtotal">Subtotal: ${formatter.format(
                            ordersInfo.subtotal
                        )}</h4>
                        <h4 class="shippingFee">Shipping Fee: ${formatter.format(
                            ordersInfo.shippingFee
                        )}</h4>
                        <h2 class="totalAll">Total: ${formatter.format(
                            ordersInfo.total
                        )}</h2>
                    </div>
                </div>
                `);
            },
            error: function (param) {
                console.log(param);
            },
        });
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
                // datatable.net type column somehow not working, this is a makeshift solution
                $("td")
                    .filter(function () {
                        return $(this).children("div").length === 0;
                    })
                    .on("click", function () {
                        const parent = $(this).parent();
                        const firstChild = $(parent).children().first();
                        if (!$(this).hasClass("dtr-control"))
                            $(firstChild).click();
                    });
            },
        });
    });
    $(document).on("click", ".btn-send", function () {
        const orderID = $(this).attr("data-dia");

        console.log("test 1");
        console.log($("#exampleModalLabel"));

        $("#exampleModalLabel").html(`Pick a courier to deliver the products`);
        $("#exampleModal").find(".modal-footer").html(`
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            `);
        $.ajax({
            type: "POST",
            url: "/pickCourier",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {},
        });
    });
});
