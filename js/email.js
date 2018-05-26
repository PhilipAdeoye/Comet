$(document).ready(function () {
    var emailsContainer = $("#emailsContainer");
    var editModal = $("#emailEditModal");

    var placeholderVars = {
        placeholders: '',        
        inUseContainerId: "placeholdersInUse",
        notInUseContainerId: "placeholdersNotInUse",
        messageContainerId: "message",
        displayPrefix: "<span class='solid-grey-outline right-gutter'>",
        displaySuffix: "</span>",
        inUseCache: [],
        notInUseCache: []
    };

    getEmails();

    emailsContainer.on("click", ".edit-btn", function () {
        var editBtn = $(this);
        var id = editBtn.data("id");
        var url = editBtn.data("url");

        if (url && id) {
            $.get(url, {"id": id})
                .done(function (data) {
                    editModal.find(".modal-body .form-content").html(data);
                    placeholderVars.placeholders = editBtn.data("placeholders");
                    updatePlaceholders();
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
                        getEmails();
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
    
    editModal.on("keyup", "#message", updatePlaceholders);
    editModal.on("hide.bs.modal", clearPlaceholderCache);

    function getEmails() {
        var url = emailsContainer.data("url");
        if (url) {
            $.get(url, {})
                .done(function (data) {
                    emailsContainer.html(data);
                });
        }
    }
    
    function updatePlaceholders() {
        var placeholders = placeholderVars.placeholders.split(",");
        var text = editModal.find("#" + placeholderVars.messageContainerId).val();
        
        var placeholdersInUse = [];
        var placeholdersNotInUse = [];        
        
        for (var i = 0; i < placeholders.length; i++) {
            if (text.indexOf(placeholders[i]) === -1) {
                placeholdersNotInUse.push(placeholderVars.displayPrefix + placeholders[i] + placeholderVars.displaySuffix);
            }
            else {
                placeholdersInUse.push(placeholderVars.displayPrefix + placeholders[i] + placeholderVars.displaySuffix);
            }
        }
        
        // Only update the DOM if there has been a change to the placeholders
        if (placeholderVars.inUseCache.toString() !== placeholdersInUse.toString()) {
            editModal.find("#" + placeholderVars.inUseContainerId).html(placeholdersInUse.join(""));
            placeholderVars.inUseCache = placeholdersInUse;
        }
        if (placeholderVars.notInUseCache.toString() !== placeholdersNotInUse.toString()) {
            editModal.find("#" + placeholderVars.notInUseContainerId).html(placeholdersNotInUse.join(""));
            placeholderVars.notInUseCache = placeholdersNotInUse;
        }        
    }
    
    function clearPlaceholderCache() {
        placeholderVars.notInUseCache = [];
        placeholderVars.inUseCache = [];
    }
});

