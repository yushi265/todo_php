<?php

require_once('../dbconnect.php');

class TaskLogic {

  /**
   * タスク一覧表示
   * @param void
   * @return array $list
   */
  public static function getTaskList() {
    $sql = "SELECT * FROM task";

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute();
      $tasklist = $stmt->fetchall();
      return $tasklist;
    } catch(\Exeption $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスクの追加
   * @param array $task
   * @return bool $result
   */
  public static function addTask($task) {
    $result = false;

    $sql = "INSERT INTO task (task) VALUES (?)";//プレースホルダー

    $arr = [];
    $arr[] = $task['task'];

    echo $task['task'];

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    } catch(\Exception $e) {
      return $result;
    }

  }

}

?>
