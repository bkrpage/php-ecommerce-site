<?php
session_start();
	$uid = "i7214451"; 
	$pwd = "Password"; 
	$host = "127.0.0.1";
	$db = $uid;
	$conn = mysqli_connect($host, $uid, $pwd, $db);
	if(isset($_POST["confirm"])){
	// this is where get all the general user details and display them to him
	
	$itemName =$_POST['itemName'];
	$itemDesc =$_POST['itemDesc'];
	$variantDesc =$_POST['variantDesc'];
	$price =$_POST['price'];
	$stock = $_POST['stock'];
	$tags = $_POST['tags'];
	
	$item_Id = "SELECT MAX(ITEM_ID)FROM ITEM";
	$r = mysqli_query($conn,$item_Id);
	$row = mysqli_fetch_row($r);
	
	$id = $row[0]+1;
	
	
	$arr=explode(" ",$tags);
	$query4 = "SELECT MAX(VARIANT_ID) FROM ITEM_VARIANT WHERE ITEM_ID = ($id);"; 
	$res4 = mysqli_query($conn, $query4);
	$row= mysqli_fetch_row($res4);
	$var_Id =$row[0]+1;
	echo "$var_Id";
	echo "$id";
	echo "$variantDesc";
	echo "$price";
	echo "$stock";
	$query1 = "INSERT INTO ITEM(ITEM_ID,ITEM_NAME, ITEM_DESC) 
		VALUES ('$id','$itemName', '$itemDesc');";
	$result1 = mysqli_query($conn, $query1);
	require 'upload.php';
	$query2 = "INSERT INTO ITEM_VARIANT(VARIANT_ID,ITEM_ID,VARIANT_DESC, PRICE, ITEM_STOCK, ITEM_IMG) 
		VALUES ('$var_Id','$id','$variantDesc', '$price','$stock','$target_file');";
	$result2 = mysqli_query($conn, $query2);
	$res4 = mysqli_query($conn, $query4);
	$row= mysqli_fetch_row($res4);


foreach ($arr as $temp_tag){
	$query3 = "INSERT INTO TAG(ITEM_ID , TAG) VALUES($id,'$temp_tag');";
	$result3 = mysqli_query($conn, $query3);
}
if(isset($_POST["moreVariants"])){
	//set session var
	$_SESSION["morevars"]=true;
	$_SESSION["itemID"]=$id;
	$_SESSION["check"]=false;
	header('Location:addvariant.php');
}
	
}
?>
<html>
	<form name="adimAdd" action="admin.php"  method="Post" enctype="multipart/form-data">
	Item name*:<br>
	<input type="text" name="itemName"value ="<?php
	if(isset($_POST['itemName'])){
	echo $_POST['itemName'];
	}
	?>"><br>
	
	Description*:<br>
	<input type="text" name="itemDesc"value = "<?php
	if(isset($_POST['itemDesc'])){
	echo $_POST['itemDesc'];
	}
	?>"><br>
	
	Tags* (Separate each tag with a space):<br>
	<input type="text" name="tags"value = "<?php
	if(isset($_POST['tags'])){
	echo $_POST['tags'];
	}
	?>"><br> 
	
	Variant*:<br>
	<input type="text" name="variantDesc" value="<?php
			if(isset($_POST['variantDesc'])){
			echo $_POST['variantDesc'];
			}
		?>">
		
		<p></p>
		Add more Variants?
		<input type="checkbox" name="moreVariants" value=>
		
	<p></p>
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
	<button type="submit" name = "confirm"> Add Product</button>

    </form>
</html>