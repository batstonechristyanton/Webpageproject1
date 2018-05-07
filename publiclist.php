<?php 
require_once 'includes/library.php';
$badurl=false;
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

if(isset($_GET['user'])){  //check for wish in url

  $user=$_GET['user'];
  $query = "select * from gclWish where fk_userID=?";
  $type='i';
    $params= array(&$user); //build parameter array

     //run query
    $result= & $db->select_param($query,$type, $params);
  } else
  $badurl=true;

  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Public List</title>
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

       <?php if($badurl): ?>
        <p>There no user identified</p>
      <?php elseif($result->num_rows <=0): ?>
       <p>This list is currently empty</p>
     <?php else: ?>


      <ol>
        <?php while($row = $results->fetch_assoc()): ?>

          <li>
            <?php if($row['bought']):?>       
             <a id="detail" href="wishdetails.php?id=<?php echo $row['wishID']; ?>" class="checked"><?php echo $row['wish']; ?></a>
             <a href="uncheck.php?id=<?php echo $row['wishID']; ?>&user=<?php echo $user; ?>" class="button">Uncheck</a>
           <?php else: ?>
            <a href="wishdetails.php?id=<?php echo $row['wishID']; ?>"><?php echo $row['wish']; ?></a>
            <a href="checkoff.php?id=<?php echo $row['wishID']; ?>&user=<?php echo $user; ?>" class="button">Checkoff</a>
          <?php endif ?>
        </li>       
      <?php endwhile; ?>
    </ol>
  <?php endif ?>
</main>

<?php include "footer.php"; ?>
</div>

<div id="dialog-wish-details" title="Wish Details">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;" ></span>

<div id="dialog-confirm3" title="Write List Password">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Write the List password to access the content</p>
  <label for="lpassword">Password:</label>
  <input name="lpassword" id="lpassword" type="password" size="25" maxlength="100" />
  <br>
  <span class="error" id="ajaxerror"></span>
</div>

</body>
</html>