<?php

session_start();

// if (!empty($_SESSION['id']))
// {
// 	  header('Location: index.php');
// 	    exit;
//
// }
require_once('functions.php');

// $errors = array();
// $errors['name'] = '';
// $errors['email'] = '';

var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$name = $_POST['name'];
	$email = $_POST['email'];
	$errors = array();

		// バリデーション
		if ($name == '')
	        {
			$errors['name'] = 'ユーザーネームが未入力です';
	        }

	        if ($email == '')
	        {
			$errors['email'] = 'メールアドレスが未入力です';
	        }

	        // バリデーション突破後
	        if (empty($errors))
	        {
			$dbh = connectDB();
			$sql = "select * from users where name = :name and email = :email";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":name", $name);
			$stmt->bindParam(":email", $email);
			$stmt->execute();

			$row = $stmt->fetch();

			if ($row)
			{
				$_SESSION['id'] = $row['id'];
				header('Location: index.php');
				exit;

			}
			else
			{
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
	ユーザーネーム: <input type="text" name="name">
	<?php if ($errors['name']) : ?>
	<?php echo h($errors['name']) ?>
	<?php endif ?>
	</p>
	<p>
	メールアドレス: <input type="text" name="email">
	<?php if ($errors['email']) : ?>
	<?php echo h($errors['email']) ?>
	<?php endif ?>
	</p>
      <input type="submit" value="ログイン">
    </form>
  </body>
</html>

