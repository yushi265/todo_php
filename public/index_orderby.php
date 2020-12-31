<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

//ログインチェック
$result = UserLogic::checkLogin();
if(!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

//ユーザー全タスク取得
$user_id = $_SESSION['login_user']['id'];
$tasklist = TaskLogic::taskOrderBy($user_id);

if(!isset($tasklist)) {
  exit('表示できませんでした');
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>タスクリスト</title>
</head>
<body>
  <div class="container">

    <!-- ユーザー情報 -->
    <p>
    <?php echo h($_SESSION['login_user']['name']."様"); ?>
    <?php echo h("<".$_SESSION['login_user']['email'].">"); ?>
    </p>

    <!-- タイトル -->
    <h3>タスク管理</h3>
    <p>現在時刻：<?php echo getNow()?></p>

    <!-- タスク追加 -->
    <div class="alert alert-primary" role="alert">
      <form action="addtask.php" method="post">
        <input type="text" name="task" value="" placeholder="新しいタスク" class="input_task">
        　　　期限日
        <select name="due_date">
          <option value="9999/12/31">-</option>
          <?php for($i = 0; $i < 10; $i++): ?>
            <option value="<?php echo date('Y/n/j', strtotime('+'.$i.'day')); ?>">
              <?php echo date('n/j', strtotime('+'.$i.'day')); ?>
            </option>
          <?php endfor ?>
        </select>
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['login_user']['id'] ?>">
        <button type="submit" class="btn btn-primary">登録
        </button>
      </form>
    </div>

    <!-- 全タスク表示 -->
    <div class="alert alert-primary" role="alert">
      <?php if($tasklist === array()): ?>
        <p>タスクが登録されていません</p>
      <?php else: ?>
        <div class="task_list">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">タスク(<?php echo count($tasklist) ?>)</th>
                <th scope="col"><a href="index.php">追加日</a></th>
                <th scope="col"><a href="index_orderby.php">期限日</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tasklist as $task): ?>
                <tr>
                  <!-- タスク -->
                  <td><?php echo $task['task'] ?></td>
                  <!-- 登録時間 -->
                  <td><?php echo h(str_replace("-", "/", substr($task['created'],5,11))); ?></td>
                  <!-- 期限日 -->
                  <td>
                    <?php if($task['due_date'] === '9999-12-31'): ?>
                      <p>　-</p>
                    <?php else: ?>
                      <p><?php echo h(str_replace("-", "/", substr($task['due_date'],5,5))); ?></p>
                    <?php endif ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      <?php endif ?>
    </div>

    <!-- ログアウト -->
    <div class="btn_group">
      <a href="edit_task.php">
        <button type="buttom" class="btn btn-primary">編集</button>
      </a>
      <a href="delete_task.php">
        <button type="buttom" class="btn btn-primary">削除</button>
      </a>
    <form action="logout.php" method="post" class="logout_btn">
      <input type="hidden" name="logout">
      <button type="submit" name="logout" value="ログアウト" class="btn btn-primary">ログアウト
      </button>
    </form>
    </div>
  </div>
</body>
</html>
