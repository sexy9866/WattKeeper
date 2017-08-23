<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getSurvey();
?>

<link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<style>
	body{position:relative; background:#e6e7e8;}
</style>
<body>

<!-- 헤더 시작 -->
<? 
	//$parentPage="../index.php";
	$titleName="설문조사";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 리스트4내용 시작 -->
<div class="list4_read">
	<ul>
		<li>
			<p><span><설문조사></span> <?=$list['sName']?></p>
			<p class="date">2016.12.01</p>
			<div style="clear:both;"></div>
		</li>
		<li class="event_con">
			<!-- 상징이미지 시작 -->
<?if($list[imgPath1]!="" && $list[imgPath1]!=null && $list[imgPath1]!="null" && $list[imgPath1]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath1]?>' width="100%"/></div><?}?>
<?if($list[imgPath2]!="" && $list[imgPath2]!=null && $list[imgPath2]!="null" && $list[imgPath2]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath2]?>' width="100%"/></div><?}?>
<?if($list[imgPath3]!="" && $list[imgPath3]!=null && $list[imgPath3]!="null" && $list[imgPath3]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath3]?>' width="100%"/></div><?}?>
<?if($list[imgPath4]!="" && $list[imgPath4]!=null && $list[imgPath4]!="null" && $list[imgPath4]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath4]?>' width="100%"/></div><?}?>
<?if($list[imgPath5]!="" && $list[imgPath5]!=null && $list[imgPath5]!="null" && $list[imgPath5]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath5]?>' width="100%"/></div><?}?>
<?if($list[imgPath6]!="" && $list[imgPath6]!=null && $list[imgPath6]!="null" && $list[imgPath6]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath6]?>' width="100%"/></div><?}?>

<!-- 상징이미지 끝 -->
			<p><?=$list['sDetail']?></p>
		</li>
	</ul>
</div>
<!-- 리스트4내용 끝 -->

<!-- 확인버튼 -->
<div onClick="var openNewWindow = window.open('about:blank');openNewWindow.location.href='<?=$list['sURL']?>'" class="foot_checkup_btn"><p>설문조사 하러 가기</p></div>

</body>
</html>