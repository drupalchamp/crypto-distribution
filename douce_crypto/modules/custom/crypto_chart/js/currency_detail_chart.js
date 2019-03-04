/**
 * @file
 */

(function ($, Drupal, drupalSettings, window) {

    $(document).ready(function () {
      var fsym = $('#currency_detail_chart').attr('symbol');
      var tsym = "USD";
      var dataUrl = 'https://min-api.cryptocompare.com/data/histoday?fsym=' + fsym + '&tsym=' + tsym + '&limit=2000&api_key='+drupalSettings.crypto_api_key.api_key;

      $.getJSON(dataUrl, function (data) {
        var seriesOptions = [], arr = [], close = [], seriesCounter = 0;

        $.each(data['Data'], function (kt, vt) {
          close.push([Number(vt['time'])*1000, vt['close']]);
        });

        $.each(close, function (key, value) {
          seriesOptions[0] = {
            name: 'Price',
            data: close,
            visible: true
          };

          seriesCounter += 1;

          if (seriesCounter === close.length) {
            Highcharts.stockChart('currency_detail_chart', {
              chart: {
                events: {
                  redraw: function () {
                    setTimeout(function () {
                    }, 1000);
                  }
                }
              },

              yAxis: {
                labels: {
                    formatter: function () {
                        return (this.value > 0 ? ' + ' : '') + this.value;
                    },
                },
                plotLines: [{
                    value: 0,
                    width: 0,
                    color: 'silver'
                }],
              },

              series: seriesOptions,
            });
          }
       });
     });
  });

})(jQuery, Drupal, drupalSettings, window);
