<?php

$f = file_get_contents('../../info.plist');

preg_match_all('/<key>version<\/key>\s+<string>(.*)<\/string>/mx', $f, $matches);
echo 'v'.$matches[1][0];

?>
