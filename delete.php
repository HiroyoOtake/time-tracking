<?php

require_once('functions.php');

session_start();

$id = $_GET['id'];
$user_id = $_SESSION['id'];

$dbh = connectDB();
$sql = "select * from input_info where id = :id and user_id = :user_id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();

$row = $stmt->fetch();

if (!$row) {
	header('Location: timer.php');
	exit;
}

$sql_delete = "delete from input_info where id = :id";
$stmt_delete = $dbh->prepare($sql_delete);
$stmt_delete->bindParam(":id", $id);
$stmt_delete->execute();

header('Location: index.php');
exit;

 ?>
