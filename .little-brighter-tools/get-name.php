<?php

$f = file_get_contents('../info.plist');

preg_match_all('/<key>name<\/key>\s+<string>(.*)<\/string>/mx', $f, $matches);
echo $matches[1][0];

?>
