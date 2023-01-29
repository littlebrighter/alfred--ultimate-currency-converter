<?php

#[AllowDynamicProperties]
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

    if ((getenv('lb2_api_service') !== false) && (getenv('lb2_api_key') !== false)) {

      if ((getenv('lb2_api_service') == 'currencyconverterapi.com')) {

      // ---------------------------------------

      $response = $this->app->sendHTTPRequest('https://free.currconv.com/api/v7/convert?'.http_build_query(array(
        'apiKey' => getenv('lb2_api_key'),
        'q' => $this->from.'_'.$this->to,
        'compact' => 'ultra')));

      fwrite(STDERR, print_r($response, true));
      $res_obj = json_decode($response);

      fwrite(STDERR, print_r($res_obj, true));

      if ($response) {
        $this->responseFromAmount = $this->amount * 1.0;
        $this->responseFromCurrency = $this->from;
        $this->responseToAmount = $this->amount * 1.0 * $res_obj->{$this->from.'_'.$this->to};
        $this->responseToCurrency = $this->to;

        return $this->valid = true;
      }
      return $this->valid = false;

    }

    else if ((getenv('lb2_api_service') == 'exchangeratesapi.io')) {

      $response = $this->app->sendHTTPRequest('http:///api.exchangeratesapi.io/v1/latest?'.http_build_query(array(
        'access_key' => getenv('lb2_api_key'),
        'base' => $this->from,
        'symbols' => $this->to)));

      $res_obj = json_decode($response, true);

      fwrite(STDERR, print_r($res_obj, true));

      if ($res_obj['success'] == 1) {
        $this->responseFromAmount = $this->amount * 1.0;
        $this->responseFromCurrency = $this->from;
        $this->responseToAmount = $this->amount * 1.0 * $res_obj['rates'][$this->to];
        $this->responseToCurrency = $this->to;

        return $this->valid = true;
      }
      return $this->valid = false;

    }

    else if ((getenv('lb2_api_service') == 'getgeoapi.com')) {

      $response = $this->app->sendHTTPRequest('https://api.getgeoapi.com/v2/currency/convert?'.http_build_query(array(
        'api_key' => getenv('lb2_api_key'),
        'from' => $this->from,
        'to' => $this->to,
        'format' => 'json')));

      $res_obj = json_decode($response, true);

      fwrite(STDERR, print_r($res_obj, true));

      if ($res_obj['status'] == 'success') {
        $this->responseFromAmount = $this->amount * 1.0;
        $this->responseFromCurrency = $this->from;
        $this->responseToAmount = $this->amount * 1.0 * $res_obj['rates'][$this->to]['rate'];
        $this->responseToCurrency = $this->to;

        return $this->valid = true;
      }
      return $this->valid = false;

    }

    }

  }

}

?>