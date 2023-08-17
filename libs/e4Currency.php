<?php

#[AllowDynamicProperties]
class e4Currency {

  protected $input = null;
  protected $options = array();

  public function __construct($input=null) {
    $this->setInput($input);
  }

  public function setInput($input=null) {
    if ($input === null) $input = '';
    $this->input = mb_strtoupper(trim($input) ?: '', 'UTF-8');
  }

  public function getInput() {
    return $this->input;
  }

  public function getOptions() {
    return array_keys($this->options);
  }

  public function getFirstOption($default=false) {
    $options = $this->getOptions();
    return $options[0] ?: $default;
  }

  public function parse() {
    $this->options = array();

    if (($tmp = self::isValidSymbol($this->input)) !== false)
      $this->options[$tmp] = true;
    if (($tmp = self::isValidCurrency($this->input)) !== false)
      $this->options[$tmp] = true;
    if (!count($this->options))
      $this->options = self::autoCompose($this->input);

    return count($this->options) ?: false;
  }

  public function isEqualOf(e4Currency $test) {
    return $this->options == $test->options;
  }

  static $validSymbols = array(
    '$' => 'USD',
    'b/.' => 'PAB',
    'gs' => 'PYG',
    'km' => 'BAM',
    'kn' => 'HRK',
    'kr' => 'SEK',
    'lei' => 'RON',
    'lek' => 'ALL',
    'ls' => 'LVL',
    'lt' => 'LTL',
    'mt' => 'MZN',
    'p.' => 'BYR',
    'r' => 'ZAR',
    'rm' => 'MYR',
    's' => 'SOS',
    's/.' => 'PEN',
    'z$' => 'ZWD',
    'zł' => 'PLN',
    '£' => 'GBP',
    '¥' => 'JPY',
    'ƒ' => 'ANG',
    'Дин' => 'RSD',
    'ден' => 'MKD',
    'лв' => 'BGN',
    'ман' => 'AZN',
    'руб' => 'RUB',
    '؋' => 'AFN',
    '฿' => 'THB',
    '៛' => 'KHR',
    '₡' => 'CRC',
    '₤' => 'TRL',
    '₦' => 'NGN',
    '₨' => 'NPR',
    '₩' => 'KRW',
    '₪' => 'ILS',
    '₫' => 'VND',
    '€' => 'EUR',
    '₭' => 'LAK',
    '₮' => 'MNT',
    '₱' => 'PHP',
    '₴' => 'UAH',
    '₹' => 'INR',
    '﷼' => 'SAR'
  );

  static $special_countries = array(
    'USD' => 'US',
    'CHF' => 'CH',
    'SDG' => 'SD',
    'AUD' => 'AU',
    'EUR' => 'EU'
  );

  static $validCurrency = array(
    'AED' => 'UAE Dirham',
    'AFN' => 'Afghan Afghani',
    'ALL' => 'Albanian Lek',
    'AMD' => 'Armenian Dram',
    'ANG' => 'Netherlands Antillean Gulden',
    'AOA' => 'Angolan Kwanza',
    'ARS' => 'Argentine Peso',
    'AUD' => 'Australian Dollar',
    'AWG' => 'Aruban Florin',
    'AZN' => 'Azerbaijani Manat',
    'BAM' => 'Bosnia And Herzegovina Konvertibilna Marka',
    'BBD' => 'Barbadian Dollar',
    'BDT' => 'Bangladeshi Taka',
    'BGN' => 'Bulgarian Lev',
    'BHD' => 'Bahraini Dinar',
    'BIF' => 'Burundi Franc',
    'BMD' => 'Bermudan Dollar',
    'BND' => 'Brunei Dollar',
    'BOB' => 'Bolivian Boliviano',
    'BRL' => 'Brazilian Real',
    'BSD' => 'Bahamian Dollar',
    'BTC' => 'Bitcoin',
    'BTN' => 'Bhutanese Ngultrum',
    'BWP' => 'Botswana Pula',
    'BYN' => 'New Belarusian Ruble',
    'BZD' => 'Belize Dollar',
    'CAD' => 'Canadian Dollar',
    'CDF' => 'Congolese Franc',
    'CHF' => 'Swiss Franc',
    'CLP' => 'Chilean Peso',
    'CNY' => 'Chinese Yuan',
    'COP' => 'Colombian Peso',
    'CRC' => 'Costa Rican Colon',
    'CUP' => 'Cuban Peso',
    'CVE' => 'Cape Verdean Escudo',
    'CZK' => 'Czech Koruna',
    'DJF' => 'Djiboutian Franc',
    'DKK' => 'Danish Krone',
    'DOP' => 'Dominican Peso',
    'DZD' => 'Algerian Dinar',
    'EGP' => 'Egyptian Pound',
    'ERN' => 'Eritrean Nakfa',
    'ETB' => 'Ethiopian Birr',
    'EUR' => 'Euro',
    'FJD' => 'Fijian Dollar',
    'FKP' => 'Falkland Islands Pound',
    'GBP' => 'British Pound Sterling',
    'GEL' => 'Georgian Lari',
    'GGP' => 'Guernsey Pound',
    'GHS' => 'Ghanaian Cedi',
    'GIP' => 'Gibraltar Pound',
    'GMD' => 'Gambian Dalasi',
    'GNF' => 'Guinean Franc',
    'GTQ' => 'Guatemalan Quetzal',
    'GYD' => 'Guyanese Dollar',
    'HKD' => 'Hong Kong Dollar',
    'HNL' => 'Honduran Lempira',
    'HRK' => 'Croatian Kuna',
    'HTG' => 'Haitian Gourde',
    'HUF' => 'Hungarian Forint',
    'IDR' => 'Indonesian Rupiah',
    'ILS' => 'Israeli New Sheqel',
    'IMP' => 'Manx pound',
    'INR' => 'Indian Rupee',
    'IQD' => 'Iraqi Dinar',
    'IRR' => 'Iranian Rial',
    'ISK' => 'Icelandic Krona',
    'JEP' => 'Jersey Pound',
    'JMD' => 'Jamaican Dollar',
    'JOD' => 'Jordanian Dinar',
    'JPY' => 'Japanese Yen',
    'KES' => 'Kenyan Shilling',
    'KGS' => 'Kyrgyzstani Som',
    'KHR' => 'Cambodian Riel',
    'KMF' => 'Comorian Franc',
    'KPW' => 'North Korean Won',
    'KRW' => 'South Korean Won',
    'KWD' => 'Kuwaiti Dinar',
    'KYD' => 'Cayman Islands Dollar',
    'KZT' => 'Kazakhstani Tenge',
    'LAK' => 'Lao Kip',
    'LBP' => 'Lebanese Lira',
    'LKR' => 'Sri Lankan Rupee',
    'LRD' => 'Liberian Dollar',
    'LSL' => 'Lesotho Loti',
    'LYD' => 'Libyan Dinar',
    'MAD' => 'Moroccan Dirham',
    'MDL' => 'Moldovan Leu',
    'MGA' => 'Malagasy Ariary',
    'MKD' => 'Macedonian Denar',
    'MMK' => 'Myanma Kyat',
    'MNT' => 'Mongolian Tugrik',
    'MOP' => 'Macanese Pataca',
    'MRO' => 'Mauritanian Ouguiya',
    'MUR' => 'Mauritian Rupee',
    'MVR' => 'Maldivian Rufiyaa',
    'MWK' => 'Malawian Kwacha',
    'MXN' => 'Mexican Peso',
    'MYR' => 'Malaysian Ringgit',
    'MZN' => 'Mozambican Metical',
    'NAD' => 'Namibian Dollar',
    'NGN' => 'Nigerian Naira',
    'NIO' => 'Nicaraguan Cordoba',
    'NOK' => 'Norwegian Krone',
    'NPR' => 'Nepalese Rupee',
    'NZD' => 'New Zealand Dollar',
    'OMR' => 'Omani Rial',
    'PAB' => 'Panamanian Balboa',
    'PEN' => 'Peruvian Nuevo Sol',
    'PGK' => 'Papua New Guinean Kina',
    'PHP' => 'Philippine Peso',
    'PKR' => 'Pakistani Rupee',
    'PLN' => 'Polish Zloty',
    'PYG' => 'Paraguayan Guarani',
    'QAR' => 'Qatari Riyal',
    'RON' => 'Romanian Leu',
    'RSD' => 'Serbian Dinar',
    'RUB' => 'Russian Ruble',
    'RWF' => 'Rwandan Franc',
    'SAR' => 'Saudi Riyal',
    'SBD' => 'Solomon Islands Dollar',
    'SCR' => 'Seychellois Rupee',
    'SDG' => 'Sudanese Pound',
    'SEK' => 'Swedish Krona',
    'SGD' => 'Singapore Dollar',
    'SHP' => 'Saint Helena Pound',
    'SLL' => 'Sierra Leonean Leone',
    'SOS' => 'Somali Shilling',
    'SRD' => 'Surinamese Dollar',
    'STD' => 'Sao Tome And Principe Dobra',
    'SYP' => 'Syrian Pound',
    'SZL' => 'Swazi Lilangeni',
    'THB' => 'Thai Baht',
    'TJS' => 'Tajikistani Somoni',
    'TMT' => 'Turkmenistan Manat',
    'TND' => 'Tunisian Dinar',
    'TOP' => 'Paanga',
    'TRY' => 'Turkish New Lira',
    'TTD' => 'Trinidad and Tobago Dollar',
    'TWD' => 'New Taiwan Dollar',
    'TZS' => 'Tanzanian Shilling',
    'UAH' => 'Ukrainian Hryvnia',
    'UGX' => 'Ugandan Shilling',
    'USD' => 'United States Dollar',
    'UYU' => 'Uruguayan Peso',
    'UZS' => 'Uzbekistani Som',
    'VEF' => 'Venezuelan Bolivar',
    'VND' => 'Vietnamese Dong',
    'VUV' => 'Vanuatu Vatu',
    'WST' => 'Samoan Tala',
    'XAF' => 'Central African CFA Franc',
    'XCD' => 'East Caribbean Dollar',
    'XOF' => 'West African CFA Franc',
    'XPF' => 'CFP Franc',
    'YER' => 'Yemeni Rial',
    'ZAR' => 'South African Rand',
    'ZMW' => 'Zambian Kwacha',
    'ZWL' => 'Zimbabwean Dollar'
  );

