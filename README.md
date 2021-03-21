# The best Alfred Workflow for Converting Currencies. Period.

<img src="https://littlebrighter.erevo.io/alfred/img/ultimate-currency-converter/workflow.png" width="764">

## Prerequisites

This workflow relies on currencyconverterapi.com. You will need to get an API key, which is available entirely free of charge [here](https://free.currencyconverterapi.com/free-api-key).

The free version is limited to 100 requests/hour, enough for most of us. Ultimate Currency Converter caches the requests, so that currency combinations are only fetched once per hour. Thus, you can perform 100 different combinations of two currencies every hour without running into the limit.

## Instructions

- `currency` *`<query>`*

Currency Converter accepts simple and complex queries.

### Basic exchange rate
 * `currency €` → displays current EUR / USD exchange rate
 * `currency € £` → displays current EUR / GBP exchange rate

### Basic conversion
 * `currency 23 €` → converts 23 EUR to USD
 * `currency 23€ £` → converts 23 EUR to GBP
 * `currency 23 € £` → converts 23 EUR to GBP

### Symbols and International Codes support
Use at your convenience, many symbols included (e.g. €, $, £, ¥, ₨, kn, …).
 * `currency 23 EUR £` → Convert 23 EUR to GBP

###  Natural language support
  * `currency 23 EUR to £` → converts 23 EUR to GBP
  * `currency from 23 € to GBP` → converts 23 EUR to GBP
  * `currency to GBP from 23€` → converts 23 EUR to GBP
  * `currency to GBP 23€` → converts 23 EUR to GBP

Many other combinations possible.

- `currency-set-from` *`<query>`*

Set a new default input currency

- `currency-set-to` *`<query>`*

Set a new default out currency

- `currency-look-up`

Browse currencies, currency abbreviations and symbols, countries and country abbreviations directly in the Alfred window.



## Preferences

Preferences are Alfred Workflow Environment Variables, which can be set / changed in the Alfred Preferences.

Name | | Values
------------|---|-------
lb_freecurrencyconverter_api_key | required | API key for the **free** version of the currencyconverter.api
lb_language | optional | possible values: `en` (default) or `de`, <br>only affects decimal point (`en` = `.` / `de` = `,`)<br>and thousands seperator (`en` = `,` / `de` = `.`)

---

## Acknowledgements

This workflow was heavily inspired and is based on the source code of bigluck’s (aka Luca Bigon) original [Alfred 2 Currency Converter](https://github.com/bigluck/alfred2-currencyconverter). Released with Luca’s permission.

---

### References

* <https://www.alfredapp.com/workflows/>
* <https://free.currencyconverterapi.com/free-api-key>
* <https://github.com/bigluck/alfred2-currencyconverter>
