<?php

require_once('../dbconnect.php');
require_once('../functions.php');

class TaskLogic {

  /**
   * リミットを取得
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

    $sql = "SELECT * FROM task WHERE user_id = ? AND completed IS NULL ORDER BY ".$sort." ".$order;
    $sql .= " LIMIT ".$offset_num.",".$limit;

    $arr = [];
    $arr[] = $user_id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $tasklist = $stmt->fetchall();
      return $tasklist;
    } catch(\Exception $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * 完了済みタスクを取得
   * @param int $user_id
   * @return array $comp_list
   */
  public static function getUserCompTask($user_id) {

    $sql = "SELECT * FROM task WHERE user_id = ? AND completed IS NOT NULL LIMIT 5";

    $arr = [];
    $arr[] = $user_id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $tasklist = $stmt->fetchall();
      return $tasklist;
    } catch (\Exception $e) {
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
    } catch(\Exception $e) {
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
  public static function getTaskById($id) {

    $sql = 'SELECT * FROM task WHERE id = ?';

    $arr = [];
    $arr[] = $id;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($arr);
      $task = $stmt->fetch();
      return $task;
    } catch(\Exception $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスクを編集する
   * @param int $id
   * @param string $edited_task
   * @return bool $result
   */
  public static function editTask($data) {
    $sql = 'UPDATE task SET task = ?, due_date = ?, memo = ? WHERE id = ?';

    $arr = [];
    $arr[] = $data['task'];
    $arr[] = $data['due_date'];
    $arr[] = $data['memo'];
    $arr[] = $data['id'];

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    } catch(\Exception $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスクを完了する
   * @param int $task_id
   * @return bool $result
   */
  public static function compTask($task_id) {
    $sql = 'UPDATE task SET completed = ? WHERE id = ?';

    $arr = [];
    $arr[] = getNowDate();
    $arr[] = $task_id;

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    } catch (\Exception $e) {
      exit('表示できませんでした');
    }
  }

  /**
   * タスクを未完了に戻す
   * @param int $task_id
   * @return bool $result
   */
  public static function undoTask($task_id)
  {
    $sql = 'UPDATE task SET completed = NULL WHERE id = ?';

    $arr = [];
    $arr[] = $task_id;

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    } catch (\Exception $e) {
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
    } catch(\Exception $e) {
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
    } catch(\Exception $e) {
      exit('表示できませんでした');
    }
  }
}
?>
