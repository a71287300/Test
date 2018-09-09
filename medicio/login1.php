<?php
	//啟動session
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>會員登入</title>
</head>
<body>
	<?php
		//使用 isset()方法，判別有沒有此變數可以使用，以及為已經登入
		if(isset($_SESSION['is_login']) && $_SESSION['is_login'] == TRUE):

		//使用php header來轉址到資料庫頁面
		header('location: mysqli_oo.php');
		
		else:
	?>
	<form action="checkpassword.php" method="post">
		<p>帳號：<input type="text" name="account"></br></p>
		<p>密碼：<input type="password" name="password"></br></p>
		<p><input type="submit" name="Submit" value="登入"></p>
	</form>
	<p><a href="register.html">尚未註冊嗎? 立即註冊</a></p>
	<?php endif; ?>
</body>
</html>