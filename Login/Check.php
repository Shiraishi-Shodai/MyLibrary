<?php
session_start();
// データベース接続情報
$host = "localhost";
$user = "root";
$pwd = "pathSQL";
$dbname = "library";
// dsnは以下の形でどのデータベースかを指定する
$dsn = "mysql:host={$host};port=3306;dbname={$dbname};";
// フォームからの入力値を取得
$login_id = $_POST['login_id'];
$password = $_POST['password'];

try {
  // PDOでデータベースのコネクションを生成
  // 第一引数dsn, 第二引数user, 第三引数password
  // newでインスタンス化して使う
  $conn = new PDO($dsn, $user, $pwd);
  // SQL文の解説:入力したユーザidに対応する行をuserテーブルから取得
  $stmt = $conn->prepare('select * from users where user_id = :login_id');
  $stmt->bindValue(":login_id", $login_id);
  $res = $stmt->execute();

  //$resultに取得した行を格納
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  //取得した行からパスワードを取得し、変数answerに代入
  $answer = isset($result["password"]) ? $result["password"] : null;
  //取得した行からuser_type_idを取得し、変数user_type_idに代入
  $user_type_id = isset($result["user_type_id"]) ? $result["user_type_id"] : null;
  // ユーザ名を取得
  $user_name = isset($result["user_name"]) ? $result["user_name"] : null;
  // ログイン成功時の処理
  if ($answer !== null && $answer == $password) {
    session_start();
    //セッション変数user_idにログインしたユーザのユーザidを代入
    $_SESSION["user_id"] = $result["user_id"];
    // セッション変数usernameにユーザ名を代入
    $_SESSION["user_name"] = $result["user_name"];

    // ユーザの所属情報を取得
    $affiliation_id = $result['affiliation_id'];
    $stmt = $conn->prepare('SELECT affiliation_name FROM affiliation WHERE affiliation_id = :affiliation_id');
    $stmt->bindValue(":affiliation_id", $affiliation_id);
    $res = $stmt->execute();
    // 所属名の取得
    $affiliation = $stmt->fetch(PDO::FETCH_ASSOC);
    //所属名をセッション変数に代入
    $_SESSION["affiliation_name"] = $affiliation["affiliation_name"];
    $_SESSION["affiliation_id"] = $affiliation_id;

    // ユーザのタイプ情報を取得
    $user_type_id = $result['user_type_id'];
    $stmt = $conn->prepare('SELECT user_type_name FROM user_type WHERE user_type_id = :user_type_id');
    $stmt->bindValue(":user_type_id", $user_type_id);
    $res = $stmt->execute();

    // ユーザータイプ名の取得
    $user_type_name = $stmt->fetch(PDO::FETCH_ASSOC);
    //ユーザータイプ名をセッション変数に代入
    $_SESSION["user_type_name"] = $user_type_name["user_type_name"];

    if ($user_type_id == 1) {
      header("Location: Student/Home/StudentHome.php"); //学生用のホーム画面に遷移
      exit;
    } elseif ($user_type_id == 2) {
      header("Location: Teacher/Home.php"); //教員用のホーム画面に遷移
      exit;
    } else {
      echo "ユーザ種別が無効です。";
    }
  } else {
    // ログイン失敗時の処理
    header("Location: LoginError.php");
  }
  //   $conn->close();
} catch (PDOException $e) {
  //データベースへの接続失敗
  $e->getMessage();
}
