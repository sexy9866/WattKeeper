<!-- 검진일 선택 시작 -->
<div class="checkup_date_select">
	<div class="date"><!-- 예정일 -->
		<p>검진 예정일 <span>(검진 예정일을 입력해주세요)</span></p>
		<div class="date_select_box">
			<select id="sYear" >
			</select>
			<a>년</a>
			<select id="sMonth" style="width:21%;">
			</select>
			<a>월</a>
			<select id="sDay" style="width:21%; padding:0 7px;">
				<option value="0">선택</option>
			</select>
			<a style="">일</a>
		</div>
		<div style="clear:both;"></div>
	</div>

	<div class="place"><!-- 병원 -->
		<p>검진 예정병원 <span>(병원명을 입력해 주세요)</span></p>
		<input type="text" id="sHospital" />
		<div class="btn" id="jVal">확인</div>
		<div style="clear:both;"></div>
	</div>
</div>
<!-- 검진일 선택 끝 -->

<!-- 검진일 노출 시작 -->
<div class="checkup_result">
	<div>
		<p id="sDue"><span>검진 예정일</span>을 지정해주세요</p>
		<p id="sHos"><span>검진 예정 병원</span>을 입력해주세요</p>
	</div>
</div>
<!-- 검진일 노출 끝 -->

<!-- 확인버튼 -->
<div class="foot_checkup_btn"><p>나의 검진일 적용</p></div>