<?php
require_once('functions.php');

$dbh = connectDb();

// 日付の表示
$date = date("Y-m-d");
//var_dump($date);

$H = date("H");
$I = date("i");
$A = date("i",strtotime("+30 minute"));

$sec = strtotime($date);
$sec -= 60*60*24*7;
$date_old = date("Y-m-d",$sec);

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
		$error = '<font color="red">*アクションを入力して下さい。</font><br>';
	} elseif ($d1 == "") {
		$error = '<font color="red">*開始日を入力して下さい。</font><br>';
	} elseif ($g1 == "" | $m1 == "") {
		$error = '<font color="red">*開始時刻を入力して下さい。</font><br>';
	} elseif ($d2 == "") {
		$error = '<font color="red">*終了日を入力して下さい。</font><br>';
	} elseif ($g2 == "" | $m2 == "") {
		$error = '<font color="red">*終了時刻を入力して下さい。</font><br>';
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

		$stmt->execute(array(
			":action" => "$action",
			":start_time" => "$start_time",
			":end_time" => "$end_time",
			":created_at" => "$created_at"
			));
		
		header("Location:index.php");		
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
		 
				<input type="text" name="action" class="action" placeholder=" 今日は何をしましたか?">

				<input type="text" name="d1" value="<?php echo $date; ?>" class="d1">

				<select name="g1">
					<?php for ($i = 0; $i <= 23; $i++): ?>
					<option value="<?php echo $i; ?>"<?php if($H == $i): ?>selected<?php endif ?>><?php echo sprintf('%02d', $i); ?></option>
					<?php endfor ?>
				</select>

				<select name="m1">
					<?php for ($i = 0; $i <= 59; $i++): ?>
					<option value="<?php echo $i; ?>"<?php if($I == $i): ?>selected<?php endif ?>><?php echo sprintf('%02d', $i); ?></option>
					<?php endfor ?>
				</select>

				~

				<input type="text" name="d2" value="<?php echo $date; ?>" class="d2">

				<?php
				if ($I >= 30) {
					$H = date("H",strtotime("+1 hour"));
				}
				?>

				<select name="g2">
					<?php for ($i = 0; $i <= 23; $i++): ?>
					<option value="<?php echo $i; ?>"<?php if($H == $i): ?>selected<?php endif ?>><?php echo sprintf('%02d', $i); ?></option>
					<?php endfor ?>
				</select>

				<select name="m2">
					<?php for ($i = 0; $i <= 59; $i++): ?>
					<option value="<?php echo $i; ?>"<?php if($A == $i): ?>selected<?php endif ?>><?php echo sprintf('%02d', $i); ?></option>
					<?php endfor ?>
				</select>

				<input type="submit" name="time-tracking" value="登録" class='btn'>

				</form>
			
			<?php 
			if ($error != "") {
				echo $error;
			}
			?>
			</div>

			<?php
			//DBからデータを取り出す
			$sql = "select * from input_info where start_time > $date_old order by start_time DESC";
			$stmt = $dbh->prepare($sql);

			$stmt->execute();

			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$prev_date = '';
			foreach ($rows as $input) {
				$start_date = substr($input['start_time'], 0, 10);
					if ($start_date != $prev_date) {
						$dotw = date("D",strtotime($start_date));
						echo '<ul><li class="date">' . $start_date . ' ' . $dotw .'</li></ul>';
						$prev_date = $start_date;
					}						

						list($date_start, $start_time) = explode(" ",$input['start_time']);
						list($date_end, $end_time) = explode(" ",$input['end_time']);
						$start_time = substr($input['start_time'], 11, 5);
						$end_time = substr($input['end_time'], 11, 5);
						echo '<ul><li class="list">' . $input['action'] . '　' .  '<span class="action_time">' . $start_time . '~' . $end_time . '</span></li></ul>';
			}
			?>

		</div>
	</body>
</html>


