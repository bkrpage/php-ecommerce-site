<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
$conn = Common::connect_db();

if (isset($_POST["confirm"])){
	$name = $_POST['itemName'];
	$name = Common::clean($name, $conn);
	$desc = $_POST['itemDesc'];
	$desc = Common::clean($desc, $conn);
	$tags = $_POST['itemTags'];
	$tags = Common::clean($tags, $conn);
	$updateqry = "UPDATE ITEM SET ITEM_NAME='$name', ITEM_DESC='$desc' WHERE ITEM_ID = '$item_id';";
	mysqli_query($conn, $updateqry);
	
	$name = clean ($name, $conn);
	$desc = clean ($desc, $conn);
	$tags = clean ($tags, $conn);
	
	$arr = Array();
	$arr=explode(" ",$tags);
	
	foreach ($arr as $temp_tag){
		$updateqry2 ="INSERT INTO TAG(ITEM_ID, TAG) VALUES($item_id,'$temp_tag');";
		mysqli_query($conn, $updateqry2);
	}
	if(isset($_POST["deletebox"])){	
		$updateqry="UPDATE ITEM SET IS_OBSELETE =1 WHERE ITEM_ID= $item_id;";
		mysqli_query($conn, $updateqry);
	}else{
		$updateqry="UPDATE ITEM SET IS_OBSELETE =0 WHERE ITEM_ID= $item_id;";
		mysqli_query($conn, $updateqry);
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
	$del = $result['IS_OBSELETE'];
	echo"<input required type='text' name='itemName' maxlength = '30' value ='$n'><br>";
	echo"<input required type='text' name='itemDesc' maxlength = '140' value ='$d'><br>";
	echo"<input required type='text' name='itemTags' maxlength = '200' value ='$tag_string'><br>";
	echo"Obselete";
			if($del==1){
				echo"<input type ='checkbox' name ='deletebox'  checked >";
			}else{
				echo"<input type ='checkbox' name ='deletebox'>";
			}
}
echo "<button type='submit' name = 'confirm'> Add Product</button>";


	function clean($info, $conn){
					$info = trim($info);
					$info = strip_Tags($info);
					$info = mysqli_real_escape_string($conn, $info);
					return $info;
				}

?>