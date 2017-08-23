<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ;?>
<?

if (! class_exists("ApiGeneral"))
{

	class ApiGeneral extends ApiBase
	{

		function __construct($req)
		{
			parent::__construct($req);
		}
		
		
		function getUser(){
			$no = $_COOKIE['deviceNumber'];
				
			$sql = "SELECT * FROM tblDevice WHERE deviceNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function deleteComment(){
			$noArr = $_REQUEST["no"];
			
			$sql = "DELETE FROM tblComment WHERE no='{$noArr}'";
			$this->update($sql);
		}
		
		function insertComment(){
			$user_fk = $_REQUEST["user_fk"];
			$desc = $_REQUEST["desc"];
			$password = $_REQUEST["password"];
			$fk = $_REQUEST["fk"];
			
			$sql = "INSERT INTO tblComment(`desc`, fk, user_fk, password, regDate) VALUES('{$desc}', '{$fk}', '{$user_fk}', '{$password}', NOW())";
			$this->update($sql);
		}
		
		function turnOnPush(){
			$no = $_REQUEST['deviceNumber'];
			$sql = "UPDATE tblDevice SET allowPush=1 WHERE deviceNumber='{$no}'";
			$result = $this->update($sql);
		}
		
		function turnOffPush(){
			$no = $_REQUEST['deviceNumber'];
			$sql = "UPDATE tblDevice SET allowPush=2 WHERE deviceNumber='{$no}'";
			$result = $this->update($sql);
		}
		
		function getSigungu(){
			$sidoNumber = $this->req['sidoNumber'];
			$sql = "SELECT * FROM tblZipGugun WHERE sidoNumber='{$sidoNumber}' ORDER BY `desc` ASC";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getLabelList(){
			$sql = "SELECT * FROM tblLabel ORDER BY lName ASC";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function getSido(){
			$sql = "SELECT * FROM tblZipSido ORDER BY `desc` ASC";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getMain(){
			$sql = "
			SELECT
			*
			FROM tblShow ORDER BY mNumber DESC LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getHospitalList(){
			$zipArr = $this->req["addr2"];
				
			$search_text = $this->req["search_text"];
			$where = "WHERE 1=1 ";
			if($search_text != ""){
				$where .= " AND hName LIKE '%{$search_text}%' ";
			}
				
			if ($zipArr != ""){
				$noStr = join(",", $zipArr);
				$where .= " AND addr2 IN ({$noStr})";
			}else{
				if($this->req['addr1'] != "" && $this->req['addr1'] != 0){
					$where .= " AND addr1='{$this->req['addr1']}'";
				}
			}
				
			if($this->req['lNumber']!= "" &&  $this->req['lNumber'] != 0){
				$where .= " AND hLabel='{$this->req['lNumber']}'";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblHospital
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "
			SELECT
			* ,
			(SELECT lName from tblLabel where hLabel=lNumber Limit 1) AS labelName,
			(SELECT `sido`.desc from tblZipSido as `sido` where addr1=sidoNumber Limit 1) AS sidoName,
			(SELECT `gugun`.desc from tblZipGugun as `gugun` where addr2=gugunNumber Limit 1) AS gugunName
			FROM tblHospital {$where} ORDER BY hName ASC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function getCommunityList(){
					
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblCommunity
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblCommunity ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getSurveyList(){
			if($this->req["page"] != "-1"){
				$this->initPageForMobile() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblSurvey
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPageForMobile($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
				
			$sql = "SELECT * FROM tblSurvey ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function getNoticeFixedList(){
			$where = " WHERE fixed = 1";
			
			$sql = "SELECT *, (SELECT COUNT(*) FROM tblComment WHERE fk=sNumber) AS cnt
			FROM tblNotice {$where} ORDER BY regDate DESC";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getNoticeList(){
			$where = " WHERE eStatus = 1";
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblNotice
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPageForMobile($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";

			}
		
			$sql = "SELECT *, (SELECT COUNT(*) FROM tblComment WHERE fk=sNumber) AS cnt
			FROM tblNotice {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getMixedList(){
			if($this->req["page"] != "-1"){
				$this->initPageForMobile() ;
				$sql = "
				SELECT COUNT(*) AS rn FROM 
				(
				SELECT 
				*, '' AS sURL, 0 AS tp 
				FROM tblEvent 
				
				UNION
				
				SELECT 
				sNumber AS eNumber, 
				sName AS eName, 
				sDetail AS eDetail, 
				sURL, 
				'' AS eCouponStatus,
				imgPath1, imgPath2, imgPath3, 
				imgPath4, imgPath5, imgPath6, 
				startDate, endDate, regDate, 
				'' AS tempKey, 1 AS tp 
				FROM tblSurvey
				) AS temp
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPageForMobile($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
			
			$sql = "SELECT * FROM 
					(
					SELECT 
					*, '' as sURL, 0 AS tp 
					FROM tblEvent 
					
					UNION
					
					SELECT 
					sNumber AS eNumber, 
					sName AS eName, 
					sDetail AS eDetail, 
					sURL, 
					'' AS eCouponStatus,
					imgPath1, imgPath2, imgPath3, 
					imgPath4, imgPath5, imgPath6, 
					startDate, endDate, regDate, 
					'' AS tempKey, 1 AS tp 
					FROM tblSurvey
					) AS temp
					ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function getCommentList(){
			$where = "WHERE fk='{$this->req["sNumber"]}'";
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblComment
				{$where}
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "
			SELECT *, LEFT(MD5(CONCAT((SELECT deviceID from tblDevice WHERE deviceNumber=user_fk),'delimeter', fk)),6) AS hashed
			FROM tblComment {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getNotice(){
			$no = $this->req["sNumber"];
				
			$sql = "SELECT * FROM tblNotice WHERE sNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function getSurvey(){
			$no = $this->req["sNumber"];
			
			$sql = "SELECT * FROM tblSurvey WHERE sNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		function getEventList(){
			if($this->req["page"] != "-1"){
				$this->initPageForMobile() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblEvent
				";
			
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPageForMobile($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
			
			$sql = "SELECT * FROM tblEvent ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function insertEntryWithValidation(){
			
		}
		
		function registerEntry(){
			$eventNumber = $this->req['eventNumber'];
			$entryName = $this->req['entryName'];
			$entryPhone = $this->req['entryPhone'];
			$postcode = $this->req['postcode'];
			$addr1 = $this->req['addr1'];
			$addr2 = $this->req['addr2'];
			
			$couponSerial = $this->req['couponSerial'];
			
			$valid = "SELECT *, COUNT(*) AS rn FROM tblCoupon 
			WHERE couponSerial='{$couponSerial}' AND (tblCoupon.tempKey = (SELECT tempKey FROM tblEvent WHERE eNumber='{$eventNumber}' LIMIT 1)) LIMIT 1";
			
			if($couponSerial != ""){
				if($this->getValue($valid, "rn") == 0) return $this->makeResultJson(100, "쿠폰번호가 유효하지 않습니다.");
			}
			
			$couponNumber = $this->getValue($valid, "couponNumber");
			
			$isUsed = "SELECT COUNT(*) AS rn FROM tblEntry WHERE couponNumber = '{$couponNumber}'";
			
			if($this->getValue($isUsed, "rn") != 0) return $this->makeResultJson(150, "이미 사용된 쿠폰입니다.");
			
			$sql = "
				INSERT INTO 
				tblEntry(eventNumber, couponNumber, entryName, entryPhone, postcode, addr1, addr2, regDate) 
				VALUES('{$eventNumber}', '{$couponNumber}', '{$entryName}', '{$entryPhone}', '{$postcode}', 
				'{$addr1}', '{$addr2}', NOW())";
			
			$this->update($sql);
			
			return $this->makeResultJson(200, "접수가 완료되었습니다.");
		}
		
		function getEvent(){
			$no = $this->req["eNumber"];
		
			$sql = "SELECT 
			*, 
			DATE(startDate) AS sD, 
			DATE(endDate) AS eD,
			CASE 
            WHEN NOW() < `startDate` THEN '이벤트 진행 기간이 아닙니다.' 
            WHEN NOW() > `endDate` THEN '종료된 이벤트입니다.'
            WHEN NOW() >= `startDate` AND NOW() <= `endDate` THEN '진행중'
            ELSE '오류'
       		END as bState 
			FROM tblEvent WHERE eNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getDisease(){
			$no = $this->req["dNumber"];
		
			$sql = "SELECT * FROM tblDisease WHERE dNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function getPrevention(){
			$no = $this->req["dNumber"];
		
			$sql = "SELECT * FROM tblPrediction WHERE dNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getDiet(){
			$no = $this->req["dNumber"];
		
			$sql = "SELECT * FROM tblDiet WHERE dNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getProduct(){
			$no = $this->req["pNumber"];
		
			$sql = "SELECT * FROM tblProduct WHERE pNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function getDiseaseList(){
			
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblDisease
				WHERE dStatus=1
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblDisease WHERE dStatus=1 ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
				
			return json_encode($result);
		}
		
		function getDietList(){
					
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblDiet
				WHERE dStatus=1
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblDiet WHERE dStatus=1 ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return json_encode($result);
		}
		
		function getPreventionList(){
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblPrediction
				WHERE dStatus=1
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblPrediction WHERE dStatus=1 ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return json_encode($result);
		}
		
		function getProductList(){
					
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblProduct
				WHERE pStatus=1
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblProduct WHERE pStatus=1 ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
				
			return json_encode($result);
		}

	} // 클래스 종료
}
?>