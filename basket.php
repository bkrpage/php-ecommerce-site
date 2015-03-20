<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$cart = new Cart();

// When "Empty Basket" is clicked
if (isset($_POST['empty'])){
    if ($_POST['empty']){
        $_SESSION['cart_items'] = null;
        unset($_SESSION['cart_items']);
    }
}

//sets the cart items if they're set in the session.
if (!empty($_SESSION['cart_items'])) {
    $cart->setItems($_SESSION['cart_items']);
}

// Adds item to cart from URL.
if (isset($_POST['add'])) {

    $product_id = $_POST['id'];
    $variant_id = $_POST['v'];

    $conn = Common::connect_db();
    $get_product = "SELECT * FROM ITEM WHERE ITEM_ID = '$product_id'";
    $get_variant = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID = '$product_id' AND VARIANT_ID = '$variant_id'";
    $product_result = mysqli_query($conn, $get_product);
    $variant_result = mysqli_query($conn, $get_variant);

    $id = null;
    $name = null;
    $desc = null;

    while ($row = mysqli_fetch_assoc($product_result)) {
        // Get Base information from table "ITEM"
        $id = $row['ITEM_ID'];
        $name = $row['ITEM_NAME'];
        $desc = $row['ITEM_DESC'];
    }
    while ($row = mysqli_fetch_assoc($variant_result)) {
        //use base information AND get current variant information - this will be shown on the cart page.
        $item = new Item($id, $name, $desc, $row['VARIANT_ID'], $row['VARIANT_DESC'], $row['ITEM_IMAGE'], $row['PRICE'], $row['ITEM_STOCK']);

        $cart->addItem($item);
    }

    $_SESSION['cart_items'] = $cart->getItems(); // reset the session with new data.

}

$page_title = "Cart - " . count($cart); // For header.php
require("inc/header.php");
?>

<div class="body-box">
    <h2>Cart Contents (<?php
        echo count($cart);
        ?> items)</h2>

    <?php

    if (!$cart->isEmpty()) {
        foreach ($cart->getItems() as $items) { // goes into first layer of array revealing item ID
            foreach ($items as $vrnt) { // goes into 2nd layer of array revealing variant ID
                $item = $vrnt['item']; // gets the item object stored under that item and variant and refactors it into $item
                $qty = $vrnt['qty'];
                printf('<p><strong>%s - %s</strong>: %d - £%0.2f each</p>', $item->getPName(), $item->getVDesc(), $qty, $item->getPrice());
                ?>
                <form action="item_qty.php" method="post" >
                    <input type="hidden" name ="id" value="<?php echo $item->getPID();?>">
                    <input type="hidden" name="v_id" value="<?php echo $item->getVID();?>">
                    Quantity: <input type="text" name="qty" value="<?php echo $qty; ?>" size="3">
                    Remove Item? <input type="checkbox" name="remove" value="true">
                    <input type="submit" value ="Edit Quantity">
                </form>
                <?php
            }
        }
        $total = $cart->calcTotalPrice();
        printf("<p>Total: £%0.2f </p>", $total);
        ?>

        <form action='purchase.php' method='post'>
            <input type='hidden' name='step' value='1'>
            <input type='submit' value='Purchase'>
        </form>
        <BR>
        <form action='basket.php' method='post'>
            <input type="hidden" name="empty" value="true">
            <input type='submit' value='Empty Basket'>
        </form>
    <?php
    }
    ?>
</div>
</body>
</html>