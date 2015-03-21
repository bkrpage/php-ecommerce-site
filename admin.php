<?php
/**
@author Rowan Trodd
@author Reece Tucker
*/
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Admin Control Panel";
include("inc/header.php");

if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)){
        $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.

		?>
		
		<div class="body-box">
		<p><a href="orderreview.php">Order Review</a></p>
		<p><a href="useroverview.php">User Overview</a></p>
		<p><a href="additem.php">Add Item</a></p>
		<p><a href="updatestock.php">Update Stock</a></p>
		</div>
		
		<?php
	}else{
		header('Location:login.php');
	}
?>
