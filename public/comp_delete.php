<?php

require_once('../classes/TaskLogic.php');

$id = $_POST['task_id'];

$result = TaskLogic::deleteTask($id);

if(!$result) {
  exit('削除できませんでした');
} else {
  header('Location: index.php');
}

?>
