<?php
	// include QR_BarCode class 
	include "QR_BarCode.php"; 
	require_once("dbtools.inc.php");

	$account = $_GET['account'];
		
	$link = create_connection();
    $sql = "SELECT `ID`,`cellphone`,`petname` FROM `member_info` WHERE account='$account'";
	$result = execute_sql($link, "membership", $sql);
    echo "<font color=\"black\"><center><table border='1'></center><tr align='center'></font>";
	while ($field = $result->fetch_field())   // 顯示欄位名稱
		echo "<td>" . $field->name . "</td>";
	echo "</tr>";
	while ($row = $result->fetch_row())
	{
		echo "<tr>";
		for ($i = 0; $i < $result->field_count; $i++)
			echo "<font color=\"black\"><td align=\"center\">  $row[$i]  </td></font>";
		echo "</tr>";
	}
?>