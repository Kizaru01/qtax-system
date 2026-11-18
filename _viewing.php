<?php
set_time_limit(0);
$filename  = dirname(__FILE__).'/_getviewing.txt';

// store new message in the file
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
if ($msg != '')
{
  file_put_contents($filename,$msg);
  die();
}

// Reload periodically even if multiple writes share one file timestamp.
$lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
$currentmodif = filemtime($filename);
$deadline = microtime(true) + 5;
while ($currentmodif <= $lastmodif && microtime(true) < $deadline)
{
  usleep(10000); // sleep 10ms to unload the CPU
  clearstatcache();
  $currentmodif = filemtime($filename);
}

// return a json array
$response = array();
// $response['msg']       = file_get_contents($filename);
$response['timestamp'] = $currentmodif;
echo json_encode($response);
flush();

?>
