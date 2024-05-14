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

//     $.ajax({
//         type: "POST",
//         url: "/getLoggedIn",
//         data: {
//             _token: token,
//         },
//         success: function (response) {
//             window.navigator.serviceWorker.ready.then(
//                 (serviceWorkerRegistration) => {
//                     const beamsClient = new PusherPushNotifications.Client({
//                         instanceId: "1d20c86a-7a76-4cb2-b6ff-8053628e0303",
//                         serviceWorkerRegistration: serviceWorkerRegistration,
//                     });
//                     const beamTokenProvider =
//                         new PusherPushNotifications.TokenProvider({
//                             url: "/pusher/request-token",
//                             headers: {
//                                 "X-CSRF_TOKEN": token,
//                             },
//                         });
//                     beamsClient.getRegistrationState().then((state) => {
//                         let states = PusherPushNotifications.RegistrationState;
//                         console.log(state);
//                         switch (state) {
//                             case states.PERMISSION_GRANTED_REGISTERED_WITH_BEAMS:
//                             case states.PERMISSION_PROMPT_REQUIRED:
//                                 beamsClient
//                                     .start()
//                                     .then(() =>
//                                         beamsClient.setUserId(
//                                             `${response}`,
//                                             beamTokenProvider
//                                         )
//                                     )
//                                     .catch(console.error);
//                                 break;
//                             case states.PERMISSION_GRANTED_NOT_REGISTERED_WITH_BEAMS:
//                                 beamsClient.start().then(() => {
//                                     beamsClient.setUserId(
//                                         `${response}`,
//                                         beamTokenProvider
//                                     );
//                                 });
//                             case states.PERMISSION_DENIED:
//                                 beamsClient.stop();
//                                 $.ajax({
//                                     type: "POST",
//                                     url: "/notification/change/denied",
//                                     data: {
//                                         _token: token,
//                                     },
//                                     success: function (response) {
//                                         console.log(response);
//                                     },
//                                 });
//                             default:
//                                 break;
//                         }
//                     });
//                 }
//             );
//         },
//     });

//     // Device Interest
//     window.navigator.serviceWorker.ready.then((serviceWorkerRegistration) => {
//         const beamsClient = new PusherPushNotifications.Client({
//             instanceId: "41478210-9249-430a-8232-6659fa6e957b",
//             serviceWorkerRegistration: serviceWorkerRegistration,
//         });
//         beamsClient
//             .start()
//             .then((beamsClient) => beamsClient.getDeviceId())
//             .then((deviceId) =>
//                 console.log(
//                     "Successfully registered with Beams. Device ID:",
//                     deviceId
//                 )
//             )
//             .then(() => {
//                 beamsClient.addDeviceInterest("Notification");
//             })
//             .then(() => beamsClient.getDeviceInterests())
//             .then((interests) => console.log("Current interests:", interests))
//             .catch(console.error);
//     });
// };

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
