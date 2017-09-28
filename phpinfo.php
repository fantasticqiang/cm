<!DOCTYPE html>
<html>
<body>

<form action="https://123.56.119.177:8443/pay/pay.htm" method='post' >
First name:<br>
<input type="hidden" name="msg" value="$msg"/>
<input type="text" name="name">
<br>
Last name:<br>
<input type="text" name="password">

<input type="submit" value="submit" name="submit"/>
</form>

<p>Note that the form itself is not visible.</p>

<p>Also note that the default width of a text field is 20 characters.</p>

</body>
</html>


<?php
include 'util.php';
 //phpinfo();
 //echo 'hello php';
header("Content-type: text/html; charset=utf-8"); 
//var_dump($_GET);die;
//数据接收
	/*$name = $_POST['name'];
	$password = $_POST['password'];
	//字符串拼接
	$newxml = '<?xml version="1.0" encoding="utf-8"?>
    <note>
	<name>'.$name.'</name>
	<password>'.$password.'</password>
	<note>';
	//写入文件
	file_put_contents('./auto_p.xml',$newxml);*/
	/*****生成请求内容**开始*****/
	$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?><message application="CertPayOrder" cardNo="6217000010074078454" 
	credentialNo="230224198006132015" credentialType="01" cvv2="" guaranteeAmt="0" merchantFrontEndUrl="https://127.0.0.1:8443/pay-interface/order_request.jsp" merchantId="1001548" 
	merchantOrderAmt="200" merchantOrderDesc="环球地理" merchantOrderId="'.date("YmdHis").'" merchantPayNotifyUrl="http://169.254.225.73/notify.php" 
	payerId="" salerId="" userMobileNo="13120033858" userName="张三" validPeriod="" version="1.0.1"/>';	
	
	$str_wangYin = '<?xml version="1.0" encoding="utf-8" standalone="no"?><message accountType="0" application="SubmitOrder" bankId="" bizType="" credentialNo="" credentialType="" guaranteeAmt="0" merchantFrontEndUrl="https://127.0.0.1:8443/pay-interface/order_request.jsp" merchantId="1001714" merchantOrderAmt="1" merchantOrderDesc="环球地理" merchantOrderId="'.date("YmdHis").'" merchantPayNotifyUrl="http://169.254.225.73/notify.php" msgExt="" orderTime="20170504092105" payMode="0" payerId="" rptType="1" salerId="" userMobileNo="13333333333" userName="" userType="1" version="1.0.1"/>';
	
$strMD5 =  MD5($str_wangYin,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str_wangYin);
$msg = $base64_src."|".$strsign;
echo $msg;
/*****生成请求内容**结束*****/
/*****生成请求内容**结束*****/
$def_url =  '<div style="text-align:center">';
$def_url .= '<body onLoad="//document.ipspay.submit();">网银订单确认';
$def_url .= '<form name="ipspay" action="'.$gateway_url.'" method="post">';
$def_url .=	'<input name="msg" type="hidden" value="'.$msg.'" /><input type="submit" value="提交"/>';
$def_url .=	'</form></div>';
echo $def_url;

echo htmlspecialchars($_SERVER["PHP_SELF"]);
 
?>