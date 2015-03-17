<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Register!";

include("inc/header.php");

if (isset($_COOKIE['user'])){
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])){
    if ($_SESSION['loggedin'] == true){

        header('Location: control_panel.php?alreadyRegistered=true'); //TODO

    } else {
        echo "Error";
        unset($_SESSION['loggedin']);
    }
} else {

// This info is inserted into databases
$email = $_POST['email'];
$password = $_POST['password'];
$hashed_pw = SHA1("$password");
$firstname = $_POST['first_name'];
$surname = $_POST['surname'];
$addr1 = $_POST['address1'];
$addr2 = $_POST['address2'];
$addr3 = $_POST['address3'];
$postcode = $_POST['post_code'];
$phone = $_POST['number'];
/*$sec_q = $_POST['sec_q'];
$sec_a = $_POST['sec_a'];*/
?>


<div class="form-box change-details">

<h1>Register</h1>
<?php
if (!empty($_POST)){
    $conn = Common::connect_db();

    $db_errors = array();

    //This is only for email confirmation
    $email_confirmation = $_POST['confirm_email'];
    $password_confirmation = $_POST['confirm_password'];

    //set lower case
    $email = strtolower($email);
    $email_confirmation = strtolower($email_confirmation);

    /*$sec_a = strtolower($sec_a);*/



    //email check
    if (empty($email)){
        $db_errors[] = "<p class='error'>An email address is required</p><style>.e{border: 1px solid #CC0000;}</style>"; //add to the errors
    } else {
        //Check if user exists
        $q_email_check = "SELECT u_email FROM users WHERE u_email LIKE '$email'"; //Query to find duplicate emails
        $result_email = mysqli_query($conn, $q_email_check);

        if (mysqli_num_rows($result_email) >= 1){
            $db_errors[] = "<p class='error'>Email is taken</p><style>.e{border: 1px solid #CC0000;}</style>";
        } else {
            //check if emails are the same
            if ($email != $email_confirmation){
                $db_errors[] = "<p class='error'>you need to confirm the email address</p><style>.ec{border: 1px solid #CC0000;}</style>";
            }
        }
    }
    //password check
    if (empty($password)){
        $db_errors[] = "<p class='error'>A password is required</p><style>.pw{border: 1px solid #CC0000;}</style>";
    } else {
        if (strlen($password) < 8){
            $db_errors[] = "<p class='error'>Password needs to be more than 8 characters</p><style>.pw{border: 1px solid #CC0000;}</style>";
        } else {
            //check if passwords are the same
            if ($password != $password_confirmation){
                $db_errors[] = "<p class='error'>Password do not match</p><style>.pw,.cpw{border: 1px solid #CC0000;}</style>";
            }
        }
    }

    //check everything else
    if(empty($firstname)){ //tested and worked
        $db_errors[] = "<p class='error'>First name is required</p><style>.fn{border: 1px solid #CC0000;}</style>";
    }
    if(empty($surname)){ // tested and worked
        $db_errors[] = "<p class='error'>Surname is required</p><style>.sn{border: 1px solid #CC0000;}</style>";
    }
    if(empty($phone)){ //tested and worked
        $db_errors[] = "<p class='error'>Phone number is required</p><style>.pn{border: 1px solid #CC0000;}</style>";
    } else {
        //check if Phone number is valid length.
        if (!preg_match("/^[0-9]+$/",$phone)){
            $db_errors[] = "<p class='error'>Phone number can only be digits</p><style>.pn{border: 1px solid #CC0000;}</style>";
        } else {
            if (strlen($phone) != 11){
                $db_errors[] = "<p class='error'>Phone number needs to be 11 digits long</p><style>.pn{border: 1px solid #CC0000;}</style>";
            }
        }
    }
    if(empty($addr1)){ //test3ed and worked
        $db_errors[] = "<p class='error'>First address line is requires</p><style>.a1{border: 1px solid #CC0000;}</style>";
    }
    if(empty($postcode)){ //tested and worked
        $db_errors[] = "<p class='error'>A post code is required</p><style>.pc{border: 1px solid #CC0000;}</style>";
    }
    /*if(empty($sec_q)){ //tested and worked
        $db_errors[] = "<p class='error'>Enter a security question</p><style>.sq{border: 1px solid #CC0000;}</style>";
    } else {
        if(empty($sec_a)){
            $db_errors[] = "<p class='error'>Answer your security question</p><style>.sa{border: 1px solid #CC0000;}</style>";
        }
    }*/

    $email = mysqli_real_escape_string($conn, $email);
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $surname = mysqli_real_escape_string($conn, $surname);
    $phone = mysqli_real_escape_string($conn, $phone);
    $addr1 = mysqli_real_escape_string($conn, $addr1);
    $addr2 = mysqli_real_escape_string($conn, $addr2);
    $addr3 = mysqli_real_escape_string($conn, $addr3);
    $postcode = mysqli_real_escape_string($conn, $postcode);
    /*$sec_q = mysqli_real_escape_string($conn, $sec_q);
    $sec_a = mysqli_real_escape_string($conn, $sec_a);*/



    //checks if there are any errors.
    if (empty($db_errors)){
        //sql queries
        $add_users = "INSERT INTO LOGIN (USERNAME, PASSWORD, ADMIN, TOKEN) VALUES ('$email','$hashed_pw', 0, null);";
        $add_user_details = "INSERT INTO CUSTOMER_DETAILS VALUES ('$email','$firstname','$surname','$addr1','$addr2','$addr3',
							'$postcode','$phone','sub','sub');";

        if (mysqli_query($conn, $add_users)){

            if (mysqli_query($conn, $add_user_details)){
                header('Location: login.php?registered=true'); // TODO
            } else {
                echo "<p class='error'>There was an unexpected Error. Please try again.</p>";
            }
        } else {
            echo "<p class='error'>There was an unexpected Error. Please try again.</p>";
        }
    } else {
        foreach($db_errors as $error){
            echo "$error";
        }
    }
    mysqli_close($conn);
}
}
?>
<form action="register.php" method="POST">
<div class="change-details left">
    <label for="first_name">Name *</label>
    <input type="text" name="first_name" maxlength="256" class="groupdown fn" value="<?php if (isset($firstname)) echo $firstname;?>">

    <label for="surname">Surname *</label>
    <input type="text" name="surname" maxlength="256" class="sn" value="<?php if (isset($surname)) echo $surname;?>">

    <label for="email">Email *</label>
    <input type="email" name="email" maxlength="256"  class="groupdown e" value="<?php if (isset($email)) echo $email;?>">

    <label for="confirm_email">Email Confirm *</label>
    <input type="email" name="confirm_email" maxlength="256"  class="ec" value="<?php if (isset($email_confirmation)) echo $email_confirmation;?>">

    <!--<label for="sec_q">Security Question *</label>
    <input type="text" name="sec_q" maxlength="256" class="groupdown sq" value="<?php if (isset($sec_q)) echo $sec_q;?>">

    <label for="sec_a">Security Answer *</label>
    <input type="text" name="sec_a" maxlength="256" class="sa" value="<?php if (isset($sec_a)) echo $sec_a;?>">-->
