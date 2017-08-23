<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ;?>
<?

/*
 * Admin process
 * add by dev.lee
 */
if (! class_exists("ApiShop"))
{

	class ApiShop extends ApiBase
	{

		function __construct($req)
		{
			parent::__construct($req);
		}
		
		// 카테고리 (http://106.240.232.36:8004/action_front.php?cmd=ApiShop.getListOfCategory)
		function getListOfCategory()
		{
			$sql = "
				SELECT * 
				FROM v_alive_category 
				ORDER BY no ASC
			";
			
			$list = $this->getArray($sql);
			
			if (sizeof($list) > 0)
				return $this->makeResultJson("1", "", $list);
			else
				return $this->makeResultJson("-1000", "내역이 없습니다.");
		}
		
		// 상점리스트 (http://106.240.232.36:8004/action_front.php?cmd=ApiShop.getListOfShop)
		function getListOfShop()
		{
			$categoryCD = $this->req["categoryCD"];
			$promotion = $this->req["promotion"];
			$isAll = $this->req["isAll"];
			$isUnlimitDistance = $this->req["isUnlimitDistance"];
			$name = $this->req["name"];
			$page = $this->req["page"];
			
			$lat = $this->req["lat"] == "" ? 0 : (int) ($this->req["lat"] * 1E6);
			$lng = $this->req["lng"] == "" ? 0 : (int) ($this->req["lng"] * 1E6);
			
			$disKM = 1;
			$latRadius = $this->LAT_ONE_KM * $disKM;
			$lngRadius = $this->LNG_ONE_KM * $disKM;
			
// 			$group_fk = $this->appUser["group_fk"];
// 			$memberType = $this->appUser["member_type"];
			$group_fk = 8;
			$memberType = M;
			
			if ($lat == 0 && lng == 0)
			{
				// 멤버쉽일 경우 지정된 그룹의 주소 기반으로
				if ($memberType == $this->MEM_TYPE_MEMBER)
				{
					$sql = "
						SELECT *
						FROM v_alive_user_group
						WHERE `no`='{$group_fk}'
						LIMIT 0, 1
					";
					
					$memRow = $this->getRow($sql);
					
					$lat = $memRow["latitude"];
					$lng = $memRow["longitude"];
				}
				else
				{
					$lat = 37565852;
					$lng = 126977984;
				}
			}
			
			$where = " WHERE 1=1 ";
			
			if($isUnlimitDistance != "1")
			{
				$where .= " AND sh.latitude BETWEEN ({$lat} - $latRadius) AND ({$lat} + $latRadius) ";
				$where .= " AND sh.longitude BETWEEN ({$lng} - $lngRadius) AND ({$lng} + $lngRadius) ";
				
				$outWhere = " WHERE distance_km < {$disKM} ";
			}
			
			// rowPerPage
			

			if ($categoryCD != "")
				$where .= " AND sh.category_cd='{$categoryCD}' ";
			
			if ($promotion != "")
				$where .= " AND sh.promotion='{$promotion}' ";
			
			if ($name != "")
				$where .= " AND sh.name like '%{$name}%' ";
			
			$orderBy = " ORDER BY promotion DESC, distance_m ASC";
			
			$limitStr = "";
			if ($isAll == "N")
			{
				$this->initPage();
				$sql = "
					SELECT COUNT(TMP.no) AS rn
					FROM
					(
						SELECT 
							sh.*,
							(FLOOR(SQRT(POWER(ABS({$lat} - sh.latitude), 2) + POWER(ABS({$lng} - sh.longitude), 2)) / 10) / 1000) AS distance_km
						FROM v_alive_shop sh
						{$where}
					) AS TMP
					{$outWhere}
				";
				
				$this->rownum = $this->getValue($sql, "rn");
				
				$this->setPage($this->rownum);
				$limitStr = " LIMIT {$this->startNum}, {$this->endNum} ";
			}
			else
			{
				$limitStr = " LIMIT 1000; ";
			}
			
			$sql = "
				SELECT 
					TMP.*
					, (TMP.longitude / 1E6) AS longitude
					, (TMP.latitude / 1E6) AS latitude
				FROM
				(
					SELECT
						sh.no
						, sh.name
						, sh.tel
						, sh.addr_old
						, sh.addr_new
						, sh.latitude
						, sh.longitude
						, sh.discount_desc
						, sh.category_cd
						, sh.promotion
						, IFNULL((SELECT F.file_vir_name FROM tbl_file F WHERE F.pa_no= sh.no AND F.file_type='{$this->FILE_TYPE_SHOP}' ORDER BY RAND() DESC LIMIT 1), '') AS img_path
						, IFNULL((SELECT GR.discount_rate FROM tbl_shop_group_rate GR WHERE sh.no = GR.shop_fk AND GR.group_fk = '{$group_fk}' LIMIT 1), sh.discount_rate) AS discount_rate
						, COUNT(co.no) AS comment_count
						, TRUNCATE(IFNULL(AVG(co.rank), 0), 0) AS rank
						, (FLOOR(SQRT(POWER(ABS('{$lat}' - sh.latitude), 2) + POWER(ABS('{$lng}' - sh.longitude), 2))) / 10.0) AS distance_m
						, (FLOOR(SQRT(POWER(ABS({$lat} - sh.latitude), 2) + POWER(ABS({$lng} - sh.longitude), 2)) / 10) / 1000) AS distance_km
					FROM v_alive_shop sh
					LEFT JOIN tbl_comment co ON(co.target_fk = sh.no AND comm_type = '{$this->COMMENT_TYPE_SHOP}')
					{$where}
					GROUP BY sh.no
					{$orderBy}
				) TMP
				{$outWhere}
				{$limitStr}
			";
			
			$list = $this->getArray($sql);
			
			
			if (sizeof($list) > 0)
				return $this->makeResultJson("1", "", $list);
			else
				return $this->makeResultJson("-1000", "내역이 없습니다.");
		}
		
		// 상점상세보기 (http://106.240.232.36:8004/action_front.php?cmd=ApiShop.getInfoOfShop)
		function getInfoOfShop()
		{
			$no = $this->req["no"];
			
			$lat = $this->req["lat"] == "" ? 0 : (int) ($this->req["lat"] * 1E6);
			$lng = $this->req["lng"] == "" ? 0 : (int) ($this->req["lng"] * 1E6);
			
			$memberType = $this->appUser["member_type"];
			$group_fk = $this->appUser["group_fk"];
			
			if ($lat == 0 && lng == 0)
			{
				// 멤버쉽일 경우 지정된 그룹의 주소 기반으로
				if ($memberType == $this->MEM_TYPE_MEMBER)
				{
					$sql = "
						SELECT *
						FROM v_alive_user_group
						WHERE `no` = '{$group_fk}'
						LIMIT 0, 1
					";
					
					$memRow = $this->getRow($sql);
					
					$lat = $memRow["latitude"];
					$lng = $memRow["longitude"];
				}
				else
				{
					$lat = 37565852;
					$lng = 126977984;
				}
			}
			
			$sql = "
				SELECT 
					sh.no
					, sh.name
					, sh.tel
					, sh.addr_new
					, sh.addr_old
					, sh.latitude
					, sh.longitude
					, sh.discount_desc
					, sh.category_cd
					, sh.promotion
					, IFNULL((SELECT GR.discount_rate FROM tbl_shop_group_rate GR WHERE sh.no = GR.shop_fk AND GR.group_fk = '{$group_fk}' LIMIT 1), sh.discount_rate) AS discount_rate
					, (sh.longitude / 1E6) AS longitude
					, (sh.latitude / 1E6) AS latitude
					, COUNT(co.no) AS comment_count
					, TRUNCATE(IFNULL(AVG(co.rank), 0), 0) AS rank
					, (FLOOR(SQRT(POWER(ABS('{$lat}' - sh.latitude), 2) + POWER(ABS('{$lng}' - sh.longitude), 2))) / 10.0) AS distance_m 
					, (FLOOR(SQRT(POWER(ABS({$lat} - sh.latitude), 2) + POWER(ABS({$lng} - sh.longitude), 2)) / 10) / 1000) AS distance_km
				FROM v_alive_shop sh
				LEFT JOIN tbl_comment co ON(co.target_fk = sh.no AND comm_type = '{$this->COMMENT_TYPE_SHOP}')
				WHERE sh.no = '{$no}'
				GROUP BY sh.no
				LIMIT 0, 1
			";
			
			$row = $this->getRow($sql);
			
			$sql = "
				SELECT * 
				FROM tbl_file 
				WHERE pa_no='{$no}' and file_type='{$this->FILE_TYPE_SHOP}'
			";
			
			$list = $this->getArray($sql);
			
			$entity = array(
				"row" => $row,
				"files" => $list
			);
			
			return $this->makeResultJson("1", "", $entity);
		}
		
		// 상점에 달린 코멘트 보기 (http://106.240.232.36:8004/action_front.php?cmd=ApiShop.getListOfShopComment&no=1)
		function getListOfShopComment()
		{
			$no = $this->req["no"];
			
			$this->initPage();
			
			$sql = "
				SELECT COUNT(*)
				FROM v_alive_shop_comment co, v_alive_user us
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
					v_alive_shop_comment co, v_alive_user us
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
		
		// 할인 금액
		// /action_front.php?cmd=ApiShop.useUserPoint&no=1&usePoint=100
		function useUserPoint()
		{
			$userNo = $this->appUser["no"];
			$groupNo = $this->appUser["group_fk"];
			$shopNo = $this->req["no"];
			$usePoint = $this->req["usePoint"];
			$payAmount = $this->req["payAmount"];
			
			$userBalanceAmt = $this->inFn_Common_getUserPointBalance($userNo);
			
			if ($userBalanceAmt < $usePoint)
				return $this->makeResultJson("-100", "보유 포인트가 부족합니다.", $userBalanceAmt);
			
			$result = $this->inFn_Common_savePointTrans("O", $userNo, $usePoint, $groupNo, $shopNo, $this->PAY_TYPE_USE, $payAmount);
			
			if ($result)
				return $this->makeResultJson("1", "포인트가 차감되었습니다.", ($userBalanceAmt - $usePoint));
			else
				return $this->makeResultJson("-1", "포인트 차감에 실패했습니다.", $userBalanceAmt);
		}

		/**
		 * 상점 리뷰 등록
		 * /action_front.php?cmd=ApiShop.saveShopComment&shopNo=1&rank=1&comment=테스트
		 *
		 * @return string
		 */
		function saveShopComment()
		{
			$shopNo = $this->req["shopNo"];
			$userNo = $this->appUser["no"];
			$rank = $this->req["rank"];
			$comment = $this->req["comment"];
			//$userNo = 1;
			
			if ($shopNo == "")
				return $this->makeResultJson("-100", "비정상 접근");
			if ($rank == "" || $rank == "0")
				return $this->makeResultJson("-101", "별점을 입력해주세요.");
			if ($comment == "")
				return $this->makeResultJson("-102", "리뷰 내용을 입력해주세요.");
			
			$sql = "
				INSERT INTO tbl_comment(user_fk, target_fk, comment, comm_type, rank, reg_dt)
				VALUES('{$userNo}', '{$shopNo}', '{$comment}', '{$this->COMMENT_TYPE_SHOP}', '{$rank}', NOW())
			";
			$result = $this->update($sql);
			
			if ($result > 0)
				return $this->makeResultJson("1", "등록되었습니다.");
			else
				return $this->makeResultJson("-1", "등록에 실패했습니다.");
		}
	} // 클래스 종료
}
?>