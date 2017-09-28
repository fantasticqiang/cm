<html>
<head>
	<meta charset="utf-8">
	<title>显示订单</title>
	<style>
		textarea{
			height:250px;
			width:300px;
		}
	</style>
</head>

<body style="text-align:center;">
<h2>从数据库中查出最新的10条数据</h2>
<table style="margin:0 auto;" border="1">
<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "test";
 
// 创建连接
$conn = new mysqli($servername, $username, $password,$dbname);
mysqli_set_charset($conn, 'utf8');
$sql = "select * from test order by Id desc limit 0,10";
$result = $conn->query($sql);

echo('<tr><td>ID</td><td style="width:500px;">内容</td></tr>');
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()){
		echo('<tr><td>'.$row["Id"].'</td><td><textarea style="width:99%;">'.$row["detail"].'</textarea></td></tr>');
	}
}

?>
</table>
</body>
</html>