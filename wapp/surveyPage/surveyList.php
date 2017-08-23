<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<? 
	$obj = new ApiGeneral( $_REQUEST );
	$list = $obj->getSurveyList();
	$endPage = $obj->endPage;
?>

<link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<style>
	body{position:relative;}
</style>
<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="../index.php";
	$titleName="설문조사";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 리스트4 시작 -->
<div class="list4">
	<ul>
	
		<?for($i = 0; $i < sizeof($list); $i++){?>
		<li onClick="window.location.href='surveyDetail.php?sNumber=<?=$list[$i]['sNumber']?>'"><!-- li한개당 리스트1개 -->
			<p><span><설문조사> </span><?=$list[$i]['sName']?></p>
			<p class="date"><?=$list[$i]['regDate']?></p>
			<div style="clear:both;"></div>
		</li>
		<?}?>
	</ul>
</div>
<!-- 리스트4 끝 -->

 <?include "../static/fpager.php" ?>

</body>
</html>