/**
 * @file
 * Global jQuery, Drupal, drupalSettings, window
 * jslint white:true, multivar, this, browser:true.
 */

(function ($) {

  /* SCRIPT START */

  $('#historyfilter').daterangepicker({
     // autoUpdateInput: false,.
     ranges: {
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'Last 3 months': [moment().subtract(3, 'month'), moment()],
       'Last 12 months': [ moment().subtract(12, 'month'), moment()],
       'Year to Date': [ moment().startOf('year'), moment()]
     },
     datepickerOptions: {
       numberOfMonths : 2
     },
  });

  $('#historyfilter').on('apply.daterangepicker', function (ev, picker) {
    $(this).find('span').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    var start = picker.startDate;
    var end = picker.endDate;
    window.location.href = location.origin + location.pathname + '?start=' + start + '&end=' + end;
  });

  /* SCRIPT END */

})(jQuery);
