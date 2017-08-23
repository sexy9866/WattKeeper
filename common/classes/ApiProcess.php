<? include_once $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ; ?>
<?php

if (! class_exists("ApiProcess"))
{

	class ApiProcess extends ApiBase
	{
		
		function __construct($req)
		{
			parent::__construct($req);
		}
		
		function processRunGate()
		{
			$logData = "Call API : processRunGate";
			$this->writeFileLog($logData, 'processRunGate');
			
			$this->sendBiPush();
		}
		
		
		function getBulkPushData()
		{
			
			$sql = "
				SELECT * FROM tblDevice WHERE registrationKey != '' AND allowPush = 1
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		
		function sendBulkPush()
		{
			$targetList = $this->req["targetList"];
			
			
			for ($i=0; $i<sizeof($targetList); $i++)
			{
				
				$targetList[$i] = json_decode($targetList[$i], true);
			}
			
			$pushObj = new Push();
			$pushObj->pushFlag = $this->req["push_type"];
			$pushObj->pushNo = $this->req["push_no"];
			$pushObj->pushMessage = $this->req["push_msg"];
			$pushObj->sendPushArray($targetList);
		}
		
		
		/**
		 * 정보공유 푸시 전송
		 * 스케줄러 (1분)
		 */
		function sendBiPush()
		{
			$sql = "
				SELECT group_code, board_no, push_msg
				FROM tbl_bi_push
				WHERE status = 1
				GROUP BY group_code, board_no, push_msg
			";
			$groupCodeList = $this->getArray($sql);
			
			if(sizeof($groupCodeList) > 0)
			{
				$sql = "
					UPDATE tbl_bi_push
					SET status = 2
				";
				$this->update($sql);
				
				$pushObj = new Push();
				for($i = 0; $i<sizeof($groupCodeList); $i++)
				{
					$sql = "
						SELECT U.registrationKey, U.deviceTypeID
						FROM tbl_bi_push P
						JOIN tbl_user U ON(P.user_fk = U.no)
						WHERE
							P.group_code = '{$groupCodeList[$i]["group_code"]}'
							AND U.status = 'Y'
							AND U.is_push = 1
							AND U.info_push = 1
							AND U.registrationKey IS NOT NULL
							AND U.registration_key != ''
					";
					$pushTargetList = $this->getArray($sql);
					
					if(sizeof($pushTargetList) > 0)
					{
						$pushObj->pushFlag = $pushObj->PUSH_TYPE_BI;
						$pushObj->pushNo = $groupCodeList[i]["board_no"];
						$pushObj->pushMessage = $groupCodeList[i]["push_msg"];
						$pushObj->sendPushArray($pushTargetList);
					}
				}
				
			}
			
		}
		
		
		
		
		/**
		 * 포인트 충전 차감
		 * 스케줄러(매월1일 00시)
		 */
		function reChargePoint()
		{
			// 남은 포인트 차감
			$sql = "
				SELECT user_fk, group_fk, IFNULL(SUM(CASE trans_type WHEN 'I' THEN amt ELSE (amt*-1) END ), 0) AS balanceAmt
				FROM tbl_point_trans
				GROUP BY user_fk
				HAVING(balanceAmt > 0)
			";
			$targetList = $this->getArray($sql);
			for($i=0; $i<sizeof($targetList); $i++)
			{
				$this->inFn_Common_savePointTrans("O", $targetList[$i]["user_fk"], $targetList[$i]["balanceAmt"], $targetList[$i]["group_fk"], 0, $this->PAY_TYPE_RETRIEVE);			
			}
			
			
			$sql = "
				INSERT INTO tbl_point_trans(user_fk, group_fk, trans_type, amt, pay_type, reg_dt, reg_date)
				(
					SELECT U.user_fk, U.group_fk, 'I', G.group_point, '{$this->PAY_TYPE_ADMIN}', NOW(), CURDATE()
					FROM tbl_user U
					JOIN tbl_user_group G ON(U.group_fk = G.no)
					WHERE U.status = 'Y' AND U.member_type = 'M' AND G.status = 'Y' AND G.group_point > 0
				)
			";
			$this->update($sql);
			
		}
		
		
		
		
	} //클래스 종료
}
?>