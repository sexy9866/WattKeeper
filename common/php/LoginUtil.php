<?php
/*
 * Created on 2006. 09. 25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */ 
if(! class_exists("LoginUtil")){
	 class LoginUtil
	 {
	 	public static $spliter = 30 ;		// Seperator Ascii code
	 	//public static "211.110.140.155";
	 	// public static $cookieDomain = "localhost";
	 	public static $cookieDomain = "106.240.232.36" ;
			
		static function getAdminUser()
		{
			$cookieStr = $_COOKIE["admMap"] ;
	 		
			if( LoginUtil::isAdminLogin() == false ){	
				$map['adminNumber'] = "-1" ;
				// $map['mem_id'] = session_id() ;
			}
			else{
				$cookieStr = pack("H*",$cookieStr);
				
				$aUser = explode(chr(self::$spliter),$cookieStr);
	
				$map['adminNumber']			=	$aUser[0] ;
				$map['adminID']			=	$aUser[1] ;
				$map['adminName']			=	$aUser[2] ;
				$map['regDate']			=	$aUser[3] ;
			}
	
	 		return $map ;	
		}
		
		// 로그인 유무
		static function isAdminLogin(){
			$cookieStr = $_COOKIE["admMap"] ;	
			return ( $cookieStr != "" ) ? true : false ;
		}
		
		//관리자 로그인
		static function doAdminLogin($row){
	
			if($row != null){
				$cookieStr =
	
				$row['adminNumber']			. chr(self::$spliter) .
				$row['adminID']			. chr(self::$spliter) .
				$row['adminName']			. chr(self::$spliter) .
				$row['regDate']			. chr(self::$spliter);
								
				$cookieStr = bin2hex($cookieStr) ; // 16진수로 암호화
	
// 				setcookie("admMap",$cookieStr,-1,"/", self::$cookieDomain);
				setcookie("admMap",$cookieStr,-1,"/", '') ;
//				var_dump(self::$cookieDomain);
//				var_dump($_COOKIE);
				//exit;
				return true;
			}else{
				return false;
			}			
		}
		//admin 로그아웃
		static function doAdminLogout(){
			setcookie("admMap","",time() - 3600,"/",self::$cookieDomain) ;
		}

		//입력 후 로그인 - APP 로그인
		static function doAppLogin($row){	
			if($row != null){
				$cookieStr =
				$row['no']			. chr(self::$spliter) .
				$row['id']			. chr(self::$spliter) .				
				$row['name']		. chr(self::$spliter) .
				$row['group_fk']	. chr(self::$spliter) .
				$row['member_type']	. chr(self::$spliter) .
				$row['app_type']	. chr(self::$spliter) .
				$row['regi_type']	. chr(self::$spliter) ;
								
				$cookieStr = bin2hex($cookieStr) ; // 16진수로 암호화
	
				//setcookie("userMap",$cookieStr,-1,"/", '.richware.co.kr') ;
				setcookie("userMap",$cookieStr,-1,"/", self::$cookieDomain) ;
	
				return true ;
				
			}else{
				
				return false ;
			}
		}
		
		
		// 어플 로그인 여부를 확인한다.
	 	static function isAppLogin(){
	 		$aUser[0] = "";
	 		if(isset($_COOKIE["userMap"])) {
		 		$cookieStr = $_COOKIE["userMap"] ;
		 		
				$cookieStr = pack("H*",$cookieStr);
					
				$aUser = explode(chr(self::$spliter),$cookieStr);		
	 		}
	 		return ( $aUser[0] != "" && $aUser[0] != "-1"  ) ? true : false ;
	 	}
		
		
		static function getAppUser(){
			$cookieStr = isset($_COOKIE["userMap"]) ? $_COOKIE["userMap"] : "" ;
			
			if(isset($_COOKIE["userMap"])){
				$cookieStr = pack("H*",$cookieStr);
				
				$aUser = explode(chr(self::$spliter),$cookieStr);
	
				$map['no']		=	$aUser[0] ;
				$map['id']			=	$aUser[1] ;
				$map['name']		=	$aUser[2] ;
				$map['group_fk']		=	$aUser[3] ;
				$map['member_type']		=	$aUser[4] ;
				$map['regi_type']		=	$aUser[5] ;
			}
			$aUser = explode(chr(self::$spliter),$cookieStr);

			$map['no']		=	$aUser[0] ;
			$map['id']			=	$aUser[1] ;
			$map['name']		=	$aUser[2] ;
			$map['group_fk']		=	$aUser[3] ;
			$map['member_type']		=	$aUser[4] ;
			$map["app_type"]		=	$aUser[5] ;
			$map['regi_type']		=	$aUser[6] ;
			
			if( LoginUtil::isAppLogin() == false )
			{	
				$map['no'] = "-1" ;
			}
	
	 		return $map ;	
		}
		
		
		static function doAppLogout(){
			setcookie("userMap","",time() - 3600,"/",self::$cookieDomain) ;
		}
		
	

 	}	
}
?>