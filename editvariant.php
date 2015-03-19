<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Edit Variant";
include("inc/header.php");

if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)){
	$_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
	
	$conn = Common::connect_db();
	
	if ((!empty($_GET['id']))) { // down here to excecute above code before hand.
		$item = $_GET['id'];
	} else if (!empty($_POST['id'])){
		$item = $_POST['id'];
	} else {
		header(Location::browse.php);
	}
	
	if(isset($_POST['confirm'])){
		
		$query2 = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID ='$item';";
		$result = mysqli_query($conn, $query2);
		$count= 1;
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$d = $row['VARIANT_ID'];
			$boxname = "desc".$d;
			$desc =$_POST[$boxname];
			$desc = Common::clean($desc, $conn);
			$boxname = "price".$d;
			$price =$_POST[$boxname];
			if(($price)< 0){
				$errorCatch[] = '-Please enter a Price above 0!';
			}else {
				$price = Common::clean($price, $conn);
			}
			$boxname = "img".$d;
			$boxname = "stock".$d;
			$stock =$_POST[$boxname];
			if(($stock) < 0){
				$errorCatch[] = '-Please enter a Stock above 0!';
			}else {
				$stock = Common::clean($stock, $conn);
			}
			$boxname = "delete".$d;
			$delete =$_POST[$boxname];
			
			if(isset($_POST["$boxname"])){
				$updateqry1="UPDATE ITEM_VARIANT SET IS_OBSELETE =1 WHERE ITEM_ID= $item AND VARIANT_ID=$count;";
				mysqli_query($conn, $updateqry1);
			}else{
				$updateqry2="UPDATE ITEM_VARIANT SET IS_OBSELETE =0 WHERE ITEM_ID= $item AND VARIANT_ID=$count;";
				mysqli_query($conn, $updateqry2);
			}
			
			if(!empty($errorCatch)){
				foreach($errorCatch as $msg){
					echo"$msg <br>";
				}
			} else {
				$updateqry3="UPDATE ITEM_VARIANT SET VARIANT_DESC = '$desc', PRICE = $price, ITEM_STOCK = $stock WHERE ITEM_ID= $item AND VARIANT_ID=$count;";
				mysqli_query($conn, $updateqry3);
				$boxname = "img".$d;
				require 'upload.php';
				
				if($uploadOk==1 && $target_file != 'images/'){
					$updateqry="UPDATE ITEM_VARIANT SET ITEM_IMG = '$target_file' WHERE ITEM_ID= $item AND VARIANT_ID=$count;";
					mysqli_query($conn, $updateqry);
				}
			}
			unset($errorCatch);
			$count++;	
		}
	}
	
	$query2 = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID ='$item';";
	$result2 = mysqli_query($conn, $query2);
	echo"<form name='adimAdd' action='editvariant.php' method='Post' enctype='multipart/form-data'>";
	$drawcount=1;
	
	while ($row = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
		$d = $row['VARIANT_ID'];
		$desc = $row['VARIANT_DESC'];
		$p = $row['PRICE'];
		$s = $row['ITEM_STOCK'];
		$del = $row['IS_OBSELETE'];
		
		$boxname= "desc".$d;
		echo"Variant Description <br>";
		echo "<input required type='text' name='$boxname' maxlength = '140' value ='$desc'><br>";
		$boxname = "price".$d;
		echo"Variant Price<br>";
		echo "<input required type='number' name='$boxname' step='.01' decimals='1' min='0'  maxlength = '10' value ='$p'><br>";
		$boxname = "fileToUpload".$drawcount;
		echo"Variant Image <br>";
		echo "<input type='file' name='$boxname' id='$boxname'><br>";
		$boxname = "stock".$d;
		echo"Variant Stock <br>";
		echo "<input required type='number' step='any' min='0'  name='$boxname' maxlength = '7' value ='$s'><br>";
		echo"Obselete";
		$boxname = "delete".$d;
		
		if($del==1){
			echo"<input type ='checkbox' name ='$boxname' checked >";
		}else{
			echo"<input type ='checkbox' name ='$boxname'>";
		}
		echo"<br>";
		$drawcount++;
	}
	echo"<input type='hidden' name='id' value='$item'><button type='submit' name = 'confirm'> Update Variants</button> </form>";
    } else {
        header('Location:login.php');
    }

	
?>