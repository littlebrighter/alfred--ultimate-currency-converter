<?php

class e4QuerySend {

  protected $app = null;
  protected $amount = 1;
  protected $from = null;
  protected $to = null;
  protected $responseFromAmount = null;
  protected $responseFromCurrency = null;
  protected $responseToAmount = null;
  protected $responseToCurrency = null;
  protected $valid = false;

  public function __construct($app, $amount, $from, $to) {
    $this->app = $app;
    $this->amount = $this->responseFromAmount = $amount;
    $this->from = $this->responseFromCurrency = $from;
    $this->to = $this->responseToCurrency = $to;
  }

  public function getFromAmount() {
    return $this->responseFromAmount;
  }

  public function getFromCurrency() {
    return $this->responseFromCurrency;
  }

  public function getToAmount() {
    return $this->responseToAmount;
  }

  public function getToCurrency() {
    return $this->responseToCurrency;
  }

  public function isValid() {
    return $this->valid;
  }

  public function sendRequest() {

    /*
    $response = $this->app->sendHTTPRequest('https://free.currconv.com/api/v7/convert?'.http_build_query(array(
      'apiKey' => $_ENV['lb_freecurrencyconverter_api_key'],
      'q' => $this->from.'_'.$this->to,
      'compact' => 'ultra')));

    $res_obj = json_decode($response);

    if ($response) {
      $this->responseFromAmount = $this->amount * 1.0;
      $this->responseFromCurrency = $this->from;
      $this->responseToAmount = $this->amount * 1.0 * $res_obj->{$this->from.'_'.$this->to};
      $this->responseToCurrency = $this->to;

      return $this->valid = true;
    }
    return $this->valid = false;
    */

    $this->responseFromAmount = 33.579 * 1.0;
    $this->responseFromCurrency = 'OMR';
    $this->responseToAmount = 34697.154;
    $this->responseToCurrency = 'USD';
    return $this->valid = true;

  }

}

?>