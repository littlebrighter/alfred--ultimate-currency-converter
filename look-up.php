<?php

// ---------------------------------------------------------------------------------------------------

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// ---------------------------------------------------------------------------------------------------

require('libs/e4Currency.php');

// ---------------------------------------------------------------------------------------------------

$param = $argv[1];

// ---------------------------------------------------------------------------------------------------

$result = array();
$i = 0;

// ---------------------------------------------------------------------------------------------------

if ($argv[1] != '') {

  define('KEYWORD_CURRENCY', 'BD1F8719-5CF7-4BFB-BCD5-403E45BC16F7');
  define('KEYWORD_LOOKUP', 'B04157B8-7BF9-4D82-89D4-2A47C439E32F');
  define('KEYWORD_SET_FROM', '49937CBC-15F2-40F2-869E-05389B3132B5');
  define('KEYWORD_SET_TO', '4582263F-3BD2-4C8C-BE92-885558B2AB09');

  $f = explode("\n", trim(file_get_contents('info.plist')));
  $keyword = '';
  $keywords = array();
  foreach ($f as $k => $v) {
    if (trim($v) == '<key>keyword</key>') {
      $keyword = str_replace('<string>', '', str_replace('</string>', '', trim($f[$k+1])));
    }
    elseif ((trim($v) == '<key>uid</key>') && ($keyword != '')) {
      $uid = str_replace('<string>', '', str_replace('</string>', '', trim($f[$k+1])));
      $keywords[$uid] = $keyword;
      $keyword = '';
    }
  }

  $cur = str_replace('currency-select ', '', $argv[1]);

  $result[$i]['title'] = '← back';
  $result[$i]['arg'] = $keywords[KEYWORD_LOOKUP].' '.$cur;
  $result[$i]['icon']['path'] = 'icon-lookup.png';
  $result[$i]['valid'] = true;
  $i++;

  $result[$i]['title'] = 'use in next query';
  $result[$i]['arg'] = $keywords[KEYWORD_CURRENCY].' '.$cur;
  $result[$i]['valid'] = true;
  $i++;

  $result[$i]['title'] = 'Set default from';
  $result[$i]['subtitle'] = 'please confirm new selection in next window';
  $result[$i]['arg'] = $keywords[KEYWORD_SET_FROM].' '.$cur;
  $result[$i]['icon']['path'] = 'icon-settings.png';
  $result[$i]['valid'] = true;
  $i++;

  $result[$i]['title'] = 'Set default to';
  $result[$i]['subtitle'] = 'please confirm new selection in next window';
  $result[$i]['arg'] = $keywords[KEYWORD_SET_TO].' '.$cur;
  $result[$i]['icon']['path'] = 'icon-settings.png';
  $result[$i]['valid'] = true;
  $i++;


}

// ---------------------------------------------------------------------------------------------------
else {

  foreach (e4Currency::$validCurrency as $k => $v) {

    $currency_id = $k;
    $currency_name = $v;

    $currency_symbol = array_search($currency_id, e4Currency::$validSymbols);

    $s = '';
    $num = 0;
    foreach (e4Currency::$countries as $kk => $vv) {
      if ($vv['currencyId'] == $currency_id) {
        $num++;
        $vv['id'].'&nbsp';
        $s = 'used in '.$vv['name'].' '.$emoji_flags[$kk];
      }
    }

    if ($num > 1) {
      if (array_key_exists($currency_id, e4Currency::$special_countries)) {
        $s = 'used in '.e4Currency::$countries[e4Currency::$special_countries[$currency_id]]['name'].' '.$emoji_flags[e4Currency::$special_countries[$currency_id]];
      }
      else {
        $s = 'used in several countries';
      }
      $num = 1;
    }
    elseif ($num == 0) {
      $result[$i]['subtitle'] = 'not used in any country in Alfred database';
    }

    if ($num == 1) {
      $result[$i]['subtitle'] = $s;
    }

    $result[$i]['title'] = $currency_id.' – '.$currency_name.($currency_symbol ? ' ('.$currency_symbol.')' : '');
    $result[$i]['match'] = $currency_id.' '.$currency_name.' '.($currency_symbol ? $currency_symbol : '');
    $result[$i]['arg'] = 'currency-select '.$currency_id;
    $result[$i]['text']['largetype'] = $currency_id.' = '.$currency_name."\n".$result[$i]['subtitle'];
    $result[$i]['text']['copy'] = $currency_id;
    $result[$i]['valid'] = true;
    $i++;

  }

  foreach (e4Currency::$countries as $k => $v) {

    $result[$i]['title'] = $k.' – '.$v['name'].str_replace('  ', ' ', ' '.$emoji_flags[$k].' ').'('.$v['alpha3'].')';
    $result[$i]['subtitle'] = 'uses '.$v['currencyId'].' - '.e4Currency::currencyName($v['currencyId']);
    $result[$i]['match'] = $k.' '.$v['alpha3'].' '.$v['currencyId'].' '.$v['name'];
    $result[$i]['arg'] = 'currency-select '.$v['currencyId'];
    $result[$i]['text']['largetype'] = $v['name'].str_replace('  ', ' ', ' '.$emoji_flags[$k].' ')."\n".'uses '.$v['currencyId'].' = '.e4Currency::currencyName($v['currencyId']);
    $result[$i]['text']['copy'] = $v['currencyId'];
    $result[$i]['valid'] = true;
    $i++;

  }

}

// ---------------------------------------------------------------------------------------------------

echo '{"items": '.json_encode($result).'}';

// ---------------------------------------------------------------------------------------------------

?>