import $ from "jquery";
import "select2/dist/css/select2.min.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.min.css";
import "select2";
import AutoNumeric from "autonumeric";
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.fn.select2.defaults.set("theme", "bootstrap");
    $("#navProduct").addClass("active");
    $("#navProduct > div").addClass("show");
    $("#newproduct > a").addClass("active");
    $("#selectCategory").select2({
        placeholder: "Select a category",
        ajax: {
            type: "POST",
            url: "/fetch-categories",
            data: function (params) {
                return { _token: csrfToken, q: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });
    $("#selectBrand").select2({
        placeholder: "Select a brand (pick a category first)",
        disabled: true,
        ajax: {
            type: "POST",
            url: "/fetch-brands",
            data: function (params) {
                return {
                    _token: csrfToken,
                    q: params.term,
                    id: $("#selectCategory").val(),
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });
    const priceInput = new AutoNumeric("#inputPrice", {
        digitGroupSeparator: ".",
        decimalCharacter: ",",
        decimalPlaces: "0",
        modifyValueOnUpDownArrow: true,
        upDownStep: "1000",
        minimumValue: "1000",
    });

    $("#selectCategory").on("change", function () {
        $("#selectBrand").attr("disabled", false);
    });
});
