<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Change Details";

include("inc/header.php");

if (isset($_COOKIE['user'])){
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])){
    if ($_SESSION['loggedin'] == true){

        $session_email = $_SESSION['userID'];

        //Information from forms
        $firstname = $_POST['first_name'];
        $surname = $_POST['surname'];
        $phone = $_POST['number'];
        $addr1 = $_POST['address1'];
        $addr2 = $_POST['address2'];
        $addr3 = $_POST['address3'];
        $postcode = $_POST['post_code'];
        $country = $_POST['country'];

        $email = $_POST['email'];

        $email_confirmation = $_POST['confirm_email'];

        $password = $_POST['password'];
        $hashed_pw = SHA1("$password"); //Hashed password for entry into DB

        $email = strtolower($email);
        $email_confirmation = strtolower($email_confirmation);

        $conn = Common::connect_db();
        if (!$conn){
            die(mysqli_connect_error());
        }

        $auth_errors = array();

        $qry_user_details = "SELECT * FROM CUSTOMER_DETAILS WHERE USERNAME LIKE '$session_email';";
        $result_user_details = mysqli_query($conn,$qry_user_details);

        $user_details = mysqli_fetch_row($result_user_details);



        ?>
        <div class="form-box change-details ">

        <h1>Change details</h1>
        <p>Changing details for <?php echo" $user_details[1]";?>. Not you? <a href="logout.php">Logout</a><p>
        <p>Leave a field empty if you do not wish to change it</p>
        <?php
        if (!empty($_POST)){
            //check for existing emails
            if (isset($email)){
                $qry_email_check = "SELECT USERNAME FROM LOGIN WHERE USERNAME LIKE '$email'"; //Query to find duplicate emails
                $result_email = mysqli_query($conn, $qry_email_check);

                if (mysqli_num_rows($result_email) >= 1){
                    $auth_errors[] = "<p class='error'>Email is taken</p><style>.e{border: 1px solid #CC0000;}</style>";
                } else {
                    //check if email and email confirm are the same
                    if ($email != $email_confirmation){
                        $auth_errors[] = "<p class='error'>Emails do not match</p><style>.e,.ec{border: 1px solid #CC0000;}</style>";
                    }
                }
            }

            //check if Phone number is valid length.
            if (!empty($phone)){
                if (!preg_match("/^[0-9]+$/",$phone)){
                    $auth_errors[] = "<p class='error'>Phone number can only be digits</p><style>.pn{border: 1px solid #CC0000;}</style>";
                } else {
                    if (strlen($phone) != 11){
                        $auth_errors[] = "<p class='error'>Phone number is an incorrect length, please enter 11 digits.</p><style>.pn{border: 1px solid #CC0000;}</style>";
                    }
                }
            }

            //check if a security answer is given when updating question
            if(!empty($sec_q) && empty($sec_a)){
                $auth_errors[] = "<p class='error'>Please enter an answer for your security question.</p><style>.sa{border: 1px solid #CC0000;}</style>";
            } else if (empty($sec_q) && !empty($sec_a)){
                $auth_errors[] = "<p class='error'>Please enter a Security Question</p><style>.sq{border: 1px solid #CC0000;}</style>";
            }


            //password check
            if (empty($password)){
                $auth_errors[] = "<p class='error'>Please enter your current password</p><style>.pw{border: 1px solid #CC0000;}</style>";
            } else {
                $qry_pwd_check = "SELECT * FROM LOGIN WHERE USERNAME LIKE '$session_email' AND PASSWORD LIKE '$hashed_pw'";
                $result_pwd = mysqli_query($conn, $qry_pwd_check);

                if (mysqli_num_rows($result_pwd) == 0){
                    $auth_errors[] = "<p class='error'>Password is incorrect</p><style>.pw{border: 1px solid #CC0000;}</style>";
                }
            }

            // Used PHP and MySQL in easy steps' error method here - modified to my usage.
            if (empty($auth_errors)){

                $update_errors = array();

                //SQL queries, adds to $update_errors if the connection fails
                if (!empty($firstname)){
                    $q_edit_fn = "UPDATE CUSTOMER_DETAILS  SET FIRST_NAME = '$firstname' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_fn)){
                        $update_errors[] ="<p class='error'>There was an error while changing the First Name. Please try again.</p><style>.fn{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($surname)){
                    $q_edit_sn = "UPDATE CUSTOMER_DETAILS SET LAST_NAME = '$surname' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_sn)){
                        $update_errors[] ="<p class='error'>There was an error while changing the Surname. Please try again.</p><style>.sn{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($phone)){
                    $q_edit_no = "UPDATE CUSTOMER_DETAILS SET CONTACT_NUMBER = '$phone' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_no)){
                        $update_errors[] ="<p class='error'>There was an error while changing the Phone number. Please try again.</p><style>.pn{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($addr1)){
                    $q_edit_a1 = "UPDATE CUSTOMER_DETAILS SET ADDRESS1 = '$addr1' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_a1)){
                        $update_errors[] ="<p class='error'>There was an error while changing Address 1. Please try again.</p><style>.a1{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($addr2)){
                    $q_edit_a2 = "UPDATE CUSTOMER_DETAILS SET ADDRESS2 = '$addr2' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_a2)){
                        $update_errors[] ="<p class='error'>There was an error while changing Address 2. Please try again.</p><style>.a2{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($addr3)){
                    $q_edit_a3 = "UPDATE CUSTOMER_DETAILS SET ADDRESS3 = '$addr3' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_a3)){
                        $update_errors[] ="<p class='error'>There was an error while changing Address 3. Please try again.</p><style>.a3{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($postcode)){
                    $q_edit_pc = "UPDATE CUSTOMER_DETAILS SET POSTCODE = '$postcode' WHERE USERNAME LIKE '$session_email'";

                    if(!mysqli_query($conn, $q_edit_pc)){
                        $update_errors[] ="<p class='error'>There was an error while changing the Post Code. Please try again.</p><style>.pc{border: 1px solid #CC0000;}</style>";
                    }
                }

                if (!empty($email)) {
                    $q_edit_users = "UPDATE CUSTOMER_DETAILS SET USERNAME = '$email' WHERE USERNAME LIKE '$session_email'";
                    $q_edit_users_2 = "UPDATE LOGIN SET USERNAME = '$email' WHERE USERNAME LIKE '$session_email'";


                    if (!mysqli_query($conn, $q_edit_users)) {
                        $update_errors[] = "<p class='error'>There was an error while changing the Email. Please try again.(CUSTOMER_DETAILS)</p><style>.e{border: 1px solid #CC0000;}</style>";
                    } else {
                        if (!mysqli_query($conn, $q_edit_users_2)) {
                            $update_errors[] = "<p class='error'>There was an error while changing the Email. Please try again(LOGIN).</p><style>.e{border: 1px solid #CC0000;}</style>";
                        } else {
                            $_SESSION['userID'] = $email;
                            //needs to change the session emails so it doesnt stay logged in as a false email after changing.
                            $cookie_name = "user";
                            $cookie_value = $email;
                            $cookie_time = time() + 3600 * 24 * 7; //setting cookie expiry time for a week
                            setcookie($cookie_name, $cookie_value, $cookie_time);
                        }
                    }
                }

                if (empty($update_errors)){
                    header('Location: account.php?successfulChange=true');


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
        <form action="change_details.php?" method="POST">
        <div class="change-details left">
            <label for="first_name">Name</label>
            <input type="text" name="first_name" maxlength="256" class="groupdown fn" value="<?php if (isset($firstname)) echo $firstname;?>">

            <label for="surname">Surname</label>
            <input type="text" name="surname" maxlength="256" class="sn" value="<?php if (isset($surname)) echo $surname;?>">

            <label for="email">Email </label>
            <input type="email" name="email" maxlength="256"  class="groupdown e" value="<?php if (isset($email)) echo $email;?>">

            <label for="confirm_email">Email Confirm</label>
            <input type="email" name="confirm_email" maxlength="256"  class="ec" value="<?php if (isset($email_confirmation)) echo $email_confirmation;?>">

        </div>

        <div class="change-details right">
            <label for="number">Phone number</label>
            <input type="text" name="number" maxlength="11" class="pn"value="<?php if (isset($phone)) echo $phone;?>">

            <label for="address1">Address</label>
            <input type="text" name="address1" maxlength="256" class="groupdown a1"  value="<?php if (isset($addr1)) echo $addr1;?>">

            <label for="address2">Address 2</label>
            <input type="text" name="address2" maxlength="256" class="groupdown a2"  value="<?php if (isset($addr3)) echo $addr2;?>">

            <label for="address3">Address 3 </label>
            <input type="text" name="address3" maxlength="256" class="groupdown a3" value="<?php if (isset($addr3)) echo $addr3;?>">

            <label for="post_code">Post/Zip Code </label>
            <input type="text" name="post_code"  maxlength="16" class="groupdown pc"value="<?php if (isset($postcode)) echo $postcode;?>">

        </div>

        <div class="clearfix"><!-- Standard clearfix to correct floated divs --></div>

        <div id="password-confirm" >
            <label for="password">So we know it's you, please confirm your password</label>
            <input type="password" name="password" class="pw">
            <input type="submit" value="Edit details" class="submit">
        </div>
        </form>

        <div id="cd-change-pass">
            <form action="change_password.php">
                <input type="submit" value="Change your password here" class="submit">
            </form>
        </div>
        </div>
    <?php

    } else {
        echo "Error";
        session_destroy();
    }
} else {
    header('Location: index.php'); //this refers to an error message in index.php

}
?>

</body>
</html>