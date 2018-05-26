
$(document).ready(function () {
    var attendancefiltersContainer = $("#attendancefiltersContainer");
    var attendanceSummaryContainer = $("#attendanceSummaryContainer");

    initializeAttendanceDatePickers();

    attendancefiltersContainer.on("click", "#applyAttendanceFilterBtn", function () {
        var form = attendancefiltersContainer.find("form");
        $.get(form.prop("action"), form.serialize())
            .done(function (data) {
                attendanceSummaryContainer.find(".panel-body").html(data);
                makeVolunteerCountsDataTable();
                makeVolunteerHoursDataTable();
            });
    });

    function initializeAttendanceDatePickers() {
        var hiddenUserAgent = attendancefiltersContainer.find("#hiddenUserAgent");
        var startDatePicker = attendancefiltersContainer.find("#attendanceStartDate");
        var endDatePicker = attendancefiltersContainer.find("#attendanceEndDate");

        var startOfLastYear = new Date("1/1/" + ((new Date()).getFullYear() - 1));
        var endOfLastYear = new Date("12/31/" + ((new Date()).getFullYear() - 1));

        if (hiddenUserAgent.data("isMobile") === "yes") {
            startDatePicker.on("change", function (e) {
                endDatePicker.prop("min", $(this).val());
            });            
            
            endDatePicker.on("change", function (e) {
                startDatePicker.prop("max", $(this).val());
            });
        } else {
            startDatePicker.datetimepicker({
                format: "MM/DD/YYYY",
                defaultDate: moment(startOfLastYear.valueOf())
            });

            endDatePicker.datetimepicker({
                format: "MM/DD/YYYY",
                defaultDate: moment(endOfLastYear.valueOf())
            });

            // Link the two time pickers such that the end date always comes after the start date
            startDatePicker.on("dp.change", function (e) {
                endDatePicker.data("DateTimePicker").minDate(e.date);
            });
            endDatePicker.on("dp.change", function (e) {
                startDatePicker.data("DateTimePicker").maxDate(e.date);
            });
        }
    }
    
    function makeVolunteerCountsDataTable() {
        var volunteerCountsTable = $("#volunteerCountsTable");
        addSearchBoxesToTableHeader(volunteerCountsTable);
        
        var dt = volunteerCountsTable.DataTable({
            dom: "Brtip",
            buttons: [{
                extend: 'excelHtml5',
                text: 'Download as Excel',
                filename: 'volunteer_counts_by_day'
            }]
        });
        addIndividualColumnSearchFunctionalityToDatatable(dt);
    }
    
    function makeVolunteerHoursDataTable() {
        var volunteerHoursTable = $("#volunteerHoursTable");
        addSearchBoxesToTableHeader(volunteerHoursTable);
        
        var dt = volunteerHoursTable.DataTable({
            dom: "Brtip",
            buttons: [{
                extend: 'excelHtml5',
                text: 'Download as Excel',
                filename: 'volunteer_hours'
            }]
        });
        addIndividualColumnSearchFunctionalityToDatatable(dt);
    }
    
    function addSearchBoxesToTableHeader(table) {
        table.find("tfoot th").each(function () {
            if (!$(this).hasClass("no-search")) {
                $(this).html('<input type="text"/>');
            }
        });
    }
    
    function addIndividualColumnSearchFunctionalityToDatatable(dt) {
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
});
