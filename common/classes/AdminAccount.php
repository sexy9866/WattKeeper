<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

if(!class_exists("AdminAccount")){
	class AdminAccount extends  AdminBase {		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function modifyAccount(){
			$id = $this->req['adminID'];
			$pass = $this->req['adminPWDcur'];
			
			$sql = "
			SELECT COUNT(*) AS rn
			FROM tblAdmin
			WHERE adminID = '{$id}' AND adminPWD = PASSWORD('{$pass}')
			LIMIT 1
			";
			
			if($this->getValue($sql, 'rn')){
				$sql = "UPDATE tblAdmin SET
				adminName = '{$this->req["adminName"]}',
				adminPWD = PASSWORD('{$this->req["adminPWDnew"]}'),
				regDate = NOW()
				WHERE
				adminNumber = '{$this->req["adminNumber"]}'
				";
				$this->update($sql);
				return $this->makeResultJson("0", "정상적으로 변경되었습니다.");
			}else return $this->makeResultJson("1", "현재 패스워드가 일치하지 않습니다.");
			
			return;
		}
		
		function registerAccount(){
			$sql = "SELECT COUNT(*) AS rn FROM tblAdmin WHERE adminID='{$this->req["adminID"]}'";
			if(!$this->getValue($sql, 'rn')){
				$sql = "INSERT INTO tblAdmin(adminName, adminID, adminPWD, regDate)
				VALUES (
				'{$this->req["adminName"]}', '{$this->req["adminID"]}', PASSWORD('{$this->req["adminPWD"]}'), NOW()
				)";
				$this->update($sql);
				return $this->makeResultJson("0", "정상 등록되었습니다.");
			}else return $this->makeResultJson("1", "이미 존재하는 아이디입니다.");
		}
		
		function getAccountList(){
			$search_text = $this->req["search_text"];
			$where = "";
			if($search_text != ""){
				$where = " WHERE adminID LIKE '%{$search_text}%' OR adminName LIKE '%{$search_text}%' ";
			}
		
			if($this->req["page"] != "-1"){
				$this->initPage() ;
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tblAdmin
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
				$this->setPage($this->rownum) ;
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "SELECT * FROM tblAdmin {$where} ORDER BY adminNumber DESC {$limit}";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		function getAccount(){
			$no = $this->req["adminNumber"];
				
			$sql = "SELECT * FROM tblAdmin WHERE adminNumber = '{$no}' LIMIT 1";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		function deleteAccount(){
			$noArr = $this->req["no"];
			$noStr = join(",", $noArr);
			
			if ($noStr != ""){
				$sql = "DELETE FROM tblAdmin WHERE adminNumber IN({$noStr})";
				$this->update($sql);
			}
		}
		
	} // class end
}

?>