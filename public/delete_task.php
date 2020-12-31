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

$user_id = $_SESSION['login_user']['id'];

$tasks = TaskLogic::getUserTaskList($user_id);

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
    <h3>タスク削除</h3>
    <div class="alert alert-primary" role="alert">
      <form action="comp_delete.php" method="post">
        <p>どのタスクを削除しますか？</p>
        <p>
          <select name="task_id">
            <?php foreach ($tasks as $task): ?>
              <option value="<?php  echo $task['id'] ?>"><?php echo $task['task'] ?></option>
            <?php endforeach ?>
          </select>
        </p>
        <button type="submit" class="btn btn-primary">削除する</button>
      </form>
      <a href="index.php">←戻る</a>
    </div>
  </div>

</body>
</html>
