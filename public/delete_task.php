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

echo var_dump($_POST);

$delete_id = $_POST['id'];

$result = TaskLogic::deleteTask($delete_id);

if (!$result) {
  exit('削除できませんでした');
} else {
  toIndex();
}
