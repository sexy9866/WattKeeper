<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?
/*
 * Admin process
 * add by dev.lee
 * */
if(!class_exists("AdminUser")){
	class AdminUser extends  AdminBase {
		
		function __construct($req) 
		{
			parent::__construct($req);
		}
		
		
		/**
		 * 관리자 리스트 조회
		 */
		function getListOfAdminUser()
		{
			$admin_type = $this->req["admin_type"];
			$search_text = $this->req["search_text"];
			
			$login_no = $this->admUser["no"];
			$login_type = $this->admUser["admin_type"];
			
			//최초 페이지 설정
			$this->initPage() ;
			
			$where = " WHERE adm.is_apply = 1 AND adm.admin_type != 1";
			
			if($login_type != "1")
			{
				$where .= " AND adm.no = '{$login_no} '";
			}
			else
			{
				if($admin_type != "")
					$where .= " AND adm.admin_type = '{$admin_type}' ";
				
				if($search_text != "")
					$where .= " AND adm.admin_id LIKE '%{$search_text}%' ";
			}
			
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tblAdmin adm
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
				
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum) ;
			
			$sql = "
				SELECT
					adm.*
					, CASE adm.admin_type 
						WHEN '2' THEN
							IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = adm.target_fk AND G.status = 'Y' LIMIT 1 ), '-')
						WHEN '3' THEN
							IFNULL((SELECT S.name FROM tbl_shop S WHERE S.no = adm.target_fk AND S.status = 'Y' LIMIT 1 ), '-')
						ELSE '-'
					END AS target_name
				FROM tblAdmin adm
				{$where}
				ORDER BY adm.no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		/**
		 * 관리자 상세
		 * @return NULL
		 */
		function getInfoOfAdminUser()
		{
			$no = $this->req["no"];
			
			$sql = "
				SELECT
					adm.*
					, CASE adm.admin_type 
						WHEN '2' THEN
							IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = adm.target_fk AND G.status = 'Y' LIMIT 1 ), '')
						WHEN '3' THEN
							IFNULL((SELECT S.name FROM tbl_shop S WHERE S.no = adm.target_fk AND S.status = 'Y' LIMIT 1 ), '')
						ELSE ''
					END AS target_name
				FROM tblAdmin adm
				WHERE no = '{$no}'
				LIMIT 1
			";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		/**
		 * 관리자 저장
		 * @return string
		 */
		function saveAdminUser()
		{
			$no = $this->req["no"];
			$admin_type = $this->req["admin_type"];
			$admin_id = $this->req["admin_id"];
			$admin_pwd = $this->req["admin_pwd"];
			$admin_name = $this->req["admin_name"];
			$admin_phone = $this->req["admin_phone"];
			$is_inquire_position = $this->req["is_inquire_position"] == "" ? 0 : 1;
			$target_fk = $this->req["target_fk"];
			
			
			$sql = "
				SELECT COUNT(*) AS isReg
				FROM tblAdmin
				WHERE `no` != '{$no}' AND is_apply = 1 AND admin_id = '{$admin_id}'
			";
			$isReg = $this->getValue($sql, "isReg");
			
			if($isReg > 0)
				return $this->makeResultJson(-100, "중복된 아이디입니다.");
			
				
			// 민원 관리자 초기화
			if($admin_type == "2")
			{
				$sql = "
					UPDATE tblAdmin
					SET is_inquire_position = 0
					WHERE target_fk = '{$target_fk}' AND admin_type='{$admin_type}' AND is_apply = 1 
				";
				$this->update($sql);
			}
			
			
			if($no == "")
			{
				$sql = "
					INSERT INTO tblAdmin(admin_type, is_inquire_position, admin_id, admin_pwd, admin_pwd_enc, admin_name, admin_phone, target_fk, is_apply, regist_dt)
					VALUES('{$admin_type}', '{$is_inquire_position}', '{$admin_id}', MD5('{$admin_pwd}'), HEX('{$admin_pwd}'), '{$admin_name}', '{$admin_phone}', '{$target_fk}', 1, NOW())
				";
				$this->update($sql);
				
				return $this->makeResultJson(1, "등록되었습니다.");
			}
			else
			{
				if($admin_pwd != "")
				{
					$addQuery = " , admin_pwd = MD5('{$admin_pwd}'), admin_pwd_enc = HEX('{$admin_pwd}') ";
				}
				
				$sql = "
					UPDATE tblAdmin
					SET
						admin_type = '{$admin_type}'
						, is_inquire_position = '{$is_inquire_position}'
						, admin_id = '{$admin_id}'
						, admin_name = '{$admin_name}'
						, admin_phone = '{$admin_phone}'
						, target_fk = '{$target_fk}'
						{$addQuery}
					WHERE `no` = '{$no}'
				";
				$this->update($sql);
				
				return $this->makeResultJson(1, "수정되었습니다.");
			}
			
		}
		
		
		/**
		 * 관리자 삭제
		 */
		function deleteAdminUser()
		{
			$noArr = $this->req["no"];
			
			$noStr = implode(',', $noArr);
			
			$sql = "
				UPDATE tblAdmin
				SET is_apply = -1
				WHERE `no` IN({$noStr})
			";
			$this->update($sql);
		}

		function getListOfUserForBoard($member_type = "", $vip_status = 0)
		{
			$search_text	= $this->req["search_text"];
			$login_type = $this->admUser["admin_type"];
			$target_fk = $this->admUser["target_fk"];
		
				
			$where = " WHERE status = 'Y' ";
				
			if($login_type != "1")
			{
				$where .= " AND group_fk = '{$target_fk}' ";
			}
				
			$addSelect = "";
			$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
				
			if($member_type == "M")
			{
				$where .= " AND (U.member_type = '{$member_type}' OR U.member_type = 'H')";
				// add all type
				//$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			}
		
				
				
			if($member_type == "V")
			{
				$where .= " AND (U.member_type = '{$member_type}' OR U.member_type = 'VH') ";
				// add all type
				//$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			}
				
			if($vip_status == 1){
				$where .= "AND U.vip_status = {$vip_status} ";
			}
				
			if($search_text != ""){
				$where .= " AND ((U.id LIKE '%{$search_text}%' AND U.regi_type = 'E') OR U.tel LIKE '%{$search_text}%' OR U.name LIKE '%{$search_text}%' ) ";
			}
				
		
		
			if($this->req["page"] != "-1")
			{
				//최초 페이지 설정
				$this->initPage() ;
		
				$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_user U
				{$where}
				";
		
				$this->rownum = $this->getValue($sql, 'rn');
		
				//총 로우수를 획득후 페이지 최종 설정
				$this->setPage($this->rownum) ;
		
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}
		
			$sql = "
			SELECT
			U.*
			{$addSelect}
			FROM tbl_user U
			{$where}
			ORDER BY U.no DESC
			{$limit}
			";
				
			$result = $this->getArray($sql);
				
			//echo json_encode($result);
			return $result;
		}
		

		// 회원 리스트
		function getListOfUser($member_type = "", $vip_status = 0)
		{
			$search_text	= $this->req["search_text"];
			$login_type = $this->admUser["admin_type"];
			$target_fk = $this->admUser["target_fk"];

			
			$where = " WHERE status = 'Y' ";
			
			if($login_type != "1")
			{
				$where .= " AND group_fk = '{$target_fk}' ";
			}
			
			$addSelect = "";
			$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			
			if($member_type == "M")
			{
				$where .= " AND U.member_type = '{$member_type}' ";
				// add all type
				//$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			}
		
			
			
			if($member_type == "V")
			{
				$where .= " AND (U.member_type = '{$member_type}' OR U.member_type = 'VH') ";
				// add all type
				//$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			}
			
			if($vip_status == 1){
				$where .= "AND U.vip_status = {$vip_status} ";
			}
			
			if($search_text != ""){
				$where .= " AND ((U.id LIKE '%{$search_text}%' AND U.regi_type = 'E') OR U.tel LIKE '%{$search_text}%' OR U.name LIKE '%{$search_text}%' ) ";
			}
			
		
				
			if($this->req["page"] != "-1")
			{
				//최초 페이지 설정
				$this->initPage() ;
				
				$sql = "
					SELECT COUNT(*) AS rn
					FROM tbl_user U
					{$where}
				";
				
				$this->rownum = $this->getValue($sql, 'rn');
				
				//총 로우수를 획득후 페이지 최종 설정
				$this->setPage($this->rownum) ;
				
				$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			}

			$sql = "
				SELECT 
					U.*
					{$addSelect}
				FROM tbl_user U
				{$where}
				ORDER BY U.no DESC
				{$limit}
			";
			
			$result = $this->getArray($sql);
			
			//echo json_encode($result);
			return $result;
		}
		
		function getListOfUserForExcel($member_type = "")
		{
			$search_text	= $this->req["search_text"];
				
			$login_type = $this->admUser["admin_type"];
			$target_fk = $this->admUser["target_fk"];
		
				
			$where = " WHERE status = 'Y' ";
				
			if($login_type != "1")
			{
				$where .= " AND group_fk = '{$target_fk}' ";
			}
				
			$addSelect = "";
			$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
				
			if($member_type == "M")
			{
				$where .= " AND (U.member_type = '{$member_type}' OR U.member_type = 'H') ";
				// add all type
				//$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			}
				
			if($member_type == "V")
			{
				$where .= " AND (U.member_type = '{$member_type}' OR U.member_type = 'VH') ";
				// add all type
				//$addSelect .= " , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name ";
			}
				
			if($vip_status == 1){
				$where .= "AND U.vip_status = {$vip_status} ";
			}
				
			if($search_text != "")
				$where .= " AND ((U.id LIKE '%{$search_text}%' AND U.regi_type = 'E') OR U.tel LIKE '%{$search_text}%' OR U.name LIKE '%{$search_text}%' )";
					
		
		
				if($this->req["page"] != "-1")
				{
					//최초 페이지 설정
					$this->initPage() ;
		
					$sql = "
					SELECT COUNT(*) AS rn
					FROM tbl_user U
					{$where}
					";
		
					$this->rownum = $this->getValue($sql, 'rn');
		
					//총 로우수를 획득후 페이지 최종 설정
					$this->setPage($this->rownum) ;
		
					$limit = " LIMIT 0, 999999 ; ";
				}
		
				$sql = "
				SELECT
				U.*
				{$addSelect}
				FROM tbl_user U
				{$where}
				ORDER BY U.no DESC
				{$limit}
				";
					
				$result = $this->getArray($sql);
					
				//echo json_encode($result);
				return $result;
		}
		
		
		/**
		 * 멤버쉽 승인 거절 처리
		 * @return string
		 */
		function processRequestMemberShipUser()
		{
			
			$no = $this->req["no"];
			$member_type = $this->req["member_type"];
			
			$sql = "
				UPDATE tbl_user
				SET member_type = '{$member_type}'
				WHERE `no` = '{$no}'
			";
			$this->update($sql);
			
			// 포인트 충전되어야함
			if($member_type == "M")
			{
				$sql = "
					SELECT U.*, UG.group_point
					FROM tbl_user U
					JOIN tbl_user_group UG ON(U.group_fk = UG.no)
					WHERE U.no = '{$no}'
					LIMIT 1
				";
				$userInfo = $this->getRow($sql);
				
				if($userInfo != null && $userInfo["group_point"] > 0)
					$this->inFn_Common_savePointTrans("I", $no, $userInfo["group_point"], $userInfo["group_fk"], 0, $this->PAY_TYPE_ADMIN);
				
				$pushObj = new Push();
				$pushObj->pushFlag = $this->PUSH_TYPE_MS_OK;
				$pushObj->pushMessage = "멤버십 요청이 승인되었습니다.";
				$pushObj->sendPushOnce($userInfo);
				
				return $this->makeResultJson(1, "승인되었습니다.");
			}
			else if($member_type == 'V'){
				$pushObj = new Push();
				$pushObj->pushFlag = $this->PUSH_TYPE_V_OK;
				$pushObj->pushMessage = "VIP 요청이 승인되었습니다.";
				$pushObj->sendPushOnce($userInfo);
				
				return $this->makeResultJson(1, "VIP 승인되었습니다.");
			}
			else
			{
				$sql = "
					SELECT U.*
					FROM tbl_user U
					WHERE U.no = '{$no}'
					LIMIT 1
				";
				$userInfo = $this->getRow($sql);
				
				$pushObj = new Push();
				$pushObj->pushFlag = $this->PUSH_TYPE_MS_NO;
				$pushObj->pushMessage = "멤버십 회원으로 가입하기 위해서는 소속 회사나 단체에서 먼저 서비스 가입을 해야합니다.\n가입문의 @02-6376-0001# 그룹바이㈜";
				$pushObj->sendPushOnce($userInfo);
				
				return $this->makeResultJson(1, "거절되었습니다.");
			}
			
		}
		
		/**
		 * 회원 삭제
		 */
		function deleteUser()
		{
			$noArr = $this->req["no"];
				
			$noStr = implode(',', $noArr);
				
			$sql = "
				UPDATE tbl_user
				SET status = 'N'
				WHERE `no` IN({$noStr})
			";
			$this->update($sql);
		}


		// 회원 상세 정보
		function getInfoOfUser()
		{
			$no = $this->req["no"];

			$sql = "
				SELECT 
					U.*
					 , IFNULL((SELECT G.name FROM tbl_user_group G WHERE G.no = U.group_fk AND G.status = 'Y' LIMIT 1), '') AS group_name
				FROM tbl_user U
				WHERE U.no = '{$no}'
				LIMIT 1
			";

			$result = $this->getRow($sql);

			return $result;
		}



	} // class end
}
?>