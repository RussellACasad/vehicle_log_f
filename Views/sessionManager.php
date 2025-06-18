<?php 
session_start(); 

if(!isset($minRole)){
    $minRole = "guest"; 
}

$loginRole = $_SESSION["user-role"] ??  "guest"; 

if ($minRole == 'admin')
{
    if ($loginRole == "user")
    {
        header("Location: ../User/"); 
        die; 
    }

    if ($loginRole == "guest")
    {
        header("Location: ../login.php"); 
        die; 
    }
}

if ($minRole == 'user')
{
    if ($loginRole == "guest")
    {
        header("Location: ../login.php"); 
        die; 
    }
}