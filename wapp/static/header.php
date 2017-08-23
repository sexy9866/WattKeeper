<!-- GWEB -->
<script type="text/javascript" src="/common/js/gweb_devi.js" charset="utf-8"></script>

<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>
<script>
$(document).ready(function(){

	var isOpen = false;

	var parentPage = "<?=$parentPage!=""?$parentPage:""?>";
	$("#backbtn").click(function(){
		if(parentPage=="") window.history.back();
		else window.location.href = parentPage;
	});
	
	$(".jLeftMenu").click(function(){
		if(isOpen) closeAction();
		else openAction();
    });

    
    $(".jClose").click(function(e){
        closeAction();
    });

	function openAction(){
		isOpen = true;
		$("#leftmenu").show();

        $("body").css("overflow", "hidden");

        $(".slidemenu").show();
        $(".slidemenu").css({
            "right" : "0"
        });
        
        $('.body').bind('touchmove', function(e){e.preventDefault()});
	}
    
    function closeAction(){
    	isOpen = false;
    	$("#leftmenu").fadeOut(500, function(){
            $("#leftmenu").hide();
        });
        
        $("body").css("overflow", "hidden");
        $(".slidemenu").css({
            "right" : "-150%"
        });
        
        $('.body').unbind('touchmove');
    }
});

</script>
<div class="header_space"></div>
<div class="header">
<ul>
<li><a id="backbtn" name="backbtn"><img src="/wapp/image/btn_title_back.png" width="25" style="<?=$enableBack?'':'display:none;'?>" /></a></li><!-- 뒤로가기 버튼 활성화시 display:none 해제 -->
<li><p><?=$titleName?></p></li>
<li class="jLeftMenu"><a id="menubtn" name="menubtn"><img src="/wapp/image/btn_title_menu.png" width="25"/></a></li>
</ul>
</div>

<div class="side_box" id="leftmenu" style="display:none; width:240px">
	<div class="side_menu modal slidemenu" style = "right:-150%; display: none;">
		<div class="side_title"><!-- 타이틀 시작 -->
			<p onClick="window.location.href='/wapp/index.php'">WattKeeper</p>
			<img src="/wapp/image/btn_side_esc.png" width="33" class="jClose"/>
		</div><!-- 타이틀 끝 -->
	
		<!-- 리스트 시작 -->
		<dl class="side_list">
			<!-- <dd onClick="window.location.href='/wapp/search.php'"><img src="/wapp/image/ic_side_search.png" width="14" />&nbsp;WattKeeper</dd> -->
			<dt onClick="window.location.href='/wapp/info/cInfo.php'">WattKeeper</dt><!-- WattKeeper -->
			<dd onClick="window.location.href='/wapp/info/cInfo.php'">패밀리 등록</dd>
			
			<dt onClick="window.location.href='/wapp/control/main.php?tab=0'" >Controller</dt><!-- 게시판 -->
			<dd onClick="window.location.href='/wapp/control/main.php?tab=0'" >리모컨</dd>
			<dd onClick="window.location.href='/wapp/control/main.php?tab=1'" >전력량 관리</dd>
			<dd onClick="window.location.href='/wapp/control/main.php?tab=2'">알림로그</dd>
			
		</dl>
		<!-- 리스트 끝 -->
		<!-- 하단 버튼 시작 -->
		<ul class="side_foot_btn">
			
			<li onClick="window.location.href='/wapp/static/setting.php'" class="sf_btn2"><img src="/wapp/image/ic_side_setting.png" width="14" />설정</li>
		</ul>
		<!-- 하단 버튼 끝 -->

	</div>
</div>

