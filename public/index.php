<?php

require_once('../classes/TaskLogic.php');

$tasklist = TaskLogic::getTaskList();

if(!isset($tasklist)) {
  exit('表示できませんでした');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>todo</title>
</head>
<body>
  <div class="container">
    <h3>todoリスト</h3>

    <!-- タスク追加 -->
    <div class="alert alert-primary" role="alert">
      <form action="addtask.php" method="post">
        <input type="text" name="task" value="" class="input_task">
        <button type="submit" class="btn btn-primary">登録
        </button>
      </form>
    </div>

    <!-- 全タスク表示 -->
    <div class="alert alert-primary" role="alert">
      <ul class="list-group">
        <?php if($tasklist === array()): ?>
          <p>タスクが登録されていません</p>
        <?php else: ?>
          <?php foreach($tasklist as $task): ?>
            <li class="list-group-item">
              <p><?php echo $task['task'] ?></p>
              <div class="btn_group">
                <a href="edittask.php?id=<?php echo $task['id'] ?>">
                <button type="buttom" class="btn btn-primary">編集</button>
                </a>
                <a href="deletetask.php?id=<?php echo $task['id'] ?>">
                  <button type="buttom" class="btn btn-primary">削除</button>
                </a>
              </div>
            </li>
          <?php endforeach ?>
        <?php endif ?>
      </ul>
    </div>
  </div>

</body>
</html>
