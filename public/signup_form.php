<?php

session_start();

require_once('../classes/UserLogic.php');
require_once('../functions.php');

$result = UserLogic::checkLogin();
if($result) {
  header('Location: mypage.php');
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
  <title>ユーザー登録フォーム</title>
</head>
<body>
  <div class="container">
    <h3>ユーザー登録</h3>
    <div class="alert alert-primary" role="alert">
      <form action="register.php" method="post">
        <p>必要事項を入力してください</p>
        <p>
          <label>名前：</label>
          <input type="text" name="name">
        </p>
        <p>
          <label>メールアドレス：</label>
          <input type="email" name="email">
        </p>
        <p>
          <label>パスワード：</label>
          <input type="text" name="password">
        </p>
        <p>
          <label>パスワード確認：</label>
          <input type="text" name="password_conf">
        </p>
        <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
        <button type="submit" class="btn btn-primary">登録</button>
      </form>
    </div>
    <a href="login_form.php">←戻る</a>
  </div>
</body>
</html>
