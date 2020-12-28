<?php

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}

function getNow() {
  date_default_timezone_set('Asia/Tokyo');
  return date("Y/m/d H:i:s");
}

?>
