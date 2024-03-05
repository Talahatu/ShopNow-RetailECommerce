import $ from "jquery";

import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-fixedcolumns-bs5";
import "datatables.net-fixedheader-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-select-bs5";

import Pusher from "pusher-js";

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

    const opsiLanguage = {
        emptyTable: "Tidak ada pesanan",
        lengthMenu: "Menampilkan _MENU_ Pesanan",
        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ Pesanan",
        search: "Cari",
        paginate: {
            previous: "Sebelumnya",
            next: "Selanjutnya",
        },
    };

    var DTcolumns = (optionType = "new") => {
        return [
            { data: "orderID" },
            { data: "destination_address" },
            {
                data: null,
                render: function (data, type, row) {
                    return data.distance + " KM";
                },
            },
            { data: "payment_method" },
            {
                data: null,
                render: function (data, type, row) {
                    if (optionType == "new") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle p-2 btnGroupAction" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><button class="btn btn-block btn-icon-text btn-info btn-accept dropdown-item mb-2" data-dia="${data.id}">Terima</button></li>
                            <li><button class="btn btn-block btn-icon-text btn-danger btn-reject dropdown-item" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Tolak</button></li>
                            </ul>
                        </div>
                            <button type="button" class="btn btn-block btn-lg btn-outline-info btn-detail p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian Pesanan</button>
                        </div>`;
                    } else if (optionType == "accepted") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            <button type="button" class="btn btn-block btn-lg btn-outline-success btn-send p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Kirim Pesanan</button>
                        </div>`;
                    } else if (optionType == "sent") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            Dalam Perjalanan
                        </div>`;
                    } else if (optionType == "done") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            Rp 10.000
                        </div>`;
                    } else {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <button class="btn btn-block btn-lg btn-outline-success btn-chat p-2" data-dia="${data.id}">Kirim Pesan</button>
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
                                  (col.title == "Aksi"
                                      ? ``
                                      : `<td>${col.title}</td>`) +
                                  (col.title == "Aksi"
                                      ? `<td>${col.data}</td>`
                                      : `<td>: ${col.data}</td>`) +
                                  "</tr>"
                            : "";
                    }).join("");

                    return data ? $("<table/>").append(data) : false;
                },
            },
        },
        language: opsiLanguage,
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
            columnOpenFix();
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
            url: "/getIdsFromOrder",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (responseA) {
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
                                "Terdapat masalah ketika menerima pesanan, hubungi staff divisi bantuan pelayanan"
                            );
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            },
        });
    });

    $(document).on("click", ".btn-reject", function () {
        const orderID = $(this).attr("data-dia");
        const button = $(this);
        const parent = $("#row_" + orderID);

        $("#exampleModalLabel").html("Masukan alasan penolakan");
        $("#exampleModal").find(".modal-footer").html(`
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Tutup</button>
        <button type="button" class="btn btn-primary" id="simpanAlasan">Simpan</button>
    `);
        $("#exampleModal").find(".modal-body").html(`
        <label for="reason">Alasan: </label>
        <input type="text" name="reason" id="reason" placeholder="Penolakan Karena..."
            class="form-control text-white">
        `);

        $(document).on("click", "#simpanAlasan", function () {
            const alasan = $("#reason").val();
            $.ajax({
                type: "POST",
                url: "/order/reject",
                data: {
                    _token: csrfToken,
                    orderID: orderID,
                    reason: alasan,
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
                        const modal = document.getElementById("exampleModal");
                        bootstrap.Modal.getInstance(modal).hide();
                    } else {
                        alert(
                            "Terdapat masalah ketika menerima pesanan, hubungi staff divisi bantuan pelayanan"
                        );
                    }
                },
                error: function (err) {
                    console.log(err);
                },
            });
        });
    });

    $(document).on("click", ".btn-detail", function () {
        const orderID = $(this).attr("data-dia");
        const IDOrder = $("#row_" + orderID)
            .find("td.dtr-control")
            .text();
        $("#exampleModalLabel").html(`Rincian Pesanan Nomor ${IDOrder}`);
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                            <label for="orderDate" class="col-sm-3 col-form-label">Tanggal Pesanan</label>
                            <div class="col-sm-9">
                                <label for="orderDate" class="col-form-label">:&nbsp;${
                                    ordersInfo.order_date
                                }</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="orderStatus" class="col-sm-3 col-form-label">Status Pesanan</label>
                            <div class="col-sm-9">
                                <label for="orderStatus" class="col-form-label">:&nbsp;${
                                    ordersInfo.orderStatus
                                }</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="destination" class="col-sm-3 col-form-label">Alamat Tujuan</label>
                            <div class="col-sm-9">
                                <label for="destination" class="col-form-label">:&nbsp;${
                                    ordersInfo.destination_address
                                }&nbsp;
                                    <span class="text-muted">&lpar;Jarak Perkiraan ${Math.round(
                                        products[0].distance
                                    )} KM&rpar;</span></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="paymentMethod" class="col-sm-3 col-form-label">Metode Pembayaran</label>
                            <div class="col-sm-9">
                                <label for="paymentMethod" class="col-form-label">:&nbsp;${
                                    ordersInfo.payment_method
                                }</label>
                            </div>
                        </div>
                        <hr>
                        <h3>produk Dipesan: </h3>
                        <div class="card table-responsive">
                            <table class="table table-hover sortable-table">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>SKU</th>
                                        <th>Jumlah</th>
                                        <th>Harga/pcs</th>
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
                        <h4 class="shippingFee">Ongkos Kirim: ${formatter.format(
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
                                              (col.title == "Aksi"
                                                  ? ``
                                                  : `<td>${col.title}</td>`) +
                                              (col.title == "Aksi"
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
                    language: opsiLanguage,
                    rowId: function (row) {
                        return "row_" + row.id;
                    },
                    columns: DTcolumns(type),
                });
                table.rows.add(data).draw();
                table.columns.adjust().draw();
                columnOpenFix();
            },
        });
    });
    $(document).on("click", ".btn-send", function () {
        const orderID = $(this).attr("data-dia");

        console.log("test 1");
        console.log($("#exampleModalLabel"));

        $("#exampleModalLabel").html(`Pilih kurir untuk pengiriman`);
        $("#exampleModal").find(".modal-footer").html(`
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            `);
        $("#exampleModal").find(".modal-body").html(`
        <div class="table-responsive">
                        <h3>Status Kurir Saat ini</h3>
                        <table class="table table-hover sortable-table">
                            <thead>
                                <tr>
                                    <td>No.</td>
                                    <td>Nama Kurir</td>
                                    <td>Pesanan pada kurir</td>
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form mt-4">
                        <label for="courier">Pilih kurir</label>
                        <select class="form-control text-light" name="courier" id="selectCourier"></select>
                    </div>`);
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

const columnOpenFix = () => {
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
};
