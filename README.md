Douce Crypto profile
==================

Douce Crypto distribution is a simple Drupal 8 distribution for building a comprehensive list of live market capitalization data, charts and prices for over 100 crypto / Digital currencies  such as Bitcoin, Ethereum, Litecoin, XRP etc. with responsive design for all modern gadgets: computers, laptops, tablets and smartphones (landscape and portrait mode).

I have integrated the Crypto Compare site https://www.cryptocompare.com	websocket & REST API for crypto currencies live steam.

To install the profile:

- Unzip the folder and copy it to the necessary folder of your server.
- Install your new website with the Douce Crypto profile. It’s set by default, you just need to click the “Save and continue” button and follow the instructions.
- Wait for the Douce Crypto profile to be installed, enable the necessary modules and enjoy your website.

Notes:

- Please update the Crypto API Key after site installation "/admin/config/crypto_api/settings", else data updation will be failed when free API key maximum limit is reached.

  You can generate a new API key from Crypto Compare site https://www.cryptocompare.com	API menu tab.

- Installation of profile may take some time. If you get the “Maximum execution time...” error, then it needs to increase the max_execution_time parameter in php.ini file.

Features

- Provides easily configurable Crypto theme with Layout, Color, Link, Button etc settings from here "/admin/appearance/settings/crypto".

- Easily add and configure currencies data to the site.
  You can add a new currency with basic information from here "/node/add/currency" and then add a markets regarding this currency to store the markets data from here "/node/add/markets"

- Provides Live Price, Market CAP, Volume, Circulating Supply, Change etc data with the help of Crypto Compare https://www.cryptocompare.com API

- Import new News Feeds via Feeds Importer,
  Here one news feed added currently for news listing "/admin/content/feed", you can add more feeds from here "/feed/add".

- Provides SASS support for the Douce Crypto theme
  You can write the SASS code in sass file in theme folder "sass" directory and compile it

- Uses the Highchart library to display the intractive chart.

- Now this distribution supported more the 800 crypto currencies.

Thank you for choosing 

- Douce Crypto Profile