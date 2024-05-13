import $ from "jquery";
import * as PusherPushNotifications from "@pusher/push-notifications-web";

$(function () {
    const token = document
        .querySelector("meta[name=csrf-token]")
        .getAttribute("content");
    initServiceWorker(token);
    console.log("TESTT");
});
const initServiceWorker = (token) => {
    console.log("Initiate notification script...");
    if (!"serviceWorker" in navigator) {
        console.log("service worker is not supported");
        return;
    }

    if (PushManager in window) {
        console.log("Push is not supported");
        return;
    }

    navigator.serviceWorker
        .register("/service-worker.js")
        .then((response) => {
            console.log("Service worker is installed");
            initPush(token);
        })
        .catch((err) => {
            console.log("ERROR: " + err);
        });
};

const initPusher = (token) => {
    const beamsClient = new PusherPushNotifications.Client({
        instanceId: "41478210-9249-430a-8232-6659fa6e957b",
    });

    const beamTokenProvider = new PusherPushNotifications.TokenProvider({
        url: "/pusher/request-token",
        headers: {
            "X-CSRF_TOKEN": token,
        },
    });
    beamsClient
        .start()
        .then(() => beamsClient.setUserId("0", beamTokenProvider))
        .catch(console.error);

    // Device Interest
    // window.navigator.serviceWorker.ready.then((serviceWorkerRegistration) => {
    //     const beamsClient = new PusherPushNotifications.Client({
    //         instanceId: "41478210-9249-430a-8232-6659fa6e957b",
    //         serviceWorkerRegistration: serviceWorkerRegistration,
    //     });
    //     beamsClient
    //         .start()
    //         .then((beamsClient) => beamsClient.getDeviceId())
    //         .then((deviceId) =>
    //             console.log(
    //                 "Successfully registered with Beams. Device ID:",
    //                 deviceId
    //             )
    //         )
    //         .then(() => {
    //             beamsClient.addDeviceInterest("Notification");
    //         })
    //         .then(() => beamsClient.getDeviceInterests())
    //         .then((interests) => console.log("Current interests:", interests))
    //         .catch(console.error);
    // });
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
            // Not working i dunno
            // subscribeUser();

            initPusher(token);
        })
        .catch((err) => {
            console.log(err);
        });
};
const subscribeUser = () => {
    navigator.serviceWorker.ready
        .then((registration) => {
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    "BCXOau7LQQJy-k5B8_aolbDVbw7II3rdNuX8JCfvYYBsw4vnFaG4WALCL9eoaTJNmL2YOxNit9tKUiex4ER4rWI"
                ),
            };

            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then((pushSubscription) => {
            console.log(
                "Received PushSubscription: ",
                JSON.stringify(pushSubscription)
            );
            storePushSubscription(pushSubscription);
        });
};

const storePushSubscription = (pushSubscription) => {
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
