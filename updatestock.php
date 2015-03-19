<?php
if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)){
        $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
        $is_admin == true;
		require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');	 
		$conn = Common::connect_db();
			
		$result = mysqli_query($conn, "SELECT * FROM ITEM");
		$storeArray = Array();
		
		while ($r = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$i_id=$r['ITEM_ID'];
			//updating stock
			if(isset($_POST['confirm'])){
			
				$query2 = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID ='$i_id';";
				$result3 = mysqli_query($conn, $query2);
				
				while ($row = mysqli_fetch_array($result3, MYSQL_ASSOC)) {
					$variant =  $row['VARIANT_ID'];
					$boxname = $i_id."v".$variant;
					$temp_val = $_POST[$boxname];
					
					if(($temp_val) < 0){
						$errorCatch[] = '-Please enter a Stock above 0!';
					}else {
						$temp_val = Common::clean($temp_val, $conn);
					}
					
					if(!empty($errorCatch)){
						echo'Error:';
					
						foreach($errorCatch as $msg){
							echo"<br> $msg ";
						}
					} else {
						$t_query = "UPDATE ITEM_VARIANT SET ITEM_STOCK = '$temp_val' WHERE ITEM_ID = '$i_id' AND VARIANT_ID = '$variant';";
						$res = mysqli_query($conn, $t_query);
					}
				}
			}
		}
		echo"<form name='adimAdd' action='updatestock.php'  method='Post' enctype='multipart/form-data'>";
		$res = mysqli_query($conn, "SELECT * FROM ITEM");	
		
		while ($r = mysqli_fetch_array($res, MYSQL_ASSOC)) {
			$n = $r['ITEM_NAME'];
			echo "$n <br>";
			$a=$r['ITEM_ID'];
			
			$query2 = "SELECT * FROM ITEM_VARIANT WHERE ITEM_ID ='$a';";
			$result2 = mysqli_query($conn, $query2);
						
			while ($row = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
				$v_id=$row['VARIANT_ID'];
				$v_desc=$row['VARIANT_DESC'];	
				echo $v_desc;
				$boxname= $a."v".$v_id;
				$query1 = "SELECT ITEM_STOCK FROM ITEM_VARIANT WHERE ITEM_ID =$a AND VARIANT_ID = '$v_id';";
				$result1 = mysqli_query($conn, $query1);
				$stock = mysqli_fetch_row($result1);
				echo "<input type='number' step='any' min='0'  name='$boxname' value ='$stock[0]' >";
				echo "<br>";
			}
		
		echo"<br>";
		}
		echo"<button type='submit' name = 'confirm'> Update Stock</button> </form>";
	}else{
		header('Location:login.php');
	}

?>