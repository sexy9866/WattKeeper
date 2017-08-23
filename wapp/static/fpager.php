<script>
var endPage = <?=$endPage!=""?$endPage:1?>;
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
	var page = request.getParameter('page');
	if(page - 1 <= 0) return;
	if(page == "") window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=1" );
	else window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=" + (parseInt(page) - 1) );
}

function tab_next(){
	var request = new Request();
	var page = request.getParameter('page');
	if(parseInt(page) + 1 > endPage) return;
	if(page == "") window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=1" );
	else window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=" + (parseInt(page) + 1) );
}

function goPage(num){
	window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=" + (parseInt(num)) );
}


</script>
<!-- 풋터 버튼 시작 -->
<?
	$current = $_REQUEST['page']!=""?$_REQUEST['page'] : 1;
	if($current == 0 || $current < 0) $current = 1;
	$minPage = (intval(($current - 1) / 5) * 5) + 1;
?>
<div class="event_list_foot">
<ul>
<li onClick="tab_prev();" class="prev"><img src="../image/btn_event_list_prev.png" width="51"/></li>
<li class="num_box">
<div onClick="goPage(<?=$minPage?>);" class="num <?=$current==$minPage?"select":""?>"><?=$minPage?></div><!-- 현재 페이지에 addClass 'select' -->
<?if($endPage >= $minPage + 1){?><div onClick="goPage(<?=$minPage + 1?>);" class="num <?=$current==$minPage+1?"select":""?>"><?=$minPage + 1?></div><?}?>
<?if($endPage >= $minPage + 2){?><div onClick="goPage(<?=$minPage + 2?>);" class="num <?=$current==$minPage+2?"select":""?>"><?=$minPage + 2?></div><?}?>
<?if($endPage >= $minPage + 3){?><div onClick="goPage(<?=$minPage + 3?>);" class="num <?=$current==$minPage+3?"select":""?>"><?=$minPage + 3?></div><?}?>
<?if($endPage >= $minPage + 4){?><div onClick="goPage(<?=$minPage + 4?>);" class="num <?=$current==$minPage+4?"select":""?>"><?=$minPage + 4?></div><?}?>
</li>
<li onClick="tab_next();" class="next"><img src="../image/btn_event_list_next.png" width="51"/></li>
</ul>
<div style="clear:both;"></div>
</div>
<!-- 풋터 버튼 끝 -->