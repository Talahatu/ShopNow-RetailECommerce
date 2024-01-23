import $ from "jquery";
import Pusher from "pusher-js";

$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const channels = [];
    const ownerID = $("#dio").val();
    const shopPP = $("#seller-images-pp").attr("src");
    var customerID = 0;
    const win = $(this);

    var pusher = new Pusher("c58a82be41ea6c60c1d7", {
        cluster: "ap1",
        channelAuthorization: {
            endpoint: "/pusher/auth",
            headers: { "X-CSRF-Token": csrfToken },
        },
    });

    $(window)
        .on("resize", function () {
            if (win.width() <= 768) {
                console.log("test");
                if ($("#plist").hasClass("people-list")) {
                    $("#plist").removeClass("people-list");
                    $("#plist").removeClass("show");
                    $("#plist").addClass("collapse");
                }
            } else {
                if (!$("#plist").hasClass("people-list")) {
                    $("#plist").addClass("people-list");
                    if (
                        !$("#plist").hasClass("show") ||
                        $("#plist").hasClass("collapse")
                    ) {
                        $("#plist").removeClass("show");
                        $("#plist").removeClass("collapse");
                    }
                }
            }
        })
        .trigger("resize"); //trigger the resize event to run the function on page load

    $(".customer-item").on("click", function () {
        if (win.width() <= 768) {
            $("#plist").addClass("collapse");
            $("#plist").removeClass("show");
        }
        const customerPP = $(this).find("img").attr("src");
        const customerName = $(this).find(".name").text();
        $("#chat-header").html(`
        <img src="${customerPP}" alt="avatar" width="45" height="45" loading="lazy"
        style="object-fit: cover;" class="rounding-circle">
        <div class="chat-about">
            <h6 class="m-b-0">${customerName}</h6>
        </div>
        `);
        $("#chat-history-ul").html("");

        customerID = $(this).find(".dic").val();

        if (!channels.includes(`private-my-channel-${customerID}-${ownerID}`)) {
            var channel = pusher.subscribe(
                `private-my-channel-${customerID}-${ownerID}`
            );
            // Listen for incoming chat from self and/or seller
            channel.bind("client-load-chats", function (data) {
                const msg = data.message;
                const key = data.key;
                const time = formatDate(new Date(data.time));
                const html =
                    key == "regular"
                        ? `
                        <li class="clearfix">
                        <div class="message-data text-left d-flex flex-row justify-content-start">
                            <img src="${customerPP}" alt="avatar" width="45" height="45" loading="lazy" style="object-fit: cover;" class="rounded-circle">
                            <div>
                                <div class="small p-2 ms-3 mb-1 text-dark rounded-3 bg-light">${msg}</div>
                                <span class="small p-2 ms-2 align-self-center message-data-time text-light">${time}</span>
                            </div>
                        </div>
                    </li>
                `
                        : `
                        <li class="clearfix">
                        <div class="message-data text-right d-flex flex-row justify-content-end">
                            <div>
                                <div class="small p-2 me-3 mb-1 text-dark rounded-3 bg-light">${msg}</div>
                                <span class="small p-2 me-2 align-self-center message-data-time text-light">${time}</span>
                            </div>
                            <img src="${shopPP}" alt="avatar" width="45" height="45" loading="lazy" style="object-fit: cover;" class="rounded-circle">
                        </div>
                    </li>
                `;
                $("#chat-history-ul").append(html);

                scrollToBottom("chat-history");
            });
            channels.push(`private-my-channel-${customerID}-${ownerID}`);
        }
        $(".chat-message").attr("hidden", false);

        // request prev chat
        $.ajax({
            type: "POST",
            url: "/chat/loadChats",
            data: {
                _token: csrfToken,
                sellerID: ownerID,
                userID: customerID,
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
                            <li class="clearfix">
                                <div class="message-data text-right d-flex flex-row justify-content-start">
                                    <img src="${customerPP}" alt="avatar" width="45" height="45" loading="lazy" style="object-fit: cover;" class="rounded-circle">
                                    <div>
                                        <div class="small p-2 ms-3 mb-1 text-dark rounded-3 bg-light">${content}</div>
                                        <span class="small p-2 ms-2 align-self-center message-data-time text-light">${time}</span>
                                    </div>
                                </div>
                            </li>
                        `
                            : `
                            <li class="clearfix">
                                <div class="message-data text-right d-flex flex-row justify-content-end">
                                    <div>
                                        <div class="small p-2 me-3 mb-1 text-dark rounded-3 bg-light">${content}</div>
                                        <span class="small p-2 me-2 align-self-center message-data-time text-light">${time}</span>
                                    </div>
                                    <img src="${shopPP}" alt="avatar" width="45" height="45" loading="lazy" style="object-fit: cover;" class="rounded-circle">
                                </div>
                            </li>
                        `;
                    $("#chat-history-ul").append(html);
                }

                // Scroll to bottom
                scrollToBottom("chat-history");
                console.log("asdasd");
            },
        });
    });

    $("#submitChat").on("click", function (e) {
        console.log("test");
        const content = $("#chat-content-text").val();
        $.ajax({
            type: "POST",
            url: "/sendMessageSeller",
            data: {
                _token: csrfToken,
                content: content,
                shopID: ownerID,
                customerID: customerID,
            },
            success: function (response) {
                $("#chat-content-text").val("");
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
