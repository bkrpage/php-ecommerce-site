<?php

if (isset($_COOKIE['user'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (!empty($_SESSION['loggedin'])) {
    if ($_SESSION['loggedin'] == true) {
        $conn = Common::connect_db();

        $users_email = $_SESSION['userID'];

        $get_det = "SELECT * FROM CUSTOMER_DETAILS WHERE USERNAME LIKE '$users_email'";
        $result = mysqli_query($conn, $get_det);

        $user_details = mysqli_fetch_row($result);

        $u_fn = $user_details[1];

        ?>
        <div class="login-module">
            Hello, <?php echo $u_fn; ?>! Not <?php echo $u_fn ?>? <a href="logout.php">Logout </a><BR>
        </div>
    <?php
    } else {
        echo "Error";
        session_destroy();
    }
} else {
    ?>
    <div class="login-module"><a href="login.php"> Login</a>. Don't have an account? <a href="register.php">Register
            here</a></div>
<?php
}

?>