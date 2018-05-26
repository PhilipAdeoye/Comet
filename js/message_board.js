$(document).ready(function () {

    var messageBoardContainer = $("#messageBoardContainer");
    var createModal = $("#messageCreateModal");
    var editModal = $("#messageEditModal");

    messageBoardContainer.on("show.bs.collapse", function (e) {
        var target = $(e.target);
        var url = target.data("url");
        var year = target.data("year");
        var contentDestination = target.find(".message-panel-content");

        if (url && year && $.trim(contentDestination.html()) === '') {
            $.get(url, {
                "year": year
            })
                .done(function (data) {
                    contentDestination.html(data);
                });
        }
    });

    $("#addNewMessageBtn").on("click", function () {
        var url = $(this).data("url");
        $.get(url, {
        }).done(function (data) {
            createModal.find(".modal-body .form-content").html(data);
            createModal.modal("show");
            createModal.find(".modal-footer > .btn-primary").prop("disabled", false);
        }).fail(function () {
            toastrRegularError("Sorry, message creation encountered an error", "Error");
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
                        createModal.modal("hide");
                        window.setTimeout(function () {
                            location.reload();
                        }, 250);
                    } else {
                        primaryBtn.prop("disabled", false);
                    }
                })
                .fail(function () {
                    toastrStickyError("We are unable to post your message", "Error!");
                });
        }
    });


    messageBoardContainer.on("click", ".edit-btn", function () {
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
                    toastrRegularError("Sorry, but message editing encountered an error", "Error");
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
                        editModal.modal("hide");
                        window.setTimeout(function () {
                            location.reload();
                        }, 250);
                    } else {
                        primaryBtn.prop("disabled", false);
                    }
                })
                .fail(function () {
                    toastrStickyError("We are unable to save your changes", "Error!");
                });
        }
    });


});

