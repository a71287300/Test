<?php
	//啟動session
	session_start();
?>
<!DOCTYPE html>
<html lang="zh-ch"> <!--/*語系*/-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- /*可以用來提供網頁內容的資訊給瀏覽器或是搜尋引擎*/ -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>智慧項圈</title>
	
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="plugins/cubeportfolio/css/cubeportfolio.min.css">
	<link href="css/nivo-lightbox.css" rel="stylesheet" />
	<link href="css/nivo-lightbox-theme/default/default.css" rel="stylesheet" type="text/css" />
	<link href="css/owl.carousel.css" rel="stylesheet" media="screen" />
    <link href="css/owl.theme.css" rel="stylesheet" media="screen" />
	<link href="css/animate.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">

	<!-- boxed bg -->
	<link id="bodybg" href="bodybg/bg10.css" rel="stylesheet" type="text/css" />
	<!-- template skin -->
	<link id="t-colors" href="color/default.css" rel="stylesheet">


</head>

<body>
	
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="top-area">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-6">
					</div>
					<div class="col-sm-6 col-md-6">
					<p class="bold text-right">連絡電話:0975777719</p>
					</div>
				</div>
			</div>
		</div>
     <div class="container navigation">
		
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                
            </div>

            <!-- /.navbar-collapse -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			  <ul class="nav navbar-nav">
				<li class="active"><a href="index.php">首頁</a></li>
				<li><a href="#boxes">功能</a></li>
				<li><a href="power.php">最新紀錄</a></li>
				<li><a href="Logout.php">登出</a></li>
			  </ul>
            </div>
        
        <!-- /.container -->
    </nav>
	

	<!-- Section: intro -->

    <section id="intro" class="intro">
		<div class="intro-content">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
					<div class="wow fadeInDown" data-wow-offset="0" data-wow-delay="0.1s">
					<h2 class="h-ultra">智慧項圈官網</h2>
					</div>
					<div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.1s">
					<h4 class="h-light">提供 <span class="color">寵物長期紀錄</span>給您</h4>
					</div>
						<div class="well well-trans">
						<div class="wow fadeInRight" data-wow-delay="0.1s">

						<ul class="lead-list">
							<li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>長期紀錄寵物身體狀況</strong><br />&nbsp;&nbsp;</span></li>
							<li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>方便比較寵物身體狀況</strong><br />&nbsp;&nbsp;</span></li>
							<li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>幫助飼主更了解寵物狀況</strong><br />&nbsp;&nbsp;</span></li>
						</ul>

						</div>
						</div>


					</div>
					<b>
	    <?php
		require_once("dbtools.inc.php");
		
		if(isset($_SESSION['is_login']) && $_SESSION['is_login'] == TRUE):

		$account = $_SESSION['account'];
		$link = create_connection();
		$sql = "SELECT * FROM power WHERE account ='$account' ORDER BY date DESC,time DESC";
		mysqli_select_db($link, "membership");
		$result = mysqli_query($link,$sql);
		
		$data_nums = mysqli_num_rows($result);
		$per = 4;
		$pages = ceil($data_nums/$per);
		if (!isset($_GET["page"]))  //假如$_GET["page"]未設置
			$page = 1; //則在此設定起始頁數
		else 
		{
			$page = intval($_GET["page"]); //確認頁數只能夠是數值資料
			$page = ($page > 0) ? $page : 1; //確認頁數大於零 
			$page = ($pages > $page) ? $page : $pages; //確認使用者沒有輸入太神奇的數字 
		}
		$start = ($page-1)*$per; //每一頁開始的資料序號
		$result2 = mysqli_query($link,"SELECT * FROM power WHERE account ='$account' ORDER BY date DESC,time DESC LIMIT " .$start. ', '.$per) or die("Error");
		
		while($row = mysqli_fetch_array($result2))
		{ 
			for ($i = 1; $i < $result->field_count; $i=$i+5)
			{
				echo  "<font color=\"black\">室溫為攝氏$row[$i]度,</font> ";
				if($row[$i]<30)
					if($row[$i]>=20)
					echo '<font color="black">其溫度正常</br></font>';
				else
					echo '<font color="RED">其溫度異常</br></font>';
				else
					echo '<font color="RED">其溫度異常</br></font>';
				echo  $row[$i+2]. " " .$row[$i+3]."</br>"."</br>" ;
			}
		}	
		
		//分頁頁碼
		echo "<font color=\"black\">共 .$data_nums. 筆-在 .$page. 頁-共 .$pages. 頁</font>";
		echo "<br/><a href=?page=1>首頁</a> ";
		echo "第 ";
		for($i=1;$i<=$pages;$i++)
		{
			if ( $page-3 < $i && $i < $page+3 ) 
			{
				echo "<a href=?page=".$i.">".$i."</a> ";
			}
		} 
		echo " 頁 <a href=?page=".$pages.">末頁</a><br/><br/>";
		
		else:
			header('location: index.php');
		endif;
	?>
                    </b>	
				</div>		
			</div>
		</div>		
    </section>
	
	<!-- /Section: intro -->

	<!-- Section: boxes -->
    <section id="boxes" class="home-section paddingtop-80">
	
		<div class="container">
			<div class="row">
			<form action="power_info.php" method="post">
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-check fa-3x circled bg-skin"></i>
							 <input type="submit" value="長期紀錄" class="btn btn-skin btn-block btn-lg">
							<p>
							讓飼主放心出門，輕鬆瞭解寵物需要
							</p>
						</div>
					</div>
				</div>
				</form>
				<form action="QR_Code.php" method="post">
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-list-alt fa-3x circled bg-skin"></i>
							<input type="submit" value="QR_Code" class="btn btn-skin btn-block btn-lg">
							<p>
							寵物走失時方便找尋主人
							</p>
						</div>
					</div>
				</div>
				</form>
				<form action="team.html" method="post">
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							<i class="fa fa-user-md fa-3x circled bg-skin"></i>
							<input type="submit" value="製作團隊" class="btn btn-skin btn-block btn-lg">
							<p>
							一為各位會員提升網站品質
							</p>
						</div>
					</div>
				</div>
				</form>
				<form action="google map api.php" method="post">
				<div class="col-sm-3 col-md-3">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box text-center">
							
							<i class="fa fa-check fa-3x circled bg-skin"></i>
							<input type="submit" value="追蹤系統" class="btn btn-skin btn-block btn-lg">
							<p>
							防止寵物走失，設有連線功能
							</p>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>

	</section>


  </body>
</html>