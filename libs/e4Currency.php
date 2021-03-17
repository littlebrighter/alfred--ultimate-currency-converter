<?php

class e4Currency {

  protected $input = null;
  protected $options = array();

  public function __construct($input=null) {
    $this->setInput($input);
  }

  public function setInput($input=null) {
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
    if (array_key_exists($currency, self::$specialDigits)) {
      $num_decimals = self::$specialDigits[$currency];
    }
    return $num_decimals;
  }


}

?>