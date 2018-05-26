$(document).ready(function () {
    var rolesContainer = $("#rolesContainer");
    var editModal = $("#roleEditModal");
    var createModal = $("#roleCreateModal");
    var abilitiesEditModal = $("#abilitiesEditModal");

    getRoles();

    $("#addNewRoleBtn").on("click", function () {
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
                        getRoles();
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

    rolesContainer.on("click", ".edit-btn", function () {
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
    
    rolesContainer.on("click", ".edit-abilities-btn", function () {
        var btn = $(this);
        var id = btn.data("id");
        var url = btn.data("url");

        if (url && id) {
            $.get(url, {"id": id})
                .done(function (data) {
                    abilitiesEditModal.find(".modal-footer > .btn-primary").prop("disabled", false).data("id", id);
                    abilitiesEditModal.find(".modal-body .form-content").html(data);
                    abilitiesEditModal.modal("show");
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
                        getRoles();
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
    
    abilitiesEditModal.on("click", ".modal-footer > .btn-primary", function () {
        var primaryBtn = $(this);
        var url = primaryBtn.data("url");
        var id = primaryBtn.data("id");
        var checkedTrainingLevels = [];
        primaryBtn.prop("disabled", true);
        abilitiesEditModal.find(".form-content input[type=checkbox]:checked").each(function () {
            checkedTrainingLevels.push($(this).val());
        });
        if (url && id) {
            $.post(url, {
                id: id,
                training_level_ids: checkedTrainingLevels.join(",")
            }).done(function (data) {
                abilitiesEditModal.modal("hide");
                toastrRegularSuccess("Abilities Upgraded - or Downgraded", "Success!");
            }).fail(function () {
                primaryBtn.prop("disabled", false);
                toastrStickyError("We are unable to save your changes", "Error!");
            });
        }
    });

    function getRoles() {
        var url = rolesContainer.data("url");
        if (url) {
            $.get(url, {})
                .done(function (data) {
                    rolesContainer.html(data);
                });
        }
    }
});

