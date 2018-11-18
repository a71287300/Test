<?php
//啟動session
session_start();
// include QR_BarCode class 
include "QR_BarCode.php"; 
require_once("dbtools.inc.php");
$string = '122.116.196.99/medicio/data.php?account=';
    if(isset($_SESSION['is_login']) && $_SESSION['is_login'] == TRUE):
		$account = $_SESSION['account'];
        $string = $string.$account;

    else:
        header('location: index.php');
    endif;
    
// QR_BarCode object 
$qr = new QR_BarCode(); 

// create text QR code 
$qr->url($string);

// display QR code image
$qr->qrCode();
?>