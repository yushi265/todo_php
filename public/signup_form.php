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

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>ユーザー登録</title>
</head>

<body>

  <script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
  <script>
    $(document).ready(function() {
      var formInputs = $('input[type="text"],input[type="email"],input[type="password"]');
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
        <p>　ユーザー登録</p>
        <form action="register.php" method="post">
          <div class="form-item">
            <p class="formLabel">ユーザー名</p>
            <input type="text" name="name" id="name" class="form-style" autocomplete="off" required />
          </div>
          <div class="form-item">
            <p class="formLabel">メールアドレス</p>
            <input type="email" name="email" id="email" class="form-style" autocomplete="off" required />
          </div>
          <div class="form-item">
            <p class="formLabel">パスワード</p>
            <input type="password" name="password" id="password" class="form-style" required />
            <div class="pw-view"><i class="fa fa-eye"></i></div>
          </div>
          <div class="form-item">
            <p class="formLabel">パスワード確認</p>
            <input type="password" name="password_conf" id="password_conf" class="form-style" autocomplete="off" required />
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo h(setToken()) ?>">
          <div class="form-item">
            <input type="submit" class="login pull-right" value="新規登録">
            <a class="form_btn" href="login_form.php">←戻る</a>
            <div class="clear-fix"></div>
          </div>
        </form>
      </div>

    </div>
  </body>




</html>
