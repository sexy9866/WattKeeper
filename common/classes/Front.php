<?include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/FrontBase.php";?>
<?
if(!class_exists("Front")){
	class Front extends FrontBase{
		
		function __construct($req) 
		{
			parent::__construct($req);
		}
		
		function wrapParam()
		{
			$this->req['page']	= ($this->req['page'] == "") ? 1 : $this->req['page'] ;
		}

		
		//공지사항 FAQ리스트
		function getListOfNotice()
		{
			$noticeType = $this->req["notice_type"];
			$lastNoticeNo = $this->req["last_notice_no"];

			if($lastNoticeNo == "")
			{
				$limit = " LIMIT 0, 10 ";
			}
			else
			{
				$addWhere .= " AND no < '{$lastNoticeNo}' ";
			}

			$sql = "
				SELECT
					*,
					CASE WHEN DATE_ADD(regist_dt, INTERVAL 1 DAY) > NOW() THEN '1' ELSE '0' END AS is_new
				FROM tbl_notice
				WHERE notice_type = '{$noticeType}' {$addWhere}
				ORDER BY notice_no DESC
				{$limit}
			";

			$list = $this->getArray($sql);
					
			return $list;
		}
		
	}
	
}
?>