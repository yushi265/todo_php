<?php

session_start();

require_once('../functions.php');
require_once('../classes/TaskLogic.php');
require_once('../classes/UserLogic.php');

// ログインチェック
$result = UserLogic::checkLogin();
if (!$result) {
  $_SESSION['login_err'] = 'ログインしてください';
  header('Location: login_form.php');
  return;
}

//変数定義
$user_id = $_SESSION['login_user']['id'];
$limit = TaskLogic::getLimit();
$max_page = TaskLogic::getMaxPage($user_id);
$max_task = TaskLogic::countUserTask($user_id);

// URLバリデーション
if (isset($_GET['sort'])) {
  if ($_GET['sort'] !== "created" && $_GET['sort'] !== "due_date") {
    toIndex();
  } else {
    $sort = $_GET['sort'];
  }
} else {
  $sort = "created";
}

if (isset($_GET['order'])) {
  if ($_GET['order'] !== "asc" && $_GET['order'] !== "desc") {
    toIndex();
  } else {
    $order = $_GET['order'];
  }
} else {
  $order = "asc";
}

if (isset($_GET['page'])) {
  if ($_GET['page'] <= $max_page) {
    $page = $_GET['page'];
  } else {
    toIndex();
  }
} else {
  $page = '1';
}

// URLパラメータをもとにタスクを取得
$tasklist = TaskLogic::getUserTask($user_id, $sort, $order, $page);

if (!isset($tasklist)) {
  exit('表示できませんでした');
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>タスク管理</title>

  <link rel="shortcut icon" href="../image/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="components.css">


</head>

<body>
  <div class="container index">

    <!-- ユーザー情報 -->
    <p>
      <?php echo h($_SESSION['login_user']['name'] . "様"); ?>
      <?php echo h("<" . $_SESSION['login_user']['email'] . ">"); ?>
    </p>

    <!-- タイトル -->
    <h3>タスク管理</h3>
    <p>現在時刻：<?php echo h(getNow()) ?></p>

    <!-- タスク追加 -->
    <div class="page_content">
      <form action="addtask.php" method="post">
        <!-- <div class="add_input"> -->
        <input type="text" name="task" value="" placeholder="新しいタスク" class="input_task">
        期限
        <select name="due_date">
          <option value="9999/12/31">-</option>
          <?php for ($i = 0; $i < 14; $i++) : ?>
            <option value="<?php echo h(date('Y/n/j', strtotime('+' . $i . 'day'))); ?>">
              <?php echo h(date('n/j', strtotime('+' . $i . 'day'))); ?>
            </option>
          <?php endfor ?>
        </select>
        <input type="hidden" name="user_id" value="<?php echo h($user_id) ?>">
        <!-- </div> -->
        <!-- <div class="add_btn"> -->
        <button type="submit" class="btn add_btn">追加</button>
        <!-- </div> -->
      </form>
    </div>

    <!-- 全タスク表示 -->
    <div class="page_content">
      <?php if ($tasklist === array()) : ?>
        <p>タスクが登録されていません</p>
      <?php else : ?>
        <div class="task_list">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="checkAll">
                    <label class="form-check-label" for="checkAll">
                      タスク&nbsp;(<?php echo h($max_task) ?>)
                    </label>
                  </div>

                </th>
                <!-- ソートが追加日の時 -->
                <?php if ($sort === 'created') : ?>
                  <?php if ($order === 'asc') : ?>
                    <th scope="col">
                      <a href="index.php?sort=created&order=desc">追加日&nbsp;<i class="fas fa-sort-up fa-xs"></i></a>
                    </th>
                  <?php else : ?>
                    <th scope="col">
                      <a href="index.php?sort=created&order=asc">追加日&nbsp;<i class="fas fa-sort-down fa-xs"></i></a>
                    </th>
                  <?php endif ?>
                  <th scope="col">
                    <a href="index.php?sort=due_date">期限日&nbsp;<i class="fas fa-sort fa-xs"></i></a>
                  </th>
                <?php endif ?>
                <!-- ソートが期限日の時 -->
                <?php if ($sort === 'due_date') : ?>
                  <th scope="col">
                    <a href="index.php?sort=created">追加日&nbsp;<i class="fas fa-sort fa-xs"></i></a>
                  </th>
                  <?php if ($order === 'asc') : ?>
                    <th scope="col">
                      <a href="index.php?sort=due_date&order=desc">期限日&nbsp;<i class="fas fa-sort-up fa-xs"></i></a>
                    </th>
                  <?php else : ?>
                    <th scope="col">
                      <a href="index.php?sort=due_date&order=asc">期限日&nbsp;<i class="fas fa-sort-down fa-xs"></i></a>
                    </th>
                  <?php endif ?>
                <?php endif ?>
                <th>

                </th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($tasklist as $task) : ?>
                <tr>
                  <!-- タスク -->
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="selected_id[]" value="<?php echo h($task['id']) ?>" id="task<?php echo h($task['id']) ?>">
                      <label class="form-check-label" for="task<?php echo h($task['id']) ?>">
                        <?php echo h($task['task']) ?>
                      </label>
                    </div>
                  </td>
                  <!-- 登録時間 -->
                  <td class="created"><?php echo h(str_replace("-", "/", substr($task['created'], 5, 11))); ?></td>
                  <!-- 期限日 -->
                  <td>
                    <?php if ($task['due_date'] === '9999-12-31') : ?>
                      <p>　-</p>
                    <?php else : ?>
                      <p><?php echo h(str_replace("-", "/", substr($task['due_date'], 5, 5))); ?></p>
                    <?php endif ?>
                  </td>
                  <td>
                    <a href="show.php?id=<?php echo h($task['id']) ?>">
                      <button class="btn">詳細</button>
                    </a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      <?php endif ?>

      <!-- ページャー -->
      <nav class="cp_navi">
        <div class="cp_pagination">
          <?php if ($page == 1) : ?>
            <p class="cp_pagenum prev disabled">&nbsp;<i class="fas fa-chevron-left fa-xs"></i>&nbsp;</p>
          <?php else : ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($page - 1) ?>" class="cp_pagenum prev">
              &nbsp;<i class="fas fa-chevron-left fa-xs"></i>&nbsp;
            </a>
          <?php endif ?>

          <?php for ($i = 1; $i <= $max_page; $i++) : ?>
            <?php if ($page == $i) : ?>
              <span aria-current="page" class="cp_pagenum current"><?php echo h($i) ?></span>
              <?php continue; ?>
            <?php endif ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($i) ?>" class="cp_pagenum"><?php echo h($i) ?></a>
          <?php endfor ?>

          <?php if ($page == $max_page) : ?>
            <p class="cp_pagenum prev disabled">&nbsp;<i class="fas fa-chevron-right fa-xs"></i>&nbsp;</p>
          <?php else : ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($page + 1) ?>" class="cp_pagenum next">
              &nbsp;<i class="fas fa-chevron-right fa-xs"></i>&nbsp;
            </a>
          <?php endif ?>
        </div>
      </nav>

      <!-- ログアウト -->
      <div class="btn_group">
        <a href="delete_task.php"><button type="button" class="btn">削除</button></a>
        <form action="logout.php" method="post" class="logout_btn">
          <input type="hidden" name="logout">
          <button type="submit" name="logout" value="ログアウト" class="btn">ログアウト</button>
        </form>
      </div>
    </div>
  </div>

</body>

</html>
