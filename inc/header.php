<html>
<head>
    <title> <?php echo $page_title; ?></title>

    <link rel="stylesheet" type="text/css" href="style/normalise.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/base.css">

    <?php
    /*    foreach($stylesheet as $style){
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style/".$style.".css">"";
        }
        */
    ?>

</head>
<body>
<header>

    <div class="title">
        <h1>PHP e-Shop</h1>
    </div>

    <?php include('module_login.php'); ?>

    <div class="cf"></div>

</header>
<nav>
    <ul>
        <li><a href="index.php"> Home</a></li>
        <li><a href="search.php">Search</a></li>
        <li><a href="basket.php">Basket</a></li>

        <?php
        if (!empty($_SESSION['loggedin'])) {
            if ($_SESSION['loggedin'] == true) {
                echo "<li><a href='account.php'>Your account</a></li>";
            }
        }


        if (($_COOKIE['admin'] == 1) || ($_SESSION['admin'] == 1)) {
            echo "<li><a href='admin.php'>Admin Options</a></li>";
        }
        ?>
    </ul>
</nav>
<div class="cf"></div>