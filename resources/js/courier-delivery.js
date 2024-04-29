import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L, { LatLng, marker } from "leaflet";
import "leaflet-routing-machine";
import "lrm-graphhopper";
import "leaflet-routing-machine/dist/leaflet-routing-machine.css";
import "jquery-mask-plugin";
import "leaflet-spin";

$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;

    const [orderID, deliveryID] = $("#dia").val().split("-");
    let markerStart = null;
    let markerEnd = null;
    let routeControl = null;

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

    $.ajax({
        type: "POST",
        url: "/getDeliveryDetail",
        data: {
            _token: csrfToken,
            orderID: orderID,
        },
        success: function (response) {
            const latitudeDestination = response.destination_latitude;
            const longitudeDestination = response.destination_longitude;
            generateMap(latitudeDestination, longitudeDestination);

            if (response.payment_method == "cod") {
                $("#operationalFeeUsed").mask("#.##0", {
                    reverse: true,
                });
                $(document).on("input", "#operationalFeeUsed", function () {
                    const image = $("#proofImage")[0];
                    checkFilled(image, $(this).val());
                });
            }

            $(document).on("change", "#proofImage", function () {
                if (response.payment_method == "cod") {
                    checkFilled(this, $("#operationalFeeUsed").val());
                } else {
                    $("#finishDelivery").attr(
                        "disabled",
                        !this.files.length > 0
                    );
                }

                const file = this.files[0];
                if (file) {
                    const fileReader = new FileReader();
                    fileReader.onload = function (event) {
                        $("#file-preview").attr("src", event.target.result);
                    };
                    fileReader.readAsDataURL(file);
                }
            });
        },
    });

    $("#backButton").on("click", function () {
        window.location.href = "/courier/home";
    });

    $("#finishDelivery").on("click", function () {
        const [orderID, deliveryID] = $("#dia").val().split("-");
        $("#loader").removeClass("d-none");
        $("#loader").addClass("d-flex");
        $.ajax({
            type: "POST",
            url: "/getOrderPaymentType",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
        })
            .then(function (response) {
                const type = response.type;
                let result = true;
                let image = $("#proofImage")[0];

                let formData = new FormData();
                formData.append("_token", csrfToken);
                formData.append("file", image.files[0]);
                formData.append("orderID", orderID);
                formData.append("deliveryID", deliveryID);
                formData.append("type", type);

                if (result)
                    return $.ajax({
                        type: "POST",
                        url: "/delivery/finish",
                        data: formData,
                        processData: false,
                        contentType: false,
                    });
            })
            .then(function (response) {
                if (response) {
                    sessionStorage.removeItem("lat");
                    sessionStorage.removeItem("lng");
                    window.location.href = "/courier/home";
                }
            })
            .catch(function (param) {
                console.log(param);
            });
    });

    // $("#test").on("click", function () {
    //     navigator.geolocation.getCurrentPosition(success, error);

    //     function success(data) {
    //         console.log(data);
    //     }
    //     function error(err) {
    //         console.log(err);
    //     }
    // });

    const generateMap = (latitudeDestination, longitudeDestination) => {
        //===================================== Map Section Start =====================================
        var map = L.map("map").setView(
            [latitudeDestination, longitudeDestination],
            15
        );

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 17,
            minZoom: 13,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        map.locate({
            watch: true,
            enableHighAccuracy: true,
        });

        let counter = 0; // For lighter check (hopefully)

        map.on("locationfound", function (e) {
            if (e.accuracy <= 50) {
                map.spin(false);
                const currentPosition = e.latlng;

                // // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                if (!markerStart) {
                    markerStart = L.marker(
                        [currentPosition.lat, currentPosition.lng],
                        {
                            draggable: false,
                            bubblingMouseEvents: false,
                        }
                    ).addTo(map);
                    markerEnd = L.marker(
                        [latitudeDestination, longitudeDestination],
                        {
                            draggable: false,
                            bubblingMouseEvents: false,
                        }
                    ).addTo(map);
                } else {
                    markerStart.setLatLng(e.latlng);
                }

                // // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                markerStart.bindTooltip("<b>Lokasi Saat Ini</b>").openTooltip();

                markerEnd.bindTooltip("<b>Alamat Tujuan</b>").openTooltip();

                // // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                if (routeControl != null) {
                    routeControl.setWaypoints([
                        L.latLng(currentPosition.lat, currentPosition.lng),
                        routeControl.options.waypoints[1],
                    ]);
                } else {
                    routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(currentPosition.lat, currentPosition.lng), // Start point
                            L.latLng(latitudeDestination, longitudeDestination), // End point
                        ],
                        router: L.Routing.graphHopper(
                            "fc06c31b-e90b-47b4-941f-42b9c8971b33"
                        ),
                        createMarker: function (i, waypoint, n) {
                            return i === 0 ? markerStart : markerEnd;
                        },
                        routeWhileDragging: false,
                        addWaypoints: false,
                    }).addTo(map);
                }

                sessionStorage.setItem("lat", currentPosition.lat);
                sessionStorage.setItem("lng", currentPosition.lng);

                counter = 0;
            } else {
                // For Debug Purposes
                // console.log(
                //     "Accuracy is not accurate! The accuracy radius is " +
                //         e.accuracy
                // );
                map.spin(true);

                if (sessionStorage.getItem("lat") && counter <= 0) {
                    console.log("TEST");
                    let currentLatitude = sessionStorage.getItem("lat");
                    let currentLongitude = sessionStorage.getItem("lng");
                    if (!markerStart) {
                        markerStart = L.marker(
                            [currentLatitude, currentLongitude],
                            {
                                draggable: false,
                                bubblingMouseEvents: false,
                            }
                        ).addTo(map);
                        markerEnd = L.marker(
                            [latitudeDestination, longitudeDestination],
                            {
                                draggable: false,
                                bubblingMouseEvents: false,
                            }
                        ).addTo(map);
                    } else {
                        markerStart.setLatLng(
                            new LatLng(currentLatitude, currentLongitude)
                        );
                    }

                    // // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                    markerStart
                        .bindTooltip("<b>Lokasi Saat Ini</b>")
                        .openTooltip();

                    markerEnd.bindTooltip("<b>Alamat Tujuan</b>").openTooltip();

                    // // Don't remove!!!!!!!!!!!!!!!!!!!!!!
                    let routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(currentLatitude, currentLongitude), // Start point
                            L.latLng(latitudeDestination, longitudeDestination), // End point
                        ],
                        router: L.Routing.graphHopper(
                            "fc06c31b-e90b-47b4-941f-42b9c8971b33"
                        ),
                        createMarker: function (i, waypoint, n) {
                            return i === 0 ? markerStart : markerEnd;
                        },
                        routeWhileDragging: false,
                        addWaypoints: false,
                    }).addTo(map);

                    counter++;
                }
            }
        });

        map.on("locationerror", function (err) {
            console.log(err);
        });
        //===================================== Map Section End =====================================
    };

    const checkFilled = (image, fee) => {
        $("#finishDelivery").attr(
            "disabled",
            fee == "" || image.files.length <= 0
        );
    };
});
