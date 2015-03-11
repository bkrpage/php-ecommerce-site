<?php
require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');

$conn = Common::connect_db();
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
    echo $row[0];
    $id = $row[0]+1;
    echo $id;
    echo $tags;

    $arr=explode(" ",$tags);


    $query1 = "INSERT INTO ITEM (ITEM_ID,ITEM_NAME, ITEM_DESC)
		VALUES ('$id','$itemName', '$itemDesc');";

    $query2 = "INSERT INTO ITEM_VARIANT (ITEM_ID,VARIANT_DESC, PRICE, ITEM_STOCK)
		VALUES ('$id','$variantDesc', '$price','$stock');";



    $result1 = mysqli_query($conn, $query1);
    $result2 = mysqli_query($conn, $query2);

    foreach ($arr as $temp_tag){
        $query3 = "INSERT INTO TAG (ITEM_ID , TAG) VALUES($id,'$temp_tag');";
        $result3 = mysqli_query($conn, $query3);
    }

}

// START HEADER
$page_title = "Admin";
include("inc/header.php");
// END HEADER
?>
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
    <input type="text" id="variantDesc" name="variantDesc" value="
		<?php
    if(isset($_POST['variantDesc'])){
        echo $_POST['variantDesc'];
    }
    ?>">

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