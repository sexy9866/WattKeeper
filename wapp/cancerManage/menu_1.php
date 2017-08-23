<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getDiseaseList();
?>

<link href="../css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>
<script>
	$(document).ready(function(){

		var page = 1;
		var isEnd = false;

		$("div#lastPostsLoader").empty();
		
		lastPostFunc();
		
		function lastPostFunc()  {  
			if(!isEnd) $(".lastPostsLoader").html("<center><img class='loaderImg' src='../image/progress.gif' height=50px /></center>");
		    $.ajax({
				url : "/action_front.php?cmd=ApiGeneral.getDiseaseList",
				async : false,
				cache : false,
				dataType : 'json',
				data : {
					"page" : page
				},
				success : function(data){
			        if (data.length != 0) {
				        isEnd = false; 
				        for(var i = 0; i < data.length; i++){
				        var html = "<li onClick=\"window.location.href=\'cancerDetail.php?dNumber=" + data[i]['dNumber'] + "\';\"> <a>" + data[i]['dName'] + "</a> <img src='../image/btn_list_next.png' width='25'/></li>";
			        	$(".wrdLatest").append(html);          
				        }
				        page++;
			        }else{
				        isEnd = true;
				    }
				},
				error : function(data){
					alert("데이터를 불러오는 중 오류가 발생하였습니다.");
				}
			});  
		    $(".lastPostsLoader").empty();        
		}  


		$(window).scroll(function(){  
		    if  (!isEnd && $(window).scrollTop() == $(document).height() - $(window).height()){  
		    	lastPostFunc();  
		    }
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
	$titleName="유방 관련 질환";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 리스트2 시작 -->
<div class="list2">
<ul class="wrdLatest">

</ul>
<div class="lastPostsLoader"></div>
</div>
<!-- 리스트2 끝 -->



</body>
</html>