<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');
    session_start();

    $cart = new Cart();

    if (!empty($_SESSION['cart_items'])){
        $cart -> setItems($_SESSION['cart_items']);
    }

    // Adds item to cart from URL.
    if ($_POST['add'] == true){
        $conn = Common::connect_db();

        $product_id = $_GET['id'];
        $variant_id = $_GET['v'];

        $get_product = "SELECT * FROM PRODUCT WHERE ITEM_ID = '$product_id'";
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

            $cart -> addItem($item);
        }

        $_SESSION['cart_items'] = $cart -> getItems();

    }

    $page_title = "Cart - " . count($cart); // For header.php
    require("inc/header.php");
?>

    <h2>Cart Contents (<?php echo count($cart) ?> items)</h2>

<?php
    echo "Hello";
    if (!$cart -> isEmpty()) {
        foreach ($cart -> getItems() as $arr) {
            $item = $arr['item'];
            printf('<p><strong>%s - %s</strong>: %d @ £%0.2f each.</p>', $item->getPName(), $item -> getVName(), $arr['qty'], $item->getPrice());
        }
    }

    $total = $cart -> calcTotalPrice();
    echo "<p>Total: £$total </p>";
?>
</body>
</html>