 <?php
 require_once("includes/library.php");  //include library file
  $db = new Db(); //construct database object
  
  //save email from GET array
  $username=$_GET['username'];
  
  //check database for passed username
  
  //build query
  $query = "select username from gclUsers where username =?";
  $type='s'; //create type string
  $params= array(&$username); //build parameter array
  
  //run query
  $result= & $db->select_param($query,$type, $params);
  
  //ouput the number of rows so it is sent to the calling AJAX
  echo $result->num_rows;
  ?>