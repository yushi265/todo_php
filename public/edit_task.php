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
$edit_err = [];

if(!filter_input(INPUT_POST, 'task')) {
  $edit_err['task_msg'] = 'タスクを入力してください。';
  $edit_err['task'] = $_POST['task'];
}
if(mb_strlen($_POST['task']) > 50) {
  $edit_err['task_msg'] = 'タスクは50文字以内で入力してください。';
  $edit_err['task'] = $_POST['task'];
}
if(mb_strlen($_POST['memo']) > 140) {
  $edit_err['memo_msg'] = 'メモは140文字以内で入力してください。';
  $edit_err['memo'] = $_POST['memo'];
}

//エラーがなければ
if(count($edit_err) === 0) {
  $result = TaskLogic::editTask($_POST);
  if($result) {
    toIndex();
  } else {
    exit('変更できませんでした');
  }
} else {
  $_SESSION['edit_err'] = $edit_err;
  header('Location: show.php?id='.$_POST['id']);
}

?>
