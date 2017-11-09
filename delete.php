<?php

require_once 'functions.php';
//排除可能性
if (empty($_GET['item']) || !is_numeric($_GET['item'])) {
	exit('shit1');
}
$id = $_GET['item'];

//根据参数删除数据
$conn = db_connect();

$query = mysqli_query($conn,"delete from begin where id = '{$id}';");

if (!$query) {
	exit('shit2');
}
$affected_rows = mysqli_affected_rows($conn);
var_dump($affected_rows);

if ($affected_rows !== 1) {
	exit('shit3');
}

header('Location: /user-crud/inde.php');


