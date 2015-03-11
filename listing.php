<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');

    $product_id = $_GET['id'];
    if (isset($_GET['v'])) {
        $variant_id = $_GET['v'];
    } else {
        $variant_id = 1;
    }

    $conn = Common::connect_db();
    $get_product = "SELECT * FROM ITEM WHERE ITEM_ID = '$product_id'";
    $get_variant = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID = '$product_id' AND VARIANT_ID = '$variant_id'";
    $product_result = mysqli_query($conn,$get_product);
    $variant_result = mysqli_query($conn,$get_variant);

    $id = null;
    $name = null;
    $desc = null;
    while ($row = mysqli_fetch_assoc($product_result)){
        // Get Base information from table "ITEM"
        $id = $row['ITEM_ID'];
        $name = $row['ITEM_NAME'];
        $desc = $row['ITEM_DESC'];
    }
    while ($row = mysqli_fetch_assoc($variant_result)){
        //use base information AND get current variant information.
        $item = new Item($id, $name, $desc, $row['VARIANT_ID'], $row['VARIANT_DESC'],
                             $row['ITEM_IMAGE'], $row['PRICE'], $row['ITEM_STOCK']);
    }

    // START HEADER
    $page_title = "Listing - " . $item -> getPName();
    include("inc/header.php");
    // END HEADER
?>
    <h1><?php echo $item -> getPName(); ?></h1>
    <p>
        <?php echo $item -> getPDesc(); ?>
        <BR>
        Current Variation: <?php echo $item ->getVDesc(); ?>
    </p>

    <img src="<?php echo $item -> getImgPath(); ?>" alt="Image of <?php echo $item->getPName() ." : " .$item->getVDesc(); ?>">

    <p>
        Variants:
        <ul>
            <?php
            $qry_all_variants = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID = '$product_id'";
            $all_variants_result = mysqli_query($conn,$qry_all_variants);
            while($row = mysqli_fetch_assoc($all_variants_result)){
                echo "<li> <a href='listing.php?id=".$id."&v=".$row['VARIANT_ID']." '>". $row['VARIANT_DESC'] . "</a> </li> \n\t\t";
            }
            echo "\n";
            ?>
        </ul>
    </p>

    <h3><?php echo "Price: £" . $item -> getPrice(); ?></h3>

    <?php
        if ($item -> getStock() >= 1){
            echo "Currently in Stock <BR><BR>";
        } else {
            echo "Currently out of stock<BR><BR>";
        }
    ?>

    <form action="basket.php" method="GET">
        <input type="hidden" name="id" value="<?php echo $item->getPId(); ?>">
        <input type="hidden" name="add" value="true" >
        <input type="submit" value ="Add To Cart">
    </form>
