import $ from "jquery";

import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-fixedcolumns-bs5";
import "datatables.net-fixedheader-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-select-bs5";
import moment from "moment/moment";
import "moment/locale/id";
import Pikaday from "pikaday";
import "pikaday/css/pikaday.css";
// import Pusher from "pusher-js";
import "select2";
import "select2/dist/css/select2.min.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.min.css";

import "jquery-mask-plugin";

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        $("#loader").addClass("d-none");
        $("#loader").removeClass("d-flex");
    }
};
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
                            <button type="button" class="btn btn-block btn-lg btn-outline-info btn-detail p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian</button>
                        </div>`;
                    } else if (optionType == "accepted") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <button type="button" class="btn btn-block btn-lg btn-outline-info btn-detail p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian</button>
                            <button type="button" class="btn btn-block btn-lg btn-outline-success btn-send p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Kirim</button>
                        </div>`;
                    } else if (optionType == "sent") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <button type="button" class="btn btn-block btn-lg btn-outline-success btn-detail p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian Pesanan</button>
                        <button type="button" class="btn btn-block btn-lg btn-outline-info btn-detail-delivery p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian Pengiriman</button>
                        </div>`;
                    } else if (optionType == "done") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <button type="button" class="btn btn-block btn-lg btn-outline-info btn-detail p-2" data-dia="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian</button>
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
                                  (col.title == "Aksi/Keterangan"
                                      ? ``
                                      : `<td>${col.title}</td>`) +
                                  (col.title == "Aksi/Keterangan"
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
        columnDefs: [{ targets: [1, 2], className: "text-end" }],
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
                console.log(ordersInfo);
                for (const item of products) {
                    tableRow += `
                    <tr>
                        <td class="text-center"><img src="${baseUrl}/productimages/${item.iname}"></img></td>
                        <td>${item.pname}</td>
                        <td>${item.sku}</td>
                        <td class="text-end">${item.qty}</td>
                        <td class="text-end">${item.price}</td>
                        <td class="text-end">${item.subtotal}</td>
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
                            <label for="Nama Pelanggan" class="col-sm-3 col-form-label">Nama Pelanggan</label>
                            <div class="col-sm-9">
                                <label for="destination" class="col-form-label">:&nbsp;${
                                    ordersInfo.user.name
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
                        <h3>Produk Dipesan: </h3>
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

    $(document).on("click", ".btn-detail-delivery", function () {
        const orderID = $(this).attr("data-dia");
        const IDOrder = $("#row_" + orderID)
            .find("td.dtr-control")
            .text();
        $("#exampleModalLabel").html(`Rincian Pengiriman ${IDOrder}`);
        $("#exampleModal").find(".modal-footer").html(`
        
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Selesaikan Pengiriman</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
        `);

        $.ajax({
            type: "POST",
            url: "/delivery/detail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                const data = response;
                const delivery = data.deliveries[0];
                // Tanggal pickup/mulai pengiriman, tanggal selesai pengirimaan
                let tableRow = `
                    <tr>
                        <td>Mulai Pengiriman</td>
                        <td>${
                            delivery.pickup_date ? delivery.pickup_date : "-"
                        }</td>
                        <td class="text-end">${delivery.courier.name}</td>
                    </tr>
                    <tr>
                        <td>Sampai Tujuan</td>
                        <td>${
                            delivery.arrive_date ? delivery.arrive_date : "-"
                        }</td>
                        <td class="text-end">${delivery.courier.name}</td>
                    </tr>
                    `;
                const downContent =
                    data.payment_method == "cod"
                        ? `<h5 class="subtotal muted">Uang Saku Diberikan: ${formatter.format(
                              delivery.feeAssigned
                          )}</h5>
                <h5 class="shippingFee muted">Uang Saku Digunakan: ${formatter.format(
                    delivery.feeUsed
                )}</h5>
                <h5 class="totalAll muted">Total Pesanan: ${formatter.format(
                    data.total
                )}</h5>
                <h4>Nominal Uang Diterima Dari Kurir: ${formatter.format(
                    data.total + (delivery.feeAssigned - delivery.feeUsed)
                )}</h4>
                <small>*Pastikan telah menerima uang dari kurir!</small>`
                        : `<h5 class="totalAll muted">Total Pesanan: ${formatter.format(
                              data.total
                          )}</h5>`;

                $("#exampleModal").find(".modal-body").html(`
                <div class="orderDetails">
                        <div class="card table-responsive">
                            <table class="table table-hover sortable-table">
                                <thead>
                                    <tr>
                                        <th>Keterangan</th>
                                        <th>Tanggal</th>
                                        <th>Nama Kurir</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    ${tableRow}
                                </tbody>
                            </table>
                        </div>
                        <div class="footer mt-1 text-end" style="padding-bottom:0px;">
                        <h5 class="subtotal muted">Uang Saku Diberikan: ${formatter.format(
                            delivery.feeAssigned
                        )}</h5>
                        <h5 class="shippingFee muted">Uang Saku Digunakan: ${formatter.format(
                            delivery.feeUsed
                        )}</h5>
                        <h5 class="totalAll muted">Total Pesanan: ${formatter.format(
                            data.total
                        )}</h5>
                        <h4>Nominal Uang Diterima Dari Kurir: ${formatter.format(
                            data.total +
                                (delivery.feeAssigned - delivery.feeUsed)
                        )}</h4>
                        <small>*Pastikan telah menerima uang dari kurir!</small>
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
                                              (col.title == "Aksi/Keterangan"
                                                  ? ``
                                                  : `<td>${col.title}</td>`) +
                                              (col.title == "Aksi/Keterangan"
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
                    columnDefs: [{ targets: [0, 2], className: "text-end" }],
                });
                table.rows.add(data).draw();
                table.columns.adjust().draw();
                columnOpenFix();
            },
        });
    });
    $(document).on("click", ".btn-send", function () {
        // Show Modal
        const orderID = $(this).attr("data-dia");
        let courierID = "";
        let date = "";
        let saku = 0;
        const parent = $("#row_" + orderID);
        $("#exampleModalLabel").html(`Pilih kurir untuk pengiriman`);
        $("#exampleModal").find(".modal-footer").html(`
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveDelivery" disabled>Simpan</button>
            `);

        let orderPaymentType = null;
        // Query couriers for table and select2
        $.ajax({
            type: "POST",
            url: "/getOrderPaymentType",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
        })
            .then(function (response1) {
                orderPaymentType = response1.type;
                return $.ajax({
                    type: "POST",
                    url: "/getAllCourier",
                    data: {
                        _token: csrfToken,
                    },
                });
            })
            .then(function (response) {
                const couriers = response.couriers;
                let bodyRows = ``;
                for (let i = 0; i < couriers.length; i++) {
                    bodyRows += `
                <tr>
                    <td>${i + 1}</td>
                    <td>${couriers[i].name}</td>
                    <td>${couriers[i].deliveries.length}</td>
                    <td>${
                        couriers[i].deliveries.length == 0
                            ? "Tersedia"
                            : "Sedang dalam perjalanan"
                    }</td>
                </tr>
                `;
                }
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
                            ${bodyRows}
                        </tbody>
                    </table>
                </div>
                <div class="form mt-4">
                    <label for="courier">Pilih kurir</label>
                    <select class="form-control text-light" name="courier" id="selectCourier"></select>
                </div>
                <div class="form mt-4">
                    <label for="courier">Pilih Tanggal Kirim</label>
                    <div id="datepicker-popup" class="input-group date">
                      <input type="text" class="form-control form-control-sm datepicker">
                      <span class="input-group-addon input-group-append border-left datepicker-icon">
                        <span class="mdi mdi-calendar input-group-text"></span>
                      </span>
                    </div>
                </div>`);

                if (orderPaymentType == "cod") {
                    $("#exampleModal").find(".modal-body").append(`
                        <div class="form mt-4">
                            <label for="Uang Saku">Uang Operasional</label>
                            <div class="input-group">
                                <span class="input-group-text dark-background text-light">Rp</span>
                                <input id="UangSaku" name="Uang Saku" type="text" class="form-control text-light" placeholder="Masukan nominal uang operasional">
                            </div>
                        </div>
                    `);
                    $("#UangSaku").mask("#.##0", {
                        reverse: true,
                    });

                    $(document).on("input", "#UangSaku", function () {
                        saku = $(this).cleanVal();
                        checkModalRequirement(date, courierID, saku);
                    });
                }
                var picker = new Pikaday({
                    field: $(".datepicker")[0],
                    i18n: {
                        previousMonth: "Bulan Sebelumnya",
                        nextMonth: "Bulan Selanjutnya",
                        months: moment.localeData().months(),
                        weekdays: moment.localeData().weekdays(),
                        weekdaysShort: moment.localeData().weekdaysShort(),
                    },
                    format: "dddd, D MMMM YYYY",
                    defaultDate: new Date(),
                    minDate: new Date(),
                    onSelect: function (value) {
                        date = this.toString("YYYY-MM-DD");
                        checkModalRequirement(date, courierID, saku);
                    },
                });
                picker.setDate(new Date());

                $(document).on("click", ".datepicker-icon", function () {
                    picker.show();
                });

                $("#selectCourier")
                    .select2({
                        placeholder: "Pilih Kurir",
                        allowClear: true,
                        dropdownParent: $("#exampleModal"),
                        theme: "bootstrap",
                        ajax: {
                            type: "POST",
                            url: "/fetch-courier",
                            data: function (param) {
                                return {
                                    _token: csrfToken,
                                    searchTerm: param.term,
                                };
                            },
                            processResults: function (response) {
                                return {
                                    results: $.map(
                                        response.data,
                                        function (item) {
                                            return {
                                                text: item.name,
                                                id: item.id,
                                            };
                                        }
                                    ),
                                };
                            },
                        },
                    })
                    .on("select2:select", function (e) {
                        courierID = $(e.currentTarget).val();
                        checkModalRequirement(date, courierID, saku);
                    })
                    .on("select2:unselect", function (e) {
                        courierID = "";
                        checkModalRequirement(date, courierID, saku);
                    });
            })
            .catch(function (param) {
                console.log(param);
            });

        // Submit Data
        $("#saveDelivery").on("click", function () {
            $("#loader").addClass("d-flex");
            $("#loader").removeClass("d-none");
            $.ajax({
                type: "POST",
                url: "/pickCourier",
                data: {
                    _token: csrfToken,
                    orderID: orderID,
                    deliveryDate: date,
                    courierID: courierID,
                    operational: saku,
                },
                success: function (response) {
                    if (response) {
                        const modal = document.getElementById("exampleModal");
                        bootstrap.Modal.getInstance(modal).hide();
                        $("#loader").addClass("d-none");
                        $("#loader").removeClass("d-flex");
                        $(parent).next().hasClass("child")
                            ? $(parent).next().remove()
                            : "";
                        $(parent).remove();
                    } else {
                        console.log("Error Submit");
                    }
                },
                error: function (param) {
                    console.log(param);
                },
            });
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

const checkModalRequirement = (date, courier, saku) => {
    if (date != "" && courier != "" && saku > 0) {
        $("#saveDelivery").attr("disabled", false);
        return;
    }
    $("#saveDelivery").attr("disabled", true);
};
