/* global toastr */

// DataTables default settings
$.extend($.fn.dataTable.defaults, {
    "columnDefs": [{
            "targets": "no-sort",
            "orderable": false
        }, {
            "targets": "no-search",
            "searchable": false
        }]
});

// Default options for the toastr plugin
toastr.options.closeButton = true;

var toastrTimeoutOptions = {
    sticky: 0,
    regular: 5000
};

$(function () {

    // By default, when the close button on bootstrap alerts is clicked,
    // the alert is removed from the DOM and cannot be shown again.
    // This hides it instead so that it can be shown again

    $(document).on("click", "[data-hide]", function () {

        // Hide the closest parent with the class in $(this)'s 
        // data-hide attribute
        $(this).closest("." + $(this).attr("data-hide")).hide();
    });

    $.ajaxSetup({
        statusCode: {
            401: function () {
                window.setTimeout(function () {
                    window.location.href = '/welcome';
                }, 2000);
            },
            403: function () {
                window.setTimeout(function () {
                    // basically just reload the page
                    window.location.href = window.location.href;
                }, 2000);
            }
            //,
//            500: function () {
//                window.setTimeout(function () {
//                    window.location.href = '/error/error';
//                }, 2000);
//            }
        }
    });

    var uiActivityIndicator = $("#uiActivityIndicator");
    var csrf_token = $("[name$=_csrf_token]").val();
    var csrf_token_name = $("[name$=_csrf_token]").prop("name");

    $(document)
        .ajaxStart(function () {
            uiActivityIndicator.show();
        })
        .ajaxStop(function () {
            uiActivityIndicator.fadeOut(200);
        })
        .ajaxSend(function (event, request, settings) {
            if (settings.type === "POST"
                && typeof csrf_token !== "undefined"
                && typeof settings.data !== "object") { // exempt ajax calls using the FormData api                

                // If the post request doesn't already have the csrf_token
                if (settings.data.indexOf(csrf_token_name) === -1) {
                    if (settings.data.length > 0) {
                        settings.data += "&"+ csrf_token_name +"=" + encodeURIComponent(csrf_token);
                    } else {
                        settings.data = csrf_token_name + "=" + encodeURIComponent(csrf_token);
                    }
                }
            }
        });

    
    $(document).on("shown.bs.modal", ".modal", function () {
        
        // Right after a modal is shown, adjust the max-height of the .modal-body - the content - so 
        // that the header text as well as the action buttons at the bottom (if any) can be seen 
        // at the same time
        setModalBodyMaxHeight($(this));
    });
        
    $(window).on("resize", function () {
        // If there are any open modal, resize it's .modal-body
        setModalBodyMaxHeight($(".modal:visible"));
    });
    
    
    // Enable toggling between admin mode and regular user mode (for admins only)
    $("#toggleAdminViewMode").on("click", function() {
        $.get($(this).data("url"), {            
        }).done(function() { 
            window.location.reload(); 
        });
    });
    
    // To let the user know that a panel group is collapsible
    function toggleCollapseIndicator() {
        $(this).find(".collapse-indicator").toggleClass("glyphicon-chevron-down glyphicon-chevron-left");
    }
    $(".panel-group").on("hidden.bs.collapse", toggleCollapseIndicator);
    $(".panel-group").on("shown.bs.collapse", toggleCollapseIndicator);
});

// Function to encapsulate what seems to be the most common success use-case 
function toastrRegularSuccess(message, title) {
    toastr.options.timeOut = toastrTimeoutOptions.regular;
    toastr.clear();
    toastr.success(message, title);
}

// Function to encapsulate what seems to be the most common error use-case
function toastrStickyError(message, title) {
    toastr.options.timeOut = toastrTimeoutOptions.sticky;
    toastr.error(message, title);
}

function toastrRegularError(message, title) {
    toastr.options.timeOut = toastrTimeoutOptions.regular;
    toastr.error(message, title);
}

function toastrRegularInfo(message, title) {
    toastr.options.timeOut = toastrTimeoutOptions.regular;
    toastr.info(message, title);
}

function toastrRegularWarning(message, title) {
    toastr.options.timeOut = toastrTimeoutOptions.regular;
    toastr.warning(message, title);
}

function getFileExtension(filePath) {
    return filePath.substring(filePath.lastIndexOf(".")).toLowerCase();
}

function showErrors(elementId, errors, title) {

    var errorContainer = $("#" + elementId);
    var errorUL = errorContainer.length > 0 && errorContainer.children("ul");

    if (errorUL.length > 0) {
        errorUL.empty();
        errorUL.append(errors);

        if (title) {
            var titleEl = errorContainer.children("strong");
            if (titleEl.length > 0) {
                titleEl.html(title);
            }
        }

        errorContainer.show();
    } else {
        //resort to using toastr
        errors = "<ul>" + errors + "</ul>";

        toastr.options.timeOut = toastrTimeoutOptions.sticky;
        var defaultPosition = toastr.options.positionClass;
        toastr.options.positionClass = "toast-top-full-width";

        toastr.error(errors, title ? title : "Please correct the following errors");

        toastr.options.positionClass = defaultPosition;
    }
}

function setModalBodyMaxHeight(jqueryEl) {
    var modal = jqueryEl;

    var windowHeight = $(window).height();
    var modalHeaderHeight = parseInt(modal.find(".modal-header").css("height"), 10);
    var modalFooterHeight = parseInt(modal.find(".modal-footer").css("height"), 10);

    // modal-dialog margin = 10px, and modal-content border = 1px
    var otherFixedAllowances = 22;
    var modalBodyMaxHeight = windowHeight - (modalHeaderHeight + modalFooterHeight + otherFixedAllowances);

    modal.find(".modal-body").css("max-height", modalBodyMaxHeight);
}

