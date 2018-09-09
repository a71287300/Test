<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	//啟動session
	session_start();
	
	require_once("dbtools.inc.php");
	header("Content-type:text/html");
	header("charset=utf-8");
	
	//取得表單資料
	$account = $_POST["account"];
	$password = $_POST["password"];
	
	//建立資料連接
	$link = create_connection();
	
	//將帳號與密碼和資料庫的紀錄作比對
	$sql = "SELECT * FROM username Where account = '$account' AND password = '$password'";
	$result = execute_sql($link, "membership", $sql);
	
	//若飼主名子與密碼錯誤，就顯示對話方塊要求查明後再登入
	if(mysqli_num_rows($result) == 0)
	{
		//釋放記憶體空間
		mysqli_free_result($result);
		
		//關閉資料連接
		mysqli_close($link);
		
		//將session加入一個失敗的紀錄
		$_SESSION['is_login'] = false;
		
		echo"帳號或密碼錯誤，請查明後再登入";
	}
	//否則將資料寫入Cookie，然後導向到會員專區網頁
	else
	{
		//比對登入的帳號
		$_SESSION['account'] = $account;
		
		//將帳號與密碼和資料庫的紀錄作比對
		$sql_1 = "SELECT * FROM member_info Where account = '$account'";
		$result = execute_sql($link, "membership", $sql_1);
		
		//將session加入一個已經登入的紀錄
		$_SESSION['is_login'] = true;
		
		if(mysqli_num_rows($result) == 0)
				header("location:member_info.html");
		else
			header("location:mysqli_oo.php");
	}
?>