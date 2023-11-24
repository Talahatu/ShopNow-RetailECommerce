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
                    `<i class="bi-gift-fill"></i> Added to cart!`
                );
                $("#toastBody").html(`
                Product: ${data.name} is successfully added to your cart! <a href="/cart">Check it out here!</a> 
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
                        `<i class="bi-gift-fill"></i> Added to wishlist!`
                    );
                    $("#toastBody").html(`
                    Product: ${response.name} is successfully added to your wishlist! <a href="/wishlist">Check it out here!</a> 
                    `);
                    toast.show();
                } else {
                    $("#wishlistStatus").removeClass("fa-solid");
                    $("#wishlistStatus").addClass("fa-regular");
                    $("#toastHeader").html(
                        `<i class="bi-gift-fill"></i> Removed from wishlist!`
                    );
                    $("#toastBody").html(`
                    Product: ${response.name} is removed from your wishlist! `);
                    toast.show();
                }
            },
        });
    });
});
