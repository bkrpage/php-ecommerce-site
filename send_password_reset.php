<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
require 'mailer/PHPMailerAutoload.php';
session_start();

$page_title = "Send reset token";

include("inc/header.php");

echo "<div class='body-box'>";
if (isset($_COOKIE['user'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])) {
    if ($_SESSION['loggedin'] == true) {
        header('/index.php');
    }
} else {
    if ($_POST['step'] == 2) {
        $conn = Common::connect_db();

        $user = $_POST['email'];
        $qry_check_user = "SELECT * FROM LOGIN WHERE USERNAME LIKE '$user'";
        $result_check = mysqli_query($conn, $qry_check_user);

        if (mysqli_num_rows($result_check) >= 1) {

            function generateRandomString()
            {
                $length = 20;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }


            $token = generateRandomString();

            $qry_add_token = "UPDATE LOGIN SET TOKEN = '$token' WHERE USERNAME LIKE '$user'";
            if (mysqli_query($conn, $qry_add_token)) {

                //Create a new PHPMailer instance
                $mail = new PHPMailer;
                $mail->IsSMTP();
                $mail->Host = "localhost";
                //Set who the message is to be sent from
                $mail->setFrom('i7214754@bournemouth.ac.uk', 'PHP - ESHOP');
                //Set who the message is to be sent to
                $mail->addAddress($user, $name);
                //Set the subject line
                $mail->Subject = 'Your Password reset token';
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $mail->msgHTML("Please copy and paste this link into your browser: http://student20269.201415.uk/assignment2/reset_password.php?t=$token");

                //send the message, check for errors
                if (!$mail->send()) {
                    echo "The message was not sent. Please contact the web admin with this code: " . $mail->ErrorInfo;
                } else {
                    echo "Message sent, your reset link will be in your inbox soon.";
                    echo "<BR><a href='login.php'>Go to Login</a>";
                }
            }
        } else {
            header('Location: send_password_reset.php?user_not_exist=true');
        }
    } else {
        if ($_GET['user_not_exist']) {
            echo "Sorry, that email does not exist.";
        }
        ?>

        Please enter your email:
        <form action="send_password_reset.php" method="POST">
            <input type="email" name="email">
            <input type="hidden" name="step" value="2">
            <input type="submit" value="Send Password reset">
        </form>
    <?php
    }
}
?>
</div>
</body>
</html>