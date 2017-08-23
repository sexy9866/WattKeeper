<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ;?>
<?

if(!class_exists("Admin")){
	class Admin extends  AdminBase {
		
		function __construct($req) {
			parent::__construct($req);
		}
		
		function wrapParam(){
			$this->req['page']	= ($this->req['page'] == "") ? 1 : $this->req['page'] ;
		}

		function getAddQuery(){
			$addQuery = "" ;
			$addQuery .= $this->getSearchQuery() ;

			return $addQuery ;
		}

		function login(){
			$id = $this->req[adm_id];
			$pass = $this->req[adm_pw];
			
			$sql = "
				SELECT *
				FROM tblAdmin 
				WHERE adminID = '{$id}' AND adminPWD = PASSWORD('{$pass}')
				LIMIT 1
			";
			
			$retVal = $this->getRow($sql);
			
			if($retVal == null){
				$_REQUEST[msg] = "로그인 정보가 일치하지 않습니다.";
				return;
			}
			else{
				LoginUtil::doAdminLogin($retVal);
				$_REQUEST[rurl] =  bin2hex("/admin/productManage/productList.php");
				return;
			}
		}
		
		
		//계정 정보 조회
		function getAdminInfo(){
			$no = $this->admUser["adminNumber"];
			
			$sql = "
				SELECT * 
				FROM tblAdmin 
				WHERE adminNumber = '{$no}'
				LIMIT 1
			";
			$result = $this->getRow($sql);
			
			return $result;
		}
			
		
		
		
		function checkLogin(){
			
			if(LoginUtil::isAdminLogin() == false){
				$rurl = bin2hex($_SERVER[REQUEST_URI]) ;
				
				if(stristr($_SERVER[REQUEST_URI],"pop"))
					echo "<script>alert('관리자로 로그인 후 이용할 수 있습니다.') ; opener.location.href = 'index.php'; self.close();</script>" ;
				else
					echo "<script>alert('관리자로 로그인 후 이용할 수 있습니다.') ; location.href = 'index.php' ;</script>" ;
			}
			
		}
		

		function logout(){
			LoginUtil::doAdminLogout();
			$_REQUEST[rurl] = bin2hex("/admin/index.php");
		}
		
		function updateShopLocation($latitude, $longitude, $no){
			
			$sql = "UPDATE tbl_shop
					SET latitude = '{$latitude}', longitude = '{$longitude}'
					WHERE no = '{$no}'";
			
			$this->update($sql);
		}
		
		function getMinShopNo(){
			$sql = "SELECT MIN(no) AS cnt FROM tbl_shop
					WHERE latitude = -1 AND (addr_old != '' OR addr_new != '')
					ORDER BY no";
			
			return $this->getValue($sql, "cnt");
		}
		
		function getShopLocation($count){
			
			$sql = "SELECT no ,addr_old, addr_new FROM tbl_shop
					WHERE (latitude = -1 OR longitude = -1)
					AND (addr_old != '' OR addr_new != '')
					AND no > {$count}
					ORDER BY no 
					LIMIT 0, 100";
			
			return $this->getArray($sql);
		}
		
	
	}
}
?>