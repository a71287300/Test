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
		<style>
            /*設定gmap_canvas顯示區(寬與高)*/
            #gmap_canvas{
                width:100%;
                height:35em;
            }
        </style>

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
	<link id="bodybg" href="bodybg/bg1.css" rel="stylesheet" type="text/css" />
	<!-- template skin -->
	<link id="t-colors" href="color/default.css" rel="stylesheet">


</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">

<div id="wrapper">
	
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

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			  <ul class="nav navbar-nav">
				<li class="active"><a href="index.php">首頁</a></li>
				<li><a href="#boxes">功能</a></li>
				<li><a href="power.php">最新紀錄</a></li>
				<li><a href="Logout.php">登出</a></li>
			  </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	

	<!-- Section: intro -->
    <section id="intro" class="intro">
		<div class="intro-content">
			<div class="container">
				<div class="row">
					
				        <!--地圖顯示區-->
        <div id="gmap_canvas"></div>

        <?php
			require_once("dbtools.inc.php");
			
			if(isset($_SESSION['is_login']) && $_SESSION['is_login'] == TRUE):

			$account = $_SESSION['account'];
			$link = create_connection();
			$sql = "SELECT * FROM position WHERE account ='$account' AND latitude IS NOT NULL ORDER BY date DESC,time DESC LIMIT 1";
			$result = execute_sql($link, "membership", $sql);
		
			while ($position = $result->fetch_row())
			{
				for ($i = 1; $i < $result->field_count; $i=$i+5)
				{
					$latitude = $position[$i];
					$longitude = $position[$i+1];
					echo  "<b><font color=\"black\">$latitude</font></b></br>" ;
					echo  "<b><font color=\"black\">$longitude</font></b>";
				}
			}
			
			else:
				header('location: index.php');
			endif;
        ?>
		
							
					
					</div>					
				</div>		
			</div>
			
    </section>
	
	<!-- /Section: intro -->
	
	<!-----google map Start----->
<script src="http://maps.google.com/maps/api/js?language=zh-TW"></script>
<script>
    function init_map() {
        /*地圖參數相關設定 Start*/
        var Options = {
            zoom: 15, /*地圖縮放的比例*/
            center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>) /*所查詢位置的經緯度位置*/
        };
        
        map = new google.maps.Map(document.getElementById("gmap_canvas"), Options);   /*把地圖放在頁面的 div 裡*/
        /*地圖參數相關設定 End*/
        
        /*自行設定圖標 Start*/
        var image = {
            url: 'https://google-developers.appspot.com/maps/documentation/javascript/examples/full/images/beachflag.png', /*自定圖標檔案位置或網址*/
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(20, 32), /*寬20像素，高32像素*/
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
          };
        
        marker = new google.maps.Marker({
            map: map, /*設定要套用的地圖*/
            position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>), /*圖標經緯度位置*/
            icon: image
        });
        /*自行設定圖標 End*/
    }
    google.maps.event.addDomListener(window, 'load', init_map);
</script>
<!-----google map End----->

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

	</section>




