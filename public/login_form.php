<?php

session_start();

$err = $_SESSION;

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
        <?php if(isset($err['mdg'])): ?>
          <?php echo $err['msg'] ?>
        <?php endif ?>
        <p>必要事項を入力してください</p>
        <p>
          <label>メールアドレス：</label>
          <input type="email" name="email">
          <?php if(isset($err['email'])): ?>
            <?php echo $err['email'] ?>
          <?php endif ?>
        </p>
        <p>
          <label>パスワード：</label>
          <input type="text" name="password">
          <?php if(isset($err['password'])): ?>
            <?php echo $err['password'] ?>
          <?php endif ?>
        </p>
        <button type="submit" class="btn btn-primary">ログイン</button>
      </form>
    </div>
  </div>
</body>
</html>
