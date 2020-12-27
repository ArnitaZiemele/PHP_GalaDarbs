<?php
    $page="login";
    
    // Pārbauda vai lietotājs jau ir pierakstījies
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php?page=welcome");
        exit;
    }

    $username = $password = "";
    $username_err = $password_err = "";
    
    // Apstrādā formas datus
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        // Pārbauda vai lietotājvārds nav tukšs
        if(empty(trim($_POST["username"]))){
            $username_err = "Lūdzu ievadiet lietotājvārdu.";
        } else{
            $username = trim($_POST["username"]);
        }
        
        // Pārbauda vai parole nav tukša
        if(empty(trim($_POST["password"]))){
            $password_err = "Lūdzu ievadiet paroli.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate credentials
        if(empty($username_err) && empty($password_err)){
            $sql = "SELECT id, role, username, password, full_name, gender, email FROM users WHERE username = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $username;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    // Pārbauda vai lietotājvārds eksistē, ja ir, tad pārbauda paroli
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        mysqli_stmt_bind_result($stmt, $id, $role, $username, $hashed_password, $full_name, $gender, $email);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                // Ja parole pareiza, sāk jaunu sesiju
                                session_start();
                                
                                // Saglabā sesijas mainīgos
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["role"] = $role;
                                $_SESSION["username"] = $username;
                                $_SESSION["full_name"] = $full_name;
                                $_SESSION["gender"] = $gender;
                                $_SESSION["email"] = $email;                         
                                
                                // Redirect uz sākumu
                                header("location: index.php");
                            } else{
                                // Error, ja parole nav pareiza
                                $password_err = "Parole ievadīta nepareizi.";
                            }
                        }
                    } else{
                        // Error, ja lietotājvārds neeksistē
                        $username_err = "Profils ar tādu lietotājvārdu neeksistē.";
                    }
                } else{
                    echo "Kaut kas nogāja greizi... Lūdzu mēģiniet vēlreiz.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
?>

<div class="container mt-3" style="width: 400px">
    <h2>Pierakstīties</h2>
    <p>Lūdzu aizpildi informāciju, lai pierakstītos.</p>
    <form action="index.php?page=login" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Lietotājvārds</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Parole</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <input type="submit" class="btn btn-primary" value="Pierakstīties">
        <p>Nav izveidots profils? <a href="index.php?page=register">Reģistrēties</a>.</p>
    </form>
</div>    