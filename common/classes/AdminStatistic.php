<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

if(!class_exists("AdminStatistic")){
	class AdminStatistic extends  AdminBase {		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function modifyStatistic(){
			$sql = "UPDATE tblStatistic SET  
			pName = '{$this->req["pName"]}', 
			pDesc1 = '{$this->req["pDesc1"]}', 
			pDesc2 = '{$this->req["pDesc2"]}', 
			pDetail = '{$this->req["pDetail"]}', 
			pStatus = '{$this->req["pStatus"]}', 
			imgPath1 = '{$this->req["imgPath1"]}', 
			imgPath2 = '{$this->req["imgPath2"]}', 
			imgPath3 = '{$this->req["imgPath3"]}',
			imgPath4 = '{$this->req["imgPath4"]}',
			imgPath5 = '{$this->req["imgPath5"]}',
			imgPath6 = '{$this->req["imgPath6"]}',
			regDate = NOW() 
			WHERE 
			pNumber = '{$this->req["pNumber"]}'
			";
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
			(SELECT COUNT(*) FROM tblCoupon WHERE tblCoupon.tempKey = tblEvent.tempKey) AS cnt,
			(SELECT COUNT(*) FROM tblEntry WHERE tblEntry.eventNumber = tblEvent.eNumber) AS entry
			FROM tblEvent {$where} ORDER BY eNumber DESC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function getPivotList(){
			$former = $this->req["jRsvDateStart"] != "" ? $this->req["jRsvDateStart"] : "1970-01-01";
			$latter = $this->req["jRsvDateEnd"] != "" ? $this->req["jRsvDateEnd"] : "2099-12-30";
			$where = " WHERE 1=1 ";
			
			if(intval($this->req["jRange"])==0){
				$where .= "";
			}else{
				$where .= " AND '{$former}' <= regDate AND regDate <= '{$latter}'";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn FROM
				(
				SELECT 
					YEAR(regDate) AS yy,
					MONTH(regDate) AS mm,
					SUM(CASE 
					WHEN hLabel = 1 THEN 1
					ELSE 0
					END) label1 ,
					SUM(CASE 
					WHEN hLabel = 2 THEN 1
					ELSE 0
					END) label2 ,
					SUM(CASE 
					WHEN hLabel = 3 THEN 1
					ELSE 0
					END) label3 ,
					(SELECT COUNT(*) FROM tblHospitalHistory WHERE YEAR(regDate)=yy AND MONTH(regDate)=mm) AS total
					FROM tblHospitalHistory 
					{$where} 
					GROUP BY YEAR(regDate), MONTH(regDate)
					ORDER BY regDate DESC
				) AS something
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT 
					YEAR(regDate) AS yy,
					MONTH(regDate) AS mm,
					SUM(CASE 
					WHEN hLabel = 1 THEN 1
					ELSE 0
					END) label1 ,
					SUM(CASE 
					WHEN hLabel = 2 THEN 1
					ELSE 0
					END) label2 ,
					SUM(CASE 
					WHEN hLabel = 3 THEN 1
					ELSE 0
					END) label3 ,
					(SELECT COUNT(*) FROM tblHospitalHistory WHERE YEAR(regDate)=yy AND MONTH(regDate)=mm) AS total
					FROM tblHospitalHistory 
					{$where} 
					GROUP BY YEAR(regDate), MONTH(regDate)
					ORDER BY regDate DESC
					{$limit}";
			
			$result = $this->getArray($sql);
		
			return $result;
		}
		
		function getLabelList(){
			$sql = "SELECT * FROM tblLabel ORDER BY lNumber ASC";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		function registerStatistic(){
			$sql = "INSERT INTO tblStatistic(pName, pDesc1, pDesc2, pDetail, pStatus, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, regDate)
			VALUES (
			'{$this->req["pName"]}', '{$this->req["pDesc1"]}', '{$this->req["pDesc2"]}', '{$this->req["pDetail"]}', '{$this->req["pStatus"]}',
			'{$this->req["imgPath1"]}', '{$this->req["imgPath2"]}', '{$this->req["imgPath3"]}', '{$this->req["imgPath4"]}',
			'{$this->req["imgPath5"]}', '{$this->req["imgPath6"]}', NOW()
			)";
			$this->update($sql);
			
			return;
		}
		
		function getStatisticList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE pName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblStatistic
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblStatistic {$where} ORDER BY pNumber DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function displayStatistic(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "UPDATE tblStatistic SET pStatus=1 WHERE pNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayStatistic(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblStatistic SET pStatus=2 WHERE pNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getStatistic(){
			$no = $this->req["pNumber"];
				
			$sql = "SELECT * FROM tblStatistic WHERE pNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		function deleteStatistic(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
			
			if ($noStr != ""){
				$sql = "DELETE FROM tblStatistic WHERE pNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
	} // class end
}

?>