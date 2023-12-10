import $ from "jquery";
import Pusher from "pusher-js";

$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const id = $("#diu").val();
    var sellerID = 0;
    var pusher = new Pusher("c58a82be41ea6c60c1d7", {
        cluster: "ap1",
        channelAuthorization: {
            endpoint: "/pusher/auth",
            headers: { "X-CSRF-Token": csrfToken },
        },
    });

    var myCollapse = document.getElementById("navbarLeftAlignExample");
    var bsCollapse = new bootstrap.Collapse(myCollapse, {
        toggle: false,
    });

    $(window)
        .on("resize", function () {
            var win = $(this); //this = window
            if (win.width() > 768) {
                // To show the collapsible element
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        })
        .trigger("resize"); //trigger the resize event to run the function on page load

    $(".list-group-item-action").on("click", function () {
        // regular-seller
        sellerID = parseInt($(this).find(".dis").val());

        var channel = pusher.subscribe(`private-my-channel-${id}-${sellerID}`);

        channel.bind("client-load-chats", function (data) {
            const msg = data.message;
            const key = data.key;
            const datetime = "";
            const sellerPP = "";
            const html =
                key == "regular"
                    ? `
            <div class="d-flex flex-row justify-content-end">
                <div>
                    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">${msg}</p>
                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13</p>
                </div>
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                    alt="avatar 1" style="width: 45px; height: 100%;">
            </div>
            `
                    : `
            <div class="d-flex flex-row justify-content-start">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp" alt="avatar 1" style="width: 45px; height: 100%;">
                <div>
                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${msg}</p>
                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM | Aug 13</p>
                </div>
            </div>
            `;
            $("#chat-content").append(html);
        });

        $("#chat-container").attr("hidden", false);

        // $.ajax({
        //     type: "POST",
        //     url: "/chat/loadChats",
        //     data: {
        //         _token: csrfToken,
        //         sellerID: sellerID,
        //         userID: id,
        //     },
        //     success: function (response) {
        //         var channel = pusher.subscribe(`my-channel-${id}-${sellerID}`);
        //     },
        // });
    });
    $("#submitChat").on("click", function (e) {
        const content = $("#exampleFormControlInput2").val();
        $.ajax({
            type: "POST",
            url: "/sendMessage",
            data: {
                _token: csrfToken,
                content: content,
                sellerID: sellerID,
            },
            success: function (response) {
                $("#exampleFormControlInput2").val("");
            },
        });
    });
});
