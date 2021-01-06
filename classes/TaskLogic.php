<?php

require_once('../dbconnect.php');
require_once('../functions.php');

class TaskLogic {

  /**
   * リミットを5に固定
   * @return int $limit
  */
  public static function getLimit() {
    return $limit = 5;
  }

  /**
   * 最大のページ数を取得
   * @param int $user_id
   * @return int $result
   */
  public static function getMaxPage($user_id) {
    $taskCount = self::countUserTask($user_id);
    $limit = self::getLimit();
    $result = ceil($taskCount / $limit);
    return $result;
  }

  /**
   * タスク一覧表示
   * @param string $user_id
   * @param string $sort
   * @param string $order
   * @return array $list
   */
  public static function getUserTask($user_id, $sort, $order, $page) {
    $limit = self::getLimit();

    $offset_num = $limit * ($page - 1);

    $sql = "SELECT * FROM task WHERE user_id = ? ORDER BY ".$sort." ".$order;
    $sql .= " LIMIT ".$offset_num.",".$limit;

    $arr = [];
    $arr[] = $user_id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $tasklist = $stmt->fetchall();
      return $tasklist;
    } catch(\Exeption $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスク一覧表示
   * @param string $user_id
   * @return array $list
   */
  public static function getUserTaskAll($user_id) {

    $sql = "SELECT * FROM task WHERE user_id = ?";

    $arr = [];
    $arr[] = $user_id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
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

    $sql = "INSERT INTO task (task, created, due_date, user_id) VALUES (?, ?, ?, ?)";//プレースホルダー

    $arr = [];
    $arr[] = $task['task'];
    $arr[] = getNow();
    $arr[] = $task['due_date'];
    $arr[] = $task['user_id'];

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
   * @param int $id
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
   * @param int $task_id
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

  /**
   * ユーザーのタスクをカウント
   * @param int $user_id
   * @return int $result
   */
  public static function countUserTask($user_id) {
    $sql = "SELECT COUNT(*) FROM task WHERE user_id = ?";

    $arr = [];
    $arr[] = $user_id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $result = $stmt->fetchColumn();
      return $result;
    } catch(\Exeption $e) {
      exit('表示できませんでした');
    }
  }
}
?>
