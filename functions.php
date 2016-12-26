<?php

// エラーレベルの設定
error_reporting(E_ALL & ~E_NOTICE);

// DB接続
define('DSN','mysql:host=localhost;dbname=timetracking;charset=utf8;');
define('USER','root');
define('PASSWORD','root');

function connectDb()
{
	try {
	return new PDO(DSN, USER, PASSWORD);
	// echo '成功しました！';
} 
	catch (PDOException $e) {
	echo $e->getMessage();
	exit;
}
}

// XSS対策
function h($s)
{
	return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
}
?>
