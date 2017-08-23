<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ;?>
<?

if (! class_exists("ApiBoard"))
{

	class ApiBoard extends ApiBase
	{

		function __construct($req)
		{
			parent::__construct($req);
		}

		/**
		 * 민원 등록
		 *
		 * @return string
		 */
		function saveBoard(){
			
			$userNo = $this->appUser["no"];

			$board_type = $this->req["board_type"];
			$contents = $this->req["contents"];
			$no = $this->req["no"];
			$is_public = $this->req["is_public"];
			
			
			if ($no == ""){
				$sql = "
                    INSERT INTO tbl_board(board_type, user_fk, contents, is_public, reg_dt, status)
                    VALUES('{$board_type}', '{$userNo}', '{$contents}', '{$is_public}', NOW(), 'H')
                ";
				$result = $this->update($sql);
				
				if ($result > 0){
					$no = $this->mysql_insert_id();
					$userInfo = $this->inFn_ApiBase_getInfoOfUser($userNo);
					
					LogUtil::writeFileLog($this->logPath, $board_type . " :: " . $userInfo["member_type"]);
					// 푸시 정보 저장
					if ($board_type == $this->BOARD_TYPE_IP){
						$sql = "
							INSERT INTO(group_code, push_msg, board_no, user_fk)
							(
								SELECT '정보나눔 글이 등록되었습니다.', '{$no}'
								FROM v_alive_user U
								WHERE U.group_cd = '{$userInfo["group_cd"]}'
							)
						
						";
					}
					else if($userInfo["member_type"] == $this->MEM_TYPE_MEMBER)
					{
						
						$sql = "
							SELECT *
							FROM tbl_admin
							WHERE target_fk = '{$userInfo["group_fk"]}' AND admin_type = 2 AND is_inquire_position = 1 AND is_apply = 1
							LIMIT 1
						";
						$adminInfo = $this->getRow($sql);
						
						//TODO sms테스트 필요
						if($adminInfo != null && $adminInfo["admin_phone"] != "") {
							$retVal = $this->sendSMS("민원이 등록되었습니다.\n확인바랍니다.", $adminInfo["admin_phone"], $this->SEND_SMS_PHONE);
							
							if(!$retVal) {
								return $this->makeResultJson(-9999, "SMS 에러");
							}
						}
					}
					return $this->makeResultJson("1", "등록되었습니다.", $no);
				}
				else
				{
					return $this->makeResultJson("-1", "등록에 실패했습니다.", $no);
				}
			}
			else
			{
				
				$sql = "
                    UPDATE tbl_board
                    SET
                        contents = '{$contents}'
                        , board_type = '{$board_type}'
                        , is_public = '{$is_public}'
                    WHERE `no` = '{$no}'
                ";
				$this->update($sql);
				
				return $this->makeResultJson("1", "수정되었습니다.", $no);
			}
		}

		/**
		 * 민원 삭제
		 */
		function delBoard()
		{
			$no = $this->req["no"];
			
			$sql = "
				UPDATE tbl_board
				SET is_apply = '-1'
				WHERE `no` = '{$no}'
			";
			$this->update($sql);
			
			return $this->makeResultJson("1", "삭제되었습니다.");
		}

		/**
		 * 민원 리스트
		 */
		function getListOfBoard()
		{
			$userNo = $this->appUser["no"];
			$userInfo = $this->inFn_ApiBase_getInfoOfUser($userNo);
			$where = " WHERE B.is_apply = 1 AND B.user_fk='{$userNo}'";
			

			$this->initPage();
			
			$sql = "
                SELECT COUNT(*)
                FROM tbl_board B
                JOIN tbl_user U ON(B.user_fk = U.no)
                {$where}
            ";
			$this->rownum = $this->getValue($sql, "rn");
			$this->setPage($this->rownum);
			
			$sql = "
				SELECT 
					B.*
					, (SELECT COUNT(*) FROM tbl_comment C WHERE C.target_fk = B.no AND C.comm_type = '{$this->COMMENT_TYPE_BOARD}') AS comment_count
					, U.name
					, 
					IFNULL(
						(SELECT F.file_vir_name FROM tbl_file F WHERE F.pa_no = U.no AND F.file_type = '{$this->FILE_TYPE_MEM}' LIMIT 1)
						, ''
					) AS user_img
				FROM tbl_board B
                JOIN tbl_user U ON(B.user_fk = U.no)
				{$where}
				ORDER BY `no` DESC
				LIMIT {$this->startNum}, {$this->endNum}
			";
			
			$list = $this->getArray($sql);
			
			if (sizeof($list) > 0)
				return $this->makeResultJson("1", "", $list);
			else
				return $this->makeResultJson("-1000", "내역이 없습니다.");
		}

		/**
		 * 민원 상세보기
		 *
		 * @return string
		 */
		function getInfoOfBoard()
		{
			$boardNo = $this->req["no"];
			
			$sql = "
				SELECT
					B.*
					, (SELECT COUNT(*) FROM tbl_comment C WHERE C.target_fk = B.no AND C.comm_type = '{$this->COMMENT_TYPE_BOARD}') AS comment_count
					, U.name
					, 
					IFNULL(
						(SELECT F.file_vir_name FROM tbl_file F WHERE F.pa_no = U.no AND F.file_type = '{$this->FILE_TYPE_MEM}' LIMIT 1)
						, ''
					) AS user_img
				FROM tbl_board B
                JOIN tbl_user U ON(B.user_fk = U.no)
				WHERE B.no = '{$boardNo}'
				LIMIT 1
			";
			$result = $this->getRow($sql);
			
			if ($result != null)
			{
				$sql = "
					SELECT
						C.*
						, U.name
						,
						IFNULL(
							(SELECT F.file_vir_name FROM tbl_file F WHERE F.pa_no = U.no AND F.file_type = '{$this->FILE_TYPE_MEM}' LIMIT 1)
							, ''
						) AS user_img
					FROM tbl_comment C
					JOIN tbl_user U ON(C.user_fk = U.no)
					WHERE target_fk = '{$boardNo}' AND C.comm_type = '{$this->COMMENT_TYPE_BOARD}'
					ORDER BY no DESC
				";
				$commList = $this->getArray($sql);
				
				return $this->makeResultJson("1", "", $result, Array(
					"comm_list" => $commList
				));
			}
			else
				return $this->makeResultJson("-1000", "내역이 없습니다.");
		}

		/**
		 * 민원 댓글 리스트
		 * /action_front.php?cmd=ApiBoard.getListOfBoardComment&no=1&page=1
		 *
		 * @return string
		 */
		function getListOfBoardComment()
		{
			$no = $this->req["no"];
			
			$this->initPage();
			
			$sql = "
				SELECT COUNT(*)
				FROM v_alive_board_comment co, tbl_user us
				WHERE
					co.target_fk='{$no}'
					AND co.user_fk = us.no
				ORDER BY co.no ASC
			";
			
			$this->rownum = $this->getValue($sql, "rn");
			
			$this->setPage($this->rownum);
			
			$sql = "
				SELECT
					co.*
					, us.name as mem_name
					, (SELECT f.file_vir_name FROM tbl_file f WHERE f.pa_no=us.no AND f.file_type='{$this->FILE_TYPE_MEM}' LIMIT 0, 1) AS mem_file
				FROM
					v_alive_board_comment co, tbl_user us
				WHERE
					co.target_fk='{$no}'
					AND co.user_fk = us.no
				ORDER BY co.no ASC
				LIMIT {$this->startNum}, {$this->endNum}
			";
			
			$list = $this->getArray($sql);
			
			if (sizeof($list) > 0)
				return $this->makeResultJson("1", "", $list);
			else
				return $this->makeResultJson("-1000", "내역이 없습니다.");
		}

		/**
		 * 댓글 등록
		 *
		 * @return string
		 */
		function saveBoardComment()
		{
			$boardNo = $this->req["boardNo"];
			$userNo = $this->appUser["no"];
			$comment = $this->req["comment"];
			
			//$userNo = 20;
			
			if ($boardNo == "")
				return $this->makeResultJson("-100", "비정상 접근");
			
			if ($comment == "")
				return $this->makeResultJson("-102", "댓글 내용을 입력해주세요.");
			
			$sql = "
				INSERT INTO tbl_comment(user_fk, target_fk, comment, comm_type, reg_dt)
				VALUES('{$userNo}', '{$boardNo}', '{$comment}', '{$this->COMMENT_TYPE_BOARD}', NOW())
			";
			$result = $this->update($sql);
			
			if ($result > 0)
			{
				
				// 푸시 전송
				$sql = "
					SELECT U.* 
					FROM tbl_board B
					JOIN tbl_user U ON(B.user_fk = U.no)
					WHERE B.no = '{$boardNo}' AND U.is_push = 1 AND U.comm_push  = 1 AND U.registration_key != ''
					LIMIT 1
				";
				$pushKey = $this->getRow($sql);
				
				if($pushKey != null && $userNo != $pushKey["no"])
				{
					$pushObj = new Push();
					$pushObj->pushFlag = $this->PUSH_TYPE_BI_COM;
					$pushObj->pushMessage = "댓글이 등록되었습니다.";
					$pushObj->pushNo = $boardNo;
					$pushObj->sendPushOnce($pushKey);
				}
				
				return $this->makeResultJson("1", "등록되었습니다.");
			}
			else
				return $this->makeResultJson("-1", "등록에 실패했습니다.");
		}

		/**
		 * 민원 댓글 삭제
		 *
		 * @return string
		 */
		function delBoardComment()
		{
			$commentNo = $this->req["commentNo"];
			
			$this->inFn_ApiBase_delComment($commentNo);
			
			return $this->makeResultJson("1", "삭제되었습니다.");
		}
		
		
	} // 클래스 종료
}
?>