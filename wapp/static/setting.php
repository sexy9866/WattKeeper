<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<? 
	$obj = new ApiGeneral( $_REQUEST );
	$user = $obj->getUser();
?>

<link href="../css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>
<script>
	$(document).ready(function(){
		
		var push = <?=$user['allowPush']!=""?$user['allowPush']:1?>;

		setPush();

		$("#push_setting").click(function(){
			if(push != 1){
				$.ajax({
					url : "/action_front.php?cmd=ApiGeneral.turnOnPush",
					async : false,
					cache : false,
					data : {
						"deviceNumber" : <?=$_COOKIE['deviceNumber']!=""?$_COOKIE['deviceNumber']:0?>
					},
					success : function(data){
						push = 1;
						setPush();
					}
				});
			}else{
				$.ajax({
					url : "/action_front.php?cmd=ApiGeneral.turnOffPush",
					async : false,
					cache : false,
					data : {
						"deviceNumber" : <?=$_COOKIE['deviceNumber']!=""?$_COOKIE['deviceNumber']:0?>
					},
					success : function(data){
						push = 2;
						setPush();
					}
				});
			}
		});

		 $("#privacy").click(function(){
			 gweb.alertEntity("개인 정보 수칙 및 사용 동의", "../entity/privacy.html", function(){});
		 });
		
		function setPush(){
			if(push == 1){
				$("#push").attr("src", "../image/btn_push_on.png");
			}else{
				$("#push").attr("src", "../image/btn_push_off.png");
			}
		}
			
	});
	
</script>
</head>

<style>
	body{background-color:#e6e7e8}
</style>

<body>

<!-- 헤더 시작 -->
<? 
	//$parentPage="../index.php";
	$titleName="설정";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 리스트2 시작 -->
<div class="setting">
	<ul>
		<li id="push_setting"><!-- li한개당 리스트1개 -->
			<p>푸시 알림</p>
			<img id="push" src="" width="50"/>
		</li>
		<li>
			<p>개인 보호 정책</p>
			<a id="privacy">정책보기</a>
		</li>
	</ul>
	<div style="clear:both;"></div>
</div>
<!-- 리스트2 끝 -->

</body>
</html>