<?php
    $user='root';
    $password="";
    $host="localhost";
    $mysqli=new mysqli($host,$user,$password);
if($mysqli->connect_errno){
    die("Cannot connect database<br>\n");
}
else{
    if(!$mysqli->select_db('db_user_ASG')){
        die("Database doesn't exsist<br>\n");
    }
    
}
function header_dynamic(){
    header('Expires: Mon, 26 Jul 1997 06:00:00 GMT');
    header("Last-Modified: ".gmdate("D, d M Y H:i:s"));
    if($_SERVER["SERVER_PROTOCOL"]=="HTTP/1.0"){
        header("Pragma: no-cache");
    }
    else{
        header("Cache-control: no-cache, mut-revalidate");
    }
}
?>