  static $emojiFlags = array(
    'AED' => "\u{1F1E6}\u{1F1EA}",
    'AFN' => "\u{1F1E6}\u{1F1EB}",
    'ALL' => "\u{1F1E6}\u{1F1F1}",
    'AMD' => "\u{1F1E6}\u{1F1F2}",
    'ANG' => "",
    'AOA' => "\u{1F1E6}\u{1F1F4}",
    'ARS' => "\u{1F1E6}\u{1F1F7}",
    'AUD' => "\u{1F1E6}\u{1F1FA}",
    'AWG' => "\u{1F1E6}\u{1F1FC}",
    'AZN' => "\u{1F1E6}\u{1F1FF}",
    'BAM' => "\u{1F1E7}\u{1F1E6}",
    'BBD' => "\u{1F1E7}\u{1F1E7}",
    'BDT' => "\u{1F1E7}\u{1F1E9}",
    'BGN' => "\u{1F1E7}\u{1F1EC}",
    'BHD' => "\u{1F1E7}\u{1F1ED}",
    'BIF' => "\u{1F1E7}\u{1F1EE}",
    'BMD' => "\u{1F1E7}\u{1F1F2}",
    'BND' => "\u{1F1E7}\u{1F1F3}",
    'BOB' => "\u{1F1E7}\u{1F1F4}",
    'BRL' => "\u{1F1E7}\u{1F1F7}",
    'BSD' => "\u{1F1E7}\u{1F1F8}",
    'BTC' => "",
    'BTN' => "\u{1F1E7}\u{1F1F9}",
    'BWP' => "\u{1F1E7}\u{1F1FC}",
    'BYN' => "\u{1F1E7}\u{1F1FE}",
    'BZD' => "\u{1F1E7}\u{1F1FF}",
    'CAD' => "\u{1F1E8}\u{1F1E6}",
    'CDF' => "\u{1F1E8}\u{1F1E9}",
    'CHF' => "\u{1F1E8}\u{1F1ED}",
    'CLP' => "\u{1F1E8}\u{1F1F1}",
    'CNY' => "\u{1F1E8}\u{1F1F3}",
    'COP' => "\u{1F1E8}\u{1F1F4}",
    'CRC' => "\u{1F1E8}\u{1F1F7}",
    'CUP' => "\u{1F1E8}\u{1F1FA}",
    'CVE' => "\u{1F1E8}\u{1F1FB}",
    'CZK' => "\u{1F1E8}\u{1F1FF}",
    'DJF' => "\u{1F1E9}\u{1F1EF}",
    'DKK' => "\u{1F1E9}\u{1F1F0}",
    'DOP' => "\u{1F1E9}\u{1F1F4}",
    'DZD' => "\u{1F1E9}\u{1F1FF}",
    'EGP' => "\u{1F1EA}\u{1F1EC}",
    'ERN' => "\u{1F1EA}\u{1F1F7}",
    'ETB' => "\u{1F1EA}\u{1F1F9}",
    'EUR' => "\u{1F1EA}\u{1F1FA}",
    'FJD' => "\u{1F1EB}\u{1F1EF}",
    'FKP' => "\u{1F1EB}\u{1F1F0}",
    'GBP' => "\u{1F1EC}\u{1F1E7}",
    'GEL' => "\u{1F1EC}\u{1F1EA}",
    'GGP' => "\u{1F1EC}\u{1F1EC}",
    'GHS' => "\u{1F1EC}\u{1F1ED}",
    'GIP' => "\u{1F1EC}\u{1F1EE}",
    'GMD' => "\u{1F1EC}\u{1F1F2}",
    'GNF' => "\u{1F1EC}\u{1F1F3}",
    'GTQ' => "\u{1F1EC}\u{1F1F9}",
    'GYD' => "\u{1F1EC}\u{1F1FE}",
    'HKD' => "\u{1F1ED}\u{1F1F0}",
    'HNL' => "\u{1F1ED}\u{1F1F3}",
    'HRK' => "\u{1F1ED}\u{1F1F7}",
    'HTG' => "\u{1F1ED}\u{1F1F9}",
    'HUF' => "\u{1F1ED}\u{1F1FA}",
    'IDR' => "\u{1F1EE}\u{1F1E9}",
    'ILS' => "\u{1F1EE}\u{1F1F1}",
    'IMP' => "\u{1F1EE}\u{1F1F2}",
    'INR' => "\u{1F1EE}\u{1F1F3}",
    'IQD' => "\u{1F1EE}\u{1F1F6}",
    'IRR' => "\u{1F1EE}\u{1F1F7}",
    'ISK' => "\u{1F1EE}\u{1F1F8}",
    'JEP' => "\u{1F1EF}\u{1F1EA}",
    'JMD' => "\u{1F1EF}\u{1F1F2}",
    'JOD' => "\u{1F1EF}\u{1F1F4}",
    'JPY' => "\u{1F1EF}\u{1F1F5}",
    'KES' => "\u{1F1F0}\u{1F1EA}",
    'KGS' => "\u{1F1F0}\u{1F1EC}",
    'KHR' => "\u{1F1F0}\u{1F1ED}",
    'KMF' => "\u{1F1F0}\u{1F1F2}",
    'KPW' => "\u{1F1F0}\u{1F1F5}",
    'KRW' => "\u{1F1F0}\u{1F1F7}",
    'KWD' => "\u{1F1F0}\u{1F1FC}",
    'KYD' => "\u{1F1F0}\u{1F1FE}",
    'KZT' => "\u{1F1F0}\u{1F1FF}",
    'LAK' => "\u{1F1F1}\u{1F1E6}",
    'LBP' => "\u{1F1F1}\u{1F1E7}",
    'LKR' => "\u{1F1F1}\u{1F1F0}",
    'LRD' => "\u{1F1F1}\u{1F1F7}",
    'LSL' => "\u{1F1F1}\u{1F1F8}",
    'LYD' => "\u{1F1F1}\u{1F1FE}",
    'MAD' => "\u{1F1F2}\u{1F1E6}",
    'MDL' => "\u{1F1F2}\u{1F1E9}",
    'MGA' => "\u{1F1F2}\u{1F1EC}",
    'MKD' => "\u{1F1F2}\u{1F1F0}",
    'MMK' => "\u{1F1F2}\u{1F1F2}",
    'MNT' => "\u{1F1F2}\u{1F1F3}",
    'MOP' => "\u{1F1F2}\u{1F1F4}",
    'MRO' => "\u{1F1F2}\u{1F1F7}",
    'MUR' => "\u{1F1F2}\u{1F1FA}",
    'MVR' => "\u{1F1F2}\u{1F1FB}",
    'MWK' => "\u{1F1F2}\u{1F1FC}",
    'MXN' => "\u{1F1F2}\u{1F1FD}",
    'MYR' => "\u{1F1F2}\u{1F1FE}",
    'MZN' => "\u{1F1F2}\u{1F1FF}",
    'NAD' => "\u{1F1F3}\u{1F1E6}",
    'NGN' => "\u{1F1F3}\u{1F1EC}",
    'NIO' => "\u{1F1F3}\u{1F1EE}",
    'NOK' => "\u{1F1F3}\u{1F1F4}",
    'NPR' => "\u{1F1F3}\u{1F1F5}",
    'NZD' => "\u{1F1F3}\u{1F1FF}",
    'OMR' => "\u{1F1F4}\u{1F1F2}",
    'PAB' => "\u{1F1F5}\u{1F1E6}",
    'PEN' => "\u{1F1F5}\u{1F1EA}",
    'PGK' => "\u{1F1F5}\u{1F1EC}",
    'PHP' => "\u{1F1F5}\u{1F1ED}",
    'PKR' => "\u{1F1F5}\u{1F1F0}",
    'PLN' => "\u{1F1F5}\u{1F1F1}",
    'PYG' => "\u{1F1F5}\u{1F1FE}",
    'QAR' => "\u{1F1F6}\u{1F1E6}",
    'RON' => "\u{1F1F7}\u{1F1F4}",
    'RSD' => "\u{1F1F7}\u{1F1F8}",
    'RUB' => "\u{1F1F7}\u{1F1FA}",
    'RWF' => "\u{1F1F7}\u{1F1FC}",
    'SAR' => "\u{1F1F8}\u{1F1E6}",
    'SBD' => "\u{1F1F8}\u{1F1E7}",
    'SCR' => "\u{1F1F8}\u{1F1E8}",
    'SDG' => "\u{1F1F8}\u{1F1E9}",
    'SEK' => "\u{1F1F8}\u{1F1EA}",
    'SGD' => "\u{1F1F8}\u{1F1EC}",
    'SHP' => "\u{1F1F8}\u{1F1ED}",
    'SLL' => "\u{1F1F8}\u{1F1F1}",
    'SOS' => "\u{1F1F8}\u{1F1F4}",
    'SRD' => "\u{1F1F8}\u{1F1F7}",
    'STD' => "\u{1F1F8}\u{1F1F9}",
    'SYP' => "\u{1F1F8}\u{1F1FE}",
    'SZL' => "\u{1F1F8}\u{1F1FF}",
    'THB' => "\u{1F1F9}\u{1F1ED}",
    'TJS' => "\u{1F1F9}\u{1F1EF}",
    'TMT' => "\u{1F1F9}\u{1F1F2}",
    'TND' => "\u{1F1F9}\u{1F1F3}",
    'TOP' => "\u{1F1F9}\u{1F1F4}",
    'TRY' => "\u{1F1F9}\u{1F1F7}",
    'TTD' => "\u{1F1F9}\u{1F1F9}",
    'TWD' => "\u{1F1F9}\u{1F1FC}",
    'TZS' => "\u{1F1F9}\u{1F1FF}",
    'UAH' => "\u{1F1FA}\u{1F1E6}",
    'UGX' => "\u{1F1FA}\u{1F1EC}",
    'USD' => "\u{1F1FA}\u{1F1F8}",
    'UYU' => "\u{1F1FA}\u{1F1FE}",
    'UZS' => "\u{1F1FA}\u{1F1FF}",
    'VEF' => "\u{1F1FB}\u{1F1EA}",
    'VND' => "\u{1F1FB}\u{1F1F3}",
    'VUV' => "\u{1F1FB}\u{1F1FA}",
    'WST' => "\u{1F1FC}\u{1F1F8}",
    'XAF' => "",
    'XCD' => "",
    'XOF' => "",
    'XPF' => "",
    'YER' => "\u{1F1FE}\u{1F1EA}",
    'ZAR' => "\u{1F1FF}\u{1F1E6}",
    'ZMW' => "\u{1F1FF}\u{1F1F2}",
    'ZWL' => "\u{1F1FF}\u{1F1FC}"
  );

