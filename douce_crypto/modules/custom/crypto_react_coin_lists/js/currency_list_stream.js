/**
 * @file
 * Provides the function for react to make
 * list and graph.
 */

//global variable
var currentPrice = {};

//Function dataUnpack
function dataUnpack(message) {
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
}

//Function decorateWithFullVolume
function decorateWithFullVolume(message) {
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
  displayMKTCAP(from,to);
}

//Function displayData
function displayData(messageToDisplay, from, tsym, fsym) {
  var priceDirection = messageToDisplay.FLAGS;
  var fields = CCC.CURRENT.DISPLAY.FIELDS;
  for (var key in fields) {
    if (messageToDisplay[key]) {
      if (fields[key].Show) {
        switch (fields[key].Filter) {
          case 'String':
            jQuery('[id="' + key + '_' + from + '"]').text(messageToDisplay[key]);
          break;

          case 'Number':
            var symbol = fields[key].Symbol == 'TOSYMBOL' ? tsym : fsym;

            if (key == 'VOLUME24HOURTO') {
              var OLD_RAW_VOLUME24HOURTO = jQuery('#VOLUME24HOURTO_' + from).attr('data-raw');
              var NEW_RAW_VOLUME24HOURTO = messageToDisplay[key];
 
              OLD_RAW_VOLUME24HOURTO = OLD_RAW_VOLUME24HOURTO !== undefined ? OLD_RAW_VOLUME24HOURTO : 0;
							  
              jQuery('[id="' + key + '_' + from + '"]').removeClass();
              if (NEW_RAW_VOLUME24HOURTO > OLD_RAW_VOLUME24HOURTO) {
                jQuery('[id="' + key + '_' + from + '"]').addClass('up');
              }
              else if (NEW_RAW_VOLUME24HOURTO < OLD_RAW_VOLUME24HOURTO) {
                jQuery('[id="' + key + '_' + from + '"]').addClass('down');
              }

              jQuery('[id="' + key + '_' + from + '"]').text(CCC.convertValueToDisplay(symbol, messageToDisplay[key]));
              jQuery('[id="' + key + '_' + from + '"]').attr('data-raw', messageToDisplay[key]);
            } else {
              jQuery('[id="' + key + '_' + from + '"]').text(CCC.convertValueToDisplay(symbol, messageToDisplay[key]));
            }

          break;
        }
      }
    }
  }
		
  jQuery('[id="PRICE_'+from+'"]').removeClass();
  if (priceDirection & 1) {
    jQuery('[id="PRICE_'+from+'"]').addClass("up");
  }
  else if (priceDirection & 2) {
    jQuery('[id="PRICE_'+from+'"]').addClass("down");
  }

  if (messageToDisplay['PRICE'] > messageToDisplay['OPEN24HOUR']) {
    jQuery('[id="CHANGE24HOURPCT_'+from+'"]').removeClass();
    jQuery('[id="CHANGE24HOURPCT_'+from+'"]').addClass("pct-up");
  }
  else if (messageToDisplay['PRICE'] < messageToDisplay['OPEN24HOUR']) {
    jQuery('[id="CHANGE24HOURPCT_'+from+'"]').removeClass();
    jQuery('[id="CHANGE24HOURPCT_'+from+'"]').addClass("pct-down");
  }
}

//Function displayMKTCAP
function displayMKTCAP(from,to) {
  var PRICE_FULL_URL = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=" + from + "&tsyms=" + to;
  jQuery.getJSON(PRICE_FULL_URL, function (data) {
    var NEW_RAW_MKTCAP = data['RAW'][from][to]['MKTCAP'];
    var DISPLAY_MKTCAP = data['DISPLAY'][from][to]['MKTCAP'];
    var OLD_RAW_MKTCAP = jQuery('#MKCAP_' + from).attr('data-raw');
    OLD_RAW_MKTCAP = OLD_RAW_MKTCAP !== undefined ? OLD_RAW_MKTCAP : 0;
    jQuery('[id="MKCAP_'+from+'"]').attr('data-raw', NEW_RAW_MKTCAP);
		  

    jQuery('[id="MKCAP_'+from+'"]').text(DISPLAY_MKTCAP);
    jQuery('[id="MKCAP_'+from+'"]').removeClass();
    if (NEW_RAW_MKTCAP > OLD_RAW_MKTCAP) {
      jQuery('[id="MKCAP_'+from+'"]').addClass("up");
    }
    else if (NEW_RAW_MKTCAP < OLD_RAW_MKTCAP) {
      jQuery('[id="MKCAP_'+from+'"]').addClass("down");
    }

    var NEW_RAW_SUPPLY = data['RAW'][from][to]['SUPPLY'];
    var DISPLAY_SUPPLY = data['DISPLAY'][from][to]['SUPPLY'].split(" ").pop().split('.')[0] + " " + from;
    var OLD_RAW_SUPPLY = jQuery('#SUPPLY_' + from).attr('data-raw');
    OLD_RAW_SUPPLY = OLD_RAW_SUPPLY !== undefined ? OLD_RAW_SUPPLY : 0;
    jQuery('#SUPPLY_' + from).attr('data-raw', NEW_RAW_SUPPLY);

    jQuery('#SUPPLY_' + from).text(DISPLAY_SUPPLY);
    jQuery('#SUPPLY_' + from).removeClass();
    if (NEW_RAW_SUPPLY > OLD_RAW_SUPPLY) {
      jQuery('#SUPPLY_' + from).addClass("up");
    }
    else if (NEW_RAW_SUPPLY < OLD_RAW_SUPPLY) {
      jQuery('#SUPPLY_' + from).addClass("down");
    }
  });
}
 