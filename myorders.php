<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Review";
include("inc/header.php");

if (isset($_COOKIE['user'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])) {
    if ($_SESSION['loggedin'] == true) {
        $email = $_SESSION['userID'];
        $conn = Common::connect_db();
        $sort = $_POST['picker'];
        $hide = $_POST['hidecompleted'];
        $ef = "";
        $lf = "";
        $most = "";
        $least = "";
        $hidden = "";
        if ($sort == 'ef') {
            $ef = "selected";
        } else if ($sort == 'lf') {
            $lf = "selected";
        } else if ($sort == 'most') {
            $most = "selected";
        } else {
            $least = "selected";
        }

        echo "Sort By <form name='orderby' action='orderreview.php'  method='Post' enctype='multipart/form-data'>
			<select name='picker'>
			<option value='ef' $ef>Earliest First</option>
			<option value='lf' $lf>Latest First</option>
			<option value='most' $most>Most Expensive First</option>
			<option value='least' $least>Least Expensive First</option>
			</select>";

        if ($hide == 'on') {
            echo " Show Completed Orders <input type=checkbox name ='hidecompleted' checked>";
        } else {
            echo " Hide Completed Orders  <input type=checkbox name ='hidecompleted' >";
        }

        echo " <button type='submit' name = 'confirm'> Sort</button></form>";
        echo "<table border='0' cellspacing='2' cellpadding='4'>
		<tr>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Order ID &nbsp;</font>
		</td>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Email &nbsp;</font>
		</td>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Order Date &nbsp;</font>
		</td>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Delivery Date &nbsp;</font>
		</td>	
		<td>
		<font face='Arial, Helvetica, sans-serif'>Delivery Method &nbsp;</font>
		</td>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Shipping Address &nbsp;</font>
		</td>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Total &nbsp;</font>
		</td>
		<td>
		<font face='Arial, Helvetica, sans-serif'>Dispatched &nbsp;</font>	
		</td>
		</tr>";

        $compound = "";
        if ($hide == 'on') {
            $compound = "AND DISPATCHED<1";
        }

        $qry = "SELECT * FROM ORDERS WHERE EMAIL = '$email'" . $compound . " ORDER BY DELIVERY_DATE;";

        if ($sort == 'lf') {
            $qry = "SELECT * FROM ORDERS WHERE EMAIL = '$email'" . $compound . " ORDER BY DELIVERY_DATE DESC;";
        } else if ($sort == 'most') {
            $qry = "SELECT * FROM ORDERS WHERE EMAIL = '$email'" . $compound . " ORDER BY TOTAL DESC;";
        } else if ($sort == 'least') {
            $qry = "SELECT * FROM ORDERS WHERE EMAIL = '$email'" . $compound . " ORDER BY TOTAL ASC;";
        }

        $result = mysqli_query($conn, $qry);

        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

            $f1 = $row['ORDER_ID'];
            $f2 = $row['EMAIL'];
            $f3 = $row['TOTAL'];
            $f4 = $row['ORDER_DATE'];
            $f5 = $row['DELIVERY_DATE'];
            $f6 = $row['DELIVERY_METHOD'];
            $f7 = $row['SHIPPING_ADDRESS'];
            $f8 = $row['DISPATCHED'];

            if ($f8 > 0) {
                $dis = "disabled";
            } else {
                $dis = "";
            }

            echo "<tr>
				<td>$f1</td>
				<td>$f2</td>
				<td>$f4</td>
				<td>$f5</td>
				<td>$f6</td>
				<td>$f7</td>
				<td>£$f3</td>
				<td>$f8</td>
				</tr>";
        }
    } else {
        header('Location:login.php');
    }
} else {
    unset($_SESSION['loggedin']); // should never happen so - error
    header('Location:login.php');
}
?>