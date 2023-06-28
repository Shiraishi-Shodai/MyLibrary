<?php

// db.phpを読み込む
require_once("db.php");
$dao = new DAO();
$user = $dao->getUser(2);

print_r($user);
