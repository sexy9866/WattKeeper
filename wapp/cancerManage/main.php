<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>

<link href="../css/style.css" rel="stylesheet" type="text/css">
<script>
function menu01(){
	window.location.href="./menu_1.php"	
}
function menu02(){
	window.location.href="./menu_2.php"
}
function menu03(){
	window.location.href="./menu_3.php"
}
function menu04(){
	window.location.href="./menu_4.php"
}
function menu05(){
	window.location.href="./menu_5.php"
}
</script>
</head>

<style>
	body{background-color:#e6e7e8}
</style>

<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="../index.php";
	$titleName="유방 질환";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 리스트1 시작 -->
<div class="list1">
	<ul>
		<li onclick="menu01();"><!-- li한개당 리스트1개 -->
			<img src="../image/ic_info_list_1.png" width="42"/>
			<a>유방 관련 질환</a>
		</li>
		<li onclick="menu02();">
			<img src="../image/ic_info_list_2.png" width="42"/>
			<a>유방암 예방 및 권장식단</a>
		</li>
		<li onclick="menu03();">
			<img src="../image/ic_info_list_3.png" width="42"/>
			<a>유방암 수술 후 유의 사항</a>
		</li>
		<li onclick="menu04();">
			<img src="../image/ic_info_list_4.png" width="42"/>
			<a>유방암 관련 커뮤니티</a>
		</li>
		<li onclick="menu05();">
			<img src="../image/ic_info_list_5.png" width="42"/>
			<a>유방암 상식 코너</a>
		</li>
	</ul>
</div>
<!-- 리스트1 끝 -->

</body>
</html>