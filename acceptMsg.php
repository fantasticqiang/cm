<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>接收自己发送的内容</title>
</head>

<body style="text-align:center;">

<?php


$msg = file_get_contents('php://input');
//存在文件里
file_put_contents('msg.txt',$msg.PHP_EOL,FILE_APPEND);

?>


</body>
</html>