<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');

	$conn = Common::connect_db();
	
	$query1 = "SELECT * FROM ITEM";
	$result=mysqli_query($conn,$query1);
	
	while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
	
		$tempProd=$row['ITEM_ID'];
		$r=mysqli_query($conn,"SELECT * FROM ITEM WHERE ITEM_ID ='$tempProd';");
		
		while ($r = mysqli_fetch_array($r,MYSQLI_ASSOC)){
			echo"<div class='my_class'>";
			echo($r['ITEM_NAME']);
			$query1 = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID='$tempProd' AND VARIANT_ID=1;";
			$result2=mysqli_query($conn, $query1);
			$res = mysqli_fetch_array($result2);
			echo"<br>";
			$prc = $res['PRICE'];
			$src = $res['ITEM_IMG'];
			echo"Price: £$prc ";
			echo"<br>";
			echo"<a href='listing.php?id=$tempProd'>";
			echo"<img src='$src' alt='' style='width:100px;height:100px'>";
			echo"</a>";
			echo"</div>";
			echo"<br>";
		}
	}
?>