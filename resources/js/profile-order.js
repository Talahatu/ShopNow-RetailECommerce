import $ from "jquery";
import moment from "moment/moment";
import "moment/locale/id";
import "bootstrap-star-rating/css/star-rating.min.css";
import "bootstrap-star-rating/js/star-rating.min.js";

$(function () {
    $(".nav-link-profile").removeClass("active");
    $("#v-tabs-order-tab-lg").addClass("active");
    $("#v-tabs-order-tab").addClass("active");

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    let globalOrder = null;

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    const baseUrl = window.location.protocol + "//" + window.location.host;

    $(document).on("click", ".btn-profile-order-detail", function () {
        const orderID = $(this).attr("data-di");
        $("#modalTitle").html("Rincian Pesanan");
        if ($(this).attr("current-tab") == "new") {
            $("#modalFooter").html(`
            <button type="button" class="btn btn-danger" id="btnCancelOrder">Batalkan Pesanan</button>
            <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>`);
        } else {
            $("#modalFooter").html(`
        <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>`);
        }

        $.ajax({
            type: "POST",
            url: "/profile/order/detail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                const order = response;
                globalOrder = order;
                let finishProof = ``;
                let finishFooter = ``;
                let map = null;
                let riwayatPesanan = `
                    <tr>
                        <td>${moment(order.order_date).format(
                            "dddd, D MMMM YYYY"
                        )}</td>
                        <td>Pesanan pertama kali dibuat</td>
                    </tr>`;

                let products = ``;
                if (order.accept_date) {
                    riwayatPesanan += `
                        <tr>
                            <td>${moment(order.accept_date).format(
                                "dddd, D MMMM YYYY"
                            )}</td>
                            <td>Pesanan diterima oleh seller</td>
                        </tr>
                    `;
                }
                if (order.cancel_date) {
                    riwayatPesanan += `
                        <tr>
                            <td>${moment(order.cancel_date).format(
                                "dddd, D MMMM YYYY"
                            )}</td>
                            <td>Pesanan dibatalkan oleh seller</td>
                        </tr>
                    `;
                }
                if (order.deliveries.length > 0) {
                    const delivery = order.deliveries[0];

                    riwayatPesanan += `
                        <tr>
                            <td>${moment(delivery.start_date).format(
                                "dddd, D MMMM YYYY"
                            )}</td>
                            <td>Pesanan telah ditugaskan kepada kurir</td>
                        </tr>
                    `;

                    if (delivery.pickup_date != null) {
                        riwayatPesanan += `
                        <tr>
                            <td>${moment(order.pickup_date).format(
                                "dddd, D MMMM YYYY"
                            )}</td>
                            <td>Pesanan telah diambil dari toko dan sedang dalam perjalanan menuju ${
                                order.destination_address
                            }</td>
                        </tr>
                        `;

                        //======================== When Courier Finish START ====================================
                        if (delivery.arrive_date != null) {
                            riwayatPesanan += `
                                <tr>
                                    <td>${moment(order.arrive_date).format(
                                        "dddd, D MMMM YYYY"
                                    )}</td>
                                    <td>Pesanan telah sampai di tujuan pada ${
                                        order.destination_address
                                    }.</td>
                                </tr>
                            `;

                            finishProof = `
                            <div class="proof">
                                <h5>Bukti foto dari kurir: </h5>
                                <img src="${baseUrl}/deliveryProof/${delivery.proofImage}" style="width:200px;height:200px;object-fit:cover;"></img>
                            </div>`;

                            finishFooter = `
                                <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary" id="btnFinishOrder" data-bs-toggle="modal" data-bs-target="#exampleModal2">Selesaikan</button>
                            `;

                            $("#modalFooter").html(finishFooter);
                        }
                        //======================== When Courier Finish END ====================================
                    }
                }

                for (const iterator of order.details) {
                    products += `
                        <tr>
                            <td><img src="${baseUrl}/productimages/${iterator.product.images[0].name}" style="width:40px;height:40px;object-fit:cover;"></img></td>
                            <td>${iterator.product.name}</td>
                            <td>${iterator.qty}</td>
                            <td>${iterator.price}</td>
                        </tr>
                    `;
                }

                $("#modalBody").html(`
                <div class="row">
                        <div class="form-group col-6">
                            <label for="orderNumber"><b>Order ID:&nbsp;</b></label>
                            <div>
                                <label for="orderNumber">${
                                    order.orderID
                                }</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="orderNumber"><b>Nama Toko:&nbsp;</b></label>
                            <div>
                                <label for="orderNumber">${
                                    order.shop.name
                                }</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="orderNumber"><b>Kontak Toko:&nbsp;</b></label>
                            <div>
                                <label for="orderNumber">${
                                    order.shop.phoneNumber
                                }</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="orderNumber"><b>Jenis Pembayaran:&nbsp;</b></label>
                            <div>
                                <label for="orderNumber">${
                                    order.payment_method == "cod"
                                        ? "Bayar di tempat (COD)"
                                        : "Saldo"
                                }</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="orderNumber"><b>Alamat Toko:&nbsp;</b></label>
                            <div>
                                <label for="orderNumber">${
                                    order.shop.address
                                }</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="orderNumber"><b>Alamat Tujuan:&nbsp;</b></label>
                            <div>
                                <label for="orderNumber">${
                                    order.destination_address
                                }</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h1>Riwayat Pesanan</h1>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${riwayatPesanan}
                        </tbody>
                    </table>
                    ${finishProof}
                    <hr>
                    <h1>Barang Pesanan</h1>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${products}
                            </tbody>
                        </table>
                    </div>
                    <div class="order-info text-end mt-2">
                        <h5>Subtotal: ${formatter.format(order.subtotal)}</h5>
                        <h5>Ongkos Kirim: ${formatter.format(
                            order.shippingFee
                        )}</h5>
                        <h2>Total: ${formatter.format(order.total)}</h2>
                    </div>
                `);
            },
        });
    });
    $(document).on("click", "#btnCancelOrder", function () {
        $.ajax({
            type: "POST",
            url: "/profile/order/cancel",
            data: {
                _token: csrfToken,
                orderID: globalOrder.id,
            },
            success: function (response) {
                if (response) {
                    $(
                        `.list-group-item[data-dia="order-${globalOrder.id}"]`
                    ).remove();
                    if ($("#order-container").children().length > 0) {
                        $("#order-container")
                            .html(`<div class="text text-center text-muted">
                        <p>Tidak ada pesanan</p>
                    </div>`);
                    }
                }
            },
        });
    });
    $(document).on("click", "#btnFinishOrder", function () {
        console.log("Test 1");
        $.ajax({
            type: "POST",
            url: "/profile/order/finish",
            data: {
                _token: csrfToken,
                orderID: globalOrder.id,
            },
            success: function (response) {
                if (response) {
                    $("#item-" + globalOrder.id).remove();

                    if ($("#order-container").children().length > 0) {
                        $("#order-container")
                            .html(`<div class="text text-center text-muted">
                                                    <p>Tidak ada pesanan</p>
                                                </div>`);
                    }

                    const modal = document.getElementById("exampleModal");
                    bootstrap.Modal.getInstance(modal).hide();

                    $("#exampleModal2")
                        .find("#modalTitle")
                        .html("Berikan ulasan pesanan");

                    $("#exampleModal2").find("#modalBody").html(`
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <label for="starRating" class="form-label">Berikan Rating!</label>
                                                            <input type="number" class="rating" id="starRating" required>
                                                        </div>
                                                        <div class="form-floating">
                                                            <textarea class="form-control" placeholder="Berikan Ulasan" id="floatingTextarea" style="height:200px" required></textarea>
                                                            <label for="floatingTextarea">Ulasan...</label>
                                                        </div>
                                                    `);
                    $("#exampleModal2").find("#modalFooter").html(`
                                                        <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="button" class="btn btn-primary" id="btnSave" attr-dia="">Simpan</button>`);
                    $("#starRating").rating({
                        min: 0,
                        max: 5,
                        step: 1,
                        size: "lg",
                        showCaption: false,
                    });
                }
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
    $("#exampleModal2").on("click", "#btnSave", function () {
        console.log("Test 2");
        const rating = $("#starRating").val();

        const review = $("#floatingTextarea").val();
        if (!rating || !review) {
            alert("Mohon isikan ulasan dan rating bintang");
            return;
        }
        $.ajax({
            type: "POST",
            url: "/profile/order/giveRating",
            data: {
                _token: csrfToken,
                orderID: globalOrder.id,
                rating: rating,
                review: review,
            },
            success: function (response) {
                const modal = document.getElementById("exampleModal2");
                bootstrap.Modal.getInstance(modal).hide();
            },
        });
    });
    $(".nav-link").on("click", function () {
        const tab = $(this).attr("data-sts");
        $.ajax({
            type: "POST",
            url: "/profile/order/changeTab",
            data: {
                _token: csrfToken,
                type: tab,
            },
            success: function (response) {
                let html = ``;
                if (tab == "new") {
                    if (response.length > 0) {
                        for (const iterator of response) {
                            html += `
                            <div class="list-group-item list-group-item card" aria-current="true" data-dia="order-${
                                iterator.id
                            }">
                                <div class="card-body ps-2 pe-2 text-center text-md-start">
                                    <div class="top-side">
                                        <span class="text-muted">${moment(
                                            iterator.order_date
                                        ).format("dddd, D MMMM YYYY")}</span>
                                        <h3 class="d-md-flex align-items-center text-center">${
                                            iterator.orderID
                                        }
                                            <span class="d-none d-md-block">&nbsp;</span><span
                                                class="badge bg-info d-none d-md-block">Menunggu Seller</span>
                                        </h3>
                                        <span class="badge bg-info d-md-none d-block">Menunggu Seller</span>
                                        <p><i class="fa fa-location-dot"></i>&nbsp;&nbsp;${
                                            iterator.destination_address
                                        }</p>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div
                                            class="snippet-info d-flex d-md-block flex-column align-items-center text-center text-md-start">
                                            <a href="/show-product/${
                                                iterator.details[0].product_id
                                            }"
                                                class="d-flex align-items-center p-2 my-2 text-dark">
                                                <img src="${baseUrl}/productimages/${
                                iterator.details[0].product.images[0].name
                            }"
                                                    alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="info-product d-none d-md-block ms-4">
                                                    <h5 class="mb-1">${
                                                        iterator.details[0]
                                                            .product.name
                                                    }</h5>
                                                    <div class="price-info">
                                                        <span>Qty:${
                                                            iterator.details[0]
                                                                .qty
                                                        }</span>
                                                        <h4>Rp ${formatter.format(
                                                            iterator.total
                                                        )}</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="info-product d-block d-md-none">
                                                <h5 class="mb-1">${
                                                    iterator.details[0].product
                                                        .name
                                                }</h5>
                                                <div class="price-info">
                                                    <span>Qty: ${
                                                        iterator.details[0].qty
                                                    }</span>
                                                    <h4>Rp ${formatter.format(
                                                        iterator.total
                                                    )}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline btn-dark btn-lg btn-profile-order-detail"
                                            data-di="${
                                                iterator.id
                                            }" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian
                                            Pesanan</button>
                                    </div>
                                </div>
                            </div>
                            `;
                        }
                    } else {
                        html = `<div class="text text-center text-muted">
                        <p>Tidak ada pesanan</p>
                    </div>`;
                    }
                } else if (tab == "accepted") {
                    if (response.length > 0) {
                        for (const iterator of response) {
                            html += `
                            <div class="list-group-item list-group-item card" aria-current="true" data-dia="order-${
                                iterator.id
                            }">
                                <div class="card-body ps-2 pe-2 text-center text-md-start">
                                    <div class="top-side">
                                        <span class="text-muted">${moment(
                                            iterator.order_date
                                        ).format("dddd, D MMMM YYYY")}</span>
                                        <h3 class="d-md-flex align-items-center text-center">${
                                            iterator.orderID
                                        }
                                            <span class="d-none d-md-block">&nbsp;</span><span
                                                class="badge bg-info d-none d-md-block">Sedang Diproses</span>
                                        </h3>
                                        <span class="badge bg-info d-md-none d-block">Sedang Diproses</span>
                                        <p><i class="fa fa-location-dot"></i>&nbsp;&nbsp;${
                                            iterator.destination_address
                                        }</p>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div
                                            class="snippet-info d-flex d-md-block flex-column align-items-center text-center text-md-start">
                                            <a href="/show-product/${
                                                iterator.details[0].product_id
                                            }"
                                                class="d-flex align-items-center p-2 my-2 text-dark">
                                                <img src="${baseUrl}/productimages/${
                                iterator.details[0].product.images[0].name
                            }"
                                                    alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="info-product d-none d-md-block ms-4">
                                                    <h5 class="mb-1">${
                                                        iterator.details[0]
                                                            .product.name
                                                    }</h5>
                                                    <div class="price-info">
                                                        <span>Qty:${
                                                            iterator.details[0]
                                                                .qty
                                                        }</span>
                                                        <h4>Rp ${formatter.format(
                                                            iterator.total
                                                        )}</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="info-product d-block d-md-none">
                                                <h5 class="mb-1">${
                                                    iterator.details[0].product
                                                        .name
                                                }</h5>
                                                <div class="price-info">
                                                    <span>Qty: ${
                                                        iterator.details[0].qty
                                                    }</span>
                                                    <h4>Rp ${formatter.format(
                                                        iterator.total
                                                    )}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline btn-dark btn-lg btn-profile-order-detail"
                                            data-di="${
                                                iterator.id
                                            }" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian
                                            Pesanan</button>
                                    </div>
                                </div>
                            </div>
                            `;
                        }
                    } else {
                        html = `<div class="text text-center text-muted">
                        <p>Tidak ada pesanan</p>
                    </div>`;
                    }
                } else if (tab == "sent") {
                    if (response.length > 0) {
                        for (const iterator of response) {
                            console.log(iterator);
                            html += `
                                <div class="list-group-item list-group-item card" aria-current="true" id="item-${
                                    iterator.id
                                }">
                                <div class="card-body ps-2 pe-2 text-center text-md-start">
                                    <div class="top-side">
                                        <span class="text-muted"> ${moment(
                                            iterator.order_date
                                        ).format("dddd, D MMMM YYYY")}</span>
                                        <h3 class="d-md-flex align-items-center text-center">${
                                            iterator.orderID
                                        }<span
                                                class="d-none d-md-block">&nbsp;</span>
                                            ${
                                                iterator.deliveries[0]
                                                    .arrive_date != null
                                                    ? `<span class="badge bg-warning d-none d-md-block">Mohon Selesaikan</span>`
                                                    : `<span class="badge bg-info d-none d-md-block">Dalam Perjalanan</span>`
                                            }
                                        </h3>
                                        ${
                                            iterator.deliveries[0].status ==
                                            "done"
                                                ? `<span class="badge bg-warning d-md-none d-block">Mohon Selesaikan</span>`
                                                : `<span class="badge bg-info d-md-none d-block">Dalam Perjalanan</span>`
                                        }

                                        <p><i class="fa fa-location-dot"></i>&nbsp;&nbsp;${
                                            iterator.destination_address
                                        }</p>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div
                                            class="snippet-info d-flex d-md-block flex-column align-items-center text-center text-md-start">
                                            <a href="/show-product/${
                                                iterator.details[0].product_id
                                            }"
                                                class="d-flex align-items-center p-2 my-2 text-dark">
                                                <img src="${baseUrl}/productimages/${
                                iterator.details[0].product.images[0].name
                            }"
                                                    alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="info-product d-none d-md-block ms-4">
                                                    <h5 class="mb-1">${
                                                        iterator.details[0]
                                                            .product.name
                                                    }</h5>
                                                    <div class="price-info">
                                                        <span>Qty: ${
                                                            iterator.details[0]
                                                                .qty
                                                        }</span>
                                                        <h4>Rp ${formatter.format(
                                                            iterator.total
                                                        )}</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="info-product d-block d-md-none">
                                                <h5 class="mb-1">${
                                                    iterator.details[0].product
                                                        .name
                                                }</h5>
                                                <div class="price-info">
                                                    <span>Qty: ${
                                                        iterator.details[0].qty
                                                    }</span>
                                                    <h4>Rp ${formatter.format(
                                                        iterator.total
                                                    )}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline btn-dark btn-lg btn-profile-order-detail"
                                            data-di="${
                                                iterator.id
                                            }" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian
                                            Pesanan</button>
                                    </div>
                                </div>
                            </div>
                            `;
                        }
                    } else {
                        html = `<div class="text text-center text-muted">
                        <p>Tidak ada pesanan</p>
                    </div>`;
                    }
                } else if (tab == "done") {
                    if (response.length > 0) {
                        for (const iterator of response) {
                            html += `
                            <div class="list-group-item list-group-item card" aria-current="true" data-dia="order-${
                                iterator.id
                            }">
                                <div class="card-body ps-2 pe-2 text-center text-md-start">
                                    <div class="top-side">
                                        <span class="text-muted">${moment(
                                            iterator.order_date
                                        ).format("dddd, D MMMM YYYY")}</span>
                                        <h3 class="d-md-flex align-items-center text-center">${
                                            iterator.orderID
                                        }
                                        <span class="d-none d-md-block">&nbsp;</span><span
                                        class="badge bg-success d-none d-md-block">Selesai</span>
                                        </h3>
                                        <span class="badge bg-selesai d-md-none d-block">Selesai</span>
                                        <p><i class="fa fa-location-dot"></i>&nbsp;&nbsp;${
                                            iterator.destination_address
                                        }</p>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div
                                            class="snippet-info d-flex d-md-block flex-column align-items-center text-center text-md-start">
                                            <a href="/show-product/${
                                                iterator.details[0].product_id
                                            }"
                                                class="d-flex align-items-center p-2 my-2 text-dark">
                                                <img src="${baseUrl}/productimages/${
                                iterator.details[0].product.images[0].name
                            }"
                                                    alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="info-product d-none d-md-block ms-4">
                                                    <h5 class="mb-1">${
                                                        iterator.details[0]
                                                            .product.name
                                                    }</h5>
                                                    <div class="price-info">
                                                        <span>Qty:${
                                                            iterator.details[0]
                                                                .qty
                                                        }</span>
                                                        <h4>Rp ${formatter.format(
                                                            iterator.total
                                                        )}</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="info-product d-block d-md-none">
                                                <h5 class="mb-1">${
                                                    iterator.details[0].product
                                                        .name
                                                }</h5>
                                                <div class="price-info">
                                                    <span>Qty: ${
                                                        iterator.details[0].qty
                                                    }</span>
                                                    <h4>Rp ${formatter.format(
                                                        iterator.total
                                                    )}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline btn-dark btn-lg btn-profile-order-detail"
                                            data-di="${
                                                iterator.id
                                            }" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian
                                            Pesanan</button>
                                    </div>
                                </div>
                            </div>
                            `;
                        }
                    } else {
                        html = `<div class="text text-center text-muted">
                            <p>Tidak ada pesanan</p>
                        </div>`;
                    }
                } else if (tab == "cancel") {
                    if (response.length > 0) {
                        for (const iterator of response) {
                            html += `
                            <div class="list-group-item list-group-item card" aria-current="true" data-dia="order-${
                                iterator.id
                            }">
                                <div class="card-body ps-2 pe-2 text-center text-md-start">
                                    <div class="top-side">
                                        <span class="text-muted">${moment(
                                            iterator.order_date
                                        ).format("dddd, D MMMM YYYY")}</span>
                                        <h3 class="d-md-flex align-items-center text-center">${
                                            iterator.orderID
                                        }
                                        <span class="d-none d-md-block">&nbsp;</span><span
                                        class="badge bg-danger d-none d-md-block">Dibatalkan</span>
                                        </h3>
                                        <span
                                        class="badge bg-danger d-block d-md-none">Dibatalkan</span>
                                        <p><i class="fa fa-location-dot"></i>&nbsp;&nbsp;${
                                            iterator.destination_address
                                        }</p>
                                    </div>

                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                        <div
                                            class="snippet-info d-flex d-md-block flex-column align-items-center text-center text-md-start">
                                            <a href="/show-product/${
                                                iterator.details[0].product_id
                                            }"
                                                class="d-flex align-items-center p-2 my-2 text-dark">
                                                <img src="${baseUrl}/productimages/${
                                iterator.details[0].product.images[0].name
                            }"
                                                    alt="Image" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="info-product d-none d-md-block ms-4">
                                                    <h5 class="mb-1">${
                                                        iterator.details[0]
                                                            .product.name
                                                    }</h5>
                                                    <div class="price-info">
                                                        <span>Qty:${
                                                            iterator.details[0]
                                                                .qty
                                                        }</span>
                                                        <h4>Rp ${formatter.format(
                                                            iterator.total
                                                        )}</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="info-product d-block d-md-none">
                                                <h5 class="mb-1">${
                                                    iterator.details[0].product
                                                        .name
                                                }</h5>
                                                <div class="price-info">
                                                    <span>Qty: ${
                                                        iterator.details[0].qty
                                                    }</span>
                                                    <h4>Rp ${formatter.format(
                                                        iterator.total
                                                    )}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline btn-dark btn-lg btn-profile-order-detail"
                                            data-di="${
                                                iterator.id
                                            }" data-bs-toggle="modal" data-bs-target="#exampleModal">Rincian
                                            Pesanan</button>
                                    </div>
                                </div>
                            </div>
                            `;
                        }
                    } else {
                        html = `<div class="text text-center text-muted">
                            <p>Tidak ada pesanan</p>
                        </div>`;
                    }
                }

                $(".tab-pane.active > #order-container").html(html);
            },
        });
    });
});
