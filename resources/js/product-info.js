import $ from "jquery";
$(function () {
    var toastEl = document.getElementById("myToast");
    var toast = new bootstrap.Toast(toastEl);
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    $("#button-minus").on("click", function () {
        const qty = $("#qty-input");
        let val = parseInt($(qty).val());
        if (val > 1) {
            $(qty).val(val - 1);
        }
    });
    $("#button-plus").on("click", function () {
        const qty = $("#qty-input");
        let val = parseInt($(qty).val());
        if (val > 0) {
            $(qty).val(val + 1);
        }
    });

    $(".buy-now").on("click", function () {
        const id = $(this).attr("attr-dia");
        const qty = $("#qty-input").val();
        const price = $("#price").val();

        $.ajax({
            type: "POST",
            url: "/cart/buynow",
            data: {
                _token: csrfToken,
                id: id,
                qty: qty,
                price: price,
            },
            success: function (response) {
                window.location.href = "/cart";
            },
        });
    });
    $(".add-to-cart").on("click", function () {
        const id = $(this).attr("attr-dia");
        const qty = $("#qty-input").val();
        const price = $("#price").val();
        $.ajax({
            type: "POST",
            url: "/cart/add",
            data: {
                _token: csrfToken,
                id: id,
                qty: qty,
                price: price,
            },
            success: function (response) {
                const data = response.data;
                $("#toastHeader").html(
                    `<i class="bi-gift-fill"></i> Telah masuk ke keranjang!`
                );
                $("#toastBody").html(`
                ${data.name} telah berhasil masuk ke keranjang anda! <a href="/cart">Lihat disini!</a> 
                `);
                toast.show();
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
    $(".add-to-wishlist").on("click", function () {
        const id = $(this).attr("attr-dia");
        const state = $("#wishlistStatus").hasClass("fa-regular");
        $.ajax({
            type: "POST",
            url: "/wishlist/toggle",
            data: {
                _token: csrfToken,
                id: id,
                state: state,
            },
            success: function (response) {
                if (!response) return;
                if (state) {
                    $("#wishlistStatus").removeClass("fa-regular");
                    $("#wishlistStatus").addClass("fa-solid");
                    $("#toastHeader").html(
                        `<i class="bi-gift-fill"></i> Telah masuk ke wishlist!`
                    );
                    $("#toastBody").html(`
                    ${response.name} telah berhasil masuk ke wishlist anda! <a href="/wishlist">Lihat disini!</a> 
                    `);
                    toast.show();
                } else {
                    $("#wishlistStatus").removeClass("fa-solid");
                    $("#wishlistStatus").addClass("fa-regular");
                    $("#toastHeader").html(
                        `<i class="bi-gift-fill"></i> Dihapus dari wishlist!`
                    );
                    $("#toastBody").html(`
                    ${response.name} telah dihapus dari wishlist anda! `);
                    toast.show();
                }
            },
        });
    });
});
