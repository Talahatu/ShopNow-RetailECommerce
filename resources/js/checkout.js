import $ from "jquery";
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

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
        const selected = $(".address-item");
        console.log(selected);
    });
});
