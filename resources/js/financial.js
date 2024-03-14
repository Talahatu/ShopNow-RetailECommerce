import $ from "jquery";
import { Chart } from "chart.js/auto";

$(function () {
    $("#navReport").addClass("active");
    $("#navReport > div").addClass("show");
    $("#myReport > a").addClass("active");

    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        type: "POST",
        url: "/seller/financial/sold-chart",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            const data = response;

            const chart = new Chart($("#lineChart"), {
                type: "line",
                options: {
                    aspectRatio: 1,
                    scales: {
                        y: {
                            ticks: {
                                max: 100,
                                stepSize: 10,
                                callback: function (value, index, values) {
                                    return Math.ceil(value);
                                },
                            },
                        },
                    },
                },
                data: {
                    labels: data.map((row) => row.Month),
                    datasets: [
                        {
                            label: "Pesanan Selesai Per Bulan",
                            data: data.map((row) => row.Sold),
                        },
                    ],
                },
            });
        },
        error: function (param) {
            console.log(param);
        },
    });

    $.ajax({
        type: "POST",
        url: "/seller/financial/popular",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            const data = response;
            console.log(data);

            const chart = new Chart($("#barChart"), {
                type: "bar",
                options: {
                    aspectRatio: 1,
                    scales: {
                        y: {
                            ticks: {
                                max: 100,
                                stepSize: 10,
                                callback: function (value, index, values) {
                                    return Math.ceil(value);
                                },
                            },
                        },
                    },
                },
                data: {
                    labels: data.map((row) => row.Name),
                    datasets: [
                        {
                            label: "Jumlah Produk Dipesan",
                            data: data.map((row) => row.Sold),
                        },
                    ],
                },
            });
        },
        error: function (param) {
            console.log(param);
        },
    });
});
