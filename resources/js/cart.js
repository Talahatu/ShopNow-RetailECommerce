import $ from "jquery";

$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    $(".button-minus").on("click", function () {
        const parent = $(this).parent().parent().parent();
        const qty = $(parent).find("#qty-input");
        const id = $(parent).attr("attr-dia");
        let val = parseInt($(qty).val());
        if (val <= 1) return;
        val -= 1;
        updateQTY(val, qty, parent, id);
    });
    $(".button-plus").on("click", function () {
        const parent = $(this).parent().parent().parent();
        const qty = $(parent).find("#qty-input");
        const id = $(parent).attr("attr-dia");
        let val = parseInt($(qty).val());
        val += 1;
        updateQTY(val, qty, parent, id);
    });

    $(".item-selected").on("click", function () {
        const parent = $(this).parent().parent();
        const id = $(parent).attr("attr-dia");
        const value = $(this).is(":checked");

        $.ajax({
            type: "POST",
            url: "/cart/updateSelected",
            data: {
                _token: csrfToken,
                id: id,
                value: value,
            },
            success: function (response) {
                console.log(response);
                if (!response) return;
                calculateTotal();
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
    $(".btn-remove-cart").on("click", function () {
        const parent = $(this).parent().parent();
        const id = $(parent).attr("attr-dia");
        $.ajax({
            type: "DELETE",
            url: "/cart/delete",
            data: {
                _token: csrfToken,
                id: id,
            },
            success: function (response) {
                if (response) {
                    $(parent).remove();
                    calculateTotal();
                }
            },
        });
    });

    $("#btn-checkout").on("click", function () {});

    const calculateTotal = () => {
        const allSelected = $(".item-selected:checked");
        let cartTotal = 0;
        let shipTotal = 0;
        $.each(allSelected, function (indexInArray, valueOfElement) {
            cartTotal += parseInt(
                $(valueOfElement).parent().parent().find(".item-total").val()
            );

            let totalWeight =
                parseInt(
                    $(valueOfElement)
                        .parent()
                        .parent()
                        .find(".item-weight")
                        .val()
                ) *
                parseInt(
                    $(valueOfElement).parent().parent().find(".item-qty").val()
                );
            let distance = parseInt(
                $(valueOfElement).parent().parent().find(".item-distance").val()
            );
            shipTotal += calculateShipping(totalWeight, distance);
        });
        $("#cartTotal").val(cartTotal);
        $("#cartTotalLabel").html(formatter.format(cartTotal));
        $("#shipping").val(shipTotal);
        $("#shippingLabel").html(formatter.format(shipTotal));
        let total = cartTotal + shipTotal;
        $("#total").val(total);
        $("#totalLabel").html(formatter.format(total));
    };

    const calculateShipping = (weight, distance) => {
        return calculateFeeByWeight(weight) + calculateFeebyDistance(distance);
    };

    const calculateFeeByWeight = (weight) => {
        // weight by grams
        let result = 0;
        const kilograms = 1000;
        if (weight < kilograms) {
            result = 3000;
        } else if (weight >= kilograms && weight < 3 * kilograms) {
            result = Math.round(weight / kilograms) * 9000;
        } else if (Math.round(weight) == 3 * kilograms) {
            result = 35000;
        } else if (weight > 3 * kilograms && weight <= 6 * kilograms) {
            result = 35000 + Math.round(weight / kilograms) * 3000;
        } else {
            // greater than 6kg
            result = 50000;
        }
        return result;
    };
    const calculateFeebyDistance = (distance) => {
        // distance in kilometer
        const kilometer = 1000;
        const per100Meter = 100;
        let result = 0;
        let distanceInMeter = distance * kilometer;
        if (distanceInMeter < per100Meter) {
            result = 500;
        } else if (
            distanceInMeter >= per100Meter &&
            distanceInMeter < kilometer
        ) {
            result = Math.round(distanceInMeter / per100Meter) * 500;
        } else if (Math.round(distance) == 1) {
            result = 5000;
        } else if (Math.round(distance) > 1 && Math.round(distance) <= 3) {
            result = 5000 + distance * 2000;
        } else {
            result = 15000;
        }
        return result;
    };
    const updateQTY = (qty, el, elParent, id) => {
        $.ajax({
            type: "POST",
            url: "/cart/updateQuantity",
            data: {
                _token: csrfToken,
                qty: qty,
                id: id,
            },
            success: function (response) {
                if (!response) return;
                $(el).val(qty);
                let price = parseInt($(elParent).find(".item-price").val());
                let total = price * parseInt(qty);
                $(elParent).find(".item-total").val(total);
                $(elParent)
                    .find(".item-total-label")
                    .html(formatter.format(total));
                if ($(elParent).find(".item-selected").is(":checked")) {
                    calculateTotal();
                }
            },
        });
    };
});
