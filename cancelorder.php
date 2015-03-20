<?php
if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)) {
    $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
    require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
    $conn = Common::connect_db();
    $id = $_POST['orderid2'];

    // cancel order
    $qry = "UPDATE ORDERS SET DISPATCHED =2 WHERE ORDER_ID = $id;";
    mysqli_query($conn, $qry);

    header('Location:orderreview.php');
} else {
    header('Location:login.php');
}
?>