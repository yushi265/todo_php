<?php
session_start();

require_once('../classes/TaskLogic.php');

//エラーメッセージ
$err = [];

//バリデーション
$task = filter_input(INPUT_POST, 'task');
if(!$task) {
  $err[] = 'タスクが入力されていません';
}
if(mb_strlen($task) > 50) {
  $err[] = '50文字以内で入力してください';
}

$taskCount = TaskLogic::countUserTask($_SESSION['login_user']['id']);

if($taskCount > 20) {
  $err[] = '登録できるのは２０個までです';
}

//エラーがなければタスクを登録
if(count($err) === 0 && $taskCount <= 20) {
  $hasAdded = TaskLogic::addTask($_POST);
  toIndex();

  if(!$hasAdded) {
    $err[] = '登録に失敗しました。';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>エラー</title>
</head>
<body>
  <div class="container">
    <h3>エラー発生</h3>
    <?php if(count($err) > 0): ?>
      <?php foreach($err as $e): ?>
        <p><?php echo $e; ?></p>
      <?php endforeach ?>
    <?php endif ?><br>
    <a href="index.php">
      <button type="buttom" class="btn btn-primary">←戻る</button>
    </a>
  </div>
</body>
</html>
