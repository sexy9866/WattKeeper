<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getDisease();
?>
<link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="menu_1.php";
	$titleName="유방 관련 질환";
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

<!-- 소제목 끝 -->

<!-- 설명 시작 -->
<div class="illness_info">
<dl>
<dt><?=$list['dName']?></dt>

<dd><br></dd>
<dd><?=$list['dDetail']?></dd>

<?if($list['dURL']!=""){?>

<dd class="detail_view"><a onClick="var openNewWindow = window.open('about:blank');openNewWindow.location.href='<?=$list['dURL']?>'" target="blank">상세 보기</a></dd>
<?}?>
</dl>
</div>
<!-- 설명 끝 -->

<!--20170330 추가분-->
<div class="notify_wrap">
	<div class="notify_box">
		<p class="notify_title">법적 한계의 고지</p>

		<div>
			<p class="notify_con">본 정보는 건강정보에 대한 소비자의 이해를 돕기 위한 참고자료일 뿐이며 개별 환자의 증상과 질병에 대한 정확한 판단을 위해서는 의사의 진료가 반드시 필요합니다.</p>
		</div>
	</div>
</div>

</body>
</html>