<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');

    $product_id = $_GET['id'];
    if (isset($_GET['v'])) {
        $variant_id = $_GET['v'];
    } else {
        $variant_id = 0;
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
        //create Item using database
        $id = $row['ITEM_ID'];
        $name = $row['ITEM_NAME'];
        $desc = $row['ITEM_DESC'];
    }
    while ($row = mysqli_fetch_assoc($variant_result)){
        $item = new Item($id, $name, $desc, $row['VARIANT_ID'], $row['VARIANT_DESC'],
                             $row['IMG_PATH'], $row['PRICE'], $row['STOCK']);
    }

    $page_title = "Listing - " . $item -> getPName();

    // bring in html header.
    include("inc/header.php");
?>
    <h1><?php echo $item -> getPName(); ?></h1>
    <p>
        <h3><?php echo "Price: £" . $item -> getPrice(); ?></h3>

        <img src="<?php echo $item -> getImgPath(); ?>" alt="Image of <?php echo $item->getPName() ." : " .$item->getVDesc(); ?>">

        <?php echo $item -> getPDesc(); ?>

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

        <form action="basket.php" method="GET">
            <input type="hidden" name="id" value="<?php echo $item->getPId(); ?>">
            <input type="hidden" name="add" value="true" >
            <input type="submit" value ="Buy">
        </form>

    <?php print_r($item); ?>
    </p>
