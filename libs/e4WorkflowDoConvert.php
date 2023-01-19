<?php

#[AllowDynamicProperties]
class e4WorkflowDoConvert extends e4WorkflowCommands {

  public function run($inQuery, $args) {

    // Parsing user request
    $inQuery = $inQuery;
    $inDefaultFrom = $this->app->getDefault('from');
    $inDefaultTo = $this->app->getDefault('to');
    $out = array();

    $objQuery = new e4QueryParser($this->app, $inQuery, $inDefaultFrom, $inDefaultTo);

    if ($objQuery->parse()) {
      $tmpFrom = $objQuery->getFrom();
      $tmpTo = $objQuery->getTo();
      $tmpAmount = $objQuery->getAmount();

      if (count($tmpFrom) > 1) {
        foreach ($tmpFrom AS $currency) {
          $suggest = $objQuery->getSuggestion('from', $currency);
          $out[] = array(
            'uid' => $currency,
            'arg' => 'none',
            'title' => $suggest,
            'subtitle' => e4Currency::currencyName($currency).' ('.$currency.')',
            'autocomplete' => $suggest,
            'icon' => 'icon.png',
            'valid' => 'no');
        }
      }
      else if (count($tmpTo) > 1) {
        foreach ($tmpTo AS $currency) {
          $suggest = $objQuery->getSuggestion('to', $currency);
          $out[] = array(
            'uid' => $currency,
            'arg' => 'none',
            'title' => $suggest,
            'subtitle' => e4Currency::currencyName($currency).' ('.$currency.')',
            'autocomplete' => $suggest,
            'icon' => 'icon.png',
            'valid' => 'no');
        }
      }
      else {
        $tmpResponse = new e4QuerySend($this->app, $tmpAmount, $tmpFrom[0], $tmpTo[0]);
        if ($tmpResponse->sendRequest()) {

          $dec_point = '.';
          $thousands_sep = ',';
          $language = 'en';

          if ((getenv('lb_language')) and (getenv('lb_language') == 'de')) {
            $language = 'de';
            $dec_point = ',';
            $thousands_sep = '.';
          }

          $out[] = array(
            'uid' => 'none',
            'arg' => ($language == 'de' ? str_replace('.', $dec_point, $tmpResponse->getToAmount()) : $tmpResponse->getToAmount()),
            'title' => implode(' ', array(
              number_format($tmpResponse->getFromAmount(), e4Currency::currencyDecimals($tmpResponse->getFromCurrency()), $dec_point, $thousands_sep),
              $tmpResponse->getFromCurrency(),
              '→',
              number_format($tmpResponse->getToAmount(), e4Currency::currencyDecimals($tmpResponse->getToCurrency()), $dec_point, $thousands_sep),
              $tmpResponse->getToCurrency()
            )),
            'subtitle' => implode(' ', array(
              e4Currency::currencyName($tmpResponse->getFromCurrency()),
              e4Currency::currencyFlag($tmpResponse->getFromCurrency()),
              '→',
              e4Currency::currencyName($tmpResponse->getToCurrency()),
              e4Currency::currencyFlag($tmpResponse->getToCurrency())
            )),
            'icon' => 'icon.png',
            'valid' => 'yes');
        }
      }
    }

    // Invalid response matched
    if (!count($out))
      $out[] = array(
        'uid' => 'none',
        'arg' => 'none',
        'title' => 'Invalid query '.$inQuery,
        'subtitle' => 'Try ‘23 €‘, ‘23 € to $‘ or ‘€‘ and convert your currency',
        'icon' => 'icon.png',
        'valid' => 'no'
      );

    return $out;

  }
}

?>
