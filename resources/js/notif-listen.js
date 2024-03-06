import $ from "jquery";
import Pusher from "pusher-js";
import Push from "push.js";

$(function () {
    const baseUrl = window.location.protocol + "//" + window.location.host;

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    var pusher = new Pusher("c58a82be41ea6c60c1d7", {
        cluster: "ap1",
        channelAuthorization: {
            endpoint: "/pusher/auth",
            headers: { "X-CSRF-Token": csrfToken },
        },
    });

    console.log(Push.Permission.has());
    if (!Push.Permission.has()) {
        Push.Permission.request(onGranted, onDenied);
    }
    const onGranted = (params) => {
        // console.log(params);
    };
    const onDenied = (params) => {
        // console.log(params);
    };

    $.ajax({
        type: "POST",
        url: "/getAllRelatedShop",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            for (const order of response.orders) {
                let channel = pusher.subscribe(
                    `private-my-channel-${order.user_id}-${order.shop_id}`
                );

                channel.bind("client-notif", function (data) {
                    console.log("TEST");
                    console.log(data);
                    if (data.key == "accept") {
                        Push.create("Pesanan Diterima", {
                            body: data.message + " " + data.time,
                            icon: baseUrl + "/images/logoshpnw2_ver4.png",
                            link: "/profile/notif",
                            timeout: 4000,
                            onClick: function () {
                                window.focus();
                                this.close();
                            },
                        });
                    } else if (data.key == "sentToCourier") {
                        Push.create("Pesanan Diberikan Ke Kurir", {
                            body: data.message + " " + data.time,
                            icon: baseUrl + "/images/logoshpnw2_ver4.png",
                            link: "/profile/notif",
                            timeout: 4000,
                            onClick: function () {
                                window.focus();
                                this.close();
                            },
                        });
                    }
                });
            }
        },
    });
});