</div>

<div class="change-details right">
<label for="number">Phone number *</label>
<input type="text" name="number" maxlength="11" class="pn"value="<?php if (isset($phone)) echo $phone;?>">

<label for="address1">Address *</label>
<input type="text" name="address1" maxlength="256" class="groupdown a1"  value="<?php if (isset($addr1)) echo $addr1;?>">

<label for="address2">Address 2 </label>
<input type="text" name="address2" maxlength="256" class="groupdown a2"  value="<?php if (isset($addr3)) echo $addr2;?>">

<label for="address3">Address 3 </label>
<input type="text" name="address3" maxlength="256" class="groupdown a3" value="<?php if (isset($addr3)) echo $addr3;?>">

<label for="post_code">Post/Zip Code *</label>
<input type="text" name="post_code"  maxlength="16" class="groupdown pc"value="<?php if (isset($postcode)) echo $postcode;?>">
</div>

<div class="clearfix"><!-- Standard clearfix to correct floated divs --></div>

<div id="password" >
    <label for="password">Password *</label>
    <input type="password" name="password" class="groupdown pw">

    <label for="confirm_password">Password confirm *</label>
    <input type="password" name="confirm_password" class="cpw">

    <input type="submit" value="Register" class="submit">
</div>

</form>
</div>

</body>
</html>