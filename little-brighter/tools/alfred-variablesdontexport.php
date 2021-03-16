<?php

// ------------------------------------------------------------------------------

if ($argv[1] == '--clean') {

  // ------------------------------------------------------------------------------

  $out = array();
  $cmd = '/usr/libexec/Plistbuddy -x -c "Print :variablesdontexport" info.plist 2>/dev/null';
  exec($cmd, $out, $ret);

  // error code, e.g. if no variables to not export exist
  if ($ret > 0) {
    fwrite(STDERR, 'no workflow variables detected, that should not be exported'."\n");
    exit(1);
  }

  // ------------------------------------------------------------------------------

  // "hack" with json encode/decode to flatten object to an array
  $vars_dont_export = json_decode(json_encode(simplexml_load_string(implode("\n",$out))), true)['array']['string'];

  // if only one entry, Plistbuddy does not return array, so, turn string into 1-element-array
  if (!is_array($vars_dont_export)) {
    $vars_dont_export = array($vars_dont_export);
  }

  // if old file exists, delete it
  if (file_exists('.alfred-variablesdontexport')) {
    unlink('.alfred-variablesdontexport');
  }

  $s = '';
  foreach ($vars_dont_export as $k => $v) {

    $out = array();
    $cmd = '/usr/libexec/Plistbuddy -c "Print :variables:'.$v.'" info.plist 2>/dev/null';
    exec($cmd, $out, $ret);

    if ($ret > 0) {
      fwrite(STDERR, 'variable '.$v.' could not be loaded from plist'."\n");
      exit(4);
    }

    $s .= $v."\t".implode('', $out)."\n";
    echo 'saving:'.$v."\n";

  }

  // store variable names and values, this file is .gitignored
  file_put_contents('.alfred-variablesdontexport', $s);

  // ------------------------------------------------------------------------------

  // set variables to empty strings
  foreach ($vars_dont_export as $k => $v) {

    $out = array();
    $cmd = '/usr/libexec/Plistbuddy -c "Set :variables:'.$v.'" info.plist 2>/dev/null';
    exec($cmd, $out, $ret);

    if ($ret > 0) {
      fwrite(STDERR, 'variable '.$v.' could not be cleaned in plist'."\n");
      exit(5);
    }

    echo 'cleaning: '.$v."\n";

  }

  // ------------------------------------------------------------------------------

}

// ------------------------------------------------------------------------------

elseif ($argv[1] == '--restore') {

  // if no file exists, exit
  if (!file_exists('.alfred-variablesdontexport')) {
    fwrite(STDERR, 'no saved variables detected, cannot restore'."\n");
    exit(2);
  }

  $vars = explode("\n", trim(file_get_contents('.alfred-variablesdontexport')));

  foreach ($vars as $k => $v) {
    $x = explode("\t", $v);
    //echo $x[0].' --> '.$x[1]."\n";

    $out = array();
    $cmd = '/usr/libexec/Plistbuddy -c "Set :variables:'.$x[0].' '.$x[1].'" info.plist';
    exec($cmd, $out, $ret);

    if ($ret > 0) {
      fwrite(STDERR, 'variable '.$v.' could not be restored into plist'."\n");
      exit(6);
    }

    echo 'restoring: '.$x[0]."\n";

  }

  // if old file exists, delete it
  if (file_exists('.alfred-variablesdontexport')) {
    unlink('.alfred-variablesdontexport');
  }

}

// ------------------------------------------------------------------------------

else {
  fwrite(STDERR, 'unknown command'."\n");
  exit(3);
}

// ------------------------------------------------------------------------------

?>