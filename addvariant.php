<?php
session_start();
$feedback = $_SESSION["check"]; // Note from bradley - Could this "Check" be changed into something less general, could be "v_added" = true, etc.
if ($feedback==true){
echo "Variant has been added";
}

$item_id = $_SESSION["itemID"];
	$uid = "i7214451"; 
	$pwd = "Password"; 
	$host = "127.0.0.1";
	$db = $uid;
	$conn = mysqli_connect($host, $uid, $pwd, $db);
echo $item_id;
	$variantDesc =$_POST['variantDesc'];
	$price =$_POST['price'];
	$stock = $_POST['stock'];

$query = "SELECT * FROM ITEM WHERE ITEM_ID = $item_id;";
$result = mysqli_query($conn, $query);
	if($result){
		$row = mysqli_fetch_row($result);
	
		$itemName = $row[1];
		$itemDesc =  $row[2];
		
	}else{
		echo"error couldn't get the details of this item ";
	}
	if (isset($_POST["variantConfirm"])){
	$query4 = "SELECT MAX(VARIANT_ID) FROM ITEM_VARIANT WHERE ITEM_ID = ($item_id);"; 
	$res4 = mysqli_query($conn, $query4);
	$row= mysqli_fetch_row($res4);
	$var_Id =$row[0]+1;
//print values of item (non-editable)
$query2 = "INSERT INTO ITEM_VARIANT(VARIANT_ID,ITEM_ID,VARIANT_DESC, PRICE, ITEM_STOCK) 
		VALUES ('$var_Id','$item_id','$variantDesc', '$price','$stock');";
		$result = mysqli_query($conn, $query2);
		if ($result){
			$_SESSION["check"]=true;
			header('Location:addvariant.php');
		}else {
			$_SESSION["check"]=false;
			echo "Error";
		}
//form of variant

//onCommit if cbox ticked do another.

mysqli_close($conn);

}
?>
<!DOCTYPE html>	
<html>
	<form action="addvariant.php" method="Post">
				
				Item name: <?php echo $itemName; 
				?><br>	
				<br>
				Item Description: <?php echo $itemDesc; 
				?><br>
				<p></p>
			</form>
	<form id="vars" action="addvariant.php" method="Post">
	Variant*:<br>
	<input type="text" name="variantDesc" value="<?php
			if(isset($_POST['variantDesc'])){
			echo $_POST['variantDesc'];
			}
		?>"><br>
		
	Price*:<br>
	<input type="text" name="price"value = "<?php
			if(isset($_POST['price'])){
			echo $_POST['price'];
			}
		?>"><br>
	
	Initial variant Stock*:<br>
	<input type="text" name="stock"value = "<?php
			if(isset($_POST['stock'])){
			echo $_POST['stock'];
			}
		?>"><br> 
	
	<p></p>
	Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
	<p></p>
	<button type="submit" name = "variantConfirm"> Add Variant</button>
		</form>
</html>