  // Standard-Digits = 2
  static $specialDigits = array(

    'BIF' => 0,
    'XOF' => 0,
    'XAF' => 0,
    'XPF' => 0,
    'CVE' => 0,
    'CLP' => 0,
    'KMF' => 0,
    'DJF' => 0,
    'GNF' => 0,
    'ISK' => 0,
    'JPY' => 0,
    'PYG' => 0,
    'RWF' => 0,
    'KRW' => 0,
    'UGX' => 0,
    'VUV' => 0,
    'VND' => 0,

    'MGA' => 1,

    'BHD' => 3,
    'IQD' => 3,
    'JOD' => 3,
    'KWD' => 3,
    'LYD' => 3,
    'OMR' => 3,
    'TND' => 3

  );

  static $countries = array(
    'AD' => array("alpha3" => "AND", "currencyId" => "EUR", "name" => "Andorra"),
    'AE' => array("alpha3" => "ARE", "currencyId" => "AED", "name" => "United Arab Emirates"),
    'AF' => array("alpha3" => "AFG", "currencyId" => "AFN", "name" => "Afghanistan"),
    'AG' => array("alpha3" => "ATG", "currencyId" => "XCD", "name" => "Antigua and Barbuda"),
    'AI' => array("alpha3" => "AIA", "currencyId" => "XCD", "name" => "Anguilla"),
    'AL' => array("alpha3" => "ALB", "currencyId" => "ALL", "name" => "Albania"),
    'AM' => array("alpha3" => "ARM", "currencyId" => "AMD", "name" => "Armenia"),
    'AO' => array("alpha3" => "AGO", "currencyId" => "AOA", "name" => "Angola"),
    'AR' => array("alpha3" => "ARG", "currencyId" => "ARS", "name" => "Argentina"),
    'AT' => array("alpha3" => "AUT", "currencyId" => "EUR", "name" => "Austria"),
    'AU' => array("alpha3" => "AUS", "currencyId" => "AUD", "name" => "Australia"),
    'AW' => array("alpha3" => "ABW", "currencyId" => "AWG", "name" => "Aruba"),
    'AZ' => array("alpha3" => "AZE", "currencyId" => "AZN", "name" => "Azerbaijan"),
    'BA' => array("alpha3" => "BIH", "currencyId" => "BAM", "name" => "Bosnia-Herzegovina"),
    'BB' => array("alpha3" => "BRB", "currencyId" => "BBD", "name" => "Barbados"),
    'BD' => array("alpha3" => "BGD", "currencyId" => "BDT", "name" => "Bangladesh"),
    'BE' => array("alpha3" => "BEL", "currencyId" => "EUR", "name" => "Belgium"),
    'BF' => array("alpha3" => "BFA", "currencyId" => "XOF", "name" => "Burkina Faso"),
    'BG' => array("alpha3" => "BGR", "currencyId" => "BGN", "name" => "Bulgaria"),
    'BH' => array("alpha3" => "BHR", "currencyId" => "BHD", "name" => "Bahrain"),
    'BI' => array("alpha3" => "BDI", "currencyId" => "BIF", "name" => "Burundi"),
    'BJ' => array("alpha3" => "BEN", "currencyId" => "XOF", "name" => "Benin"),
    'BM' => array("alpha3" => "BMU", "currencyId" => "BMD", "name" => "Bermuda"),
    'BN' => array("alpha3" => "BRN", "currencyId" => "BND", "name" => "Brunei"),
    'BO' => array("alpha3" => "BOL", "currencyId" => "BOB", "name" => "Bolivia"),
    'BR' => array("alpha3" => "BRA", "currencyId" => "BRL", "name" => "Brazil"),
    'BS' => array("alpha3" => "BHS", "currencyId" => "BSD", "name" => "Bahamas"),
    'BT' => array("alpha3" => "BTN", "currencyId" => "BTN", "name" => "Bhutan"),
    'BW' => array("alpha3" => "BWA", "currencyId" => "BWP", "name" => "Botswana"),
    'BY' => array("alpha3" => "BLR", "currencyId" => "BYN", "name" => "Belarus"),
    'BZ' => array("alpha3" => "BLZ", "currencyId" => "BZD", "name" => "Belize"),
    'CA' => array("alpha3" => "CAN", "currencyId" => "CAD", "name" => "Canada"),
    'CD' => array("alpha3" => "COD", "currencyId" => "CDF", "name" => "Congo, Democratic Republic"),
    'CF' => array("alpha3" => "CAF", "currencyId" => "XAF", "name" => "Central African Republic"),
    'CG' => array("alpha3" => "COG", "currencyId" => "XAF", "name" => "Congo"),
    'CH' => array("alpha3" => "CHE", "currencyId" => "CHF", "name" => "Switzerland"),
    'CI' => array("alpha3" => "CIV", "currencyId" => "XOF", "name" => "Cote d'Ivoire"),
    'CL' => array("alpha3" => "CHL", "currencyId" => "CLP", "name" => "Chile"),
    'CM' => array("alpha3" => "CMR", "currencyId" => "XAF", "name" => "Cameroon"),
    'CN' => array("alpha3" => "CHN", "currencyId" => "CNY", "name" => "China"),
    'CO' => array("alpha3" => "COL", "currencyId" => "COP", "name" => "Colombia"),
    'CR' => array("alpha3" => "CRI", "currencyId" => "CRC", "name" => "Costa Rica"),
    'CU' => array("alpha3" => "CUB", "currencyId" => "CUP", "name" => "Cuba"),
    'CV' => array("alpha3" => "CPV", "currencyId" => "CVE", "name" => "Cape Verde"),
    'CY' => array("alpha3" => "CYP", "currencyId" => "EUR", "name" => "Cyprus"),
    'CZ' => array("alpha3" => "CZE", "currencyId" => "CZK", "name" => "Czech Republic"),
    'DE' => array("alpha3" => "DEU", "currencyId" => "EUR", "name" => "Germany"),
    'DJ' => array("alpha3" => "DJI", "currencyId" => "DJF", "name" => "Djibouti"),
    'DK' => array("alpha3" => "DNK", "currencyId" => "DKK", "name" => "Denmark"),
    'DM' => array("alpha3" => "DMA", "currencyId" => "XCD", "name" => "Dominica"),
    'DO' => array("alpha3" => "DOM", "currencyId" => "DOP", "name" => "Dominican Republic"),
    'DZ' => array("alpha3" => "DZA", "currencyId" => "DZD", "name" => "Algeria"),
    'EC' => array("alpha3" => "ECU", "currencyId" => "USD", "name" => "Ecuador"),
    'EE' => array("alpha3" => "EST", "currencyId" => "EUR", "name" => "Estonia"),
    'EG' => array("alpha3" => "EGY", "currencyId" => "EGP", "name" => "Egypt"),
    'ER' => array("alpha3" => "ERI", "currencyId" => "ERN", "name" => "Eritrea"),
    'ES' => array("alpha3" => "ESP", "currencyId" => "EUR", "name" => "Spain"),
    'ET' => array("alpha3" => "ETH", "currencyId" => "ETB", "name" => "Ethiopia"),
    'EU' => array("alpha3" => "EUR", "currencyId" => "EUR", "name" => "Europe"),
    'FI' => array("alpha3" => "FIN", "currencyId" => "EUR", "name" => "Finland"),
    'FJ' => array("alpha3" => "FJI", "currencyId" => "FJD", "name" => "Fiji"),
    'FK' => array("alpha3" => "FLK", "currencyId" => "FKP", "name" => "Falkland Islands (Malvinas)"),
    'FM' => array("alpha3" => "FSM", "currencyId" => "USD", "name" => "Micronesia"),
    'FR' => array("alpha3" => "FRA", "currencyId" => "EUR", "name" => "France"),
    'GA' => array("alpha3" => "GAB", "currencyId" => "XAF", "name" => "Gabon"),
    'GB' => array("alpha3" => "GBR", "currencyId" => "GBP", "name" => "United Kingdom"),
    'GD' => array("alpha3" => "GRD", "currencyId" => "XCD", "name" => "Grenada"),
    'GE' => array("alpha3" => "GEO", "currencyId" => "GEL", "name" => "Georgia"),
    'GG' => array("alpha3" => "GGY", "currencyId" => "GGP", "name" => "Guernsey"),
    'GH' => array("alpha3" => "GHA", "currencyId" => "GHS", "name" => "Ghana"),
    'GI' => array("alpha3" => "GIB", "currencyId" => "GIP", "name" => "Gibraltar"),
    'GM' => array("alpha3" => "GMB", "currencyId" => "GMD", "name" => "Gambia"),
    'GN' => array("alpha3" => "GIN", "currencyId" => "GNF", "name" => "Guinea"),
    'GR' => array("alpha3" => "GRC", "currencyId" => "EUR", "name" => "Greece"),
    'GT' => array("alpha3" => "GTM", "currencyId" => "GTQ", "name" => "Guatemala"),
    'GW' => array("alpha3" => "GNB", "currencyId" => "XOF", "name" => "Guinea-Bissau"),
    'GY' => array("alpha3" => "GUY", "currencyId" => "GYD", "name" => "Guyana"),
    'HK' => array("alpha3" => "HKG", "currencyId" => "HKD", "name" => "Hong Kong"),
    'HN' => array("alpha3" => "HND", "currencyId" => "HNL", "name" => "Honduras"),
    'HR' => array("alpha3" => "HRV", "currencyId" => "HRK", "name" => "Croatia"),
    'HT' => array("alpha3" => "HTI", "currencyId" => "HTG", "name" => "Haiti"),
    'HU' => array("alpha3" => "HUN", "currencyId" => "HUF", "name" => "Hungary"),
    'ID' => array("alpha3" => "IDN", "currencyId" => "IDR", "name" => "Indonesia"),
    'IE' => array("alpha3" => "IRL", "currencyId" => "EUR", "name" => "Ireland"),
    'IL' => array("alpha3" => "ISR", "currencyId" => "ILS", "name" => "Israel"),
    'IM' => array("alpha3" => "IMN", "currencyId" => "IMP", "name" => "Isle of Man"),
    'IN' => array("alpha3" => "IND", "currencyId" => "INR", "name" => "India"),
    'IQ' => array("alpha3" => "IRQ", "currencyId" => "IQD", "name" => "Iraq"),
    'IR' => array("alpha3" => "IRN", "currencyId" => "IRR", "name" => "Iran, Islamic Republic of"),
    'IS' => array("alpha3" => "ISL", "currencyId" => "ISK", "name" => "Iceland"),
    'IT' => array("alpha3" => "ITA", "currencyId" => "EUR", "name" => "Italy"),
    'JE' => array("alpha3" => "JEY", "currencyId" => "JEP", "name" => "Jersey"),
    'JM' => array("alpha3" => "JAM", "currencyId" => "JMD", "name" => "Jamaica"),
    'JO' => array("alpha3" => "JOR", "currencyId" => "JOD", "name" => "Jordan"),
    'JP' => array("alpha3" => "JPN", "currencyId" => "JPY", "name" => "Japan"),
    'KE' => array("alpha3" => "KEN", "currencyId" => "KES", "name" => "Kenya"),
    'KG' => array("alpha3" => "KGZ", "currencyId" => "KGS", "name" => "Kyrgyzstan"),
    'KH' => array("alpha3" => "KHM", "currencyId" => "KHR", "name" => "Cambodia"),
    'KI' => array("alpha3" => "KIR", "currencyId" => "AUD", "name" => "Kiribati"),
    'KM' => array("alpha3" => "COM", "currencyId" => "KMF", "name" => "Comoros"),
    'KN' => array("alpha3" => "KNA", "currencyId" => "XCD", "name" => "Saint Kitts and Nevis"),
    'KP' => array("alpha3" => "PRK", "currencyId" => "KPW", "name" => "Korea North"),
    'KR' => array("alpha3" => "KOR", "currencyId" => "KRW", "name" => "Korea South"),
    'KW' => array("alpha3" => "KWT", "currencyId" => "KWD", "name" => "Kuwait"),
    'KY' => array("alpha3" => "CYM", "currencyId" => "KYD", "name" => "Cayman Islands"),
    'KZ' => array("alpha3" => "KAZ", "currencyId" => "KZT", "name" => "Kazakhstan"),
    'LA' => array("alpha3" => "LAO", "currencyId" => "LAK", "name" => "Laos"),
    'LB' => array("alpha3" => "LBN", "currencyId" => "LBP", "name" => "Lebanon"),
    'LC' => array("alpha3" => "LCA", "currencyId" => "XCD", "name" => "Saint Lucia"),
    'LI' => array("alpha3" => "LIE", "currencyId" => "CHF", "name" => "Liechtenstein"),
    'LK' => array("alpha3" => "LKA", "currencyId" => "LKR", "name" => "Sri Lanka"),
    'LR' => array("alpha3" => "LBR", "currencyId" => "LRD", "name" => "Liberia"),
    'LS' => array("alpha3" => "LSO", "currencyId" => "LSL", "name" => "Lesotho"),
    'LT' => array("alpha3" => "LTU", "currencyId" => "EUR", "name" => "Lithuania"),
    'LU' => array("alpha3" => "LUX", "currencyId" => "EUR", "name" => "Luxembourg"),
    'LV' => array("alpha3" => "LVA", "currencyId" => "LVL", "name" => "Latvia"),
    'LY' => array("alpha3" => "LBY", "currencyId" => "LYD", "name" => "Libya"),
    'MA' => array("alpha3" => "MAR", "currencyId" => "MAD", "name" => "Morocco"),
    'MC' => array("alpha3" => "MCO", "currencyId" => "EUR", "name" => "Monaco"),
    'MD' => array("alpha3" => "MDA", "currencyId" => "MDL", "name" => "Moldova"),
    'ME' => array("alpha3" => "MNE", "currencyId" => "EUR", "name" => "Montenegro"),
    'MG' => array("alpha3" => "MDG", "currencyId" => "MGA", "name" => "Madagascar"),
    'MK' => array("alpha3" => "MKD", "currencyId" => "MKD", "name" => "Macedonia (Former Yug. Rep.)"),
    'ML' => array("alpha3" => "MLI", "currencyId" => "XOF", "name" => "Mali"),
    'MM' => array("alpha3" => "MMR", "currencyId" => "MMK", "name" => "Myanmar"),
    'MN' => array("alpha3" => "MNG", "currencyId" => "MNT", "name" => "Mongolia"),
    'MO' => array("alpha3" => "MAC", "currencyId" => "MOP", "name" => "Macau"),
    'MR' => array("alpha3" => "MRT", "currencyId" => "MRO", "name" => "Mauritania"),
    'MS' => array("alpha3" => "MSR", "currencyId" => "XCD", "name" => "Montserrat"),
    'MT' => array("alpha3" => "MLT", "currencyId" => "EUR", "name" => "Malta"),
    'MU' => array("alpha3" => "MUS", "currencyId" => "MUR", "name" => "Mauritius"),
    'MV' => array("alpha3" => "MDV", "currencyId" => "MVR", "name" => "Maldives"),
    'MW' => array("alpha3" => "MWI", "currencyId" => "MWK", "name" => "Malawi"),
    'MX' => array("alpha3" => "MEX", "currencyId" => "MXN", "name" => "Mexico"),
    'MY' => array("alpha3" => "MYS", "currencyId" => "MYR", "name" => "Malaysia"),
    'MZ' => array("alpha3" => "MOZ", "currencyId" => "MZN", "name" => "Mozambique"),
    'NA' => array("alpha3" => "NAM", "currencyId" => "NAD", "name" => "Namibia"),
    'NC' => array("alpha3" => "NCL", "currencyId" => "XPF", "name" => "New Caledonia"),
    'NE' => array("alpha3" => "NER", "currencyId" => "XOF", "name" => "Niger"),
    'NG' => array("alpha3" => "NGA", "currencyId" => "NGN", "name" => "Nigeria"),
    'NI' => array("alpha3" => "NIC", "currencyId" => "NIO", "name" => "Nicaragua"),
    'NL' => array("alpha3" => "NLD", "currencyId" => "EUR", "name" => "Netherlands"),
    'NO' => array("alpha3" => "NOR", "currencyId" => "NOK", "name" => "Norway"),
    'NP' => array("alpha3" => "NPL", "currencyId" => "NPR", "name" => "Nepal"),
    'NR' => array("alpha3" => "NRU", "currencyId" => "AUD", "name" => "Nauru"),
    'NZ' => array("alpha3" => "NZL", "currencyId" => "NZD", "name" => "New Zealand"),
    'OM' => array("alpha3" => "OMN", "currencyId" => "OMR", "name" => "Oman"),
    'PA' => array("alpha3" => "PAN", "currencyId" => "PAB", "name" => "Panama"),
    'PE' => array("alpha3" => "PER", "currencyId" => "PEN", "name" => "Peru"),
    'PF' => array("alpha3" => "PYF", "currencyId" => "XPF", "name" => "French Polynesia"),
    'PG' => array("alpha3" => "PNG", "currencyId" => "PGK", "name" => "Papua New Guinea"),
    'PH' => array("alpha3" => "PHL", "currencyId" => "PHP", "name" => "Philippines"),
    'PK' => array("alpha3" => "PAK", "currencyId" => "PKR", "name" => "Pakistan"),
    'PL' => array("alpha3" => "POL", "currencyId" => "PLN", "name" => "Poland"),
    'PR' => array("alpha3" => "PRI", "currencyId" => "USD", "name" => "Puerto Rico"),
    'PT' => array("alpha3" => "PRT", "currencyId" => "EUR", "name" => "Portugal"),
    'PW' => array("alpha3" => "PLW", "currencyId" => "USD", "name" => "Palau"),
    'PY' => array("alpha3" => "PRY", "currencyId" => "PYG", "name" => "Paraguay"),
    'QA' => array("alpha3" => "QAT", "currencyId" => "QAR", "name" => "Qatar"),
    'RO' => array("alpha3" => "ROU", "currencyId" => "RON", "name" => "Romania"),
    'RS' => array("alpha3" => "SRB", "currencyId" => "RSD", "name" => "Serbia"),
    'RU' => array("alpha3" => "RUS", "currencyId" => "RUB", "name" => "Russia"),
    'RW' => array("alpha3" => "RWA", "currencyId" => "RWF", "name" => "Rwanda"),
    'SA' => array("alpha3" => "SAU", "currencyId" => "SAR", "name" => "Saudi Arabia"),
    'SB' => array("alpha3" => "SLB", "currencyId" => "SBD", "name" => "Solomon Islands"),
    'SC' => array("alpha3" => "SYC", "currencyId" => "SCR", "name" => "Seychelles"),
    'SD' => array("alpha3" => "SDN", "currencyId" => "SDG", "name" => "Sudan"),
    'SE' => array("alpha3" => "SWE", "currencyId" => "SEK", "name" => "Sweden"),
    'SG' => array("alpha3" => "SGP", "currencyId" => "SGD", "name" => "Singapore"),
    'SH' => array("alpha3" => "SHN", "currencyId" => "SHP", "name" => "Saint Helena"),
    'SI' => array("alpha3" => "SVN", "currencyId" => "EUR", "name" => "Slovenia"),
    'SK' => array("alpha3" => "SVK", "currencyId" => "EUR", "name" => "Slovakia"),
    'SL' => array("alpha3" => "SLE", "currencyId" => "SLL", "name" => "Sierra Leone"),
    'SM' => array("alpha3" => "SMR", "currencyId" => "EUR", "name" => "San Marino"),
    'SN' => array("alpha3" => "SEN", "currencyId" => "XOF", "name" => "Senegal"),
    'SO' => array("alpha3" => "SOM", "currencyId" => "SOS", "name" => "Somalia"),
    'SR' => array("alpha3" => "SUR", "currencyId" => "SRD", "name" => "Suriname"),
    'SS' => array("alpha3" => "SSD", "currencyId" => "SDG", "name" => "South Sudan"),
    'ST' => array("alpha3" => "STP", "currencyId" => "STD", "name" => "Sao Tome and Principe"),
    'SV' => array("alpha3" => "SLV", "currencyId" => "USD", "name" => "El Salvador"),
    'SY' => array("alpha3" => "SYR", "currencyId" => "SYP", "name" => "Syria"),
    'SZ' => array("alpha3" => "SWZ", "currencyId" => "SZL", "name" => "Swaziland"),
    'TD' => array("alpha3" => "TCD", "currencyId" => "XAF", "name" => "Chad"),
    'TG' => array("alpha3" => "TGO", "currencyId" => "XOF", "name" => "Togo"),
    'TH' => array("alpha3" => "THA", "currencyId" => "THB", "name" => "Thailand"),
    'TJ' => array("alpha3" => "TJK", "currencyId" => "TJS", "name" => "Tajikistan"),
    'TM' => array("alpha3" => "TKM", "currencyId" => "TMT", "name" => "Turkmenistan"),
    'TN' => array("alpha3" => "TUN", "currencyId" => "TND", "name" => "Tunisia"),
    'TO' => array("alpha3" => "TON", "currencyId" => "TOP", "name" => "Tonga"),
    'TR' => array("alpha3" => "TUR", "currencyId" => "TRY", "name" => "Turkey"),
    'TT' => array("alpha3" => "TTO", "currencyId" => "TTD", "name" => "Trinidad and Tobago"),
    'TV' => array("alpha3" => "TUV", "currencyId" => "AUD", "name" => "Tuvalu"),
    'TW' => array("alpha3" => "TWN", "currencyId" => "TWD", "name" => "Taiwan"),
    'TZ' => array("alpha3" => "TZA", "currencyId" => "TZS", "name" => "Tanzania"),
    'UA' => array("alpha3" => "UKR", "currencyId" => "UAH", "name" => "Ukraine"),
    'UG' => array("alpha3" => "UGA", "currencyId" => "UGX", "name" => "Uganda"),
    'US' => array("alpha3" => "USA", "currencyId" => "USD", "name" => "United States of America"),
    'UY' => array("alpha3" => "URY", "currencyId" => "UYU", "name" => "Uruguay"),
    'UZ' => array("alpha3" => "UZB", "currencyId" => "UZS", "name" => "Uzbekistan"),
    'VC' => array("alpha3" => "VCT", "currencyId" => "XCD", "name" => "Saint Vincent and the Grenadines"),
    'VE' => array("alpha3" => "VEN", "currencyId" => "VEF", "name" => "Venezuela"),
    'VN' => array("alpha3" => "VNM", "currencyId" => "VND", "name" => "Vietnam"),
    'VU' => array("alpha3" => "VUT", "currencyId" => "VUV", "name" => "Vanuatu"),
    'WF' => array("alpha3" => "WLF", "currencyId" => "XPF", "name" => "Wallis and Futuna Islands"),
    'WS' => array("alpha3" => "WSM", "currencyId" => "WST", "name" => "Samoa (Western)"),
    'YE' => array("alpha3" => "YEM", "currencyId" => "YER", "name" => "Yemen"),
    'ZA' => array("alpha3" => "ZAF", "currencyId" => "ZAR", "name" => "South Africa"),
    'ZM' => array("alpha3" => "ZMB", "currencyId" => "ZMW", "name" => "Zambia"),
    'ZW' => array("alpha3" => "ZWE", "currencyId" => "ZWL", "name" => "Zimbabwe")
  );

