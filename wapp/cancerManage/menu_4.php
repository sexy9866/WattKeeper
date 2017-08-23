<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getCommunityList();
?>
<!DOCTYPE>
<html>
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title></title>
<link href="../css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>
<script>
$(document).ready(function(){
	$(".links").click(function(){
		if($(this).attr('url') == ""){
			gweb.alert("알림", "홈페이지 정보가 없습니다.", function(){
			});
		}else{
			var openNewWindow = window.open('about:blank');
			openNewWindow.location.href=$(this).attr('url');
		}
	});
});
</script>
</head>
<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="main.php";
	$titleName="유방암 관련 커뮤니티";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 검색결과 리스트 시작 -->
<div class="community_list">
<ul>

<?for($i=0;$i<sizeof($list);$i++){?>
<li><!-- li 시작 --><!--리스트 1개당 li한개 반복-->
<div class="list_box_left">
<div class="list_title">
<div><?=$list[$i]['cName']?></div><!-- 커뮤니티명 -->
</div>
<?if($list[$i]["cURL"] != "" && $list[$i]["cURL"] != "http://"){?><p style="clear:both;">홈페이지 : <span><?=$list[$i]['cURL']?><span></p><!-- 홈페이지 -->
<?}else{?>
<p style="clear:both;"><span> &nbsp;<span></p><!-- 홈페이지 -->
<?}?>
</div>
<div class="list_box_right">
<?if($list[$i]["cURL"] != "" && $list[$i]["cURL"] != "http://"){?><a href="#" class="links" url="<?=$list[$i]['cURL']?>"><div><p>홈페이지<br/>바로가기</p></div></a>
<?}else{?>

<!-- <div><p>홈페이지<br/>정보없음</p></div></a> -->
<?}?>
</div>
<div style="clear:both;"></div>
</li><!-- li 끝 -->
<?}?>
</ul>
</div>
<!-- 검색결과 리스트 끝 -->

</body>
</html>