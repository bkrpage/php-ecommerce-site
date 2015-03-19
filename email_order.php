<?php

require 'mailer/PHPMailerAutoload.php';

$text = "
<p> Thanks for your order, details of your order are shown below.</p>

            <p>
                Order ID: <strong> $order_id</strong><BR>
                User Ordered: $order_email<BR>
                Date Ordered: $order_date<BR>
                Delivery Date: $order_del_date<BR>
                Delivery Method: $del_method<BR>
                Shipped To: $order_add<BR>
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
";

$qry_get_order_contents = "SELECT * FROM ORDER_CONTENTS  WHERE ORDER_ID LIKE '$order_id'";
$get_order_contents = mysqli_query($conn, $qry_get_order_contents);

while ($row = mysqli_fetch_assoc($get_order_contents)){
    $variant_id = $row['VARIANT_ID'];
    $item_id = $row['ITEM_ID'];
    $qty = $row['QUANTITY'];
    $price = $row ['PRICE'];

    $qry_get_item_name = "SELECT ITEM_NAME FROM ITEM WHERE ITEM_ID LIKE '$item_id'";
    $get_item_name = mysqli_query($conn, $qry_get_item_name);
    $item_name = mysqli_fetch_row($get_item_name);

    $qry_get_var_name = "SELECT VARIANT_DESC FROM ITEM_VARIANT WHERE ITEM_ID LIKE '$item_id' AND VARIANT_ID LIKE '$variant_id'";
    $get_var_name = mysqli_query($conn, $qry_get_var_name);
    $var_name = mysqli_fetch_row($get_var_name);

    $text .= sprintf("<tr><td>%s - %s</td><td>%u</td><td>£%.2f</td></tr>", $item_name[0], $var_name[0], $qty, $price);
}

$text .= "</tbody><tfoot>";

$text .= sprintf("<tr><td colspan='2'> Subtotal</td><td> £%0.2f</td>", $subtotal);
$text .= sprintf("<tr><td colspan='2'> Postage</td><td> £%0.2f</td>", $post);
$text .= sprintf("<tr><td colspan='2'> Total</td><td><strong> £%0.2f</strong></td>", $total);

$text .= "</tfoot></table>";

$mail = new PHPMailer;
$mail->IsSMTP();
$mail->Host = "localhost";
//Set who the message is to be sent from
$mail->setFrom('i7214754@bournemouth.ac.uk', 'PHP-ESHOP Order Confirmation');
//Set who the message is to be sent to
$mail->addAddress($order_email, $name);
//Set the subject line
$mail->Subject = 'Your recent order with us.';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML("$text ");
//send the message, check for errors
if (!$mail->send()) {
    echo "The order email was not sent. Please contact the web admin with this code: " . $mail->ErrorInfo;
} else {
}

?>