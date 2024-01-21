import $ from "jquery";
import Pusher from "pusher-js";

$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const channels = [];

    var pusher = new Pusher("c58a82be41ea6c60c1d7", {
        cluster: "ap1",
        channelAuthorization: {
            endpoint: "/pusher/auth",
            headers: { "X-CSRF-Token": csrfToken },
        },
    });

    $(".customer-item").on("click", function () {
        const customerPP = $(this).find("img").attr("src");
        const shopPP = $("#seller-images-pp").attr("src");
        const customerName = $(this).find(".name").text();
        $("#chat-header").html(`
        <img src="${customerPP}" alt="avatar" width="45" height="45" loading="lazy"
        style="object-fit: cover;" class="rounding-circle">
        <div class="chat-about">
            <h6 class="m-b-0">${customerName}</h6>
        </div>
        `);
        $("#chat-history-ul").html("");
        $(".chat-message").attr("hidden", false);
    });
});
