<?php
/**
 * @author: Bradley Page
 */

require($_SERVER['DOCUMENT_ROOT'] . '/assignment2/src/require.php');
session_start();

$page_title = "Control Panel";

include("inc/header.php");

if (isset($_COOKIE['user'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = $_COOKIE['user'];
}
if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)) {
    $_SESSION['admin'] = 1; // so the session is definitely set i.e. session has ended but cookies are set.
    $is_admin = true;
}

if (!empty($_SESSION['loggedin'])) {
    if ($_SESSION['loggedin'] == true) {
        $conn = Common::connect_db();

        $users_email = $_SESSION['userID'];

        $get_det = "SELECT * FROM CUSTOMER_DETAILS WHERE USERNAME LIKE '$users_email'";
        $result = mysqli_query($conn, $get_det);

        $user_details = mysqli_fetch_row($result);
        // Assigning user details to vars
        $u_e = $user_details[0];
        $u_fn = $user_details[1];
        $u_sn = $user_details[2];
        $u_a1 = $user_details[3];
        $u_a2 = $user_details[4];
        $u_a3 = $user_details[5];
        $u_pc = $user_details[6];
        $u_no = $user_details[7];
        ?>
        <div class="body-box">
            <h1> Welcome <?php
                echo $u_fn;
                ?>! </h1>

            <table id="user-details">
                <tr>
                    <td>First Name:</td>
                    <td><?php
                        echo $u_fn;
                        ?></td>
                </tr>
                <tr>
                    <td>Surname:</td>
                    <td><?php
                        echo $u_sn;
                        ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php
                        echo $u_e;
                        ?></td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td><?php
                        echo $u_no;
                        ?></td>
                </tr>
                <tr>
                    <td>Address 1:</td>
                    <td><?php
                        echo $u_a1;
                        ?></td>
                </tr>
                <tr>
                    <td>Address 2:</td>
                    <td><?php
                        echo $u_a2;
                        ?></td>
                </tr>
                <tr>
                    <td>Address 3:</td>
                    <td><?php
                        echo $u_a3;
                        ?></td>
                </tr>
                <tr>
                    <td>Post Code:</td>
                    <td><?php
                        echo $u_pc;
                        ?></td>
                </tr>
            </table>
            <BR>

            <form action="change_details.php">
                <input type="submit" value="Anything wrong? Change Details" class="submit">
            </form>
            <form action="logout.php">
                <input type="submit" value="Not <?php
                echo $u_fn;
                ?>? Logout" class="submit">
            </form>
        </div>

    <?php
    } else {
        echo "Error";
        session_destroy();
    }

} else {
    header('Location: index.php?notLoggedIn=true');
}
?>
</body>
</html>