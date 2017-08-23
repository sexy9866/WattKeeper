<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getPrevention();
?>
<link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="menu_2.php?tab=1";
	$titleName="유방암 예방 및 관리";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->


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

<!-- 소제목 시작 -->
<div class="model_name">Information</div>
<!-- 소제목 끝 -->

<!-- 설명 시작 -->
<div class="illness_info">
<dl>
<dt><?=$list['dName']?></dt>

<dd><br></dd>
<dd><?=$list['dDetail']?></dd>

</dl>
</div>
<!-- 설명 끝 -->

</body>
</html>