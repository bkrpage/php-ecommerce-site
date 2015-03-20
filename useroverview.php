<?php
session_start();
	if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)){
		$_SESSION['admin'] = 1;
        $page_title = "Review";
		include("inc/header.php");
		require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
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
			<td>
			<font face='Arial, Helvetica, sans-serif'>First Name &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'>Last Name &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'>Email &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'>Admin &nbsp;</font>
			</td>	
			<td>
			<font face='Arial, Helvetica, sans-serif'> Address &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'> Contact Number &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'> Reset Token &nbsp;</font>
			</td>
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
			<td>
			<font face='Arial, Helvetica, sans-serif'>  $f1 &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'>  $f2 &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'>  $f3 &nbsp;</font>
			</td>";
			
			if($f4==1){
				echo"<td>
					<font face='Arial, Helvetica, sans-serif'> <form name='adimAdd' action='useroverview.php'  method='Post' enctype='multipart/form-data'> <input type=hidden name='admins' value='0'> <input type=hidden name='uname' value='$f3'> <button type='submit' name = 'confirm' > Demote Admin</button></form> &nbsp;</font>
					</td>";
			}else{
				echo"<td>
					<font face='Arial, Helvetica, sans-serif'> <form name='adimAdd' action='useroverview.php'  method='Post' enctype='multipart/form-data'> <input type=hidden name='admins' value='1'> <input type=hidden name='uname' value='$f3'> <button type='submit' name = 'confirm' > Promote To Admin</button></form> &nbsp;</font>
					</td>";
			}
			echo"
			<td>
			<font face='Arial, Helvetica, sans-serif'>  $f6a, $f6b, $f6c, $f6d &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'>  $f5 &nbsp;</font>
			</td>
			<td>
			<font face='Arial, Helvetica, sans-serif'> $f7 &nbsp;</font>
			</td>
			</tr>";		
		
		
		}
	}
?>