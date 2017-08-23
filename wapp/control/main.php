<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>

<!DOCTYPE>
<html>
<head>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title></title>
<link href="../css/style.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/common/js/richJS_beta-min.js" charset="utf-8"></script>
<script>
function Request(){
	 var requestParam ="";
	  this.getParameter = function(param){
	  var url = unescape(location.href);  
	   var paramArr = (url.substring(url.indexOf("?")+1,url.length)).split("&"); 
	   for(var i = 0 ; i < paramArr.length ; i++){
	     var temp = paramArr[i].split("=");
	     if(temp[0].toUpperCase() == param.toUpperCase()){
	       requestParam = paramArr[i].split("=")[1]; 
	       break;
	     }
	   }
	   return requestParam;
	 }
	}

function tab_prev(){
	var request = new Request();
	var page = request.getParameter('dPage');
	if(page == "0") return;
	else{
		if(page == "") window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=1&dPage=0" );
		else window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=1&dPage=" + (parseInt(page) - 1) );
	}
}

function tab_next(){
	var request = new Request();
	var page = request.getParameter('dPage');
	if(page == "3") return;
	else{
		if(page == "") window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=1&dPage=1" );
		else window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=1&dPage=" + (parseInt(page) + 1) );
	}
}

	$(document).ready(function(){
		
		function tabSelect(){
			$("#tab01").removeClass();
			$("#tab02").removeClass();
			$("#tab03").removeClass();
			if(tabNum == 1){
				$("#tab01").addClass('tab_select');
			}else if(tabNum == 2){
				$("#tab02").addClass('tab_select');
			}else if(tabNum == 3){
				$("#tab03").addClass('tab_select');
			}else{
				$("#tab01").addClass('tab_select');
			}
		}

		$(".hazard_check_left, .hazard_check_right").click(function(e){
			if(!$(this).find(".diag").is(":checked")){
				$(this).find(".diagl").addClass("chk_selected");
				$(this).find(".diag").prop("checked", true);
			}else{
				$(this).find(".diagl").removeClass("chk_selected");
				$(this).find(".diag").prop("checked", false);
			}
		});
		
		var tabNum = <?=$_REQUEST['tab']!=""?$_REQUEST['tab']:0?>;
		tabSelect();

		$("#tab01").click(function(){
			if(tabNum!=1 && tabNum!=0) window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=1" );
		});
		$("#tab02").click(function(){
			if(tabNum!=2) window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=2" );
		});
		$("#tab03").click(function(){
			if(tabNum!=1 && tabNum!=0) window.location.href = window.location.href.replace( /[\?#].*|$/, "?tab=3" );
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
	$parentPage="../index.php";
	$titleName="Controller";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 탭메뉴 시작 -->
<div class="tab1">
	<ul>
		<li class="" id="tab01" name="tab01"><p>리모컨</p></li><!-- 선택된 탭에 addClass = 'tab_select' -->
		<li class="" id="tab02" name="tab02"><p>전력량 관리</p></li>
		<li class="" id="tab03" name="tab03"><p>알림로그</p></li>
	</ul>
	<div style="clear:both;"></div>
</div>
<!-- 탭메뉴 끝 -->
<?
if($_REQUEST['tab']==1) include "tab_01.php";
else if($_REQUEST['tab']==2) include "tab_02.php";
else if($_REQUEST['tab']==3) include "tab_03.php";
else include "tab_01.php";
?>

<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/footer.php" ?>