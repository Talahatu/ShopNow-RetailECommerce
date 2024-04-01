import $ from "jquery";
import "select2/dist/css/select2.min.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.min.css";
import "select2";
import AutoNumeric from "autonumeric";
document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        $("#loader").addClass("d-none");
        $("#loader").removeClass("d-flex");
    }
};
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.fn.select2.defaults.set("theme", "bootstrap");
    $("#navProduct").addClass("active");
    $("#navProduct > div").addClass("show");
    $("#newproduct > a").addClass("active");
    $("#selectCategory").select2({
        placeholder: "Pilih kategori",
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
        placeholder: "Pilih Merek (Pilih kategori terlebih dahulu)",
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
        minimumValue: "0",
    });
    const displaySelectedImage = (event, elementId) => {
        $(".carousel-indicators").html("");
        $(".carousel-inner").html("");
        const files = event.target.files; // FileList object
        // Loop through the FileList
        for (var i = 0, f; (f = files[i]); i++) {
            // Only process image files.
            if (!f.type.match("image.*")) {
                continue;
            }
            var reader = new FileReader();
            // Closure to capture the file information.
            reader.onload = (function (theFile, index) {
                return function (e) {
                    // Render thumbnail.
                    var indicators = `<button type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="${index}" ${
                        index == 0 ? "class=active" : ""
                    } aria-current="true"
                    aria-label="Slide ${index + 1}"></button>`;
                    var inners = `<div class="carousel-item ${
                        index == 0 ? "active" : ""
                    }">
                    <img src="${e.target.result}"
                        class="d-block w-100" alt="SelecteImages" style="object-fit: cover; max-height:300px">
                    </div>`;
                    $(".carousel-indicators").append(indicators);
                    $(".carousel-inner").append(inners);
                };
            })(f, i);
            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    };

    $("#selectCategory").on("change", function () {
        $("#selectBrand").attr("disabled", false);
    });
    $("#image").on("change", function (e) {
        displaySelectedImage(e, "selectedPlaceholder");
    });
});
