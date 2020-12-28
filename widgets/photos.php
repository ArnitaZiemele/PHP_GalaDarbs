<?php  
    $table = "gallery";
    require_once "config.php";
    $s="";
    // Ja lietotājs nospiež "Submit" jebkurā no formam
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(array_key_exists('title', $_POST)) {//notīra nosaukumu
            if (get_magic_quotes_gpc()) 
                $s = stripslashes($$_POST['title']); 
            $title = trim(mysqli_real_escape_string($link, $s));
        }
        else {
            $title = "";
        }
        if ($title == '')  // ja nosaukums ir tukšs   
            $title = '(empty title)';  // izmanto (empty title)        
        if (isset($_FILES['photo'])){            
            @list(, , $imtype, ) = getimagesize($_FILES['photo']['tmp_name']); 
            if ($imtype == 3)  // parbaudam attela tipu     
                $ext="png"; //lai uzsetotu attēla linku
            elseif ($imtype == 2) 
                $ext="jpeg";
            elseif ($imtype == 1) 
                $ext="gif"; 
            else               
                $msg = 'Nezināms faila formāts'; 
            if (!isset($msg)) {//Ja nav kļūdu                
                $data = file_get_contents($_FILES['photo']['tmp_name']);    
                $data = mysqli_real_escape_string($link, $data);
                $sql = "INSERT INTO {$table} SET ext='$ext', title='$title', data='$data'";
                mysqli_query($link, $sql) or die('cannot get results!');
                $msg = 'Attēls veiksmīgi augšupielādēts';   
            }        
        }        
        elseif (isset($_GET['title'])) // isset(..title) vajadzīgs  
            $msg = 'Fails nav ielādēts';
        if (isset($_POST['del'])){ //ja attēls atzīmēts dzēšanai  
            $id = intval($_POST['del']);  
            $sql = "DELETE FROM {$table} WHERE id=$id";
            mysqli_query($link, $sql) or die('cannot get results!'); 
            header('location: ../index.php?page=gallery');
            $msg = 'Attēls izdzēsts';       
        }
    }
    elseif (isset($_GET['show'])){    
        $id = intval($_GET['show']); 
        $sql = "SELECT ext, UNIX_TIMESTAMP(image_time), data 
        FROM {$table} WHERE id=$id LIMIT 1";
        $result = mysqli_query($link, $sql) or die('cannot get results!');
        if (mysqli_num_rows($result) == 0)    
            die('no image');    
        list($ext, $image_time, $data) = mysqli_fetch_row($result); 
        $send_304 = false;    
        if (php_sapi_name() == 'apache') {
            $ar = apache_request_headers(); 
            if (isset($ar['If-Modified-Since']) &&  // If-Modified-Since eksiste    
            ($ar['If-Modified-Since'] != '') &&  // nav tukšs          
            (strtotime($ar['If-Modified-Since']) >= $image_time))  // un lielaks par           
                $send_304 = true;                                         // image_time   
        }    
        if ($send_304) { 
            header('Last-Modified: '.gmdate('%e.%m.%Y H:i:s', $ts).' GMT', true, 304);
            exit(); 
        }
        header('Last-Modified: '.gmdate('%e.%m.%Y H:i:s', $image_time).' GMT',  true, 200);// Izvadam Last-Modified uzgalvi 
        header('Expires: '.gmdate('%e.%m.%Y H:i:s',  $image_time + 86400*365).' GMT',  true, 200);
        header('Content-Length: '.strlen($data)); // Izvadam HTTP uzgalvi   
        header("Content-type: image/{$ext}");    
        echo $data; // Izvadam attēlu 
        exit();
    }
 ?>