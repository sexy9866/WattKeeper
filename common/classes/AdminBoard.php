<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

if(!class_exists("AdminBoard")){
	class AdminBoard extends  AdminBase {		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function deleteDanglings(){
			$no = $this->req["tempKey"];
			$sql = "DELETE FROM tblCoupon WHERE tempKey='{$no}'";
			$this->update($sql);
		}
		
		function getPreventionList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE dName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblPrediction
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblPrediction {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function modifyPrevention(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
		
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
				
			$sql = "UPDATE tblPrediction SET
			dName = '{$this->req["dName"]}',
			dDetail = '{$this->req["dDetail"]}',
			dStatus = '{$this->req["dStatus"]}',
			imgPath1 = '{$imgPath1}',
			imgPath2 = '{$imgPath2}',
			imgPath3 = '{$imgPath3}',
			imgPath4 = '{$imgPath4}',
			imgPath5 = '{$imgPath5}',
			imgPath6 = '{$imgPath6}',
			regDate = NOW()
			WHERE
			dNumber = '{$this->req["dNumber"]}'
			";
			$this->update($sql);
				
			return;
		}
		
		function registerPrevention(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
		
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
				
			$sql = "INSERT INTO tblPrediction(dName, dDetail, dStatus, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, regDate)
			VALUES (
			'{$this->req["dName"]}', '{$this->req["dDetail"]}', '{$this->req["dStatus"]}',
			'{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}', '{$imgPath5}', '{$imgPath6}', NOW()
			)";
			$this->update($sql);
				
			return;
		}
		
		function getDietList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE dName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblDiet
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblDiet {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function modifyNotice(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
			
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
				
			if($this->req["fixed"] == 1){
				$preSql = "UPDATE tblNotice SET fixed=0";
				$this->update($preSql);
			}
				
			$sql = "UPDATE tblNotice SET
			sName = '{$this->req["sName"]}',
			sDetail = '{$this->req["sDetail"]}',
			eStatus = 2,
			fixed = '{$this->req["fixed"]}',
			postDate = NOW(),
			regDate = NOW(),
			imgPath1 = '{$imgPath1}',
			imgPath2 = '{$imgPath2}',
			imgPath3 = '{$imgPath3}',
			imgPath4 = '{$imgPath4}',
			imgPath5 = '{$imgPath5}',
			imgPath6 = '{$imgPath6}'
			WHERE
			sNumber = '{$this->req["sNumber"]}'
			";
			$this->update($sql);
		
			return;
		}
		
		function registerNotice(){
				
			$imgResult = $this->inFn_Common_fileSave($_FILES);
			
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			if($this->req["fixed"] == 1){
				$preSql = "UPDATE tblNotice SET fixed=0";
				$this->update($preSql);
			}
				
			$sql = "INSERT INTO tblNotice(sName, sDetail, eStatus, fixed, regDate, postDate, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6)
			VALUES (
			'{$this->req["sName"]}', '{$this->req["sDetail"]}', 2, '{$this->req["fixed"]}', NOW(), NOW(), '{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}', '{$imgPath5}', '{$imgPath6}'
			)";
			$this->update($sql);
		
			return;
		}
		
		function modifyDiet(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
		
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
		
			$sql = "UPDATE tblDiet SET
			dName = '{$this->req["dName"]}',
			dDetail = '{$this->req["dDetail"]}',
			dStatus = '{$this->req["dStatus"]}',
			imgPath1 = '{$imgPath1}',
			imgPath2 = '{$imgPath2}',
			imgPath3 = '{$imgPath3}',
			imgPath4 = '{$imgPath4}',
			imgPath5 = '{$imgPath5}',
			imgPath6 = '{$imgPath6}',
			regDate = NOW()
			WHERE
			dNumber = '{$this->req["dNumber"]}'
			";
			$this->update($sql);
		
			return;
		}
		
		function registerDiet(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
		
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
		
			$sql = "INSERT INTO tblDiet(dName, dDetail, dStatus, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, regDate)
			VALUES (
			'{$this->req["dName"]}', '{$this->req["dDetail"]}', '{$this->req["dStatus"]}',
			'{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}', '{$imgPath5}', '{$imgPath6}', NOW()
			)";
			$this->update($sql);
		
			return;
		}
		
		function displayPrevention(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblPrediction SET dStatus=1 WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayPrevention(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblPrediction SET dStatus=2 WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getPrevention(){
			$no = $this->req["dNumber"];
		
			$sql = "SELECT * FROM tblPrediction WHERE dNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function deletePrevention(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "DELETE FROM tblPrediction WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function displayDiet(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblDiet SET dStatus=1 WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayDiet(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblDiet SET dStatus=2 WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getDiet(){
			$no = $this->req["dNumber"];
		
			$sql = "SELECT * FROM tblDiet WHERE dNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function deleteDiet(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "DELETE FROM tblDiet WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function generateCoupon(){
			$dateStart = md5($this->req['startDate']);
			$dateEnd = md5($_REQUEST['endDate']);
			$eventName = md5($_REQUEST['eventName']);
			$numberCode = $_REQUEST['quantity'];
			$avalanche = "B9BFC175BDE4DA470EE58406CF0E5EF6499CE1D6";
			$randnum = rand() + 1;
			
			$unique = "SELECT COUNT(*) AS rnn FROM tblCoupon WHERE tempKey='{$randnum}'";
			
			while($this->getValue($unique, "rnn")!=0){
				$randnum = rand() + 1;
			}
		
			$hash = md5( $dateStart . $avalanche . $dateEnd . $eventName );
			$THRESHOLD = 2;
		
			$abbreviated = "";
		
			$slashCounter = 0;
		
			for($looper = 0; $looper < 24; $looper ++) {
				if ($looper % 2 == 0) {
					$slashCounter ++;
					$abbreviated .= $hash [$looper];
					if ($slashCounter % 4 == 0 && $looper != 24 - $THRESHOLD)
						$abbreviated .= "-";
				}
			}
			
			$sql = "INSERT INTO tblCoupon(couponSerial, regDate, tempKey) VALUES ";
			
			for($e = 0; $e < $numberCode; $e++){
				$numberHash = substr(md5($e), 0, 4);
				$retVal = strtoupper($abbreviated."-".$numberHash);
				if($e != $numberCode - 1) $sql .= " ('{$retVal}', NOW(), '{$randnum}'), ";
				else $sql .= " ('{$retVal}', NOW(), '{$randnum}') ";
			}
			
			$this->update($sql);
			
			return json_encode($randnum);
		}
		
		function modifyBoard(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
			
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "UPDATE tblEvent SET  
			eName = '{$this->req["eName"]}', 
			eDetail = '{$this->req["eDetail"]}', 
			eCouponStatus = '{$this->req["eCouponStatus"]}', 
			imgPath1 = '{$imgPath1}', 
			imgPath2 = '{$imgPath2}', 
			imgPath3 = '{$imgPath3}',
			imgPath4 = '{$imgPath4}',
			imgPath5 = '{$imgPath5}',
			imgPath6 = '{$imgPath6}',
			startDate = '{$this->req["jRsvDateStart"]}',
			endDate = '{$this->req["jRsvDateEnd"]}',
			regDate = NOW(),
			tempKey = '{$this->req["tempKey"]}'
			WHERE 
			eNumber = '{$this->req["eNumber"]}'
			";
			$this->update($sql);
			
			return;
		}
		
		function registerBoard(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
			
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "INSERT INTO tblEvent(eName, eDetail, eCouponStatus, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, startDate, endDate, regDate, tempKey)
			VALUES (
			'{$this->req["eName"]}', '{$this->req["eDetail"]}', '{$this->req["eCouponStatus"]}',
			'{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}', '{$imgPath5}', '{$imgPath6}', 
			'{$this->req["jRsvDateStart"]}', '{$this->req["jRsvDateEnd"]}', NOW(), '{$this->req["tempKey"]}'
			)";
			$this->update($sql);
			
			return;
		}
		
		function registerMain(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
				
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
				
			$sql = "INSERT INTO tblShow(title, content, imgPath1, imgPath2, imgPath3, regDate)
			VALUES (
			'{$this->req["title"]}', '{$this->req["content"]}', '{$imgPath1}', '{$imgPath2}', '{$imgPath3}', NOW())";
			$this->update($sql);
				
			return;
		}
		
		function getBoardList(){
			$former = $this->req["jRsvDateStart"] != "" ? $this->req["jRsvDateStart"] : "1970-01-01";
			$latter = $this->req["jRsvDateEnd"] != "" ? $this->req["jRsvDateEnd"] : "2099-12-30";
			$search_text = $this->req["search_text"];
			$where = " WHERE 1=1 ";
			if($search_text != ""){
				$where .= " AND eName LIKE '%{$search_text}%' ";
			}
			
			switch (intval($this->req["eStatus"] )) {
				case 1: $where .= ""; // All
					break;
				case 2: $where .= " AND NOW() >= `startDate` AND NOW() <= `endDate` "; // Ongoing
					break;
				case 3: $where .= " AND NOW() < `startDate` "; // Pending
					break;
				case 4: $where .= " AND NOW() > `endDate` "; // Ended
					break;
				default: $where .= ""; // All
					break;
			}
			
			if(intval($this->req["jRange"])==0){
				$where .= "";
			}else{
				$where .= " AND '{$former}' <= startDate AND endDate <= '{$latter}'";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblEvent
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT *, 
			DATE(startDate) AS sD, 
			DATE(endDate) AS eD, 
			CASE 
            WHEN NOW() < `startDate` THEN '대기중' 
            WHEN NOW() > `endDate` THEN '종료'
            WHEN NOW() >= `startDate` AND NOW() <= `endDate` THEN '진행중'
            ELSE '오류'
       		END as bState
			FROM tblEvent {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function displayBoard(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "UPDATE tblEvent SET pStatus=1 WHERE eNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayBoard(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblEvent SET pStatus=2 WHERE eNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getBoard(){
			$no = $this->req["eNumber"];
		
			$sql = "
				SELECT 
				*,
				CASE 
            	WHEN NOW() < `startDate` THEN 'PENDING' 
            	WHEN NOW() > `endDate` THEN 'EXIT'
            	WHEN NOW() >= `startDate` AND NOW() <= `endDate` THEN 'ONGOING'
            	ELSE 'ERROR'
       			END as bState
				FROM tblEvent WHERE eNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
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
		
		function deletePush(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "DELETE FROM tblPush WHERE sNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function deleteBoard(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "DELETE FROM tblEvent WHERE eNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getPush(){
			$no = $this->req["sNumber"];
		
			$sql = "SELECT * FROM tblPush WHERE sNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getSurvey(){
			$no = $this->req["sNumber"];
		
			$sql = "SELECT * FROM tblSurvey WHERE sNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function deleteSurvey(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "DELETE FROM tblSurvey WHERE sNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function registerPush(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
		
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath"];
				
			$sql = "INSERT INTO tblPush(sName, sDetail, imgPath, regDate)
			VALUES (
			'{$this->req["sName"]}', '{$this->req["sDetail"]}', '{$imgPath1}', NOW()
			)";
			$this->update($sql);
		
			return;
		}
		
		function modifyPush(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
		
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath"];
				
			$sql = "UPDATE tblPush SET
			sName = '{$this->req["sName"]}',
			sDetail = '{$this->req["sDetail"]}',
			imgPath = '{$imgPath1}',
			uptDate = NOW()
			WHERE
			sNumber = '{$this->req["sNumber"]}'
			";
			$this->update($sql);
		
			return;
		}
		
		function registerSurvey(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
				
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "INSERT INTO tblSurvey(sName, sDetail, sURL, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, startDate, endDate, regDate)
			VALUES (
			'{$this->req["sName"]}', '{$this->req["sDetail"]}', '{$this->req["sURL"]}',
			'{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}', '{$imgPath5}', '{$imgPath6}',
			'{$this->req["jRsvDateStart"]}', '{$this->req["jRsvDateEnd"]}', NOW()
			)";
			$this->update($sql);
				
			return;
		}
		
		function modifySurvey(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
				
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "UPDATE tblSurvey SET
			sName = '{$this->req["sName"]}',
			sDetail = '{$this->req["sDetail"]}',
			sURL = '{$this->req["sURL"]}',
			imgPath1 = '{$imgPath1}', 
			imgPath2 = '{$imgPath2}', 
			imgPath3 = '{$imgPath3}',
			imgPath4 = '{$imgPath4}',
			imgPath5 = '{$imgPath5}',
			imgPath6 = '{$imgPath6}',
			startDate = '{$this->req["jRsvDateStart"]}',
			endDate = '{$this->req["jRsvDateEnd"]}',
			regDate = NOW()
			WHERE
			sNumber = '{$this->req["sNumber"]}'
			";
			$this->update($sql);
				
			return;
		}
		
		function getNoticeList(){
			$former = $this->req["jRsvDateStart"] != "" ? $this->req["jRsvDateStart"] : "1970-01-01";
			$latter = $this->req["jRsvDateEnd"] != "" ? $this->req["jRsvDateEnd"] : "2099-12-30";
			$search_text = $this->req["search_text"];
			$where = " WHERE 1=1 ";
			if($search_text != ""){
				$where .= " AND sName LIKE '%{$search_text}%' ";
			}
		
			switch (intval($this->req["eStatus"] )) {
				case 0: $where .= ""; // All
				break;
				case 1: $where .= " AND eStatus = 1 "; // Ongoing
				break;
				case 2: $where .= " AND eStatus = 2 "; // Pending
				break;
				default: $where .= ""; // All
				break;
			}
		
			if(intval($this->req["jRange"])==0){
				$where .= "";
			}else{
				$where .= " AND DATE('{$former}') <= DATE(regDate) AND DATE(regDate) <= DATE('{$latter}')";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblNotice
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT *, (SELECT COUNT(*) FROM tblComment WHERE fk=sNumber) AS cnt
			FROM tblNotice {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getSurveyList(){
			$former = $this->req["jRsvDateStart"] != "" ? $this->req["jRsvDateStart"] : "1970-01-01";
			$latter = $this->req["jRsvDateEnd"] != "" ? $this->req["jRsvDateEnd"] : "2099-12-30";
			$search_text = $this->req["search_text"];
			$where = " WHERE 1=1 ";
			if($search_text != ""){
				$where .= " AND sName LIKE '%{$search_text}%' ";
			}
				
			switch (intval($this->req["eStatus"] )) {
				case 1: $where .= ""; // All
				break;
				case 2: $where .= " AND NOW() >= `startDate` AND NOW() <= `endDate` "; // Ongoing
				break;
				case 3: $where .= " AND NOW() < `startDate` "; // Pending
				break;
				case 4: $where .= " AND NOW() > `endDate` "; // Ended
				break;
				default: $where .= ""; // All
				break;
			}
				
			if(intval($this->req["jRange"])==0){
				$where .= "";
			}else{
				$where .= " AND '{$former}' <= startDate AND endDate <= '{$latter}'";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblSurvey
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT *,
			DATE(startDate) AS sD,
			DATE(endDate) AS eD,
			CASE
			WHEN NOW() < `startDate` THEN '대기중'
			WHEN NOW() > `endDate` THEN '종료'
			WHEN NOW() >= `startDate` AND NOW() <= `endDate` THEN '진행중'
			ELSE '오류'
			END as bState
			FROM tblSurvey {$where} ORDER BY sNumber DESC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function getBoardForCouponWithKey(){
			$no = $this->req["eNumber"];
		
			$sql = "
			SELECT
			*, DATE(startDate) AS sD, DATE(endDate) AS eD,
			(SELECT COUNT(*) FROM tblCoupon WHERE tblCoupon.tempKey = tblEvent.tempKey) AS cnt,
			(SELECT COUNT(*) FROM tblEntry WHERE tblEntry.eventNumber = '{$no}') AS entry 
			FROM tblEvent WHERE eNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getBoardForCoupon(){
			$no = $this->req["tempKey"];
			if($no=="") return;
			$sql = "
			SELECT
			*, DATE(startDate) AS sD, DATE(endDate) AS eD,
			(SELECT COUNT(*) FROM tblCoupon WHERE tblCoupon.tempKey = '{$no}') AS cnt
			FROM tblEvent WHERE tempKey = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
		
			return $result;
		}
		
		function getCouponListWithKeyForExcel(){
			$where = "WHERE eventNumber='{$this->req["eNumber"]}'";
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblEntry
				{$where}
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "
			SELECT *,
			(SELECT couponSerial FROM tblCoupon WHERE tblEntry.couponNumber=tblCoupon.couponNumber LIMIT 1) AS serial,
			(SELECT `sido`.desc from tblZipSido as `sido` where addr1=sidoNumber Limit 1) AS sidoName,
			(SELECT `gugun`.desc from tblZipGugun as `gugun` where addr2=gugunNumber Limit 1) AS gugunName
			FROM tblEntry {$where} ORDER BY entryNumber DESC";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getCouponListWithKey(){
			$where = "WHERE eventNumber='{$this->req["eNumber"]}'";
				
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblEntry
				{$where}
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
				
			$sql = "
			SELECT *,
			(SELECT couponSerial FROM tblCoupon WHERE tblEntry.couponNumber=tblCoupon.couponNumber LIMIT 1) AS serial,
			(SELECT `sido`.desc from tblZipSido as `sido` where addr1=sidoNumber Limit 1) AS sidoName,
			(SELECT `gugun`.desc from tblZipGugun as `gugun` where addr2=gugunNumber Limit 1) AS gugunName 
			FROM tblEntry {$where} ORDER BY entryNumber DESC {$limit}";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getCouponList(){
			$where = "WHERE tblCoupon.tempKey='{$this->req["tempKey"]}'";
			
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblCoupon
				{$where}
				";
			
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
			
			$sql = "
						SELECT *,
						(
						SELECT
						CASE
							WHEN count(*)=0 THEN 1
							ELSE 2
						END
						from tblEntry where tblEntry.couponNumber=tblCoupon.couponNumber LIMIT 1
						) AS used,
						(SELECT regDate from tblEntry where tblEntry.couponNumber=tblCoupon.couponNumber LIMIT 1) AS useDate
						FROM tblCoupon {$where} ORDER BY couponNumber DESC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function getCouponListForExcel(){
			$where = "WHERE tblCoupon.tempKey='{$this->req["tempKey"]}'";
				
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblCoupon
				{$where}
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
				
			$sql = "
			SELECT *,
			(
			SELECT
			CASE
			WHEN count(*)=0 THEN 1
			ELSE 2
			END
			from tblEntry where tblEntry.couponNumber=tblCoupon.couponNumber LIMIT 1
			) AS used,
			(SELECT regDate from tblEntry where tblEntry.couponNumber=tblCoupon.couponNumber LIMIT 1) AS useDate
			FROM tblCoupon {$where} ORDER BY couponNumber DESC";
			$result = $this->getArray($sql);
		
			return $result;
		}
		
	
		
		function displayNotice(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblNotice SET eStatus=1, postDate=NOW() WHERE sNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayNotice(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblNotice SET eStatus=2 WHERE sNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getNotice(){
			$no = $this->req["sNumber"];
		
			$sql = "SELECT *, (SELECT COUNT(*) FROM tblComment WHERE fk=sNumber) AS cnt FROM tblNotice WHERE sNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function deleteComment(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "DELETE FROM tblComment WHERE no IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function deleteNotice(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "DELETE FROM tblNotice WHERE sNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getPushList(){
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblPush
				";
					
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "
			SELECT * FROM tblPush ORDER BY regDate DESC {$limit}";
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
	} // class end
}

?>