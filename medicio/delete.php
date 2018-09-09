<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	require_once("dbtools.inc.php");
	$account = $_POST['account'];
		
	/* 建立資料連接 */
	$link = create_connection();
		
	/* 刪除資料 */
	$sql = "DELETE FROM member_info Where account = '$account'";
	$result = execute_sql($link, "membership", $sql);
		
	/* 關閉資料連接 */
	mysqli_close($link);
		
	echo"刪除成功";

?>