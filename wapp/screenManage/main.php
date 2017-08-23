<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiScreen.php" ?>
<?
	$obj = new ApiScreen($_REQUEST) ;
	$list = $obj->getScreenList();
	$recent = $obj->getRecent();
	$vnum = $obj->virtualNum;
?>

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
	var validate = false;

	$(document).ready(function(){	

		$(".screenConfirm").click(function(){
			$.ajax({
				url : "/action_front.php?cmd=ApiScreen.updateScreen",
				async : false,
				cache : false,
				dataType : "json",
				data : {
					"screenNumber" : <?=$recent['screenNumber']!=""?$recent['screenNumber']:0?>
				},
				success : function(data){
					gweb.alert("알림", "기록되었습니다.", function(){location.href = "main.php?tab=1";});
				}
			});
		});

		$(".foot_checkup_btn").click(function(){
			if(validate) {
				var mon = $("#sMonth").val();
				var ddd = $("#sDay").val();
				if(mon.length == 1) mon = "0" + mon;
				if(ddd.length == 1) ddd = "0" + ddd;
				
				if(!richTime($("#sYear").val() + "-" + mon + "-" + ddd).isAfter(richTime())){
					gweb.alert("알림", "검진 예정일을 오늘 이후로 설정하세요.", function(){});
					return;
				}
				
				$.ajax({
					url : "/action_front.php?cmd=ApiScreen.registerScreen",
					async : false,
					cache : false,
					dataType : "json",
					data : {
						"deviceNumber" : <?=$_COOKIE['deviceNumber']!=""?$_COOKIE['deviceNumber']:0?>,
						"screenDate" : $("#sYear").val()+"-"+$("#sMonth").val()+"-"+$("#sDay").val(),
						"hNumber" : $("#sHospital").val()
					},
					success : function(data){
						gweb.alert("알림", "등록되었습니다.", function(){location.href = "main.php?tab=1";});
					}
				});
			}
			else{
				gweb.alert("알림", "모든 정보를 입력 후 확인을 눌러주세요.", function(){});
			}
		});
		
		$("#jVal").click(function(){
			if($("#sDay").val() == 0) {
				gweb.alert("알림", "날짜를 선택하세요.", function(){});
				validate = false;
				return;
			}
			if($("#sHospital").val() == "") {
				gweb.alert("알림", "병원명을 입력하세요.", function(){});
				validate = false;
				return;
			}

			$("#sDue").html("<span>검진 예정일 : </span>&nbsp;" + $("#sYear").val()+"년 "+$("#sMonth").val()+"월 "+$("#sDay").val() + "일");
			$("#sHos").html("<span>검진 예정 병원 : </span>&nbsp;" + $("#sHospital").val());
			validate = true;
		});
		
		for(var year=parseInt(richTime().format("Y")); year < parseInt(richTime().format("Y")) + 3; year++) 
			$("#sYear").append("<option value='"+year+"'>"+year+"</option>");
		for(var month=1; month<=12; month++) $("#sMonth").append("<option value='"+ month +"'>" + month + "</option>");

		for(var day=1; day <= richTime($("#sYear").val() + "."+ $("#sMonth").val() +".1").endOf('month').format("D"); day++)
			$("#sDay").append("<option value='"+ day +"'>" + day + "</option>");
		
		$("#sMonth").change(function(){
			var mon = $("#sMonth").val();
			if(mon.length == 1) mon = "0" + mon;
			$("#sDay").html('');
			for(var day=1; day <= richTime($("#sYear").val()+ "-"+ mon +"-01").endOf('month').format("D"); day++)
				$("#sDay").append("<option value='"+ day +"'>" + day + "</option>");
		});
		
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
			var sNum = $(this).attr('no');
			
			gweb.confirmCustom("알림", "삭제하시겠습니까?", function(){}, function(){
				
				$.ajax({
					url : "/action_front.php?cmd=ApiScreen.deleteScreen",
					async : false,
					cache : false,
					data : {
						"screenNumber" : sNum
					},
					success : function(data){
						location.href = "main.php?tab=1";
					}
				});
			});
			
			
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
	$titleName="검진일 관리";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 탭메뉴 시작 -->
<div class="tab1">
	<ul>
		<li class="" id="tab01" name="tab01"><p>나의 검진일</p></li><!-- 선택된 탭에 addClass = 'tab_select' -->
		<li class="" id="tab02" name="tab02"><p>검진 정보 입력</p></li>
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