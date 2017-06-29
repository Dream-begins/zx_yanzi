<?php 
include_once "checklogin.php";
?>
	<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Q战神后台管理</title>
    <link rel="stylesheet" type="text/css" href="res/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="res/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="res/jquery.jqplot.min.css">
    <script type="text/javascript" src="res/jquery.min.js"></script>
    <script type="text/javascript" src="res/jquery.easyui.min.js"></script>
 
	

	<script type="text/javascript" src="res/admin.js"></script>
    </head>
    <body >
		<div><h2>补偿发放</h2></div>
		<div>
		<div>服务器: <textarea id="zones"></textarea> </div>
		<form  action="dogift.php" method="post"   id="giftform">
			<table>
				
				
				<tr><td>创建角色时间:</td><td><input type="hidden" id="zone" name="zone" /><input    style="width:150px" id="createtime" name="createtime">---<input    style="width:150px" id="createtime2" name="createtime2"></td></tr>
				<tr><td>最近登陆时间:</td><td><input  style="width:150px" name="lastactive" id="lastactive">---<input   style="width:150px" name="lastactive2" id="lastactive2"></td></tr>
				<tr><td>等级大于等于</td><td><input id="level" name="level"  /></td><td>等级小于</td><td><input id="maxlevel" name="maxlevel"  /></td></tr>	
			<tr><td>道具:</td><td><input type="text" class="easyui-validatebox" value="0"  name="objs1" id="gift_objs1"/>,<input type="text" class="easyui-validatebox" value="0" name="objs2" id="gift_objs2"/>,<input type="text" class="easyui-validatebox" value="0"  name="objs3" id="gift_objs3"/></td></tr>
				<tr><td>数量:</td><td><input type="text" class="easyui-validatebox" value="0" name="nums1" id="gift_nums1" />,<input type="text" class="easyui-validatebox" value="0" name="nums2" id="gift_nums2" />,<input type="text" class="easyui-validatebox"  value="0"  name="nums3" id="gift_nums3" /></td></tr>
				<tr><td>是否绑定:</td><td><input type="text" class="easyui-validatebox"  value="1"  name="binds1" id="gift_binds1"/>,<input type="text" class="easyui-validatebox" value="1" name="binds2" id="gift_binds2"/>,<input type="text" class="easyui-validatebox" value="1"  name="binds3" id="gift_binds3"/></td></tr>
				<tr><td>邮件标题:</td><td><input type="text" class="easyui-validatebox"  required="true" name="subject" id="subject"/></td></tr>
				<tr><td>邮件内容:</td><td><textarea name="content" class="easyui-validatebox"  required="true" id="content"></textarea><span id="inputContent2"></span></td></tr>
				<tr><td cols="2" align="center">
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm_gift09172()">提交</a> 
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="queryGift_gift09172()">查询</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearGift_gift09172()">清除</a>
				 </td></tr>
			</table>
		
		</form>
		<div id="status"></div>
		<div>

		<textarea id="log_gift09172"></textarea>
		</div>
		</div>
常用礼包:
  <table width="960px">
    <tr>
      <td wdith="50%">
        <fieldset>
            <legend>外部用</legend>
            <?php 
		$datas = array();
    include_once "newweb/h_header.php";
    $DB_HOST=FT_MYSQL_COMMON_HOST;
    $DB_USER=FT_MYSQL_COMMON_ROOT;
    $DB_PASS=FT_MYSQL_COMMON_PASS;
    $DB_NAME=FT_MYSQL_BILL_DBNAME;
    $con = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
