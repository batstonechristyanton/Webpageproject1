 <?php
 require_once("includes/library.php");  //include library file
  $db = new Db(); //construct database object
  

  //save listPassword from GET array
  $listPass=$_GET['lpassword'];
 
  //check database for passed password
  
  //build query
  $query = "select listPassword from gclUsers where listPassword=?";
  $type='s'; //create type string
  $params= array(&$listPass); //build parameter array
  
  //run query
  $result= & $db->select_param($query,$type, $params);
  
  //ouput the number of rows so it is sent to the calling AJAX
  echo $result->num_rows;
?>