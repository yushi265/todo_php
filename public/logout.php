<?php

session_start();

require_once('../classes/UserLogic.php');

//バリデーション
if(!filter_input(INPUT_POST, 'logout')) {
  exit('不正なリクエストです');
}

//ログインチェック
$result = UserLogic::checkLogin();
if(!$result) {
  exit('セッションが切れましたのでログインし直してください');
}

//ログアウト処理
UserLogic::logout();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>ログイン画面</title>
</head>
<body>
  <div class="container">
    <div class="alert alert-primary" role="alert">
      <p>ログアウトしました</p>
      <a href="login_form.php">←戻る</a>
  </div>
</body>
</html>
