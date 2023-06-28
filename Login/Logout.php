<?php
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<title>ログアウト</title>
<meta http-equiv="refresh" content=" 5; url=Login.php"><!--5秒後にログインページに戻る-->
</head>
<body>
<h1>
<font size='5'>ログアウトしました</font>
</h1>
<p>5秒後に自動でログインページに戻ります</p>
<p><a href='Login.php'>ログインページ</a></p>
</body>
</html>