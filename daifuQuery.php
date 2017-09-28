<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>订单查询</title>
</head>

<body>

<?php
include 'util.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/

/**
 *获取请求xml需要的参数
 */
	//必填1，商户号
$merchantId = !empty($_POST["merchantId"])?$_POST["merchantId"]:"1000000";
	//必填2，商户订单号
$merchantOrderId = !empty($_POST["merchantOrderId"])?$_POST["merchantOrderId"] : "";
if($merchantOrderId == ""){
	die("订单号不能为空！");
}
//应用名称，交易查询
$application = "QueryOrder";
//

/**
 *拼接请求的xml
 */
$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?>
	<message application="'. $application .'" version="1.0.1" merchantId="'. $merchantId .'"
	merchantOrderId="'. $merchantOrderId .'"/>';

	
	/*****生成请求内容**开始*****/
$strMD5 =  MD5($str,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str);
$msg = $base64_src."|".$strsign;
/*****生成请求内容**结束*****/
$ch = curl_init();
$gateway_url =  $gateway_url;
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


?>


</form>

</body>
</html>