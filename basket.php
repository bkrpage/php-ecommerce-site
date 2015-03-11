<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');
    session_start();

    $cart = new Cart();

    if (!empty($_SESSION['cart_items'])){
        $cart -> setItems($_SESSION['cart_items']);
    }

    // Adds item to cart from URL.
    if ($_GET['add'] == true){
        $conn = Common::connect_db();

        $item_id = $_GET['id'];
        $get_product = "SELECT * FROM PRODUCT WHERE ITEM_ID = '$item_id'";
        $result= mysqli_query($conn,$get_product);

        while ($row = mysqli_fetch_assoc($result)){
            //create Item using database
            $item = new Item($row['ITEM_ID'],$row['ITEM_NAME'],$row['ITEM_DESC'], $row['ITEM_IMG'], $row['ITEM_PRICE']);
            $cart -> addItem($item);
        }

        $_SESSION['cart_items'] = $cart -> getItems();

    }

    $page_title = "Cart - " . count($cart); // For header.php
    require("inc/header.php");
?>

    <h2>Cart Contents (<?php echo count($cart) ?> items)</h2>

<?php
    if (!$cart->isEmpty()) {
        foreach ($cart -> getItems() as $arr) {
            $item = $arr['item'];
            printf('<p><strong>%s</strong>: %d @ £%0.2f each.</p>', $item->getName(), $arr['qty'], $item->getPrice());
        }
    }

    print_r($cart -> getItems());

?>
</body>
</html>