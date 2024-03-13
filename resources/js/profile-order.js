import $ from "jquery";

$(function () {
    $(".nav-link-profile").removeClass("active");
    $("#v-tabs-order-tab-lg").addClass("active");
    $("#v-tabs-order-tab").addClass("active");

    $(document).on("click", ".btn-profile-order-detail", function () {
        const orderID = $(this).attr("data-di");

        let html = `
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" modal-tags="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close btnCloseModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">

                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSave" attr-dia="">Simpan</button>
                </div>
            </div>
        </div>
    </div>
        `;
    });
});
