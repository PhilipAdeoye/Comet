$(document).ready(function () {

    var editModal = $("#userEditModal");
    var adminsContainer = $("#adminsContainer");
    var usersContainer = $("#usersContainer");
    var userVolunteerRecordModal = $("#userVolunteerRecordModal");
    var messageSelectedPeopleModal = $("#messageSelectedPeopleModal");

    $("#userEditBtn").on("click", getUserEditForm);

    editModal.on("click", ".modal-footer > .btn-primary", submitUserEditForm);
    editModal.on("change", "#partnerId", getTrainingLevelsForPartner);
    if (adminsContainer.length > 0) {
        getAdmins();
        adminsContainer.on("click", ".edit-btn", getUserEditForm);
        adminsContainer.on("click", ".volunteer-record-btn", getUserVolunteerRecord);
    } else if (usersContainer.length > 0) {
        getUsers();
        usersContainer.on("click", ".edit-btn", getUserEditForm);
        usersContainer.on("click", ".volunteer-record-btn", getUserVolunteerRecord);
    }

    function getUserEditForm() {
        var el = $(this);
        var userId = el.data("id");
        var url = el.data("url");

        if (url && userId) {
            $.get(url, {"id": userId})
                .done(function (data) {
                    editModal.find(".modal-body .form-content").html(data);
                    editModal.modal("show");
                    editModal.find(".modal-footer > .btn-primary").prop("disabled", false);
                    editModal.find("#availableToServerPopover").popover();
                })
                .fail(function () {
                    toastrRegularError("Sorry, but we're unable to process your request", "Error!");
                });
        }
    }

    function submitUserEditForm() {
        var primaryBtn = $(this);
        var form = $("#" + primaryBtn.data("formId"));
        if (form.length > 0) {
            primaryBtn.prop("disabled", true);
            $.post(form.prop('action'), form.serialize())
                .done(function (data) {
                    editModal.find(".modal-body .form-content").html(data);
                    var errors = editModal.find(".modal-body .validation-errors-alert");

                    if (!errors.is(":visible")) {
                        if (adminsContainer.length > 0) {
                            getAdmins();
                        } else if (usersContainer.length > 0) {
                            // Commented out because the table is so big loading it on every change
                            // will get tiring
                            //getUsers(); 
                        }
                        toastrRegularSuccess("Your changes have been saved", "Success!");
                        editModal.modal("hide");
                    } else {
                        primaryBtn.prop("disabled", false);
                    }
                })
                .fail(function () {
                    toastrStickyError("We are unable to save your changes", "Error!");
                });
        }
    }

    function getTrainingLevelsForPartner() {
        var trainingLevelSelectContainer = editModal.find("#trainingLevelSelectContainer");
        var url = trainingLevelSelectContainer.data("url");
        var partnerId = $(this).val();

        if (trainingLevelSelectContainer.length && url && partnerId) {
            $.get(url, {
                "partner_id": partnerId
            }).done(function (data) {
                trainingLevelSelectContainer.html(data);
            });
        }
    }

    function getUserVolunteerRecord() {
        var url = $(this).data("url");
        var userId = $(this).data("id");
        if (url && userId && userVolunteerRecordModal.length > 0) {
            $.get(url, {"user_id": userId})
                .done(function (data) {
                    userVolunteerRecordModal.find(".modal-body").html(data);
                    userVolunteerRecordModal.modal("show");
                })
                .fail(function () {
                    toastrRegularError("Sorry, but we're unable to fetch the volunteer record", "Error!");
                });
        }
    }

    function getAdmins() {
        var url = adminsContainer.data("url");
        if (url) {
            $.get(url, {})
                .done(function (data) {
                    adminsContainer.html(data);
                    applyDataTables(adminsContainer.find("table"));
                });
        }
    }

    function getUsers() {
        var url = usersContainer.data("url");
        if (url) {
            $.get(url, {})
                .done(function (data) {
                    usersContainer.html(data);
                    applyDataTables(usersContainer.find("table"));
                });
        }
    }

    function applyDataTables(table) {
        table.find("tfoot th").each(function () {
            if (!$(this).hasClass("no-search")) {
                $(this).html('<input type="text"/>');
            }
        });

        table.find("tfoot th:first input").prop("placeholder", "Search");

        // DataTable
        var dt = table.DataTable({
            dom: 'Brtip',
            buttons: [
                {
                    text: 'Message Users In Table',
                    className: 'btn-primary',
                    action: function (e, dt, node, config) {
                        var indexOfEmailColumnInReverse = -8;

                        var emailList = dt
                            .column(indexOfEmailColumnInReverse, {
                                order: "current",
                                page: "all",
                                search: "applied"
                            })
                            .data()
                            .reduce(function (accumulator, currentValue) {
                                return accumulator + "," + currentValue;
                            });
                            showMessageModal(emailList);
                    }
                }
            ]
        });

        // Apply the search
        dt.columns().every(function () {
            var that = this;

            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
    }

    function showMessageModal(emails) {

        var url = messageSelectedPeopleModal.data("getSendMessageFormUrl");
        if (url) {
            $.get(url, {
            }).done(function (data) {
                messageSelectedPeopleModal.find(".modal-body .form-content").html(data);
                messageSelectedPeopleModal.find("#emails").val(emails);
                messageSelectedPeopleModal.modal("show");
                messageSelectedPeopleModal.find(".modal-footer > .btn-primary").prop("disabled", false);
            }).fail(function () {
                toastrRegularError("Sorry, we encountered an error", "Error");
            });
        }
    }
});


