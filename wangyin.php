<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>在线充值</title>
</head>

<body>

<?php
include 'util.php';
/******************* 页面跳转下单，直连网银（快捷H5下单，微信WAP参考此文档） *****************/

$productname = !empty($_POST["merchantName"])?$_POST["merchantName"]:"iphone7";//非必填

//必填1，商户代码，商户号
$merchantId = $_POST["merchantId"];
//必填2，商户订单号
$merchantOrderId = date("YmdHis");
//必填3，商品价格（单位是分,1000分，也就是10元）
$price = !empty($_POST["merchantOrderAmt"])?$_POST["merchantOrderAmt"]:"1000";
//必填4，通知地址
$notifyUrl = !empty($_POST["merchantPayNotifyUrl"])?$_POST["merchantPayNotifyUrl"]:"https://127.0.0.1:8443/pay-interface/notify.jsp";
//必填r，订单时间
$orderTime = date("YmdHis");



$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?>
	<message accountType="0" application="SubmitOrder" bankId="" bizType="" credentialNo="" credentialType="" guaranteeAmt="0" 
	merchantFrontEndUrl="https://127.0.0.1:8443/pay-interface/order_request.jsp"
	merchantId="'.$merchantId.'" merchantOrderAmt="' .$price. '" merchantOrderDesc="环球地理" merchantOrderId="'.$merchantOrderId.'" 
	merchantPayNotifyUrl="'.$notifyUrl.'" msgExt="" orderTime="'.$orderTime.'" payMode="0" 
	payerId="" rptType="1" salerId="" userMobileNo="13333333333" userName="" userType="1" version="1.0.1"/>';	
	
	echo '<h2 style="text-align:center;">原文</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$str.'</textarea></div>';
	
	
/*****生成请求内容**开始*****/
$strMD5 =  MD5($str,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str);
$msg = $base64_src."|".$strsign;
	echo '<h2 style="text-align:center;">加密串</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$msg.'</textarea></div>';
/*****生成请求内容**结束*****/
/**
$def_url =  '<div style="text-align:center">';
$def_url .= '<body onLoad="//document.ipspay.submit();">网银订单确认';
$def_url .= '<form name="ipspay" action="'.$gateway_url.'" method="post">';
$def_url .=	'<input name="msg" type="hidden" value="'.$msg.'" /><input type="submit" value="提交"/>';
$def_url .=	'</form></div>';
echo $def_url;
**/
?>

<h2 style="text-align:center;">网银支付</h2>

<form action="<?php echo $gateway_url ?>" method="post">
<input type="hidden" name="msg" value="<?php echo $msg ?>"/>
<div style="text-align:center;">
	<table style="margin:0 auto;">
		<tr>
			<td>网银订单确认</td>	
		</tr>
		<tr>
			<td>
				<input type="submit" value="提交订单"/>
			</td>
		</tr>
	</table>
</div>
</form>

</body>
</html>