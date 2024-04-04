import $ from "jquery";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import "leaflet-routing-machine";
import "lrm-graphhopper";
import "leaflet-routing-machine/dist/leaflet-routing-machine.css";
import moment from "moment/moment";
import "moment/locale/id";
import "jquery-mask-plugin";

$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;

    delete L.Icon.Default.prototype._getIconUrl;

    L.Icon.Default.mergeOptions({
        iconRetinaUrl: "/images/vendor/leaflet/dist/marker-icon-2x.png",
        iconUrl: "/images/vendor/leaflet/dist/marker-icon.png",
        shadowUrl: "/images/vendor/leaflet/dist/marker-shadow.png",
    });
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    $(".btnTakeDelivery").on("click", function () {
        const [orderID, deliveryID] = $(this).attr("data-di").split("-");
        $("#exampleModal").find(".modal-footer").html(`
            <button type="button" class="button button-border button-rounded button-teal button-fill" data-bs-dismiss="modal" id="closeModal"><span>Tutup</span></button>
            <button type="button" class="button button-border button-rounded button-green button-fill me-2" id="saveDelivery" data-di="${orderID}-${deliveryID}"><span>Mulai Pengiriman</span></button>
            
        `);
        $("#exampleModalLabel").html("Rincian Pesanan");

        $.ajax({
            type: "POST",
            url: "/getDeliveryDetail",
            data: {
                _token: csrfToken,
                orderID: orderID,
            },
            success: function (response) {
                const order = response;
                const latitudeDestination = order.destination_latitude;
                const longitudeDestination = order.destination_longitude;

                generateModal(order);
                generateMap(latitudeDestination, longitudeDestination);
            },
        });
    });

    $(document).on("click", "#saveDelivery", function () {
        const [order_ID, delivery_ID] = $(this).attr("data-di").split("-");
        $("#loader").removeClass("d-none");
        $("#loader").addClass("d-flex");
        $.ajax({
            type: "POST",
            url: "/pickupItems",
            data: {
                _token: csrfToken,
                orderID: order_ID,
                deliveryID: delivery_ID,
            },
            success: function (response) {
                if (response) {
                    const id = response.id;
                    const startDate = response.startDate;
                    const orderID = response.orderID;
                    const address = response.address;
                    const deliveryID = response.deliveryID;
                    const html = `
                    <article class="entry event col-12 mb-4">
                        <div
                            class="grid-inner bg-white row g-0 p-3 border-0 rounded-5 shadow-sm h-shadow all-ts h-translate-y-sm">
                            <div class="col-md-12 p-4">
                                <div class="entry-meta no-separator mb-1 mt-0 d-flex d-sm-block justify-content-center">
                                    <ul>
                                        <li>
                                            <span
                                                class="text-uppercase fw-medium">${moment(
                                                    startDate
                                                ).format(
                                                    "dddd, D MMMM YYYY"
                                                )}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="entry-title">
                                    <h3 class="text-center text-sm-start"><a>${orderID}</a></h3>
                                </div>
                                <div class="entry-meta no-separator text-center text-sm-start">
                                    <ul>
                                        <li><a class="fw-normal"><i class="uil uil-map-marker"></i>
                                                ${address}</a></li>
                                    </ul>
                                </div>
                                <div class="entry-content my-3 text-center text-md-start">
                                    <a class="button button-border button-rounded button-green button-fill btnDeliveryDone"
                                        data-di="${id}-${deliveryID}" href="${baseUrl}/courier/delivery/${id}/${deliveryID}"><i
                                            class="fa-solid fa-truck-fast"></i><span>Rinci Pengiriman</span></a>
                                </div>
                            </div>
                        </div>
                    </article>
                    `;

                    $("#now").prepend(html);

                    const button = $(
                        ".btnTakeDelivery[data-di='" +
                            order_ID +
                            "-" +
                            delivery_ID +
                            "']"
                    )[0];
                    const article = $(button)
                        .parent()
                        .parent()
                        .parent()
                        .parent()[0];

                    $(article).remove();

                    const modal = document.getElementById("exampleModal");
                    bootstrap.Modal.getInstance(modal).hide();
                    $("#loader").removeClass("d-flex");
                    $("#loader").addClass("d-none");

                    window.location.reload();
                }
            },
        });
    });

    // Change to new page
    // $(".btnDeliveryDone").on("click", function () {
    //     const [orderID, deliveryID] = $(this).attr("data-di").split("-");
    //     $("#exampleModal").find(".modal-footer").html(`
    //         <button type="button" class="button button-border button-rounded button-teal button-fill" data-bs-dismiss="modal" id="closeModal"><span>Tutup</span></button>
    //         <button type="button" class="button button-border button-rounded button-green button-fill me-2" id="finishDelivery" data-di="${orderID}-${deliveryID}" disabled><span>Selesaikan</span></button>

    //     `);
    //     $("#exampleModalLabel").html("Selesaikan Pengiriman");

    //     $.ajax({
    //         type: "POST",
    //         url: "/getDeliveryDetail",
    //         data: {
    //             _token: csrfToken,
    //             orderID: orderID,
    //         },
    //         success: function (response) {
    //             const order = response;
    //             const latitudeDestination = order.destination_latitude;
    //             const longitudeDestination = order.destination_longitude;

    //             generateModal(order, "finish");
    //             generateMap(latitudeDestination, longitudeDestination);

    //             if (order.payment_method == "cod") {
    //                 $("#finishDelivery").attr("disabled", true);
    //                 $("#operationalFeeUsed").mask("#.##0", {
    //                     reverse: true,
    //                 });
    //                 $(document).on("input", "#operationalFeeUsed", function () {
    //                     const image = $("#proofImage")[0];
    //                     checkFilled(image, $(this).val());
    //                 });
    //             }

    //             $(document).on("change", "#proofImage", function () {
    //                 if (order.payment_method == "cod") {
    //                     checkFilled(this, $("#operationalFeeUsed").val());
    //                 } else {
    //                     $("#finishDelivery").attr(
    //                         "disabled",
    //                         !this.files.length > 0
    //                     );
    //                 }
    //             });
    //         },
    //     });
    // });

    // $(document).on("click", "#finishDelivery", function () {
    //     const [orderID, deliveryID] = $(this).attr("data-di").split("-");
    //     $("#loader").removeClass("d-none");
    //     $("#loader").addClass("d-flex");
    //     $.ajax({
    //         type: "POST",
    //         url: "/getOrderPaymentType",
    //         data: {
    //             _token: csrfToken,
    //             orderID: orderID,
    //         },
    //     })
    //         .then(function (response) {
    //             const type = response.type;
    //             let result = true;
    //             let saku = 0;
    //             let image = $("#proofImage")[0];
    //             if (type == "cod") {
    //                 saku = $("#operationalFeeUsed").cleanVal();
    //                 result = confirm(
    //                     "Apakah anda yakin uang saku yang digunakan sebesar " +
    //                         formatter.format(saku) +
    //                         "?"
    //                 );
    //             }

    //             let formData = new FormData();
    //             formData.append("_token", csrfToken);
    //             formData.append("file", image.files[0]);
    //             formData.append("orderID", orderID);
    //             formData.append("deliveryID", deliveryID);
    //             formData.append("moneyUsed", saku);
    //             formData.append("type", type);

    //             if (result)
    //                 return $.ajax({
    //                     type: "POST",
    //                     url: "/delivery/finish",
    //                     data: formData,
    //                     processData: false,
    //                     contentType: false,
    //                 });
    //         })
    //         .then(function (response) {
    //             if (response) {
    //                 const button = $(
    //                     ".btnDeliveryDone[data-di='" +
    //                         orderID +
    //                         "-" +
    //                         deliveryID +
    //                         "']"
    //                 )[0];
    //                 const article = $(button)
    //                     .parent()
    //                     .parent()
    //                     .parent()
    //                     .parent()[0];

    //                 $(article).remove();

    //                 const modal = document.getElementById("exampleModal");
    //                 bootstrap.Modal.getInstance(modal).hide();
    //                 $("#loader").removeClass("d-flex");
    //                 $("#loader").addClass("d-none");
    //             }
    //         })
    //         .catch(function (param) {
    //             console.log(param);
    //         });
    // });

    // Might not be used at all
    // $("#withdrawFee").on("click", function () {
    //     const max = $("#feeAvailable").attr("data-val");
    //     $("#exampleModal").find(".modal-footer").html(`
    //         <button type="button" class="button button-border button-rounded button-teal button-fill" data-bs-dismiss="modal" id="closeModal"><span>Tutup</span></button>
    //         <button type="button" class="button button-border button-rounded button-green button-fill me-2" id="withdrawFeeProcess" disabled><span>Tarik</span></button>

    //     `);
    //     $("#exampleModalLabel").html("Penarikan Uang Saku");

    //     $("#exampleModal").find(".modal-body").html(`
    //         <div class="col-12">
    //                 <label for="operationalFeeWithdraw">Jumlah Uang:</label>
    //                 <div class="input-group">
    //                     <span class="input-group-text">Rp</span>
    //                         <input id="operationalFeeWithdraw" name="operationalFeeWithdraw" type="text" class="form-control" placeholder="Masukan nominal uang saku">
    //                 </div>
    //             </div>
    //     `);
    //     $("#operationalFeeWithdraw").mask("#.##0", {
    //         reverse: true,
    //     });

    //     $(document).on("input", "#operationalFeeWithdraw", function () {
    //         $("#withdrawFeeProcess").attr(
    //             "disabled",
    //             $(this).cleanVal() <= 5000
    //         );

    //         if ($(this).cleanVal() > max) {
    //             $("#operationalFeeWithdraw").val(max).trigger("input");
    //             alert("Nominal melebihi nominal uang saku tersedia!");
    //         }
    //     });
    // });

    // $(document).on("click", "#withdrawFeeProcess", function () {
    //     $.ajax({
    //         type: "POSS",
    //         url: "/courier/fee/withdraw",
    //         data: {
    //             _token: csrfToken,
    //             amount: $("#operationalFeeWithdraw").cleanVal(),
    //         },
    //         success: function (response) {
    //             $("#feeAvailable").html(formatter.format(response));
    //             $("#feeAvailable").attr("data-val", response);

    //             const modal = document.getElementById("exampleModal");
    //             bootstrap.Modal.getInstance(modal).hide();
    //         },
    //     });
    // });

    // =========================================== Functions Start ==================================================

    const generateModal = (order, data) => {
        // let codAddition = ``;
        // let finishAdd = "";
        let html = `
            <div class="row">
                <div class="form-group col-6">
                    <label for="orderNumber" ><b>Nama Pemesan:&nbsp;</b></label>
                    <div >
                        <label for="orderNumber" >${order.user.name}</label>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="hp"><b>Nomor HP:&nbsp;</b></label>
                    <div >
                        <label for="hp">${order.user.phoneNumber}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                        <label for="orderNumber"><b>Nomor Pesanan:&nbsp;</b></label>
                        <div >
                            <label for="orderNumber">${order.orderID}</label>
                        </div>
                    </div>
                <div class="form-group col-6">
                    <label for="paymentType"><b>Jenis Pembayaran:&nbsp;</b></label>
                    <div class="col-sm-7">
                        <label for="paymentType" class="col-form-label">${
                            order.payment_method == "cod"
                                ? "Pembayaran di tempat (COD)"
                                : "Saldo"
                        }</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                    <label for="orderNumber" class="col-sm-5 col-form-label"><b>Tanggal Pengiriman:&nbsp;</b></label>
                    <div class="col-sm-7">
                        <label for="orderNumber" class="col-form-label">${moment(
                            order.deliveries[0].start_date
                        ).format("dddd, D MMMM YYYY")}</label>
                    </div>
                </div>
            <div class="form-group row">
                <label for="destination" class="col-sm-3 col-form-label"><b>Alamat Toko:&nbsp;</b></label>
                <div class="col-sm-9">
                    <label for="destination" class="col-form-label">${
                        order.shop.address
                    }</label>
                </div>
            </div>
            <div class="form-group row">
                <label for="destination" class="col-sm-3 col-form-label"><b>Alamat Tujuan:&nbsp;</b></label>
                <div class="col-sm-9">
                    <label for="destination" class="col-form-label">${
                        order.destination_address
                    }</label>
                </div>
            </div>
            <div class="style-msg infomsg">
					<div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Jangan lupa untuk memastikan anda menerima uang operasional yang diberikan penjual sebelum memulai pengiriman</div>
				</div>
                <div class="form-group row">
                    <label for="orderTotal" class="col-sm-5 col-form-label"><b>Nominal Uang Operational:&nbsp;</b></label>
                    <div class="col-sm-7">
                        <label for="orderTotal" class="col-form-label">${formatter.format(
                            order.deliveries[0].feeAssigned
                        )}
                    </div>
                </div>
            <hr>
            <div id="map" style="height: 360px" class="m-4"></div>
            `;
        // if (data == "finish") {
        //     finishAdd = `
        //         <div class="divider divider-rounded divider-center"><i class="bi-camera-fill"></i></div>
        //         <div class="style-msg infomsg">
        // 			<div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Gambar yang dipilih hanya dalam bentuk jpg, jpeg, dan png</div>
        // 		</div>
        //         <div class="form-group row">
        //             <label for="proof" class="col-sm-5 col-form-label"><b>Bukti Selesai:&nbsp;</b></label>
        //             <div class="col-sm-7">
        //                 <input type="file" accept="image/png, image/jpg, image/jpeg" capture="camera" id="proofImage">
        //             </div>
        //         </div>
        //     `;
        //     html += finishAdd;
        //     if (order.payment_method == "cod") {
        //         codAddition = `
        //         <div class="divider divider-rounded divider-center"><i class="bi-pencil"></i></div>
        //         <div class="style-msg alertmsg">
        // 			<div class="sb-msg"><i class="bi-exclamation-diamond-fill"></i><strong>Perhatian!</strong> Isi bagian "Uang saku digunakan" untuk menyelesaikan pengiriman</div>
        // 		</div>
        //         <div class="form-group row">
        //             <label for="orderTotal" class="col-sm-5 col-form-label"><b>Total Pembayaran:&nbsp;</b></label>
        //             <div class="col-sm-7">
        //                 <label for="orderTotal" class="col-form-label">${formatter.format(
        //                     order.total
        //                 )}
        //             </div>
        //         </div>
        //         <div class="col-12">
        //             <label for="operationalFeeUsed">Uang Saku Digunakan:</label>
        //             <div class="input-group">
        //                 <span class="input-group-text">Rp</span>
        //                     <input id="operationalFeeUsed" name="operationalFeeUsed" type="text" class="form-control" placeholder="Masukan nominal uang saku">
        //             </div>
        //         </div>`;
        //         html += codAddition;
        //     }
        // }
        $("#exampleModal").find(".modal-body").html(html);
    };

    const generateMap = (latitudeDestination, longitudeDestination) => {
        //===================================== Map Section Start =====================================
        var map = L.map("map").setView(
            [latitudeDestination, longitudeDestination],
            15
        );

        // Don't remove!!!!!!!!!!!!!!!!!!!!!!
        // let markerStart = L.marker([-7.28838, 112.77239], {
        //     draggable: false,
        //     bubblingMouseEvents: false,
        // }).addTo(map);

        let markerEnd = L.marker([latitudeDestination, longitudeDestination], {
            draggable: false,
            bubblingMouseEvents: false,
        }).addTo(map);

        // Don't remove!!!!!!!!!!!!!!!!!!!!!!
        // markerStart.bindTooltip("<b>Alamat Toko</b>").openTooltip();

        markerEnd.bindTooltip("<b>Alamat Tujuan</b>").openTooltip();

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 17,
            minZoom: 13,
            attribution:
                '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        // Don't remove!!!!!!!!!!!!!!!!!!!!!!
        // let routeControl = L.Routing.control({
        //     waypoints: [
        //         L.latLng(-7.28838, 112.77239), // Start point
        //         L.latLng(-7.31997, 112.767487), // End point
        //     ],
        //     router: L.Routing.graphHopper(
        //         "fc06c31b-e90b-47b4-941f-42b9c8971b33"
        //     ),
        //     createMarker: function (i, waypoint, n) {
        //         return i === 0 ? markerStart : markerEnd;
        //     },
        //     routeWhileDragging: false,
        //     addWaypoints: false,
        // }).addTo(map);
        //===================================== Map Section End =====================================
    };

    // Changes to new page
    // const checkFilled = (image, fee) => {
    //     $("#finishDelivery").attr(
    //         "disabled",
    //         fee == "" || image.files.length <= 0
    //     );
    // };

    // Not Used maybe
    // function startCamera(front) {
    //     console.log(front);
    //     const video = document.getElementById("webcam");
    //     const constraints = {
    //         video: {
    //             facingMode: front ? "user" : "environment",
    //             width: 320,
    //             height: 320,
    //         },
    //     };
    //     if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    //         console.log("AHA");
    //         navigator.mediaDevices
    //             .getUserMedia(constraints)
    //             .then(function (stream) {
    //                 console.log(stream);
    //                 video.srcObject = stream;
    //             })
    //             .catch(function (err) {
    //                 console.error("Error accessing camera: ", err);
    //             });
    //     } else {
    //         console.error("getUserMedia not supported on your browser!");
    //     }
    // }
});
