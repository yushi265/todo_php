## <todo_php> phpでデータベースを用いてタスク管理を行うアプリ。

### 〇ファイル構成

     インデックス  
        - index.php  
            - 全タスクを取得して一覧表示、取得できなかったらメッセージ表示
            - ボタン一つで昇順・降順の切り替えができるようにした
            - クエリストリングがないときは追加日昇順へリンク
            - 新しいタスクを入力し、期限日を設定して、登録ボタンを押すとaddtask.phpへ
            - 編集ボタンをクリックするとedittask.phpへ
            - 削除ボタンをクリックするとdeletetask.phpへ

    タスク追加  
        - addtask.php  
            - index.phpからPOSTで値を受け取る
            - 未入力か50文字以上をバリデーションし$errにメッセージを入れて表示
            - $errが0の時TaskLogit::addTask();を実行
            - 登録が成功したらindex.phpに戻す

    タスク内容編集  
        - edit_task.php  
            - index.phpからGETでタスクのidを受け取る
            - TaskLogit::getTaskById();でタスク内容を取得し表示
            - 変更内容を入力しcomp_edit.phpへ送信

        - comp_edit.php  
            - edittask.phpからPOSTでidとedited_taskを受け取る
            - TaskLogit::editTask();でタスク内容を変更
            - 成功したらindex.phpへ戻す

    タスク削除  
        - delete_task.php  
            - index.phpからGETでタスクのidを受け取る
            - TaskLogit::getTaskById();でタスク内容を取得し表示
            - 確認ボタンを押すとcomp_delete.phpへ送信

        - comp_delete.php  
            - deleetask.phpからPOSTでidを受け取る
            - TaskLogit::deleteTask();でタスクを削除
            - 成功したらindex.phpへ戻す

    その他  
        - env.php  
            - データベースのデータを定数で定義  
        - dbconnect.php  
            - データベース接続を行う関数。$pdoを返す  
        - functions.php  
            - セキュリティ対策の関数。エスケープ処理。  
    
### 〇アップデート
  
    - 12/26 タスク追加、編集、削除追加  
    - 12/27 エスケープ処理、追加日登録追加  
    - 12/28 期限日の登録機能追加  
    - 12/29 ユーザー登録・ログイン機能追加  
    - 12/30 ログアウト、ログインチェック、CSRF対策  
    - 12/31 ソート機能、編集・削除にページ追加  
    - 1/4 クエリストリングでソート、ボタン一つで昇降順切替可に
    - 1/5 ページネーション追加
    - 1/6 昇降順がわかるようにアイコン表示、ページ前へ次へボタン追加
    - 1/9 UI見直し、ボタン見直し、エラー修正
    - 1/10 チェックボタン、ページャー変更、タスク詳細ページから編集できるように変更
    - 1/11 詳細画面で削除できるように、
### 〇追加したい機能

    - ページネーション  
    - 検索  
  
### 〇メモ
    - SQL文、ORDER BYに変数を代入する場合
    - × $sql = "SELECT * FROM task WHERE user_id = ? ORDER BY :sort :order;
    - プレースホルダーを使うとエラーになるため、
    - 〇 $sql = "SELECT * FROM task WHERE user_id = ? ORDER BY ".$sort." ".$order;
    - とするとうまくいく。
