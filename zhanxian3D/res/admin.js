$(function(){
		$.fn.datebox.defaults.parser = parseDate;
		$.fn.datebox.defaults.formatter = formatDateText;
});
function exportData(tb,url){
     var param = {};
     $.each($("#"+tb+" .tab_search :input").serializeArray(),function(i,o){
             if($.trim(o.value) != "")
                param[o.name] = o.value;

     });
	 window.open(url+"?export=1&"+$.param(param));
}
function reloadData(tb){
	 var param = {};
	 $.each($("#"+tb+" .tab_search :input").serializeArray(),function(i,o){
		 	 if($.trim(o.value) != "")
			 	param[o.name] = o.value;
		  
	 });
	 _period = param.period;

		$('#'+tb+" #dataTable").datagrid("clearSelections");
		$('#'+tb+" #dataTable").datagrid("load",param);
	// reloadGraph();
	 
}
function open1(title,url){
	if ($('#tt').tabs('exists',title)){
		$('#tt').tabs('select', title);
		} else {
			$('#tt').tabs('add',{
			title:title,
			href:url, 
			closable:true ,
			tools:[{
			iconCls:'icon-reload',
			handler:function(){
				$('#tt').tabs("getTab",title).panel('refresh', url);
				 
			}
			}]
		});
	}
} 

function loadUrl(title,url)
{
	$('#tt').tabs('add',{
			title:'New Tab',
			content:'Tab Body',
			closable:true 
			 
	});
	$("#content").load(url);
}
function loadAcc()
{
	$("#content").load("acc.html");
}
function loadServer()
{
	$("#content").load("server.html");
}
function loadTotal()
{
	$("#content").load("total.html");
}

 Date.prototype.formatDate = function (format) //author: meizz  
	{  
		var o = {  
			"M+": this.getMonth() + 1, //month  
			"d+": this.getDate(),    //day  
			"h+": this.getHours(),   //hour  
			"m+": this.getMinutes(), //minute  
			"s+": this.getSeconds(), //second  
			"q+": Math.floor((this.getMonth() + 3) / 3),  //quarter  
			"S": this.getMilliseconds() //millisecond  
		}  
		if (/(y+)/.test(format)) format = format.replace(RegExp.$1,  
		(this.getFullYear() + "").substr(4 - RegExp.$1.length));  
		for (var k in o) if (new RegExp("(" + k + ")").test(format))  
			format = format.replace(RegExp.$1,  
		  RegExp.$1.length == 1 ? o[k] :  
			("00" + o[k]).substr(("" + o[k]).length));  
		return format;  
	}  

function formatDateText(date) {  
				 
		return date.formatDate("yyyy-MM-dd"); 
	
}  
function formatDateTextt(date) {  
				 
		return date.formatDate("yyyy-MM-dd hh:mm:ss"); 
	
}  
function parseDate(dateStr) {  

	var regexDT = /(\d{4})-?(\d{2})?-?(\d{2})?\s?(\d{2})?:?(\d{2})?:?(\d{2})?/g;  
	var matchs = regexDT.exec(dateStr);  
	if(matchs==null)
		return new Date();
	var date = new Array();  
	for (var i = 1; i < matchs.length; i++) {  
		if (matchs[i]!=undefined) {  
			date[i] = matchs[i];  
		} else {  
			if (i<=3) {  
				date[i] = '01';  
			} else {  
				date[i] = '00';  
			}  
		}  
	}  
	return new Date(date[1], date[2]-1, date[3], date[4], date[5],date[6]);  
}  
indexToZoneArr = [1,4,
                57,67,
                82,93,
                102,114,
                130,144,
                149,164,
                150,167,
                190,209,
				201,221,
				231,255];
function indexToZone(idx)
        {
                for(i=0;i<indexToZoneArr.length/2;i++)
                {
                        if(indexToZoneArr[i*2] == idx)
                                return indexToZoneArr[i*2+1];
                        else if(indexToZoneArr[i*2] >idx)
                        {
                                return indexToZoneArr[(i-1)*2+1] +(idx - indexToZoneArr[(i-1)*2]);
                        }
                }

                return indexToZoneArr[(i-1)*2+1] +(idx - indexToZoneArr[(i-1)*2]);
        }
function zoneToIndex(zone)
        {
                for(i=0;i<indexToZoneArr.length/2;i++)
                {
                        if(indexToZoneArr[i*2+1] == zone)
                                return indexToZoneArr[i*2];
                        else if(indexToZoneArr[i*2+1] >zone)
                        {
                                return indexToZoneArr[(i-1)*2] +(zone - indexToZoneArr[(i-1)*2+1]);
                        }
                }

                return indexToZoneArr[(i-1)*2] +(zone - indexToZoneArr[(i-1)*2+1]);
        }
function formatZone(zone)
{
	zone = zoneToIndex(parseInt(zone)+3);
	return zone;
	if(zone< 60)
		return zone-3;
	else if(zone < 93)
		return zone-10;
	else if(zone <114)
		return zone-11;
	else if(zone <144)
		return zone-12;
	else if(zone <164)
		return zone-14;
	else if(zone <167)
	    return zone-15;
	else
		return zone-17; 
}
function formatZone2(zone)
{
    zone = zoneToIndex(parseInt(zone));
    return zone;
}
