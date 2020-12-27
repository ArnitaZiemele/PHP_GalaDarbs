<?php
    $page="reset-password";
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: index.php?page=login");
        exit;
    }
    
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Pārbaudīt paroli
        if(empty(trim($_POST["new_password"]))){
            $new_password_err = "Lūdzu ievadiet jauno paroli.";     
        } elseif(strlen(trim($_POST["new_password"])) < 6){
            $new_password_err = "Parolei jābūt vismaz 6 simbolus garai.";
        } else{
            $new_password = trim($_POST["new_password"]);
        }
        
        //Pārbaudīt atkārtoto paroli
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Lūdzu apstipriniet paroli.";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)){
                $confirm_password_err = "Paroles nesakrīt.";
            }
        }
            
        // Pārbaudīt pirms ievada datubāzē
        if(empty($new_password_err) && empty($confirm_password_err)){
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                
                if(mysqli_stmt_execute($stmt)){
                    // Parole veiksmīgi noaminīta. Beidz sesiju un redirekto uz login lapu
                    session_destroy();
                    header("location: index.php?page=login");
                    exit();
                } else{
                    echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
?>

<div class="container mt-3" style="width: 400px">
    <h2>Nomainīt paroli</h2>
    <p>Lūdzu aizpildiet laukus, lai nomainītu paroli</p>
    <form action="index.php?page=reset-password" method="post"> 
        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
            <label>Jaunā parole</label>
            <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
            <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Jaunā parole vēlreiz</label>
            <input type="password" name="confirm_password" class="form-control">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Mainīt">
            <a class="btn btn-secondary" href="index.php?page=profile">Atcelt</a>
        </div>
    </form>
</div> 