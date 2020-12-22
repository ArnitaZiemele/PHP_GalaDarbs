<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',1);

$db_host = "localhost"; // J�nomaina  
$db_user = "root";
$db_pwd = "";
$database = "privatamajaslapa";
$table = "gallery";
// jaizmanto tas pats nosaukums k� sql tabulai
$password = "123";
// vienkar�s aug�uplades ierobe�ojums,
// lai nedotu iesp�ju aug�upl�d�t visiem
if (!mysql_connect($db_host, $db_user, $db_pwd)) die("Can't connect to database");
if (!mysql_select_db($database))    die("Can't select database");
// �i funkcija izmanto 
// $_GET, $_POST, etc... main�gus
// piln��i aizsarg�ts SQL vaicajums
function  sql_safe($s){ 
 if (get_magic_quotes_gpc()) 
 $s = stripslashes($s); 
 return mysql_real_escape_string($s);
 }
 // Ja lietot�js nospie� "Submit" jebkur� no formam
 if ($_SERVER['REQUEST_METHOD'] == 'POST')
 {
 // notira nosaukumu   
 $title = trim(sql_safe($_POST['title']));
 if ($title == '')  // ja nosaukums ir tuk�   
 $title = '(empty title)';  // izmanto (empty title) 
 if ($_POST['password'] != $password)  // parbauda paroli  
 $msg = 'Error: wrong upload password'; 
 else    
 {        
 if (isset($_FILES['photo']))       
 {            
 @list(, , $imtype, ) = getimagesize($_FILES['photo']['tmp_name']); 
 // Tiek noteikts attela tips.  
 // Mes izmantojam @ lai neradas k��das    
 if ($imtype == 3)  // parbaudam attela tipu     
 $ext="png";  // lai izmantotu vel�k HTTP virsgalve  
 elseif ($imtype == 2) 
 $ext="jpeg";
 elseif ($imtype == 1) 
 $ext="gif"; 
 else               
 $msg = 'Error: unknown file format'; 
 if (!isset($msg))  // Ja nebija k�udu     
 {                
 $data = file_get_contents($_FILES['photo']['tmp_name']);    
 $data = mysql_real_escape_string($data); 
 // Sagatabojam datus priek� MySQL vaicajuma    
 mysql_query("INSERT INTO {$table}    
                  SET ext='$ext', title='$title',  
				             data='$data'"); 
 $msg = 'Success: image uploaded';   
 }        
 }        
 elseif (isset($_GET['title']))      // isset(..title) vajadz�gs  
 $msg = 'Error: file not loaded';   // lai parlicin�tos k� mes izmantojam       
 // aug�uplades formu nevis          
 // formu priek� dz��anas     
 if (isset($_POST['del']))  // Ja tika atzim�ti att�li uz dz��anu
 {                          // iek�a 'uploaded images form';   
 $id = intval($_POST['del']);  
 mysql_query("DELETE FROM {$table} WHERE id=$id");  
 $msg = 'Photo deleted';       
 }   
 }
 }
 elseif (isset($_GET['show']))
 {    
 $id = intval($_GET['show']); 
 $result = mysql_query("SELECT ext, UNIX_TIMESTAMP(image_time), data 
                              FROM {$table} WHERE id=$id LIMIT 1"); 
 if (mysql_num_rows($result) == 0)    
 die('no image');    
 list($ext, $image_time, $data) = mysql_fetch_row($result); 
 $send_304 = false;    
 if (php_sapi_name() == 'apache') {    
 // Ja web serveris ir Apache       
 // mes dabujam check HTTP        
 // If-Modified-Since uzgalve  
 // un netika s�t�ts att�ls     
 // ja ir ieke�ota versija 
 $ar = apache_request_headers(); 
 if (isset($ar['If-Modified-Since']) &&  // If-Modified-Since eksiste    
 ($ar['If-Modified-Since'] != '') &&  // nav tuk�           
 (strtotime($ar['If-Modified-Since']) >= $image_time))  // un lielaks par           
 $send_304 = true;                                         // image_time   
 }    
 if ($send_304)  
 {  
 // S�tam 304 atbildi uz parl�ku
 // "Parl�ks, jums viss ir norm�li,
 // m�s nes�tam neko jaunu"   
 header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ts).' GMT', true, 304);
 exit(); 
 }    
 // Izvadam Last-Modified uzgalvi   
 header('Last-Modified: '.gmdate('D, d M Y H:i:s', $image_time).' GMT',  true, 200);
 // termi�a beigas uzlikam +1 gads   
 // Mums nav jaunu attelu uz aug�upl�di  
 // t�p�c, parl�ks var saglab�t �o att�lu uz ilg�ku laiku 
 header('Expires: '.gmdate('D, d M Y H:i:s',  $image_time + 86400*365).' GMT',  true, 200);
 // Izvadam HTTP uzgalvi   
 header('Content-Length: '.strlen($data)); 
 header("Content-type: image/{$ext}");    
 // Izvadam att�lu   
 echo $data; 
 exit();
 }
 ?>
 <html><head><title>MySQL Blob Image Gallery Example</title></head><body>
 <?php
 if (isset($msg))  // �i sekcija ir speci�li priek�           
                     // zi�u izvades
 {
 ?>
 <p style="font-weight: bold;"><?php echo $msg?>
 <br>
 <a href="<?php echo $_SERVER['PHP_SELF']?>">reload page</a>
 <!-- Pievienotais atjauno�anas links     atjaunot POST vaicajumus nav laba ideja -->
 </p>
 <?php
 }
 ?>
 <h1>Blob image gallery</h1>
 <h2>Uploaded images:</h2>
 <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
 <!-- �i forma ir priek� att�lu dz��anas -->
 <?php
 $result = mysql_query("SELECT id, image_time, title FROM {$table} ORDER BY id DESC");
 if (@mysql_num_rows($result) == 0)   // tabula ir tuk�a  
     echo '<ul><li>No images loaded</li></ul>';
 else
 {   
 echo '<ul>';    
 while(list($id, $image_time, $title) = mysql_fetch_row($result))   
 { 
 // izvadam sarakstu       
 echo "<li><input type='radio' name='del' value='{$id}'>";     
 echo "<a href='{$PHP_SELF}?show={$id}'>{$title}</a> &ndash; ";  
 echo "<small>{$image_time}</small></li>";  
 }    
 echo '</ul>';  
 echo '<label for="password">Password:</label><br>'; 
 echo '<input type="password" name="password" id="password"><br><br>';  
 echo '<input type="submit" value="Delete selected">';
 }
 ?>
 </form><h2>Upload new image:</h2>
 <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
 <label for="title">Title:</label><br><input type="text" name="title" id="title" size="64"><br><br>
 <label for="photo">Photo:</label><br><input type="file" name="photo" id="photo"><br><br>
 <label for="password">Password:</label><br>
 <input type="password" name="password" id="password"><br><br>
 <input type="submit" value="upload"></form>
 </body>
 </html>