<?php
session_start();

if (!empty($_SESSION['id']))
{
	  header('Location: index.php');
	  exit;

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

