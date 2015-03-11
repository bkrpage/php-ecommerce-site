<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');

    $conn = Common::connect_db();

    $item_id = $_GET['id'];

    $get_product = "SELECT * FROM PRODUCT WHERE ITEM_ID = '$item_id'";
    $result= mysqli_query($conn,$get_product);

    //$item = new Item("a","b","2");

    while ($row = mysqli_fetch_row($result)){
        //create Item using database
        $item = new Item($row[0],$row[1],$row[2], $row[3], $row[4]);
    }

    $page_title = "Listing - " . $item -> getName();

    // bring in html header.
    include("inc/header.php");
?>
    <h1><?php echo $item -> getName(); ?></h1>
    <p>
        <h3><?php echo "Price: £" . $item -> getPrice(); ?></h3>

        <img src="<?php echo $item -> getImgPath(); ?>" alt="Image of <?php $item -> getName(); ?>">

        <?php echo $item -> getDesc(); ?>

        <form action="basket.php" method="GET">
            <input type="hidden" name="id" value="<?php echo $item->getId(); ?>">
            <input type="hidden" name="add" value="true" >
            <input type="submit" value ="Buy">
        </form>


    </p>
