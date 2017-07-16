if (app === undefined) {
    var app = {};
}

app.initDatePickers = function (elem) {
    $(elem ? elem : document).find('.datetime-picker').datetimepicker({
        "pickTime": true,
        "useCurrent": false,
        "minDate": "1\/1\/1900",
        "maxDate": "",
        "showToday": true,
        "disabledDates": [],
        "enabledDates": [],
        "icons": {
            "time": "fa fa-times",
            "date": "fa fa-calendar",
            "up": "fa fa-chevron-up",
            "down": "fa fa-chevron-down"
        },
        "useStrict": false,
        "daysOfWeekDisabled": [],
        "useMinutes": true,
        "minuteStepping": 1,
        "language": "es",
        "sideBySide": true,
        "useSeconds": false
    });

    $(elem ? elem : document).find('.date-picker').datetimepicker({
        "pickTime": false,
        "useCurrent": false,
        "minDate": "1\/1\/1900",
        "maxDate": "",
        "showToday": true,
        "disabledDates": [],
        "enabledDates": [],
        "icons": {
            "time": "glyphicon glyphicon-time",
            "date": "glyphicon glyphicon-calendar",
            "up": "glyphicon glyphicon-chevron-up",
            "down": "glyphicon glyphicon-chevron-down"
        },
        "useStrict": false,
        "daysOfWeekDisabled": [],
        "useMinutes": false,
        "minuteStepping": 1,
        "language": "es",
        "sideBySide": true,
        "useSeconds": false
    });
};