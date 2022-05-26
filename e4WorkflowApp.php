<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// ------------------------------------------------------------------------------------

//function __autoload($className) {
spl_autoload_register(function ($className) {
  include 'libs/'.$className.'.php';
});

// ------------------------------------------------------------------------------------

// check for update

if (!file_exists(getenv('alfred_workflow_cache'))) mkdir(getenv('alfred_workflow_cache'), 0777, true);

// if no previous update status available or old status is older than 24 h --> get new status
$v = '';
if ( (!file_exists(getenv('alfred_workflow_cache').'/update')) ||
     ((time() - filemtime(getenv('alfred_workflow_cache').'/update')) > 60*60*24 ) ||
     (file_get_contents(getenv('alfred_workflow_cache').'/update') == '') ) {
  $v = @file_get_contents('https://littlebrighter.erevo.io/alfred/?repo='.str_replace('io.erevo.littlebrighter.', '', getenv('alfred_workflow_bundleid')));
  file_put_contents(getenv('alfred_workflow_cache').'/update', $v);
}
else {
  $v = @file_get_contents(getenv('alfred_workflow_cache').'/update');
}

if (($v) && (version_compare(getenv('alfred_workflow_version'), str_replace('v', '', $v)) == -1)) {

  exec('osascript -e \'tell application id "com.runningwithcrayons.Alfred" to run trigger "update-available" in workflow "'.getenv('alfred_workflow_bundleid').'" with argument "new workflow version is available ('.$v.') for '.getenv('alfred_workflow_name').'"\'');

  $result[$i]['title'] = getenv('alfred_workflow_name').': a new workflow version is available ('.$v.')';
  $result[$i]['subtitle'] = 'you are running v'.getenv('alfred_workflow_version').' – select to open download page';
  $result[$i]['icon']['path'] = 'icon-update.png';
  $result[$i]['autocomplete'] = '--download-update';
  $result[$i]['arg'] = '--download-update';
  $result[$i]['valid'] = true;
  echo '{"items": '.json_encode($result).'}';

  exit;

}

// ------------------------------------------------------------------------------------

// Loading application config
$app = new e4WorkflowApp();
echo $app->run($argv);

// ------------------------------------------------------------------------------------

// e4Workflow library
class e4WorkflowApp {

  public $appDefaultCommand = false;
  public $appCommands = array();

  public $name = 'global';
  public $id = 'global';
  public $version = 1.0;
  public $ttl = 3600;

  public $defaults = array();
  protected $defaultsHash;

  public $cachePath = false;
  private $cacheLoaded = false;
  protected $configLoaded = false;
  public $configPath = false;

  public function __construct() {

    // Defaults einstellen
    $this->setName();
    $this->setVersion();
    $this->setCacheTTL();

    // Loading default configuration
    $this->addDefault('from', 'EUR');
    $this->addDefault('to', 'USD');

    // Loading app commands
    $this->addCommand('convert',
        array('id' => 'convert',
              'cmd' => 'Convert',
              'title' => 'Ultimate Currency Converter',
              'subtitle' => 'Try ‘23 €‘, ‘23 € to $‘ or ‘€‘ and convert your currency',
              'default' => true));

    $this->addCommand('set from',
        array('id' => 'set from',
              'cmd' => 'Set',
              'title' => 'Ultimate Currency Converter – set default from',
              'subtitle' => 'Change default currency used for ’from conversions‘'));

    $this->addCommand('set to',
        array('id' => 'set to',
              'cmd' => 'Set',
              'title' => 'Ultimate Currency Converter – set default to',
              'subtitle' => 'Change default currency used for to conversions‘'));

    // Callback functions on application exit
    register_shutdown_function(array($this, 'exportConfig'));
    register_shutdown_function(array($this, 'clearCacheFiles'));

  }

