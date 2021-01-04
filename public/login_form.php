<?php

session_start();

require_once('../classes/UserLogic.php');
require_once('../functions.php');

//ログインチェック
$result = UserLogic::checkLogin();
if($result) {
  header('Location: mypage.php');
  return;
}

//リロード対策
$err = $_SESSION;
$login_err = isset($err['login_err']) ? $err['login_err'] : null;
unset($err['login_err']);

$_SESSION = array();
session_destroy();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>ログインフォーム</title>
</head>
<body>
  <div class="container">
    <h3>ログインフォーム</h3>
    <div class="alert alert-primary" role="alert">
      <form action="login.php" method="post">
        <!-- エラー表示 -->
        <?php if(isset($err['mdg'])): ?>
          <?php echo $err['msg'] ?>
        <?php endif ?>
        <?php if(isset($login_err)): ?>
          <?php echo h($login_err) ?>
        <?php else: ?>
          <p>必要事項を入力してください</p>
        <?php endif ?>
        <!-- 入力部分 -->
        <p>
          <label>メールアドレス：</label>
          <input type="email" name="email">
          <?php if(isset($err['email'])): ?>
            <?php echo $err['email'] ?>
          <?php endif ?>
        </p>
        <p>
          <label>パスワード：</label>
          <input type="password" name="password">
          <?php if(isset($err['password'])): ?>
            <?php echo $err['password'] ?>
          <?php endif ?>
        </p>
        <p>
            <a href="signup_form.php">→新規登録</a>
        </p>
        <button type="submit" class="btn btn-primary">ログイン</button>
      </form>
    </div>
  </div>
</body>
</html>
