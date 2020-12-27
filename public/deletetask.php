<?php

require_once('../classes/TaskLogic.php');

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
  <title>タスク削除</title>
</head>
<body>
  <div class="container">
    <h3>タスク削除</h3>
    <p>タスク：<?php echo $task['task'] ?></p>
    <form action="comp_delete.php" method="post">
      <button type="submit" name="id" value="<?php echo $task['id'] ?>" class="btn btn-primary">削除する</button>
    </form>
    <a href="index.php">←戻る</a>
  </div>

</body>
</html>