  public function setName() {

    $this->name =getenv('alfred_workflow_name');
    $this->id = getenv('alfred_workflow_bundleid');

    $this->cachePath = getenv('alfred_workflow_cache').'/';
    $this->configPath = getenv('alfred_workflow_data').'/';

    @mkdir($this->cachePath, 0777, true);
    @mkdir($this->configPath, 0777, true);

  }

  public function setVersion() {
    $this->version = getenv('alfred_workflow_version');
  }

  public function setCacheTTL($ttl=3600) {
    $this->ttl = $ttl ?: 3600;
  }

  public function addCommand($key, $configs) {
    $configs['icon'] = $configs['icon'] ?: 'icon.png';
    $configs['valid'] = $configs['valid'] ? 'yes' : 'no';
    if ($configs['default'] === true)
    $this->appDefaultCommand = $configs['id'];
    $this->appCommands[$key] = $configs;
  }

  public function run($argv) {
    array_shift($argv);
    $query = trim($argv[0]) ?: '';

    $objects = array();
    $out = array();

    // Reading and executing input query
    if ($argv[1] != 'default' && count($this->appCommands) > 0)
    foreach ($this->appCommands AS $key => $config)
    if (!$query || preg_match('/^'.preg_quote(substr($query, 0, strlen($key)), '/').'/i', $key))
    $objects[] = $this->loadCommander($key, $query);

    // Filter results and running requests
    if (!count($objects) && $this->appDefaultCommand !== false)
    $out = $this->loadCommander($this->appDefaultCommand, $query)->run($query, $argv);
    elseif (count($objects) == 1 && ($data = $objects[0]->getQueryMatch()) !== false)
    $out = $objects[0]->run($data[1], $argv);
    elseif (count($objects) > 0)
    foreach($objects AS $object)
    $out[] = $object->getCommandSuggest();

    // Transform output array to XML
    if (!is_array($out))
    return $out;

    $xmlObject = new SimpleXMLElement("<items></items>");
    $tmpTypes = array(
      'uid' => 'addAttribute',
      'arg' => 'addAttribute',
      'valid' => 'addAttribute',
      'autocomplete' => 'addAttribute'
    );
    foreach($out AS $rows) {
      $objItem = $xmlObject->addChild('item');
      foreach ($rows AS $key => $value) {
        $objItem->{ $tmpTypes[$key] ?: 'addChild' }($key, $value);
      }
    }
    return $xmlObject->asXML();

  }

  public function loadCommander($id, $query) {
    $config = $this->appCommands[$id];
    $className = 'e4WorkflowDo'.$config['cmd'];
    return new $className($this, $query, $config);
  }

  public function addDefault($key, $value) {
    if (!$this->getDefault($key))
    $this->setDefault($key, $value);
  }

  public function getDefault($key) {
    $this->importConfig();
    return $this->defaults[$key];
  }

  public function setDefault($key, $value) {
    $this->importConfig();
    $this->defaults[$key] = $value;
  }

  public function importConfig() {
    if ($this->cacheLoaded)
    return false;
    $this->cacheLoaded = true;
    $content = @file_get_contents($this->configPath.'config.json');
    $this->defaults = @json_decode($content, true) ?: array();
    $this->defaultsHash = md5($content) ?: '';
    return true;
  }

  public function exportConfig() {
    $content = json_encode($this->defaults);
    if (md5($content) == $this->defaultsHash)
    return false;
    file_put_contents($this->configPath.'config.json', $content);
    return true;
  }

