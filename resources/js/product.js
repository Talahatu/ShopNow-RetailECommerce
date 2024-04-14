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
    $("#navProduct").addClass("active");
    $("#navProduct > div").addClass("show");
    $("#myproduct > a").addClass("active");
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    const opsiLanguage = {
        emptyTable: "Tidak ada produk",
        lengthMenu: "Menampilkan _MENU_ Produk",
        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ Produk",
        search: "Cari",
        paginate: {
            previous: "Sebelumnya",
            next: "Selanjutnya",
        },
    };

    var DTcolumns = (optionType = "live") => {
        return [
            { data: "name" },
            { data: "SKU" },
            {
                data: "price",
                render: $.fn.dataTable.render.number(".", ",", 0, "Rp "),
            },
            { data: "stock" },
            {
                data: null,
                render: function (data, type, row) {
                    if (optionType == "live") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <div class="btn-group" role="group">
                            <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Ubah</a></li>
                            <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Hapus</a></li>
                            </ul>
                        </div>
                            <button class="btn btn-block btn-lg btn-outline-info btn-archive p-2" data-dia="${data.id}"><i class="mdi mdi-archive btn-icon-prepend"></i>Arsip</button>
                        </div>`;
                    } else if (optionType == "archive") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <div class="btn-group" role="group">
                            <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Ubah</a></li>
                            <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Hapus</a></li>
                            </ul>
                        </div>
                            <button class="btn btn-block btn-lg btn-outline-success btn-live p-2" data-dia="${data.id}"><i class="mdi mdi-folder-lock-open btn-icon-prepend"></i>Kembali Dijual</button>
                        </div>`;
                    } else {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            <div class="btn-group" role="group">
                                <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Aksi</button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                                <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Ubah</a></li>
                                <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Hapus</a></li>
                                </ul>
                            </div>
                        </div>`;
                    }
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
        rowId: function (row) {
            return "row_" + row.id;
        },
        columns: DTcolumns(),
        columnDefs: [{ targets: [2, 3], className: "text-end" }],
    });

    $.ajax({
        type: "POST",
        url: "/fetch/product/live",
        data: {
            _token: csrfToken,
        },
        success: function (response) {
            const data = response.data;
            table.rows.add(data).draw();
            table.columns.adjust().draw();
            columnOpenFix();
        },
    });
    $(document).on("click", ".btn-delete", function () {
        let result = confirm("Apakah anda yakin ingin menghapus produk ini?");
        if (!result) return;
        const id = $(this).attr("data-dia");
        $.ajax({
            type: "DELETE",
            url: "/product/" + id,
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
    $(document).on("click", ".btn-archive", function () {
        const id = $(this).attr("data-dia");
        $.ajax({
            type: "PUT",
            url: "/archive/product",
            data: {
                _token: csrfToken,
                id: id,
            },
            success: function (response) {
                const data = response.data;
                $("#row_" + data.id).remove();
            },
        });
    });
    $(document).on("click", ".btn-live", function () {
        const id = $(this).attr("data-dia");
        $.ajax({
            type: "PUT",
            url: "/live/product",
            data: {
                _token: csrfToken,
                id: id,
            },
            success: function (response) {
                const data = response.data;
                $("#row_" + data.id).remove();
            },
        });
    });
    $(document).on("click", ".product-type", function () {
        const type = $(this).attr("data-type");
        $.ajax({
            type: "POST",
            url: "/fetch/product/repopulate",
            data: {
                _token: csrfToken,
                type: type,
            },
            success: function (response) {
                const data = response.data;
                table.clear().draw();
                table.destroy();
                table = $("#myTable").DataTable({
                    responsive: true,
                    language: opsiLanguage,
                    rowId: function (row) {
                        return "row_" + row.id;
                    },
                    columns: DTcolumns(type),
                    columnDefs: [{ targets: [2, 3], className: "text-end" }],
                });
                table.rows.add(data).draw();
                table.columns.adjust().draw();
                columnOpenFix();
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
