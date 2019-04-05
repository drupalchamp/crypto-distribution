/**
 * @file
 * Contains JS for homepage graph listing.
 */

(function ($, Drupal, drupalSettings, window) {
  setTimeout(function () {
    $(".view-currency-listing td.chart").each(function () {
      if ($(this).find(".price_graph").is(':empty')) {
        var container = $(this).find(".price_graph").attr('id');
        var fsym = $(this).find(".price_graph").attr('symbol');
        var tsym = "USD";
        var currentSubs;
        var currentSubsText = "";
        var dataUrl = 'https://min-api.cryptocompare.com/data/histoday?fsym=' + fsym + '&tsym=' + tsym + '&limit=50&api_key=' + drupalSettings.crypto_api_key.api_key;

        $.getJSON(dataUrl, function (data) {
          if (data.Response != "Error") {
            var seriesOptions = [], arr = [], close = [], seriesCounter = 0;

            var color = '#f5f5f5';
            var green = '#3d9400';
            var red = '#A11B0A';
            var old_val = 0;

            jQuery.each(data['Data'], function (kt, vt) {
              if (old_val < vt['close']) {
                color = green;
              }
              else if (old_val > vt['close']) {
                color = red;
              }
              old_val = vt['close'];
              var time = Number(vt['time']);
              var value = Number(vt['close']);
              close[kt] = {
                date: time,
                y: value,
                segmentColor: color
              };
            });

            jQuery.each(close, function (key, value) {

            seriesOptions[0] = {
              type: 'coloredline',
              name: 'Price',
              data: close,
              visible: true,
            };

            seriesCounter += 1;

            if (seriesCounter === close.length) {

            Highcharts.setOptions({
              lang:{
                rangeSelectorZoom: ''
              }
            });

            Highcharts.stockChart(container, {
              chart: {
                width: 175,
                height: 36,
                spacing:[0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                backgroundColor: 'transparent'
              },

              title : {
                text : null
              },

              tooltip:   {
                pointFormat: '<b>Price</b><br/>${point.y}',
                style: {
                  fontSize: '10px',
                },
                padding: 2,
                align : 'center',
                headerFormat: '',
                crosshairs: [false]
              },

              legend:   { enabled: false },
              credits:   { enabled: false },
              scrollbar: { enabled: false },
              navigator: { enabled: false },
              exporting: { enabled: false },
              rangeSelector: { enabled: false },
              xAxis: { visible: false },

              yAxis: {
                labels: {
                    formatter: function () {
                    },
                },
                plotLines: [{
                    value: 0,
                    width: 0,
                    color: 'silver'
                }],
              },

              plotOptions: {
                series: {
                    lineWidth: 1,
                },
              },

              series: seriesOptions
            });
          }
        });
       }
    });
    }
  });
}, 2000);

})(jQuery, Drupal, drupalSettings, window);
