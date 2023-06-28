<?php
    session_start();
    $user_id = $_SESSION["user_id"];

    // データベース接続情報
    $host = "localhost";
    $user = "root";
    $pwd = "pathSQL";
    $dbname = "library";
    // dsnは以下の形でどのデータベースかを指定する
    $dsn = "mysql:host={$host};port=3306;dbname={$dbname};";

    try{
        //データベースに接続
        $conn = new PDO($dsn, $user, $pwd);
        $sql = "select * from book where affiliation_id 
                = (select affiliation_id from user where user_id = :user_id)";
        //SQL実行準備
        $stmt = $conn->prepare($sql);
        //:user_idにセッション変数から取得したuser_idを代入
        $stmt->bindValue(":user_id",$user_id);
        //SQL文を実行
        $stmt->execute();
        //bookテーブルの情報を変数resultに配列として代入
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
        //foreachでresultに代入された内容を表示
        foreach($result as $book){
            //print_r($book);
        }
    }catch(PDOException $e){
        //データベースへの接続失敗
        $e->getMessage();
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>
<body>
<div class="main">
        <div id="contents">白石学園ポータルサイト
        </div>
        <div id="login">ログイン者:アイドル星野　俊輔<br>
                        区分:学生<br>
                        学科:ITE3-2
        </div>
   </div>

   <p class="search" >
   <input type="text" id="search-input" placeholder="検索欄">
    <button id="search-button" onclick="searchImages()">検索</button></p>
   
    <div class="image-container"></div>
 
</body>
</html>
