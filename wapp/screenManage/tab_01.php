<!-- 검진 예정일 시작 -->
<div class="checkup_wrap1">
<?if($recent!=""){?>
<div class="checkup_box">
<p>검진 예정일</p>
<p class="checkup_date"><?=$recent['sDt']?></p>
<p class="checkup_place">검진 병원 : <?=$recent['hNumber']?></p>

<div><p class="screenConfirm">검진 완료</p></div>
</div>
<?}else{?>
<div class="checkup_box">

<p>-</p>
<p class="checkup_date">예정된 검진 정보가 없습니다.</p>
<p class="checkup_place">검진 정보를 입력하세요.</p>

</div>
<?}?>
</div>
<!-- 검진 예정일 끝 -->

<!-- 검진 이력 시작 -->
<div class="checkup_wrap2">
<p>나의 검진 이력</p>

<ul>
<li class="checkup_list_title"><!-- 차트의 제목부분 -->
<dl>
<dt>검진일</dt>
<dt>검진병원</dt>
</dl>
</li>

<?for($i=0; $i < sizeof($list); $i++){?>
<li class="checkup_list"><!-- 검진이력 리스트 (여기서 부터 li한개씩 반복) -->
<dl>
<dd><?=$list[$i]['sDate']?></dd>
<dd><?=$list[$i]['hNumber']?></dd>
<dd><a class="screenDel" no="<?=$list[$i]['screenNumber']?>"><img src="../image/btn_list_esc.png" width="24" /></a></dd>
</dl>
</li>
<?}?>
</ul>
</div>
<!-- 검진 이력 끝 -->