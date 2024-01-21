import $ from "jquery";
import Pusher from "pusher-js";

$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const id = $("#diu").val();
    var channels = [];
    var sellerID = 0;
    var win = $(this); //this = window
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
            if (win.width() > 768) {
                // To show the collapsible element
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        })
        .trigger("resize"); //trigger the resize event to run the function on page load

    $(".list-group-item-action").on("click", function () {
        if (win.width() <= 768) bsCollapse.hide();
        $("#chat-content").html("");
        // regular-seller
        sellerID = parseInt($(this).find(".dis").val());
        const sellerName = $(this).find("#seller-name").text();
        const userPP = $("#user-images-pp").attr("src");
        const sellerPP = $(this).find(".seller-images-pp").attr("src");

        if (!channels.includes(`private-my-channel-${id}-${sellerID}`)) {
            var channel = pusher.subscribe(
                `private-my-channel-${id}-${sellerID}`
            );
            // Listen for incoming chat from self and/or seller
            channel.bind("client-load-chats", function (data) {
                const msg = data.message;
                const key = data.key;
                const time = formatDate(new Date(data.time));
                const html =
                    key == "regular"
                        ? `
                <div class="d-flex flex-row justify-content-end">
                    <div>
                        <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">${msg}</p>
                        <p class="small me-3 mb-3 rounded-3 text-muted">${time}</p>
                    </div>
                    <img src="${userPP}"
                        alt="avatar 1" style="width: 40px; height: 40px;" class="rounded-circle">
                </div>
                `
                        : `
                <div class="d-flex flex-row justify-content-start">
                    <img src="${sellerPP}" alt="avatar 1" style="width: 40px; height: 40px;" class="rounded-circle">
                    <div>
                        <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${msg}</p>
                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">${time}</p>
                    </div>
                </div>
                `;
                $("#chat-content").append(html);

                scrollToBottom("chat-content");
            });
            channels.push(`private-my-channel-${id}-${sellerID}`);
        }

        $("#chat-header").html(`
            <div class="d-flex flex-row">
                <div class="pt-1">
                    <p class="fw-bold mb-0">${sellerName}</p>
                </div>
            </div>
        `);
        $("#chat-container").attr("hidden", false);

        // request prev chat
        $.ajax({
            type: "POST",
            url: "/chat/loadChats",
            data: {
                _token: csrfToken,
                sellerID: sellerID,
                userID: id,
            },
            success: function (response) {
                const contents = response;
                // Load all prev chats
                for (let index = 0; index < contents.length; index++) {
                    const time = formatDate(new Date(contents[index]["date"]));
                    const content = contents[index]["content"];
                    const html =
                        contents[index]["sender"] == "customer"
                            ? `
                        <div class="d-flex flex-row justify-content-end">
                            <div>
                                <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">${content}</p>
                                <p class="small me-3 mb-3 rounded-3 text-muted">${time}</p>
                            </div>
                            <img src="${userPP}"
                                alt="avatar 1" style="width: 40px; height: 40px" class="rounded-circle">
                        </div>
                        `
                            : `
                        <div class="d-flex flex-row justify-content-start">
                            <img src="${sellerPP}" class="rounded-circle" alt="avatar 1" style="width: 40px; height: 40px;">
                            <div>
                                <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${content}</p>
                                <p class="small ms-3 mb-3 rounded-3 text-muted float-end">${time}</p>
                            </div>
                        </div>
                        `;
                    $("#chat-content").append(html);
                }

                // Scroll to bottom
                scrollToBottom("chat-content");
            },
        });
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
                console.log(response);
                $("#exampleFormControlInput2").val("");
            },
        });
    });
});

function formatDate(date) {
    const hours = date.getHours();
    const minutes = date.getMinutes();
    const month = date.toLocaleString("default", { month: "short" });
    const day = date.getDate();

    // pad the hours with leading zeros if needed
    const formattedHours = hours < 10 ? "0" + hours : hours;

    // Pad the minutes with leading zeros if needed
    const formattedMinutes = minutes < 10 ? "0" + minutes : minutes;

    // Construct the formatted date string
    const formattedDate = `${formattedHours}:${formattedMinutes} | ${month} ${day}`;
    return formattedDate;
}

const scrollToBottom = (id) => {
    // Scroll to bottom
    $("#" + id).scrollTop(document.getElementById(id).scrollHeight);
};
