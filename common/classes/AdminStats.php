<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?
/*
 * Admin process
 * add by dev.lee
 * */
if(!class_exists("AdminStats")){
	class AdminStats extends  AdminBase {
		
		function __construct($req) 
		{
			parent::__construct($req);
		}
		
		
		/**
		 * 일별 통계실 필요한 월 일 데이터
		 * @param unknown $year
		 * @param unknown $month
		 */
		function getMonthDayListData($year, $month)
		{
			$sql = "
				SELECT *
				FROM tbl_stats_date SD
				WHERE SD.year = '{$year}' AND SD.month = '{$month}'
				ORDER BY SD.date ASC
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		
		/**
		 * 마이포인트 통계
		 */
		function getListOfPointStatsForUser()
		{
			$year	= $this->req["year"] == "" ? date("Y", time()) : $this->req["year"];
			$month	= $this->req["month"] == "" ? date("n", time()) : $this->req["month"];
			
			
			$sql = "
				SELECT 
					SD.date
					, IFNULL(SUM(CASE WHEN PT.pay_type = '{$this->PAY_TYPE_ADMIN}' AND PT.trans_type = 'I' THEN PT.amt ELSE 0 END), 0) AS input_point
					, IFNULL(SUM(CASE WHEN PT.pay_type = '{$this->PAY_TYPE_USE}' AND PT.trans_type = 'O' THEN PT.amt ELSE 0 END), 0) AS output_point
				FROM tbl_stats_date SD
				LEFT JOIN tbl_point_trans PT ON(SD.date = PT.reg_date AND PT.pay_type != '{$this->PAY_TYPE_RETRIEVE}')
				WHERE SD.year = '{$year}' AND SD.month = '{$month}'
				GROUP BY SD.date
				ORDER BY SD.date ASC
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		
		/**
		 * 통계 정보 조회
		 * @return unknown[][]|mixed[][]
		 */
		function getGroupPointStatsData()
		{
			$year	= $this->req["year"] == "" ? date("Y", time()) : $this->req["year"];
			$pointArr = Array();
			
			$where = " WHERE G.status = 'Y' ";
			$subWhere = " WHERE PT.pay_type != '{$this->PAY_TYPE_RETRIEVE}' AND PT.group_fk > 0 AND YEAR(PT.reg_date) = '{$year}' ";
				
			if($this->admUser["admin_type"] == "2")
			{
				$where .= " AND G.no = '{$this->admUser["target_fk"]}' ";
				$subWhere .= " AND PT.group_fk = '{$this->admUser["target_fk"]}'";
			}
			
			$sql = "
				SELECT
					P.month, P.input_point, P.output_point, G.name AS group_name, G.no AS group_fk
					, IFNULL((SELECT A.adminName FROM tblAdmin A WHERE A.target_fk = G.no LIMIT 1), '-') AS manager_name
				FROM tbl_user_group G
				LEFT JOIN (
					SELECT 
						PT.group_fk
						, MONTH(PT.reg_date) AS `month`
						, IFNULL(SUM(CASE WHEN PT.pay_type = '{$this->PAY_TYPE_ADMIN}' AND PT.trans_type = 'I' THEN PT.amt ELSE 0 END), 0) AS input_point
						, IFNULL(SUM(CASE WHEN PT.pay_type = '{$this->PAY_TYPE_USE}' AND PT.trans_type = 'O' THEN PT.amt ELSE 0 END), 0) AS output_point
					FROM tbl_point_trans PT
					{$subWhere}
					GROUP BY PT.group_fk, `month`
					ORDER BY PT.group_fk ASC, `month` ASC
				) P ON(P.group_fk = G.no)
				{$where}
			";
			$result = $this->getArray($sql);
			
			$monthIndex = 0;
			$tmp_group_fk = "0";
			$groupData = Array();
			for($i=0; $i<sizeof($result); $i++)
			{
				if($result[$i]["group_fk"] != $tmp_group_fk)
				{
					if($i > 0)
						$pointArr[] = $groupData;
					
					$monthIndex = 1;
					$tmp_group_fk = $result[$i]["group_fk"];
					$groupData = Array(
						"group_fk"	=> $result[$i]["group_fk"],
						"group_name"	=> $result[$i]["group_name"],
						"manager_name"	=> $result[$i]["manager_name"],
						"list"		=> Array()
					);
				}
				
				$groupData["list"][$result[$i]["month"]] = Array("input_point" => $result[$i]["input_point"], "output_point" => $result[$i]["output_point"]);
			}
			
			$pointArr[] = $groupData;
			
			return $pointArr;
		}
		
		
		
		/**
		 * 상점별 통계
		 * @return unknown[][]|mixed[][]
		 */
		function getShopPointStatsData()
		{
			
			$year	= $this->req["year"] == "" ? date("Y", time()) : $this->req["year"];
			$pointArr = Array();
			
			$where = " WHERE S.status = 'Y' ";
			$subWhere = " WHERE PT.trans_type = 'O' AND PT.pay_type = '{$this->PAY_TYPE_USE}' AND PT.shop_fk > 0 AND YEAR(PT.reg_date) = '{$year}' ";
			
			if($this->admUser["admin_type"] == "3")
			{
				$where .= " AND S.no = '{$this->admUser["target_fk"]}' ";
				$subWhere .= " AND PT.shop_fk = '{$this->admUser["target_fk"]}'";
			}
				
			$sql = "
				SELECT 
					P.month, P.output_point, S.name AS shop_name, S.no AS shop_fk
					, IFNULL((SELECT A.adminName FROM tblAdmin A WHERE A.target_fk = S.no AND A.admin_type = '3' AND A.is_apply = 1 LIMIT 1), '-') AS manager_name
				FROM tbl_shop S
				LEFT JOIN (
					SELECT
						PT.shop_fk
						, MONTH(PT.reg_date) AS `month`
						, IFNULL(SUM(PT.amt), 0) AS output_point
					FROM tbl_point_trans PT
					{$subWhere}
					GROUP BY PT.shop_fk, `month`
					ORDER BY PT.shop_fk ASC, `month` ASC
				) P ON(P.shop_fk = S.no)
				{$where}
			";
			$result = $this->getArray($sql);
				
			$monthIndex = 0;
			$tmp_shop_fk = "0";
			$shopData = Array();
			for($i=0; $i<sizeof($result); $i++)
			{
				if($result[$i]["shop_fk"] != $tmp_shop_fk)
				{
					if($i > 0)
						$pointArr[] = $shopData;
							
						$monthIndex = 1;
						$tmp_shop_fk = $result[$i]["shop_fk"];
						$shopData = Array(
							"shop_fk"	=> $result[$i]["shop_fk"],
							"shop_name"	=> $result[$i]["shop_name"],
							"manager_name"	=> $result[$i]["manager_name"],
							"list"		=> Array()
						);
				}
		
				$shopData["list"][$result[$i]["month"]] = $result[$i]["output_point"];
			}
				
			$pointArr[] = $shopData;
				
			return $pointArr;
				
		}
		
		
		
		
		/**
		 * 그룹별 상점 통계
		 * @return unknown[][]|mixed[][]
		 */
		function getShopPointStatsDataForGroup()
		{
			$no = $this->req["no"];
			$year	= $this->req["year"] == "" ? date("Y", time()) : $this->req["year"];
			$month	= $this->req["month"] == "" ? date("n", time()) : $this->req["month"];
			$pointArr = Array();
		
			$sql = "
				SELECT G.no AS group_fk, G.name AS group_name, SD.date, IFNULL(SUM(PT.amt), 0) AS output_point
				FROM 
				(
					SELECT *
					FROM tbl_user_group G
					WHERE G.no IN(
						SELECT T.group_fk
						FROM tbl_point_trans T
						WHERE T.shop_fk = '{$no}' AND T.trans_type = 'O' AND T.pay_type = 'use' AND YEAR(T.reg_date) = '{$year}' AND MONTH(T.reg_date) = '{$month}'
						GROUP BY T.group_fk
					)
				) G
				JOIN tbl_stats_date SD ON (SD.year = '{$year}' AND SD.month = '{$month}')
				LEFT JOIN tbl_point_trans PT ON(PT.shop_fk = '{$no}' AND PT.trans_type = 'O' AND PT.pay_type = 'use' AND SD.date = PT.reg_date AND G.no = PT.group_fk)
				GROUP BY G.no, SD.date
				ORDER BY G.no ASC, SD.date ASC
			";
			$result = $this->getArray($sql);
		
			$monthIndex = 0;
			$tmp_group_fk = "0";
			$groupData = Array();
			for($i=0; $i<sizeof($result); $i++)
			{
				if($result[$i]["group_fk"] != $tmp_group_fk)
				{
					if($i > 0)
						$pointArr[] = $groupData;
							
						$monthIndex = 1;
						$tmp_group_fk = $result[$i]["group_fk"];
						$groupData = Array(
							"group_fk"	=> $result[$i]["group_fk"],
							"group_name"	=> $result[$i]["group_name"],
							"total_point"	=> 0,
							"list"		=> Array()
						);
				}
		
				$groupData["list"][$result[$i]["date"]] = $result[$i]["output_point"];
				$groupData["total_point"] += $result[$i]["output_point"];
			}
		
			$pointArr[] = $groupData;
		
			return $pointArr;
		
		}
		
		
		/**
		 * 상점 통계 회원별
		 * @return mixed[][]
		 */
		function getShopPointStatsDataForUser()
		{
			$no = $this->req["no"];
			$year	= $this->req["year"] == "" ? date("Y", time()) : $this->req["year"];
			$month	= $this->req["month"] == "" ? date("n", time()) : $this->req["month"];
			$pointArr = Array();
		
			$sql = "
				SELECT	
					U.no AS user_fk, U.name  AS user_name, SD.date, IFNULL(SUM(PT.amt), 0) AS output_point
				FROM
				(
					SELECT *
					FROM tbl_user U
					WHERE U.no IN(
						SELECT T.user_fk
						FROM tbl_point_trans T
						WHERE T.shop_fk = '{$no}' AND T.trans_type = 'O' AND T.pay_type = 'use' AND YEAR(T.reg_date) = '{$year}' AND MONTH(T.reg_date) = '{$month}'
						GROUP BY T.user_fk
					)
				) U
				JOIN tbl_stats_date SD ON (SD.year = '{$year}' AND SD.month = '{$month}')
				LEFT JOIN tbl_point_trans PT ON(PT.shop_fk = '{$no}' AND PT.trans_type = 'O' AND PT.pay_type = 'use' AND SD.date = PT.reg_date AND U.no = PT.user_fk)
				GROUP BY U.no, SD.date
				ORDER BY U.no ASC, SD.date ASC
			";
			$result = $this->getArray($sql);
		
			$tmp_user_fk = "0";
			$groupData = Array();
			for($i=0; $i<sizeof($result); $i++)
			{
				if($result[$i]["user_fk"] != $tmp_user_fk)
				{
					if($i > 0)
						$pointArr[] = $groupData;
							
						$monthIndex = 1;
						$tmp_user_fk = $result[$i]["user_fk"];
						$groupData = Array(
							"user_fk"	=> $result[$i]["user_fk"],
							"user_name"	=> $result[$i]["user_name"],
							"total_point"	=> 0,
							"list"		=> Array()
						);
				}
		
				$groupData["list"][$result[$i]["date"]] = $result[$i]["output_point"];
				$groupData["total_point"] += $result[$i]["output_point"];
			}
		
			$pointArr[] = $groupData;
		
			return $pointArr;
		
		}


	} // class end
}
?>