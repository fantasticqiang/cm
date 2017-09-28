<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>扫码支付测试</title>
</head>

<body>



<h2 style="text-align:center;">原文</h2>
<form id="form1" action="#" method="post">
	<table style="margin:0 auto;">
		<tr>
			<td>
				<textarea id="str" name="str" cols="120" rows="20"><?php if(!empty($_POST["str"])){echo str_replace(array('\\'),"",$_POST["str"]);}
				?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" value="提交"/>
			</td>
		</tr>
	</table>
</form>

<?php
include 'util.php';
include 'phpqrcode.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/


$msg = "";

//判断变量是否存在

if(!empty($_POST["str"])){
	$str = str_replace(array('\\'),"",$_POST["str"]);
	$strMD5 =  MD5($str,true);	
	$strsign =  sign($strMD5);
	$base64_src=base64_encode($str);
	$msg = $base64_src."|".$strsign;
}else{
	exit;
}

/*****生成请求内容**结束*****/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $gateway_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$tmp = explode("|", $result);
$resp_xml = base64_decode($tmp[0]);
$resp_sign = $tmp[1];
if(verity(MD5($resp_xml,true),$resp_sign)){//验签
	echo '<h2 style="text-align:center;">响应结果</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$resp_xml.'</textarea></div>';
} else echo '验签失败';

//解析xml
$xmlDoc = new DOMDocument();
$xmlDoc -> loadXML($resp_xml);
$node = $xmlDoc -> getElementsByTagName("message")->item(0);
$codeUrl = $node -> getAttribute("codeUrl");
//2.解析xml
$xmlDoc2 = simplexml_load_string($resp_xml);
$codeUrl2 = $xmlDoc2 -> codeUrl;
echo 'codeUrl:  '.$codeUrl;
if(!empty($codeUrl)){
	QRcode::png($codeUrl,'qr.png',0,5,2); 
}



echo '<h2 style="text-align:center;">支付二维码</h2>'

?>


<form action="<?php echo $gateway_url ?>" method="post">
<div style="text-align:center;">
	<table style="margin:0 auto;">
		<tr>
			<td>
				<img src="qr.png" alt="二维码"/>
			</td>
		</tr>
	</table>
</div>
</form>

</body>
</html>