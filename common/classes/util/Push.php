<?php
if(! class_exists("Push") )	{

	class Push
	{
			
		public $pushMessage = "" ;		// Push Message		
		public $pushFlag = "1" ;			// Push flag  - 1:관리자 전체 푸시 / 2:후기 등록 푸시 / 3:포인트 적립 푸시
		public $pushNo = "" ;
		public $pushBadge = "0";
		
		private $gcm_key = "AAAAyeVb4Vo:APA91bGRYGSkQp6BMr--2jaqQxMkIzSJFWXXp0gdf0m7SvFh82oJ73u8DyEkTjOHyAyopcdw55gl3ZPC8pHku9365tC2WIY3uUxwvCYByypSncLdSsmMj_Ox_umLIuNvqNhFJuFFF-MM" ; // 동네
		private $gcm_key2 = "AAAAyeVb4Vo:APA91bGRYGSkQp6BMr--2jaqQxMkIzSJFWXXp0gdf0m7SvFh82oJ73u8DyEkTjOHyAyopcdw55gl3ZPC8pHku9365tC2WIY3uUxwvCYByypSncLdSsmMj_Ox_umLIuNvqNhFJuFFF-MM"; // 직장
		//private $gcm_key = "AIzaSyB2dCs0PHflZYKnlrhF5bNZl_z7FYRYqfY";
		
		// xml 키가 들어 왔을경우 
		function _counstruct($req) 
		{

		}

		// 단일 발송
		function sendPushOnce($pushKey)
		{
			if($pushKey["device_type_id"] != "2" && strlen($pushKey["registration_key"]) > 32)
			{
				if($pushKey["app_type"] == 1){
					$this->sendMessageGCM(Array(
						$pushKey["registration_key"]
					));
				} else {
					$this->sendMessageGCM2(Array(
						$pushKey["registration_key"]
						));					
				}
			}
			else if($pushKey["device_type_id"] == "2" && strlen($pushKey["registration_key"]) > 32)
			{
				$this->sendMessageApnsArray(Array(
					$pushKey["registration_key"]
				));
			}
		}
		
		//벌크단위로 발송
		function sendPushArray($pushKeyArr)
		{
			$gcmKeyArr = Array();
			$gcmKeyArr2 = Array();
			$apnsKeyArr = Array();
			

			if($pushKeyArr != null){
				foreach($pushKeyArr as $key => $pushKey){
					if($pushKey["device_type_id"] != "2" && strlen($pushKey["registration_key"]) > 32){
						if($pushKey["app_type"] == 1){
							array_push($gcmKeyArr, $pushKey["registration_key"]);
						}
						else{
							array_push($gcmKeyArr2, $pushKey["registration_key"]);
						}
					}
					
					if($pushKey["device_type_id"] == "2" && strlen($pushKey["registration_key"]) > 32){
						array_push($apnsKeyArr, $pushKey["registration_key"]);
					}

					if(sizeof($gcmKeyArr) >= 500){
						$this->sendMessageGCM($gcmKeyArr);
						$gcmKeyArr = Array();
					}
					
					if(sizeof($gcmKeyArr2) >= 500){
						$this->sendMessageGCM2($gcmKeyArr);
						$gcmKeyArr = Array();
					}
					
					if(sizeof($apnsKeyArr) >= 100){
						$this->sendMessageApnsArray($apnsKeyArr);
						$apnsKeyArr = Array();
					}

				}
			}



			//자투리 푸시들 전송
			if(sizeof($gcmKeyArr) > 0){
				$this->sendMessageGCM($gcmKeyArr);
			}
			
			if(sizeof($gcmKeyArr) > 0){
				$this->sendMessageGCM2($gcmKeyArr2);
			}
			
			//자투리 푸시들 전송
			if(sizeof($apnsKeyArr) > 0){
				$this->sendMessageApnsArray($apnsKeyArr);
			}

		}

			
		// GCM 서버로 MESSAGE 보내기 (동네)
		function sendMessageGCM($keyArray)
		{
			$ch = curl_init();  

			$msgJson = array(
				"collapse_key" => "score_update" ,
				"time_to_live" => 1 ,
				"delay_while_idle" => true,
				"data" => array(								
					"flag"		=> $this->pushFlag,								
					"message"	=> $this->pushMessage,
					"badge"		=> $this->pushBadge
				),
				"registration_ids" => $keyArray
			) ;
			
			$msg = json_encode($msgJson) ;
			
			$headers = array(
				"Content-Type: application/json", 
				"Content-Length: ". strlen($msg), 
				"Authorization: key=" . $this->gcm_key  
			);
						
			curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
			$result = curl_exec($ch);
			
			curl_close($ch);
		}

		// GCM 서버로 MESSAGE 보내기 (직장)
		function sendMessageGCM2($keyArray)
		{
			$ch = curl_init();
		
			$msgJson = array(
				"collapse_key" => "score_update" ,
				"time_to_live" => 1 ,
				"delay_while_idle" => true,
				"data" => array(
					"flag"		=> $this->pushFlag,
					"message"	=> $this->pushMessage,
					"badge"		=> $this->pushBadge
				),
				"registration_ids" => $keyArray
			) ;
				
			$msg = json_encode($msgJson) ;
				
			$headers = array(
				"Content-Type: application/json",
				"Content-Length: ". strlen($msg),
				"Authorization: key=" . $this->gcm_key2
			);
		
			curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
			$result = curl_exec($ch);
				
			curl_close($ch);
		}


		// 디바이스토큰ID
		function sendMessageApns($deviceToken)
		{
			// 개발용			
			$apnsHost = 'gateway.sandbox.push.apple.com' ;
			$apnsCert = '/www/way21/authFile/way21Dev.pem';
			
			// 운영
			// $apnsHost = 'gateway.push.apple.com' ;
			// $apnsCert = '/www/way21/authFile/way21Dist.pem'; 
			
			$pass = 'pass' ;

			$apnsPort = 2195 ;

			$payload = array('aps' => array('alert' => $this->pushMessage, "no" => $this->push_no, 'flag' => $this->pushFlag , 'badge' => 0, 'sound' => 'default')) ;
			$payload = json_encode($payload) ;

			$streamContext = stream_context_create() ;
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert) ;
			stream_context_set_option($streamContext, 'ssl', 'passphrase', $pass) ;

			// $apns = stream_socket_client($apnsHost, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
			// $apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
			// $apns = stream_socket_client('ssl://gateway.push.apple.com:2195', $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext) ;
			$apns = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext) ;
			
			if($apns)
			{
				$apnsMessage = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $deviceToken)).chr(0).chr(strlen($payload)).$payload ;
				fwrite($apns, $apnsMessage) ;
				fclose($apns) ;
			}
			
			return true ;
		}


		// 어레이 단위로 보내기
		function sendMessageApnsArray($deviceTokenArray)		
		{		 
			
			// 개발용			
			$apnsHost = 'gateway.sandbox.push.apple.com' ;
			$apnsCert = '/home/ohyou/authFile/OhYouDev0207.pem';
			
			// 운영
			// $apnsHost = 'gateway.push.apple.com' ;
			// $apnsCert = '/home/ohyou/authFile/OhYouDist.pem'; 
			
			$pass = 'pass' ;
			

			$apnsPort = 2195 ;

			//echo json_encode($deviceTokenArray);
			
			

			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
			stream_context_set_option($streamContext, 'ssl', 'passphrase', $pass);
			
			$apns = @stream_socket_client('ssl://'.$apnsHost.":".$apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext) ;
			

			if($apns)
			{
				for($i=0; $i<sizeof($deviceTokenArray); $i++){
					$apnsMessage  = "";
					$payload = array('aps' => array('alert' => $this->push_message, 'flag' => $this->push_flag , 'badge' => 0, 'sound' => 'default')) ;
					$payload = json_encode($payload) ;
					//$apnsMessage = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $deviceTokenArray[$i])).chr(0).chr(strlen($payload)).$payload ;
					$apnsMessage = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $deviceTokenArray[$i])).chr(intval(strlen($payload)/256)).chr(intval(strlen($payload)%256)).$payload ;
					fwrite($apns, $apnsMessage) ;
				}
				fclose($apns) ;
			}
			
			return true ;
		}




	}

}

?>
