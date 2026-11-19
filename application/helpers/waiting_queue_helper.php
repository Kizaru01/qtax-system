<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function qtax_regular_waiting_types() {
  return "'REGULAR', 'REGULAR5-10'";
}

function qtax_special_waiting_types() {
  return "'SPECIAL', 'SPECIAL5-10'";
}

function qtax_waiting_types() {
  return qtax_regular_waiting_types() . ', ' . qtax_special_waiting_types();
}

function qtax_queue_arrival_order() {
  return "CASE WHEN status = 'NA' THEN COALESCE(dtorder, dtadded) ELSE dtorder END, id";
}

function qtax_waiting_order() {
  return "CASE WHEN status = 'NA' AND type IN (" . qtax_special_waiting_types() . ") THEN 0 ELSE 1 END, "
    . qtax_queue_arrival_order();
}
