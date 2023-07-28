<?php
require_once('./db_con.php');

function checkSessionValidity(){
                if($_SESSION['user_agent']!==$user_agent){
                    return false;
                }  
    } else{
        return false;
    }
?>