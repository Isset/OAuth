<?php

$file   = __DIR__ . '/response.txt';
$data   = serialize($_GET);
$handle = fopen($file, 'w');

fwrite($handle, $data);
fclose($handle);
