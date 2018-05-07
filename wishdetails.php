<?php
session_start();

require_once("includes/library.php");  //include library file
$db = new Db();

if(!isset($_GET['wishid'])){
  header('Location: index.php');
  exit;
}

$wishid = $_GET['wishid']; //Get the wish id from the url
$userid = $_SESSION['id']; //Get the user id from the session 

//This deletes the item by user id wish id.
$query = "SELECT * FROM gclWish WHERE  wishID = ?";
$types = 'i';
$params = array( &$wishid);
$result = $db->select_param($query, $types, $params); //Run the query 
if($result->num_rows >0){
      //fetch row of result set into associative array
  $row = $result->fetch_assoc(); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Wish Details</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">    
</head>

<main>   
  <img src='<?php echo "http://loki.trentu.ca/~tylerudall/www_data/".$row["filepath"]; ?>' alt=""/>
  <h1><?php echo $row["wish"]; ?></h1>
  <h2><?php echo "Priority: " .$row["priority"]; ?></h2>
  <p><?php echo $row["wishDesc"]; ?> </p>
  <a href="<?php echo $row["weblink"]; ?>"><?php echo $row['weblink']; ?></a>
</main>