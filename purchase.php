<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
    session_start();

    $cart = new Cart();

    if ($_POST['step'] == 1) {
        $page_title = "Delivery"; // For header.php
    } else if ($_POST['step']  == 2){
        $page_title = "Confirm Order";
    }

    require("inc/header.php");



    if (!empty($_SESSION['cart_items'])) {
        $cart->setItems($_SESSION['cart_items']);

        if ($_POST['step'] == 1) { // Select Postage step
            ?>
            <form action="purchase.php" method="POST">
                <input type="hidden" name="step" value="2">

                <input type="radio" name="delivery" value="standard" checked> Standard Delivery (3-5 days) - FREE!
                <br>
                <input type="radio" name="delivery" value="first"> First Class (1-2 days) - £3.99
                <br>
                <input type="radio" name="delivery" value="courier"> Courier (Tomorrow 1PM) - £8.99
                <BR>
                <input type="submit" value="Review Order">
            </form>
        <?php
        } else if ($_POST['step'] == 2) { // Confirm Order step - shows items and total.

            if ($_POST['delivery'] == "standard") {
                $post = 0.0;
                $post_method = 1;
            } else if ($_POST['delivery'] == "first") {
                $post = 3.99;
                $post_method = 2;
            } else if ($_POST['delivery'] == "courier") {
                $post = 8.99;
                $post_method = 3;
            }

            $subtotal = $cart->calcTotalPrice();
            $total = $subtotal + $post;
            ?>

            <h2> Confirm Order </h2>

            <table>
            <thead>
            <tr>
                <th> Product</th>
                <th> Quantity</th>
                <th> Price</th>
            </tr>
            </thead>
            <tbody>
            <?php

            foreach ($cart->getItems() as $items) { // goes into first layer of array revealing item ID
                foreach ($items as $vrnt) { // goes into 2nd layer of array revealing variant ID
                    $item = $vrnt['item']; // gets the item object stored under that item and variant and refactors it into $item
                    $qty = $vrnt['qty'];
                    printf("<tr><td>%s - %s</td><td>%d</td><td>£%0.2f </td></tr>", $item->getPName(), $item->getVDesc(), $qty, $item->getPrice());

                }
            }
            echo "</tbody><tfoot>";

            printf("<tr><td colspan='2'> Subtotal</td><td> £%0.2f</td>", $subtotal);
            printf("<tr><td colspan='2'> Postage</td><td> £%0.2f</td>", $post);
            printf("<tr><td colspan='2'> Total</td><td><strong> £%0.2f</strong></td>", $total);

            echo "</tfoot></table>";
            echo "<BR>
        <form action='purchase.php' method ='post'>
            <input type='hidden' name='post_method' value='$post_method'>
            <input type='hidden' name='step' value='3'>
            <input type='submit' value='Complete Order'>
        </form>
        ";

        } else if ($_POST['step'] == 3) {

            $subtotal = $cart->calcTotalPrice();
            $total = $subtotal + $_POST['postage'];

            require('create_order.php');

            //$orders_row is from create_order.php
            $order_id = $orders_row['ORDER_ID'];
            $order_email = $orders_row['EMAIL'];
            $order_total = $orders_row['TOTAL'];
            $order_date = $orders_row['ORDER_DATE'];
            $order_del_date = $orders_row['DELIVERY_DATE'];
            switch ($orders_row['DELIVERY_METHOD']) {
                case 1:
                    $del_method = "Standard Postage";
                    break;
                case 2:
                    $del_method = "First Class";
                    break;
                case 3:
                    $del_method = "Courier";
                    break;
            }
            $order_add = $orders_row['SHIPPING_ADDRESS'];

?>

            <h2> Order Purchased! </h2>

            <p> Thanks for your order, details of your order are shown below.</p>

            <p>
                Order ID: <strong><?php echo $order_id; ?></strong><BR>
                User Ordered: <?php echo $order_email; ?><BR>
                Date Ordered: <?php echo $order_date; ?><BR>
                Delivery Date: <?php echo $order_del_date; ?><BR>
                Delivery Method: <?php echo $del_method; ?><BR>
                Shipped To: <?php echo $order_add; ?><BR>
            </p>
            <table>
                <thead>
                <tr>
                    <th> Product</th>
                    <th> Quantity</th>
                    <th> Price</th>
                </tr>
                </thead>
                <tbody>
<?php

             printf("<tr><td>%s - %s</td><td>%d</td><td>£%0.2f </td></tr>", $item->getPName(), $item->getVDesc(), $qty, $item->getPrice());

            echo "</tbody><tfoot>";

            printf("<tr><td colspan='2'> Subtotal</td><td> £%0.2f</td>", $subtotal);
            printf("<tr><td colspan='2'> Postage</td><td> £%0.2f</td>", $post);
            printf("<tr><td colspan='2'> Total</td><td><strong> £%0.2f</strong></td>", $total);

            echo "</tfoot></table>";
            echo "<BR>
            <form action='index.php' method ='post'>
                <input type='submit' value='Go Home'>
            </form>
            ";
        }
    } else {
       // header('index.php');
    }

