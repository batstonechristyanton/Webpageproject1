<?php
require_once("includes/library.php");  //include library file
$db = new Db();

session_start();
header("Location:login.php");
setcookie('uNameRember',"",1);
session_destroy();
?>