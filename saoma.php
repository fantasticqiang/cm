<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>扫码支付</title>
</head>

<body>

<?php
include 'util.php';
include 'phpqrcode.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/

/**
 *获取请求xml需要的参数
 */
	//必填1，应用名称
$application = !empty($_POST["application"])?$_POST["application"]:"WeiXinScanOrder";
	//必填2，时间戳
$timestamp = !empty($_POST["timestamp"])?$_POST["timestamp"] : date("YmdHis");
	//必填3，商户代码、商户号(从钱通平台下发)
$merchantId = $_POST["merchantId"];
	//必填4，商户订单号（值唯一）
$merchantOrderId = !empty($_POST["merchantOrderId"]) ? $_POST["merchantOrderId"] : time();
	//必填5，订单金额
$merchantOrderAmt = !empty($_POST["merchantOrderAmt"]) ? $_POST["merchantOrderAmt"] : "200";
	//必填6，订单描述
$merchantOrderDesc = !empty($_POST["merchantOrderDesc"]) ? $_POST["merchantOrderDesc"] : "小米4";
	//必填7，用户名
$userName  = !empty($_POST["userName"]) ? $_POST["userName"] : "Jim";
	//必填8，异步通知地址
$merchantPayNotifyUrl = !empty($_POST["merchantPayNotifyUrl"]) ? $_POST["merchantPayNotifyUrl"] : "http://localhost/notify.php";


/**
 *拼接请求的xml
 */
$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?>
	<message application="' .$application. '" version="1.0.1"
	timestamp="' .$timestamp. '"
	merchantId="' .$merchantId. '"
	merchantOrderId="' .$merchantOrderId. '"
	merchantOrderAmt="' .$merchantOrderAmt. '"
	merchantOrderDesc="' .$merchantOrderDesc. '"
	userName="' .$userName. '"
	payerId=""
	salerId=""
	guaranteeAmt="0"
	merchantPayNotifyUrl="' .$merchantPayNotifyUrl. '"/>';

	echo '<h2 style="text-align:center;">原文示例</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$str.'</textarea></div>';
	error_log("".$str."", 3, "logs/1.log");
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
echo($codeUrl2 ."fff</br>");
if(!empty($codeUrl)){
	QRcode::png($codeUrl,'qr.png',0,5,2); 
}


$applicationName = "未知应用";
if($application == 'WeiXinScanOrder'){
	$applicationName = "微信";
}else {
	$applicationName = "支付宝";
}
echo '<h2 style="text-align:center;">'.$applicationName.'支付二维码</h2>'

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