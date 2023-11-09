const { error } = require("jquery");

var map;
var marker;
const csrfToken = $('meta[name="csrf-token"]').attr("content");
$(function () {
    $("#image").on("change", function (e) {
        displaySelectedImage(e, "selectedAvatar");
    });

    $("#exampleModal").on("show.bs.modal", function (e) {
        $("#btnSave").attr("disabled", true);
        const button = e.relatedTarget;
        const title = $(button).attr("data-bs-title");
        $("#modalTitle").html(title);
        $.ajax({
            type: "POST",
            url: "/getAddAddressForm",
            data: {
                _token: csrfToken,
            },
            success: function (response) {
                $("#modalBody").html(response.content);
                document
                    .querySelectorAll(".form-outline")
                    .forEach((formOutline) => {
                        new mdb.Input(formOutline).init();
                    });
                map = L.map("map").setView([3.57898, 98.635307], 15);
                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 15,
                    attribution:
                        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);
            },
        });
    });
    $(document).on("change", "#address", function () {
        $("#btnSave").attr("disabled", false);
        var address = $(this).val();
        map.on("click", onMapClick);
        $.get(
            location.protocol +
                "//nominatim.openstreetmap.org/search?format=json&q=" +
                address,
            function (data) {
                const location = data[0];
                map.setView(L.latLng(location.lat, location.lon), 15);
                $("#ll").val(`${location.lat},${location.lon}`);
                // Draw map & marker
                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution:
                        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);
                marker = L.marker(L.latLng(location.lat, location.lon)).addTo(
                    map
                );
            }
        );
    });

    $("#btnSave").on("click", function () {
        $.ajax({
            type: "POST",
            url: "/add-new-address",
            data: {
                _token: csrfToken,
                address: $("#address").val(),
                latlng: $("#ll").val(),
            },
            success: function (response) {
                console.log(response);
                $("#exampleModal").modal("hide");
                $("#modalTitle").html("");
                $("#modalBody").html("");
            },
        });
    });
    $(".btnCloseModal").on("click", function () {
        $("#modalTitle").html("");
        $("#modalBody").html("");
    });
});
const onMapClick = (e) => {
    if (typeof marker !== "undefined") map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
};
const displaySelectedImage = (event, elementId) => {
    const selectedImage = document.getElementById(elementId);
    const fileInput = event.target;
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            selectedImage.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
};
