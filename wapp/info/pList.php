<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getProductList();
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
				url : "/action_front.php?cmd=ApiGeneral.getProductList",
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
				        var html = "<dl onClick=\"window.location.href=\'productDetail.php?pNumber=" + data[i]['pNumber'] + "\';\"> <dt>" + data[i]['pName'] + "</dt><dd><b>" + data[i]['pDesc1'] + "</b></dd><dd>" + data[i]['pDesc2'] + "</dd></dl>";
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
<style>body{background:#e6e7e8;}</style>
<body>

<!-- 헤더 시작 -->
<? 
	$parentPage="../index.php";
	$titleName="제품 소개";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 제품설명 시작 -->
<div class="model_explanation">
<div class="wrdLatest"></div>
	<div class="lastPostsLoader"></div>
</div>
<!-- 제품설명 끝 -->

</body>
</html>