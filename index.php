<?php

require_once('functions.php');

session_start();

if (!empty($_SESSION['id']))
{
	header('Location: timer.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$name = $_POST['name'];
	$password = $_POST['password'];
	
	$errors = array();

	// バリデーション
	if ($name == '')
	{
		$errors['name'] = 'ユーザ名が未入力です。';
	}

	if ($password == '')
	{
		$errors['password'] = 'パスワードが未入力です。';
	}
		
	        // バリデーション突破後
	        if (empty($errors))
       	        {
			// 入力された値を持つレコードがあるか調べる
			$dbh = connectDB();
			$sql = "select * from users where user_name = :name and password = :password";
			$stmt = $dbh->prepare($sql);

			$stmt->bindParam(":name", $name);
			$stmt->bindParam(":password", $password);

			$stmt->execute();

			$row = $stmt->fetch();
			
			// 該当レコードがあった場合は$_SESSION['id']に値を持たせてtimer.phpへ
			// なかった場合は、エラーメッセージを出す

			if ($row)
			{
				$_SESSION['id'] = $row['id'];
				header('Location: timer.php');
				exit;
			} else { 
				echo 'ユーザー名またはパスワードが間違っています';
			}
		}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>timetracking - login</title>
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/login.css">
	</head>
	<body>
		<div id ="all">
			<header>
					<h1><a href="index.php"><img src="img/logo2.png" alt="ロゴ"></a></h1> 
					<p><a href="signup.php">新規登録</a></p>
				</div>
			</header>

			<main>
				<div class="loginForm">
					<video src="mov/degi.mov" muted loop autoplay></video>
					<div class="table">
						<div class="text">
							<p class="midashi">過ごした時間を記録する。</p>
							<p>これから取り掛かろうとしている作業の項目を入力し、</p>
							<p>始めるときにスタートボタン、終わるときにストップボタンを押すことで</p>
							<p>自分が何に時間を使っているかを簡単に可視化することができます。</p>
						</div>
						<form action="" method="post">
						<div class="error">
							<?php if ($errors['name']) : ?>
							<?php echo h($errors['name']) . "<br>" ?>
							<?php endif ?>
							<?php if ($errors['password']) : ?>
							<?php echo h($errors['password']) . "<br>" ?>
							<?php endif ?>
						</div>
							<p>
							<input type="text" name="name" placeholder="ユーザ名">
							</p>
							<p>
							<input type="text" name="password" placeholder="パスワード">
							</p>
							<p class="legal">ユーザ登録をしていない方は、<strong>ユーザ名/パスワード:guest</strong>でログインして頂くか、<a href="signup">新規登録</a>を行って下さい。</p>
							<p class="gotoCat"><button name="submit" class="button">ログイン</button></p>
						</form>
					</div>
				</div>
			</main>

			<footer>
				<div class="inner">
					<ul>
						<li><a href="#">プライバシー</a></li>  
						<li><a href="#">セキュリティ</a></li> 
						<li><a href="#">お問い合わせ</a></li>
					</ul>
					<p>Copyright 2016 Otake. All rights reserved.</p>
				</div>
			</footer>
		</div>
	 </body>
</html>

