import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import "leaflet-routing-machine";
import "lrm-graphhopper";

$(function () {
    delete L.Icon.Default.prototype._getIconUrl;

    L.Icon.Default.mergeOptions({
        iconRetinaUrl: "/images/vendor/leaflet/dist/marker-icon-2x.png",
        iconUrl: "/images/vendor/leaflet/dist/marker-icon.png",
        shadowUrl: "/images/vendor/leaflet/dist/marker-shadow.png",
    });
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    var map = L.map("map").setView([-7.28838, 112.77239], 13);

    const markerStart = L.marker([-7.28838, 112.77239], {
        draggable: false,
        bubblingMouseEvents: false,
    }).addTo(map);
    const markerEnd = L.marker([-7.31997, 112.767487], {
        draggable: false,
        bubblingMouseEvents: false,
    }).addTo(map);

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 15,
        attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    L.Routing.control({
        waypoints: [
            L.latLng(-7.28838, 112.77239), // Start point
            L.latLng(-7.31997, 112.767487), // End point
        ],
        router: L.Routing.graphHopper("fc06c31b-e90b-47b4-941f-42b9c8971b33"),
        createMarker: function (i, waypoint, n) {
            return i === 0 ? markerStart : markerEnd;
        },
    }).addTo(map);

    $(".btnDetailDelivery").on("click", function () {
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Tutup</button>
        `);
        const orderID = $(this).attr("data-di");

        $.ajax({
            type: "POST",
            url: "/getDeliveryDetail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                console.log(response);
            },
        });
    });
});
