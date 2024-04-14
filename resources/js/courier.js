import $ from "jquery";

import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-fixedcolumns-bs5";
import "datatables.net-fixedheader-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-select-bs5";

document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        $("#loader").addClass("d-none");
        $("#loader").removeClass("d-flex");
    }
};
$(function () {
    $("#navCourier").addClass("active");
    $("#navCourier > div").addClass("show");
    $("#mycourier > a").addClass("active");

    const csrfToken = $('meta[name="csrf-token"]').attr("content");
    const baseUrl = window.location.protocol + "//" + window.location.host;

    const opsiLanguage = {
        emptyTable: "Tidak ada Kurir",
        lengthMenu: "Menampilkan _MENU_ Kurir",
        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ Kurir",
        search: "Cari",
        paginate: {
            previous: "Sebelumnya",
            next: "Selanjutnya",
        },
    };

    let formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });

    var DTcolumns = () => {
        return [
            {
                data: "id",
            },
            { data: "name" },
            {
                data: "operationalFee",
            },
            { data: "status" },
            {
                data: null,
                render: function (data, type, row) {
                    const html = `<a class="btn btn-outline-info btn-update m-1" data-di="${data.id}" href="${baseUrl}/courier/update/${data.id}">Ubah</a><br><button class="btn btn-outline-danger btn-delete m-1" data-di="${data.id}">Hapus</button>`;
                    return html;
                },
            },
        ];
    };

    var table = $("#myTable").DataTable({
        responsive: {
            details: {
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.hidden
                            ? '<tr data-dt-row="' +
                                  col.rowIndex +
                                  '" data-dt-column="' +
                                  col.columnIndex +
                                  '">' +
                                  (col.title == "Action"
                                      ? ``
                                      : `<td>${col.title}</td>`) +
                                  (col.title == "Action"
                                      ? `<td>${col.data}</td>`
                                      : `<td>: ${col.data}</td>`) +
                                  "</tr>"
                            : "";
                    }).join("");
                    return data ? $("<table/>").append(data) : false;
                },
            },
        },
        language: opsiLanguage,
        columns: DTcolumns(),
        columnDefs: [
            { targets: [2], className: "text-end" },
            { targets: [0], visible: false },
        ],
    });
    columnOpenFix();

    $(document).on("click", ".btn-delete", function () {
        let result = confirm("Apakah anda yakin ingin menghapus kurir ini?");
        if (!result) return;
        const id = $(this).attr("data-di");
        $.ajax({
            type: "DELETE",
            url: "/courier/delete/" + id,
            headers: { "X-CSRF-TOKEN": csrfToken },
            success: function (response) {
                if (!response) return;
                $("#row_" + id).remove();
            },
            error: function (er) {
                console.log(er);
            },
        });
    });
});

const columnOpenFix = () => {
    // datatable.net type column somehow not working, this is a makeshift solution
    $("td")
        .filter(function () {
            return $(this).children("div").length === 0;
        })
        .on("click", function () {
            const parent = $(this).parent();
            const firstChild = $(parent).children().first();
            if (!$(this).hasClass("dtr-control")) $(firstChild).click();
        });
};
