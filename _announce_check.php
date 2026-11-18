<?php
// Long-poll for "Now serving" announcements (viewing monitor only).
// Written by Transaction::_push_announcement() into _announce.txt.
//
//   ?since=-1  returns the current sequence immediately with no items
//              (used on page load so old announcements are not replayed)
//   ?since=N   waits until the sequence changes (max 20s), then returns
//              every item with seq > N, oldest first
set_time_limit(0);
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

$filename = dirname(__FILE__).'/_announce.txt';

function read_announcements($filename)
{
  $log = array('seq' => 0, 'items' => array());
  $fp = @fopen($filename, 'r');
  if ($fp === false) {
    return $log;
  }
  if (flock($fp, LOCK_SH)) {
    $data = json_decode(stream_get_contents($fp), true);
    flock($fp, LOCK_UN);
    if (is_array($data) && isset($data['seq'], $data['items']) && is_array($data['items'])) {
      $log = $data;
    }
  }
  fclose($fp);
  return $log;
}

$since = isset($_GET['since']) ? (int) $_GET['since'] : -1;
$log   = read_announcements($filename);

if ($since >= 0) {
  $deadline = microtime(true) + 20;
  while ((int) $log['seq'] == $since && microtime(true) < $deadline) {
    usleep(200000); // 200ms
    $log = read_announcements($filename);
  }
}

$items = array();
if ($since >= 0 && (int) $log['seq'] > $since) {
  foreach ($log['items'] as $item) {
    if ((int) $item['seq'] > $since) {
      $items[] = $item;
    }
  }
}

echo json_encode(array('seq' => (int) $log['seq'], 'items' => $items));
flush();
