<?php
/**
@author Rowan Trodd
@author Reece Tucker
**/
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Add Variant";
include("inc/header.php");

echo "<div class='body-box''>";

if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)) {
    $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
    $feedback = $_SESSION["check"];

    if ($feedback == true) {//feedback if previous form added a variant
        echo "<p>Variant has been added</p>";
    }

    if(isset($_GET['id'])){
		$item_id = $_GET['id'];
		$_SESSION["itemID"]=$item_id;
    }else{	
        $item_id = $_SESSION["itemID"];
    }

    $conn = Common::connect_db();

    $query = "SELECT * FROM ITEM WHERE ITEM_ID = $item_id;";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_row($result);
        $itemName = $row[1];
        $itemDesc = $row[2];

    } else {
        echo "<p> Error: couldn't get the details of this item </p>";
    }

    if (isset($_POST["confirm"])) {
		//if the form has been changed, attempt to add new variant
        $variantDesc = $_POST['variantDesc'];
        $variantDesc = Common::clean($variantDesc, $conn);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $count = 1;

        if (($price) < 0) {
            $errorCatch[] = '-Please enter a Price above 0!';
        } else {
            $price = Common::clean($price, $conn);
        }

        if (($stock) < 0) {
            $errorCatch[] = '-Please enter a Stock above 0!';
        } else {
            $stock = Common::clean($stock, $conn);
        }

        $query4 = "SELECT MAX(VARIANT_ID) FROM ITEM_VARIANT WHERE ITEM_ID = $item_id;";
        $res4 = mysqli_query($conn, $query4);
        $row = mysqli_fetch_row($res4);
        $var_Id = $row[0] + 1;

		//if any errors, prevent creation of variant and print out the errors
        if (!empty($errorCatch)) {
            foreach ($errorCatch as $msg) {
                echo " <p>Error: $msg</p>";
            }
        } else {
			//attempt to create a new variant using SQL
            require 'upload.php';

            $query2 = "INSERT INTO ITEM_VARIANT(VARIANT_ID,ITEM_ID,VARIANT_DESC, PRICE, ITEM_STOCK, ITEM_IMG) VALUES ('$var_Id','$item_id','$variantDesc','$price','$stock','$target_file');";
            $result = mysqli_query($conn, $query2);

            if ($result) {
                $_SESSION["check"] = true;
                header('Location:addvariant.php');
            } else {
                $_SESSION["check"] = false;
                echo "Error";
            }

            mysqli_close($conn);
        }
    }
} else {
    header('Location:login.php');
}
?>
Item name: <?php
echo $itemName;
?>
<br>
<br>
Item Description: <?php	
echo $itemDesc;
?>
<br><BR>

<form id="adimAdd" action="addvariant.php" method="Post" enctype="multipart/form-data">
    Variant*:<br>
    <input required type="text" name="variantDesc" maxlength="140" value="<?php
    if (isset($_POST['variantDesc'])) {
        echo $_POST['variantDesc'];
    }
    ?>">
    <br>
    Price*:<br>
    <input required type="number" step=".01" decimals="1" min="0" name="price" maxlength="10" value="<?php
    if (isset($_POST['price'])) {
        echo $_POST['price'];
    }
    ?>"><br>

    Initial variant Stock*:<br>
    <input required type="number" step="any" min="0" name="stock" maxlength="7" value="<?php
    if (isset($_POST['stock'])) {
        echo $_POST['stock'];
    }
    ?>">
    <br>

    <p></p>
    Select image to upload:
    <input type="file" name="fileToUpload1" id="fileToUpload1">

    <p></p>
    <button type="submit" name="confirm"> Add Product</button>

</form>
</div>
</body>
</html>