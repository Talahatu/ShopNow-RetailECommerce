import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: "/images/vendor/leaflet/dist/marker-icon-2x.png",
    iconUrl: "/images/vendor/leaflet/dist/marker-icon.png",
    shadowUrl: "/images/vendor/leaflet/dist/marker-shadow.png",
});

var map;
var marker;
const csrfToken = $('meta[name="csrf-token"]').attr("content");
let formatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
});
$(function () {
    $(".nav-link-profile").removeClass("active");
    $("#v-tabs-profile-tab-lg").addClass("active");
    $("#v-tabs-profile-tab").addClass("active");

    $("#image").on("change", function (e) {
        displaySelectedImage(e, "selectedAvatar");
    });

    // Bootstrap 5 event not compatible with JQuery
    var btnAdd = document.getElementById("btnAdd");
    btnAdd.addEventListener("click", function (e) {
        $("#btnSave").attr("disabled", true);
        const button = $(this);
        const title = $(button).attr("data-bs-title");
        $("#modalTitle").html(title);
        $("#modalFooter").html(`
            <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="btnSave" attr-dia="">Simpan</button>
        `);
        $.ajax({
            type: "POST",
            url: "/getAddAddressForm",
            data: {
                _token: csrfToken,
            },
            success: function (response) {
                $("#exampleModal").attr("modal-tags", "add-home");
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
        console.log(
            location.protocol +
                "//nominatim.openstreetmap.org/search?format=json&q=" +
                address
        );
        $.get(
            location.protocol +
                "//nominatim.openstreetmap.org/search?format=json&q=" +
                address,
            function (data) {
                console.log(data);
                const location = data[0];
                console.log(location);
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

    $(document).on("click", "#btnSave", function () {
        const tags = $("#exampleModal").attr("modal-tags");
        const tagsArr = tags.split("-");
        const id = $(this).attr("attr-dia");
        $.ajax({
            type: "POST",
            url: "/add-new-address",
            data: {
                _token: csrfToken,
                address: $("#address").val(),
                latlng: $("#ll").val(),
                tags: tags,
                id: id,
            },
            success: function (response) {
                console.log(response);
                const a = response.data;
                $(".btnCloseModal").click();
                $("#modalTitle").html("");
                $("#modalBody").html("");
                if (tagsArr[0] == "update") {
                    if (tagsArr[1] == "home") {
                        $("a[attr-int='" + id + "']").find(".address-name")
                            .html(`${a.name} <span
                            class="badge bg-success">Home</span>`);
                        return;
                    }
                    $("a[addr-type='shop']")
                        .find(".address-name")
                        .html(
                            `${a.address} <span class="badge bg-warning">Shop</span>`
                        );
                    return;
                }
                $("#list-address").append(`
                <a href="#" class="list-group-item list-group-item-action address-item" aria-current="true">
                    <div class="d-flex w-100 justify-content-between address-content">
                        <h5 class="mb-1">${a.name} <span class="badge bg-success">Home</span></h5>
                        <button type="button" class="btn btn-outline-info btn-sm set-current-addr">Jadikan Alamat Saat Ini</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-info btn-update">Ubah</button>
                        <button type="button" class="btn btn-danger btn-delete" id="deleteAddress">Hapus</button>
                    </div>
                    <input type="hidden" class="dia" attr-dia="${a.id}">
                </a>
                `);
            },
        });
    });

    $(document).on("click", ".set-current-addr", function () {
        const parent = $(this).parent().parent();
        const i = $(parent).find(".dia");
        $.ajax({
            type: "POST",
            url: "/set-cur-addr",
            data: {
                _token: csrfToken,
                id: $(i).attr("attr-dia"),
            },
            success: function (response) {
                if (response.status == "OK") {
                    var prevCur = $(".address-item.active");
                    $(prevCur).find("small").remove();
                    $(prevCur)
                        .find(".address-content")
                        .append(
                            `<button type="button" class="btn btn-outline-info btn-sm set-current-addr">Jadikan Alamat Saat Ini</button>`
                        );
                    $(prevCur)
                        .find(".btn-group")
                        .append(
                            `<button type="button" class="btn btn-danger btn-delete" id="deleteAddress">Hapus</button>`
                        );
                    $(prevCur).removeClass("active");
                    console.log(prevCur);

                    $(parent).addClass("active");
                    $(parent).find(".set-current-addr").remove();
                    $(parent)
                        .find(".address-content")
                        .append(`<small>Current</small>`);
                    $(parent).find(".btn-delete").remove();
                }
            },
        });
    });

    $(document).on("click", ".btn-update", function () {
        $("#btnSave").attr("disabled", true);
        const a = $(this).parent().parent();
        const id = $(a).find(".dia").attr("attr-dia");
        const title = $(this).attr("data-bs-title");
        const type = $(a).attr("addr-type");
        $("#modalTitle").html(title);
        $("#modalFooter").html(`
            <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="btnSave" attr-dia="">Simpan</button>
        `);
        $.ajax({
            type: "POST",
            url: "/getUpdateAddAddressForm",
            data: {
                _token: csrfToken,
                id: id,
                type: type,
            },
            success: function (response) {
                $("#exampleModal").attr(
                    "modal-tags",
                    type == "shop" ? "update-shop" : "update-home"
                );
                $("#btnSave").attr("attr-dia", id);
                const data = response.data;
                $("#ll").val(`${data.lat},${data.long}`);
                $("#modalBody").html(response.content);
                document
                    .querySelectorAll(".form-outline")
                    .forEach((formOutline) => {
                        new mdb.Input(formOutline).init();
                    });
                $("#address").val(type == "shop" ? data.address : data.name);
                map = L.map("map").setView([data.lat, data.long], 15);
                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 18,
                    minZoom: 5,
                    attribution:
                        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);
                map.on("click", onMapClick);
                marker = L.marker(L.latLng(data.lat, data.long)).addTo(map);
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
    $(document).on("click", ".btn-delete", function () {
        const result = confirm("Apakah anda yakin ingin menghapus alamat ini?");
        if (!result) return;
        const a = $(this).parent().parent();
        const id = $(a).find(".dia").attr("attr-dia");
        $.ajax({
            type: "DELETE",
            url: "/delete-address",
            data: {
                _token: csrfToken,
                id: id,
            },
            success: function (response) {
                $(a).remove();
            },
        });
    });
    $(".btnCloseModal").on("click", function () {
        $("#modalTitle").html("");
        $("#modalBody").html("");
        $("#exampleModal").attr("modal-tags", "");
    });
});

$("#btnTopup").on("click", function () {
    const value = $("#topup").val();
    $.ajax({
        type: "POST",
        url: "/saldo/topup",
        data: {
            _token: csrfToken,
            value: value,
        },
        success: function (response) {
            if (!response) return;
            $("#balance").html(
                formatter.format(
                    parseInt($("#balance-val").val()) + parseInt(value)
                )
            );
            $("#topup").val(0);
        },
    });
});
const onMapClick = (e) => {
    if (typeof marker !== "undefined") map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
    $("#btnSave").attr("disabled", false);
    $("#ll").val(`${e.latlng.lat},${e.latlng.lng}`);
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
