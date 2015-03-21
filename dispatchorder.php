<?php
/**
@author Reece Tucker
@author Rowan Trodd
*/
session_start();
if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)) {
    $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
    $is_admin == true;
    require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
    $conn = Common::connect_db();
    $id = $_POST['orderid'];
    //dispatch order
    $qry = "UPDATE ORDERS SET DISPATCHED =1 WHERE ORDER_ID = $id;";

    //stock keeping
    $query = "SELECT*FROM ORDER_CONTENTS WHERE ORDER_ID = $id;";
    $result = mysqli_query($conn, $query);
    $updateqry = Array();

    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
        $c = 0;
        $v_id = $row['VARIANT_ID'];
        $i_id = $row['ITEM_ID'];
        $o_qty = $row['QUANTITY'];

        $q = "SELECT ITEM_STOCK FROM ITEM_VARIANT WHERE ITEM_ID = $i_id AND VARIANT_ID=$v_id;";
        $res = mysqli_query($conn, $q);

        while ($temp = mysqli_fetch_array($res, MYSQL_ASSOC)) {
            $stock = $temp['ITEM_STOCK'];

            $stock = $stock - $o_qty;
        }

        if ($stock < 0) {
            $error = 1;
        } else {
            $updateqry[] = "UPDATE ITEM_VARIANT SET ITEM_STOCK = $stock WHERE ITEM_ID = $i_id AND VARIANT_ID =$v_id;";
        }

        $c++;
    }
    if (error == 1) {//error trap prevents stock being decremented if order cannot be fulfilled
        echo "Insufficient Stock, Dispatch Cancelled.";
        echo "<button onClick='orderreview.php'> Back to Orders Screen </button>";
    } else {
        mysqli_query($conn, $qry);

        foreach ($updateqry as $uq) {//update each piece of stock in turn (as form grow with options, this must be looped)
            mysqli_query($conn, $uq);
        }

        header('Location:orderreview.php');//if success, back to review the orders
    }
} else {
    header('Location:login.php');//if not logged in, take to login screen
}
?>