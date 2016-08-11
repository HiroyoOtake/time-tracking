<?php

define('DSN','mysql:host=localhost;dbname=timetracking;charset=utf8;');
define('USER','root');
define('PASSWORD','root');

function connectDb()
{
// $dsn = 'mysql:host=localhost;dbname=timetracking;charset=utf8;';
// $user = 'root';
// $password = 'root';

try {
	return new PDO(DSN, USER, PASSWORD);
        // echo '成功しました！';
		} 
	        catch (PDOException $e) {
        	echo $e->getMessage();
       		exit;
    		}
}

function h($s)
{
	 return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
}
?>
