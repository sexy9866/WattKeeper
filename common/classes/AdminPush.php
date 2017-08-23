<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?
/*
 * Admin process
 * add by dev.lee
 * */
if(!class_exists("AdminPush")){
	class AdminPush extends  AdminBase {
		
		function __construct($req) 
		{
			parent::__construct($req);
		}
		
		
		function getTokensForAll(){
			$sql = "SELECT registrationKey FROM tblDevice WHERE registrationKey != '' AND allowPush = 1";
			return $this->getArray($sql);
		}
		
		function getTokensForScreen(){
			$sql = "SELECT registrationKey FROM tblDevice WHERE
					registrationKey != '' AND allowPush = 1 AND 
					deviceNumber IN (SELECT deviceNumber FROM tblScreen WHERE isTemp=1 
					AND DATE(screenDate)=DATE(NOW()))";
			return $this->getArray($sql);
		}
		
		function sendPushForScreen(){
			$tokens = $this->getTokensForScreen();
				
			$myMessage = "오늘은 검진 예정일입니다.";
			$title = "검진일 알림";
			$img = "";
				
			$message = array(
					"message" => $myMessage,
					"title" => $title,
					"img" => $img
			);
				
			$message_status = $this->fcmSimplePush($tokens, $message);
			
			return $message_status;
		}
		
		function sendPushAdminDevi(){
			$no = $_REQUEST["no"];
			$tokens = $this->getTokensForAll();
			
			$sql = "SELECT * FROM tblPush WHERE sNumber='{$no}'";
			
			$msgBox = $this->getRow($sql);
			
			$myMessage = $msgBox["sDetail"];
			$title = $msgBox["sName"];
			$img = $this->con_domain."/upload_img/".$msgBox["imgPath"];
			
			
			$message = array(
					"body" => $myMessage,
					"message" => $myMessage,
					"title" => $title,
					"img" => $img
			);
			
			$sql2 = "UPDATE tblPush SET uptDate=NOW() WHERE sNumber='{$no}'";
			$this->update($sql2);
			
			$message_status = $this->fcmSimplePush($tokens, $message);
			
			return $message_status;
		}
		
		function fcmSimplePush($tokens, $message)
		{
			
			$tokenPack = array();
			for($i = 0; $i < sizeof($tokens); $i++){
				$tokenPack[$i] = $tokens[$i]["registrationKey"];
			}
			
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array(
					'registration_ids' => $tokenPack,
					'data' => $message,
					"notification" => $message
			);
			
			$gcm_key = "AAAAyeVb4Vo:APA91bGRYGSkQp6BMr--2jaqQxMkIzSJFWXXp0gdf0m7SvFh82oJ73u8DyEkTjOHyAyopcdw55gl3ZPC8pHku9365tC2WIY3uUxwvCYByypSncLdSsmMj_Ox_umLIuNvqNhFJuFFF-MM" ;
		
			$headers = array(
					'Authorization:key =' . $gcm_key,
					'Content-Type: application/json'
			);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}
			curl_close($ch);
			return $result;
		}
		
		
		function sendPushWithMsg($msg){
			$push_msg = $msg ;
			
			// 푸시 전송
			$params = Array(
					"push_msg"	=> $push_msg,
					"push_type"	=> $this->PUSH_TYPE_ADMIN
					);
			
			$this->sendPushBulk($params);
			
			return $this->makeResultJson(1, "전송되었습니다.");
			
		}
		
		function sendPushForBillSend()
		{
			$room_fk = $this->req["room_fk"] ;
			$push_msg = "청구서가 도착했습니다." ;
			$push_code = $this->getBulkPushCode();
			
			$sql="
				SELECT user_fk
				FROM tbl_room
				WHERE no='{$room_fk}'
			";
			$result=$this->getRow($sql);
			$user_fk=$result["user_fk"];
			
			$sql = "
			INSERT INTO tbl_push_target_bulk(push_code, registration_key, device_type_id, app_type)
			(
			SELECT '{$push_code}', registration_key, device_type_id, app_type
			FROM tbl_user U
			WHERE U.no='{$user_fk}' AND U.status = 'Y' AND U.registration_key != '' AND is_push = 1
			LIMIT 0, 1
			)";
				
			$push_count = $this->update($sql);
				
			// 푸시 전송
			$params = Array(
					"push_code"	=> $push_code,
					"push_msg"	=> $push_msg,
					"push_type"	=> $this->PUSH_TYPE_ADMIN
					);
				
			$this->sendPushBulk($params);
				
			return $this->makeResultJson(1, "전송되었습니다.");
		}
		
		function sendPushForChargeSend()
		{
			$building_name = $this->req["building_name"] ;
			$room_name	= $this->req["room_name"];
			$push_msg = "청구서가 도착했습니다." ;
			$push_code = $this->getBulkPushCode();
			$sql="
				SELECT vip_fk
				FROM tbl_building AS `B`
				JOIN tbl_room AS `R` ON `B`.no = `R`.building_fk
				WHERE `R`.name='{$room_name}' AND `B`.name='{$building_name}'
			";
			$result=$this->getRow($sql);
			$vip_fk=$result["vip_fk"];
			
				
			$sql = "
			INSERT INTO tbl_push_target_bulk(push_code, registration_key, device_type_id, app_type)
			(
			SELECT '{$push_code}', registration_key, device_type_id, app_type
			FROM tbl_user U
			WHERE U.no='{$vip_fk}' AND U.status = 'Y' AND U.registration_key != '' AND is_push = 1
			LIMIT 0, 1
			)
			";
				
			$push_count = $this->update($sql);
				
			// 푸시 전송
			$params = Array(
					"push_code"	=> $push_code,
					"push_msg"	=> $push_msg,
					"push_type"	=> $this->PUSH_TYPE_ADMIN
					);
				
			$this->sendPushBulk($params);
				
			return $this->makeResultJson(1, "전송되었습니다.");
				
		}
		
		function sendAdminPush()
		{
			$push_msg = $this->req["push_msg"];
			$group_no = $this->req["group_no"];
			$push_target_type = $this->req["push_target_type"];
			$push_code = $this->getBulkPushCode();
			
			if($push_target_type == "1"){
				$sql = "
					INSERT INTO tbl_push_target_bulk(push_code, registration_key, device_type_id, app_type)
					(
						SELECT '{$push_code}', registration_key, device_type_id, app_type
						FROM tbl_user U
						WHERE U.status = 'Y' AND U.registration_key != '' AND is_push = 1
					)
				";
				$push_count = $this->update($sql);
			}
			else
			{
				$sql = "
					INSERT INTO tbl_push_target_bulk(push_code, registration_key, device_type_id, app_type)
					(
						SELECT '{$push_code}', registration_key, device_type_id, app_type
						FROM tbl_user U
						WHERE U.group_fk = '{$group_no}' AND U.status = 'Y' AND U.registration_key != '' AND is_push = 1
					)
				";
				$push_count = $this->update($sql);
			}
			
			$sql = "
				INSERT INTO tbl_push_log(push_target_type, group_fk, push_msg, push_count, push_dt)
				VALUES('{$push_target_type}', '{$group_no}', '{$push_msg}', '{$push_count}', NOW())
			";
			$this->update($sql);
			$push_msg = $this->req["push_msg"];
			$group_no = $this->req["group_no"];
			$push_target_type = $this->req["push_target_type"];
			
			// 푸시 전송
			$params = Array(
				"push_code"	=> $push_code,
				"push_msg"	=> $push_msg,
				"push_type"	=> $this->PUSH_TYPE_ADMIN
			);
			$this->sendPushBulk($params);
			
			return $this->makeResultJson(1, "전송되었습니다.");
		}
		
		// 푸시키 코드 조회
		function getBulkPushCode()
		{
			$microTime = microtime();
			$timeArr = explode(" ", $microTime);
			$code = str_replace(".", "", ($timeArr[1] . ($timeArr[0]*1E8)));
			
			return $code;
		}
		
		
		function getListOfAdminPush()
		{
			//최초 페이지 설정
			$this->initPage();
					
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_push_log
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
				
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
				
			$sql = "
				SELECT
					*
				FROM tbl_push_log
				ORDER BY no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
			$result = $this->getArray($sql);
				
			return $result;
		}

	} // class end
}
?>