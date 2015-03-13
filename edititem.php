<?php
$uid = "i7214451"; 
$pwd = "Password"; 
$host = "127.0.0.1";
$db = $uid;
$item_id=13;
$conn = mysqli_connect($host, $uid, $pwd, $db);

if (isset($_POST["confirm"])){
	$name = $_POST['itemName'];
	$desc = $_POST['itemDesc'];
	$tags = $_POST['itemTags'];
	$updateqry = "UPDATE ITEM SET ITEM_NAME='$name', ITEM_DESC='$desc' WHERE ITEM_ID = '$item_id';";
	mysqli_query($conn, $updateqry);
	
	$arr = Array();
	$arr=explode(" ",$tags);

	foreach ($arr as $temp_tag){
		$updateqry2 ="INSERT INTO TAG(ITEM_ID, TAG) VALUES($item_id,'$temp_tag');";
		mysqli_query($conn, $updateqry2);
	}
}

$query = "SELECT * FROM ITEM WHERE ITEM_ID = $item_id;";
$result = mysqli_query($conn, $query);
$query2 = "SELECT DISTINCT TAG FROM TAG WHERE ITEM_ID = '$item_id';";	
$result2 = mysqli_query($conn, $query2);
$tag_string="";
$storeArray = Array();

while ($row = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
	$storeArray[] =  $row['TAG'];  
}
foreach($storeArray as $n){
	$tag_string=$tag_string." ".$n;
	
}
$tag_string = trim($tag_string);

echo"<form name='adimAdd' action='edititem.php'  method='Post' enctype='multipart/form-data'>";
while ($result = mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$n = $result['ITEM_NAME'];
	$d = $result['ITEM_DESC'];
	echo"<input type='text' name='itemName'value ='$n'><br>";
	echo"<input type='text' name='itemDesc'value ='$d'><br>";
	echo"<input type='text' name='itemTags'value ='$tag_string'><br>";
}
echo "<button type='submit' name = 'confirm'> Add Product</button>";
?>