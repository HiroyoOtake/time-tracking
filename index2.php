<?php

session_start();

if (empty($_SESSION['id']))
{
	 header('Location: login.php');
	 exit;
}

//delete
require_once('functions.php');

$dbh = connectDb();


// 現在時刻の UNIX timestamp
$now = time();

// 日時の表示
$date = date("Y-m-d",$now);
$date_and_time = date("Y-m-d H:i:s",$now);

// 一週間前の日付を表示
$date_7days_ago = date("Y-m-d",$now - 60*60*24*7);

$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$action = $_POST['action'];

			// 入力データ チェック
			if ($action == "") {
				$error = 'アクションを入力して下さい。';
			}

			// 入力データにエラーがない場合
			if (!$error) {

			// $_SESSION['start_time'] が
			//   セットされていない場合は、スタートボタンが押された時
			//   セットされている場合は、ストップボタンが押された時
			if (!isset($_SESSION['start_time'])) {

			// スタートボタンが押された時刻を start_time とする
			$_SESSION['start_time'] = $date_and_time;
			$_SESSION['action']     = $action;

			// ブラウザで更新ボタンを押してもアラートが出ないようにする。
			// (同じページへリダイレクトすることで、GET でアクセスし直すため)
																													header("Location: index.php");
																													exit;
																													}
																													// 以下はストップボタンが押された時
			else {

				$start_time = $_SESSION['start_time'];
				$end_time   = $date_and_time;
				$created_at = $date_and_time;

				$sql = "insert into input_info (action, start_time, end_time, created_at) values (:action, :start_time, :end_time, :created_at)";
				$stmt = $dbh->prepare($sql);

				$stmt->bindParam(":action", $action);
				$stmt->bindParam(":start_time", $start_time);
				$stmt->bindParam(":end_time", $end_time);
				$stmt->bindParam(":created_at", $created_at);

				$stmt->execute();

				unset($_SESSION['action']);
				unset($_SESSION['start_time']);

				header("Location: index.php");
				exit;
				}
			}
}
// STARTを押した後、別のブラウザで開いた時に必要な処理
if (isset($_SESSION['start_time'])) {
	$btn_word   = "STOP";
	$btn_design = "stop_btn";
} else {
	$btn_word   = "START";
	$btn_design = "start_btn";

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
			<li class="name"> TIME TRACKING</li>

			</nav>
			</div>
		</header>

		<div class="timer">
			<div class="form">
				<form action="" method="post">
				
				<input type="text" name="action" class="action" placeholder="  今日は何をしましたか?" 
					<?php if (isset($_SESSION['start_time'])):?>
						value="<?php echo $_SESSION['action']; ?>"
					<?php endif ?>
				>

				<input type="hidden" name="start_time" value="<?php echo $date_and_time; ?>">
				
				<?php if(isset($_SESSION['start_time'])): ?>
				<input type="hidden" name="end_time" value="<?php echo $date_and_time; ?>">
				<input type="hidden" name="start_time_at_STOP" value="<?php echo $start_time; ?>">
				<?php endif ?>

				<input type="submit" name="time-tracking" value="<?php echo $btn_word ?>" class="<?php echo $btn_design ?>">

				</form>
			</div>

			<!-- アクション未入力時のエラーを表示 -->
			<div class="error">
				<?php if ($error): ?> 
				<font color="red">* <?php echo h($error); ?></font><br>
				<?php endif ?>
			</div>

			<?php
			//一週間前までのデータを表示
			$sql = "select * from input_info where start_time > :date_7days_ago order by start_time DESC";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(":date_7days_ago", $date_7days_ago);
			$stmt->execute();

			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$prev_date = '';
			?>

			<?php foreach ($rows as $input): ?> 
			       <?php $start_date = substr($input['start_time'], 0, 10); ?>

			<?php if ($start_date != $prev_date): ?> 
				<?php $prev_date = $start_date; ?>
				<ul><li class="date">
				<?php echo h($start_date); ?> <?php echo date("D",strtotime($start_date)); ?> 
				</li></ul>

			<?php endif ?>
			<ul><li class="list">
			<?php echo $input['action']; ?> <span class="action_time"> <?php echo substr($input['start_time'],11,5) ?> ~ <?php echo substr($input['end_time'],11,5) ?> 
			<a href="edit.php?id=<?php echo h($input['id']) ?>">[edit]</a>
			<a href="delete.php?id=<?php echo h($input['id']); ?>" class="delete_btn">