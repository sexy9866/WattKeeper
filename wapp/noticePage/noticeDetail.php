<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getNotice();
	$clist = $obj->getCommentList() ;
	$deviceNum = $_COOKIE['deviceNumber']!=""?$_COOKIE['deviceNumber']:0;
?>

<link href="../css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>
<script>
	$(document).ready(function(){

		$(".h_del").click(function(){
			var origin = $(this).attr("pw");
			var input = "";
			var no = $(this).attr("no");

			gweb.confirmDel("", "댓글 작성시 입력한 비밀번호를 입력해 주세요.", function(){
			}, function(){
				input = $("#comm_password").val();

				if(origin == input){
					$.ajax({
						url : "/action_front.php?cmd=ApiGeneral.deleteComment",
						async : false,
						cache : false,
						dataType : 'json',
						data : {
							"no" : no
						},
						success : function(data){
							location.reload();
						}
					});
				}else{
					gweb.alert("알림", "비밀번호가 일치하지 않습니다.", function(){});
				}
			});
			
		});

		$(".jSubmitComment").click(function(){

			var devNum = "<?=$deviceNum?>";
			if(devNum == "0" && false){ // TODO
				gweb.alert("오류", "사용자 정보를 가져올 수 없습니다.", function(){});
				return;
			}
			
			if($("#desc").val() == ""){
				gweb.alert("알림", "내용을 입력하세요.", function(){});
				return;
			}
			
			if($("#password").val() == ""){
				gweb.alert("알림", "댓글 삭제 시 사용할 비밀번호를 입력하세요.", function(){});
				return;
			}

			$.ajax({
				url : "/action_front.php?cmd=ApiGeneral.insertComment",
				async : false,
				cache : false,
				data : {
					"fk" : "<?=$_REQUEST["sNumber"]?>",
					"user_fk" : devNum,
					"desc" : $("#desc").val(),
					"password" : $("#password").val()
				},
				success : function(data){
					location.reload();
				}
			});
		});
		
		
		
	});
</script>
</head>
<style>
	body{position:relative; background:#e6e7e8;}
</style>
<body>

<!-- 헤더 시작 -->
<? 
	//$parentPage="../index.php";
	$titleName="알림게시판";
	$enableBack=true;
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<!-- 리스트4내용 시작 -->
<div class="list4_read">
	<ul>
		<li>
			<p><?=$list['sName']?></p>
			<p class="date"><?=$list['regDate']?></p>
			<div style="clear:both;"></div>
		</li>
		<li class="event_con">
			<!-- 상징이미지 시작 -->
<?if($list[imgPath1]!="" && $list[imgPath1]!=null && $list[imgPath1]!="null" && $list[imgPath1]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath1]?>' width="100%"/></div><?}?>
<?if($list[imgPath2]!="" && $list[imgPath2]!=null && $list[imgPath2]!="null" && $list[imgPath2]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath2]?>' width="100%"/></div><?}?>
<?if($list[imgPath3]!="" && $list[imgPath3]!=null && $list[imgPath3]!="null" && $list[imgPath3]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath3]?>' width="100%"/></div><?}?>
<?if($list[imgPath4]!="" && $list[imgPath4]!=null && $list[imgPath4]!="null" && $list[imgPath4]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath4]?>' width="100%"/></div><?}?>
<?if($list[imgPath5]!="" && $list[imgPath5]!=null && $list[imgPath5]!="null" && $list[imgPath5]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath5]?>' width="100%"/></div><?}?>
<?if($list[imgPath6]!="" && $list[imgPath6]!=null && $list[imgPath6]!="null" && $list[imgPath6]!="NULL"){?>
		<div class="illness_image"><img src='/upload_img/<?=$list[imgPath6]?>' width="100%"/></div><?}?>
<!-- 상징이미지 끝 -->
		<p><?=$list['sDetail']?></p>
		</li>

	</ul>
	
</div>

<!-- 댓글 -->
<div class="cmlist">
	<h2>댓글 (<?=sizeof($clist)?>)</h2>
	<ul>
	<?for($i = 0; $i < sizeof($clist); $i++){?>
		<li>
			<div class="comm"><?=$clist[$i]["desc"]?></div>
			<div class="h">
				<span class="h_id"><?=$clist[$i]["hashed"]!=""?$clist[$i]["hashed"]:"디버깅 테스트"?></span>
				<span class="h_date"><?=$clist[$i]["regDate"]?></span>
				<?if($clist[$i]["user_fk"] == $deviceNum){?>
				<span class="h_del" no="<?=$clist[$i]["no"]?>" pw="<?=$clist[$i]["password"]?>"><a href="#"><img src="../image/h_del.png">삭제</a></span>
				<?}?>
			</div>
		</li>
	<?}?>
	</ul>
	<form>
		<fieldset>
			<legend class="blind">댓글쓰기</legend>
			<div class="comm_write_top">
				<label for="comm_write_textarea" class="blind">사용자의 후기나 체험담 관련 내용은 작성할 수 없습니다.</label>
				<textarea rows="3" cols="30" id="desc" name="desc" placeholder="사용자의 후기나 체험담 관련 내용은 작성할 수 없습니다."></textarea>
			</div>
			<div class="comm_write_btm">
				<div class="l">
					<label for="comm_password" class="blind">댓글 삭제 시 사용할 비밀번호</label>
					<input type="password" id="password" name="password" placeholder="댓글 삭제 시 사용할 비밀번호" />
				</div>
				<button type="button" class="r jSubmitComment">등록</button>
			</div>
		</fieldset>
	</form>
</div>
<!-- //댓글 -->
<!-- 리스트4내용 끝 -->


</body>
</html>