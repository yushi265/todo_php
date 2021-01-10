<?php
session_start();

require_once('../classes/UserLogic.php');

//エラーメッセージ
$err = [];

//CSRF対策トークン
$token = filter_input(INPUT_POST, 'csrf_token');
if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
  exit('不正なリクエストです');
}
unset($_SESSION['csrf_token']);

//バリデーション
if(!$name = filter_input(INPUT_POST, 'name')) {
  $err[] = 'お名前が入力されていません';
}
if(!$email = filter_input(INPUT_POST, 'email')) {
  $err[] = 'メールアドレスが入力されていません';
}
$password = filter_input(INPUT_POST, 'password');
if(!preg_match("/\A[a-z\d]{8,100}+\z/i",$password)) {
  $err[] = 'パスワードは英数字8文字以上100文字以下にしてください。';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if($password_conf !== $password) {
  $err[] = '確認用パスワードが一致していません';
}

//エラーがなければユーザー登録
if(count($err) === 0) {
  $result = UserLogic::createUser($_POST);

  if(!$result) {
    $err[] = '登録に失敗しました';
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>登録画面</title>
</head>
<body>
  <div class="container">
    <?php if(count($err) > 0): ?>
      <h3>登録エラー</h3>
    <?php else: ?>
      <h3>登録完了</h3>
    <?php endif ?>
    <div class="page_content">
      <?php if(count($err) > 0): ?>
        <?php foreach($err as $e): ?>
          <p><?php echo h($e) ?></p>
        <?php endforeach ?>
      <?php else: ?>
        <p>登録が完了しました</p>
      <?php endif ?>
    </div>
    <?php if(count($err) > 0): ?>
      <a href="signup_form.php">←戻る</a>
    <?php else: ?>
      <a href="login_form.php">←戻る</a>
    <?php endif ?>

  </div>
</body>
</html>
