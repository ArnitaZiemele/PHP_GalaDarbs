<?php
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        ini_set('display_errors',1);
    
        $db_host = "localhost"; 
        $db_user = "root";
        $db_pwd = "";
        $database = "privatamajaslapa";
        
        if (!mysql_connect($db_host, $db_user, $db_pwd)) die("Can't connect to database");
        if (!mysql_select_db($database))    die("Can't select database");
    
    $table = "gallery";

    function  sql_safe($s){ 
        if (get_magic_quotes_gpc()) 
            $s = stripslashes($s); 
        return mysql_real_escape_string($s);
    }
    // Ja lietotājs nospiež "Submit" jebkurā no formam
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $title = trim(sql_safe($_POST['title']));//notīra nosaukumu
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
                $msg = 'Error: unknown file format'; 
            if (!isset($msg)) {//Ja nav kļūdu                
                $data = file_get_contents($_FILES['photo']['tmp_name']);    
                $data = mysql_real_escape_string($data);
                mysql_query("INSERT INTO {$table}    
                                SET ext='$ext', title='$title',  
                                            data='$data'"); 
                $msg = 'Success: image uploaded';   
            }        
        }        
        elseif (isset($_GET['title'])) // isset(..title) vajadzīgs  
            $msg = 'Error: file not loaded';
        if (isset($_POST['del'])){ //ja attēls atzīmēts dzēšanai  
            $id = intval($_POST['del']);  
            mysql_query("DELETE FROM {$table} WHERE id=$id");  
            $msg = 'Photo deleted';       
        }
    }
    elseif (isset($_GET['show'])){    
        $id = intval($_GET['show']); 
        $result = mysql_query("SELECT ext, UNIX_TIMESTAMP(image_time), data 
                                    FROM {$table} WHERE id=$id LIMIT 1"); 
        if (mysql_num_rows($result) == 0)    
            die('no image');    
        list($ext, $image_time, $data) = mysql_fetch_row($result); 
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