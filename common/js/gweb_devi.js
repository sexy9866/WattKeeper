var _progress ;

var sidoValue = 0;
var zipArr = new Array();

function GWeb()
{
    this.initialize() ;
    
   // _progress = new GProgress() ;
}

GWeb.prototype.initialize = function()
{
   
   
    
} ;

GWeb.prototype.moveDetailPage = function(page, data){
	
	var pageID = history.length;
	
	var originHTML = $("HTML");
	
	$(window).unbind("hashchange") ;
	
	$(window).bind("hashchange", function()
	{
		var hash = location.hash.replace("#", "") ;
	
		if(hash == pageID)
		{
			var detailPage = $("<div/>")
								.attr("id", pageID)
								.css({
									"position"	: "absolute",
									"left"		: "0",
									"top"		: "0",
									"width"		: "100%",
									"height"	: "100%",
									"z-index"	: "2440"
								})
								.load(page, data, function(){
									var self = this;
									
									$(originHTML).children(0).hide();
									
									$("HTML").append(self);
								});
		}
		else
		{
			$("div[id='"+pageID+"']").remove();
			$(originHTML).children(0).show();
		}
		
		console.log($(window));
		
	}) ;
	
	location.hash = pageID;
}

GWeb.prototype.picker2 = function(msgTitle, msg, callBack)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/picker2.php?sidoNumber="+sidoValue, function(){

		$(".area_select:first").find("label").addClass("selected");
		$(".area_select:first").find("input").prop("checked", true);
		
		$(".area_select:first").click(function(){
			
			$(".area_select:not(:first)").find("label").removeClass("selected");
			$(".area_select:not(:first)").find("input").prop("checked", false);
			
			if(!$(this).find("input").is(":checked")){
				$(this).find("label").addClass("selected");
				$(this).find("input").prop("checked", true);
			}else{
				$(this).find("label").removeClass("selected");
				$(this).find("input").prop("checked", false);
			}
		})
		
		$(".area_select:not(:first)").click(function(){
			
			$("#jAll").find("label").removeClass("selected");
			$("#jAll").find("input").prop("checked", false);
			
			if(!$(this).find("input").is(":checked")){
				$(this).find("label").addClass("selected");
				$(this).find("input").prop("checked", true);
			}else{
				$(this).find("label").removeClass("selected");
				$(this).find("input").prop("checked", false);
			}
		})
				
		$("#jMsg").html(msg);
		$("#jTitleMsg").html(msgTitle);		
		$("#closePop").click(function(){
			$(this).closest("#customAlert").remove();
		});
		$("#jApply2").click(function(){
			zipArr = new Array();
			var noCount = $(".area_select:not(:first)").find("input:checked").length;
			for(var i = 0; i < noCount; i++ ){
				zipArr[i] = $(".area_select:not(:first)").find("input:checked").eq(i).val();
			}
			if(noCount == 0) $("#addr2").html('전체');
			else $("#addr2").html(noCount + '개 선택됨');
			
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.picker = function(msgTitle, msg, callBack)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/picker.php", function(){

		$(".area_select:first").find("label").addClass("selected");
		$(".area_select:first").find("input").prop("checked", true);
		
		$(".area_select").click(function(){
			$(".area_select").find("label").removeClass("selected");
			$(".area_select").find("input").prop("checked", false);
			
			$(this).find("label").addClass("selected");
			$(this).find("input").prop("checked", true);
			
		})
				
		$("#jMsg").html(msg);
		$("#jTitleMsg").html(msgTitle);		
		$("#closePop").click(function(){
			$(this).closest("#customAlert").remove();
		});
		$("#jApply").click(function(){
			zipArr = new Array();
			$("#addr2").html('전체');
			sidoValue = $(".area_select").find("input:checked").val();
			$("#addr1").html($(".area_select").find("input:checked").attr("desc"));
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.alert = function(msgTitle, msg, callBack)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/alert.php", function(){
				
		$("#jMsg").html(msg);
		$("#jTitleMsg").html(msgTitle);		
		$("#closePop").click(function(){
			in_CallBack();
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.alertEntity = function(msgTitle, entity, callBack)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/alertBig.php", function(){
		
		
		$.ajax(
			    {
			        type : "POST",
			        url : entity,			    
			        success : function(ret)
			        {
			        	$("#jMsg").html(ret);
			        },
			        error : function(e){
			        	console.log(e);
			        },
			        
			        dataType : "html"
			    });
		
		$("#jTitleMsg").html(msgTitle);		
		$("#closePop").click(function(){
			in_CallBack();
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.alert2 = function(msgTitle, msg, callBack)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/alertBig.php", function(){
				
		$("#jMsg").html(msg);
		$("#jTitleMsg").html(msgTitle);		
		$("#closePop").click(function(){
			in_CallBack();
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.confirmDel = function(msgTitle, msg, callBack, ac_call)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	var ac_CallBack = (ac_call == undefined) ? function(){} : ac_call;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/confirmD.php", function(){
				
		$("#jMsg").html(msg);
		$("#jTitleMsg").html(msgTitle);	
		
		$("#accept").click(function(){
			ac_CallBack();
			$(this).closest("#customAlert").remove();
		});
		$("#closePop").click(function(){
			in_CallBack();
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.confirmCustom = function(msgTitle, msg, callBack, ac_call)
{
	var in_CallBack = (callBack == undefined) ? function(){} : callBack;
	var ac_CallBack = (ac_call == undefined) ? function(){} : ac_call;
	
	var customAlert = $("<div/>").attr("id", "customAlert")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "99999"
	})
	.load("/wapp/popup/confirm.php", function(){
				
		$("#jMsg").html(msg);
		$("#jTitleMsg").html(msgTitle);	
		
		$("#accept").click(function(){
			ac_CallBack();
			$(this).closest("#customAlert").remove();
		});
		$("#closePop").click(function(){
			in_CallBack();
			$(this).closest("#customAlert").remove();
		});
		
		$(self).children().show();
	});
	
	$("body").append(customAlert);
}

GWeb.prototype.confirm = function(msg, buttons)
{
	var customConfirm = $("<div/>").attr("id", "customConfirm")
	.css({
		"position"	: "fixed",
		"left"		: "0",
		"top"		: "0",
		"width"		: "100%",
		"height"	: "100%",
		"z-index"	: "2440"
	})
	.load("/wapp/popup/confirm.php", function(){
		var self = this;
		
		var top = ($(window).height() / 2) - ($(".jConfirmPop").height() / 2);
		
		$(".jConfirmPop").css({"margin-top" : top});
		
		
		$("#jMsg", self).html(msg);
	
		$("#closePop", self).click(function(e){
			e.preventDefault();
			
			$(this).closest("#customConfirm").remove();
		});
		
		for(var i = 0; i < buttons.length; i++)
		{
			var popButton = "";
			var button = buttons[i];
			
			
			popButton = $("<button/>").attr({
							"class"	: "Btn0" + (i+1)
						})
						.css({"width" : "50%"})
						.text(button.name)
						.click(function(e){
							e.preventDefault() ;
						})
						.click(
							button.click
						);
			
			if(i == 0)
				$(popButton).addClass("type02");
			
			$(".Btns", self).append(popButton);
		}
								
		$(self).children().show();
	});
	
	$("BODY").append(customConfirm);
}

GWeb.prototype.go2 = function(template, ui, data)
{
    if (data == undefined)
    {
        data = new Object();
    }
    
    var param = gweb.bin2hex(gweb.implode(gweb.chr(30), data)) ;

    location.href = "/v.php?t=" + template + "&u=" + ui + "&p=" + param ;
};

GWeb.prototype.go = function(template, cmd, data, hash)
{
    if (data == undefined)
    {
        data = new Object();
    }
    
    data["t"] = template ;
    data["cmd"] = cmd ;
    
	var addHash = "" ;

	if( hash != undefined )
		addHash = "#" + hash ;
        
    location.href = "/v.php?" + $.param(data) + addHash;
    
};

GWeb.prototype.bin2hex = function(s)
{
	var i = 0, f = s.length, a = [];		 
	for(;i<f;i++) a[i] = s.charCodeAt(i).toString(16);  
	return a.join('');
} ;

GWeb.prototype.implode = function(separator,array)
{
	var temp = '';
	for(var i=0;i<array.length;i++){
		temp +=  array[i] 
		if(i!=array.length-1)
		{
			temp += separator  ; 
		}
	}//end of the for loop

return temp;

} ;

GWeb.prototype.chr = function(s)
{
	return String.fromCharCode(s) ;	
} ;

GWeb.prototype.back = function()
{
    history.back();
};

GWeb.prototype.scall = function(cmd, data, postExecuteHandler)
{
	if (data == undefined || data == null)
		data = "1=1" ;
		
	data += "&cmd=" + cmd ;
	
	this.call("", data, postExecuteHandler) ;
} ;

GWeb.prototype.call = function(cmd, data, postExecuteHandler)
{
    if (data == undefined || data == null)
        data = new Object() ;
        
    data["cmd"] = cmd ;

    $.ajax(
    {
        type : "POST",
        url : "/e.php",
        data : data,
        beforeSend : function()
        {
        	//dialog.showProgress() ;
            // _progress.show();
        },
        success : function(ret)
        {

            var resultCode = ret["result_code"];
            var result = ret["result"];
            
            if( resultCode == 999 )
            {
            	confirmLogin() ;
            	return false ;
            }

            postExecuteHandler(resultCode, result);

        },
        complete : function(jqXHR, textStatus)
        {
        	dialog.hideProgress() ;
        
            // _progress.hide();
        },
        error : function(e){
        	console.log(e);
        },
        
        dataType : "json"
    });
};

GWeb.prototype.load = function(selector, url, data, callback)
{
	
	//dialog.showProgress() ;
	
	$(selector).load(url, data, function(){
		
		dialog.hideProgress() ;
		
		if( callback != undefined )
			callback() ;
		
	}) ;
	
} ;

GWeb.prototype.appendLoad = function(selector, url, data, callback)
{
	
	//dialog.showProgress() ;
	var holder = $("<div></div>") ;
	holder.load(url, data, function(){
		
		dialog.hideProgress() ;
		
		var html = holder.html() ;
		$(selector).append(html) ;
		
		holder.remove() ;
		
		if( callback != undefined )
			callback(html) ;

		$(window).trigger("appended");
		
	}) ;
	
} ;



GWeb.prototype.emailCheck = function(strValue)
{
	var retVal = true ;
	
	var regExp = /[0-9a-zA-Z][_0-9a-zA-Z-]*@[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+){1,2}$/;
	
	if(strValue.lenght == 0)
	{
		retVal = false;
	}
	
	//이메일 형식에 맞지않으면
	if (!strValue.match(regExp))
	{
		retVal = false;
	}
	
	return retVal ;
} ;

GWeb.prototype.checkbox = function(callback)
{
	$("[checkbox-holder]").each(function(){

		var holderName = $(this).attr("checkbox-holder") ;
		var checkbox = $("[checkbox]", $(this)) ;

		var checked = (checkbox.attr("checkbox") == "checked") ;
		checkbox.attr("src", "/static/img/chk_" + ( checked ? "on":"off" ) + ".png") ;

		$(this).click(function(){
	
			var checkbox = $("[checkbox]", $(this)) ;

			var checked = (checkbox.attr("checkbox") == "checked") ;
			gweb.check(holderName, !checked) ;
			//checkbox.attr("src", "/static/img/chk_" + ( !checked ? "on":"off" ) + ".png").attr("checkbox", !checked ? "checked":"") ;

			if( callback != undefined )
			{
				callback(holderName, !checked) ;
			}

		});

	}) ;
} ;

GWeb.prototype.check = function(holderName, checked)
{
	var checkbox = $("[checkbox-holder='" + holderName + "'] [checkbox]") ;
	checkbox.attr("src", "/static/img/chk_" + ( checked ? "on":"off" ) + ".png").attr("checkbox", checked ? "checked":"") ;
} ;

GWeb.prototype.checked = function(holderName, bo)
{
	var checkbox = $("[checkbox-holder='" + holderName + "'] [checkbox]") ;
	var checked = ( checkbox.attr("checkbox") == "checked" ) ;

	if( bo == undefined )
		return checked ;
	else
		return checked ? "1" : "0" ;
} ;



GWeb.prototype.radio = function(callback)
{
	

	$("[radio-holder]").each(function(){

		var holderName = $(this).attr("radio-holder") ;
		var radios = $("[radio]", $(this)) ;

		radios.each(function(){
			
			var radio = $(this) ;
			var checked = (radio.attr("radio") == "selected") ;

			if( checked )
				radio.addClass("item_over").removeClass("item") ;
			else
				radio.addClass("item").removeClass("item_over") ;

			radio.click(function(){

				var selectedValue = radio.attr("value") ;
				gweb.select(holderName, radio.attr("value")) ;

				if( callback != undefined )
				{
					callback(holderName, selectedValue) ;
				}

			});

		}) ;

	}) ;
} ;

GWeb.prototype.select = function(holderName, selectedValue)
{
	var radios = $("[radio-holder='" + holderName + "'] [radio]") ;
	
	radios.each(function(){

		radios.each(function(){
					
			$(this).addClass("item").removeClass("item_over").attr("radio", "") ;

		}) ;

	}) ;

	var radio = $("[radio-holder='" + holderName + "'] [radio][value='" + selectedValue + "']") ;
	radio.addClass("item_over").removeClass("item").attr("radio", "selected") ;

} ;

GWeb.prototype.selected = function(holderName)
{
	var radios = $("[radio-holder='" + holderName + "'] [radio]") ;
	var selectedValue = "" ;

	radios.each(function(){

		radios.each(function(){
					
			if( $(this).attr("radio") == "selected" )
			{
				selectedValue = $(this).attr("value") ;
				return false ;
			}

		}) ;

	}) ;

	return selectedValue ;

} ;


var gweb = new GWeb();
