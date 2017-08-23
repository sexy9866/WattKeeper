<script>
	$(document).ready(function(){

		var page = 1;
		var isEnd = false;

		$("div#lastPostsLoader").empty();
		
		lastPostFunc();
		
		function lastPostFunc()  {  
			if(!isEnd) $(".lastPostsLoader").html("<center><img class='loaderImg' src='../image/progress.gif' height=50px /></center>");
		    $.ajax({
				url : "/action_front.php?cmd=ApiGeneral.getPreventionList",
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
				        var html = "<li onClick=\"window.location.href=\'preventionDetail.php?dNumber=" + data[i]['dNumber'] + "\';\"> <a>" + data[i]['dName'] + "</a> <img src='../image/btn_list_next.png' width='25'/></li>";
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
<!-- 리스트3 시작 -->
<div class="list3">
<ul class="wrdLatest">

</ul>
<div class="lastPostsLoader"></div>

<ul>
</div>
<!-- 리스트3 끝 -->