<?php
/**
 * @author: Bradley Page
 */
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)) {
    $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
    $is_admin = true;
}

$product_id = $_GET['id'];
if (isset($_GET['v'])) {
    $variant_id = $_GET['v'];
} else {
    $variant_id = 1;
}
$product_exists = true;

$conn = Common::connect_db();
$get_product = "SELECT * FROM ITEM WHERE ITEM_ID = '$product_id'";
$get_variant = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID = '$product_id' AND VARIANT_ID = '$variant_id'";
$product_result = mysqli_query($conn, $get_product);
$variant_result = mysqli_query($conn, $get_variant);

$id = null;
$name = null;
$desc = null;
$is_obsolete = null;
$v_is_obsolete = null;

$values = mysqli_num_rows($product_result);
if ($values > 0) {
    while ($row = mysqli_fetch_assoc($product_result)) {
        // Get Base information from table "ITEM"
        $id = $row['ITEM_ID'];
        $name = $row['ITEM_NAME'];
        $desc = $row['ITEM_DESC'];
        $is_obsolete = $row['IS_OBSELETE'];
    }
    while ($row2 = mysqli_fetch_assoc($variant_result)) {
        //use base information AND get current variant information.
        $item = new Item($id, $name, $desc, $row2['VARIANT_ID'], $row2['VARIANT_DESC'],
            $row2['ITEM_IMG'], $row2['PRICE'], $row2['ITEM_STOCK']);
        $v_is_obsolete = $row2['IS_OBSELETE'];
    }
} else {
    $product_exists = false;
}


// START HEADER
if ($product_exists) {
    $page_title = "Listing - " . $item->getPName();
} else {
    $page_title = "Product does not exist";
}
include("inc/header.php");
// END HEADER
if ($product_exists) {
    ?>
    <div class="body-box">

        <h1><?php echo $item->getPName(); ?></h1>

        <img src="<?php echo $item->getImgPath(); ?>"
             alt="Image of <?php echo $item->getPName() . " : " . $item->getVDesc(); ?>"
             style='float: right; margin-right:50px;'>
        <?php
        if ($is_admin) {
            $item_id = $item->getPID();
            echo "
            <form action='editvariant.php' method='get'>
                <input type='hidden' name='id' value='$item_id'>
                <input type='submit' value='Edit Variants'>
            </form>
            <form action='edititem.php' method='get'>
                <input type='hidden' name='id' value='$item_id'>
                <input type='submit' value='Edit Item'>
            </form>
	     <form action='addvariant.php' method='get'>
                <input type='hidden' name='id' value='$item_id'>
                <input type='submit' value='Add New Variant'>
            </form>
            ";
        }
        ?>
        <p>
            <strong>Current Variation</strong>: <?php echo $item->getVDesc(); ?>
        </p>

        <p>
            <strong>Description</strong>: <?php echo $item->getPDesc(); ?>
        </p>

        <p>
            <strong>Pick from variations</strong>:
        <ul>
            <?php
            $qry_all_variants = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID = '$product_id' ORDER BY VARIANT_ID";
            $all_variants_result = mysqli_query($conn, $qry_all_variants);
            while ($row = mysqli_fetch_assoc($all_variants_result)) {
                echo "<li> <a href='listing.php?id=" . $id . "&v=" . $row['VARIANT_ID'] . " '>" . $row['VARIANT_DESC'] . "</a> </li> \n\t";
            }
            echo "\n";
            ?>
        </ul>
        </p>

        <h3><?php echo "Price: £" . $item->getPrice(); ?></h3>

        <?php
        if (($is_obsolete == 0)&&($v_is_obsolete == 0)) {
            if ($item->getStock() >= 1) {
                echo "Currently in Stock <BR><BR>";
            } else {
                echo "Currently out of stock<BR><BR>";
            }
        } else {
            echo "Not Available";
        }
        ?>

        <form action="basket.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $item->getPId(); ?>">
            <input type="hidden" name="v" value="<?php echo $item->getVId(); ?>">
            <input type="hidden" name="add" value="true">
            <?php
                if (($is_obsolete == 0) && ($v_is_obsolete == 0)) {
                    echo "<input type='submit' value='Add To Cart'";
                    if ($item->getStock() < 1) { echo 'disabled'; }
                    echo ">";
                }
            ?>
        </form>
    </div>

<?php
} else {
    ?>

    <div class="body-box">
        <h2> Item Does Not Exist </h2>
    </div>
<?php
}
?>