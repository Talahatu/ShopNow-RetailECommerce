import $ from "jquery";

$(function () {
    const token = document
        .querySelector("meta[name=csrf-token]")
        .getAttribute("content");
    initServiceWorker(token);
});
const initServiceWorker = (token) => {
    if (!"serviceWorker" in navigator) {
        console.log("service worker is not supported");
        return;
    }

    if (PushManager in window) {
        console.log("Push is not supported");
        return;
    }

    navigator.serviceWorker
        .register("/serviceworkerV1.js")
        .then((response) => {
            initPush(token);
        })
        .catch((err) => {
            console.log("ERROR: " + err);
        });
};

const initPush = (token) => {
    if (!navigator.serviceWorker.ready) {
        console.log("Service worker is not ready!!");
        return;
    }

    new Promise(function (resolve, reject) {
        const permissionResult = Notification.requestPermission(function (
            result
        ) {
            resolve(result);
        });

        if (permissionResult) {
            permissionResult.then(resolve, reject);
        }
    })
        .then((permissionResult) => {
            if (permissionResult !== "granted") {
                throw new Error("We weren't granted permission.");
            }
            subscribeUser(token);
        })
        .catch((err) => {
            console.log(err);
        });
};
const subscribeUser = (token) => {
    navigator.serviceWorker.ready
        .then((registration) => {
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    "BE52J9nDjDid9VXZ0F0sLnCItJGZ4Da1FYRgRhMZEw7okcqv4hfc3s9d3l0kAxvRXHwQSsrlExFwNqhep8rSyvY"
                ),
            };

            // return registration.pushManager.getSubscription();
            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then((pushSubscription) => {
            // return pushSubscription.unsubscribe();
            console.log(
                "Received PushSubscription: ",
                JSON.stringify(pushSubscription)
            );
            storePushSubscription(pushSubscription, token);
        });
};

const storePushSubscription = (pushSubscription, token) => {
    const details = JSON.stringify(pushSubscription);
    console.log(details);
    $.ajax({
        type: "POST",
        url: "/pushSubscription",
        data: details,
        dataType: "json",
        contentType: "application/json",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF_TOKEN", token);
        },
        success: function (response) {
            console.log("Success: ");
            console.log(response);
        },
        error: function (err) {
            console.log("Error: ");
            console.log(err);
        },
    });
};

const urlBase64ToUint8Array = (base64String) => {
    var padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, "+")
        .replace(/_/g, "/");

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
};
