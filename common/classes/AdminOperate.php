<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;
include $_SERVER[DOCUMENT_ROOT] . "/common/php/LoginUtil.php";

/*
 * Admin process
 * add by dev.lee
 */
if (! class_exists("AdminOperate"))
{

	class AdminOperate extends AdminBase
	{

		function __construct($req)
		{
			parent::__construct($req);
		}

		
		function setPayStatus()
		{
			$no=$this->req["no"];
			$status=$this->req["status"];
			$type=$this->req["type"];
			
			$set;
			$where="WHERE no='$no'";
			
			if($type=="월세"){		//월세	
				$update="UPDATE tbl_rent_chunggu";
				$sql="
				SELECT is_paid
				FROM tbl_rent_chunggu
				{$where}
				";
				$result=$this->getRow($sql);
			}else if($type=="공과금"){		//공과금
				$update="UPDATE tbl_bill_chunggu";
				$sql="
				SELECT is_paid
				FROM tbl_bill_chunggu
				{$where}
				";
				$result=$this->getRow($sql);
			}else if($type=="퇴실정산서"){		//퇴실정산서
				$update="UPDATE tbl_end_chunggu";
				$sql="
				SELECT is_paid
				FROM tbl_end_chunggu
				{$where}
				";
				$result=$this->getRow($sql);
			}
			
			if($status==1){
				$set="SET is_paid=1";
				$sql="{$update} {$set} {$where}";
				$this->update($sql);
				return $this->makeResultJson("1", "납입처리 되었습니다.");
			}
			else if($status==0){
				$set="SET is_paid=0";
				$sql="{$update} {$set} {$where}";
				$this->update($sql);
				if($type=="퇴실정산서"){
					return $this->makeResultJson("1", "미납처리 되었습니다");
				}
				return $this->makeResultJson("1", "연체처리 되었습니다.");
			}
			
		}
		
		function getListOfRoomForPayment()
		{
			$search_building=$this->req["search_building"];
			$search_room=$this->req["search_room"];
			$rent_date_former=$this->req["rent_date_former"];
			$rent_date_latter=$this->req["rent_date_latter"];
			$bill_date_former=$this->req["bill_date_former"];
			$bill_date_latter=$this->req["bill_date_latter"];
			$leaving_due_date_former=$this->req["leaving_due_date_former"];
			$leaving_due_date_latter=$this->req["leaving_due_date_latter"];
			
			$this->initPage();
			
			$where="WHERE `R`.`status`='Y'";
			if(!empty($search_building)){
				$where.=" AND `B`.`name` LIKE '%{$search_building}%'";
			}
			
			if(!empty($search_room)){
				$where.=" AND `R`.`name` LIKE '%{$search_room}%'";
			}
			//월세납입일 모두 입력
			if(!empty($rent_date_former) && !empty($rent_date_latter)){		
				$where.=" AND `R`.`monthly_rent_date` BETWEEN DAYOFMONTH('{$rent_date_former}') AND DAYOFMONTH('{$rent_date_latter}')";
			}
			//월세납입일 앞쪽만 입력
			if(!empty($rent_date_former) && empty($rent_date_latter)){
				$where.=" AND `R`.`monthly_rent_date` >= DAYOFMONTH('{$rent_date_former}')";
			}
			//월세납입일 뒷쪽만 입력
			if(empty($rent_date_former) && !empty($rent_date_latter)){
				$where.=" AND `R`.`monthly_rent_date` <= DAYOFMONTH('{$rent_date_latter}')";
			}
			//공과금납입일 모두 입력
			if(!empty($bill_date_former) && !empty($bill_date_latter)){
				$where.=" AND `R`.`billing_date` BETWEEN DAYOFMONTH('{$bill_date_former}') AND DAYOFMONTH('{$bill_date_latter}')";
			}
			//공과금납입일 앞쪽만 입력
			if(!empty($bill_date_former) && empty($bill_date_latter)){
				$where.=" AND `R`.`billing_date` >= DAYOFMONTH('{$bill_date_former}')";
			}
			//공과금납입일 뒷쪽만 입력
			if(empty($bill_date_former) && !empty($bill_date_latter)){
				$where.=" AND `R`.`billing_date` <= DAYOFMONTH('{$bill_date_latter}')";
			}
			//퇴실예정일 모두 입력
			if(!empty($leaving_due_date_former) && !empty($leaving_due_date_latter)){
				$where.=" AND `R`.`leaving_due_date` >= '{$leaving_due_date_former}' AND `R`.`leaving_due_date` <= '{$leaving_due_date_latter}'";
			}
			//퇴실예정일 앞쪽만 입력
			if(!empty($leaving_due_date_former) && empty($leaving_due_date_latter)){
				$where.=" AND `R`.`leaving_due_date` >= '{$leaving_due_date_former}'";
			}
			//퇴실예정일 뒷쪽만 입력
			if(empty($leaving_due_date_former) && !empty($leaving_due_date_latter)){
				$where.=" AND `R`.`leaving_due_date` <= '{$leaving_due_date_latter}'";
			}
			
			
			
			
			$select="
					`R`.`no`,
					`R`.`name` AS `Rname`, 
					`R`.`monthly_rent_date`, 
					`R`.`billing_date`, 
					DATE(`R`.`leaving_due_date`) AS leaving_due_date, 
					`B`.`name` AS `Bname`,
					(SELECT 
					`is_paid`
					FROM 
					`tbl_rent_chunggu` 
					WHERE 
					`R`.`no` = `room_fk` AND DATE_FORMAT(month, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m') LIMIT 1) AS `rent_paid`,
					(SELECT 
					`is_paid`
					FROM 
					`tbl_bill_chunggu` 
					WHERE 
					`R`.`no` = `room_fk` AND DATE_FORMAT(month, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m') LIMIT 1) AS `bill_paid`
					";
			$from=' FROM tbl_room AS R JOIN tbl_building AS B ON `R`.`building_fk` = `B`.`no` ';
			
			$sql="SELECT COUNT(*) AS rn {$from} {$where}";
			$this->rownum=$this->getValue($sql, 'rn');
			
			$this->setPage($this->rownum);
			
			$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			$sql="SELECT {$select} {$from} {$where} ORDER BY `R`.`no` DESC {$limit}";
			
			$result=$this->getArray($sql);
			
			//echo json_encode($result);
			return $result;
		}
		
		
		function getListOfEndHistory()
		{
			$no=$_GET["no"];
			
			$this->initPage();
			
			$sql="
			SELECT COUNT(*) as rn
			FROM tbl_end_chunggu AS `E`
			JOIN tbl_room AS `R` ON `E`.`room_fk`=`R`.`no`
			WHERE `E`.`room_fk`='{$no}' AND `E`.`status`='Y'
			ORDER BY month DESC;
			";
			
			$this->rownum = $this->getValue($sql, 'rn');
			
			$this->setPage($this->rownum);
			$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			
			$sql="
				SELECT `R`.`name`, `E`.`no`, DATE(`R`.`entering_due_date`) AS entering_due_date, DATE(`R`.`leaving_due_date`) AS leaving_due_date, `R`.`contractor_name`, `R`.`deposit`, `R`.`balance` AS Rbalance, `E`.`subtraction_total`, `E`.`balance` AS Ebalance, `E`.`is_paid`
				FROM tbl_end_chunggu AS `E`
				JOIN tbl_room AS `R` ON `E`.`room_fk`=`R`.`no`
				WHERE `E`.`room_fk`='{$no}' AND `E`.`status`='Y'
				{$limit}
			";
			$result=$this->getArray($sql);
			//echo json_encode($result);
			return $result;
		}
		
		function getListOfEndHistoryForExcel()
		{
			$no=$_GET["no"];
				
			$this->initPage();
				
			$sql="
			SELECT COUNT(*) as rn
			FROM tbl_end_chunggu AS `E`
			JOIN tbl_room AS `R` ON `E`.`room_fk`=`R`.`no`
			WHERE `E`.`room_fk`='{$no}' AND `E`.`status`='Y'
			ORDER BY month DESC;
			";
				
			$this->rownum = $this->getValue($sql, 'rn');
				
			$this->setPage($this->rownum);
			$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
				
			$sql="
			SELECT `R`.`name`, `E`.`no`, `R`.`entering_due_date`, `R`.`leaving_due_date`, `R`.`contractor_name`, `R`.`deposit`, `R`.`balance` AS Rbalance, `E`.`subtraction_total`, `E`.`balance` AS Ebalance
			FROM tbl_end_chunggu AS `E`
			JOIN tbl_room AS `R` ON `E`.`room_fk`=`R`.`no`
			WHERE `E`.`room_fk`='{$no}' AND `E`.`status`='Y'
			LIMIT 0, 999999
			";
			$result=$this->getArray($sql);
			//echo json_encode($result);
			return $result;
		}
		
		function getListOfRoomHistory()
		{
			$no=$_GET["no"];
			$sql="
				SELECT 
				contractor_name, 
				manage_type, 
				deposit, 
				balance, 
				DATE(contract_date) AS contract_date, 
				DATE(move_in_date) AS move_in_date, 
				DATE(contract_expire_date) AS contract_expire_date, 
				DATE(leaving_due_date) AS leaving_due_date, 
				YEAR(insert_date) AS in_year, 
				MONTH(insert_date) AS in_month,
				DAYOFMONTH(insert_date) AS in_day 
				FROM tbl_room_history
				WHERE no='{$no}'
				ORDER BY pno DESC;
			";
			$result=$this->getArray($sql);
			
			$this->initPage();
			
			$sql = "SELECT COUNT(*) AS rn FROM tbl_room_history WHERE no='{$no}'";
			
			$this->rownum = $this->getValue($sql, 'rn');
				
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
				
			//echo json_encode($result);
			return $result;
		}
		
		public function getInfoOfRoomForBill(){
			$no=$this->req['no'];
			if(empty($no)){
				return array();
			}
				
			$select = 'contractor_name, 
					manage_type, 
					deposit, 
					balance, 
					DATE(contract_date) AS contract_date, 
					DATE(move_in_date) AS move_in_date, 
					DATE(contract_expire_date) AS contract_expire_date, 
					DATE(leaving_due_date) AS leaving_due_date, 
					YEAR(insert_date) AS in_year, 
					MONTH(insert_date) AS in_month,
					DAYOFMONTH(insert_date) AS in_day';
			$from = 'FROM tbl_room';
			$where = "WHERE `no` = {$no}";
				
			$sql = "SELECT {$select} {$from} {$where}";
			$result = $this->getRow($sql);
			//echo json_encode($result);
			return $result;
		}
		
		function getListOfRentHistory()
		{
			$no=$this->req["no"];

			$this->initPage();
				
			$sql="
			SELECT COUNT(*) as rn
			FROM tbl_rent_chunggu AS `C`
			JOIN tbl_room AS `R` ON `C`.`room_fk`=`R`.`no`
			WHERE `C`.`room_fk`='{$no}' AND `C`.`status`='Y'
			ORDER BY month DESC;
			";
		
				
			$this->rownum = $this->getValue($sql, 'rn');
			
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			$sql="
			SELECT `R`.`name`, `C`.`no`, YEAR(`C`.`month`) AS b_year, MONTH(`C`.`month`) AS b_month, `R`.`contractor_name`, `R`.`monthly_rent_date`, `C`.`monthly_rent`, `C`.`maintenance_fee`, `C`.`cable`, `C`.`internet`, `C`.`rent_1`, `C`.`rent_2`, `C`.`etc`, `C`.`is_paid`
			FROM tbl_rent_chunggu AS `C`
			JOIN tbl_room AS `R` ON `C`.`room_fk`=`R`.`no`
			WHERE `C`.`room_fk`='{$no}' AND `C`.`status`='Y'
			ORDER BY C.no DESC
			LIMIT {$this->startNum}, {$this->endNum}
			";
			$result=$this->getArray($sql);
			//echo $limit;
			//echo json_encode($result);
			return $result;
		}
		
		function getListOfRentHistoryForExcel()
		{
			$no=$this->req["no"];
		
			$this->initPage();
		
			$sql="
			SELECT COUNT(*) as rn
			FROM tbl_rent_chunggu AS `C`
			JOIN tbl_room AS `R` ON `C`.`room_fk`=`R`.`no`
			WHERE `C`.`room_fk`='{$no}' AND `C`.`status`='Y'
			ORDER BY month DESC;
			";
		
			$this->rownum = $this->getValue($sql, 'rn');
				
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			
			$sql="
			SELECT `R`.`name`, `C`.`no`, YEAR(`C`.`month`) AS b_year, MONTH(`C`.`month`) AS b_month, `R`.`contractor_name`, `R`.`monthly_rent_date`, `R`.`monthly_rent`, `R`.`maintenance_fee`, `R`.`cable`, `R`.`internet`, `R`.`rent_1`, `R`.`rent_2`, `R`.`etc`, `C`.`is_paid`
			FROM tbl_rent_chunggu AS `C`
			JOIN tbl_room AS `R` ON `C`.`room_fk`=`R`.`no`
			WHERE `C`.`room_fk`='{$no}' AND `C`.`status`='Y'
			ORDER BY month DESC
			LIMIT 0, 999999
			";
			$result=$this->getArray($sql);
			//echo $limit;
			//echo json_encode($result);
			return $result;
		}
		
		function getListOfBillHistory()
		{
			$no=$_GET["no"];
			$this->initPage();
			
			$sql = "SELECT COUNT(*) AS rn FROM tbl_bill_chunggu WHERE room_fk='{$no}' AND status='Y'";
			
			$this->rownum = $this->getValue($sql, 'rn');
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
			
			$sql="
			SELECT `R`.`name`, `B`.`no`, YEAR(`B`.`month`) AS b_year, MONTH(`B`.`month`) AS b_month, `R`.`contractor_name`, `R`.`billing_date`, `B`.`gas_charge`, `B`.`electricity_charge`, `B`.`water_charge`, `B`.`community_electricity_charge`, 
			`B`.community_water_charge, `B`.`is_paid`
			FROM tbl_bill_chunggu AS `B`
			JOIN tbl_room AS `R` ON `B`.`room_fk`=`R`.`no`
			WHERE `B`.`room_fk`='{$no}' AND `B`.`status`='Y'
			ORDER BY B.no DESC {$limit};
			";
			$result=$this->getArray($sql);
			//echo json_encode($result);
			return $result;
			
		}
		
		function getListOfBillHistoryForExcel()
		{
			$no=$_GET["no"];
			$this->initPage();
				
			$sql = "SELECT COUNT(*) AS rn FROM tbl_bill_chunggu WHERE room_fk='{$no}' AND status='Y'";
				
			$this->rownum = $this->getValue($sql, 'rn');
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			$limit = " LIMIT {$this->startNum}, {$this->endNum} ; ";
				
			$sql="
			SELECT `R`.`name`, `B`.`no`, `B`.`month`, `R`.`contractor_name`, `R`.`billing_date`, `B`.`gas_charge`, `B`.`electricity_charge`, `B`.`water_charge`, `B`.`community_electricity_charge`,
			`B`.community_water_charge
			FROM tbl_bill_chunggu AS `B`
			JOIN tbl_room AS `R` ON `B`.`room_fk`=`R`.`no`
			WHERE `B`.`room_fk`='{$no}' AND `B`.`status`='Y'
			ORDER BY month DESC 			
			LIMIT 0, 999999;
			";
			$result=$this->getArray($sql);
			//echo json_encode($result);
			return $result;
				
		}
		
		
		/**
		 * 그룹 리스트
		 */
		function getListOfGroup()
		{
			$search_text = $this->req["search_text"];
			
			//최초 페이지 설정
			$this->initPage();
			
			$where = " WHERE G.status = 'Y' ";
			if ($search_text != "")
				$where .= " AND (G.name LIKE '%{$search_text}%' OR G.manager_name LIKE '%{$search_text}%' OR G.manager_phone LIKE '%{$search_text}%' OR CONCAT(G.addr1, ' ', addr2) LIKE '%{$search_text}%') ";
			
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_user_group G
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
			
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			
			$sql = "
				SELECT 
					G.*
				FROM tbl_user_group G
				{$where}
				ORDER BY G.no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		

		/**
		 * 그룹 상세보기
		 *
		 * @return NULL
		 */
		function getInfoOfGroup()
		{
			$no = $this->req["no"];
			
			$sql = "
				SELECT *
				FROM tbl_user_group G
				WHERE G.no = '{$no}'
				LIMIT 1
			";
			$result = $this->getRow($sql);
			
			return $result;
		}

		/**
		 * 그룹 삭제
		 */
		function delGroup()
		{
			$noArr = $this->req["no"];
			
			$noStr = implode(",", $noArr);
			
			if ($noStr != "")
			{
				$sql = "
					UPDATE tbl_user_group
					SET status = 'N'
					WHERE no IN({$noStr})
				";
				$this->update($sql);
			}
		}

		/**
		 * 그룹 등록
		 */
		function saveGroup()
		{
			$no = $this->req["no"];
			$name = $this->req["name"];
			$manager_name = $this->req["manager_name"];
			$manager_phone = $this->req["manager_phone"];
			$addr1 = $this->req["addr1"];
			$addr2 = $this->req["addr2"];
			$group_point = $this->req["group_point"];
			
			// 좌표 조회
			$geoData = $this->reverseGeocode($addr1 . " " . $addr2);
			$latitude = $geoData["latitude"];
			$longitude = $geoData["longitude"];
			
			if ($no == "")
			{
				$sql = "
					INSERT INTO tbl_user_group(name, manager_name, manager_phone, addr1, addr2, latitude, longitude, status, group_point, regist_dt, is_matched)
					VALUES('{$name}', '{$manager_name}', '{$manager_phone}', '{$addr1}', '{$addr2}', '{$latitude}', '{$longitude}', 'Y', '{$group_point}', NOW(), 'N')
				";
				$this->update($sql);
				
				return $this->makeResultJson("1", "등록되었습니다.");
			}
			else
			{
				$sql = "
					UPDATE tbl_user_group
					SET
						name = '{$name}'
						, manager_name = '{$manager_name}'
						, manager_phone = '{$manager_phone}'
						, addr1 = '{$addr1}'
						, addr2 = '{$addr2}'
						, latitude = '{$latitude}'
						, longitude = '{$longitude}'
						, group_point = '{$group_point}'
					WHERE `no` = '{$no}'
				";
				$this->update($sql);
				
				return $this->makeResultJson("1", "수정되었습니다.");
			}
		}

		/**
		 * 상점 리스트 조회
		 */
		function getListOfShop()
		{
			$promotion = $this->req["promotion"];
			$search_text = $this->req["search_text"];
			
			$target_fk = $this->admUser["target_fk"];
			$login_type = $this->admUser["admin_type"];
			
			
			//최초 페이지 설정
			$this->initPage();
			
			$where = " WHERE S.status = 'Y' ";
			
			if($login_type == "3")
			{
				$where .= " AND S.no = '{$target_fk}' ";
			}
			else
			{
				if ($promotion != "")
					$where .= " AND S.promotion = '{$promotion}' ";
				
				if ($search_text != "")
					$where .= " AND ( S.name LIKE '%{$search_text}%' OR S.tel LIKE '%{$search_text}%' OR S.addr_new LIKE '%{$search_text}%' OR S.addr_old LIKE '%{$search_text}%' )";
			}
			
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_shop S
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
			
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			
			$sql = "
				SELECT *
				FROM tbl_shop S
				{$where}
				ORDER BY S.no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
			$result = $this->getArray($sql);
			
			return $result;
		}

		/**
		 * 상점 정보
		 * 
		 * @return NULL
		 */
		function getInfoOfShop()
		{
			$no = $this->req["no"];
			
			$sql = "
				SELECT *
				FROM tbl_shop S
				WHERE `no` = '{$no}'
				LIMIT 1
			";
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		/**
		 * 퇴실정산서 정보
		 * @return string
		 */
		function getInfoOfEnd()
		{
			$key=$_GET["key"];
			$no=$this->req["no"];
			if(empty($key)){
				$sql="
					SELECT B.name AS Bname, R.name AS Rname, R.contractor_name, YEAR(R.leaving_due_date) AS leaving_y, MONTH(R.leaving_due_date) AS leaving_m, DAYOFMONTH(R.leaving_due_date) AS leaving_d, R.balance
					FROM tbl_room AS R
					JOIN tbl_building AS B ON R.building_fk= B.no
					WHERE R.no='{$no}'
					LIMIT 1
				";
				$result=$this->getRow($sql);
			}
			else{
				$sql="
					SELECT  R.name AS Rname, R.contractor_name, YEAR(R.leaving_due_date) AS leaving_y, MONTH(R.leaving_due_date) AS leaving_m, DAYOFMONTH(R.leaving_due_date) AS leaving_d, E.month, E.subtraction_total, E.balance AS Ebalance, E.daily_fee, E.utility_fee, E.maintenance_fee, E.leaving_fee, E.disposal_fee, E.damage_fee_1, E.damage_fee_2, E.damage_fee_3, E.damage_fee_4, E.cancelation_fee, E.electricity_usage, E.gas_usage, E.water_usage, R.balance
					FROM tbl_end_chunggu AS E 
					JOIN tbl_room AS R ON E.room_fk=R.no
					WHERE E.no='{$key}'
					LIMIT 1
				";
				$result=$this->getRow($sql);
			}
			//echo json_encode($result);
			return $result;
		}
		
		/**
		 * 월세 정보
		 * @return string
		 */
		function getInfoOfRent()
		{
			$key=$_GET["key"];
			$no=$this->req["no"];
			if(empty($key)){
				$sql = "
				SELECT R.monthly_rent_date, R.name, R.maintenance_fee, R.internet, R.cable, R.rent_1, R.rent_2, R.etc, R.monthly_rent, D.name AS Bname
				FROM tbl_room AS R
				JOIN tbl_building AS D ON R.building_fk = D.no
				WHERE R.`no` = '{$no}'
				LIMIT 1
				";
				$result = $this->getRow($sql);	
				
			}
			else{
				$sql="
					SELECT *
					FROM tbl_rent_chunggu
					WHERE no='{$key}'
					LIMIT 1
				";
				$result=$this->getRow($sql);
			}
			
			//echo json_encode($result);
			return $result;
		}
		
		/**
		 * 지난 달 월세 정보
		 * @return string
		 */
		function getInfoOfLMRent()
		{
			$key=$_GET["key"];
				
			if(!empty($key)){
				$sql="
				SELECT MONTH(month) AS M, YEAR(month) AS Y, room_fk
				FROM tbl_rent_chunggu
				WHERE no='{$key}'
				LIMIT 1
				";
				$result=$this->getRow($sql);
			
				$room_fk=$result["room_fk"];
				$month=$result["M"];
				$year=$result["Y"];
			
				$iMonth=intval($month);
				$iYear=intval($year);
			
				if($iMonth == 1) {
					$iMonth=12;
					$iYear-=1;
				}
				else $iMonth-=1;
			
				$month=(string)$iMonth;
				$year=(string)$iYear;
			
				$sql="
				SELECT sum_after_payment, is_paid
				FROM tbl_rent_chunggu
				WHERE `room_fk`='{$room_fk}' AND no < '{$key}'
				ORDER BY no DESC
				LIMIT 1
				";
				
				$result=$this->getRow($sql);
				
				if($result["is_paid"]!=0) return array();
			}
			else{

				$no=$this->req["no"];
					
				$sql= "
				SELECT sum_after_payment, is_paid
				FROM tbl_rent_chunggu
				WHERE `room_fk`='{$no}' 
				ORDER BY no DESC
				LIMIT 1
				";
				$result = $this->getRow($sql);
				if($result["is_paid"]!=0) return array();
			}
			//echo json_encode($result);
			return $result;
		}
		
		/**
		 * 공과금 정보
		 * @return string
		 */
		function getInfoOfBill()
		{
			$key=$_GET["key"];
			$no=$this->req["no"];
			
			if(empty($key)){
				$sql = "
					SELECT R.billing_date, R.name, R.electricity_check_date, R.gas_check_date, R.water_check_date, D.name AS Bname
					FROM tbl_bill_chunggu AS B
					JOIN tbl_room AS R ON B.room_fk=R.no
					JOIN tbl_building AS D ON R.building_fk = D.no 
					WHERE `room_fk` = '{$no}'
					LIMIT 1
				";
				$result = $this->getRow($sql);
				
				if($result == null){
					$sql= "
					SELECT R.billing_date, R.name, R.electricity_check_date, R.gas_check_date, R.water_check_date, B.name AS Bname
					FROM tbl_room AS R
					JOIN tbl_building AS B ON R.building_fk = B.no
					WHERE R.no = '{$no}'
					";
					$result=$this->getRow($sql);
				}
			}
			else{
				$sql="
					SELECT * 
					FROM tbl_bill_chunggu
					WHERE no='{$key}'
					LIMIT 1
				";
				$result=$this->getRow($sql);
			}
			//echo json_encode($result);
			return $result;
		}
		
		function getInfoOfLMBill()
		{
			$key=$_GET["key"];
			
			if(!empty($key)){
				$sql="
				SELECT MONTH(month) AS M, YEAR(month) AS Y, room_fk
				FROM tbl_bill_chunggu
				WHERE no='{$key}'
				LIMIT 1
				";
				$result=$this->getRow($sql);
				
				$room_fk=$result["room_fk"];
				$month=$result["M"];
				$year=$result["Y"];
				
				$iMonth=intval($month);
				$iYear=intval($year);
				
				if($iMonth == 1) {
					$iMonth=12;
					$iYear-=1;
				}
				else $iMonth-=1;
				
				$month=(string)$iMonth;
				$year=(string)$iYear;
				
				$sql="
					SELECT electricity_usage, gas_usage, water_usage, sum_after_payment, is_paid
					FROM tbl_bill_chunggu
					WHERE `room_fk`='{$room_fk}' AND no<'{$key}'
					ORDER BY no DESC
					LIMIT 1
				";
				
				$result=$this->getRow($sql);
				if($result["is_paid"]!=0){
					$sql="
						SELECT electricity_usage, gas_usage, water_usage
						FROM tbl_bill_chunggu
						WHERE `room_fk`='{$room_fk}' AND no<'{$key}'
						ORDER BY no DESC
						LIMIT 1
					";
					$result = $this->getRow($sql);
				}
			}
			else{
				$month=date("m");
				$year=date("Y");
				$no=$this->req["no"];
					
				$iMonth=intval($month);
				$iYear=intval($year);
					
				if($iMonth == 1) {
					$iMonth=12;
					$iYear-=1;
				}
				else $iMonth-=1;
					
				$month=(string)$iMonth;
				$year=(string)$iYear;
				//echo $month;
				//echo $year;
				$sql= "
				SELECT electricity_usage, gas_usage, water_usage, sum_after_payment, is_paid
				FROM tbl_bill_chunggu
				WHERE `room_fk`='{$no}'
				ORDER BY no DESC
				LIMIT 1
				";
				$result = $this->getRow($sql);
				
				if($result["is_paid"] != 0){
					$sql="
					SELECT electricity_usage, gas_usage, water_usage
					FROM tbl_bill_chunggu
					WHERE `room_fk`='{$room_fk}' AND no<'{$key}'
					ORDER BY no DESC
					LIMIT 1
					";
					$result = $this->getRow($sql);
				}
			}
			
			//echo json_encode($result);
			return $result;
		}
		
		/**
		 * 퇴실정산서 저장
		 * @return string
		 */
		function saveEnd()
		{
			$key=$_GET["key"];
			$no=$this->req["no"];
			$month=$this->req["month"];
			$subtraction_total=$this->req["subtraction_total"];
			$balance=$this->req["balance"];
			$daily_fee=$this->req["daily_fee"];
			$utility_fee=$this->req["utility_fee"];
			$maintenance_fee=$this->req["maintenance_fee"];
			$leaving_fee=$this->req["leaving_fee"];
			$disposal_fee=$this->req["disposal_fee"];
			$damage_fee_1=$this->req["damage_fee_1"];
			$damage_fee_2=$this->req["damage_fee_2"];
			$damage_fee_3=$this->req["damage_fee_3"];
			$damage_fee_4=$this->req["damage_fee_4"];
			$cancelation_fee=$this->req["cancelation_fee"];
			$electricity_usage=$this->req["electricity_total_usage"];
			$gas_usage=$this->req["gas_total_usage"];
			$water_usage=$this->req["water_total_usage"];
			
			$sql="
				SELECT leaving_due_date
				FROM tbl_room
				WHERE no='{$no}'
					
			";
			
			$result=$this->getRow($sql);
			
			$leaving_due_date=$result["leaving_due_date"];
			
			if(empty($key)){
				$sql="
				INSERT INTO
				tbl_end_chunggu(room_fk, month, subtraction_total, balance, daily_fee, utility_fee, maintenance_fee, leaving_fee, disposal_fee, damage_fee_1, damage_fee_2, damage_fee_3,
				damage_fee_4, cancelation_fee, electricity_usage, gas_usage, water_usage, is_paid,status, leaving_due_date)
				VALUES
				('{$no}', '{$month}', '{$subtraction_total}', '{$balance}', '{$daily_fee}', '{$utility_fee}', '{$maintenance_fee}', '{$leaving_fee}', '{$disposal_fee}', '{$damage_fee_1}',
				 '{$damage_fee_2}', '{$damage_fee_3}', '{$damage_fee_4}', '{$cancelation_fee}', '{$electricity_usage}', '{$gas_usage}', '{$water_usage}', 2, 'Y', '{$leaving_due_date}')
				";
				
				$this->update($sql);
					
				return $this->makeResultJson(1, "등록되었습니다.");
			}
			else{
				$sql="
					UPDATE tbl_end_chunggu
					SET
						`subtraction_total`='{$subtraction_total}',
						`balance`='{$balance}',
						`daily_fee`='{$daily_fee}',
						`utility_fee`='{$utility_fee}',
						`maintenance_fee`='{$maintenance_fee}',
						`leaving_fee`='{$leaving_fee}',
						`disposal_fee`='{$disposal_fee}',
						`damage_fee_1`='{$damage_fee_1}',
						`damage_fee_2`='{$damage_fee_2}',
						`damage_fee_3`='{$damage_fee_3}',
						`damage_fee_4`='{$damage_fee_4}',
						`cancelation_fee`='{$cancelation_fee}',
						`electricity_usage`='{$electricity_usage}',
						`gas_usage`='{$gas_usage}',
						`water_usage`='{$water_usage}'
					WHERE `no`='{$key}'
						
				";
				$this->update($sql);
				return $this->makeResultJson(1, "수정되었습니다.");
			}
		}
		
		/**
		 * 공과금 저장
		 * @return string
		 */
		function saveBill()
		{
			$key=$_REQUEST[key];
			$no=$this->req["no"];
			$month=$this->req["month"];
			$day=$this->req["month"];
			$electricity_check_date=$this->req["electricity_check_date"];
			$electricity_charge=$this->req["electricity_charge"];
			$gas_check_date=$this->req["gas_check_date"];
			$gas_charge=$this->req["gas_charge"];
			$water_check_date=$this->req["water_check_date"];
			$water_charge=$this->req["water_charge"];
			$community_electricity_check_date=$this->req["community_electricity_check_date"];
			$community_water_check_date=$this->req["community_water_check_date"];
			$community_electricity_charge=$this->req["community_electricity_charge"];
			$community_water_charge=$this->req["community_water_charge"];
			$current_month_late_fee=$this->req["current_month_late_fee"];
			$sum_after_payment=$this->req["sum_after_payment"];
			$electricity_usage=$this->req["electricity_usage"];
			$gas_usage=$this->req["gas_usage"];
			$water_usage=$this->req["water_usage"];
			$current_month_charge=$this->req["current_month_charge"];
			$billing_date=$this->req["billing_date"];
			
			$month=preg_replace("/\s| /",'',$month);
			$month=date("Y-") . $month;
			
			if(empty($key)){
				$sql="
				INSERT INTO
				tbl_bill_chunggu(room_fk, month, electricity_check_date, gas_check_date, water_check_date, community_electricity_check_date, community_water_check_date,
				electricity_charge, gas_charge, water_charge, community_electricity_charge, community_water_charge, current_month_late_fee, sum_after_payment, electricity_usage,
				gas_usage, water_usage, is_paid, status, current_month_charge, billing_date)
				VALUES
				('{$no}', '{$month}', '{$electricity_check_date}', '{$gas_check_date}', '{$water_check_date}', '{$community_electricity_check_date}', '{$community_water_check_date}',
				'{$electricity_charge}', '{$gas_charge}', '{$water_charge}', '{$community_electricity_charge}', '{$community_water_charge}', '{$current_month_late_fee}', '{$sum_after_payment}',
				'{$electricity_usage}', '{$gas_usage}', '{$water_usage}', 2, 'Y', '{$current_month_charge}', '{$billing_date}')
				
				";
				
				$this->update($sql);
					
				return $this->makeResultJson(1, "등록되었습니다.");
			}
			else{
				$sql="
					UPDATE tbl_bill_chunggu
					SET
						`electricity_check_date`='{$electricity_check_date}',
						`gas_check_date`='{$gas_check_date}',
						`water_check_date`='{$water_check_date}',
						`community_electricity_check_date`='{$community_electricity_check_date}',
						`community_water_check_date`='{$community_water_check_date}',
						`electricity_charge`='{$electricity_charge}',
						`gas_charge`='{$gas_charge}',
						`water_charge`='{$water_charge}',
						`community_electricity_charge`='{$community_electricity_charge}',
						`community_water_charge`='{$community_water_charge}',
						`current_month_late_fee`='{$current_month_late_fee}',
						`current_month_charge`='{$current_month_charge}',
						`sum_after_payment`='{$sum_after_payment}',
						`electricity_usage`='{$electricity_usage}',
						`gas_usage`='{$gas_usage}',
						`water_usage`='{$water_usage}'
					WHERE `no`='{$key}'
				";
				$this->update($sql);
				
				return $this->makeResultJson(1, "수정되었습니다.");
			}
		}
		
		function saveRent()
		{
			$key=$_REQUEST[key];
			$no=$this->req["no"];
			$month=$this->req["month"];
			$monthly_rent_date=$this->req["monthly_rent_date"];
			$maintenance_fee=$this->req["maintenance_fee"];
			$internet=$this->req["internet"];
			$cable=$this->req["cable"];
			$rent_1=$this->req["rent_1"];
			$rent_2=$this->req["rent_2"];
			$etc=$this->req["etc"];
			$monthly_rent=$this->req["monthly_rent"];
			$current_month_late_fee=$this->req["current_month_late_fee"];
			$sum_after_payment=$this->req["sum_after_payment"];
			$current_month_charge=$this->req["current_month_charge"];
			$month=preg_replace("/\s| /",'',$month);
			$month=date("Y-") . $month;
			
			$sql="
				SELECT payment_owner
				FROM tbl_room
				WHERE no='{$no}'
			";
			$result=$this->getRow($sql);
			$payment_owner=$result["payment_owner"];
			
			if($key==""){
				$sql="
				INSERT INTO
				tbl_rent_chunggu(room_fk, month, maintenance_fee, internet, cable, rent_1, rent_2, etc, monthly_rent, current_month_late_fee, sum_after_payment, is_paid, status, current_month_charge, monthly_rent_date, payment_owner)
				VALUES
				('{$no}', '{$month}', '{$maintenance_fee}', '{$internet}', '{$cable}', '{$rent_1}', '{$rent_2}', '{$etc}', '{$monthly_rent}', '{$current_month_late_fee}', '{$sum_after_payment}', 2, 'Y', '{$current_month_charge}', '{$monthly_rent_date}', '{$payment_owner}')			
				";
			
				$this->update($sql);
					
				return $this->makeResultJson(1, "등록되었습니dd다.".$key);
			}
			else{
				$sql="
				UPDATE tbl_rent_chunggu
				SET
				`maintenance_fee`='{$maintenance_fee}',
				`internet`='{$internet}',
				`cable`='{$cable}',
				`rent_1`='{$rent_1}',
				`rent_2`='{$rent_2}',
				`etc`='{$etc}',
				`monthly_rent`='{$monthly_rent}',
				`current_month_late_fee`='{$current_month_late_fee}',
				`sum_after_payment`='{$sum_after_payment}',
				`current_month_late_fee`='{$current_month_late_fee}',
				`current_month_charge`='{$current_month_charge}',
				`sum_after_payment`='{$sum_after_payment}',
				`monthly_rent_date`='{$monthly_rent_date}'
				WHERE `no`='{$key}'
				";
				$this->update($sql);
			
				return $this->makeResultJson(1, "수정되었습니다.");
			}
		}

		/**
		 * 상점 저장
		 * @return string
		 */
		function saveShop()
		{
			
			$jsonResult = "";
					
			$no = $this->req["no"];
			$name = $this->req["name"];
			$tel = $this->req["tel"];
			$addr_old = $this->req["addr_old"];
			$addr_new = $this->req["addr_new"];
			$discount_desc = $this->req["discount_desc"];
			$discount_rate = $this->req["discount_rate"];
			$category_cd = $this->req["category_cd"];
			$promotion = $this->req["promotion"];
			
			$longitude = 0;
			$latitude = 0;
			
			$geoData = $this->reverseGeocode($addr_new);
			$latitude = $geoData["latitude"];
			$longitude = $geoData["longitude"];
			
			// 상점 카테고리가 제휴상점일 때 강제로 제휴로 바꿈 
			if($category_cd == "1")
			{
				$promotion = "1";
			}
			
			if($no == "")
			{
				$sql = "
					INSERT INTO tbl_shop(name, tel, addr_old, addr_new, discount_desc, discount_rate, category_cd, promotion, latitude, longitude, regist_dt, status)
					VALUES('{$name}', '{$tel}', '{$addr_old}', '{$addr_new}', '{$discount_desc}', '{$discount_rate}', '{$category_cd}', '{$promotion}', '{$latitude}', '{$longitude}', NOW(), 'Y')
				";
				$this->update($sql);
				
				$shopNumber=$this->mysql_insert_id();
				
				$jsonResult = $this->makeResultJson("1", "등록되었습니다.");
			}
			else
			{
				$sql = "
					UPDATE tbl_shop
					SET	name = '{$name}'
						, tel = '{$tel}'
						, addr_new = '{$addr_new}'
						, addr_old = '{$addr_old}'
						, discount_desc = '{$discount_desc}'
						, discount_rate = '{$discount_rate}'
						, category_cd = '{$category_cd}'
						, promotion = '{$promotion}'
					WHERE no = '{$no}'
				";
// 				, latitude = '{$latitude}'
// 				, longitude = '{$longitude}'				
				$this->update($sql);
				
				$jsonResult = $this->makeResultJson("1", "수정되었습니다.");
			}

			// 삭제된 이미지 제거
			$sql = "
				DELETE FROM tbl_file WHERE `no` NOT IN({$this->req["fileNumber_img1"]}, {$this->req["fileNumber_img2"]}, {$this->req["fileNumber_img3"]}, {$this->req["fileNumber_img4"]}, {$this->req["fileNumber_img5"]}) AND file_type = '{$this->FILE_TYPE_SHOP}' AND pa_no = '{$no}'	
			";
			$this->update($sql);
			
			$imgResult = $this->inFn_Common_fileSave($_FILES);
			
			

			for ($i=1; $i < 6; $i++)
			{
				$fileNumber = $this->req["fileNumber_img" . $i];
				if($fileNumber != "0" && $imgResult["img" . $i] == null)
				{
					$sql = "
						UPDATE tbl_file
						SET ord = {$i}
						WHERE `no` = '{$fileNumber}'
					";
					$this->update($sql);
				}
				else if($fileNumber == "0" && $imgResult["img" . $i] != null)
				{
					if($no == ""){
						$sql="
							INSERT INTO tbl_file(file_org_name, file_vir_name, pa_no, file_type, ord, reg_dt)
							VALUES('{$imgResult["img" . $i]["name"]}', '{$imgResult["img" . $i]["saveURL"]}', '{$shopNumber}', '{$this->FILE_TYPE_SHOP}', {$i}, NOW())
						";	
					}
					else{
						$sql = "
						INSERT INTO tbl_file(file_org_name, file_vir_name, pa_no, file_type, ord, reg_dt)
						VALUES('{$imgResult["img" . $i]["name"]}', '{$imgResult["img" . $i]["saveURL"]}', '{$no}', '{$this->FILE_TYPE_SHOP}', {$i}, NOW())
						";
					}
					
					$this->update($sql);
				}
				else if($fileNumber != "0" && $imgResult["img" . $i] != null)
				{
					$sql = "
						UPDATE tbl_file
						SET
							file_org_name = '{$imgResult["img" . $i]["name"]}'
							,file_vir_name = '{$imgResult["img" . $i]["saveURL"]}'
							,ord = {$i}
						WHERE `no` = '{$fileNumber}'
					";
					$this->update($sql);
				}
			}
			
			return $jsonResult;
		}
		
		/**
		 * 상점 이미지 리스트
		 * @param unknown $shopNo
		 */
		function getShopImgList($shopNo)
		{
			$sql = "
				SELECT *
				FROM tbl_file
				WHERE pa_no = '{$shopNo}' AND file_type = '{$this->FILE_TYPE_SHOP}'
				ORDER BY ord ASC
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		/**
		 * 상점 삭제
		 */
		function delShop()
		{
			$noArr = $this->req["no"];
			
			$noStr = implode(",", $noArr);
			
			if ($noStr != "")
			{
				$sql = "
					UPDATE tbl_shop
					SET status = 'N'
					WHERE no IN({$noStr})
				";
				$this->update($sql);
			}
		}
		
		function delBillUtil()
		{
			$noArr = $this->req["no"];
			$noStr = implode(',', $noArr);
			
			$sql = "
			UPDATE tbl_bill_chunggu
			SET status = 'N'
			WHERE `no` IN({$noStr})
			";
			$this->update($sql);
		}
		
		public function delBillMonth()
		{
			$noArr = $this->req["no"];
			$noStr = implode(',', $noArr);
				
			$sql = "
			UPDATE tbl_rent_chunggu
			SET status = 'N'
			WHERE `no` IN({$noStr})
			";
			$this->update($sql);
		}
		
		public function delBillEnd()
		{
			$noArr = $this->req["no"];
			$noStr = implode(',', $noArr);
			
			$sql = "
			UPDATE tbl_end_chunggu
			SET status = 'N'
			WHERE `no` IN({$noStr})
			";
			$this->update($sql);
		}
		
		/**
		 * 그룹별 할인율 조회
		 * @param unknown $shopNo
		 * @return NULL
		 */
		function getListOfShopRateForGroup($shopNo)
		{
			$admin_type = $this->admUser["admin_type"];
			
			$where = " WHERE SG.shop_fk = '{$shopNo}' AND G.status = 'Y' ";
			
			if($admin_type == "2")
			{
				$groupNo = $this->admUser["target_fk"];
				
				$where .= " AND G.no = '{$groupNo}' ";
			}
			
			$sql = "
				SELECT 
					SG.*
					, G.name AS group_name
				FROM tbl_shop_group_rate SG
				JOIN tbl_user_group G ON(SG.group_fk = G.no)
				{$where}
				ORDER BY group_name ASC
			";
			
			$result = $this->getArray($sql);
			
			return $result;
			
		}
		
		/**
		 * 그룹별 할인율 저장
		 */
		function saveShopGroupRate()
		{
			$group_no		= $this->req["group_no"];
			$shop_no		= $this->req["shop_no"];
			$discount_rate	= $this->req["discount_rate"];
			
			$sql = "
				DELETE FROM tbl_shop_group_rate WHERE shop_fk = '{$shop_no}' AND group_fk = '{$group_no}' 
			";
			$this->update($sql);
			
			$sql = "
				INSERT INTO tbl_shop_group_rate(shop_fk, group_fk, discount_rate, regist_dt)
				VALUES('{$shop_no}', '{$group_no}', '{$discount_rate}', NOW())
			";
			$this->update($sql);
			
			return $this->makeResultJson(1, "저장되었습니다.");
		}
		
		
		/**
		 * 그룹별 할인율 삭제
		 */
		function delShopGroupRate()
		{
			$group_no		= $this->req["group_no"];
			$shop_no		= $this->req["shop_no"];
				
			$sql = "
				DELETE FROM tbl_shop_group_rate WHERE shop_fk = '{$shop_no}' AND group_fk = '{$group_no}'
			";
			$this->update($sql);
			
			return $this->makeResultJson(1, "삭제되었습니다.");
		}
		
		/**
		 * 민원 관리 리스트
		 * @param unknown $groupNo
		 */
		function getListOfBoard($listType)
		{
			
			$groupNo = $this->admUser["target_fk"];
			$admin_type = $this->admUser["admin_type"];
			$board_type = $this->req["board_type"];
			$status = $this->req["status"];
			$search_text = $this->req["search_text"];
			
			$where = " WHERE B.is_apply = 1 ";
			
			if($board_type != "")
				$where .= " AND B.board_type = '{$board_type}' ";
			
			if($status != "")
				$where .= " AND B.status = '{$status}' ";
			
			if($listType == "1")
			{
				$where .= " AND U.group_fk = '0' ";
				
				if($search_text != "")
					$where .= " AND (U.name LIKE '%{$search_text}%' OR B.contents LIKE '%{$search_text}%') ";
			}
			else
			{
				if($admin_type == "1")
				{
					$where .= " AND U.group_fk > 0 ";
				}
				else
				{
					$where .= " AND U.group_fk = '{$groupNo}' ";
				}
				
				if($search_text != "")
					$where .= " AND (U.name LIKE '%{$search_text}%' OR B.contents LIKE '%{$search_text}%' OR UG.name LIKE '%{$search_text}%' ) ";
				
			}
			
			//최초 페이지 설정
			$this->initPage();
				
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_board B
				JOIN tbl_user U ON(B.user_fk = U.no)
				LEFT JOIN tbl_user_group UG ON(UG.no = U.group_fk)
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
				
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
				
			$sql = "
				SELECT 
					B.*
					, U.tel AS tel
					, U.name AS user_name
					, UG.name AS group_name
				FROM tbl_board B
				JOIN tbl_user U ON(B.user_fk = U.no)
				LEFT JOIN tbl_user_group UG ON(UG.no = U.group_fk)
				{$where}
				ORDER BY B.no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
				
			$result = $this->getArray($sql);
					
			return $result;
		}
		
		
		/**
		 * 민원 상세정보
		 * @return NULL
		 */
		function getInfoOfBoard()
		{
			$no = $this->req["no"];
			
			$sql = "
				SELECT
					B.*
					, U.group_fk
					, U.name AS user_name
					, U.tel AS user_tel
					, (SELECT UG.name FROM tbl_user_group UG WHERE UG.no = U.group_fk LIMIT 1) AS group_name
				FROM tbl_board B
				JOIN tbl_user U ON(B.user_fk = U.no)
				WHERE B.no = '{$no}'
				LIMIT 1;
			";
			
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		
		/**
		 * 민원글 삭제
		 */
		function delBoard()
		{
			$noArr = $this->req["no"];
				
			$noStr = implode(",", $noArr);
				
			if ($noStr != "")
			{
				$sql = "
					UPDATE tbl_board
					SET is_apply = -1
					WHERE no IN({$noStr})
				";
				$this->update($sql);
			}
		}
		
		
		/**
		 * 답변 저장
		 * @return string
		 */
		function saveAnswer()
		{
			//require_once $_SERVER["DOCUMENT_ROOT"] . "/common/cls/AdminThriftCall.php";
			$userInfo = LoginUtil::getAdminUser();
			$userNum = $userInfo["no"];
			$targetNum = $_GET["no"];
			$answer = $this->req["answer"];
			$no = $this->req["no"];
			$sql = "
				UPDATE tbl_board
				SET
					answer = '{$answer}'
					, answer_dt = NOW()
					, `status` = 'Y'
				WHERE `no` = '{$no}'
			";
			$this->update($sql);
			
			$sql="
				INSERT INTO tbl_comment(user_fk, target_fk, comment, comm_type, is_admin, reg_dt)
				VALUES ( {$userNum}, {$targetNum}, '{$answer}', 'BD', 'Y', NOW())
			";
			$this->update($sql);
			
			return $this->makeResultJson(1, "저장되었습니다");
		}
		
		
		/**
		 * 민원 댓글
		 * @param unknown $boardNo
		 */
		function getListOfBoardComment($boardNo)
		{
			$where = " WHERE C.target_fk = '{$boardNo}' AND C.comm_type = '{$this->COMMENT_TYPE_BOARD}' ";
				
				
			//최초 페이지 설정
			$this->initPage();
			
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_comment C
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
			
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
			
			$sql = "
				SELECT
					C.*
					, IFNULL(U.name, '관리자') AS user_name
				FROM tbl_comment C
				LEFT JOIN tbl_user U ON(C.user_fk = U.no)
				{$where}
				ORDER BY C.no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
			$result = $this->getArray($sql);
				
			return $result;
		}
		
		
		/**
		 * 댓글 삭제
		 * @return string
		 */
		function delComment()
		{
			$no = $this->req["no"];
			
			$sql = "
				DELETE FROM tbl_comment WHERE `no` = '{$no}'
			";
			$this->update($sql);
			
			return $this->makeResultJson(1, "삭제되었습니다.");
			
		}
		
		
		/**
		 * 팝업 리스트
		 * @param unknown $popup_type
		 */
		function getListOfPopup($popup_type)
		{
			$where = " WHERE popup_type = '{$popup_type}' AND is_apply = 1 ";
			
			//최초 페이지 설정
			$this->initPage();
				
			$sql = "
				SELECT COUNT(*) AS rn
				FROM tbl_popup P
				{$where}
			";
			$this->rownum = $this->getValue($sql, 'rn');
				
			//총 로우수를 획득후 페이지 최종 설정
			$this->setPage($this->rownum);
				
			$sql = "
				SELECT
					P.*
				FROM tbl_popup P
				{$where}
				ORDER BY P.no DESC
				LIMIT {$this->startNum}, {$this->endNum} ;
			";
			$result = $this->getArray($sql);
			
			return $result;
		}
		
		
		/**
		 * 팝업 상세정보 조회
		 * @return NULL
		 */
		function getInfoOfPopup()
		{
			$no = $this->req["no"];
			
			$sql = "
				SELECT *
				FROM tbl_popup
				WHERE `no` = '{$no}'
				LIMIT 1
			";
			
			$result = $this->getRow($sql);
			
			return $result;
		}
		
		
		/**
		 * 팝업 등록
		 * @return string
		 */
		function savePopup()
		{
			$no = $this->req["no"];
			$popup_type = $this->req["popup_type"];
			$link_url = $this->req["link_url"];
			
			$img_path = $this->req["uploaded_img"];
			
			if($_FILES != null)
			{
				$imgResult = $this->inFn_Common_fileSave($_FILES);
				$img_path = $imgResult["img"]["saveURL"];
			}
			
			if($no != "")
			{
				$sql = "
					UPDATE tbl_popup
					SET
						img_path = '{$img_path}'
						, link_url = '{$link_url}'
					WHERE `no` = '{$no}'
				";
				$this->update($sql);
				
				return $this->makeResultJson(1, "수정되었습니다.");
			}
			else
			{
				$sql = "
					INSERT INTO tbl_popup(img_path, link_url, popup_type, is_apply, regist_dt)
					VALUES('{$img_path}', '{$link_url}', '{$popup_type}', 1, NOW())
				";
				$this->update($sql);
				
				return $this->makeResultJson(1, "저장되었습니다.");
			}
			
		}
		
		/**
		 * 팝업 삭제
		 */
		function delPopup()
		{
			$noArr = $this->req["no"];
			
			$noStr = implode(",", $noArr);
			
			if ($noStr != "")
			{
				$sql = "
					UPDATE tbl_popup
					SET is_apply = -1
					WHERE no IN({$noStr})
				";
				$this->update($sql);
			}
		}
		
		
		/**
		 * 팝업 노출 설정
		 * @return string
		 */
		function showPopup()
		{
			$no = $this->req["no"];
			$popup_type = $this->req["popup_type"];
			
			$sql = "
				UPDATE tbl_popup
				SET status = 'N'
				WHERE is_apply = 1 AND popup_type = '{$popup_type}'
			";
			$this->update($sql);
			
			
			$sql = "
				UPDATE tbl_popup
				SET status = 'Y'
				WHERE `no` = '{$no}'
			";
			$this->update($sql);
			
			return $this->makeResultJson(1, "설정되었습니다.");
			
		}
		
	}
}

?>