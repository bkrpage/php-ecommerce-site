<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
    session_start();

    $cart = new Cart();

    if (!empty($_SESSION['cart_items'])){
        $cart -> setItems($_SESSION['cart_items']);
    }

    // Adds item to cart from URL.
    if (isset($_POST['add'])){

        $product_id = $_POST['id'];
        $variant_id = $_POST['v'];

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

            $cart -> addItem($item);
        }

        $_SESSION['cart_items'] = $cart -> getItems();

    }

    $page_title = "Cart - " . count($cart); // For header.php
    require("inc/header.php");
?>

    <div class="body-box">
    <h2>Cart Contents (<?php echo count($cart) ?> items)</h2>

<?php

    if (!$cart -> isEmpty()) {
        foreach ($cart -> getItems() as $items) { // goes into first layer of array revealing item ID
            foreach ($items as $vrnt) { // goes into 2nd layer of array revealing variant ID
                $item = $vrnt['item']; // gets the item object stored under that item and variant and refactors it into $item
                $qty = $vrnt['qty'];
                printf('<p><strong>%s - %s</strong>: %d @ £%0.2f each</p>', $item -> getPName(), $item-> getVDesc(), $qty, $item->getPrice());

            }
        }
        $total = $cart -> calcTotalPrice();
        printf ("<p>Total: £%0.2f </p>",$total);
        ?>
        <form action='purchase.php' method ='post'>
            <input type='hidden' name='step' value='1'>
            <input type='submit' value='Purchase'>
        </form>
<?php
    }
?>
    </div>
</body>
</html>