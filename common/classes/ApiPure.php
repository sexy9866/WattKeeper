<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ;?>
<?

if (! class_exists("ApiPure"))
{

	class ApiPure extends ApiBase
	{

		function __construct($req)
		{
			parent::__construct($req);
		}
		
		function getDeviceNumber(){
			$deviceID = $_REQUEST['deviceID'];
			$sql = "SELECT deviceNumber FROM tblDevice WHERE deviceID = '{$deviceID}' LIMIT 1";
			$result = $this->getValue($sql, "deviceNumber");
			
			return $result;
		}
		
		function registerWithoutRedundancy(){
			
			$deviceID = $_REQUEST['deviceID'];
			$deviceTypeID = $_REQUEST['deviceTypeID'];
			$registrationKey = $_REQUEST['registrationKey'];
			
			$sql = "
					INSERT INTO tblDevice(deviceID, deviceTypeID, regDate) 
					SELECT '{$deviceID}', '{$deviceTypeID}', NOW() FROM DUAL WHERE NOT EXISTS 
					(SELECT * FROM tblDevice WHERE deviceID = '{$deviceID}')";
			$this->update($sql);
			
			$insertKey = $this->mysql_insert_id();

			$sql3 = "SELECT deviceNumber FROM tblDevice WHERE deviceID = '{$deviceID}' LIMIT 1";
			$result = $this->getValue($sql3, "deviceNumber");
			
			$insertKey = $result == "" ? $insertKey : $result;
			
			$sql2 = "UPDATE tblDevice SET registrationKey = '{$registrationKey}' WHERE deviceNumber = '{$insertKey}'";
			$this->update($sql2);
			
			$deviceNumber = $insertKey;
			
			setcookie("deviceID", $deviceID);
			setcookie("deviceTypeID", $deviceTypeID);
			setcookie("registrationKey", $registrationKey);
			setcookie("deviceNumber", $deviceNumber);
			
		}

	} // 클래스 종료
}
?>