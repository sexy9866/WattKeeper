<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?
/*
 * Admin process
 * add by dev.lee
 * */
if(!class_exists("AdminHospital")){
	class AdminHospital extends  AdminBase {
		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function modifyHospital(){
			$sql = "UPDATE tblHospital SET
			hName = '{$this->req["hName"]}',
			hPhone = '{$this->req["hPhone"]}',
			hURL = '{$this->req["hURL"]}',
			hLabel = '{$this->req["hLabel"]}',
			addr1 = '{$this->req["addr1"]}',
			addr2 = '{$this->req["addr2"]}',
			addr3 = '{$this->req["addr3"]}',
			regDate = NOW()
			WHERE
			hNumber = '{$this->req["hNumber"]}'
			";
			$this->update($sql);
				
			$sqlh = "INSERT INTO tblHospitalHistory(hNumber, hLabel, regDate)
			VALUES ('{$this->req["hNumber"]}', '{$this->req["hLabel"]}', NOW())";
				
			$this->update($sqlh);
			return;
		}
		
		function registerHospital(){
			$sql = "INSERT INTO tblHospital(hName, hPhone, hURL, hLabel, addr1, addr2, addr3, regDate)
			VALUES (
			'{$this->req["hName"]}', '{$this->req["hPhone"]}', '{$this->req["hURL"]}', '{$this->req["hLabel"]}', 
			'{$this->req["addr1"]}', '{$this->req["addr2"]}', '{$this->req["addr3"]}', NOW()
			)";
			$this->update($sql);
			
			$hNumber = $this->mysql_insert_id();
			
			$sqlh = "INSERT INTO tblHospitalHistory(hNumber, hLabel, regDate)
			VALUES ('{$hNumber}', '{$this->req["hLabel"]}', NOW())";
			
			$this->update($sqlh);
			return;
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
		
		function getSigungu(){
			$sidoNumber = $this->req['sidoNumber'];
			$sql = "SELECT * FROM tblZipGugun WHERE sidoNumber='{$sidoNumber}' ORDER BY `desc` ASC";
			$result = $this->getArray($sql);
		
			return json_encode($result);
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
			FROM tblHospital {$where} ORDER BY hNumber DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function getHospital(){
			$no = $this->req["hNumber"];
		
			$sql = "SELECT * FROM tblHospital WHERE hNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function deleteHospital(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "DELETE FROM tblHospital WHERE hNumber IN({$noStr})";
				$this->update($sql);
			}
		}

	} // class end
}
?>