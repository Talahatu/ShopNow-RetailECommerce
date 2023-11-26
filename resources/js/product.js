import $ from "jquery";
import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-fixedcolumns-bs5";
import "datatables.net-fixedheader-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-select-bs5";

$(function () {
    $("#navProduct").addClass("active");
    $("#navProduct > div").addClass("show");
    $("#myproduct > a").addClass("active");
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

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
                            <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Update</a></li>
                            <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Delete</a></li>
                            </ul>
                        </div>
                            <button class="btn btn-block btn-lg btn-outline-info btn-archive p-2" data-dia="${data.id}"><i class="mdi mdi-archive btn-icon-prepend"></i>Archive</button>
                        </div>`;
                    } else if (optionType == "archive") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                        <div class="btn-group" role="group">
                            <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                            <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Update</a></li>
                            <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Delete</a></li>
                            </ul>
                        </div>
                            <button class="btn btn-block btn-lg btn-outline-success btn-live p-2" data-dia="${data.id}"><i class="mdi mdi-folder-lock-open btn-icon-prepend"></i>Live</button>
                        </div>`;
                    } else if (optionType == "problem") {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            <div class="btn-group" role="group">
                                <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                                <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Update</a></li>
                                <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Delete</a></li>
                                </ul>
                            </div>
                        </div>`;
                    } else {
                        return `
                        <div class="d-flex flex-column btn-group-vertical">
                            <div class="btn-group" role="group">
                                <button id="btnGroupAction" type="button" class="btn btn-outline-primary dropdown-toggle p-2" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupAction">
                                <li><a class="btn btn-block btn-icon-text btn-info btn-update dropdown-item mb-2" href="/product/${data.id}/edit" data-dia="${data.id}">Update</a></li>
                                <li><a class="btn btn-block btn-icon-text btn-danger btn-delete dropdown-item" data-dia="${data.id}">Delete</a></li>
                                </ul>
                            </div>
                        </div>`;
                    }
                },
            },
        ];
    };
    var table = $("#myTable").DataTable({
        responsive: true,
        language: {
            emptyTable: "No product available",
        },
        rowId: function (row) {
            return "row_" + row.id;
        },
        columns: DTcolumns(),
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
        },
    });
    $(document).on("click", ".btn-delete", function () {
        let result = confirm(
            "Are you sure you want to delete this product from your shops?"
        );
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
                    language: {
                        emptyTable: "No product available",
                    },
                    rowId: function (row) {
                        return "row_" + row.id;
                    },
                    columns: DTcolumns(type),
                });
                table.rows.add(data).draw();
            },
        });
    });
});
