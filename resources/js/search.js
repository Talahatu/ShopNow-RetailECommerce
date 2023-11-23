import $ from "jquery";

$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;
    const query = $("#query").val();
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            successCallback,
            errorCallback
        );
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
    function successCallback(position) {
        const lat = position.coords.latitude;
        const long = position.coords.longitude;
        $.ajax({
            type: "POST",
            url: "/searchProduct",
            data: {
                _token: csrfToken,
                lat: lat,
                long: long,
                query: query,
            },
            success: function (response) {
                const products = response.products;
                if (products.length == 0) {
                    $("#products-row")
                        .html(`<h1 class="text-light">No Product matched the keywords...</h1>
                    `);
                    return;
                }
                let formatter = new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                });
                let content = ``;
                for (let index = 0; index < products.length; index++) {
                    let starFull = `<i class="fa fa-star"></i>`.repeat(
                        Math.floor(products[index].rating)
                    );
                    let starHalf =
                        products[index].rating % 1 != "0.0"
                            ? `<i class="fa-regular fa-star-half-stroke"></i>`
                            : "";
                    let starEmpty = `<i class="far fa-star"></i>`.repeat(
                        Math.floor(5 - products[index].rating)
                    );
                    content += `
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-4">
                        <a href="/show-product/${products[index].id}">
                            <div class="card">
                                <img src="${
                                    !products[index].iname
                                        ? `https://mdbootstrap.com/img/Photos/Others/placeholder.jpg`
                                        : baseUrl +
                                          "/productimages/" +
                                          products[index].iname
                                }"
                                    class="card-img-top" alt="Laptop" style="aspect-ratio:1/1; object-fit:cover" />
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="small text-muted">${
                                            products[index].distance <= 1
                                                ? products[
                                                      index
                                                  ].distance.toFixed(2)
                                                : Math.round(
                                                      products[index].distance
                                                  )
                                        } KM</p>
                                        <p class="small"><a href="#!"
                                                class="text-muted">${
                                                    products[index].cname
                                                }</a></p>
                                    </div>

                                    <div class="d-flex justify-content-between mb-1">
                                        <h5 class="mb-0 d-inline-block text-truncate" style="max-width: 100%;">
                                            ${products[index].pname}</h5>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="text-dark mb-0">
                                            ${formatter.format(
                                                products[index].price
                                            )}
                                        </h5>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <p class="text-muted mb-0"><span class="fw-bold">${
                                            products[index].stock
                                        }</span>
                                            In Stock</p>
                                        <div class="ms-auto text-warning">
                                            ${starFull}
                                            ${starHalf}
                                            ${starEmpty}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    `;
                }
                $("#products-row").html(content);
            },
            error: function (err) {
                console.log(err);
            },
        });
    }
    function errorCallback(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                console.log("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                console.log("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                console.log("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                console.log("An unknown error occurred.");
                break;
        }
    }
});
