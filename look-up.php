<?php

// ---------------------------------------------------------------------------------------------------

require('libs/e4Currency.php');

// ---------------------------------------------------------------------------------------------------

$param = $argv[1];

// ---------------------------------------------------------------------------------------------------

$result = array();
$i = 0;

// ---------------------------------------------------------------------------------------------------

if ($argv[1] != '') {

  $cur = str_replace('currency-select ', '', $argv[1]);

  $result[$i]['title'] = '← back';
  $result[$i]['arg'] = 'currency-look-up '.$cur;
  $result[$i]['valid'] = true;
  $i++;

  $result[$i]['title'] = 'use in next query';
  $result[$i]['arg'] = 'cur '.$cur;
  $result[$i]['valid'] = true;
  $i++;

  $result[$i]['title'] = 'Set default from';
  $result[$i]['subtitle'] = 'please confirm new selection in next window';
  $result[$i]['arg'] = 'currency-set-from '.$cur;
  $result[$i]['icon']['path'] = 'icon-settings.png';
  $result[$i]['valid'] = true;
  $i++;

  $result[$i]['title'] = 'Set default to';
  $result[$i]['subtitle'] = 'please confirm new selection in next window';
  $result[$i]['arg'] = 'currency-set-to '.$cur;
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