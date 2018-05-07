<?php 
require_once 'includes/library.php';
session_start();
$db = new Db(); // database handler
//check if the user is logged in.
if(!isset($_SESSION['id'])){
	header('Location: login.php');
	exit;
}

//Check if the url variable is set 
//if not redirect back home
if(!isset($_GET['wishid'])){
	header('Location: index.php');
	exit;
}

$wishid = $_GET['wishid']; //Get the wish id from the url
$userid = $_SESSION['id']; //Get the user id from the session 

//This deletes the item by user id wish id.
$query = "DELETE FROM gclWish WHERE fk_userID = ? AND wishID = ?";
$types = 'ii';
$params = array(&$userid, &$wishid);
$db->query_param($query, $types, $params); //Run the query 
header('Location: index.php'); //Redirect back home
exit; //exit