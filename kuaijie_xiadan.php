<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>接口快捷</title>
</head>

<body>

<?php
include 'util.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/

/**
 *获取请求xml需要的参数
 */
	//必填1，应用名称
$application = "CertPayOrder";
	//必填2，商户代码（商户号）
$merchantId = !empty($_POST["merchantId"])?$_POST["merchantId"]:"1000000";
	//必填3，商户订单号
$merchantOrderId = !empty($_POST["merchantOrderId"])?$_POST["merchantOrderId"] : "";
if($merchantOrderId == ""){
	die("订单号不能为空！");
}
	//必填4，订单金额（单位是分）
$merchantOrderAmt = !empty($_POST["merchantOrderAmt"]) ? $_POST["merchantOrderAmt"] :"200";
	//必填5，订单描述
$merchantOrderDesc = !empty($_POST["merchantOrderDesc"]) ? $_POST["merchantOrderDesc"] :"小米4";
	//必填6，手机号
$userMobileNo = !empty($_POST["userMobileNo"]) ? $_POST["userMobileNo"] :"13120033859";
	//必填7，姓名（身份证上姓名）
$userName = !empty($_POST["userName"]) ? $_POST["userName"] :"张志国";
	//必填8，证件类型（身份证）
$credentialType = !empty($_POST["credentialType"]) ? $_POST["credentialType"] :"01";
	//必填9，证件编号
$credentialNo = !empty($_POST["credentialNo"]) ? $_POST["credentialNo"] :"230224198006132014";
	//必填9，银行卡号
$cardNo = !empty($_POST["cardNo"]) ? $_POST["cardNo"] :"6217000010074078455";
	//必填10，异步通知地址
$merchantPayNotifyUrl = !empty($_POST["merchantPayNotifyUrl"]) ? $_POST["merchantPayNotifyUrl"] :"http://localhost/notify.php";

//应用名称，交易查询
$application = "CertPayOrder";
//

/**
 *拼接请求的xml
 */
$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?>
<message application="'.$application.'" version="1.0.1"
	merchantId="'.$merchantId.'"
	merchantOrderId="'.$merchantOrderId.'"
	merchantOrderAmt="'.$merchantOrderAmt.'"
	merchantOrderDesc="'.$merchantOrderDesc.'"
	userMobileNo="'.$userMobileNo.'"
	payerId=""
	userName="'.$userName.'"
	salerId=""
	guaranteeAmt="0"
	credentialType="01"
	credentialNo="'.$credentialNo.'"
	merchantPayNotifyUrl="'.$merchantPayNotifyUrl.'"
	merchantFrontEndUrl="https://URL:PORT/pay-interface/order_request.jsp"
	cardNo="'.$cardNo.'"
	cvv2=""
	validPeriod=""/>';
	
	echo '<h2 style="text-align:center;">原文</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$str.'</textarea></div>';

	
	/*****生成请求内容**开始*****/
$strMD5 =  MD5($str,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str);
$msg = $base64_src."|".$strsign;
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
	echo '<h2 style="text-align:center;">快捷下单_响应结果</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$resp_xml.'</textarea></div>';
} else echo '验签失败';

//解析xml
$xmlDoc = new DOMDocument();
$xmlDoc -> loadXML($resp_xml);
$node = $xmlDoc -> getElementsByTagName("message")->item(0);
$merchantOrderId = $node -> getAttribute("merchantOrderId");

?>

<form action="kuaijie_queRen.php" method="post">
<h2 style="text-align:center;">快捷确认</h2>
	<table style="margin:0 auto;margin-top:20px;">
		<tr>
			<td>商户号：</td>
			<td>
				<input type="text" name="merchantId" value="1000000"/>
			</td>
		</tr>
		<tr>
			<td>订单号：</td>
			<td>
				<input type="text" name="merchantOrderId" value="<?php echo $merchantOrderId?>"/>
			</td>
		</tr>
		<tr>
			<td>短信验证码：</td>
			<td>
				<input type="text" value=""/>（默认值：“123456”）
			</td>
		</tr>
		<tr>
			<td colspan="2" >
				<input style="margin: 0 auto;"  type="submit" value="确定"/>
			</td>
		</tr>
	</table>
</form>

</body>
</html>