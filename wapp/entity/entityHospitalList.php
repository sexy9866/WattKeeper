<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<? 
	$obj = new ApiGeneral( $_REQUEST );
	$list = $obj->getHospitalList ();
	$vnum = $obj->virtualNum;
?>

<?if(sizeof($list) == 0){?>
<!-- 검색결과 없을때 시작 -->
<div class="hospital_search_no_list">
<p>검색 결과가 없습니다.</p>
</div>
<!-- 검색결과 없을때 끝 -->
<?}else{?>
<div class="hospital_search_list">
	<ul>

<? for($i=0; $i<sizeof($list); $i++){ ?>
		<li><!-- li 시작 --><!--리스트 1개당 li한개 반복-->
			<div class="list_box_left">
				<div class="list_title">
					<div><?=$list[$i]["hName"]?></div><!-- 병원명 -->
					<?
					$style = "list_btn1";
					if($list[$i]["labelName"] == "교육지정") $style = "list_btn1";
					if($list[$i]["labelName"] == "교육주관") $style = "list_btn2";
					if($list[$i]["labelName"] == "교육우수") $style = "list_btn3";
					?>
					<div class="<?=$style?>"><b><?=$list[$i]["labelName"]?></b></div>
				</div>
				<p style="clear:both; padding-top:9px;">전화번호 : <span><?=$list[$i]["hPhone"]?><span></p><!-- 전화번호 -->
				<?if($list[$i]["hURL"] != "" && $list[$i]["hURL"] != "http://"){?><p>홈페이지 : <span><?=$list[$i]["hURL"]?></span></p><!-- 홈페이지 --><?}?>
				<p>주소 : <span><?=$list[$i]["sidoName"]." ".$list[$i]["gugunName"]." ".$list[$i]["addr3"]?></span></p><!-- 주소 -->
			</div>
			<div class="list_box_right">
				<?if($list[$i]["hURL"] != "" && $list[$i]["hURL"] != "http://"){?><div><a href="<?=$list[$i]["hURL"]?>" target="blank"><p>홈페이지<br/>바로가기</p></a></div>
				<?}else{?>
				<!-- <div><a><p>홈페이지<br/>정보없음</p></a></div> -->
				<?}?>
			</div>
			<div style="clear:both;"></div>
		</li><!-- li 끝 -->
<? } ?>
	</ul>
</div>
<? } ?>
<!-- 검색결과 리스트 끝 -->
