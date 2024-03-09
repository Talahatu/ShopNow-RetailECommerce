import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import "leaflet-routing-machine";
import "lrm-graphhopper";
import "leaflet-routing-machine/dist/leaflet-routing-machine.css";
import moment from "moment/moment";
import "moment/locale/id";

$(function () {
    delete L.Icon.Default.prototype._getIconUrl;

    L.Icon.Default.mergeOptions({
        iconRetinaUrl: "/images/vendor/leaflet/dist/marker-icon-2x.png",
        iconUrl: "/images/vendor/leaflet/dist/marker-icon.png",
        shadowUrl: "/images/vendor/leaflet/dist/marker-shadow.png",
    });
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    $(".btnTakeDelivery").on("click", function () {
        const [orderID, deliveryID] = $(this).attr("data-di").split("-");
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="button button-border button-rounded button-teal button-fill" data-bs-dismiss="modal" id="closeModal"><span>Tutup</span></button>
            <button type="button" class="button button-border button-rounded button-green button-fill me-2" id="saveDelivery" data-di="${orderID}-${deliveryID}"><span>Mulai Antarkan</span></button>
            
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

                $("#exampleModal").find(".modal-body").html(`
                    <div class="form-group row">
                        <label for="orderNumber" class="col-sm-3 col-form-label">Nomor Pesanan</label>
                        <div class="col-sm-9">
                            <label for="orderNumber" class="col-form-label">:&nbsp;${
                                order.orderID
                            }</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="orderNumber" class="col-sm-3 col-form-label">Tanggal Pengiriman</label>
                        <div class="col-sm-9">
                            <label for="orderNumber" class="col-form-label">:&nbsp;${moment(
                                order.deliveries[0].start_date
                            ).format("dddd, D MMMM YYYY")}</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="destination" class="col-sm-3 col-form-label">Alamat Toko</label>
                        <div class="col-sm-9">
                            <label for="destination" class="col-form-label">:&nbsp;${
                                order.shop.address
                            }</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="destination" class="col-sm-3 col-form-label">Alamat Tujuan</label>
                        <div class="col-sm-9">
                            <label for="destination" class="col-form-label">:&nbsp;${
                                order.destination_address
                            }</label>
                        </div>
                    </div>
                    <hr>
                    <div id="map" style="height: 360px" class="m-4"></div>
                `);

                // Map
                var map = L.map("map").setView(
                    [latitudeDestination, longitudeDestination],
                    15
                );

                // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                // let markerStart = L.marker([-7.28838, 112.77239], {
                //     draggable: false,
                //     bubblingMouseEvents: false,
                // }).addTo(map);

                let markerEnd = L.marker(
                    [latitudeDestination, longitudeDestination],
                    {
                        draggable: false,
                        bubblingMouseEvents: false,
                    }
                ).addTo(map);

                // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                // markerStart.bindTooltip("<b>Alamat Toko</b>").openTooltip();

                markerEnd.bindTooltip("<b>Alamat Tujuan</b>").openTooltip();

                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 17,
                    minZoom: 13,
                    attribution:
                        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);

                // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                // let routeControl = L.Routing.control({
                //     waypoints: [
                //         L.latLng(-7.28838, 112.77239), // Start point
                //         L.latLng(-7.31997, 112.767487), // End point
                //     ],
                //     router: L.Routing.graphHopper(
                //         "fc06c31b-e90b-47b4-941f-42b9c8971b33"
                //     ),
                //     createMarker: function (i, waypoint, n) {
                //         return i === 0 ? markerStart : markerEnd;
                //     },
                //     routeWhileDragging: false,
                //     addWaypoints: false,
                // }).addTo(map);
            },
        });
    });

    $(document).on("click", "#saveDelivery", function () {
        const [orderID, deliveryID] = $(this).attr("data-di").split("-");
        $.ajax({
            type: "POST",
            url: "/pickupItems",
            data: {
                _token: csrfToken,
                orderID: orderID,
                deliveryID: deliveryID,
            },
            success: function (response) {},
        });
    });
});
