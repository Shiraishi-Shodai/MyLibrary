<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: Login.php");
  exit;
}

$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "";
$user_name = isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : "";
$user_type_name = isset($_SESSION["user_type_name"]) ? $_SESSION["user_type_name"] : "";
$affiliation = isset($_SESSION["affiliation"]) ? $_SESSION["affiliation"] : "";

// データベース接続情報
$host = "localhost";
$user = "root";
$pwd = "pathSQL";
$dbname = "library";
$dsn = "mysql:host={$host};port=3306;dbname={$dbname};";

try {
  $conn = new PDO($dsn, $user, $pwd);

  // 貸出履歴の取得
  $sql = "SELECT l.book_id, l.return_time, l.return_due_date, b.image, b.book_name
          FROM lent AS l
          INNER JOIN book AS b ON l.book_id = b.book_id
          WHERE l.user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_id", $user_id);
  $stmt->execute();
  $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="History.css">
  <style>
    .book-image img {
      width: 200px;
    }

    .history-item {
      display: flex;
      /* フレックスボックスを使用して要素を横並びに配置 */
      align-items: flex-start;
      /* 要素を上寄せに配置 */
      margin-bottom: 20px;
    }

    .book-details {
      margin-left: 20px;
      /* book-imageからの間隔を設定 */
    }

    .rental-status {
      color: red;
      /* レンタル中の文字色を赤色に設定 */
    }

    .return-date {
      color: red;
    }

    .return-status {
      color: lime;
      /* 返却済みと返却日の文字色を黄緑色に設定 */
    }
  </style>
  <script>
    window.addEventListener("DOMContentLoaded", (event) => {
      const bookImages = document.querySelectorAll(".book-image img");
      bookImages.forEach((img) => {
        img.addEventListener("load", () => {
          const aspectRatio = img.naturalWidth / img.naturalHeight;
          const desiredWidth = 200;
          const desiredHeight = desiredWidth / aspectRatio;
          img.setAttribute("width", desiredWidth);
          img.setAttribute("height", desiredHeight);
        });
      });
    });
  </script>
</head>

<body>
  <header>
    <div class="header-wrapper">
      <h1>
        <a href="../Home/Home.php">図書館システム</a>
      </h1>
      <nav class="pc-nav">
        <ul>
          <li>ログイン者：<?php echo $user_name; ?></li>
          <li>区分：<?php echo $user_type_name; ?></li>
          <li>学科：<?php echo $affiliation; ?></li>
        </ul>
      </nav>
    </div>
  </header>
  <div class="header-divider"></div>
  <div class="main">
    <p class="breadcrumbs">
      <a href="../Home/Home.php">ホーム</a> &gt; 履歴
    </p>
    <h2>貸出履歴</h2>
    <?php foreach ($history as $item) : ?>
      <div class="history-item">
        <div class="book-image">
          <img src="../../../Image/<?php echo $item['image']; ?>" alt="Book Image">
        </div>
        <div class="book-details">
          <h3><?php echo $item['book_name']; ?></h3>
          <?php if ($item['return_time'] === null) : ?>
            <p class="rental-status">レンタル中</p>
            <p class="return-date">返却日：<?php echo $item['return_due_date']; ?></p>
          <?php else : ?>
            <p class="rental-status return-status">返却済み</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>

</html>