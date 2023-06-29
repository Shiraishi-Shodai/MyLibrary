<?php

// DAO.php,USER.phpを読み込み
require_once("../Test_DB/db.php");
require_once("../Test_DB/User.php");

$login_id = $_POST["login_id"];
$password = $_POST["password"];

// データベースに接続
$dao = new DAO();
// ユーザーオブジェクトを生成
$user = $dao->getUser($login_id);
// 入力したログインIDに対応するパスワードをanswerに格納
$answer = $user->getPassword();

// ログイン成功時の処理
if ($answer !== null && $answer == $password) {

  session_start();
  $_SESSION["user_id"] = $user->getUserId();
  // ログインしたユーザーのuser_type_idを変数user_type_idに格納
  $user_type_id = $user->getUserTypeId();

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
