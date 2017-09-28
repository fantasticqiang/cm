<? header("content-Type: text/html; charset=UTF-8");?>
<?php
	include 'util.php';
	/*$result=file_get_contents('php://input', 'r');
	$tmp = explode("|", $result);
	$resp_xml = base64_decode($tmp[0]);
	$resp_sign = $tmp[1];
	if(verity(MD5($resp_xml,true),$resp_sign)){//验签
		echo "支付成功，请关闭页面！";
	}else{
		echo "支付失败，请重新支付！";
	}*/
	
	/******异步通知******/
	$result=file_get_contents('php://input', 'r');
	$tmp = explode("|", $result);
	$resp_xml = base64_decode($tmp[0]);
	$resp_sign = $tmp[1];
	file_put_contents('test3.txt',$resp_xml.PHP_EOL,FILE_APPEND);
	$chenggong = verity(MD5($resp_xml,true),$resp_sign);
	file_put_contents('test3.txt',$chenggong.PHP_EOL,FILE_APPEND);
	/*if(verity(MD5($resp_xml,true),$resp_sign)){//验签
		echo '<br/>响应结果<br/><textarea cols="120" rows="20">'.$resp_xml.'</textarea>';
	} else echo '验签失败';*/
?>