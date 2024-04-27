import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L, { marker } from "leaflet";
import "leaflet-routing-machine";
import "lrm-graphhopper";
import "leaflet-routing-machine/dist/leaflet-routing-machine.css";
import moment from "moment/moment";
import "moment/locale/id";
import "jquery-mask-plugin";
import "leaflet-spin";

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

    generateDeliveryRouteMap();

    $(".btnTakeDelivery").on("click", function () {
        const [orderID, deliveryID] = $(this).attr("data-di").split("-");
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="button button-border button-rounded button-teal button-fill" data-bs-dismiss="modal" id="closeModal"><span>Tutup</span></button>
            <button type="button" class="button button-border button-rounded button-green button-fill me-2" id="saveDelivery" data-di="${orderID}-${deliveryID}"><span>Mulai Pengiriman</span></button>
            
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

    $(document).on("click", "#saveDelivery", function () {
        const [order_ID, delivery_ID] = $(this).attr("data-di").split("-");
        $("#loader").removeClass("d-none");
        $("#loader").addClass("d-flex");
        $.ajax({
            type: "POST",
            url: "/pickupItems",
            data: {
                _token: csrfToken,
                orderID: order_ID,
                deliveryID: delivery_ID,
            },
            success: function (response) {
                if (response) {
                    const id = response.id;
                    const startDate = response.startDate;
                    const orderID = response.orderID;
                    const address = response.address;
                    const deliveryID = response.deliveryID;
                    const html = `
                    <article class="entry event col-12 mb-4">
                        <div
                            class="grid-inner bg-white row g-0 p-3 border-0 rounded-5 shadow-sm h-shadow all-ts h-translate-y-sm">
                            <div class="col-md-12 p-4">
                                <div class="entry-meta no-separator mb-1 mt-0 d-flex d-sm-block justify-content-center">
                                    <ul>
                                        <li>
                                            <span
                                                class="text-uppercase fw-medium">${moment(
                                                    startDate
                                                ).format(
                                                    "dddd, D MMMM YYYY"
                                                )}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="entry-title">
                                    <h3 class="text-center text-sm-start"><a>${orderID}</a></h3>
                                </div>
                                <div class="entry-meta no-separator text-center text-sm-start">
                                    <ul>
                                        <li><a class="fw-normal"><i class="uil uil-map-marker"></i>
                                                ${address}</a></li>
                                    </ul>
                                </div>
                                <div class="entry-content my-3 text-center text-md-start">
                                    <a class="button button-border button-rounded button-green button-fill btnDeliveryDone"
                                        data-di="${id}-${deliveryID}" href="${baseUrl}/courier/delivery/${id}/${deliveryID}"><i
                                            class="fa-solid fa-truck-fast"></i><span>Rinci Pengiriman</span></a>
                                </div>
                            </div>
                        </div>
                    </article>
                    `;

                    $("#now").prepend(html);

                    const button = $(
                        ".btnTakeDelivery[data-di='" +
                            order_ID +
                            "-" +
                            delivery_ID +
                            "']"
                    )[0];
                    const article = $(button)
                        .parent()
                        .parent()
                        .parent()
                        .parent()[0];

                    $(article).remove();

                    const modal = document.getElementById("exampleModal");
                    bootstrap.Modal.getInstance(modal).hide();
                    $("#loader").removeClass("d-flex");
                    $("#loader").addClass("d-none");

                    window.location.reload();
                }
            },
        });
    });

    // =========================================== Functions Start ==================================================

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
            <div class="style-msg infomsg">
					<div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Jangan lupa untuk memastikan anda menerima uang operasional yang diberikan penjual sebelum memulai pengiriman</div>
				</div>
                <div class="form-group row">
                    <label for="orderTotal" class="col-sm-5 col-form-label"><b>Nominal Uang Operational:&nbsp;</b></label>
                    <div class="col-sm-7">
                        <label for="orderTotal" class="col-form-label">${formatter.format(
                            order.deliveries[0].feeAssigned
                        )}
                    </div>
                </div>
            <hr>
            <div id="map" style="height: 360px" class="m-4"></div>
            `;
        $("#exampleModal").find(".modal-body").html(html);
    };

    const generateMap = (latitudeDestination, longitudeDestination) => {
        //===================================== Map Section Start =====================================
        var map = L.map("map").setView(
            [latitudeDestination, longitudeDestination],
            15
        );

        let markerEnd = L.marker([latitudeDestination, longitudeDestination], {
            draggable: false,
            bubblingMouseEvents: false,
        }).addTo(map);

        markerEnd.bindTooltip("<b>Alamat Tujuan</b>").openTooltip();

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 17,
            minZoom: 13,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);
        //===================================== Map Section End =====================================
    };

    function generateDeliveryRouteMap() {
        const map = L.map("deliveryMapRoute");
        map.locate({
            watch: true,
            enableHighAccuracy: true,
        });
        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 17,
            minZoom: 13,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);
        $.ajax({
            type: "POST",
            url: "/getposition/deliveries",
            data: {
                _token: csrfToken,
            },
            success: function (response) {
                const [datas, shop] = [
                    response.currentDeliveries,
                    response.shopCoord,
                ];
                let nodes = datas.map((i) => {
                    return {
                        idDelivery: i.id,
                        orderID: i.order.orderID,
                        latitude: i.order.destination_latitude,
                        longitude: i.order.destination_longitude,
                        visited: false,
                    };
                });
                map.spin(true);
                map.once("locationfound", function (e) {
                    console.log("Fetching coordinates...");
                    if (e.accuracy >= 50) {
                        map.spin(true);
                    } else {
                        map.spin(false);
                        const currentPosition = e.latlng;
                        map.setView(
                            [currentPosition.lat, currentPosition.lng],
                            13
                        );

                        // Greedy Nearest Neighbours Algorithm
                        let optimalRoute = [
                            {
                                idDelivery: 0,
                                orderID: "Lokasi saat ini",
                                latitude: currentPosition.lat,
                                longitude: currentPosition.lng,
                            },
                        ];
                        let totalDeliveries = nodes.length;
                        let counter = 0;
                        while (counter < totalDeliveries) {
                            let currentNode = optimalRoute[counter];
                            let nearestNode = null;
                            let shortestDistance = Infinity;
                            for (const iterator2 of nodes) {
                                if (
                                    iterator2.idDelivery ==
                                        currentNode.idDelivery ||
                                    iterator2.visited
                                )
                                    continue;
                                const distance = Math.round(
                                    map.distance(
                                        L.latLng(
                                            currentNode.latitude,
                                            currentNode.longitude
                                        ),
                                        L.latLng(
                                            iterator2.latitude,
                                            iterator2.longitude
                                        )
                                    )
                                );
                                if (distance < shortestDistance) {
                                    shortestDistance = distance;
                                    nearestNode = iterator2;
                                }
                            }
                            if (!nearestNode) break;
                            optimalRoute.push(nearestNode);
                            nearestNode.visited = true;
                            counter++;
                        }

                        let waypoints = optimalRoute.map((data) => {
                            return L.latLng(data.latitude, data.longitude);
                        });
                        let markers = optimalRoute.map((data) => {
                            return L.marker([data.latitude, data.longitude], {
                                draggable: false,
                                bubblingMouseEvents: false,
                            })
                                .bindTooltip(`<b>${data.orderID}</b>`)
                                .openTooltip();
                        });
                        let markerGroup = L.featureGroup(markers).addTo(map);
                        let routeControl = L.Routing.control({
                            waypoints: waypoints,
                            router: L.Routing.graphHopper(
                                "fc06c31b-e90b-47b4-941f-42b9c8971b33"
                            ),
                            createMarker: function (i, waypoint, n) {
                                return markers[i];
                            },
                            routeWhileDragging: false,
                            addWaypoints: false,
                        }).addTo(map);
                        map.fitBounds(markerGroup.getBounds(), {
                            padding: [20, 20],
                        });
                    }
                });
            },
            error: function (err) {
                console.log(err);
            },
        });
    }
});
