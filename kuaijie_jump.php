<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>快捷支付-跳转方式</title>
</head>

<body>

<?php
include 'util.php';
/******************* 页面跳转下单，直连网银（快捷H5下单，微信WAP参考此文档） *****************/


//必填1，商户代码，商户号
$merchantId = !empty($_POST["merchantId"])?$_POST["merchantId"]:"1000000";
//必填2，商户订单号
$merchantOrderId = !empty($_POST["merchantOrderId"])?$_POST["merchantOrderId"]:"";
if($merchantOrderId == ""){
	die("商户订单号不能为空！");
}
//必填3，商品价格（单位是分,1000分，也就是10元）
$merchantOrderAmt = !empty($_POST["merchantOrderAmt"])?$_POST["merchantOrderAmt"]:"1000";
//必填4，订单描述
$merchantOrderDesc = !empty($_POST["merchantOrderDesc"])?$_POST["merchantOrderDesc"]:"小米4";
//必填5，通知地址
$notifyUrl = "https://127.0.0.1:8443/pay-interface/notify.jsp";
//必填6，买家ID
$payerId = !empty($_POST["payerId"])?$_POST["payerId"]:"500226";


$str = '<?xml version="1.0" encoding="utf-8" standalone="no"?>
<message application="CertPayOrderH5" guaranteeAmt="0"
	merchantFrontEndUrl="https://127.0.0.1:8443/pay-interface/order_request.jsp"
	merchantId="'.$merchantId.'" merchantName=""
	merchantOrderAmt="100" merchantOrderDesc="'.$merchantOrderDesc.'" merchantOrderId="'.$merchantOrderId.'"
	merchantPayNotifyUrl="'.$notifyUrl.'"
	payerId="'.$payerId.'" salerId="" version="1.0.1" />';
	
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

<h2 style="text-align:center;">快捷支付-跳转方式</h2>

<form action="<?php echo $gateway_url ?>" method="post">
<div style="text-align:center;">
	<table style="margin:0 auto;">
		<tr>
			<td>快捷支付-订单确认</td>	
		</tr>
		<tr>
			<td>
				<input type="hidden" name="msg" value="<?php echo $msg ?>"/>
			</td>
			<td>
				<input type="submit" value="确定"/>
			</td>
		</tr>
	</table>
</div>
</form>

</body>
</html>