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

$result = TaskLogic::editTask($_POST);

if (!$result) {
  exit('変更できませんでした');
} else {
  toIndex();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>タスク編集</title>
</head>
<body>
</body>
</html>
