<?php
require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Login";

include("inc/header.php");

if (isset($_COOKIE['user'])){
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}

if (isset($_SESSION['loggedin'])){
    if ($_SESSION['loggedin'] == true){
        header('Location: index.php');
    } else {
        echo "Error";
        unset($_SESSION['loggedin']);
    }
} else {
    ?>
    <div class="form-box index">

        <h1> Login </h1>

        <?php
        if ($_GET['successfulReset']){

            unset($_SESSION['user_resetting_pass']);
            echo "<p class='success'>Password successfully reset, now you can login below</p>";
        }

        if ($_GET['registered']){
            echo "<p class='success'>Successfully registered. Please login below</p>";
        }

        if ($_GET['loggedout']){
            echo "<p class='success'>You have logged out. Goodbye!</p>";
        }

        if ($_GET['alreadyloggedout']){
            echo "<p class='warning'>You are already logged out</p>";
        }

        if ($_GET['notLoggedIn']){
            echo "<p class='warning'>You are not logged in</p>";
        }

        if (!empty($_POST)){

            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashed_pw = SHA1("$password");

            $remember_me = $_POST['remember'];

            $entry_errors = array();

            if (empty($email)){
                $entry_errors[] = "<p class='error'>Please enter your Email</p><style>.e{border: 1px solid #CC0000;}</style>";
            } else {
                $conn = Common::connect_db();

                $email_check = "SELECT USERNAME FROM LOGIN WHERE USERNAME LIKE '$email'"; //Query to find duplicate emails
                $result_email = mysqli_query($conn, $email_check);
                $num_rows = mysqli_fetch_array($result_email);

                if (empty($num_rows)){
                    $entry_errors[] = "<p class='error'>User does not exist</p><style>.e{border: 1px solid #CC0000;}</style>";
                }
            }

            if (empty($password) && empty($entry_errors)){
                $entry_errors[] = "<p class='error'>Please enter a Password</p><style>.pw{border: 1px solid #CC0000;}</style>";
            } else if (empty($entry_errors)){
                $conn = Common::connect_db();

                $password_check = "SELECT PASSWORD FROM LOGIN WHERE USERNAME LIKE '$email' AND PASSWORD LIKE '$hashed_pw'"; //Query to find duplicate emails
                $result_password = mysqli_query($conn, $password_check);
                $num_rows = mysqli_fetch_array($result_password);

                if (empty($num_rows)){
                    $entry_errors[] = "<p class='error'>Password is incorrect</p><style>.pw{border: 1px solid #CC0000;}</style>";
                }
            }

            if(empty($entry_errors)){
                $conn = Common::connect_db();
                //escapes any mysqli commands
                $email = mysqli_real_escape_string($conn, $email);

                $qry = "SELECT * FROM LOGIN WHERE USERNAME LIKE '$email' AND PASSWORD LIKE '$hashed_pw'";
                $result = mysqli_query($conn,$qry);

                $rows = mysqli_num_rows($result);
                if ($rows == 1){

                    while ($values = mysqli_fetch_assoc($result)){
                        $is_admin = (boolean)$values['ADMIN'];
                    }
                    if ($is_admin == true){
                        $cookie_name = "admin";
                        $cookie_value = $is_admin;
                        $cookie_time = time() + 3600 * 24 * 7; //setting cookie expiry time for a week
                        setcookie($cookie_name, $cookie_value, $cookie_time);
                    }

                    $_SESSION['loggedin'] = true;
                    $_SESSION['userID'] = $email;
                    $_SESSION['admin'] = $is_admin;

                    //set cookie to stay logged in if wanted


                    if ($remember_me == "true"){
                        $cookie_name = "user";
                        $cookie_value = $email;
                        $cookie_time = time() + 3600 * 24 * 7; //setting cookie expiry time for a week
                        setcookie($cookie_name, $cookie_value, $cookie_time);$cookie_name = "user";
                    }

                    header('Location: index.php'); // TODO
                }

                mysqli_close($conn);
            } else {
                foreach($entry_errors as $e){
                    echo "$e";
                }
            }
        }
        ?>
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php if(!empty($email)) echo "$email" ; ?>" class="e">

            <label for="password">Password</label>
            <input type="password" name="password" class="pw">

            <label id="rememberme"><input type="checkbox" name="remember" value="true">Stay logged in?</label>

            <input type="submit" value="Login" class="submit login">
        </form>

        <form action="register.php">
            <input type="submit" value="No account? Register!" class="submit register">
        </form>

        <form action="forgot_password.php">
            <input type="submit" value="Forgot Password?" class="submit forgot">
        </form>
    </div>

<?php
}
?>
</body>
</html>