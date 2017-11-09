<?php
/**
 * 获取数据库的连接对象	
 */

function db_connect() {
	//获取桥梁
	$conn = mysqli_connect('localhost','root','123','demo');
	if (!$conn) {
		die('连接数据失败');
	}
	//设置此次连接通过UTF8编码解析数据
	mysqli_set_charset($conn,'utf8');

	return $conn;
}

/**
 * 
 */



