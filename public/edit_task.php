<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

$result = UserLogic::checkLogin();
if (!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

//バリデーション


$result = TaskLogic::editTask($_POST);

if (!$result) {
  exit('変更できませんでした');
} else {
  toIndex();
}

?>
