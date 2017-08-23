<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/meta.php" ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/common/classes/ApiGeneral.php" ?>
<?
	$obj = new ApiGeneral($_REQUEST) ;
	$list = $obj->getMain();
?>

<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<script type="text/javascript" src="/common/js/jquery-1.7.1.min.js" charset="utf-8"></script>

<!-- 기본 스크립트 -->
<script type="text/javascript" src="/common/js/jindo/jindo.js" charset="utf-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.js" charset="utf-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.Component.js" charset="utf-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.UIComponent.js" charset="utf-8"></script>
<script type="text/javascript" src="/common/js/jindo/common.js" charset="utf-8"></script>
<!--// 기본 스크립트 끝 -->
 
<!-- 사용자 script 파일 시작 -->
<script type="text/javascript" src="/common/js/jindo/jindo.m.Touch.js" charset="utf-8"></script>

<script type="text/javascript" src="/common/js/jindo/jindo.m.SwipeCommon.js" charset="uft-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.Effect.js" charset="uft-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.Morph.js" charset="uft-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.Animation.js" charset="uft-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.Slide.js" charset="uft-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.Flick.js" charset="uft-8"></script>
<script type="text/javascript" src="/common/js/jindo/jindo.m.SlideFlicking.js" charset="uft-8"></script>

<script type="text/javascript" src="/common/js/jindo/jindo.m.Flicking.js" charset="utf-8"></script>

<script type = "text/javascript">

var sent = <?=$_REQUEST['sent'] != "" ? $_REQUEST['sent'] : 1?>;

if(sent == 0) location.href = "native://action?cmd=getDeviceInfo&callback=getDeviceInfo";

function getDeviceInfo(deviceID, deviceTypeID, registrationKey) {
	$.ajax({
		url : "/action_front.php?cmd=ApiPure.registerWithoutRedundancy",
		async : false,
		cache : false,
		data : {
			"deviceID" : deviceID,
			"deviceTypeID" : deviceTypeID,
			"registrationKey" : registrationKey
		},
		success : function(data){
			location.href = "index.php?";
		},
		error : function(data){
			console.log(data);
			location.href = "index.php";
		}
	});
}
	$(document).ready(function(){
		
		var nDelay = jindo.m.getDeviceInfo().android? 1000: 0;
	    var nIndex = 0;
	    
	    setTimeout(function(){
	        
	        oFlicking = new jindo.m.Flicking('mflick', {
	            nDefaultIndex:0,
	            sContentClass : 'ct',
	            bAutoResize : true, //화면 사이즈 조정 여부
	            nFlickThreshold : 100, // 콘텐츠가 바뀌기 위한 최소한의 터치 드래그한 거리 (pixel)
	            nDuration : 300
	        });
	        
	        oFlicking.attach({
	            'touchStart' : function(oCustomEvt){
	            },
	            'beforeFlicking' : function(oCustomEvt){
	                //플리킹되기 전에 발생한다
	                nIndex = oCustomEvt.nContentsNextIndex * 1;
	            },
	            'afterFlicking' : function(oCustomEvt){
	                nIndex = this.getContentIndex();
	                setIndicator(nIndex);
	            },
	            'beforeMove' : function(oCustomEvt){
	                //현재 화면에 보이는 콘텐츠가 바꾸기 직전에 수행된다.
	            },
	            'move' : function(oCustomEvt){
	                //현재 화면에 보이는 콘텐츠가 바뀔경우 수행된다
	                
	            }
	        });
	        
	        $(".slide").css({"height" : "280px"});

	        $("#regulation").click(function(){
	        	gweb.alertEntity("이용 약관", "./entity/agreement.html", function(){});
		    });

	        $("#privacy").click(function(){
	        	gweb.alertEntity("개인 정보 수칙 및 사용 동의", "./entity/privacy.html", function(){});
		    });

	        $("#business").click(function(){
	        	gweb.alertEntity("사업자 정보 확인", "./entity/business.html", function(){});
		    });
	        
	    }, nDelay);
	    
	    setIndicator(0);
	    
	});
	function menu01(){
		window.location.href="./cancerManage/main.php"	
    }
	function menu02(){
		window.location.href="./diagPage/main.php";
    }
	function menu03(){
    	window.location.href="./screenManage/main.php"
    }
	function menu04(){
		window.location.href="./info/cInfo.php"
    }

	function goSearch(){
    	window.location.href="./search.php"
    }

    
	
	function setIndicator(num){
		$(".indicator").html('');
		for(var e = 0; e < 3; e++){
			if(e == num) $(".indicator").append('<a><img src="image/ic_main_circle_over.png" width="10" /></a>');
			else $(".indicator").append('<a><img src="image/ic_main_circle.png" width="10" /></a>');
		}
	}
</script>

<!-- 헤더 시작 -->
<?
	$titleName="";
	$enableBack=false; 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/header.php" ?>
<!-- 헤더 끝 -->

<body style="background-color:#0081BA">


<div style="text-align:center;">
    <h1 style="color:#FFFFFF;margin-bottom:50%;margin-top:15%;">WattKeeper</h1>
    <h3 style="color:#FFFFFF;">패밀리 등록</h3>
    <br/>
<?for($e=0; $e < 5;$e++){?>
    <h5 style="color:#FFFFFF;">010-0000-0000</h3>
    <?}?>
</div>
<br/>

</body>

<? include $_SERVER["DOCUMENT_ROOT"] . "/wapp/static/footer.php" ?>