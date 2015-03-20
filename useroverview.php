<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Review";
include("inc/header.php");

	if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)){
		$_SESSION['admin'] = 1;
		$conn = Common::connect_db();
		if(isset($_POST['confirm'])){
			$aChange = $_POST['admins'];
			$uname = $_POST['uname'];
			
			if($aChange==1){
				$q="UPDATE LOGIN SET ADMIN=1 WHERE USERNAME='$uname';";
				$r=mysqli_query($conn, $q);
			}else if ($aChange==0){
				$qu="UPDATE LOGIN SET ADMIN=0 WHERE USERNAME='$uname';";
				$re=mysqli_query($conn, $qu);
			}
		}
		echo"<table border='0' cellspacing='2' cellpadding='4'>
			<tr>
			<td>First Name</td>
			<td>Last Name</td>
			<td>Email</td>
			<td>Admin</td>
			<td>Address</td>
			<td>Contact Number</td>
			<td>Reset Token</td>
			</tr>";
		
		$qry = "SELECT * FROM CUSTOMER_DETAILS INNER JOIN LOGIN ON CUSTOMER_DETAILS.USERNAME = LOGIN.USERNAME; ";
		$res=mysqli_query($conn, $qry);
		
		
		while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {

		$f1=$row['FIRST_NAME'];
		$f2=$row['LAST_NAME'];
		$f3=$row['USERNAME'];
		$f4=$row['ADMIN'];
		$f6a=$row['ADDRESS1'];
		$f6b=$row['ADDRESS2'];
		$f6c=$row['ADDRESS3'];
		$f6d=$row['POSTCODE'];
		$f5=$row['CONTACT_NUMBER'];
		$f7=$row['TOKEN'];
		
		echo"<tr>
			<td>$f1</td>
			<td>$f2</td>
			<td>$f3</td>";
			
			if($f4==1){
				echo"<td>
					<form name='adimAdd' action='useroverview.php'  method='Post' enctype='multipart/form-data'> <input type=hidden name='admins' value='0'> <input type=hidden name='uname' value='$f3'> <button type='submit' name = 'confirm' > Demote Admin</button></form></td>";
			}else{
				echo"<td><form name='adimAdd' action='useroverview.php'  method='Post' enctype='multipart/form-data'> <input type=hidden name='admins' value='1'> <input type=hidden name='uname' value='$f3'> <button type='submit' name = 'confirm' > Promote To Admin</button></form></td>";
			}
			echo"
			<td>$f6a, $f6b, $f6c, $f6d </td>
			<td>$f5 </td>
			<td>$f7 </td>
			</tr>";		
		
		
		}
	}
?>