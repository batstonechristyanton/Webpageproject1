<?php 
require_once 'includes/library.php';
$db = new Db();
session_start();
if(!isset($_SESSION['id'])){
  header("Location: login.php");
  exit();
}

$userid = $_SESSION['id'];

$query = "SELECT * FROM gclWish WHERE fk_userID = ?";
$type = "i";
//array made to store the user information 
$param = array(&$userid);
$results = &$db->select_param($query, $type, $param);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/blitzer/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script defer src="js/fontawesome-all.js"></script>
  <script src="js/script.js"></script>
</head>

<body>
  <div id="container">
    <?php include "header.php"; ?>

    <main>
      <ol>  
        <?php while($row = $results->fetch_assoc()): ?>
          <li>
            <a href="wishdetails.php?wishid=<?php echo $row['wishID']; ?>" class="details"><?php echo $row['wish']; ?></a>
            <div id="editDelete">
             <a href="editWish.php?wishid=<?php echo $row['wishID']; ?>" class="edit"><i class="fas fa-pencil-alt fa-2x"></i></a>
             <a href="deletewish.php?wishid=<?php echo $row['wishID'];?>" class="delete"><i class="fas fa-trash fa-2x"></i></a>
           </div> <!--editDelete-->
         </li>
       <?php endwhile; ?>

     </ol>
   </main>
   
   <?php include "footer.php"; ?>
 </div>

 <div id="dialog-confirm" title="Delete Wish">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>

<div id="dialog-wish-details" title="Wish Details">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;" ></span>
 
  </body>
  </html>