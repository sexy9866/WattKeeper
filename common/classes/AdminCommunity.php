<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

/*
 * Admin process
 * add by dev.lee
 */

if (! class_exists("AdminCommunity"))
{

	class AdminCommunity extends AdminBase
	{

		function __construct($req)
		{
			parent::__construct($req);
		}

		function registerCommunity(){
			$sql = "INSERT INTO tblCommunity(cName, cURL, regDate)
			VALUES (
			'{$this->req["cName"]}', '{$this->req["cURL"]}', NOW()
			)";
			$this->update($sql);
				
			return;
		}
		
		function getCommunity(){
			$no = $this->req["cNumber"];
		
			$sql = "SELECT * FROM tblCommunity WHERE cNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
				
			return $result;
		}
		
		function modifyCommunity(){
			$sql = "UPDATE tblCommunity SET
			cName = '{$this->req["cName"]}',
			cURL = '{$this->req["cURL"]}',
			regDate = NOW()
			WHERE
			cNumber = '{$this->req["cNumber"]}'
			";
			$this->update($sql);
				
			return;
		}
		
		function deleteCommunity(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
				
			if ($noStr != ""){
				$sql = "DELETE FROM tblCommunity WHERE cNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
		function getCommunityList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE cName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblCommunity
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblCommunity {$where} ORDER BY regDate DESC {$limit}";
			$result = $this->getArray($sql);
				
			return $result;
		}
	}
}

?>