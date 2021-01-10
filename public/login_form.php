<?php

session_start();

require_once('../classes/UserLogic.php');
require_once('../functions.php');

//ログインチェック
$result = UserLogic::checkLogin();
if ($result) {
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
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>ログイン</title>
</head>

<body>

  <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
  <script>
    $(document).ready(function() {
      var formInputs = $('input[type="email"],input[type="password"]');
      formInputs.focus(function() {
        $(this).parent().children('p.formLabel').addClass('formTop');
        $('div#formWrapper').addClass('darken-bg');
        $('div.logo').addClass('logo-active');
      });
      formInputs.focusout(function() {
        if ($.trim($(this).val()).length == 0) {
          $(this).parent().children('p.formLabel').removeClass('formTop');
        }
        $('div#formWrapper').removeClass('darken-bg');
        $('div.logo').removeClass('logo-active');
      });
      $('p.formLabel').click(function() {
        $(this).parent().children('.form-style').focus();
      });
    });
  </script>

  <body>
    <div id="formWrapper">
      <div id="form">
        <div class="form-item">
          <!-- <p>ログイン</p> -->
          <?php if (isset($err['msg'])) : ?>
            <p><?php echo h($err['msg']) ?></p>
          <?php endif ?>
          <?php if (isset($login_err)) : ?>
            <p><?php echo h($login_err) ?></p>
          <?php else: ?>
            <p>ログイン</p>
          <?php endif ?>
        </div>
        <form action="login.php" method="post">
          <div class="form-item">
            <p class="formLabel">メールアドレス</p>
            <input type="email" name="email" id="email" class="form-style" autocomplete="on" required />
            <?php if (isset($err['email'])) : ?>
              <p><?php echo h($err['email']) ?></p>
            <?php endif ?>
          </div>
          <div class="form-item">
            <p class="formLabel">パスワード</p>
            <input type="password" name="password" id="password" class="form-style" required />
            <?php if (isset($err['password'])) : ?>
              <p><?php echo h($err['password']) ?></p>
            <?php endif ?>
            <!-- <div class="pw-view"><i class="fa fa-eye"></i></div> -->
          </div>
          <div class="form-item">
            <input type="submit" class="login pull-right" value="ログイン">
            <a class="form_btn" href="signup_form.php">新規登録</a>
            <div class="clear-fix">
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>


</body>

</html>
