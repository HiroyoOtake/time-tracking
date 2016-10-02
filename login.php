<?php
require_once('functions.php');

session_start();

// if (empty($_SESSION['id']))
// {
// 	header('Location: login.php');
// 	exit;
// }

$errors['email'] = '';
$errors['password'] = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$errors = array();

	// バリデーション
	if ($email == '')
	{
		$errors['email'] = 'メールアドレスが未入力です。';
	}

	if ($password == '')
	{
		$errors['password'] = 'パスワードが未入力です。';
	}
		
	        // バリデーション突破後
	        if (empty($errors))
       	        {
     	       	$dbh = connectDB();
	      	$sql = "select * from users where email = :email and password = :password";
	       	$stmt = $dbh->prepare($sql);
	       	$stmt->bindParam(":email", $email);
		$stmt->bindParam(":password", $password);
	       	$stmt->execute();
		$row = $stmt->fetch();

		if ($row)
		{
		$_SESSION['id'] = $row['id'];
		header('Location: index.php');
		exit;
		} else { 
		echo 'ユーザーネームかメールアドレスが間違っています';
		}
		}
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>timetracking - login</title>
  </head>
  <body>
    <form action="" method="post">
	<p>
	メールアドレス: <input type="text" name="email">
	<?php if ($errors['email']) : ?>
	<?php echo '<font color="red">' .  h($errors['email']) . '</font>' ?>
	<?php endif ?>
	</p>
	<p>
	パスワード: <input type="text" name="password">
	<?php if ($errors['password']) : ?>
	<?php echo '<font color="red">' .  h($errors['password']) . '</font>' ?>
	<?php endif ?>
	</p>
      <input type="submit" value="ログイン">
    </form>
  </body>
</html>

