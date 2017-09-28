<?php
include 'util.php';
header("Content-type: text/html; charset=utf-8"); 

/******异步通知******/
$result=file_get_contents('php://input');
$tmp = explode("|", $result);
$resp_xml = base64_decode($tmp[0]);
$file = fopen("test.txt",'w');
fwrite($file,$resp_xml);
fclose($file);
file_put_contents('test2.txt',$result.PHP_EOL,FILE_APPEND);
//$tmp = explode("|", $result);
//$resp_xml = base64_decode($tmp[0]);
//$resp_sign = $tmp[1];
//if(verity(MD5($resp_xml,true),$resp_sign)){//验签
if(false){
	echo '<br/>响应结果<br/><textarea cols="120" rows="20">'.$resp_xml.'</textarea>';
} else {
	echo '验签失败';
}

/**存储到mysql*/
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "test";
 
// 创建连接
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_set_charset($conn, 'utf8');
 
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
echo "连接成功";
$sql = "INSERT INTO test (Id, detail) VALUES (null,'".$resp_xml."')";
if ($conn->query($sql) === TRUE) {
    echo "新记录插入成功";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>