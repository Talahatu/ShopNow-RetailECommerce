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
        $("#modalFooter").html(`
        <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>`);
        $.ajax({
            type: "POST",
            url: "/profile/order/detail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                const order = response;
                let finishProof = ``;
                let finishFooter = ``;
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
                                <button type="button" class="btn btn-primary" id="btnFinishOrder" data-bs-toggle="modal" data-bs-target="#exampleModal2">Selesaikan</button>
                            `;

                            $("#modalFooter").append(finishFooter);

                            $(document).on(
                                "click",
                                "#btnFinishOrder",
                                function () {
                                    $.ajax({
                                        type: "POST",
                                        url: "/profile/order/finish",
                                        data: {
                                            _token: csrfToken,
                                            orderID: order.id,
                                        },
                                        success: function (response) {
                                            if (response) {
                                                $("#item-" + order.id).remove();

                                                const modal =
                                                    document.getElementById(
                                                        "exampleModal"
                                                    );
                                                bootstrap.Modal.getInstance(
                                                    modal
                                                ).hide();

                                                $("#exampleModal2")
                                                    .find("#modalTitle")
                                                    .html(
                                                        "Berikan ulasan pesanan"
                                                    );

                                                $("#exampleModal2").find(
                                                    "#modalBody"
                                                ).html(`
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <label for="starRating" class="form-label">Berikan Rating!</label>
                                                            <input type="number" class="rating" id="starRating">
                                                        </div>
                                                        <div class="form-floating">
                                                            <textarea class="form-control" placeholder="Berikan Ulasan" id="floatingTextarea" style="height:200px"></textarea>
                                                            <label for="floatingTextarea">Ulasan...</label>
                                                        </div>
                                                    `);
                                                $("#starRating").rating({
                                                    min: 0,
                                                    max: 5,
                                                    step: 1,
                                                    size: "lg",
                                                    showCaption: false,
                                                });

                                                $("#exampleModal2").on(
                                                    "click",
                                                    "#btnSave",
                                                    function () {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "/profile/order/giveRating",
                                                            data: {
                                                                _token: csrfToken,
                                                                orderID:
                                                                    order.id,
                                                                rating: $(
                                                                    "#starRating"
                                                                ).val(),
                                                                review: $(
                                                                    "#floatingTextarea"
                                                                ).val(),
                                                            },
                                                            success: function (
                                                                response
                                                            ) {
                                                                const modal =
                                                                    document.getElementById(
                                                                        "exampleModal2"
                                                                    );
                                                                bootstrap.Modal.getInstance(
                                                                    modal
                                                                ).hide();
                                                                window.location.reload();
                                                            },
                                                        });
                                                    }
                                                );
                                            }

                                            $("#exampleModal2").on(
                                                "hidden.bs.modal",
                                                function () {
                                                    window.location.reload();
                                                }
                                            );
                                        },
                                    });
                                }
                            );
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
});
