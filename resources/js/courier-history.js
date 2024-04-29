import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import "leaflet-routing-machine";
import "lrm-graphhopper";
import "leaflet-routing-machine/dist/leaflet-routing-machine.css";
import moment from "moment/moment";
import "moment/locale/id";
import "jquery-mask-plugin";

$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;

    delete L.Icon.Default.prototype._getIconUrl;

    L.Icon.Default.mergeOptions({
        iconRetinaUrl: "/images/vendor/leaflet/dist/marker-icon-2x.png",
        iconUrl: "/images/vendor/leaflet/dist/marker-icon.png",
        shadowUrl: "/images/vendor/leaflet/dist/marker-shadow.png",
    });
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    $(".btnDeliveryDetail").on("click", function () {
        const [orderID, deliveryID] = $(this).attr("data-di").split("-");
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="button button-border button-rounded button-teal button-fill" data-bs-dismiss="modal" id="closeModal"><span>Tutup</span></button>
            
        `);
        $("#exampleModalLabel").html("Rincian Pesanan");

        $.ajax({
            type: "POST",
            url: "/getDeliveryDetail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                const order = response;
                const latitudeDestination = order.destination_latitude;
                const longitudeDestination = order.destination_longitude;

                generateModal(order);
                generateMap(latitudeDestination, longitudeDestination);
            },
        });
    });
    const generateModal = (order, data) => {
        let html = `
            <div class="row">
                <div class="form-group col-6">
                    <label for="orderNumber" ><b>Nama Pemesan:&nbsp;</b></label>
                    <div >
                        <label for="orderNumber" >${order.user.name}</label>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="hp"><b>Nomor HP:&nbsp;</b></label>
                    <div >
                        <label for="hp">${order.user.phoneNumber}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                        <label for="orderNumber"><b>Nomor Pesanan:&nbsp;</b></label>
                        <div >
                            <label for="orderNumber">${order.orderID}</label>
                        </div>
                    </div>
                <div class="form-group col-6">
                    <label for="paymentType"><b>Jenis Pembayaran:&nbsp;</b></label>
                    <div class="col-sm-7">
                        <label for="paymentType" class="col-form-label">${
                            order.payment_method == "cod"
                                ? "Pembayaran di tempat (COD)"
                                : "Saldo"
                        }</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                    <label for="orderNumber" class="col-sm-5 col-form-label"><b>Tanggal Pengiriman:&nbsp;</b></label>
                    <div class="col-sm-7">
                        <label for="orderNumber" class="col-form-label">${moment(
                            order.deliveries[0].start_date
                        ).format("dddd, D MMMM YYYY")}</label>
                    </div>
                </div>
            <div class="form-group row">
                <label for="destination" class="col-sm-3 col-form-label"><b>Alamat Toko:&nbsp;</b></label>
                <div class="col-sm-9">
                    <label for="destination" class="col-form-label">${
                        order.shop.address
                    }</label>
                </div>
            </div>
            <div class="form-group row">
                <label for="destination" class="col-sm-3 col-form-label"><b>Alamat Tujuan:&nbsp;</b></label>
                <div class="col-sm-9">
                    <label for="destination" class="col-form-label">${
                        order.destination_address
                    }</label>
                </div>
            </div>
            <hr>
            ${
                order.payment_method == "cod"
                    ? `<h5 class="text-end">Operational:&nbsp;+&nbsp;${formatter.format(
                          order.deliveries[0].feeAssigned
                      )}</h5>
            <h5 class="text-end">Total Pesanan:&nbsp;+&nbsp;${formatter.format(
                order.total
            )}</h5>
            <hr>
            <h3 class="text-end">Disetor:&nbsp;${formatter.format(
                order.deliveries[0].feeAssigned + order.total
            )}</h3>`
                    : `
            <h5 class="text-end">Total Pesanan:&nbsp;+&nbsp;${formatter.format(
                order.total
            )}</h5>
            <hr>
            <h3 class="text-end">Disetor:&nbsp;${formatter.format(
                order.total
            )}</h3>`
            }
            `;
        $("#exampleModal").find(".modal-body").html(html);
    };
});
