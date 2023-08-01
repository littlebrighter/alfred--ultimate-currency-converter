# The best Alfred Workflow for Converting Currencies. Period.

<img src="https://littlebrighter.erevo.io/alfred/img/ultimate-currency-converter/workflow.png" width="764">

## Prerequisites

* This workflow relies on exchange rate data provided by third-party APIs

* The following services are supported, pick one of them and get an API key:

    * [currency.getgeoapi.com](https://currency.getgeoapi.com) → get your API key [here](https://currency.getgeoapi.com/currency-plans/)

    * [exchangeratesapi.io](https://exchangeratesapi.io) → get your API key [here](https://manage.exchangeratesapi.io/signup/free) (please also see [issue #8](https://github.com/littlebrighter/alfred--ultimate-currency-converter/issues/8) for recent changes restricting the free plan of this API)

    * [currencyconverterapi.com](https://currencyconverterapi.com) → get your API key from [here](https://free.currencyconverterapi.com/free-api-key), usage is limited by number of requests per hour

## Prerequisites for users of macOS Monterey 12.0 and later

Apple no longer ships php bundled with macOS from Monterey 12.0, so you need to install php manually. Our recommendation is to go with homebrew. Alfred has built-in support for php from [homebrew](https://brew.sh).

1.) Install homebrew (if you do not have it already), go here [brew.sh](https://brew.sh) or directly run
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
in Terminal.app

2.) Install php with homebrew, i.e. run
```bash
brew install php
```
in Terminal.app


## Installation

Installation is as easy as downloading the workflow and double-clicking it to call Alfred.

In order to do so, head over to the [latest release](https://github.com/littlebrighter/alfred--ultimate-currency-converter/releases/latest) and fetch the `Ultimate.Currency.Converter.v*.alfredworkflow` file, e.g. download it to your Mac. Double-click the file and you will be guided to Alfred.app which asks you, if you want to install this workflow.

## Usage

### Currency Conversion

You can trigger this workflow in the Alfred window with the following keyword:

- `currency`

Ultimate Currency Converter accepts simple and complex queries. The next paragraphs trie to transport the idea.

The workflow uses two settings. A default **from**-currency and a default **to**-currency. Everytime you start the workflow without explicitely specifying one or both currencies needed for a conversion, Ultimate Currency Converter tries to guess what you intent to do by completing your query with the default ones. See below for changing default currencies.

#### Most simple example

If you start the workflow and do not specify any parameters, Ultimate Currency Converter guesses a value of 1 to be converted from your default from-currency to your default to-currency.

- `currency` → converts 1 EUR to USD

This assumes you did not change the factory-defaults, i.e. EUR is your default from-currency and USD your default to-currency

#### Basic exchange rate
 * `currency EUR` → displays current EUR / USD exchange rate
 * `currency EUR GBP` → displays current EUR / GBP exchange rate

You get the idea: look at the first example: no second currency is given, so the workflow completes your query with USD, which is your default to-currency.

<img src="https://littlebrighter.erevo.io/alfred/img/ultimate-currency-converter/workflow-details.png" width="764">

#### Basic conversion

 * `currency 23 EUR` → converts 23 EUR to USD
 * `currency 23 EUR GBP` or `currency 23EUR GBP` → converts 23 EUR to GBP

You may use a space between number and currency or just leave it out for brevity.

#### Symbols and International Codes support

 * `currency 23 € £` → converts 23 EUR to GBP

In addition to international three-character codes from ISO 4217 (like EUR and USD), you can also type symbols. Many are included (e.g. €, $, £, ¥, ₨, kn, …). If your preferred symbol is missing, just let us know.

####  Natural language support

The query string is quite flexible. Look at the following examples and use whatever order fits your needs best. Many combinations are supported.

  * `currency 23 EUR to £` → converts 23 EUR to GBP
  * `currency from 23 € to GBP` → converts 23 EUR to GBP
  * `currency to GBP from 23€` → converts 23 EUR to GBP
  * `currency to GBP 23€` → converts 23 EUR to GBP

### Additional Features

#### Integrated Knowledge Base

If you want to find out which currency is used in a specific country or which countries all use, e.g. EUR, you can use the look-up workflow. Just type a name or code of a currency or a country as query string after the currency-look-up keyword.

- `currency-look-up` *`<query>`*

<img src="https://littlebrighter.erevo.io/alfred/img/ultimate-currency-converter/workflow-look-up.png" width="764">

### Change the default from-currency

Use the currency-set-from keyword to set a new default from-currency.

- `currency-set-from` *`<query>`*

### Change the default to-currency

Use the currency-set-to keyword to set a new default to-currency.

- `currency-set-to` *`<query>`*

## Configuration

You need to configure this workflow before being able to use it. However, this basically comes down to choosing an API service, getting a key there and entering the key into Alfred.

This workflow uses the new **configuration** feature of Alfred 5.

## Support

If you like this workflow and it helps speed up your tasks, consider supporting our work:

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://www.paypal.me/n8g1" target="_blank"><img src="https://littlebrighter.erevo.io/alfred/img/paypal.png" width="100"></a>

---

## Acknowledgements

This workflow was heavily inspired and is based partly on the source code of bigluck’s (aka Luca Bigon) original [Alfred 2 Currency Converter](https://github.com/bigluck/alfred2-currencyconverter). Released with Luca’s permission.

---

### References

* <https://www.alfredapp.com/workflows/>
* <https://free.currencyconverterapi.com/free-api-key>
* <https://github.com/bigluck/alfred2-currencyconverter>

### License

Released under [MIT License](https://github.com/littlebrighter/alfred--ultimate-currency-converter/blob/main/LICENSE)
