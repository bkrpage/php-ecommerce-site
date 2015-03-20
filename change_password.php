<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Change Password";

include("inc/header.php");

if (isset($_COOKIE['user'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])){
if ($_SESSION['loggedin'] == true){


$conn = Common::connect_db();
if (!$conn) {
    die(mysqli_connect_error());
}

$session_email = $_SESSION['userID'];

$new_password = $_POST['new_password'];
$new_password_confirm = $_POST['new_password_confirm'];

$new_hashed_pw = SHA1("$new_password");

$password = $_POST['password'];
$hashed_pw = SHA1("$password");

$email = strtolower($email);
$email_confirmation = strtolower($email_confirmation);

$qry_user_details = "SELECT * FROM LOGIN WHERE USERNAME LIKE '$session_email';";
$result_user_details = mysqli_query($conn, $qry_user_details);

$user_details = mysqli_fetch_row($result_user_details);

$auth_errors = array();
?>
<div class='body-box'>

    <h1> Change Password</h1>

    <p>Changing Password for <?php echo $user_details[0]; ?>. Not you? <a href='logout.php'>Logout</a>.</p>
    <?php

    if (!empty($_POST)) {

        //password check
        if (empty($password)) {
            $auth_errors[] = "<p class='error'>Please enter your current password</p><style>.pw{border: 1px solid #CC0000;}</style>";
        } else {
            $qry_pwd_check = "SELECT * FROM LOGIN WHERE USERNAME LIKE '$session_email' AND PASSWORD LIKE '$hashed_pw'";
            $result_pwd = mysqli_query($conn, $qry_pwd_check);

            if (mysqli_num_rows($result_pwd) == 0) {
                $auth_errors[] = "<p class='error'>Password is incorrect</p><style>.pw{border: 1px solid #CC0000;}</style>";
            }
        }

        if (empty($new_password)) {
            $auth_error[] = "<p class='error'>Please enter your new password</p><style>.npw{border: 1px solid #CC0000;}</style>";
        } else {
            if (strlen($new_password) < 8) {
                $auth_errors[] = "<p class='error'>New password is not long enough</p><style>.npw{border: 1px solid #CC0000;}</style>";
            } else {
                if (empty($new_password_confirm)) {
                    $auth_errors[] = "<p class='error'>You need to confirm your new password</p><style>.npwc{border: 1px solid #CC0000;}</style>";
                } else {
                    if ($new_password != $new_password_confirm) {
                        $auth_errors[] = "<p class='error'>The password do not match</p><style>.npw,.npwc{border: 1px solid #CC0000;}</style>";
                    }
                }
            }
        }

        if (empty($auth_errors)) {

            $update_errors = array();
            //sql queries
            if (!empty($new_password)) {
                $q_edit_pw = "UPDATE LOGIN SET PASSWORD = '$new_hashed_pw' WHERE USERNAME LIKE '$session_email'";

                if (!mysqli_query($conn, $q_edit_pw)) {
                    $update_errors[] = "<p class='error'>There was an error while changing the Password. Please try again.</p>";
                }
            }


            if (empty($update_errors)) {
                $_SESSION['userID'] = $session_email;
                header('Location: account.php?successfulPassChange=true');


            } else {
                foreach ($update_errors as $error) {
                    echo "$error";
                }
            }

        } else {
            foreach ($auth_errors as $error) {
                echo "$error";
            }
        }
    }
    } else {
        echo "Error";
        session_destroy();
    }
    } else {
        header('Location: index.php');

    }
    ?>
    <form action="change_password.php" method="POST">
        <label for="password">Your Current Password</label>
        <input type="password" name="password" class="pw">

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" class="npw">

        <label for="new_password_confirm">Confirm you new password</label>
        <input type="password" name="new_password_confirm" class="npwc">

        <input type="submit" value="Change Password" class="submit">
    </form>
</div>


</body>

</html>