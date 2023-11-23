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
});
