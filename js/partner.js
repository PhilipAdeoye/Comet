$(document).ready(function () {
    var partnersContainer = $("#partnersContainer");
    var editModal = $("#partnerEditModal");
    var createModal = $("#partnerCreateModal");

    getPartners();

    $("#addNewPartnerBtn").on("click", function () {
        var url = $(this).data("url");
        $.get(url, {
        }).done(function (data) {
            createModal.find(".modal-body .form-content").html(data);
            createModal.modal("show");
            createModal.find(".modal-footer > .btn-primary").prop("disabled", false);
        }).fail(function () {
            toastrRegularError("Sorry, we encountered an error", "Error");
        });
    });

    createModal.on("click", ".modal-footer > .btn-primary", function () {
        var primaryBtn = $(this);
        var form = $("#" + primaryBtn.data("formId"));
        if (form.length > 0) {
            primaryBtn.prop("disabled", true);
            $.post(form.prop('action'), form.serialize())
                .done(function (data) {
                    createModal.find(".modal-body .form-content").html(data);
                    var errors = createModal.find(".modal-body .validation-errors-alert");

                    if (!errors.is(":visible")) {
                        getPartners();
                        createModal.modal("hide");
                    } else {
                        primaryBtn.prop("disabled", false);
                    }
                })
                .fail(function () {
                    toastrStickyError("Sorry, but we encountered an error", "Error!");
                });
        }
    });

    partnersContainer.on("click", ".edit-btn", function () {
        var editBtn = $(this);
        var id = editBtn.data("id");
        var url = editBtn.data("url");

        if (url && id) {
            $.get(url, {"id": id})
                .done(function (data) {
                    editModal.find(".modal-body .form-content").html(data);
                    editModal.modal("show");
                    editModal.find(".modal-footer > .btn-primary").prop("disabled", false);
                })
                .fail(function () {
                    toastrRegularError("Sorry, but we encountered an error", "Error");
                });
        }
    });

    editModal.on("click", ".modal-footer > .btn-primary", function () {
        var primaryBtn = $(this);
        var form = $("#" + primaryBtn.data("formId"));
        if (form.length > 0) {
            primaryBtn.prop("disabled", true);
            $.post(form.prop('action'), form.serialize())
                .done(function (data) {
                    editModal.find(".modal-body .form-content").html(data);
                    var errors = editModal.find(".modal-body .validation-errors-alert");

                    if (!errors.is(":visible")) {
                        getPartners();
                        editModal.modal("hide");
                    } else {
                        primaryBtn.prop("disabled", false);
                    }
                })
                .fail(function () {
                    toastrStickyError("We are unable to save your changes", "Error!");
                });
        }
    });

    function getPartners() {
        var url = partnersContainer.data("url");
        if (url) {
            $.get(url, {})
                .done(function (data) {
                    partnersContainer.html(data);
                });
        }
    }
});