  public function sendHTTPRequest($url, $post=null, $ttl=300) {

    $cacheFileName = $this->cachePath.md5($url).'.cache';

    if (file_exists($cacheFileName) && time()-filemtime($cacheFileName) < $ttl) {
      return file_get_contents($cacheFileName);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    $response = curl_exec($ch);

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200 || !$response) {

      $j = json_decode($response, true);

      fwrite(STDERR, print_r($j, true));

      if ($j === null) {
        $result[0]['title'] = 'Error: Currency Conversion Service not reachable';
        $result[0]['subtitle'] = 'Please check, if service is available, e.g. by checking their website.';
        $result[0]['valid'] = false;
        echo '{"items": '.json_encode($result).'}';

        exit;

      }

      else if (array_key_exists('error', $j)) {

        if (preg_match('/^Free API Key is required./', $j['error'])) {
          $result[0]['title'] = 'Error: no API key given in Alfred Preferences';
          $result[0]['subtitle'] = 'select to open ’currencyconverterapi.com‘ in browser to get your free key';
          $result[0]['valid'] = true;
        }
        elseif (preg_match('/^Invalid Free API Key../', $j['error'])) {
          $result[0]['title'] = 'Invalid API key given in Alfred Preferences (currencyconverterapi.com)';
          $result[0]['subtitle'] = 'Hint: please edit Workflow Environment Variables in Alfred Preferences.';
          $result[0]['valid'] = false;
        }
        elseif ($j['error']['code'] == 'base_currency_access_restricted') {
          $result[0]['title'] = 'Error: base_currency_access_restricted';
          $result[0]['subtitle'] = 'Your exchangesratesapi.io account only supports converting from EUR';
        }
        elseif ($j['error']['code'] == 'invalid_access_key') {
          $result[0]['title'] = 'Invalid API key given in Alfred Preferences (exchangeratesapi.io)';
          $result[0]['subtitle'] = 'Hint: please edit Workflow Environment Variables in Alfred Preferences.';
        }
        else {
          $result[0]['title'] = 'unknown error';
          $result[0]['valid'] = false;
        }
        $result[0]['icon']['path'] = 'icon-error.png';
        $result[0]['arg'] = '--get-api-key';
        echo '{"items": '.json_encode($result).'}';

        exit;

      }
      else {
        $response = false;
      }

    }
    else {
      file_put_contents($cacheFileName, $response);
    }

    curl_close($ch);
    return $response;
  }

  public function clearCacheFiles() {
    $dp = opendir($this->cachePath);
    while ($name = readdir($dp))
    if (is_file($this->cachePath.$name) && time()-filemtime($this->cachePath.$name) > $this->ttl)
    if (preg_match('/cache$/', $name)) unlink($this->cachePath.$name);
    closedir($dp);
  }

}

// ------------------------------------------------------------------------------------

abstract class e4WorkflowCommands {

  protected $inQuery = '';
  protected $inID = '';
  protected $inConfig = array();

  public function __construct(e4WorkflowApp $app, $query, $config) {
    $this->app = $app;
    $this->inQuery = $query;
    $this->inConfig = $config;
    $this->inID = $this->inConfig['id'];
  }

  public function getConfig($key=false) {
    return $key ? $this->inConfig[$key] : $this->inConfig;
  }

  public function getQueryMatch() {
    if (preg_match('/^'.preg_quote($this->inID).'\s*(.*)\s*$/i', $this->inQuery, $out))
    return $out;
    return false;
  }

  public function getCommandSuggest() {
    return array(
      'uid' => $this->inID,
      'arg' => 'none',
      'title' => $this->getCommandSuggestValue('title'),
      'subtitle' => $this->getCommandSuggestValue('subtitle'),
      'autocomplete' => $this->inID.' ',
      'icon' => $this->getCommandSuggestValue('icon'),
      'valid' => $this->getCommandSuggestValue('valid')
    );
  }

  public function getCommandSuggestValue($row) {
    return $this->inConfig[$row] ?: null;
  }

  public function run($inQuery, $args) {
    return array(array(
      'uid' => 'none',
      'arg' => 'none',
      'title' => 'Internal error',
      'subtitle' => 'Uncompleted "'.$this->inID.'" definition!',
      'icon' => 'icon.png',
      'valid' => 'no')
    );
  }

}

// ------------------------------------------------------------------------------------

?>