<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

// ログインチェック
$result = UserLogic::checkLogin();
if(!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

$user_id = $_SESSION['login_user']['id'];
$sort = $_GET['sort'];
$order = $_GET['order'];

$limit = TaskLogic::getLimit();
$max_page = TaskLogic::getMaxPage($user_id);
$max_task = TaskLogic::countUserTask($user_id);

if(isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

// // URLバリデーション
// // クエリストリングがない時
// if(empty($sort) || empty($order)) {
//   header('Location: index.php?sort=created&order=asc');
//   return;
// }
// // $sortがcreated,due_date以外の時
// if($sort !== 'created' && $sort !== 'due_date') {
//   header('Location: index.php?sort=created&order=asc');
//   return;
// }
// // $orderがasc,desc以外の時
// if($order !== 'asc' && $order !== 'desc') {
//   header('Location: index.php?sort=created&order=asc');
//   return;
// }

// ユーザー全タスク取得
$tasklist = TaskLogic::getUserTaskList($user_id, $sort, $order, $page);

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
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
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
                <th scope="col">タスク(<?php echo $max_task ?>)</th>

                <?php if($_GET['sort'] === 'created'): ?>
                  <?php if($_GET['order'] === 'asc'):?>
                    <th scope="col"><a href="index.php?sort=created&order=desc">追加日</a></th>
                  <?php else: ?>
                    <th scope="col"><a href="index.php?sort=created&order=asc">追加日</a></th>
                  <?php endif ?>
                  <th scope="col"><a href="index.php?sort=due_date&order=asc">期限日</a></th>
                <?php endif ?>

                <?php if($_GET['sort'] === 'due_date'): ?>
                  <th scope="col"><a href="index.php?sort=created&order=asc">追加日</a></th>
                  <?php if($_GET['order'] === 'asc'):?>
                    <th scope="col"><a href="index.php?sort=due_date&order=desc">期限日</a></th>
                  <?php else: ?>
                    <th scope="col"><a href="index.php?sort=due_date&order=asc">期限日</a></th>
                  <?php endif ?>
                <?php endif ?>
              </tr>
              <!-- <th scope="col"><a href="index.php?sort=created&order=asc">追加日</a></th>
              <th scope="col"><a href="index.php?sort=due_date&order=asc">期限日</a></th> -->
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

      <div class="page">
        <?php for($i = 1; $i <= $max_page; $i++): ?>
          <?php if($page == $i): ?>
            <?php echo $i."　" ?>
            <?php continue; ?>
          <?php endif ?>
          <a href="index.php?sort=<?php echo $sort ?>&order=<?php echo $order ?>&page=<?php echo $i ?>"><?php echo $i ?></a>　
        <?php endfor ?>
      </div>
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
