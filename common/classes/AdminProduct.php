<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

if(!class_exists("AdminProduct")){
	class AdminProduct extends  AdminBase {		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function modifyProduct(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
				
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "UPDATE tblProduct SET  
			pName = '{$this->req["pName"]}', 
			pDesc1 = '{$this->req["pDesc1"]}', 
			pDesc2 = '{$this->req["pDesc2"]}', 
			pDetail = '{$this->req["pDetail"]}', 
			pStatus = '{$this->req["pStatus"]}', 
			imgPath1 = '{$imgPath1}', 
			imgPath2 = '{$imgPath2}', 
			imgPath3 = '{$imgPath3}',
			imgPath4 = '{$imgPath4}',
			imgPath5 = '{$imgPath5}',
			imgPath6 = '{$imgPath6}',
			regDate = NOW() 
			WHERE 
			pNumber = '{$this->req["pNumber"]}'
			";
			$this->update($sql);
			
			return;
		}
		
		function registerProduct(){
			$imgResult = $this->inFn_Common_fileSave($_FILES);
			
			$imgPath1 = $imgResult["img1"]["saveURL"] != null ? $imgResult["img1"]["saveURL"] : $this->req["imgPath1"];
			$imgPath2 = $imgResult["img2"]["saveURL"] != null ? $imgResult["img2"]["saveURL"] : $this->req["imgPath2"];
			$imgPath3 = $imgResult["img3"]["saveURL"] != null ? $imgResult["img3"]["saveURL"] : $this->req["imgPath3"];
			$imgPath4 = $imgResult["img4"]["saveURL"] != null ? $imgResult["img4"]["saveURL"] : $this->req["imgPath4"];
			$imgPath5 = $imgResult["img5"]["saveURL"] != null ? $imgResult["img5"]["saveURL"] : $this->req["imgPath5"];
			$imgPath6 = $imgResult["img6"]["saveURL"] != null ? $imgResult["img6"]["saveURL"] : $this->req["imgPath6"];
			
			$sql = "INSERT INTO tblProduct(pName, pDesc1, pDesc2, pDetail, pStatus, imgPath1, imgPath2, imgPath3, imgPath4, imgPath5, imgPath6, regDate)
			VALUES (
			'{$this->req["pName"]}', '{$this->req["pDesc1"]}', '{$this->req["pDesc2"]}', '{$this->req["pDetail"]}', '{$this->req["pStatus"]}',
			'{$imgPath1}', '{$imgPath2}', '{$imgPath3}', '{$imgPath4}',
			'{$imgPath5}', '{$imgPath6}', NOW()
			)";
			$this->update($sql);
			
			return;
		}
		
		function getProductList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE pName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblProduct
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblProduct {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function displayProduct(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "UPDATE tblProduct SET pStatus=1 WHERE pNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function undisplayProduct(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
		
			if ($noStr != ""){
				$sql = "UPDATE tblProduct SET pStatus=2 WHERE pNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getProduct(){
			$no = $this->req["pNumber"];
				
			$sql = "SELECT * FROM tblProduct WHERE pNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		function deleteProduct(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
			
			if ($noStr != ""){
				$sql = "DELETE FROM tblProduct WHERE pNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
	} // class end
}

?>