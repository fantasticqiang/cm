<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>发送异步通知</title>
</head>

<body style="text-align:center;">

<?php
include 'util.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/


/**
 *接受到的消息
 */
$str =  !empty($_POST["msg"]) ? $_POST["msg"] : "123456";
$notifyUrl =  !empty($_POST["notifyUrl"]) ? $_POST["notifyUrl"] : "123456";

	
	/*****生成请求内容**开始*****/
$strMD5 =  MD5($str,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str);
$msg = $base64_src."|".$strsign;
/*****生成请求内容**结束*****/
$notifyUrl = !empty($_POST["notifyUrl"]) ? $_POST["notifyUrl"] : "";
if(!empty($notifyUrl)){
	echo("<br/>");
	echo("发送消息：".$msg."<br/>");
	echo("发送成功");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $notifyUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
}

?>

<h2>填写异步通知地址</h2>
<form action="#" method="post">
<table style="margin:0 auto;">
	<tr>
		<td>
			消息：<textarea name="msg" cols="120" rows="20" ><?php $str 
			?></textarea>
		</td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td>
			地址：<input name="notifyUrl" style="text" value="<?php if(!empty($notifyUrl)){echo($notifyUrl);}else{echo('http://47.94.193.202/cm/notify.php');}
			?>"/>
		</td>
	</tr>
	
	<tr>
		<td>
			<input type="submit" value="提交" />
		</td>
	</tr>
</table>
</form>


</body>
</html>