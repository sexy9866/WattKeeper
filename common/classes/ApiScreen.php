<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ;?>
<?

/*
 * Admin process
 * add by dev.lee
 */
if (! class_exists ( "ApiScreen" )) {
	class ApiScreen extends ApiBase {
		
		function __construct($req) {
			parent::__construct ( $req );
		}
		
		function getRecent(){
			$deviceNum = $_COOKIE['deviceNumber'];
			$sql="SELECT *, Date(derived.screenDate) AS sDt FROM 
					(SELECT * FROM tblScreen WHERE isTemp=1 AND deviceNumber='{$deviceNum}' ORDER BY regDate DESC LIMIT 1) AS derived 
					WHERE DATE(derived.screenDate) >= DATE(NOW())";
			$result = $this->getRow($sql);
			return $result;
		}
		
		function updateScreen(){
			$sql = "UPDATE tblScreen SET isTemp = 2 WHERE screenNumber='{$this->req['screenNumber']}'";
			$this->update($sql);

			$sql2 = "DELETE FROM tblScreen WHERE isTemp=1";
			$this->update($sql2);
		}
		
		function registerScreen(){
			$deviceNumber = $this->req['deviceNumber'];
			$hNumber = $this->req['hNumber'];
			$screenDate = $this->req['screenDate'];
			
			$sql = "INSERT INTO tblScreen(deviceNumber, hNumber, screenDate, regDate) VALUES('{$deviceNumber}', '{$hNumber}', '{$screenDate}', NOW())";
			$this->update($sql);
		}
		
		function deleteScreen(){
			$no = $this->req['screenNumber'];
			$sql = "DELETE from tblScreen WHERE screenNumber = '{$no}'";
			$this->update($sql);
		}
		
		function getScreenList() {
			$deviceNum = $_COOKIE['deviceNumber'];
		
			if ($this->req ["page"] != "-1") {
				$this->initPage ();
				$sql = "SELECT COUNT(*) AS rn from tblScreen WHERE deviceNumber='{$deviceNum}' AND isTemp=2";
				
				$this->rownum = $this->getValue ( $sql, 'rn' );
				$this->setPage ( $this->rownum );
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
			
			$sql = "SELECT 
			*,
			DATE(screenDate) AS sDate
			from tblScreen WHERE deviceNumber='{$deviceNum}' AND isTemp=2 ORDER BY deviceNumber DESC {$limit}";
			
			$result = $this->getArray($sql);
			
			return $result;
		}
	} // 클래스 종료
}
?>