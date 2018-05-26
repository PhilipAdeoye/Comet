/* global Bloodhound */

$(document).ready(function () {

    var dateDetailsContainer = $("#dateDetailsContainer");
    var availableOpportunitiesContainer = $("#availableOpportunitiesContainer");
    var calendarContainer = $("#calendarContainer");
    var commitmentsContainer = $("#commitmentsContainer");
    var rolesContainer = $("#rolesContainer");
    var locationsContainer = $("#locationsContainer");
    var quickSignUpContainer = $("#quickSignUpContainer");

    var confirmSignUpModal = $("#confirmSignUpModal");
    var cancelCommitmentModal = $("#cancelCommitmentModal");
    var unscheduleUserModal = $("#unscheduleUserModal");
    var deleteModal = $("#opportunityDeleteModal");
    var createModal = $("#opportunityCreateModal");
    var userSelectModal = $("#userSelectModal");
    var rescheduleModal = $("#eventRescheduleModal");
    var roleHelpTextModal = $("#roleHelpTextModal");
    var messageSelectedPeopleModal = $("#messageSelectedPeopleModal");

    var allUsersTypeahead = userSelectModal.find("#allUsersTypeahead");
    var autocompleteCommitBtn = userSelectModal.find("#autocompleteCommitBtn");

    // To accomodate a fixed nav header, the body element has a top margin (set in site.css)
    // that we need to account for.
    var bodyMarginTop = parseInt($("body").css("marginTop").replace("px", ""), 10);

    refreshPanels();
    initializeCalendar();

    rolesContainer.on("click", ".select-btn", getSelectedOpportunities);

    availableOpportunitiesContainer.on("click", ".sign-up-btn", showSignUpConfirmationModal);
    dateDetailsContainer.on("click", ".sign-up-btn", showSignUpConfirmationModal);
    quickSignUpContainer.on("click", ".sign-up-btn", showSignUpConfirmationModal);

    availableOpportunitiesContainer.on("click", ".find-volunteer-btn", getPeople);
    dateDetailsContainer.on("click", ".find-volunteer-btn", getPeople);

    dateDetailsContainer.on("click", ".check-all-btn", checkAllOppoCheckboxes);
    dateDetailsContainer.on("click", ".uncheck-all-btn", uncheckAllOppoCheckboxes);
    dateDetailsContainer.on("click", ".message-volunteers-btn", initiateMessageSelectedVolunteers);
    dateDetailsContainer.on("click", ".oppo-checkbox", syncCoordinateCheckbox);

    confirmSignUpModal.on("click", ".modal-footer .btn-primary", signUp);

    commitmentsContainer.on("click", ".cancel-btn", function () {
        var id = $(this).data("id");
        if (id && cancelCommitmentModal.length) {
            cancelCommitmentModal.find(".modal-footer .btn-warning").prop("disabled", false).data("id", id);
            cancelCommitmentModal.modal("show");
        }
    });

    cancelCommitmentModal.on("click", ".modal-footer .btn-warning", function () {
        var warningBtn = $(this);
        var url = warningBtn.data("url");
        var id = warningBtn.data("id");
        if (url && id) {
            warningBtn.prop("disabled", true);
            $.post(url, {
                "id": id
            }).done(function (errors) {
                cancelCommitmentModal.modal("hide");
                if (errors) {
                    showErrors("", errors, "Sorry 'bout that...");
                } else {
                    toastrRegularSuccess("You've successfully unscheduled the opportunity!", "Success!");
                    refreshPanels();
                }
            }).fail(function () {
                toastrRegularError("Sorry, but we encountered an error. Try reloading the page", "Error!");
            });
        }
    });

    dateDetailsContainer.on("click", ".cancel-btn", function () {
        var id = $(this).data("id");
        var userId = $(this).data("userId");
        if (id && userId && unscheduleUserModal.length) {
            unscheduleUserModal.find(".modal-footer .btn-warning").data("id", id);
            unscheduleUserModal.find(".modal-footer .btn-warning").prop("disabled", false).data("userId", userId);
            unscheduleUserModal.modal("show");
        }
    });

    unscheduleUserModal.on("click", ".modal-footer .btn-warning", function () {
        var warningBtn = $(this);
        var url = warningBtn.data("url");
        var id = warningBtn.data("id");
        var userId = warningBtn.data("userId");
        if (url && id && userId) {
            warningBtn.prop("disabled", true);
            $.post(url, {
                "id": id,
                "user_id": userId
            }).done(function (errors) {
                unscheduleUserModal.modal("hide");
                if (errors) {
                    showErrors("", errors, "Sorry 'bout that...");
                } else {
                    toastrRegularSuccess("You've successfully unscheduled the volunteer!", "Success!");
                    refreshPanels();
                }
            }).fail(function () {
                toastrRegularError("Sorry, but we encountered an error", "Error!");
            });
        }
    });

    userSelectModal.on("click", ".commit-volunteer-btn", commitVolunteer);
    autocompleteCommitBtn.on("click", commitVolunteer);

    dateDetailsContainer.on("click", ".delete-btn", function () {
        var id = $(this).data("id");
        if (id && deleteModal.length) {
            deleteModal.find(".modal-footer .btn-warning").prop("disabled", false).data("id", id);
            deleteModal.modal("show");
        }
    });

    deleteModal.on("click", ".modal-footer .btn-warning", function () {
        var warningBtn = $(this);
        var url = warningBtn.data("url");
        var id = warningBtn.data("id");
        if (url && id) {
            warningBtn.prop("disabled", true);
            $.post(url, {
                "id": id
            }).done(function (errors) {
                deleteModal.modal("hide");
                if (errors) {
                    showErrors("", errors, "Sorry 'bout that...");
                } else {
                    toastrRegularSuccess("You've successfully deleted the opportunity!", "Success!");
                    refreshPanels();
                }
            }).fail(function () {
                toastrRegularError("Sorry, but we encountered an error", "Error!");
            });
        }
    });

    $("#addNewOpportunityBtn").on("click", function () {
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

    createModal.on("click", ".oppo-box .delete-btn", function () {
        if (createModal.find(".oppo-box").length > 1) {
            $(this).closest(".oppo-box").remove();

            updateOpportunityCount();
        }
    });

    createModal.on("click", ".add-more-btn", function () {
        addOneMoreOpportunity();
    });

    createModal.on("click", ".modal-footer > .btn-primary", function () {
        var primaryBtn = $(this);
        var form = $("#" + primaryBtn.data("formId"));
        if (form.length > 0) {
            primaryBtn.prop("disabled", true);
            $.post(form.prop('action'), form.serialize())
                .done(function (data) {
                    createModal.find(".modal-body .form-content").html(data);
                    var errors = createModal.find(".modal-body .field-validation-error");

                    if (!errors.is(":visible")) {
                        refreshPanels();
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

    createModal.on("change", ".oppo-box .num-spots", updateOpportunityCount);

    $("#rescheduleEventBtn").on("click", function () {
        var url = $(this).data("url");
        $.get(url, {
        }).done(function (data) {
            rescheduleModal.find(".modal-body .form-content").html(data);
            rescheduleModal.modal("show");
            rescheduleModal.find(".modal-footer > .btn-primary").prop("disabled", false);
        }).fail(function () {
            toastrRegularError("Sorry, we encountered an error", "Error");
        });
    });

    rescheduleModal.on("click", ".modal-footer > .btn-primary", function () {
        var primaryBtn = $(this);
        var form = $("#" + primaryBtn.data("formId"));
        if (form.length > 0) {
            primaryBtn.prop("disabled", true);
            $.post(form.prop('action'), form.serialize())
                .done(function (data) {
                    rescheduleModal.find(".modal-body .form-content").html(data);
                    var errors = rescheduleModal.find(".modal-body .validation-errors-alert");

                    if (!errors.is(":visible")) {
                        refreshPanels();
                        rescheduleModal.modal("hide");
                        toastrRegularSuccess("The event has been rescheduled", "Success!");
                    } else {
                        primaryBtn.prop("disabled", false);
                    }
                })
                .fail(function () {
                    toastrStickyError("Sorry, but we encountered an error", "Error!");
                });
        }
    });

    var roleHelpTextCache = {};
    $(document).on("click", ".role-description-highlight", function () {
        var roleId = $(this).data("roleId");
        var url = $(this).data("url");

        if (roleId) {
            var roleDetails = roleHelpTextCache[roleId];
            if (roleDetails) {
                showRoleDetails(roleDetails);
            } else if (url) {
                $.get(url, {id: roleId})
                    .done(function (data) {
                        data = JSON.parse(data);
                        if (data && data.id) {
                            showRoleDetails(data);
                            roleHelpTextCache[roleId] = data;
                        }
                    });
            }
        }
        function showRoleDetails(details) {
            roleHelpTextModal.find(".modal-title").html(details.description);

            var moreInfo = "";
            if (details.help_text) {
                moreInfo += "<p>" + details.help_text + "</p>";
            }
            if (details.email_text) {
                moreInfo += "<p>" + details.email_text + "</p>";
            }
            if (moreInfo === "") {
                moreInfo = "<p><i>We don't really have any information about this role. Ask your clinic manager</i></p>";
            }

            roleHelpTextModal.find(".modal-body").html(moreInfo);
            roleHelpTextModal.modal("show");
        }
    });

    if (userSelectModal.length > 0 && userSelectModal.data("allUsers")) {

        var userTypeaheadSuggestions = (function () {

            // constructs the typeahead suggestion engine
            var suggestionEngine = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: $.map(userSelectModal.data("allUsers"), function (user) {
                    return {
                        id: user.id,
                        text: user.text
                    };
                })
            });

            suggestionEngine.initialize();

            return suggestionEngine.ttAdapter();
        })();

        allUsersTypeahead.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'userSuggest',
            displayKey: 'text',
            valueKey: 'id',
            source: userTypeaheadSuggestions
        });

        allUsersTypeahead
            .on("typeahead:selected typeahead:autocompleted", function (e, datum) {
                autocompleteCommitBtn.data("userId", datum.id);
                autocompleteCommitBtn.prop("disabled", false);
            })
            .on("keyup", function () {
                if (!$(this).val()) {
                    autocompleteCommitBtn.data("userId", "");
                    autocompleteCommitBtn.prop("disabled", true);
                }
            });
    }

    function initializeCalendar() {
        if (calendarContainer.length > 0) {
            var url = calendarContainer.data("url");
            if (url) {
                $.get(url, {})
                    .done(function (data) {
                        calendarContainer.find(".panel-body").html(data);
                        var oppoCalendar = calendarContainer.find("#oppoCalendar");
                        if (oppoCalendar.length > 0 && oppoCalendar.data("dates").length > 0) {
                            oppoCalendar.datetimepicker({
                                format: "MM/DD/YYYY",
                                inline: true,
                                enabledDates: oppoCalendar.data("dates")
                            });
                            oppoCalendar.on("dp.change", function () {
                                getDatedOpportunities(oppoCalendar.val(), oppoCalendar.data("url"));
                            });
                        }
                    });
            }
        }
    }

    function addOneMoreOpportunity() {

        // Clone the last oppo-box without data or events
        var oppoClone = createModal.find(".oppo-box").last().clone();
        var hiddenNameIndex = oppoClone.find(".name-index");
        var nameIndex = parseInt(hiddenNameIndex.val(), 10);
        var newIndex = nameIndex + 1;
        hiddenNameIndex.val(newIndex);

        // For each child that has a name starting with oppos        
        oppoClone.find("[name^=oppos]").each(function () {
            var name = $(this).prop("name");
            // Update its name by replacing the old index with the new. e.g. oppos[0][date] becomes oppos[1][date]
            $(this).prop("name", name.replace(nameIndex, newIndex));
        });

        // Update the time pickers
        var startTimePicker = oppoClone.find(".start-time");
        var endTimePicker = oppoClone.find(".end-time");

        if (createModal.data("isMobile") === "yes") {
            startTimePicker.on("change", function (e) {
                endTimePicker.prop("min", $(this).val());
            });
            endTimePicker.on("change", function (e) {
                startTimePicker.prop("max", $(this).val());
            });
        } else {
            startTimePicker.datetimepicker({
                format: "H:mm",
                defaultDate: moment("9 00", "H mm")
            });

            endTimePicker.datetimepicker({
                format: "H:mm",
                defaultDate: moment("14 00", "H mm")
            });

            // Link the two time pickers such that the end time always comes after the start time
            startTimePicker.on("dp.change", function (e) {
                endTimePicker.data("DateTimePicker").minDate(e.date);
            });
            endTimePicker.on("dp.change", function (e) {
                startTimePicker.data("DateTimePicker").maxDate(e.date);
            });
        }

        // Insert the clone into the DOM
        oppoClone.appendTo(createModal.find(".modal-body .form-content"));

        // Scroll up for the user
        var modalBody = createModal.find(".modal-body");
        modalBody.animate({"scrollTop": modalBody.prop("scrollHeight") + parseInt(oppoClone.css("height"), 10)});

        updateOpportunityCount();
    }

    function updateOpportunityCount() {
        var totalOppoCount = 0;
        createModal.find(".oppo-box .num-spots").each(function () {
            var numSpots = parseInt($(this).val(), 10);
            if (!isNaN(numSpots)) {
                totalOppoCount += numSpots;
            }
        });

        createModal.find(".modal-title").html("Create Opportunities (" + totalOppoCount + ")");
    }

    function commitVolunteer() {
        var url = $(this).data("url");
        var opportunityId = $(this).data("opportunityId");
        var userId = $(this).data("userId");
        if (url && userId && opportunityId) {

            // Disable all of the commit buttons
            autocompleteCommitBtn.prop("disabled", true);
            $(".commit-volunteer-btn").prop('disabled', true);

            $.post(url, {
                "user_id": userId,
                "id": opportunityId
            }).done(function (errors) {
                if (errors) {
                    showErrors("", errors, "Sorry 'bout that...");
                } else {
                    toastrRegularSuccess("You've successfully scheduled the volunteer!", "Success!");
                    userSelectModal.modal("hide");

                    autocompleteCommitBtn.data("userId", "");
                    autocompleteCommitBtn.data("opportunityId", "");
                    allUsersTypeahead.val("");

                    refreshPanels();
                }
            }).fail(function () {
                toastrRegularError("Sorry, but we're unable to schedule the volunteer", "Error!");
            });
        }

    }

    function signUp() {
        var url = $(this).data("url");
        var id = $(this).data("id");
        if (url && id) {
            $(this).prop("disabled", true);
            $.post(url, {
                "id": id
            }).done(function (errors) {
                confirmSignUpModal.modal("hide");
                if (errors) {
                    showErrors("", errors);
                } else {
                    toastrRegularSuccess("You successfully signed up!", "Success!");
                    refreshPanels();
                }
            }).fail(function () {
                toastrRegularError("An error occurred when scheduling your opportunity", "Error!");
            });
        }
    }

    function showSignUpConfirmationModal() {
        var id = $(this).data("id");
        if (id && confirmSignUpModal.length) {
            confirmSignUpModal.find(".modal-footer .btn-primary").prop("disabled", false).data("id", id);
            confirmSignUpModal.modal("show");
        }
    }

    function getPeople() {
        var url = $(this).data("url");
        var id = $(this).data("id");
        if (url && id) {
            $.get(url, {"id": id})
                .done(function (data) {
                    userSelectModal.find(".modal-body .form-content").html(data);
                    autocompleteCommitBtn.data("opportunityId", id);
                    userSelectModal.modal("show");
                });
        }
    }

    function getDatedOpportunities(date, url) {

        if (url && date) {
            dateDetailsContainer.data("date", date);
            dateDetailsContainer.data("url", url);

            $("html, body").animate({scrollTop: dateDetailsContainer.position().top - bodyMarginTop},
                600,
                "swing",
                function () {
                    $.get(url, {"date": date})
                        .done(function (data) {
                            dateDetailsContainer.find(".panel-title span.detail-text").html(date + " - Volunteer Opportunities");
                            dateDetailsContainer.find(".panel-body").html(data);
                            dateDetailsContainer.find(".panel-group").show(250);
                        });
                });
        }
    }

    function getSelectedOpportunities() {
        var url = $(this).data("url");
        var id = $(this).data("id");
        if (url && id) {
            $("html, body").animate({scrollTop: availableOpportunitiesContainer.position().top - bodyMarginTop},
                600,
                "swing",
                function () {
                    $.get(url, {"id": id})
                        .done(function (data) {
                            availableOpportunitiesContainer.find(".panel-body").html(data);
                            availableOpportunitiesContainer.find(".panel-group").show(250);
                        });
                });


        }
    }

    function refreshDatedOpportunities() {
        var url = dateDetailsContainer.data("url");
        var date = dateDetailsContainer.data("date");
        if (url && date) {
            $.get(url, {"date": date})
                .done(function (data) {
                    dateDetailsContainer.find(".panel-body").html(data);
                });
        }
    }

    function refreshPanels() {
        availableOpportunitiesContainer.find(".panel-group").hide(250);
        getUsersCommitments();
        getOpportunitiesByRoles();
        getQuickSignUpOpportunities();
        refreshDatedOpportunities();
    }

    function getUsersCommitments() {
        if (commitmentsContainer.length > 0) {
            var url = commitmentsContainer.data("url");
            if (url) {
                $.get(url, {})
                    .done(function (data) {
                        commitmentsContainer.find(".panel-body").html(data);
                    });
            }
        }
    }

    function getOpportunitiesByRoles() {
        if (rolesContainer.length > 0) {
            var url = rolesContainer.data('url');
            if (url) {
                $.get(url, {})
                    .done(function (data) {
                        rolesContainer.find(".panel-body").html(data);
                    });
            }
        }
    }

    function getQuickSignUpOpportunities() {
        if (quickSignUpContainer.length > 0) {
            var url = quickSignUpContainer.data("url");
            if (url) {
                $.get(url, {})
                    .done(function (data) {
                        quickSignUpContainer.find(".panel-body").html(data);
                    });
            }
        }
    }

    function checkAllOppoCheckboxes() {
        dateDetailsContainer.find(".oppo-checkbox").not(":disabled").prop("checked", true);
    }

    function uncheckAllOppoCheckboxes() {
        dateDetailsContainer.find(".oppo-checkbox").not(":disabled").prop("checked", false);
    }

    function syncCoordinateCheckbox() {
        var userId = $(this).data("userId");
        var status = $(this).prop("checked");
        dateDetailsContainer.find("[data-user-id=" + userId + "].oppo-checkbox").prop("checked", status);
    }

    function initiateMessageSelectedVolunteers() {
        
        var checkedBoxes = dateDetailsContainer.find(".oppo-checkbox:checked");
        if (checkedBoxes.length < 1) {
            toastrRegularInfo("You haven't selected any volunteers. Use the checkboxes to select some");
        } else {
            var kvp = {};

            for (var i = 0; i < checkedBoxes.length; i++) {
                var that = $(checkedBoxes[i]);
                kvp[that.data("userId")] = that.data("email");
            }
            var emails = [];
            for (var key in kvp) {
                if (kvp.hasOwnProperty(key)) {
                    emails.push(kvp[key]);
                }
            }

            var url = messageSelectedPeopleModal.data("getSendMessageFormUrl");
            if (url) {
                $.get(url, {
                }).done(function (data) {
                    messageSelectedPeopleModal.find(".modal-body .form-content").html(data);
                    messageSelectedPeopleModal.find("#emails").val(emails.join(","));
                    messageSelectedPeopleModal.modal("show");
                    messageSelectedPeopleModal.find(".modal-footer > .btn-primary").prop("disabled", false);
                }).fail(function () {
                    toastrRegularError("Sorry, we encountered an error", "Error");
                });
            }
        }
    }
});
