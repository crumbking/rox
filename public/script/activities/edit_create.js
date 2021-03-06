$(function () {

    $('#activity-start-date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });
    $('#activity-end-date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false
    });
    $('#activity-start-date').on("change.datetimepicker", function (e) {
        $('#activity-end-date').datetimepicker('minDate', e.date);
    });
    $('#activity-end-date').on("change.datetimepicker", function (e) {
        $('#activity-start-date').datetimepicker('maxDate', e.date);
    });
});
