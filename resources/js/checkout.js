import $, { param } from "jquery";
import { now } from "lodash";
import Push from "push.js";
$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;

    if (!Push.Permission.has()) {
        Push.Permission.request(onGranted, onDenied);
    }
    const onGranted = (params) => {
        // console.log(params);
    };
    const onDenied = (params) => {
        // console.log(params);
    };
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    if (sessionStorage.getItem("address")) {
        $("#address-ship").html(`
        ${sessionStorage.getItem(
            "address"
        )} &nbsp;<button class="btn btn-outline-info btn-sm"
        data-bs-toggle="modal" data-bs-target="#exampleModal"
        attr-dia="${sessionStorage.getItem(
            "addressI"
        )}" data-bs-title="Change Shipping Address"
        id="btnChangeShip">Change</button>
        `);
    }
    // Bootstrap 5 event not compatible with JQuery
    var btnShip = document.getElementById("btnChangeShip");
    btnShip.addEventListener("click", function (e) {
        const button = $(this);
        const title = $(button).attr("data-bs-title");
        const id = $(this).attr("attr-dia");
        $("#modalTitle").html(title);
        $.ajax({
            type: "POST",
            url: "/getShipAddressModal",
            data: {
                _token: csrfToken,
                id: id,
            },
            success: function (response) {
                $("#modalBody").html(response.content);
            },
            error: function (err) {
                console.log(err);
            },
        });
    });

    $(document).on("click", ".address-item", function () {
        const allAddress = $(".address-item");
        $.each(allAddress, function (indexInArray, valueOfElement) {
            $(valueOfElement).removeClass("active");
        });
        $(this).addClass("active");
    });

    $(document).on("click", "#btnSave", function () {
        const selected = $(".address-item.active")[0];
        const id = $(selected).attr("attr-int");
        const name = $(selected).attr("attr-name");
        $("#address-ship").html(`
        ${name} &nbsp;<button class="btn btn-outline-info btn-sm" 
        data-bs-toggle="modal" data-bs-target="#exampleModal" attr-dia="${id}" 
        data-bs-title="Change Shipping Address" id="btnChangeShip">Change</button>
        `);
        $.ajax({
            type: "POST",
            url: "/changeCartAddress",
            data: {
                _token: csrfToken,
                addrID: id,
            },
            success: function (response) {
                $("#subtotal-label").html(formatter.format(response.cartTotal));
                $("#shippingfee-label").html(
                    formatter.format(response.shippingFee)
                );
                $("#total-label").html(formatter.format(response.total));
                $("#total-checkout").html(response.total);

                sessionStorage.setItem("address", name);
                sessionStorage.setItem("addressI", id);
            },
            error: function (err) {
                console.log(err);
            },
        });
        $(".btnCloseModal").click();
    });
    $("#btnCheckout").on("click", function () {
        const addressID = $("#btnChangeShip").attr("attr-dia");
        const method = $(".btn-method:checked").val();
        const total = $("#total-checkout").val();
        $.ajax({
            type: "POST",
            url: "/checkout/create",
            data: {
                _token: csrfToken,
                address: addressID,
                payment: method,
                total: total,
            },
            success: function (response) {
                if (response.data.length > 1) {
                    alert(response.data[1]);
                    return;
                }
                if (Push.Permission.has()) {
                    Push.create("New order created!", {
                        body:
                            "Your new order is successfully created at " +
                            new Date() +
                            ". Waiting for your order to be accepted by seller.",
                        icon: baseUrl + "/images/logoshpnw2_ver4.png",
                        link: "/profile/notif",
                        timeout: 4000,
                        onClick: function () {
                            window.focus();
                            this.close();
                        },
                    });
                }
                window.location.href = "/profile/order";
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
});
