<?php

require_once('../classes/TaskLogic.php');

$id = $_POST['id'];
$edited_task = $_POST['edited_task'];

$result = TaskLogic::editTask($id, $edited_task);

if(!$result) {
  exit('変更できませんでした');
} else {
  header('Location: index.php');
}

?>
