<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	//啟動session
	session_start();
	
	/* 引入檔案 */
	require_once("dbtools.inc.php");
	
	/* 取得表單資料 */
	$ID = $_POST['ID'];
	$petname = $_POST['petname'];
	$species = $_POST['species'];
	$cellphone = $_POST['cellphone'];

	/* 建立資料連接 */
	$link = create_connection();
	
	/* 將使用者姓名(account)和資料庫的 account 欄位做比對 */
	$account = $_SESSION['account'] ;
	$sql = "SELECT * FROM member_info Where account = '$account'" ;
	$result = execute_sql($link, "membership", $sql);
	
	if(mysqli_num_rows($result) != 0)
	{
		/* 釋放記憶體空間 */
		mysqli_free_result($result);
		
		echo"您所輸入的資料已登入過";
	}
	else
	{
		/* 釋放記憶體空間 */
		mysqli_free_result($result);
		
		/* 將資料寫入資料庫 */
		$sql = "INSERT INTO member_info (account, ID, petname, species, cellphone)
			VALUES ('$account', '$ID', '$petname', '$species', '$cellphone')";
	
		$result = execute_sql($link, "membership", $sql);
	
		/* 關閉資料連結 */
		mysqli_close($link);
		
		echo"輸入成功";
		header("location:mysqli_oo.php");
	}
?>
</body>
</html>