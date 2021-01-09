<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

$result = UserLogic::checkLogin();
if(!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

//ログインユーザーのタスクを取得
$user_id = $_SESSION['login_user']['id'];
$tasks = TaskLogic::getUserTaskAll($user_id);

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
  <div class="container">
    <h3>タスク編集</h3>
    <div class="page_content">
      <form action="comp_edit.php" method="post">
        <p>どのタスクを編集しますか？</p>
        <p>
          <select name="task_id">
            <?php foreach ($tasks as $task): ?>
              <option value="<?php  echo h($task['id']) ?>"><?php echo h($task['task']) ?></option>
            <?php endforeach ?>
          </select>
        </p>
        <p>変更後のタスク</p>
        <input type="text" name="edited_task" value="">
        <button type="submit" class="btn btn-primary">変更する</button>
      </form>
      <a href="index.php">←戻る</a>
    </div>
  </div>

</body>
</html>
