<?php

require_once('../classes/TaskLogic.php');

$task_id = $_GET['id'];

$result = TaskLogic::deleteTask($task_id);

if($result) {
  header('Location: index.php');
} else {
  exit('削除できませんでした');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>タスク完了</title>
</head>
<body>
  <div class="container">
  </div>


</body>
</html>
