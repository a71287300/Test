<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	
	/* 引入檔案 */
	require_once("dbtools.inc.php");
	
	/* 取得表單資料 */
	$account = $_POST['account'];
	$password = $_POST['password'];

	/* 建立資料連接 */
	$link = create_connection();
	
	/* 將使用者姓名(account)和資料庫的 account 欄位做比對 */
	$sql = "SELECT * FROM member_info Where account = '$account'" ;
	$result = execute_sql($link, "membership", $sql);
	
	if(mysqli_num_rows($result) != 0)
	{
		/* 釋放記憶體空間 */
		mysqli_free_result($result);
		
		header('location: register_false.php');
	}
	else
	{
		/* 釋放記憶體空間 */
		mysqli_free_result($result);
		
		/* 將資料寫入資料庫 */
		$sql = "INSERT INTO username (account, password)
			VALUES ('$account', '$password')";
	
		$result = execute_sql($link, "membership", $sql);
	
		/* 關閉資料連結 */
		mysqli_close($link);
		
		header('location: register_finish.php');
	}
?>
</body>
</html>