import $ from "jquery";
$(function () {
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
});