mysql_query('set names utf8');
              mysql_select_db("admin", $con) or die("mysql select db error". mysql_error()); 
              $sql = "SELECT * FROM `ft_common_use_gift` where state ='2'";
              $result = mysql_query($sql) or die("Invalid query: " . mysql_error());
              while ($row = mysql_fetch_assoc($result)) 
              {
                  $datas[] = $row;
              }
              foreach ($datas as $key => $value) 
              {
                echo '<a id="gift_'.$key.'" href="javascript:void(0)" class="easyui-linkbutton" onclick="giftgift_'.$key.'()">'.$value['name'].'</a>';
              }

              echo "<script>";
              foreach ($datas as $key => $value) 
              {
                echo 'function giftgift_'.$key.'(){$("#gift_objs1").val("'.$value['item1_id'].'");$("#gift_nums1").val("'.$value['item1_num'].'");$("#gift_binds1").val("'.$value['item1_bind'].'");'.'$("#gift_objs2").val("'.$value['item2_id'].'");$("#gift_nums2").val("'.$value['item2_num'].'");$("#gift_binds2").val("'.$value['item2_bind'].'");'. '$("#gift_objs3").val("'.$value['item3_id'].'");$("#gift_nums3").val("'.$value['item3_num'].'");$("#gift_binds3").val("'.$value['item3_bind'].'");}';
              }     
              echo "</script>";

             ?>      
        </fieldset>
      </td>
      <td widht='50%'>
        <fieldset>
            <legend>内部用</legend>
            <?php 
              $result = null;
              $datas = array();
              $row = array();
              $sql = '';
              $sql = "SELECT * FROM `ft_common_use_gift` where state ='1'";
              $result = mysql_query($sql) or die("Invalid query: " . mysql_error());
              while ($row = mysql_fetch_assoc($result)) 
              {
                  $datas[] = $row;
              }
              foreach ($datas as $key => $value) 
              {
                echo '<a id="gift_'.$key.'" href="javascript:void(0)" class="easyui-linkbutton" onclick="giftgift2_'.$key.'()">'.$value['name'].'</a>';
              }

              echo "<script>";
              foreach ($datas as $key => $value) 
              {
                echo 'function giftgift2_'.$key.'(){$("#gift_objs1").val("'.$value['item1_id'].'");$("#gift_nums1").val("'.$value['item1_num'].'");$("#gift_binds1").val("'.$value['item1_bind'].'");'.'$("#gift_objs2").val("'.$value['item2_id'].'");$("#gift_nums2").val("'.$value['item2_num'].'");$("#gift_binds2").val("'.$value['item2_bind'].'");'. '$("#gift_objs3").val("'.$value['item3_id'].'");$("#gift_nums3").val("'.$value['item3_num'].'");$("#gift_binds3").val("'.$value['item3_bind'].'");}';
              }     
              echo "</script>";
           ?>
        </fieldset>
      </td>
    </tr>
  </table>    


 <script>
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
	 $(function(){
			  $.fn.datebox.defaults.formatter = function(date){
				var y = date.getFullYear();
				var m = date.getMonth()+1;
				var d = date.getDate();
				return y+"-"+m+'-'+d +" "+ date.getHours()+":"+date.getMinutes()+":00";
			}
			function formatDateText(date) {  
				 
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

			$("#createtime,#lastactive,#createtime2,#lastactive2").datetimebox({showSeconds:false,  
					formatter: formatDateText,  
					parser: parseDate  }
				);
			 

			$('#giftform').form({
				url:'dogift.php',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					alert(data);
				},
				error:function(){
					alert("ss");
				}
	});

	 });
	 function addlog_gift09172(msg)
	 {
		 $("#log_gift09172").val($("#log_gift09172").val()+"\n"+msg);
	 }
	 submiting=false;
	 var mergeserver=[2,3,5,6,7,9,10,12,13,15,17,18];
	 var servers=[];
	 var serverCnt;
	 function submitNext_gift09172()
	 {
		 if(servers.length == 0)
		 {
			 if(finish)
				 addlog_gift09172("发放完成");
			 return;
		 }
		 var zone = servers.shift();
		 if(zone == undefined && finish)
		 {
			 addlog_gift09172("发放完成!");
			 return;
		 }
 
			submiting = true;
			$("#zone").val(zone);
			addlog_gift09172("开始发放服务器:"+zone);
			$('#giftform').form('submit',{success:function(data){
					addlog_gift09172("发放服务器:"+zone+"成功\n"+data);
					 
					submiting = false;
					 submitCnt = submitCnt+1;
					showProgress_gift09172(submitCnt);
					submitNext_gift09172();
				},
				error:function(){
					addlog_gift09172("发放服务器:"+zone+"失败\n"+data);
					
					submiting = false;
					 submitCnt = submitCnt+1;
					 showProgress_gift09172(submitCnt);
					submitNext_gift09172();
				}});
	 }
	 function getServerList_gift09172()
	 {
			servers = [];
		   allzone = $("#zones").val();
		   allzones = allzone.split(",");
		   for( var i=0;i<allzones.length;i++)
		  {
			   zone1 = allzones[i];
			if(zone1.indexOf("-") >0)
			  {
				zones = zone1.split("-");
				from = parseInt(zones[0]);
				to = parseInt(zones[1]);
				for(var i=from;i<=to;i++)
				  {
					//if(mergeserver.indexOf(i)>=0)
					//	continue;
					servers.push(i);
				  }
			  }
			  else
				  servers.push(parseInt(zone1));
		  }
		   serverCnt = servers.length;
	 }
	 var finish=false;
	 function showProgress_gift09172(val)
	 {
		$("#status").html("执行进度:"+val+"/"+serverCnt);
		finish = val == servers.length;
	 }
	 var submitCnt = 0;

	  function submitForm_gift09172(){
		  //if(submiting)
			//  return;
		   submitCnt = 0;
		  getServerList_gift09172();
		  if(servers.length == 0)
		  {
			  alert("没有服务器需要发放");
			  return;
		  }
		 addlog_gift09172("server count:"+servers.length); 
		  for(var i = 0;i<5&i<serverCnt;i++)
			submitNext_gift09172();
		}
		function clearNextGift_gift09172()
		{
			var zone = servers.shift();
			 if(zone == undefined)
			 {
				 addlog_gift09172("清除完成!");
				 return;
			 }
			 addlog_gift09172("开始清除:"+zone); 
			$.ajax("cleargift.php?subject="+$("#subject").val()+"&zone="+zone,{success:function(data){
				addlog_gift09172("清除成功:"+data); 
				clearNextGift_gift09172();
			
			},
				error:function(){
				addlog_gift09172("清除失败:"+zone); 
				clearNextGift_gift09172();
			}
			});
		}
      function clearGift_gift09172(){
		   getServerList_gift09172();
		  if(servers.length == 0)
		  {
			  alert("没有服务器需要发放");
			  return;
		  }
		  for(var i = 0;i<5;i++)			
			clearNextGift_gift09172();
	  }
	  function queryNextGiftgift09172()
		{
			var zone = servers.shift();
			 if(zone == undefined)
			 {
				 addlog_gift09172("查询完成!");
				 return;
			 }
			
			$.ajax("querygift.php?subject="+$("#subject").val()+"&zone="+zone,{success:function(data){
				addlog_gift09172(zone+"区:"+data+"条"); 
				queryNextGiftgift09172();
			
			},
			error:function(){
				 
				queryNextGiftgift09172();
			}
			});
		}
      function queryGift_gift09172(){
		   getServerList_gift09172();
		  if(servers.length == 0)
		  {
			  alert("没有服务器需要发放");
			  return;
		  }
		  queryNextGiftgift09172();
	  }

	  
	 </script>
    </body>
    </html>
