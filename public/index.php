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
                    <th scope="col" class="created">
                      <a href="index.php?sort=created&order=desc">追加日&nbsp;<i class="fas fa-sort-up fa-xs"></i></a>
                    </th>
                  <?php else : ?>
                    <th scope="col" class="created">
                      <a href="index.php?sort=created&order=asc">追加日&nbsp;<i class="fas fa-sort-down fa-xs"></i></a>
                    </th>
                  <?php endif ?>
                  <th scope="col">
                    <a href="index.php?sort=due_date">期限日&nbsp;<i class="fas fa-sort fa-xs"></i></a>
                  </th>
                <?php endif ?>
                <!-- ソートが期限日の時 -->
                <?php if ($sort === 'due_date') : ?>
                  <th scope="col" class="created">
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
              </tr>
            </thead>

            <tbody>
              <?php foreach ($tasklist as $task) : ?>
                <tr>
                  <!-- タスク -->
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="task<?php echo $task['id'] ?>">
                      <label class="form-check-label" for="task<?php echo $task['id'] ?>">
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
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      <?php endif ?>

      <div class="page">
        <label>
          <?php if ($page == 1) : ?>
            　<i class="fas fa-angle-double-left fa-xs"></i>　
          <?php else : ?>
            　<a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($page - 1) ?>"><i class="fas fa-angle-double-left fa-xs"></i></a>　
          <?php endif ?>
        </label>

        <?php for ($i = 1; $i <= $max_page; $i++) : ?>
          <?php if ($page == $i) : ?>
            <?php echo h($i . "　") ?>
            <?php continue; ?>
          <?php endif ?>
          <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($i) ?>"><?php echo h($i) ?></a>　
        <?php endfor ?>

        <label>
          <?php if ($page == 3) : ?>
            <i class="fas fa-angle-double-right fa-xs"></i>
          <?php else : ?>
            <a href="index.php?sort=<?php echo h($sort) ?>&order=<?php echo h($order) ?>&page=<?php echo h($page + 1) ?>"><i class="fas fa-angle-double-right fa-xs"></i></a>　
          <?php endif ?>
        </label>
      </div>
    </div>

    <!-- ログアウト -->
    <div class="btn_group">
      <a href="edit_task.php">
        <button type="button" class="btn">編集</button>
      </a>
      <a href="delete_task.php">
        <button type="button" class="btn">削除</button>
      </a>
      <form action="logout.php" method="post" class="logout_btn">
        <input type="hidden" name="logout">
        <button type="submit" name="logout" value="ログアウト" class="btn">ログアウト
        </button>
      </form>
    </div>
  </div>

  <script>
    /*
     * Material Deesign Checkboxes non Polymer.
     * Tested and working in: IE9+, Chrome (Mobile + Desktop), Safari,
     * Opera, Firefox.
     * @author Jason Mayes 2014, www.jasonmayes.com
     */

    var wskCheckbox = function() {
      var wskCheckboxes = [];
      var SPACE_KEY = 32;

      function animateCircle(checkboxElement) {
        var circle =
          checkboxElement.parentNode.getElementsByClassName('wskCircle')[0];
        var restore = '';
        if (circle.className.indexOf('flipColor') < 0) {
          restore = circle.className + ' flipColor';
        } else {
          restore = 'wskCircle';
        }
        circle.className = restore + ' show';
        setTimeout(function() {
          circle.className = restore;
        }, 150);
      }

      function addEventHandler(elem, eventType, handler) {
        if (elem.addEventListener) {
          elem.addEventListener(eventType, handler, false);
        } else if (elem.attachEvent) {
          elem.attachEvent('on' + eventType, handler);
        }
      }

      function clickHandler(e) {
        e.stopPropagation();
        if (this.className.indexOf('checked') < 0) {
          this.className += ' checked';
        } else {
          this.className = 'wskCheckbox';
        }
        animateCircle(this);
      }

      function keyHandler(e) {
        e.stopPropagation();
        if (e.keyCode === SPACE_KEY) {
          clickHandler.call(this, e);
          // Also update the checkbox state.
          var cbox = document.getElementById(this.parentNode.getAttribute('for'));
          cbox.checked = !cbox.checked;
        }
      }

      function clickHandlerLabel(e) {
        var id = this.getAttribute('for');
        var i = wskCheckboxes.length;
        while (i--) {
          if (wskCheckboxes[i].id === id) {
            if (wskCheckboxes[i].checkbox.className.indexOf('checked') < 0) {
              wskCheckboxes[i].checkbox.className += ' checked';
            } else {
              wskCheckboxes[i].checkbox.className = 'wskCheckbox';
            }
            animateCircle(wskCheckboxes[i].checkbox);
            break;
          }
        }
      }

      function findCheckBoxes() {
        var labels = document.getElementsByTagName('label');
        var i = labels.length;
        while (i--) {
          var posCheckbox = document.getElementById(labels[i].getAttribute('for'));
          if (posCheckbox !== null && posCheckbox.type === 'checkbox' &&
            (posCheckbox.className.indexOf('wskCheckbox') >= 0)) {
            var text = labels[i].innerText;
            var span = document.createElement('span');
            span.className = 'wskCheckbox';
            span.tabIndex = i;
            var span2 = document.createElement('span');
            span2.className = 'wskCircle flipColor';
            labels[i].insertBefore(span2, labels[i].firstChild);
            labels[i].insertBefore(span, labels[i].firstChild);
            addEventHandler(span, 'click', clickHandler);
            addEventHandler(span, 'keyup', keyHandler);
            addEventHandler(labels[i], 'click', clickHandlerLabel);
            var cbox = document.getElementById(labels[i].getAttribute('for'));
            if (cbox.getAttribute('checked') !== null) {
              span.click();
            }

            wskCheckboxes.push({
              'checkbox': span,
              'id': labels[i].getAttribute('for')
            });
          }
        }
      }

      return {
        init: findCheckBoxes
      };
    }();

    wskCheckbox.init();
  </script>

</body>

</html>
