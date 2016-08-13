<?php
//delete
require_once('functions.php');

$dbh = connectDb();

// 現在時刻の UNIX timestamp
$now = time();

// 日付の表示
$date = date("Y-m-d",$now);
//var_dump($date);

$H = date("H",$now);  //現在時刻の時
$I = date("i",$now);  //現在時刻の分
$A = date("i",$now + 60 * 30); //現在時刻の30分後

$date_old = date("Y-m-d",$now - 60*60*24*7);

// データを受け取る
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'];
	$d1 = $_POST['d1'];
	$g1 = $_POST['g1'];
	$m1 = $_POST['m1'];
	$g2 = $_POST['g2'];
	$m2 = $_POST['m2'];
	$d2 = $_POST['d2'];

	if ($action == "") {
		$error = 'アクションを入力して下さい。';
	} elseif ($d1 == "") {
		$error = '開始日を入力して下さい。';
	} elseif ($g1 == "" | $m1 == "") {
		$error = '開始時刻を入力して下さい。';
	} elseif ($d2 == "") {
		$error = '終了日を入力して下さい。';
	} elseif ($g2 == "" | $m2 == "") {
		$error = '終了時刻を入力して下さい。';
	} else {
		$error = '';
		
		$start_time = $d1 . " " . $g1 . ":" . $m1 . ":00";
		$end_time = $d2 . " " .  $g2 . ":" . $m2 . ":00";
		$created_at = date("Y-m-d H:i:s");
		
		$sql = "insert into input_info (action, start_time, end_time, created_at) values (:action, :start_time, :end_time, :created_at)";
		$stmt = $dbh->prepare($sql);

		$stmt->bindParam(":action", $action);
		$stmt->bindParam(":start_time", $start_time);
		$stmt->bindParam(":end_time", $end_time);
		$stmt->bindParam(":created_at", $created_at);

		$stmt->execute();
		
		header("Location:index.php");		
		exit;
	}
}



?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>timetracking</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<header>
			<div class="top-nav contain-to-grid wrapper">
			<nav class="top-bar">
			<li class="name">〠 TIME TRACKING</li>

			</nav>
			</div>
		</header>

		<div class="timer">
			<div class="form">
				<form action="" method="post">
		 
				<input type="text" name="action" class="action" placeholder="  今日は何をしましたか?">

				<input type="text" name="d1" value="<?php echo $date; ?>" class="d1">
				<input type="text" name="g1" value="<?php echo sprintf('%02d', $H); ?>" class="d2">
				:
				<input type="text" name="m1" value="<?php echo sprintf('%02d', $I); ?>" class="d2">
				~
				<input type="text" name="d2" value="<?php echo $date; ?>" class="d1">

				<?php if ($I >= 30): ?> 
					<?php $H = date("H",strtotime("+1 hour")); ?>
				<?php endif ?>

				<input type="text" name="g2" value="<?php echo sprintf('%02d', $H); ?>" class="d2">
				:
				<input type="text" name="m2" value="<?php echo sprintf('%02d', $A); ?>" class="d2">
				<input type="submit" name="time-tracking" value="登録" class='btn'>

				</form>
			</div>

			<div class="error">
				<?php if ($error): ?> 
				<font color="red">* <?php echo h($error); ?></font><br>
				<?php endif ?>
			</div>

			<?php
			//DBからデータを取り出す
			$sql = "select * from input_info where start_time > :date_old order by start_time DESC";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":date_old", $date_old);
			$stmt->execute();

			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$prev_date = '';
			?>

			<?php foreach ($rows as $input): ?> 
			       <?php $start_date = substr($input['start_time'], 0, 10); ?>

			<?php if ($start_date != $prev_date): ?> 
				<?php $prev_date = $start_date; ?>
				<ul><li class="date"> <?php echo h($start_date); ?> <?php echo date("D",strtotime($start_date)); ?> </li></ul>
				<?php endif ?>
										
			<ul><li class="list"> <?php echo $input['action']; ?> <span class="action_time"> <?php echo substr($input['start_time'],11,5) ?> ~ <?php echo substr($input['end_time'],11,5) ?> </li></ul>
			<?php endforeach ?>
			</div)

	</body>
</html>


