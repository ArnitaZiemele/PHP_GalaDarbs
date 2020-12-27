<?php
    $page="register";
    
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    
    // Apstrādāt iesniegto formu
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        // Pārbaudīt lietotājvārdu
        if(empty(trim($_POST["username"]))){
            $username_err = "Lūdzu ievadiet lietotājvārdu.";
        } else{
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["username"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "Šis lietotājvārds jau ir aizņemts.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                }
            }
        }
        
        // Pārbaudīt paroli
        if(empty(trim($_POST["password"]))){
            $password_err = "Lūdzu ievadiet paroli.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Parolei jābūt vismaz 6 simbolu garumā.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Pārbaudīt atkārtoto paroli
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Lūdzu ievadiet paroli atkārtoti.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Ievadītās paroles nesakrīt.";
            }
        }
        
        // Pārbauda vai nav kļūdas, pirms pievieno datubāzei
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Izveido paroles HASH
                
                if(mysqli_stmt_execute($stmt)){
                    // Aizsūta uz redirect lapu
                    header("location: index.php?page=login");
                } else{
                    echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz...";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
?>

<div class="container" style="width: 400px">
    <h2>Reģistrēties</h2>
    <p>Lūdzu aizpildiet visus zemākos laukus.</p>
    <form action="index.php?page=register" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Lietotājvārds</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Parole</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Parole vēlreiz</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Reģistrēties">
            <input type="reset" class="btn btn-secondary" value="Atjaunot">
        </div>
        <p>Jau ir izveidots profils? <a href="index.php?page=login">Pierakstīties</a>.</p>
    </form>
</div>   