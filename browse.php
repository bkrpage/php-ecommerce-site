<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Shop";
include("inc/header.php");

$conn = Common::connect_db();
$query1 = "SELECT * FROM ITEM";
$result = mysqli_query($conn, $query1);
?>
<div class="body-box">
    <?php
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $tempProd = $row['ITEM_ID'];
        $r = mysqli_query($conn, "SELECT * FROM ITEM WHERE ITEM_ID ='$tempProd';");

        while ($r = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            echo "<div class='display-item'>";
            $query1 = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID='$tempProd' AND VARIANT_ID=1;";
            $result2 = mysqli_query($conn, $query1);
            $res = mysqli_fetch_array($result2);
            $prc = $res['PRICE'];
            $src = $res['ITEM_IMG'];
            echo "<div class='display-img'><a href='listing.php?id=$tempProd'><img src='$src' alt='" . $r['ITEM_NAME'] . "'></a></div>";
            echo "<div class='display-name'><a href='listing.php?id=$tempProd'>" . $r['ITEM_NAME'] . "</a></div>";
            echo "<div class='display-price'>£$prc </div>";
            echo "<div class='cf'></div></div>";

        }
    }
    ?>
    <div class='cf'></div>
</div>