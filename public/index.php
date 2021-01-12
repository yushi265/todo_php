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
// $sort
if (isset($_GET['sort'])) {
  if ($_GET['sort'] !== "created" && $_GET['sort'] !== "due_date") {
    toIndex();
  } else {
    $sort = $_GET['sort'];
  }
} else {
  $sort = "created";
}
// $order
if (isset($_GET['order'])) {
  if ($_GET['order'] !== "asc" && $_GET['order'] !== "desc") {
    toIndex();
  } else {
    $order = $_GET['order'];
  }
} else {
  $order = "asc";
}
// $page
if (isset($_GET['page'])) {
  if ($_GET['page'] <= $max_page) {
    $page = $_GET['page'];
  } else {
    toIndex();
  }
} else {
  $page = '1';
}

// URLパラメータをもとに未完了タスクと完了タスクを取得
$tasklist = TaskLogic::getUserTask($user_id, $sort, $order, $page);
$comp_tasklist = TaskLogic::getUserCompTask($user_id);
if (!isset($tasklist) || !isset($comp_tasklist)) {
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
    <div class="page_content">
      <h5>
        <?php echo h($_SESSION['login_user']['name'] . "様"); ?>
        <?php //echo h("<" . $_SESSION['login_user']['email'] . ">");
        ?>
      </h5>
      <p>現在時刻：<span id="view_clock"></span></p>
      <!-- タスク追加 -->
      <form action="add_task.php" method="post">
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
        <button type="submit" class="btn add_btn">追加</button>
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
                    <th scope="col" class="created">
                      <a href="index.php?sort=created&order=desc">追加日&nbsp;<i class="fas fa-sort-up fa-xs"></i></a>
                    </th>
                  <?php else : ?>
                    <th scope="col" class="created">
                      <a href="index.php?sort=created&order=asc">追加日&nbsp;<i class="fas fa-sort-down fa-xs"></i></a>
                    </th>
                  <?php endif ?>
                  <th scope="col" class="due_date">
                    <a href="index.php?sort=due_date">期限日&nbsp;<i class="fas fa-sort fa-xs"></i></a>
                  </th>
                <?php endif ?>
                <!-- ソートが期限日の時 -->
                <?php if ($sort === 'due_date') : ?>
                  <th scope="col" class="created">
                    <a href="index.php?sort=created">追加日&nbsp;<i class="fas fa-sort fa-xs"></i></a>
                  </th>
                  <?php if ($order === 'asc') : ?>
                    <th scope="col" class="due_date">
                      <a href="index.php?sort=due_date&order=desc">期限日&nbsp;<i class="fas fa-sort-up fa-xs"></i></a>
                    </th>
                  <?php else : ?>
                    <th scope="col" class="due_date">
                      <a href="index.php?sort=due_date&order=asc">期限日&nbsp;<i class="fas fa-sort-down fa-xs"></i></a>
                    </th>
                  <?php endif ?>
                <?php endif ?>
                <!-- 詳細ボタン -->
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
                  <td class="due_date">
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
                    <button class="btn" type="submit" form="comp_id">完了</button>
                    <form action="comp_task.php" method="post" id='comp_id'>
                      <input type="hidden" name="comp_id" value="<?php echo $task['id'] ?>">
                    </form>
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
          <!-- 前へ -->
          <?php if ($page == 1) : ?>
            <p class="cp_pagenum prev disabled">&nbsp;<i class="fas fa-chevron-left fa-xs"></i>&nbsp;</p>
          <?php else : ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($page - 1) ?>" class="cp_pagenum prev">
              &nbsp;<i class="fas fa-chevron-left fa-xs"></i>&nbsp;
            </a>
          <?php endif ?>
          <!-- ページ番号 -->
          <?php for ($i = 1; $i <= $max_page; $i++) : ?>
            <?php if ($page == $i) : ?>
              <span aria-current="page" class="cp_pagenum current"><?php echo h($i) ?></span>
              <?php continue; ?>
            <?php endif ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($i) ?>" class="cp_pagenum"><?php echo h($i) ?></a>
          <?php endfor ?>
          <!-- 次へ -->
          <?php if ($page == $max_page) : ?>
            <p class="cp_pagenum prev disabled">&nbsp;<i class="fas fa-chevron-right fa-xs"></i>&nbsp;</p>
          <?php else : ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($page + 1) ?>" class="cp_pagenum next">
              &nbsp;<i class="fas fa-chevron-right fa-xs"></i>&nbsp;
            </a>
          <?php endif ?>
        </div>
      </nav>
    </div>

    <!-- 完了済みタスク表示 -->
    <div class="page_content">
      <div class="task_list">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">
                <label class="form-check-label" for="checkAll">
                  タスク&nbsp;(<?php echo h($max_task) ?>)
                </label>
              </th>
              <th>
                完了日
              </th>
              <th>
              </th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($comp_tasklist as $comp_task) : ?>
              <tr>
                <!-- タスク -->
                <td>
                  <label class="form-check-label" for="task<?php echo h($task['id']) ?>">
                    <?php echo h($comp_task['task']) ?>
                  </label>
                </td>
                <td>
                  <p><?php echo h(str_replace("-", "/", substr($comp_task['completed'], 5, 5))); ?></p>
                </td>
                <td>
                  <button class="btn">もとに戻す</button>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ログアウト -->
    <form action="logout.php" method="post" class="logout_btn">
      <input type="hidden" name="logout">
      <button class="btn" type="submit" name="logout" value="ログアウト" class="btn">ログアウト</button>
    </form>
  </div>
  </div>

  <!-- 現在時刻表示関数 -->
  <script type="text/javascript">
    timerID = setInterval('clock()', 500); //0.5秒毎にclock()を実行

    function clock() {
      document.getElementById("view_clock").innerHTML = getNow();
    }

    function getNow() {
      var now = new Date();
      var year = now.getFullYear();
      var mon = now.getMonth() + 1; //１を足すこと
      var day = now.getDate();
      var hour = now.getHours();
      var min = now.getMinutes();
      var sec = now.getSeconds();

      //出力用
      var s = year + "/" + mon + "/" + day + " " + hour + ":" + min + ":" + sec;
      return s;
    }
  </script>
</body>

</html>
