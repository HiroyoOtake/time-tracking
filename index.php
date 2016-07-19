<?php

// 日付の表示
$date = date("Y-m-j");
//var_dump($now);

$fileName = $date . ".csv";
//$fileName = "2016-07-20.csv";

// データを受け取る
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //var_dump($_POST);
    $action = $_POST['action'];
    $g1 = $_POST['g1'];
    $m1 = $_POST['m1'];
    $g2 = $_POST['g2'];
    $m2 = $_POST['m2'];

$actiontime = $g1 . ":" . $m1 . "~" . $g2 . ":" . $m2; 

$data = $date . "\t" . $action . "\t" . $actiontime . "\n";
 //var_dump($data);

$fp = fopen($fileName, "a");
fwrite($fp, $data);
fclose($fp);

$posts = file($fileName, FILE_IGNORE_NEW_LINES);
// var_dump($posts);
$posts = array_reverse($posts);
// var_dump($posts);
}

?>

<!DOCTYPE html>
<html>
<head>
<meta chsrset="utf-8">
<title></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main">
<form action="" method="post">

<input type="text" name="action">

<select name="g1">
<option>--</option>
<?php for ($i = 0; $i <= 23; $i++): ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor ?>
</select>

<select name="m1">
<option>--</option>
<?php for ($i = 0; $i <= 59; $i++): ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor ?>
</select>

~

<select name="g2">
<option>--</option>
<?php for ($i = 0; $i <= 23; $i++): ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor ?>
</select>

<select name="m2">
<option>--</option>
<?php for ($i = 0; $i <= 59; $i++): ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor ?>
</select>

<input type="submit" name="time-tracking" value="登録">
</form>

<div class="comment">

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<!-- $post = "2016/07/16 アクション 00:00~00:00" -->
<?php foreach ($posts as $post) : ?>
<?php list($date, $action, $actiontime) = explode("\t", $post) ?>
<?php echo $date ?> <?php echo $action ?> <?php echo $actiontime ?><br>
<?php endforeach ?>
<?php endif ?>
</div>
</div>
</body>
</html>


