<?php
if(! class_exists("Constants") )
{
	class Constants 
	{
	
		/* 개발서버 */
		var $excelSavePath			= "/home/WattKeeper/upload_excel" ;
		var $fileSavePath			= "/home/WattKeeper/upload_img" ;
		var $fileSavePath_720		= "/home/WattKeeper/720" ;				// linux 경로
		var $fileSavePath_640		= "/home/WattKeeper/640" ;				// linux 경로
		var $fileSavePath_480		= "/home/WattKeeper/480" ;				// linux 경로
		var $fileSavePath_320		= "/home/WattKeeper/320" ;				// linux 경로
		var $fileSavePath_100		= "/home/WattKeeper/100" ;				// linux 경로
		var $agreeInfoPath			= "/home/WattKeeper/setting/agree.txt";	// 이용약관 파일 경로
		var $privacyInfoPath		= "/home/WattKeeper/setting/privacy.txt";	// 개인정보취급방침 파일 경로
		var $payAttentionInfoPath	= "/home/WattKeeper/setting/pay_attention.txt";	// 결제시 유의사항
		

		var $logPath				= "/home/WattKeeper/log" ;	// simple 로그기록
		var $documentRoot			= "/home/WattKeeper/" ;	// simple 로그기록
		var $webRoot				= "http://tsh.fingersmith.co.kr" ;			
		var $con_domain				= "http://tsh.fingersmith.co.kr" ;	// 메일에서 사용되는 도메인
		
				
		var $fileSaveUrl			= "/upload_img/" ;
		var $fileSaveUrl_480		= "/480/" ;


		var $dbHost					= "182.161.118.74" ;
		var $dbName					= "WattKeeper" ;
		var $dbUser					= "root" ;
		var $dbPass					= "$#@!richware7" ;
		var $charset				= "utf8" ;
		
		
		/* System Constants */
		var $MEM_TYPE_NOMAL			= "N" ;		// 일반회원
		var $MEM_TYPE_HOLD			= "H" ;		// 멤버쉽 신청중
		var $MEM_TYPE_MEMBER		= "M" ;		// 멤버쉽 회원
		var $MEM_TYPE_VIP		= "V" ;		// VIP 회원
		
		var $MEM_REGI_EMAIL			= "E" ;		// 이메일 회원가입
		var $MEM_REGI_KAKAO			= "K" ;		// 카카오 회원가입
		var $MEM_REGI_FACEBOOK		= "F" ;		// 페이스북 회원가입
		
		var $STATUS_NOMAL			= "Y" ;		// 정상
		var $STATUS_STOP			= "N" ;		// 삭제(탈퇴)
		
		var $POINT_TRAN_IN			= "I" ;		// 충전
		var $POINT_TRAN_OUT			= "O" ;		// 사용
		
		var $POINT_PAY_ADM			= "admin" ;		// 관리자 지급 이벤트
		var $POINT_PAY_RET			= "retrieve" ;	// 회수 이벤트
		
		var $FILE_TYPE_MEM			= "ME" ;	// 회원 파일
		var $FILE_TYPE_SHOP			= "SH" ;	// 상점 파일
		var $FILE_TYPE_CATE			= "CA" ;	// 카테고리 파일
		
		var $COMMENT_TYPE_SHOP		= "SH" ;	// 상점 리뷰
		var $COMMENT_TYPE_BOARD		= "BD" ;	// 게시판 댓글
		
		var $BOARD_TYPE_CV			= "CV" ;	// 민원
		var $BOARD_TYPE_IP			= "IP" ;	// 개선사항
		var $BOARD_TYPE_IN			= "IN" ;	// 정보나눔
		var $BOARD_TYPE_IQ			= "IQ" ;	// 문의접수
		var $BOARD_TYPE_AS			= "AS" ;	// AS신청
		
		var $BUILDING_TYPE_SINGLE	= "SG" ;	// 원룸
		var $BUILDING_TYPE_OFFICE	= "OF" ;	// 오피스텔
		var $BUILDING_TYPE_URBAN	= "UB" ;	// 도생
		var $BUILDING_TYPE_APART	= "AP" ;	// 아파트
		var $BUILDING_TYPE_HOUSE	= "HS" ;	// 주택
		var $BUILDING_TYPE_ETC		= "ET" ;	// 기타
		
		var $ROOM_TYPE_RESIDENT	= "RD";				//입주
		var $ROOM_TYPE_EMPTY = "EP";				//공실
		var $ROOM_TYPE_UNDER_CONTRACT = "UC";		//계약중
		var $ROOM_TYPE_CONTRACT_EXTENSION = "CE";	//계약연장
		
		var $BUILDING_MANAGE_TYPE_LONG_CONS		= "LC" ;	// 장기위탁
		var $BUILDING_MANAGE_TYPE_SHORT_CONS	= "SC" ;	// 단기위탁
		var $BUILDING_MANAGE_TYPE_LONG_SELF		= "LS" ;	// 장기자기
		var $BUILDING_MANAGE_TYPE_SHORT_SELF	= "SS" ;	// 단기자기
		
		var $BANK_NH				= '11'; // 농협
		var $BANK_KB				= '04'; // 국민
		var $BANK_SH				= '88'; // 신한
		var $BANK_IBK				= '03'; // IBK 기업
		var $BANK_SM				= '45'; // 새마을
		var $BANK_BS				= '32'; // 부산
		var $BANK_KN				= '39'; // 경남
		var $BANK_KJ				= '34'; // 광주
		var $BANK_JB				= '37'; // 전북
		var $BANK_SH2				= '48'; // 신협
		var $BANK_SC				= '23'; // SC
		var $BANK_KDB				= '02'; // KDB산업
		var $BANK_DG				= '31'; // 대구
		var $BANK_JJ				= '35'; // 제주
		var $BANK_HN				= '81'; // 하나
		var $BANK_WH				= '05'; // 외환
		var $BANK_POST				= '71'; // 우체국
		var $BANK_SH3				= '07'; // 수협
		var $BANK_NH2				= '07'; // NH투자
		var $BANK_DS				= '07'; // 대신
		
		var $BOARD_PUBLIC_NORMAL	= "Y" ;		// 일반글
		var $BOARD_PUBLIC_CLOSE		= "N" ;		// 비밀글
		

		var $SHOP_PROMOTION_NORMAL	= "0" ;		// 일반업체
		var $SHOP_PROMOTION_JOIN	= "1" ;		// 프로모션 업체
		
		var $POPUP_TYPE_OPEN		= "1" ;		// 실행시 팝업
		var $POPUP_TYPE_CLOSE		= "2" ;		// 종료시 팝업
		
		var $LNG_ONE_KM				= 11259;   // LNG 약 1키로 정도
		var $LAT_ONE_KM				= 9015;   // LNG 약 1키로 정도
		
		var $PAY_TYPE_USE			= "use" ;		// 실행시 팝업
		var $PAY_TYPE_ADMIN			= "admin" ;		// 종료시 팝업
		var $PAY_TYPE_RETRIEVE		= "retrieve" ;		// 종료시 팝업
		var $SEND_SMS_PHONE			= "01042201597";
		
		// 푸시 타입 정의
		public $PUSH_TYPE_BI = "100";
		public $PUSH_TYPE_BI_COM = "101";
		public $PUSH_TYPE_MS_OK = "201";
		public $PUSH_TYPE_MS_NO = "202";
		public $PUSH_TYPE_V_OK = "203";
		public $PUSH_TYPE_V_NO = "204";
		public $PUSH_TYPE_ADMIN = "999";
	}
}
?>