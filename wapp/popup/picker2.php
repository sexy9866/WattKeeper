<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<? 
	$obj = new ApiGeneral( $_REQUEST );
	$sidos = $obj->getSigungu();
?>

<!-- mask2 -->
<div class="mask2"></div>
<div class="popGuide">
<div class="popGuideCont">
<div class="pnpArea">
<!-- 팝업1 시작 -->
<div class="address_pop1 popup">
	<div class="title"><span id="jTitleMsg"></span><img id="closePop" src="image/btn_pop_esc.png" width="13" /></div>

	<ul class="con"><!-- 시/도 선택 -->
		<li class="area_select" id="jAll"><!-- 버튼 하나당 li하나 -->
			<input type="radio" desc="전체" value="0"/>
			<label><a>전체</a></label><!-- 선택시 addClass 'selected' -->
		</li>
		
		<?for($qq=0; $qq < sizeof($sidos); $qq++){?>

		<li class="area_select">
			<input type="radio" desc="<?=$sidos[$qq]['desc']?>" value="<?=$sidos[$qq]['gugunNumber']?>" />
			<label><a><?=$sidos[$qq]['desc']?></a></label>
		</li>
		<?}?>

		
		<div style="clear:both;"></div>
	</ul>

	<div class="foot_btn" id="jApply2">적용</div>
</div>
<!-- 팝업1 끝 -->
</div>
</div>
</div>