<?php

require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDB();
$sql = "select * from input_info where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
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
