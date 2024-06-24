import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: "/images/vendor/leaflet/dist/marker-icon-2x.png",
    iconUrl: "/images/vendor/leaflet/dist/marker-icon.png",
    shadowUrl: "/images/vendor/leaflet/dist/marker-shadow.png",
});
document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        $("#loader").addClass("d-none");
        $("#loader").removeClass("d-flex");
    }
};
$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;
    const query = "";
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    $("#btnShopMap").on("click", function () {
        const shopID = $(this).attr("data-dis");
        $.ajax({
            type: "POST",
            url: "/getSeller",
            data: {
                _token: csrfToken,
                id: shopID,
            },
            success: function (response) {
                const lat = response.data.lat;
                const long = response.data.long;
                console.log(lat, long);
                let map = L.map("map").setView([lat, long], 15);
                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 16,
                    minZoom: 12,
                    attribution:
                        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);
                let marker = L.marker(L.latLng(lat, long)).addTo(map);
            },
        });
    });
});
