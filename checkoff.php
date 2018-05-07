<?php
  session_start();
  
  //build query
  $error="";
  require_once("includes/library.php");  //include library file
  $db = new Db(); //construct database object

if(isset($_GET['id'])){  //check for wish in url

  //build update query to mark wish as bought
  $query = "update gclWish set bought= 1 where wishID=?";
  $type='i';
  $params= array(&$_GET['id']); //build parameter array

   //run query
  $db->query_param($query,$type, $params);
    
  header("Location:publiclist.php?user={$_GET['user']}");
  exit();
}

?>

