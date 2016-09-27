<?php 

require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDB();
$sql = "select * from input_info where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$row = $stmt->fetch();

if (!$row)
{
	header('Location: index.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$action = $_POST['action'];
	$starttime = $_POST['starttime'];
	$endtime = $_POST['endtime'];
	$errors = array();

	// バリデーション
	if ($starttime == '')
	{
		$errors['starttime'] = '開始時間が未入力です';
	}

	if ($endtime == '')
	{
		$errors['endtime'] = '終了時間が未入力です';
	}

	// バリデーション突破後
	if (empty($errors))
	{
		$dbh = connectDB();
		$sql = "update input_info set action = :action, start_time = :starttime, end_time = :endtime,  created_at = now() where id = :id";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":action", $action);
		$stmt->bindParam(":starttime", $starttime);
		$stmt->bindParam(":endtime", $endtime);
		$stmt->execute();

		header('Location: index.php');
		exit;
	}
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>timetracking - edit</title>
  </head>
  <body>
    <form action="" method="post">
	action: <input type="text" name="action" cols="30" rows="5" value="<?php echo h($row['action']) ?>"><br>
	starttime: <input type="text" name="starttime" cols="30" rows="5" value="<?php echo h($row['start_time']) ?>"><br>
	endtime: <input type="text" name="endtime" cols="30" rows="5" value="<?php echo h($row['end_time']) ?>"><br>
	<input type="submit" value="登録">
	<p><a href="index.php">戻る</a></p>
    </form>
  </body>
</html>
