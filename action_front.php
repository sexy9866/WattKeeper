<? include $_SERVER["DOCUMENT_ROOT"] . "/common/php/LoginUtil.php" ;  ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBase.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiScreen.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiPure.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiShop.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiProcess.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiBoard.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBase.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminProduct.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/Admin.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminUser.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminOperate.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminHospital.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminPush.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminStatistic.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminBoard.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminAccount.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminDisease.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/AdminCommunity.php" ; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/php/AnyGo.php" ?>
<?

	$cmd	= $_REQUEST[cmd] ; 
	
	// Ajax , ToO , ToS , ToPbyRef , ToOandClose , ReOandClose , ReOandToS , 
	// Close , ToP , RePa , None

	//	$url =  pack("H*",$_REQUEST[rurl]);
	//	echo $url ;

// 	echo $cmd . " ////////////// ";


	$nextDisable = false ;		// 디버깅용

	$arr = explode(".", $cmd) ;

	if( sizeof($arr) != 2 )
		echo "[ControlException ] Cmd 형식이 맞지 않습니다." ;
	else
	{
		$clsNm = $arr[0] ;
		$mtdNm = $arr[1] ;
		//var_dump();
		$obj = new ReflectionClass($clsNm)		; 
		$obj= $obj->newInstance($_REQUEST)	;

		$method = new ReflectionMethod($clsNm,$mtdNm) ;

		$flow	= $_REQUEST[flow] ;

		if( $flow == "Ajax" || $flow == "" )			// JSON 이나 AJAX 일경우 	
			echo $method->invoke($obj) ;
		else
		{
			$method->invoke($obj) ;
			if( $nextDisable == false  )
			{
				$rurl	= $_REQUEST[rurl] ;
				$msg	= $_REQUEST[msg] ;
				$flow	= $_REQUEST[flow] ;

				if( $flow == "" )
					echo "[ControlException ] flow 형식이 맞지 않습니다." ;
				else
				{
					go("NORMAL",$flow,$msg,$rurl) ;	
				}

			}

		}

	}

?>