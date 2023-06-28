<?php
   // データベース接続情報
   $host = "localhost";
   $user = "root";
   $pwd = "psw";
   $dbname = "library";
   // dsnは以下の形でどのデータベースかを指定する
   $dsn = "mysql:host={$host};port=3306;dbname={$dbname};";
   try{
       //データベースに接続
       $conn = new PDO($dsn, $user, $pwd);
       // ログインしているユーザーが借りている本の名前とパスを取得するためのSQL文
       $sql = "update lent
               set return_time = NULL,
                   lendig_status = 'impossible'
               where user_id = :user_id";
       //SQL実行準備
       $stmt = $conn->prepare($sql);
       //:user_idにセッション変数から取得したuser_idを代入
       $stmt->bindValue(":user_id",$user_id);
       //SQL文を実行
       $stmt->execute();
   }catch(PDOException $e){
       //データベースへの接続失敗
       $e->getMessage();
   }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>白石学園ポータルサイト</title>
<link rel="stylesheet" href="Lent.css" type="text/css">
</head>

<body>
<div class="main">
     <div id="contents">白石学園ポータルサイト
     </div>
     <div id="login">ログイン者:大野 俊輔<br>
                     区分:学生<br>
                     学科:ITE3-2
     </div>
     
</div> <!--パンくずリスト-->
     <ul class="breadcrumb">
        <li><a href="index.php">ホーム</a></li>
        <li><a href="test.php">詳細</a></li>
    </ul>

    
<div class="books"> <!--本の詳細-->
     <img src="image/1.jpg" class="example1" style="vertical-align:top">
     <div class="text">
          <p><h2>著書名:70年でわかるアプリ開発の教科書</h3></p>
          <p><h2>著者名 : 大谷翔平</h2></p>
          <p><h2>出版社： 少年ジャンプ</h2><p>
          <p><h2>貸出状況： 貸出中</h2></p>
          <p><h2>返却予定日：-</h2></p>



<?php
    $fixedDate = date('Y-m-d'); // 固定したい日付を指定
    $timezone = 'Your_Timezone'; // 自分のタイムゾーンに合わせて設定する
    
    // 現在の日付を取得
    $currentTime = time();
    
    // 一週間後の日付を計算
    $oneWeekLater = strtotime('+1 week', strtotime($fixedDate));
    
    // 一週間後の日付から現在の日付を引いて、日数の差を計算
    $daysDifference = floor(($oneWeekLater - $currentTime) / (60 * 60 * 24));

    // 結果を出力
    echo $fixedDate;
    if ($daysDifference > 0) {
        echo "一週間後まであと " . $daysDifference . " 日あります。";
    } elseif ($daysDifference < 0) {
        echo "一週間後はすでに過ぎています。";
    } else {
        echo "今日が一週間後の日付です。";
    }
    ?>
</div>

</div> <!--貸出、戻るボタン-->
  <div class="buttonContainer">
  <button id="rentButton" onclick="openDialog()">返却する</button>
  <button id="returnButton" onclick="redirectToHome()">履歴に戻る</button>
</div>

<!--ダイアログボックスの表示-->
<div id="dialog" class="dialog">
  <div class="dialog-content">
<!--現在の日付から一週間後の日付の表氏-->
<?php
    $currentDate = date('Y-m-d');
    $oneWeekLater = date('Y年m月d日', strtotime('+1 week', strtotime($currentDate)));
    // 結果を表示
    echo '<p style="margin-top: -50px; margin-left: -10px;">'."
    この本を返却しますか？";
  ?>
      <button id="yesButton" onclick="openComplete()">はい</button>
      <button id="noButton" onclick="closeDialog()">いいえ</button>
    
<script>

</script>
  </div>
</div>

<div id="dialog2">
  <div class="dialog2-content">
  <?php
    echo  '<p style="margin-top: -50px; margin-left: -10px;">'," 返却完了！";
  ?>
  <button id="complete" class="okButton"onclick="closeDialog2()">OK</button>
  </div>
</div>

<script> 
  //はいいいえダイアログ
  function openDialog() {
    var dialog = document.getElementById("dialog");
    console.log(dialog);
    dialog.style.display = "block";
  }

    //完了ダイアログ
    function openComplete() {
    var dialog = document.getElementById("dialog2");
    closeDialog();
    dialog.style.display = "block";
  }
  
  function closeDialog() {
    var dialog = document.getElementById("dialog");
    dialog.style.display = "none";
  }

  function closeDialog2() {
    // 別のページにリダイレクト（移動）
    window.location.href = "../Home/Home.php";
  }

  //ホームに戻る
  function redirectToHome() {
    // 別のページにリダイレクト（移動）
    window.location.href = "../Home/Home.php";
  }
</script>

</body>
</html>