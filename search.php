<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();
$page_title = "Search";

include("inc/header.php");
$conn = Common::connect_db();
?>
<div class="body-box">
    <form name='adimAdd' action='search.php' method='Post' enctype='multipart/form-data'>
        <input type='text' name='searchbar' placeholder='Search our products...' maxlength='140'>
        <button type='submit' name='confirm'> Search</button>
    </form>
    <?php
    if (isset($_POST['confirm'])) {
        $searchinput = $_POST['searchbar'];
        $searchinput = Common::clean($searchinput, $conn);
        $searchTerm = "$searchinput";
        search($searchTerm, $conn);
    }

    function search($searchTerm, $conn)
    {
        $new = splitSearchTerm($searchTerm);
        $result = mysqli_query($conn, $new);

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $tempProd = $row['ITEM_ID'];
                $r = mysqli_query($conn, "SELECT * FROM ITEM WHERE ITEM_ID ='$tempProd' AND IS_OBSELETE = 0;");
				if (mysqli_num_rows($r) > 0) {

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
						$l=1;
					}
				}else{
					if($l!=1){echo("No Results Found!");}
				}
            }
        } else {
            echo("No Results Found");
        }
    }

    function splitSearchTerm($searchTerm)
    {

        $arr = explode(" ", $searchTerm);
        $new = "SELECT DISTINCT ITEM_ID FROM TAG WHERE TAG IN(";

        foreach ($arr as $v) {
            $new = $new . "'";
            $new = $new . $v;
            $new = $new . "',";
        }

        $new = rtrim($new, ",");
        $new = $new . ");";
        return ($new);
    }

    ?>
    <div class='cf'></div>
</div>