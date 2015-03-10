<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');
    session_start();

    $cart = new Cart();

    if (isset($_SESSION['cart_item'])){
        foreach ($_SESSION['cart_item'] as $id){
            /*
             * This is where the items stores in the session are used.
             * each value stored in the session is used to get the information for each
             * item in cart.
             */
            $conn = Common::connect_db();
            $get_product = "SELECT * FROM PRODUCT WHERE ITEM_ID = '$id'";
            $result= mysqli_query($conn,$get_product);

            while ($row = mysqli_fetch_row($result)){
                // Create item from ID stored from database.
                $item = new Item($row[0],$row[1],$row[2], $row[3], $row[4]);
                $cart -> addItem($item);
            }
        }
    }

    // Adds item to cart from URL.
    if ($_GET['buy'] == true){
        $conn = Common::connect_db();

        $item_id = $_GET['id'];
        $get_product = "SELECT * FROM PRODUCT WHERE ITEM_ID = '$item_id'";
        $result= mysqli_query($conn,$get_product);

        while ($row = mysqli_fetch_row($result)){
            //create Item using database
            $item = new Item($row[0],$row[1],$row[2], $row[3], $row[4]);
            $cart -> addItem($item);
        }

    }

    if (!$cart->isEmpty()) {
        $_SESSION['cart_item'] = array(); // Creates an array to stare each item in sessions.
        foreach ($cart as $arr) {
            $item = $arr['item'];
            array_push($_SESSION['cart_item'], $item -> getId()); // adds the item onto the end of session array
        }
    }

    $page_title = "Cart - " . count($cart); // For header.php
    require("inc/header.php");
?>

    <h2>Cart Contents (<?php echo count($cart) ?> items)</h2>

<?php
    if (!$cart->isEmpty()) {
        foreach ($cart as $arr) {
            $item = $arr['item'];
            printf('<p><strong>%s</strong>: %d @ £%0.2f each.</p>', $item->getName(), $arr['qty'], $item->getPrice());
        }
    }

?>
</body>
</html>