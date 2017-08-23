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
		
		function tabSelect(){
			$("#tab01").removeClass();
			$("#tab02").removeClass();
			if(tabNum == 1){
				$("#tab01").addClass('tab_select');
			}else if(tabNum == 2){
				$("#tab02").addClass('tab_select');
			}else{
				$("#tab01").addClass('tab_select');
			}
		}
		
		var tabNum = <?=$_REQUEST['tab']!=""?$_REQUEST['tab']:0?>;
		tabSelect();

		$("#tab01").click(function(){
			if(tabNum!=1 && tabNum!=0) window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=1" );
		});
		$("#tab02").click(function(){
			if(tabNum!=2) window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=2" );
		});

		$(".screenDel").click(function(){
			alert($(this).attr('no'));
		});

		$(".screenConfirm").click(function(){
			alert("검진완료 버튼 선택됨");
		});
		
	});
</script>
</head>

<style>
body{background-color:#e6e7e8}
</style>

<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="main.php";
	$titleName="유방암 예방 및 권장식단";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 탭메뉴 시작 -->
<div class="tab1">
<ul>
<li class="" id="tab01" name="tab01"><p>예방하기</p></li><!-- 선택된 탭에 addClass = 'tab_select' -->
<li class="" id="tab02" name="tab02"><p>식단관리</p></li>
</ul>
<div style="clear:both;"></div>
</div>
<!-- 탭메뉴 끝 -->
<?
if($_REQUEST['tab']==1) include "tab_01.php";
else if($_REQUEST['tab']==2) include "tab_02.php";
else include "tab_01.php";
?>

<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/footer.php" ?>
