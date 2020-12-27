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

  /**
   * idからタスクを引き出す
   * @param int $id
   * @return array $result
   */
  public static function getTaskById($task_id) {
    $sql = 'SELECT * FROM task WHERE id = ?';

    $arr = [];
    $arr[] = $task_id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $task = $stmt->fetch();
      return $task;
    } catch(\Exeption $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスクを編集する
   * @param string $id
   * @param string $edited_task
   * @return bool $result
   */
  public static function editTask($id, $edited_task) {
    $sql = 'UPDATE task SET task = ? WHERE id = ?';

    $arr = [];
    $arr[] = $edited_task;
    $arr[] = $id;

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    } catch(\Exeption $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスクを削除する
   * @param string $task_id
   * @return bool $result
   */
  public static function deleteTask($task_id) {
    $sql = 'DELETE FROM task WHERE id = ?';

    $arr = [];
    $arr[] = $task_id;

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    } catch(\Exeption $e) {
      exit('表示できませんでした');
    }
  }
}

?>