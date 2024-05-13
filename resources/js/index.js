import $ from "jquery";
import * as PusherPushNotifications from "@pusher/push-notifications-web";

$(document).ready(function () {
    const token = document
        .querySelector("meta[name=csrf-token]")
        .getAttribute("content");
    $(document).on("click", "#logout", function () {
        const beamsClient = new PusherPushNotifications.Client({
            instanceId: "41478210-9249-430a-8232-6659fa6e957b",
        });
        beamsClient.stop();
        $(this).parent().submit();
    });

    $("#alertClose").on("click", function () {
        $(this).parent()[0].remove();
    });
    $("#btnSearch").on("click", function () {
        let query = $("#searchInput").val() ? $("#searchInput").val() : "all";
        $(this).attr("href", "/search/" + query);
    });

    $("#btnTesting").on("click", function () {
        console.log("click");
        $.ajax({
            type: "POST",
            url: "/test",
            data: {
                _token: token,
            },
            success: function (response) {
                console.log("Clicked");
                console.log(response);
            },
        });
    });
});