  static function isValidSymbol($symbol) {
    $symbol = mb_strtolower(trim($symbol), 'UTF-8');
    return self::$validSymbols[$symbol] ?: false;
  }

  static function isValidCurrency($currency) {
    $currency = mb_strtoupper(trim($currency), 'UTF-8');
    return self::$validCurrency[$currency] ? $currency : false;
  }

  static function autoCompose($currency, $all=false) {
    $out = array();
    $totChars = strlen($currency);
    if (($all && $totChars >= 0 && $totChars <= 3) || (!$all && $totChars > 0 && $totChars < 3))
      foreach (self::$validCurrency AS $key => $name)
        if (!$totChars || substr_compare($key, $currency, 0, $totChars) === 0)
          $out[$key] = true;

    return $out;
  }

  static function currencyName($currency) {
    return self::$validCurrency[$currency] ?: 'Unknown';
  }

  static function currencyFlag($currency) {
    return self::$emojiFlags[$currency] ?: '';
  }

  static function currencyDecimals($currency) {

    $num_decimals = 2;

    if ((getenv('lb2_digits') !== false) && (getenv('lb2_digits') > -2) && (getenv('lb2_digits') < 7)) {

      $digits = getenv('lb2_digits');

      if ($digits == -1) {
        if (array_key_exists($currency, self::$specialDigits)) {
          $num_decimals = self::$specialDigits[$currency];
        }
      }
      else {
        $num_decimals = $digits;
      }

      return $num_decimals;

    }

  }

}

