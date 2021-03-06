<?php
/**
@author Rowan Trodd
@author Reece Tucker
**/
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Admin Control Panel";
include("inc/header.php");

if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)){
        $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.

		$conn = Common::connect_db();
		
		if(isset($_POST["confirm"])){
		//if the form has data entered, post the values and update
		
			$itemName =$_POST['itemName'];
			$itemDesc =$_POST['itemDesc'];
			$variantDesc =$_POST['variantDesc'];
			$stock = $_POST['stock'];
			$tags = $_POST['tags'];
			$count = 1;
			$price =$_POST['price'];
		
			if(($price)< 0){ //if the price is negative, flag an error for the error trap
				$errorCatch[] = '-Please enter a Price above 0!';
			}else {
				$price = Common::clean($price, $conn);
			}
			
			if(($stock) < 0){//if the stock is negative, flag an error
				$errorCatch[] = '-Please enter a Stock above 0!';
			}else {
				$stock = Common::clean($stock, $conn);
			}
			//sanitizing the data before SQL entry
			$itemName = Common::clean($itemName, $conn);
			$itemDesc = Common::clean($itemDesc, $conn);
			$variantDesc = Common::clean($variantDesc, $conn);
			$stock = Common::clean($stock, $conn);
			$tags = Common::clean($tags, $conn);
	
			$item_Id = "SELECT MAX(ITEM_ID)FROM ITEM";
			$r = mysqli_query($conn,$item_Id);
			$row = mysqli_fetch_row($r);
		
			$id = $row[0]+1;//increment to the next available id
		
			if(!empty($errorCatch)){//if errors are thrown, prevent SQL update and print error(s)
				echo'Error:';
				foreach($errorCatch as $msg){
					echo"<br> $msg ";
				}
			} else {
					//if no errors are found, perform item creation
				$arr=explode(" ",$tags);
				$query4 = "SELECT MAX(VARIANT_ID) FROM ITEM_VARIANT WHERE ITEM_ID = ($id);"; 
				$res4 = mysqli_query($conn, $query4);
				$row= mysqli_fetch_row($res4);
				$var_Id =$row[0]+1;
				
				//create new item
				$query1 = "INSERT INTO ITEM(ITEM_ID,ITEM_NAME, ITEM_DESC) VALUES ('$id','$itemName', '$itemDesc');";
				$result1 = mysqli_query($conn, $query1);
				
				//create 1st variant of new item
				require 'upload.php';
				$query2 = "INSERT INTO ITEM_VARIANT(VARIANT_ID,ITEM_ID,VARIANT_DESC, PRICE, ITEM_STOCK, ITEM_IMG) VALUES ('$var_Id','$id','$variantDesc', '$price','$stock','$target_file');";
				$result2 = mysqli_query($conn, $query2);
				
				$res4 = mysqli_query($conn, $query4);
				$row= mysqli_fetch_row($res4);
				
				//implement all tags into the tag database
				foreach ($arr as $temp_tag){
					$query3 = "INSERT INTO TAG(ITEM_ID , TAG) VALUES($id,'$temp_tag');";
					$result3 = mysqli_query($conn, $query3);
				}
				
				if(isset($_POST["moreVariants"])){
					//set session var to pass the item id to create more variants of the same item
					$_SESSION["morevars"]=true;
					$_SESSION["itemID"]=$id;
					$_SESSION["check"]=false;
					header('Location:addvariant.php');
				}
			}
		}
	}else{
		header('Location:login.php'); //if not logged in as admin, you are sent to login screen
	}
?>
	<div class="body-box">
	<form name="adimAdd" action="additem.php"  method="Post" enctype="multipart/form-data">
	Item name*:<br>
	<input required type="text" name="itemName" maxlength = "40" value ="<?php
	if(isset($_POST['itemName'])){
	echo $_POST['itemName'];
	}
	?>"><br>
	
	Description*:<br>
	<input required type="text" name="itemDesc" maxlength = "140" value = "<?php
	if(isset($_POST['itemDesc'])){
	echo $_POST['itemDesc'];
	}
	?>"><br>
	
	Tags* (Separate each tag with a space):<br>
	<input required type="text" name="tags" maxlength = "200" value = "<?php
	if(isset($_POST['tags'])){
	echo $_POST['tags'];
	}
	?>"><br> 
	
	Variant*:<br>
	<input required type="text" name="variantDesc" maxlength = "140" value="<?php
			if(isset($_POST['variantDesc'])){
			echo $_POST['variantDesc'];
			}
		?>"><br>
		
	Price*:<br>
	<input required type="number" step=".01" decimals="1" min ="0"  name="price" maxlength = "10" value = "<?php
	if(isset($_POST['price'])){
	echo $_POST['price'];
	}
	?>"><br>
	
	Initial variant Stock*:<br>
	<input required type="number" step="any" min ="0" name="stock" maxlength = "7" value = "<?php
	if(isset($_POST['stock'])){
	echo $_POST['stock'];
	}
	?>"><br> 
	
	<p></p>
	Select image to upload:
    <input type="file" name="fileToUpload1" id="fileToUpload1">
	<p></p>
	Add more Variants?
		<input type="checkbox" name="moreVariants" value=>
	<p></p>	
	<button type="submit" name = "confirm"> Add Product</button>

    </form>
    </div>
</body>
</html>