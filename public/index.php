<?php

require_once('../functions.php');
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
    <h3>ToDo LISTS</h3>
    <p>current time：<?php echo getNow()?></p>

    <!-- タスク追加 -->
    <div class="alert alert-primary" role="alert">
      <form action="addtask.php" method="post">
        <input type="text" name="task" value="" placeholder="Enter task" class="input_task">
        until
        <select name="due_date">
          <option value="">-</option>
          <?php for($i = 0; $i < 10; $i++): ?>
            <option value="<?php echo date('n/j', strtotime('+'.$i.'day')); ?>">
              <?php echo date('n/j', strtotime('+'.$i.'day')); ?>
            </option>
          <?php endfor ?>
        </select>
        <button type="submit" class="btn btn-primary">add
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
                <th scope="col">#</th>
                <th scope="col">task</th>
                <th scope="col">date added</th>
                <th scope="col">duedate</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tasklist as $task): ?>
                <tr>
                  <!-- ID -->
                  <th scope="row"><?php echo $task['id'] ?></th>
                  <!-- タスク -->
                  <td><?php echo $task['task'] ?></td>
                  <!-- 登録時間 -->
                  <td><?php echo substr($task['created'],5,14); ?></td>
                  <!-- 期限日 -->
                  <td>
                    <?php if($task['due_date'] === '0000-00-00'): ?>
                      <p>　-</p>
                    <?php else: ?>
                      <p><?php echo substr($task['due_date'],5,5); ?></p>
                    <?php endif ?>
                  </td>
                  <!-- ボタン -->
                  <td>
                    <div class="btn_group">
                      <a href="edittask.php?id=<?php echo h($task['id']) ?>">
                        <button type="buttom" class="btn btn-primary">edit</button>
                      </a>
                      <a href="deletetask.php?id=<?php echo h($task['id']) ?>">
                        <button type="buttom" class="btn btn-primary">delete</button>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      <?php endif ?>
    </div>
  </div>

</body>
</html>