// -----------------------------------------------

// ISO country code source: https://en.wikipedia.org/wiki/ISO_3166-1
// Emoji unicode codes: http://unicode.org/emoji/charts/full-emoji-list.html#country-flag

// An array to hold all the countries
$emoji_flags = array();

// Now, all the country flags as emojis
$emoji_flags["EU"] = "\u{1F1EA}\u{1F1FA}";
$emoji_flags["AD"] = "\u{1F1E6}\u{1F1E9}";
$emoji_flags["AE"] = "\u{1F1E6}\u{1F1EA}";
$emoji_flags["AF"] = "\u{1F1E6}\u{1F1EB}";
$emoji_flags["AG"] = "\u{1F1E6}\u{1F1EC}";
$emoji_flags["AI"] = "\u{1F1E6}\u{1F1EE}";
$emoji_flags["AL"] = "\u{1F1E6}\u{1F1F1}";
$emoji_flags["AM"] = "\u{1F1E6}\u{1F1F2}";
$emoji_flags["AO"] = "\u{1F1E6}\u{1F1F4}";
$emoji_flags["AQ"] = "\u{1F1E6}\u{1F1F6}";
$emoji_flags["AR"] = "\u{1F1E6}\u{1F1F7}";
$emoji_flags["AS"] = "\u{1F1E6}\u{1F1F8}";
$emoji_flags["AT"] = "\u{1F1E6}\u{1F1F9}";
$emoji_flags["AU"] = "\u{1F1E6}\u{1F1FA}";
$emoji_flags["AW"] = "\u{1F1E6}\u{1F1FC}";
$emoji_flags["AX"] = "\u{1F1E6}\u{1F1FD}";
$emoji_flags["AZ"] = "\u{1F1E6}\u{1F1FF}";
$emoji_flags["BA"] = "\u{1F1E7}\u{1F1E6}";
$emoji_flags["BB"] = "\u{1F1E7}\u{1F1E7}";
$emoji_flags["BD"] = "\u{1F1E7}\u{1F1E9}";
$emoji_flags["BE"] = "\u{1F1E7}\u{1F1EA}";
$emoji_flags["BF"] = "\u{1F1E7}\u{1F1EB}";
$emoji_flags["BG"] = "\u{1F1E7}\u{1F1EC}";
$emoji_flags["BH"] = "\u{1F1E7}\u{1F1ED}";
$emoji_flags["BI"] = "\u{1F1E7}\u{1F1EE}";
$emoji_flags["BJ"] = "\u{1F1E7}\u{1F1EF}";
$emoji_flags["BL"] = "\u{1F1E7}\u{1F1F1}";
$emoji_flags["BM"] = "\u{1F1E7}\u{1F1F2}";
$emoji_flags["BN"] = "\u{1F1E7}\u{1F1F3}";
$emoji_flags["BO"] = "\u{1F1E7}\u{1F1F4}";
$emoji_flags["BQ"] = "\u{1F1E7}\u{1F1F6}";
$emoji_flags["BR"] = "\u{1F1E7}\u{1F1F7}";
$emoji_flags["BS"] = "\u{1F1E7}\u{1F1F8}";
$emoji_flags["BT"] = "\u{1F1E7}\u{1F1F9}";
$emoji_flags["BV"] = "\u{1F1E7}\u{1F1FB}";
$emoji_flags["BW"] = "\u{1F1E7}\u{1F1FC}";
$emoji_flags["BY"] = "\u{1F1E7}\u{1F1FE}";
$emoji_flags["BZ"] = "\u{1F1E7}\u{1F1FF}";
$emoji_flags["CA"] = "\u{1F1E8}\u{1F1E6}";
$emoji_flags["CC"] = "\u{1F1E8}\u{1F1E8}";
$emoji_flags["CD"] = "\u{1F1E8}\u{1F1E9}";
$emoji_flags["CF"] = "\u{1F1E8}\u{1F1EB}";
$emoji_flags["CG"] = "\u{1F1E8}\u{1F1EC}";
$emoji_flags["CH"] = "\u{1F1E8}\u{1F1ED}";
$emoji_flags["CI"] = "\u{1F1E8}\u{1F1EE}";
$emoji_flags["CK"] = "\u{1F1E8}\u{1F1F0}";
$emoji_flags["CL"] = "\u{1F1E8}\u{1F1F1}";
$emoji_flags["CM"] = "\u{1F1E8}\u{1F1F2}";
$emoji_flags["CN"] = "\u{1F1E8}\u{1F1F3}";
$emoji_flags["CO"] = "\u{1F1E8}\u{1F1F4}";
$emoji_flags["CR"] = "\u{1F1E8}\u{1F1F7}";
$emoji_flags["CU"] = "\u{1F1E8}\u{1F1FA}";
$emoji_flags["CV"] = "\u{1F1E8}\u{1F1FB}";
$emoji_flags["CW"] = "\u{1F1E8}\u{1F1FC}";
$emoji_flags["CX"] = "\u{1F1E8}\u{1F1FD}";
$emoji_flags["CY"] = "\u{1F1E8}\u{1F1FE}";
$emoji_flags["CZ"] = "\u{1F1E8}\u{1F1FF}";
$emoji_flags["DE"] = "\u{1F1E9}\u{1F1EA}";
$emoji_flags["DG"] = "\u{1F1E9}\u{1F1EC}";
$emoji_flags["DJ"] = "\u{1F1E9}\u{1F1EF}";
$emoji_flags["DK"] = "\u{1F1E9}\u{1F1F0}";
$emoji_flags["DM"] = "\u{1F1E9}\u{1F1F2}";
$emoji_flags["DO"] = "\u{1F1E9}\u{1F1F4}";
$emoji_flags["DZ"] = "\u{1F1E9}\u{1F1FF}";
$emoji_flags["EC"] = "\u{1F1EA}\u{1F1E8}";
$emoji_flags["EE"] = "\u{1F1EA}\u{1F1EA}";
$emoji_flags["EG"] = "\u{1F1EA}\u{1F1EC}";
$emoji_flags["EH"] = "\u{1F1EA}\u{1F1ED}";
$emoji_flags["ER"] = "\u{1F1EA}\u{1F1F7}";
$emoji_flags["ES"] = "\u{1F1EA}\u{1F1F8}";
$emoji_flags["ET"] = "\u{1F1EA}\u{1F1F9}";
$emoji_flags["FI"] = "\u{1F1EB}\u{1F1EE}";
$emoji_flags["FJ"] = "\u{1F1EB}\u{1F1EF}";
$emoji_flags["FK"] = "\u{1F1EB}\u{1F1F0}";
$emoji_flags["FM"] = "\u{1F1EB}\u{1F1F2}";
$emoji_flags["FO"] = "\u{1F1EB}\u{1F1F4}";
$emoji_flags["FR"] = "\u{1F1EB}\u{1F1F7}";
$emoji_flags["GA"] = "\u{1F1EC}\u{1F1E6}";
$emoji_flags["GB"] = "\u{1F1EC}\u{1F1E7}";
$emoji_flags["GD"] = "\u{1F1EC}\u{1F1E9}";
$emoji_flags["GE"] = "\u{1F1EC}\u{1F1EA}";
$emoji_flags["GF"] = "\u{1F1EC}\u{1F1EB}";
$emoji_flags["GG"] = "\u{1F1EC}\u{1F1EC}";
$emoji_flags["GH"] = "\u{1F1EC}\u{1F1ED}";
$emoji_flags["GI"] = "\u{1F1EC}\u{1F1EE}";
$emoji_flags["GL"] = "\u{1F1EC}\u{1F1F1}";
$emoji_flags["GM"] = "\u{1F1EC}\u{1F1F2}";
$emoji_flags["GN"] = "\u{1F1EC}\u{1F1F3}";
$emoji_flags["GP"] = "\u{1F1EC}\u{1F1F5}";
$emoji_flags["GQ"] = "\u{1F1EC}\u{1F1F6}";
$emoji_flags["GR"] = "\u{1F1EC}\u{1F1F7}";
$emoji_flags["GS"] = "\u{1F1EC}\u{1F1F8}";
$emoji_flags["GT"] = "\u{1F1EC}\u{1F1F9}";
$emoji_flags["GU"] = "\u{1F1EC}\u{1F1FA}";
$emoji_flags["GW"] = "\u{1F1EC}\u{1F1FC}";
$emoji_flags["GY"] = "\u{1F1EC}\u{1F1FE}";
$emoji_flags["HK"] = "\u{1F1ED}\u{1F1F0}";
$emoji_flags["HM"] = "\u{1F1ED}\u{1F1F2}";
$emoji_flags["HN"] = "\u{1F1ED}\u{1F1F3}";
$emoji_flags["HR"] = "\u{1F1ED}\u{1F1F7}";
$emoji_flags["HT"] = "\u{1F1ED}\u{1F1F9}";
$emoji_flags["HU"] = "\u{1F1ED}\u{1F1FA}";
$emoji_flags["ID"] = "\u{1F1EE}\u{1F1E9}";
$emoji_flags["IE"] = "\u{1F1EE}\u{1F1EA}";
$emoji_flags["IL"] = "\u{1F1EE}\u{1F1F1}";
$emoji_flags["IM"] = "\u{1F1EE}\u{1F1F2}";
$emoji_flags["IN"] = "\u{1F1EE}\u{1F1F3}";
$emoji_flags["IO"] = "\u{1F1EE}\u{1F1F4}";
$emoji_flags["IQ"] = "\u{1F1EE}\u{1F1F6}";
$emoji_flags["IR"] = "\u{1F1EE}\u{1F1F7}";
$emoji_flags["IS"] = "\u{1F1EE}\u{1F1F8}";
$emoji_flags["IT"] = "\u{1F1EE}\u{1F1F9}";
$emoji_flags["JE"] = "\u{1F1EF}\u{1F1EA}";
$emoji_flags["JM"] = "\u{1F1EF}\u{1F1F2}";
$emoji_flags["JO"] = "\u{1F1EF}\u{1F1F4}";
$emoji_flags["JP"] = "\u{1F1EF}\u{1F1F5}";
$emoji_flags["KE"] = "\u{1F1F0}\u{1F1EA}";
$emoji_flags["KG"] = "\u{1F1F0}\u{1F1EC}";
$emoji_flags["KH"] = "\u{1F1F0}\u{1F1ED}";
$emoji_flags["KI"] = "\u{1F1F0}\u{1F1EE}";
$emoji_flags["KM"] = "\u{1F1F0}\u{1F1F2}";
$emoji_flags["KN"] = "\u{1F1F0}\u{1F1F3}";
$emoji_flags["KP"] = "\u{1F1F0}\u{1F1F5}";
$emoji_flags["KR"] = "\u{1F1F0}\u{1F1F7}";
$emoji_flags["KW"] = "\u{1F1F0}\u{1F1FC}";
$emoji_flags["KY"] = "\u{1F1F0}\u{1F1FE}";
$emoji_flags["KZ"] = "\u{1F1F0}\u{1F1FF}";
$emoji_flags["LA"] = "\u{1F1F1}\u{1F1E6}";
$emoji_flags["LB"] = "\u{1F1F1}\u{1F1E7}";
$emoji_flags["LC"] = "\u{1F1F1}\u{1F1E8}";
$emoji_flags["LI"] = "\u{1F1F1}\u{1F1EE}";
$emoji_flags["LK"] = "\u{1F1F1}\u{1F1F0}";
$emoji_flags["LR"] = "\u{1F1F1}\u{1F1F7}";
$emoji_flags["LS"] = "\u{1F1F1}\u{1F1F8}";
$emoji_flags["LT"] = "\u{1F1F1}\u{1F1F9}";
$emoji_flags["LU"] = "\u{1F1F1}\u{1F1FA}";
$emoji_flags["LV"] = "\u{1F1F1}\u{1F1FB}";
$emoji_flags["LY"] = "\u{1F1F1}\u{1F1FE}";
$emoji_flags["MA"] = "\u{1F1F2}\u{1F1E6}";
$emoji_flags["MC"] = "\u{1F1F2}\u{1F1E8}";
$emoji_flags["MD"] = "\u{1F1F2}\u{1F1E9}";
$emoji_flags["ME"] = "\u{1F1F2}\u{1F1EA}";
$emoji_flags["MF"] = "\u{1F1F2}\u{1F1EB}";
$emoji_flags["MG"] = "\u{1F1F2}\u{1F1EC}";
$emoji_flags["MH"] = "\u{1F1F2}\u{1F1ED}";
$emoji_flags["MK"] = "\u{1F1F2}\u{1F1F0}";
$emoji_flags["ML"] = "\u{1F1F2}\u{1F1F1}";
$emoji_flags["MM"] = "\u{1F1F2}\u{1F1F2}";
$emoji_flags["MN"] = "\u{1F1F2}\u{1F1F3}";
$emoji_flags["MO"] = "\u{1F1F2}\u{1F1F4}";
$emoji_flags["MP"] = "\u{1F1F2}\u{1F1F5}";
$emoji_flags["MQ"] = "\u{1F1F2}\u{1F1F6}";
$emoji_flags["MR"] = "\u{1F1F2}\u{1F1F7}";
$emoji_flags["MS"] = "\u{1F1F2}\u{1F1F8}";
$emoji_flags["MT"] = "\u{1F1F2}\u{1F1F9}";
$emoji_flags["MU"] = "\u{1F1F2}\u{1F1FA}";
$emoji_flags["MV"] = "\u{1F1F2}\u{1F1FB}";
$emoji_flags["MW"] = "\u{1F1F2}\u{1F1FC}";
$emoji_flags["MX"] = "\u{1F1F2}\u{1F1FD}";
$emoji_flags["MY"] = "\u{1F1F2}\u{1F1FE}";
$emoji_flags["MZ"] = "\u{1F1F2}\u{1F1FF}";
$emoji_flags["NA"] = "\u{1F1F3}\u{1F1E6}";
$emoji_flags["NC"] = "\u{1F1F3}\u{1F1E8}";
$emoji_flags["NE"] = "\u{1F1F3}\u{1F1EA}";
$emoji_flags["NF"] = "\u{1F1F3}\u{1F1EB}";
$emoji_flags["NG"] = "\u{1F1F3}\u{1F1EC}";
$emoji_flags["NI"] = "\u{1F1F3}\u{1F1EE}";
$emoji_flags["NL"] = "\u{1F1F3}\u{1F1F1}";
$emoji_flags["NO"] = "\u{1F1F3}\u{1F1F4}";
$emoji_flags["NP"] = "\u{1F1F3}\u{1F1F5}";
$emoji_flags["NR"] = "\u{1F1F3}\u{1F1F7}";
$emoji_flags["NU"] = "\u{1F1F3}\u{1F1FA}";
$emoji_flags["NZ"] = "\u{1F1F3}\u{1F1FF}";
$emoji_flags["OM"] = "\u{1F1F4}\u{1F1F2}";
$emoji_flags["PA"] = "\u{1F1F5}\u{1F1E6}";
$emoji_flags["PE"] = "\u{1F1F5}\u{1F1EA}";
$emoji_flags["PF"] = "\u{1F1F5}\u{1F1EB}";
$emoji_flags["PG"] = "\u{1F1F5}\u{1F1EC}";
$emoji_flags["PH"] = "\u{1F1F5}\u{1F1ED}";
$emoji_flags["PK"] = "\u{1F1F5}\u{1F1F0}";
$emoji_flags["PL"] = "\u{1F1F5}\u{1F1F1}";
$emoji_flags["PM"] = "\u{1F1F5}\u{1F1F2}";
$emoji_flags["PN"] = "\u{1F1F5}\u{1F1F3}";
$emoji_flags["PR"] = "\u{1F1F5}\u{1F1F7}";
$emoji_flags["PS"] = "\u{1F1F5}\u{1F1F8}";
$emoji_flags["PT"] = "\u{1F1F5}\u{1F1F9}";
$emoji_flags["PW"] = "\u{1F1F5}\u{1F1FC}";
$emoji_flags["PY"] = "\u{1F1F5}\u{1F1FE}";
$emoji_flags["QA"] = "\u{1F1F6}\u{1F1E6}";
$emoji_flags["RE"] = "\u{1F1F7}\u{1F1EA}";
$emoji_flags["RO"] = "\u{1F1F7}\u{1F1F4}";
$emoji_flags["RS"] = "\u{1F1F7}\u{1F1F8}";
$emoji_flags["RU"] = "\u{1F1F7}\u{1F1FA}";
$emoji_flags["RW"] = "\u{1F1F7}\u{1F1FC}";
$emoji_flags["SA"] = "\u{1F1F8}\u{1F1E6}";
$emoji_flags["SB"] = "\u{1F1F8}\u{1F1E7}";
$emoji_flags["SC"] = "\u{1F1F8}\u{1F1E8}";
$emoji_flags["SD"] = "\u{1F1F8}\u{1F1E9}";
$emoji_flags["SE"] = "\u{1F1F8}\u{1F1EA}";
$emoji_flags["SG"] = "\u{1F1F8}\u{1F1EC}";
$emoji_flags["SH"] = "\u{1F1F8}\u{1F1ED}";
$emoji_flags["SI"] = "\u{1F1F8}\u{1F1EE}";
$emoji_flags["SJ"] = "\u{1F1F8}\u{1F1EF}";
$emoji_flags["SK"] = "\u{1F1F8}\u{1F1F0}";
$emoji_flags["SL"] = "\u{1F1F8}\u{1F1F1}";
$emoji_flags["SM"] = "\u{1F1F8}\u{1F1F2}";
$emoji_flags["SN"] = "\u{1F1F8}\u{1F1F3}";
$emoji_flags["SO"] = "\u{1F1F8}\u{1F1F4}";
$emoji_flags["SR"] = "\u{1F1F8}\u{1F1F7}";
$emoji_flags["SS"] = "\u{1F1F8}\u{1F1F8}";
$emoji_flags["ST"] = "\u{1F1F8}\u{1F1F9}";
$emoji_flags["SV"] = "\u{1F1F8}\u{1F1FB}";
$emoji_flags["SX"] = "\u{1F1F8}\u{1F1FD}";
$emoji_flags["SY"] = "\u{1F1F8}\u{1F1FE}";
$emoji_flags["SZ"] = "\u{1F1F8}\u{1F1FF}";
$emoji_flags["TC"] = "\u{1F1F9}\u{1F1E8}";
$emoji_flags["TD"] = "\u{1F1F9}\u{1F1E9}";
$emoji_flags["TF"] = "\u{1F1F9}\u{1F1EB}";
$emoji_flags["TG"] = "\u{1F1F9}\u{1F1EC}";
$emoji_flags["TH"] = "\u{1F1F9}\u{1F1ED}";
$emoji_flags["TJ"] = "\u{1F1F9}\u{1F1EF}";
$emoji_flags["TK"] = "\u{1F1F9}\u{1F1F0}";
$emoji_flags["TL"] = "\u{1F1F9}\u{1F1F1}";
$emoji_flags["TM"] = "\u{1F1F9}\u{1F1F2}";
$emoji_flags["TN"] = "\u{1F1F9}\u{1F1F3}";
$emoji_flags["TO"] = "\u{1F1F9}\u{1F1F4}";
$emoji_flags["TR"] = "\u{1F1F9}\u{1F1F7}";
$emoji_flags["TT"] = "\u{1F1F9}\u{1F1F9}";
$emoji_flags["TV"] = "\u{1F1F9}\u{1F1FB}";
$emoji_flags["TW"] = "\u{1F1F9}\u{1F1FC}";
$emoji_flags["TZ"] = "\u{1F1F9}\u{1F1FF}";
$emoji_flags["UA"] = "\u{1F1FA}\u{1F1E6}";
$emoji_flags["UG"] = "\u{1F1FA}\u{1F1EC}";
$emoji_flags["UM"] = "\u{1F1FA}\u{1F1F2}";
$emoji_flags["US"] = "\u{1F1FA}\u{1F1F8}";
$emoji_flags["UY"] = "\u{1F1FA}\u{1F1FE}";
$emoji_flags["UZ"] = "\u{1F1FA}\u{1F1FF}";
$emoji_flags["VA"] = "\u{1F1FB}\u{1F1E6}";
$emoji_flags["VC"] = "\u{1F1FB}\u{1F1E8}";
$emoji_flags["VE"] = "\u{1F1FB}\u{1F1EA}";
$emoji_flags["VG"] = "\u{1F1FB}\u{1F1EC}";
$emoji_flags["VI"] = "\u{1F1FB}\u{1F1EE}";
$emoji_flags["VN"] = "\u{1F1FB}\u{1F1F3}";
$emoji_flags["VU"] = "\u{1F1FB}\u{1F1FA}";
$emoji_flags["WF"] = "\u{1F1FC}\u{1F1EB}";
$emoji_flags["WS"] = "\u{1F1FC}\u{1F1F8}";
$emoji_flags["XK"] = "\u{1F1FD}\u{1F1F0}";
$emoji_flags["YE"] = "\u{1F1FE}\u{1F1EA}";
$emoji_flags["YT"] = "\u{1F1FE}\u{1F1F9}";
$emoji_flags["ZA"] = "\u{1F1FF}\u{1F1E6}";
$emoji_flags["ZM"] = "\u{1F1FF}\u{1F1F2}";
$emoji_flags["ZW"] = "\u{1F1FF}\u{1F1FC}";

// -----------------------------------------------

?>