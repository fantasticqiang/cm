<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>发送异步通知给自己</title>
</head>

<body style="text-align:center;">

<h2>填写异步通知地址</h2>
<form action="#" method="post">
<table style="margin:0 auto;">
	<tr>
		<td>
			消息：<input name="msg" style="text" value=""/>
		</td>
	</tr>
	<tr>
		<td>
			地址：<input name="notifyUrl" style="text" value="<?php if(!empty($notifyUrl)){echo($notifyUrl);}else{echo('http://47.94.193.202/cm/sendMsgToSelf.php');}
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

<?php
include 'util.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/

/**
 *接受到的post消息
 */
$str =  !empty($_POST["msg"]) ? $_POST["msg"] : "123456";
$notifyUrl = 'http://47.94.193.202/cm/acceptMsg.php';

if(!empty($notifyUrl)){
	echo("<br/>");
	echo("发送消息：".$str."<br/>");
	echo("发送成功");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $notifyUrl);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	//接收
}

?>


</body>
</html>