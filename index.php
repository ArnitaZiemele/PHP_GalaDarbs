<?php
    session_start();
    $_SESSION['activePage'] = isset($_GET['page']) ? $_GET['page'] : null;
?>

<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="styles/style.css">
        <title>Arnitas Ziemeles mājaslapa</title>
    </head>
    <body>
        <?php 
            include 'widgets/menu.php';

            if(!isset($_GET['page'])) {include 'pages/home.php';} 
            elseif($_GET['page']=='gallery') {include 'pages/gallery.php';}
            elseif($_GET['page']=='events') {include 'pages/events.php';}
            elseif($_GET['page']=='articles') {include 'pages/articles.php';}
            elseif($_GET['page']=='article') {include 'pages/article.php';}
            elseif($_GET['page']=='edit-article') {include 'pages/edit-article.php';} 
            elseif($_GET['page']=='contact') {include 'pages/contact.php';}
            elseif($_GET['page']=='account') {include 'pages/account.php';} 
            elseif($_GET['page']=='edit-profile') {include 'pages/edit-profile.php';} 
            elseif($_GET['page']=='login') {include 'pages/login.php';} 
            elseif($_GET['page']=='logout') {include 'pages/logout.php';} 
            elseif($_GET['page']=='register') {include 'pages/register.php';} 
            elseif($_GET['page']=='reset-password') {include 'pages/reset-password.php';} 
            elseif($_GET['page']=='welcome') {include 'pages/welcome.php';} 
        ?>
    </body>
</html>