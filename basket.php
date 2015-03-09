<html>
<head>
    <?php
    require($_SERVER['DOCUMENT_ROOT'] . '/practise/src/require.php');

    if (isset($cart)) {
        $cart = new Cart();
    }

    if ($_GET['buy']){
        $cart ->addItem($item);
    }

    $page_title = "Cart - " . count($cart);

    require("inc/header.php");

    ?>


<body>
<?php
    echo '<h2>Cart Contents (' . count($cart) . ' items)</h2>';
    if (!$cart->isEmpty()) {
        foreach ($cart as $arr) {
            $item = $arr['item'];
            printf('<p><strong>%s</strong>: %d @ $%0.2f each.<p>', $item->getName(), $arr['qty'], $item->getPrice());
        } // End of foreach loop!
    } // End of IF.
?>
</body>
</html>