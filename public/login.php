<?php
session_start();

require_once('../classes/UserLogic.php');

//エラーメッセージ
$err = [];

//バリデーション
if(!$email = filter_input(INPUT_POST, 'email')) {
  $err['email'] = 'メールアドレスが入力されていません';
}
if(!$password = filter_input(INPUT_POST, 'password')) {
  $err['password'] = 'パスワードが入力されていません';
}
//エラーがあるときはセッションに入れて戻す
if(count($err) > 0) {
  $_SESSION = $err;
  header('Location: login_form.php');
  return;
}

//ログイン処理
$result = UserLogic::login($email, $password);
if(!$result) {
  header('Location: login_form.php');
  return;
}
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
      <?php if(count($err) > 0): ?>
        <?php foreach($err as $e): ?>
          <p><?php echo $e ?></p>
        <?php endforeach ?>
      <?php else: ?>
        <p>ログインしました</p>
      <?php endif ?>
    </div>
    <a href="index.php">マイページへ→</a>
  </div>
</body>
</html>
