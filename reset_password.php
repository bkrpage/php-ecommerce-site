<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Change Password";

include("inc/header.php");

if (isset($_COOKIE['user'])){
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])){
    if ($_SESSION['loggedin'] == true) {
        header('/index.php');
    }
} else {

    if (isset($_GET['t'])) {
        $conn = Common::connect_db();
        $token = $_GET['t'];
        $_SESSION['token'] = $token;

        $check_token = "SELECT USERNAME FROM LOGIN WHERE TOKEN = '$token'";
        $result = mysqli_query($conn, $check_token);

        $row = mysqli_fetch_row($result);

        $_SESSION['user_resetting_pass'] = $row[0];
    }

    if (!empty($_POST)) {
        $conn = Common::connect_db();

        $new_password = $_POST['new_password'];
        $new_password_confirm = $_POST['new_password_confirm'];

        $new_hashed_pw = SHA1("$new_password");

        if (empty($new_password)){
            $auth_error[] = "<p class='error'>Please enter your new password</p><style>.npw{border: 1px solid #CC0000;}</style>";
        } else {
            if(strlen($new_password) < 8){
                $auth_errors[] = "<p class='error'>New password is not long enough</p><style>.npw{border: 1px solid #CC0000;}</style>";
            } else {
                if (empty($new_password_confirm)){
                    $auth_errors[] = "<p class='error'>You need to confirm your new password</p><style>.npwc{border: 1px solid #CC0000;}</style>";
                } else {
                    if ($new_password != $new_password_confirm){
                        $auth_errors[] = "<p class='error'>The password do not match</p><style>.npw,.npwc{border: 1px solid #CC0000;}</style>";
                    }
                }
            }
        }

        if (empty($auth_errors)){

            $update_errors = array();
            //sql queries
            if (!empty($new_password)){

                $email = $_SESSION['user_resetting_pass'];
                $q_edit_pw = "UPDATE LOGIN SET PASSWORD = '$new_hashed_pw' WHERE USERNAME LIKE '$email'";


                if(!mysqli_query($conn, $q_edit_pw)){
                    $update_errors[] ="<p class='error'>There was an error while changing the Password. Please try again.</p>";
                }
            }


            if (empty($update_errors)){

                $token = $_SESSION['token'];
                $delete_token = "UPDATE LOGIN SET TOKEN = NULL WHERE TOKEN = '$token'";
                mysqli_query($conn, $delete_token);

                header('Location: login.php?successfulPassChange=true');

            } else {
                foreach($update_errors as $error){
                    echo "$error";
                }
            }

        } else {
            foreach($auth_errors as $error){
                echo "$error";
            }
        }
    }
    ?>

    <form action="reset_password.php" method="POST">
        New Password:<input type="password" name="new_password"><BR>
        Confirm: <input type="password" name="new_password_confirm"><BR>
        <input type="submit" value="Reset!">
    </form>

    <?php
} ?>