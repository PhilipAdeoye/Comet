
$(document).ready(function () {
    var filtersContainer = $("#filtersContainer");
    var rollCallContainer = $("#rollCallContainer");

    initializeDatePicker();

    filtersContainer.on("click", "#applyFiltersBtn", function () {
        var form = filtersContainer.find("form");
        $.get(form.prop("action"), form.serialize())
            .done(function (data) {
                rollCallContainer.find(".panel-body").html(data);
            });
    });

    rollCallContainer.on("click", ".mark-all-btn", function () {
        var captureForm = rollCallContainer.find("#captureForm");
        captureForm.find(".capture-btn").find("span")
            .removeClass("glyphicon-remove absent glyphicon-question-sign unmarked")
            .addClass("glyphicon-ok present");

        captureForm.find(".hidden-status").val("1");
    });

    rollCallContainer.on("click", ".unmark-all-btn", function () {
        var captureForm = rollCallContainer.find("#captureForm");
        captureForm.find(".capture-btn").find("span")
            .removeClass("glyphicon-remove absent glyphicon-ok present")
            .addClass("glyphicon-question-sign unmarked");

        captureForm.find(".hidden-status").val("");
    });
    
    rollCallContainer.on("click", ".save-btn", function () {
        var captureForm = rollCallContainer.find("#captureForm");
        $.post(captureForm.prop("action"), captureForm.serialize())
            .done(function () {
                toastrRegularSuccess("Your changes have been saved", "Success!");
            })
            .fail(function () {
                toastrRegularError("An error occurred. Try reloading the page and try again", "Error");
            });
    });

    rollCallContainer.on("click", ".capture-btn", function () {
        var btn = $(this);
        var indicator = btn.find("span.glyphicon");
        if (indicator.hasClass("glyphicon-question-sign") || indicator.hasClass("unmarked")) {
            indicator.removeClass("glyphicon-question-sign unmarked")
                .addClass("glyphicon-ok present");
            btn.siblings(".hidden-status").val("1");
            
        } else if (indicator.hasClass("glyphicon-ok") || indicator.hasClass("present")) {
            indicator.removeClass("glyphicon-ok present")
                .addClass("glyphicon-remove absent");
            btn.siblings(".hidden-status").val("0");
            
        } else if (indicator.hasClass("glyphicon-remove") || indicator.hasClass("absent")) {
            indicator.removeClass("glyphicon-remove absent")
                .addClass("glyphicon-question-sign unmarked");
            btn.siblings(".hidden-status").val("");
        }
    });

    function initializeDatePicker() {
        var dateFormGroup = filtersContainer.find("#dateFormGroup");
        var oppoDate = dateFormGroup.find("#oppoDate");

        if (dateFormGroup.data("isMobile") === "no") {
            oppoDate.datetimepicker({
                format: "MM/DD/YYYY",
                inline: true
            });
        }
    }
});
