<?php 
    $page="profile";

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php?page=login");
        exit;
    }
?>

<div class="container" style="width: 400px">
    <h4 class="mb-0 mt-0"><?php echo $_SESSION['full_name']; ?></h4><br>
    <span><?php echo $_SESSION['gender']; ?></span><br><br>
    <span><?php echo $_SESSION['email']; ?></span><br><br>
    <a href="index.php?page=reset-password" class="btn btn-sm btn-outline-primary">Nomainīt paroli</a>
    <a href="index.php?page=profile-edit" class="btn btn-sm btn-primary">Labot profilu</a>
</div>