<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>单笔代收付</title>
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
	//必填2，交易请求号
$tranId = !empty($_POST["tranId"])?$_POST["tranId"] : "";
if($tranId == ""){
		die("交易请求号不能为空！");
}
    //通知地址
$notifyUrl = $_POST["receivePayNotifyUrl"];
//卡号
$accNo = $_POST["accNo"];
//卡主人姓名
$accName = $_POST["accName"];
//金额
$amount = $_POST["amount"];
//证件类型
$credentialType = $_POST["credentialType"];
//证件号码
$credentialNo = $_POST["credentialNo"];
//手机号
$tel = $_POST["tel"];
//银行代码
$bankId = $_POST[""];
$timestamp = date("YmdHis");

/**
 *拼接请求的xml
 */
$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?>
<message accName="'.$accName.'" accNo="'.$accNo.'" accountProp="0"
	amount="'.$amount.'" application="ReceivePay" bankId="102100020794"
	receivePayNotifyUrl="http://47.94.193.202/cm/notify.php"
	credentialNo="230224195632121122" credentialType="01" receivePayType="1" drctBankId="102100099996"
	merchantId="'.$merchantId.'" summary="" tel="'.$tel.'" bankGeneralName="中国工商银行"
	timestamp="'.$timestamp.'" tranId="'.$tranId.'" version="1.0.1" />';

echo '<h2 style="text-align:center;">请求原文</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$str.'</textarea></div>';
	
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
	echo '<h2 style="text-align:center;">代付确认-响应结果</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$resp_xml.'</textarea></div>';
} else echo '验签失败';


?>


</form>

</body>
</html>