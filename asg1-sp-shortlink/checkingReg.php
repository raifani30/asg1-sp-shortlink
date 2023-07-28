<?php
session_start();

require_once('./db_con.php');

if($_SERVER['REQUEST_METHOD']==='POST'){
    $error_msg=false;
    if(isset($_POST['username'])&&isset($_POST['password'])){
        echo "hallo";
        if(!empty($_POST['username'])&&!empty($_POST['password'])){
            $username=sha1($_POST['username']);
            $password=sha1($_POST['password']);
            $av_char="/[^a-z0-9A-Z@._]/";
            if(preg_match($av_char, $username)===1){
                $_SESSION['error']="Use the right input!";
                $error_msg=true;
            }
            
            $sql="SELECT userName FROM username WHERE userName=?";
            $query=$mysqli->prepare($sql);
            $hasil=$query->bind_param("s",$username);
            $query->execute();
            // $query->bind_result($user_email_data);
            $query->store_result();
            if($query->num_rows==0){
                $sql="INSERT INTO username(userName,userPass) VALUES (?,?)";
                $query=$mysqli->prepare($sql);
                $hasil=$query->bind_param("ss",$username,$password);
                $query->execute();
                if($query->affected_rows<0){
                    $message="Sign Up Failed";
                    header("location: ./reg.php?message=$message");
                }
                else{
                    $message="Sign Up Success";
                    header("location: ./index.php?message=$message");
                }
            }
            else{
                    $query->fetch();
                    $error_msg=true;
                    $_SESSION['error']="Sign Up Failed!<br>Nama telah digunakan!";
                    header("location: ./reg.php");
                    die;
            }
        }
        else if(empty($_POST['username'])||empty($_POST['password'])){
            $_SESSION['error']="The username or password has not been filled!";
            header("Location: ./reg.php");
            die;
        }
    }
    else if(!isset($_POST['username']) && !isset($_POST['password'])){
        $_SESSION['error']="The username or password has not been filled!";
        header("Location: ./reg.php");
        die;
    }
}
?>