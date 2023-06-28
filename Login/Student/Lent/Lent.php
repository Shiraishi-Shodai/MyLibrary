<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

// POSTされたJSON文字列を取り出し
$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
print($raw);
$data = json_decode($raw); // json形式をphp変数に変換
print($data);

var_dump($json);
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "";
$user_name = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : "";
$user_type_name = isset($_SESSION["user_type_name"]) ? $_SESSION["user_type_name"] : "";
$affiliation_name = isset($_SESSION["affiliation_name"]) ? $_SESSION["affiliation_name"] : "";
// データベース接続情報
$host = "localhost";
$user = "root";
$pwd = "pathSQL";
$dbname = "library";
// dsnは以下の形でどのデータベースかを指定する
$dsn = "mysql:host={$host};port=3306;dbname={$dbname};";
try {
  //データベースに接続
  $conn = new PDO($dsn, $user, $pwd);
  // bookテーブルからbook_idに対応する書籍情報を取得するためのSQL文
  $sql = "SELECT * FROM book WHERE book_id = :book_id";
  // SQL実行準備
  $stmt = $conn->prepare($sql);
  // :book_idにセッション変数から取得したbook_idを代入
  $stmt->bindValue(":book_id", $_POST["book_id"]);
  // SQL文を実行
  $stmt->execute();

  // 結果を取得
  $book = $stmt->fetch(PDO::FETCH_ASSOC);
  // 書籍の情報を変数に格納
  $book_name = $book['book_name'];
  $author = $book['author'];
  $publisher = $book['publisher'];
  // lentテーブルから該当の行を取得するためのSQL文
  $sql = "SELECT * FROM lent WHERE user_id = :user_id AND book_id = :book_id";
  // SQL実行準備
  $stmt = $conn->prepare($sql);
  // パラメーターに値をバインド
  $stmt->bindValue(":user_id", $user_id);
  $stmt->bindValue(":book_id", $_POST["book_id"]);
  // SQL文を実行
  $stmt->execute();
  // 結果を取得
  $lentInfo = $stmt->fetch(PDO::FETCH_ASSOC);
  // 貸出状況と返却予定日を変数に格納
  $lending_status = $lentInfo['lending_status'];
  $return_due_date = $lentInfo['return_due_date'];
  $image = $lentInfo['image'];
} catch (PDOException $e) {
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
    <!--ヘッダー-->
    <div class="main">
      <div id="contents"><a href="../Home/StudentHome.php">白石学園ポータルサイト</a>
      </div>
      <div id="login">
        ログイン者:<?php echo $user_name ?><br>
        区分:<?php echo $user_type_name ?><br>
        学科:<?php echo $affiliation_name ?>
      </div>
    </div>
  </div> <!--パンくずリスト-->
  <ul class="breadcrumb">
    <li><a href="../Home/StudentHome.php">ホーム</a></li>
  </ul>
  <div class="books"> <!--本の詳細-->
    <img src="../../../Image/<?php $image ?>" class="example1" style="vertical-align:top">
    <div class="text">
      <p>
      <h2>著書名:<?php echo $book_name; ?></h2>
      </p>
      <p>
      <h2>著者名 :<?php echo $author; ?></h2>
      </p>
      <p>
      <h2>出版社：<?php echo $publisher; ?></h2>
      <p>
      <p>
      <h2>貸出状況： <?php echo $lending_status; ?></h2>
      </p>
      <p>
      <h2>返却予定日： <?php echo $return_due_date; ?></h2>
      </p>
    </div>
  </div> <!--貸出、戻るボタン-->
  <div class="buttonContainer">
    <button id="rentButton" onclick="openDialog()">貸出する</button>
    <button id="returnButton" onclick="redirectToHome()">戻る</button>
  </div>
  <!--ダイアログボックスの表示-->
  <div id="dialog" class="dialog">
    <div class="dialog-content">
      <!--現在の日付から一週間後の日付の表氏-->
      <?php
      $currentDate = date('Y-m-d');
      $oneWeekLater = date('Y年m月d日', strtotime('+1 week', strtotime($currentDate)));
      // 結果を表示
      echo '<p style="margin-top: -50px; margin-left: -10px;">' . $oneWeekLater  . " まで
    この本を借りますか？";
      ?>
      <button id="yesButton" onclick="openComplete()">はい</button>
      <button id="noButton" onclick="closeDialog()">いいえ</button>
      <script>
      </script>
    </div>
  </div>
  <div id="dialog2">
    <div class="dialog2-content">
      <button id="complete" onclick="closeDialog2()">完了しました</button>
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
      var dialog = document.getElementById("dialog2");
      dialog.style.display = "none";
    }
    //ホームに戻る
    function redirectToHome() {
      // 別のページにリダイレクト（移動）
      window.location.href = "../Home/StudentHome.php";
    }
  </script>
</body>

</html>