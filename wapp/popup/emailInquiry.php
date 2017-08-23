<? include $_SERVER["DOCUMENT_ROOT"] . "/common/inc/metaData.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/Cms.php" ?>
<?
	$obj = new Cms($_REQUEST, "");
	$userMap = LoginUtil::getUser();
	$emailList = $obj->emailList;
	$phoneList = $obj->phoneList;
	
	$email = $userMap["userEmail"];
	$email = split("@", $email);
	$mobileNumber = $userMap["mobileNumber"];
	
	
	$phone1 = substr($mobileNumber, 0, 3);
	$phone2 = substr($mobileNumber, 3, mb_strlen($mobileNumber));
	
	$cnt = 0;
	for($i = 0; $i < sizeof($emailList); $i++){
		if($emailList[$i] == $email[1])
			$cnt = 1;
	}
	
?>
<script type = "text/javascript">
	$(document).ready(function(){
		initImgUpload(1);
	});
	
	
	function initImgUpload(index) {
		/*
		var page = "pet_helper";
		
		if(window.isApp == "1")
		{
			
			$("#btnImageUpload"+index).click(function(){
				location.href = "native://action?actionName=getImageFile&actionMethod=setImageFile&index=" + index + "&page=" + page;
			});
			
			return false;
		}	
		*/
		/*
		var url = '/mobile/action_front.php?cmd=AdminBase.imagePreUpload' + "&index=" + index + "&page=pet_helper";
		new AjaxUpload("btnImageUpload"+index, {
			action: url, // I disabled uploads in this example for security reaaons
			responseType : "json",
			onSubmit: function (file, ext) {
				if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
						alertMessage("jpg, png, jpeg, gif 파일만 업로드 가능합니다.", function(){
					});
					return false;
				}
				
				//dialog.showProgress() ;
			},
			onComplete: function (file, responseText) {
				setImageFile(index, responseText.entity.imagePath);
			}
		});
		*/
	}
	
	
</script>
<div class="sidetap">

	<!-- side01 -->
	<? include $_SERVER["DOCUMENT_ROOT"] . "/common/inc/leftMenu.php" ?>	
	<!-- side01 //-->

	<div class="stp-content" id="content">

		<header class="stp-fake-header">&nbsp;</header>
		<div class="stp-overlay nav-toggle">&nbsp;</div>
		<div class="stp-content-panel">

			<!-- header -->
			<? include $_SERVER["DOCUMENT_ROOT"] . "/common/inc/header.php" ?>
			<!-- // header -->

			<!-- content -->
			<section>
			<div id="contents">

				<!-- right menu -->
				<? include $_SERVER["DOCUMENT_ROOT"] . "/common/inc/rightMenu.php" ?>
				<!-- right menu //-->

				<div class="content topline">

					<div class="topGuideArea">
						Q&A에 대한 답변은 메일로도 받아보실 수 있습니다.<br />
						게시판 운영시간: 월 ~ 금 / 09:00 ~ 18:00
						<p class="align_right"><a href="../mypage/reservView.php" class="bt">예약 내역 불러오기</a></p>

					</div>

					<div class="h3Area">
						<h3 class="h3">이메일문의</h3>
					</div>
					<div class="cont">
						<p class="starInfo">* 필수 입력</p>
						<table class="form">
							<tr>
								<th><span class="tm star">*</span>성명</th>
								<td><input type="text" class="input full" value = "<?=$userMap['korName']?>"/></td>
							</tr>
							<tr>
								<th><span class="tm star">*</span>이메일</th>
								<td>
									<div class="emailArea">
										<span><input type="text" class="input email" value = "<?=$email[0]?>" id = "email1" name ="email1" /></span>
										<span class="tm">@</span>
										<span>
<?
											if($cnt > 0){
?>
											<select class="select email" id = "email2" name = "email2">
												<option value="">선택</option>
<?
												for($i = 0; $i < sizeof($emailList); $i++){
?>
												<option value="<?=$emailList[$i]?>" <? if($emailList[$i] == $email[1]) {echo "selected";} ?> ><?=$emailList[$i]?></option>
<?
												}
?>
												<option value="직접입력" <? if($cnt == 0) echo "selected"; ?>>직접입력</option>	
											</select>
<?
											} else {
?>
												<input type="text" class="input email" value = "<?=$email[1]?>"/>
<?
											}
?>
										</span>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="tm star">*</span>휴대번호</th>
								<td>
									<div class="mobileArea">
										<select class="select tel" id = "phone1" name = "phone1">
<?
											for($i = 0; $i < sizeof($phoneList); $i++){
?>
											<option value="<?=$phoneList[$i]?>" <? if($phoneList[$i] == $phone1) echo "selected"; ?>><?=$phoneList[$i]?></option>
<?
											}
?>
										</select>
										<input type="text" class="input mobile" id = "phone2"name = "phone2" placeholder="(-)없이 숫자만 입력하세요" value = "<?=$phone2?>"/>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="tm star">*</span>제목</th>
								<td><input type="text" class="input full" /></td>
							</tr>							
							<tr>
								<td colspan="2">
									<textarea id="" class="textarea msg" >내용을 입력해 주세요.</textarea>
								</td>
							</tr>
						</table>
					</div>
					
					<!-- 예약내역 선택했을 경우 -->
					<div class="reserveList noline">
						<dl>
							<dt>
								<div class="infos">
									<span>
										<strong>확정번호</strong>
										<strong class="no">9241560</strong>
									</span>
									<span>D-120</span>
								</div>
							</dt>
							<dd>
								<div class="reserveList_info">
									<table>
										<colgroup>
											<col />
											<col />
										</colgroup>
										<tbody>
											<tr>
												<th>출발일자 : </th>
												<td>2015년 07월 02일</td>
											</tr>
											<tr>
												<th>출발항구 : </th>
												<td>치비타베키아-로마 항구 (Port of Civitavecchia)</td>
											</tr>
											<tr>
												<th>크루즈쉽 : </th>
												<td> 											
													<span class="blue">
														<img src="../../images/content/arrow_ship_01_point.png" alt="" class="ship" />
														로얄캐리비안 얼루어호(225,292톤)
													</span>
												</td>
											</tr>
										</tbody>
									</table>
									<p class="date">예약일 : 2014-11-30</p>
								</div>
							</dd>
						</dl>	

						<div class="reserveList_btn">
							<a href="#" class="bt large">삭제</a>
						</div>
					</div>
					
					<div class="fileInfo">
						<span>그림 첨부</span>
						<span>
							<a href="javascript:void(0);" class="bt" id = "btnImageUpload1">파일선택</a>
							<input type = "hidden" id = "hiddenImage1" name = "hiddenImage1"/>
							<input type = "hidden" id = "thum_file1" name = "thum_file1"/>
							<input type = "hidden" id = "thum_file_large1" name = "thum_file_large1"/>
							<img src="" alt="" class="img_size" id = "image1" style = "display:none;" />							
							선택된 파일이 없습니다.
						</span>
					</div>

					<div class="btnarea">
						<a href="#" class="fix type02" onclick="javascript:history.back(); return false;">취소</a>
						<a href="#" class="fix">문의</a>
					</div>

				</div>

			</div>
			<div class="mask"></div>
			</section>
			<!-- content //-->

			<!-- footer -->
			<!-- footer menu -->
			<? include $_SERVER["DOCUMENT_ROOT"] . "/common/inc/footer.php" ?>
			
			<!-- bottom menu -->
			<? include $_SERVER["DOCUMENT_ROOT"] . "/common/inc/bottom.php" ?>
			<!-- 하단 공통 //-->
		</div>

	</div>
</div>
</body>
</html>