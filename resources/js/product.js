import DataTable from "datatables.net-bs5";
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
    let table = new DataTable("#myTable", {
        responsive: true,
        columns: [
            { data: "name" },
            { data: "SKU" },
            { data: "price" },
            { data: "stock" },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <div class="d-flex flex-column">
                        <button class="btn btn-block btn-icon-text btn-info btn-rounded mb-2" data-dia="${data.id}">
                            <i class="mdi mdi-pencil btn-icon-prepend"></i>Update
                        </button>
                        <button class="btn btn-block btn-icon-text btn-danger btn-rounded" data-dia="${data.id}">
                            <i class="mdi mdi-delete btn-icon-prepend"></i>Delete
                        </button></div>`;
                },
            },
        ],
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
                console.log(response);
            },
        });
    });
});
