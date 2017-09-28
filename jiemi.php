<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>解密</title>
<script type="text/javascript" src="./js/jquery-1.8.0.js"></script>
</head>

<body style="text-align:center;">
<div style="margin:0 auto;">
<h2>输入加密串</h2>
<form id="form1" action="#" method="post">
	<table style="margin:0 auto;">
		<tr>
			<td>
				<textarea id="str" name="str" cols="120" rows="20"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" value="提交"/>
			</td>
		</tr>
	</table>
</form>

</div>
<?php
include 'util.php';

if(!empty($_POST["str"])){
	$msg = $_POST["str"];
	$tmp = explode("|", $msg);
	$resp_xml = base64_decode($tmp[0]);
	echo '<h2 style="text-align:center;">响应结果</h2><div style="text-align:center;"><textarea style="margin:0 auto;" cols="120" rows="20">'.$resp_xml.'</textarea></div>';
	
	$resp_sign = $tmp[1];
	if(!empty($resp_sign)){
		if(verity(MD5($resp_xml,true),$resp_sign)){//验签
			echo '<br/>';
			echo '验签成功';
		} else echo '验签失败';
	}
	
}
?>

<script>
	$(function(){
		$("#str").val(<?php $msg?>);
	});
</script>

</body>
</html>