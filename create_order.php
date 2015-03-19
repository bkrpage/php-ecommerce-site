<?php

    $conn = Common::connect_db();

    $email = $_SESSION['userID'];

    $tmp_addr = "123 address rad, address land, ad12 3re";
    //TODO - this
    $date = date("Y-m-d H:i:s");
    $del_method = $_POST['post_method'];

    // Changes delivery date according to postage
    if ($_POST['post_method'] == 1) {
        $del_date = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 5, date("Y")));
    } else if ($_POST['post_method'] == 2) {
        $del_date = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 2, date("Y")));
    } else if ($_POST['post_method'] == 3) {
        $del_date = date("Y-m-d H:i:s", mktime(13, 0, 0, date("m"), date("d") + 1, date("Y")));
    }

    // Adds order into database
    $add_order = "INSERT INTO ORDERS (EMAIL, TOTAL, ORDER_DATE, DELIVERY_DATE, DELIVERY_METHOD, SHIPPING_ADDRESS)
                    VALUES ('$email', '$total', '$date', '$del_date','$del_method' , '$tmp_addr')";
    mysqli_query($conn,$add_order);

    //gets the order from the ORDERS table to create an order contents row. - due to lack of foreign keys thanks to lack of InnoDB.
    $get_order = "SELECT * FROM ORDERS WHERE EMAIL = '$email' AND ORDER_DATE = '$date'";
    $orders_row = mysqli_fetch_assoc(mysqli_query($conn, $get_order));

    $order_id = $orders_row['ORDER_ID'];

    // Adds each item in cart to the database.
    foreach ($cart -> getItems() as $items) { // goes into first layer of array revealing item ID
        foreach ($items as $vrnt) { // goes into 2nd layer of array revealing variant ID
            $item = $vrnt['item']; // gets the item object stored under that item and variant and refactors it into $item
            $qty = $vrnt['qty'];

            $var_id = $item -> getVID();
            $item_id = $item -> getPID();
            $price = $item -> getPrice();

            $add_order_contents = "INSERT INTO ORDER_CONTENTS (ORDER_ID, VARIANT_ID, ITEM_ID, QUANTITY, PRICE)
                              VALUES ('$order_id', '$var_id', '$item_id', '$qty', '$price')";
            mysqli_query($conn,$add_order_contents);
        }
    }

    // gets rid of item data in session (finally)
    unset($_SESSION['cart_items']);
    unset($cart);

?>