/**
 * @file
 */

(function ($, Drupal, drupalSettings, window) {
  $(window).on("load", function() {

    var currentPrice = {};
    var socket = io.connect('https://streamer.cryptocompare.com/');
    // Format: {SubscriptionId}~{ExchangeName}~{FromSymbol}~{ToSymbol}
    // Use SubscriptionId 0 for TRADE, 2 for CURRENT, 5 for CURRENTAGG eg use key '5~CCCAGG~BTC~USD' to get aggregated data from the CCCAGG exchange
    // Full Volume Format: 11~{FromSymbol} eg use '11~BTC' to get the full volume of BTC against all coin pairs
    // For aggregate quote updates use CCCAGG ags market.

	var subscription = [];
	jQuery(".view-currency-listing  td.views-field-nothing ").each(function (index) {
      var symbol = jQuery(this).find('.currency_symbol').attr('symbol');
      if (symbol) {
        subscription.push('5~CCCAGG~'+symbol+'~USD');
		subscription.push('11~'+symbol);
      }
    });

    socket.emit('SubAdd', { subs: subscription });
    socket.on("m", function (message) {
        var messageType = message.substring(0, message.indexOf("~"));
        if (messageType == CCC.STATIC.TYPE.CURRENTAGG) {
            dataUnpack(message);
        }
        else if (messageType == CCC.STATIC.TYPE.FULLVOLUME) {
            decorateWithFullVolume(message);
        }
    });

    var dataUnpack = function (message) {
        var data = CCC.CURRENT.unpack(message);

        var from = data['FROMSYMBOL'];
        var to = data['TOSYMBOL'];
        var fsym = CCC.STATIC.CURRENCY.getSymbol(from);
        var tsym = CCC.STATIC.CURRENCY.getSymbol(to);
        var pair = from + to;

        if (!currentPrice.hasOwnProperty(pair)) {
            currentPrice[pair] = {};
        }

        for (var key in data) {
            currentPrice[pair][key] = data[key];
        }

        if (currentPrice[pair]['LASTTRADEID']) {
            currentPrice[pair]['LASTTRADEID'] = parseInt(currentPrice[pair]['LASTTRADEID']).toFixed(0);
        }
        currentPrice[pair]['CHANGE24HOUR'] = CCC.convertValueToDisplay(tsym, (currentPrice[pair]['PRICE'] - currentPrice[pair]['OPEN24HOUR']));
        currentPrice[pair]['CHANGE24HOURPCT'] = ((currentPrice[pair]['PRICE'] - currentPrice[pair]['OPEN24HOUR']) / currentPrice[pair]['OPEN24HOUR'] * 100).toFixed(2) + "%";
		currentPrice[pair]['CHANGE24HOURPCT']= currentPrice[pair]['CHANGE24HOURPCT'] == 'NaN%' ? '0.00%' : currentPrice[pair]['CHANGE24HOURPCT'];
        displayData(currentPrice[pair], from, tsym, fsym);
        displayMKTCAP(from,to);
    };

    var decorateWithFullVolume = function (message) {
        var volData = CCC.FULLVOLUME.unpack(message);
        var from = volData['SYMBOL'];
        var to = 'USD';
        var fsym = CCC.STATIC.CURRENCY.getSymbol(from);
        var tsym = CCC.STATIC.CURRENCY.getSymbol(to);
        var pair = from + to;

        if (!currentPrice.hasOwnProperty(pair)) {
            currentPrice[pair] = {};
        }

        currentPrice[pair]['FULLVOLUMEFROM'] = parseFloat(volData['FULLVOLUME']);
        currentPrice[pair]['FULLVOLUMETO'] = ((currentPrice[pair]['FULLVOLUMEFROM'] - currentPrice[pair]['VOLUME24HOUR']) * currentPrice[pair]['PRICE']) + currentPrice[pair]['VOLUME24HOURTO'];
    };

    var displayData = function (messageToDisplay, from, tsym, fsym) {
        var priceDirection = messageToDisplay.FLAGS;
        var fields = CCC.CURRENT.DISPLAY.FIELDS;

        for (var key in fields) {
            if (messageToDisplay[key]) {
                if (fields[key].Show) {
                    switch (fields[key].Filter) {
                        case 'String':
                            $('[id="' + key + '_' + from + '"]').text(messageToDisplay[key]);
                            break;

                        case 'Number':
                            var symbol = fields[key].Symbol == 'TOSYMBOL' ? tsym : fsym;

							if (key == 'VOLUME24HOURTO') {
							  var OLD_RAW_VOLUME24HOURTO = $('#VOLUME24HOURTO_' + from).attr('data-raw');
							  var NEW_RAW_VOLUME24HOURTO = messageToDisplay[key];
 
							  OLD_RAW_VOLUME24HOURTO = OLD_RAW_VOLUME24HOURTO !== undefined ? OLD_RAW_VOLUME24HOURTO : 0;
							  
							  $('[id="' + key + '_' + from + '"]').removeClass();
							  if (NEW_RAW_VOLUME24HOURTO > OLD_RAW_VOLUME24HOURTO) {
							    $('[id="' + key + '_' + from + '"]').addClass('up');
							  }
							  else if (NEW_RAW_VOLUME24HOURTO < OLD_RAW_VOLUME24HOURTO) {
							    $('[id="' + key + '_' + from + '"]').addClass('down');
							  }

							  $('[id="' + key + '_' + from + '"]').text(CCC.convertValueToDisplay(symbol, messageToDisplay[key]));
							  $('[id="' + key + '_' + from + '"]').attr('data-raw', messageToDisplay[key]);
							} else {
							  $('[id="' + key + '_' + from + '"]').text(CCC.convertValueToDisplay(symbol, messageToDisplay[key]));
							}
                            
                            break;
                    }
                }
            }
        }
		
        $('[id="PRICE_'+from+'"]').removeClass();
        if (priceDirection & 1) {
            $('[id="PRICE_'+from+'"]').addClass("up");
        }
        else if (priceDirection & 2) {
            $('[id="PRICE_'+from+'"]').addClass("down");
        }

        if (messageToDisplay['PRICE'] > messageToDisplay['OPEN24HOUR']) {
            $('[id="CHANGE24HOURPCT_'+from+'"]').removeClass();
            $('[id="CHANGE24HOURPCT_'+from+'"]').addClass("pct-up");
        }
        else if (messageToDisplay['PRICE'] < messageToDisplay['OPEN24HOUR']) {
            $('[id="CHANGE24HOURPCT_'+from+'"]').removeClass();
            $('[id="CHANGE24HOURPCT_'+from+'"]').addClass("pct-down");
        }
    };

    var displayMKTCAP = function (from,to) {
        var PRICE_FULL_URL = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=" + from + "&tsyms=" + to + '&api_key='+drupalSettings.crypto_api_key.api_key;
        $.getJSON(PRICE_FULL_URL, function (data) {
		  if(data['RAW']) {
          var NEW_RAW_MKTCAP = data['RAW'][from][to]['MKTCAP'];
          var DISPLAY_MKTCAP = data['DISPLAY'][from][to]['MKTCAP'];
          var OLD_RAW_MKTCAP = $('#MKCAP_' + from).attr('data-raw');
          OLD_RAW_MKTCAP = OLD_RAW_MKTCAP !== undefined ? OLD_RAW_MKTCAP : 0;
          $('[id="MKCAP_'+from+'"]').attr('data-raw', NEW_RAW_MKTCAP);
		  

          $('[id="MKCAP_'+from+'"]').text(DISPLAY_MKTCAP);
		  $('[id="MKCAP_'+from+'"]').removeClass();
          if (NEW_RAW_MKTCAP > OLD_RAW_MKTCAP) {
            $('[id="MKCAP_'+from+'"]').addClass("up");
          }
          else if (NEW_RAW_MKTCAP < OLD_RAW_MKTCAP) {
            $('[id="MKCAP_'+from+'"]').addClass("down");
          }

          var NEW_RAW_SUPPLY = data['RAW'][from][to]['SUPPLY'];
          var DISPLAY_SUPPLY = data['DISPLAY'][from][to]['SUPPLY'].split(" ").pop().split('.')[0] + " " + from;
          var OLD_RAW_SUPPLY = $('#SUPPLY_' + from).attr('data-raw');
          OLD_RAW_SUPPLY = OLD_RAW_SUPPLY !== undefined ? OLD_RAW_SUPPLY : 0;
          $('#SUPPLY_' + from).attr('data-raw', NEW_RAW_SUPPLY);

          $('#SUPPLY_' + from).text(DISPLAY_SUPPLY);
		  $('#SUPPLY_' + from).removeClass();
          if (NEW_RAW_SUPPLY > OLD_RAW_SUPPLY) {
            $('#SUPPLY_' + from).addClass("up");
          }
          else if (NEW_RAW_SUPPLY < OLD_RAW_SUPPLY) {
            $('#SUPPLY_' + from).addClass("down");
          }
		  }
        });
    };
  });
})(jQuery, Drupal, drupalSettings, window);
