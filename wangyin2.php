<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>测试xml明文</title>
</head>

<body style="text-align:center;">
<h2>输入原文</h2>
<form id="form1" action="#" method="post">
	<table style="margin:0 auto;">
		<tr>
			<td>
				<textarea id="str" name="str" cols="120" rows="20"><?php
					if(!empty($_POST["str"])){
						echo str_replace(array('\\'),"",$_POST["str"]);
					}
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

$msg = "";
if(!empty($_POST["str"])){
	$str = str_replace(array('\\'),"",$_POST["str"]);
	$strMD5 =  MD5($str,true);	
	$strsign =  sign($strMD5);
	$base64_src=base64_encode($str);
	$msg = $base64_src."|".$strsign;
	
}

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

<h2>密文</h2>
<textarea id="str" name="str" cols="120" rows="20"><?php echo $msg ?></textarea>

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