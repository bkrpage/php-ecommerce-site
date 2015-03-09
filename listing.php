<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/practise/src/require.php');

    $conn = Common::connect_db();

    $item_id = $_GET['id'];

    $get_product = "SELECT * FROM PRODUCT WHERE ITEM_ID = '$item_id'";
    $result= mysqli_query($conn,$get_product);

    //$item = new Item("a","b","2");

    while ($row = mysqli_fetch_row($result)){
        $item = new Item($row[0],$row[1], $row[5]);
    }

    $page_title = "Listing - " . $item -> getName();

    // bring in html header.
    require("inc/header.php");
?>
    <h1>item: <?php echo $item -> getName(); ?></h1>
    <p>
        <?php echo "Price: £" . $item -> getPrice(); ?>
        <form action="basket.php?id=<?php echo $item->getId(); ?>&buy" method="GET">
            <input type="submit" value ="Buy">
        </form>
    </p>


