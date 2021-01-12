<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

//ログインチェック
$result = UserLogic::checkLogin();
if (!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

$edit_err = isset($_SESSION['edit_err']) ? $_SESSION['edit_err'] : NULL;
unset($_SESSION['edit_err']);

//IDからユーザーのタスク情報を取得
$task_id = $_GET['id'];
$task = TaskLogic::getTaskById($task_id);

//取得できなかったらindex.phpにリダイレクト
if (!$task) {
  toIndex();
  return;
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>タスク編集</title>
</head>

<body>
  <div class="container">
    <div class="page_content">
      <h5>タスク詳細</h5><br>
      <form action="edit_task.php" method="post" id="edit_task">
        <input type="hidden" name="id" value="<?php echo h($task['id']) ?>">
        <label class="show_label">
          <?php if (isset($edit_err['task_msg'])) : ?>
            <?php echo $edit_err['task_msg'] ?><br>
            <input class="edit_task" type="text" name="task" value="<?php echo h($edit_err['task']) ?>">
            <i class="fas fa-pencil-alt"></i>
          <?php else : ?>
            <input class="edit_task" type="text" name="task" value="<?php echo h($task['task']) ?>">
            <i class="fas fa-pencil-alt"></i>
          <?php endif ?>
        </label>
        <label class="show_label">
          <p>追加日：<?php echo h($task['created']) ?></p>
        </label>
        <label class="show_label">
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
        </label>
        <label class="show_label">
          <p>メモ（140字以内）<i class="fas fa-pencil-alt"></i></p>
          <?php if (isset($edit_err['memo_msg'])) : ?>
            <?php echo $edit_err['memo_msg'] ?><br>
            <textarea class="edit_memo" name="memo" cols="80" rows="4"><?php echo $edit_err['memo'] ?></textarea>
          <?php else : ?>
            <textarea class="edit_memo" name="memo" cols="80" rows="4"><?php echo $task['memo'] ?></textarea>
          <?php endif ?>
        </label>
      </form>
      <form action="delete_task.php" method="post" id="delete_task">
        <input type="hidden" name="id" value="<?php echo h($task['id']) ?>">
      </form>
      <div class="btn_group">
        <button type="submit" class="btn edit_btn" form="edit_task">変更</button>
        <button type="submit" class="btn edit_btn red" form="delete_task">削除</button>
      </div>
      <a href="index.php">←戻る</a>
    </div>
  </div>
</body>

</html>
