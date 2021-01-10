<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

$result = UserLogic::checkLogin();
if (!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

//ログインユーザーのタスクの情報を取得
$task_id = $_GET['id'];
$task = TaskLogic::getTaskById($task_id);

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
    <div class="page_content">
      <form action="edit_task_test.php" method="post">
        <input type="hidden" name="id" value="<?php echo h($task['id']) ?>">
        <label>
          <input type="text" name="task" value="<?php echo h($task['task']) ?>">
        </label>
        <p>追加日：<?php echo h($task['created']) ?></p>
        <p>期限日：
          <select name="due_date">
            <option value="<?php echo $task['due_date'] ?>">
              <?php echo h(str_replace("-", "/", substr($task['due_date'], 5, 5))) ?>
            </option>
            <option value="9999/12/31">-</option>
            <?php for ($i = 0; $i < 14; $i++) : ?>
              <option value="<?php echo h(date('Y/m/d', strtotime('+' . $i . 'day'))); ?>">
                <?php echo h(date('m/d', strtotime('+' . $i . 'day'))); ?>
              </option>
            <?php endfor ?>
          </select>
        </p>
        <label>
          <p>メモ</p>
          <textarea name="memo" cols="30" rows="10"><?php echo $task['memo'] ?></textarea>
        </label>
        <br><button type="submit" class="btn">変更</button>
      </form>
      <a href="index.php">←戻る</a>
    </div>
  </div>
</body>

</html>
