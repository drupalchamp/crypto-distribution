(function($, Drupal, drupalSettings, window) {
  $(window).on("load", function() {

	var currentPrice = {};
	var coin_symbol = $(".currency_detail").find( ".coin_symbol" ).attr('symbol');
	var socket = io.connect('https://streamer.cryptocompare.com/');
	//Format: {SubscriptionId}~{ExchangeName}~{FromSymbol}~{ToSymbol}
	//Use SubscriptionId 0 for TRADE, 2 for CURRENT, 5 for CURRENTAGG eg use key '5~CCCAGG~BTC~USD' to get aggregated data from the CCCAGG exchange 
	//Full Volume Format: 11~{FromSymbol} eg use '11~BTC' to get the full volume of BTC against all coin pairs
	//For aggregate quote updates use CCCAGG ags market
	//var subscription = ['5~CCCAGG~'+ coin_symbol +'~USD','11~'+ coin_symbol + ''];
	var subscription = ['5~CCCAGG~'+ coin_symbol +'~USD','11~'+ coin_symbol];
	socket.emit('SubAdd', { subs: subscription });
	socket.on("m", function(message) {
		var messageType = message.substring(0, message.indexOf("~"));
		if (messageType == CCC.STATIC.TYPE.CURRENTAGG) {
			dataUnpack(message);
		}
		else if (messageType == CCC.STATIC.TYPE.FULLVOLUME) {
			decorateWithFullVolume(message);
		}
	});

	var dataUnpack = function(message) {
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
		displayData(currentPrice[pair], from, tsym, fsym);
		displayMKTCAP(from,to);
		convertPRICE(from);
	};

	var decorateWithFullVolume = function(message) {
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
		displayData(currentPrice[pair], from, tsym, fsym);
	};

	var displayData = function(messageToDisplay, from, tsym, fsym) {
		var priceDirection = messageToDisplay.FLAGS;
		var fields = CCC.CURRENT.DISPLAY.FIELDS;

		for (var key in fields) {
			if (messageToDisplay[key]) {
				if (fields[key].Show) {
					switch (fields[key].Filter) {
						case 'String':
							$('#' + key + '_' + from).text(messageToDisplay[key]);
							break;
						case 'Number':
							var symbol = fields[key].Symbol == 'TOSYMBOL' ? tsym : fsym;
							$('#' + key + '_' + from).text(CCC.convertValueToDisplay(symbol, messageToDisplay[key]))
							break;
					}
				}
			}
		}

		$('#PRICE_' + from).removeClass();
		if (priceDirection & 1) {
			$('#PRICE_' + from).addClass("up");
		}
		else if (priceDirection & 2) {
			$('#PRICE_' + from).addClass("down");
		}

		if (messageToDisplay['PRICE'] > messageToDisplay['OPEN24HOUR']) {
			$('#CHANGE24HOURPCT_' + from).removeClass();
			$('#CHANGE24HOURPCT_' + from).addClass("pct-up");
		}
		else if (messageToDisplay['PRICE'] < messageToDisplay['OPEN24HOUR']) {
			$('#CHANGE24HOURPCT_' + from).removeClass();
			$('#CHANGE24HOURPCT_' + from).addClass("pct-down");
		}
	};

	var displayMKTCAP = function(from,to) {
		var PRICE_FULL_URL = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=" + from + "&tsyms=" + to + '&api_key='+drupalSettings.crypto_api_key.api_key;
		$.getJSON(PRICE_FULL_URL, function(data) {
		  var NEW_RAW_MKTCAP = data['RAW'][from][to]['MKTCAP'];
		  var DISPLAY_MKTCAP = data['DISPLAY'][from][to]['MKTCAP'];
		  var OLD_RAW_MKTCAP = $('#MKCAP_' + from).attr('data-raw');
		  OLD_RAW_MKTCAP = OLD_RAW_MKTCAP !== undefined ? OLD_RAW_MKTCAP : 0;
		  $('#MKCAP_' + from).attr('data-raw', NEW_RAW_MKTCAP);

		  $('#MKCAP_' + from).text(DISPLAY_MKTCAP);
		  if (NEW_RAW_MKTCAP > OLD_RAW_MKTCAP) {
			$('#MKCAP_' + from).removeClass("down").addClass("up");
		  }
		  else if (NEW_RAW_MKTCAP < OLD_RAW_MKTCAP) {
			$('#MKCAP_' + from).removeClass("up").addClass("down");
		  }

		  var NEW_RAW_SUPPLY = data['RAW'][from][to]['SUPPLY'];
		  var DISPLAY_SUPPLY = data['DISPLAY'][from][to]['SUPPLY'].split(" ").pop().split('.')[0]+" "+from;
		  var OLD_RAW_SUPPLY = $('#SUPPLY_' + from).attr('data-raw');
		  OLD_RAW_SUPPLY = OLD_RAW_SUPPLY !== undefined ? OLD_RAW_SUPPLY : 0;
		  $('#SUPPLY_' + from).attr('data-raw', NEW_RAW_SUPPLY);

		  $('#SUPPLY_' + from).text(DISPLAY_SUPPLY);
		  if (NEW_RAW_SUPPLY > OLD_RAW_SUPPLY) {
			$('#SUPPLY_' + from).removeClass("down").addClass("up");
		  }
		  else if (NEW_RAW_SUPPLY < OLD_RAW_SUPPLY) {
			$('#SUPPLY_' + from).removeClass("up").addClass("down");
		  }
		});
	};

	var convertPRICE = function(from) {
		var symbols = ['EUR','GBP','JPY','INR','RUB','KRW'];
		var signs = ['€', '£', '¥', '₹', '₽', '₩'];
		var CONVERT_PRICE_URL = 'https://min-api.cryptocompare.com/data/price?fsym=' + from + '&tsyms=EUR,GBP,JPY,INR,RUB,KRW&api_key='+drupalSettings.crypto_api_key.api_key;
		$.getJSON(CONVERT_PRICE_URL, function(data) {

			$.each(symbols, function (key, symbol){
			  var sign = signs[key];
			  var NEW_PRICE = data[symbol];
			  var OLD_PRICE = $('#'+from + '_' + symbol +'_PRICE').attr('data-raw');
			  OLD_PRICE !== undefined ? OLD_PRICE : 0;

			  $('#'+from + '_' + symbol +'_PRICE').attr('data-raw', NEW_PRICE);
			  $('#'+from + '_' + symbol +'_PRICE').text(sign + ' ' + thousands_separators(NEW_PRICE));

			  $('#'+from + '_' + symbol +'_PRICE').removeClass();
			  if (NEW_PRICE > OLD_PRICE) {
			    $('#'+from + '_' + symbol +'_PRICE').addClass("up");
			  }
			  else if (NEW_PRICE < OLD_PRICE) {
			    $('#'+from + '_' + symbol +'_PRICE').addClass("down");
			  }

			});
		});

	};
  });

  function thousands_separators(num) {
    var num_parts = num.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
  }

})(jQuery, Drupal, drupalSettings, window);