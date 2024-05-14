import $ from "jquery";

$(function () {
    $("#btnLogoutSeller").on("click", function () {
        // Maybe not used anymore
        // window.navigator.serviceWorker.ready.then(
        //     (serviceWorkerRegistration) => {
        //         const beamsClient = new PusherPushNotifications.Client({
        //             instanceId: "1d20c86a-7a76-4cb2-b6ff-8053628e0303",
        //             serviceWorkerRegistration: serviceWorkerRegistration,
        //         });

        //         beamsClient.stop();
        //     }
        // );
        $(this).parent().submit();
    });
});
