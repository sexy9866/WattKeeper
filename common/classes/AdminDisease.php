<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

if(!class_exists("AdminDisease")){
	class AdminDisease extends  AdminBase {		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function modifyDisease(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
				
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "UPDATE tblDisease SET  
			dName = '{$this->req["dName"]}', 
			dDetail = '{$this->req["dDetail"]}', 
			dStatus = '{$this->req["dStatus"]}', 
			dURL 	= '{$this->req["dURL"]}',
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
		
		function registerDisease(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
				
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "INSERT INTO tblDisease(dName, dDetail, dStatus, dURL, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, regDate)
			VALUES (
			'{$this->req["dName"]}', '{$this->req["dDetail"]}', '{$this->req["dStatus"]}', '{$this->req["dURL"]}',
			'{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}', '{$imgPath5}', '{$imgPath6}', NOW()
			)";
			$this->update($sql);
			
			return;
		}
		
		function getDiseaseList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE dName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblDisease
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblDisease {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function displayDisease(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "UPDATE tblDisease SET dStatus=1 WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayDisease(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblDisease SET dStatus=2 WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getDisease(){
			$no = $this->req["dNumber"];
				
			$sql = "SELECT * FROM tblDisease WHERE dNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		function deleteDisease(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
			
			if ($noStr != ""){
				$sql = "DELETE FROM tblDisease WHERE dNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
	} // class end
}

?>