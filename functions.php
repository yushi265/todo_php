<?php

/**
 * エスケープ処理
 * @param string $str
 * @return string
 */
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}

/**
 * 現在時刻取得
 * @param void
 * @return string
 */
function getNow() {
  date_default_timezone_set('Asia/Tokyo');
  return date("Y/m/d H:i:s");
}

/**
 * 現在時刻取得
 * @param void
 * @return string
 */
function getNowDate() {
  date_default_timezone_set('Asia/Tokyo');
  return date("Y/m/d");
}

/**
 * CSRF対策
 * @param void
 * @return string $csrf_token
 */
function setToken() {
  $csrf_token = bin2hex(random_bytes(32));
  $_SESSION['csrf_token'] = $csrf_token;
  return $csrf_token;
}

/**
 * index.phpへヘッダー
 */
function toIndex() {
  header('Location: index.php');
  return;
}

?>
