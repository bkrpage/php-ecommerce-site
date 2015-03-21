<?php
/**
 * @author: Bradley Page
 */
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$cart = new Cart();

//sets the cart items if they're set in the session.
if (!empty($_SESSION['cart_items'])) {
    $cart->setItems($_SESSION['cart_items']);

    if (isset($_POST)) {
        $id = $_POST['id'];
        $v_id = $_POST['v_id'];
        $qty = $_POST['qty'];

        if ($_POST['remove'] == true) {
            $cart ->deleteItemById($id, $v_id);
        } else {
            $cart->updateItemById($id, $v_id, $qty);
        }

        $_SESSION['cart_items'] = $cart->getItems();
    }

    header('Location: basket.php');
} else {
    header('Location: basket.php');
}