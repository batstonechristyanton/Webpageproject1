<?php 
require_once("includes/library.php");  //include library file
// defining the varibales for the database staoring

$errormsg=array('item' => "", 'descr' => "", 'url' => "");

$newname = "";
$names="";
$id="";
$wish="";
$priority="";
$wishDesc="";
$weblink="";
$wishimage="";
$purchased="";

session_start();
if (!isset($_SESSION['id'])){
 header("Location:login.php");
 exit();
}

if(isset($_GET['wishid'])){

 $db = new Db();
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
}

if(isset($_POST['submit'])){
 $db = new Db();
 $emptyUrl = "***Field Can't Be Empty";
 $wrongUrl = "***URL is invalid";
 $emptyItem = "***Enter an Item";
 $emptyDescr = "***Enter a Description";

 if($_POST['item'] != ""){
  $Item = htmlspecialchars(htmlentities($_POST['item']));
}

else{
  $errormsg['item'] = $emptyItem;
}

if($_POST['descr'] !=""){
  $Descr = htmlspecialchars(htmlentities($_POST['descr']));
}

else{
  $errormsg['descr'] = $emptyDescr;
}

if($_POST['link'] != ""){
 $Link = filter_var($_POST['link'], FILTER_SANITIZE_URL);
 $Link= filter_var($Link, FILTER_VALIDATE_URL);
}

else if($_POST['link'] == ""){
  $errormsg['url'] = $wrongUrl;
}

else{
  $errormsg['url'] = $emptyUrl;
} 


    //creating database construct
 $fk_userID=$_SESSION['id'];
 $item=$_POST['item'];
 $priority=$_POST['completed'];
 $wishDesc=$_POST['descr'];
 $link=$_POST['link'];
 $wishId = $_POST['wishId'];


 if(isset($_POST['purchased'])){ 

  $purchased = 1;
}   
else{
 $purchased=0;
}

$query = "update gclWish set fk_userID = ?, wish=?, priority=?, wishDesc=?, weblink=?,bought =? where wishid =?";
$type='isissii'; 

    //create parameter array
$params= array(&$fk_userID, &$item, &$priority, &$wishDesc,&$link,&$purchased,&$wishId);
    //run query
$db->query_param($query,$type,$params);

$path = WEBROOT."www_data/";

if(is_uploaded_file($_FILES['wishimage']['tmp_name'])){
  $newname = createFilename('wishimage', $path, 'wishPic', $wishId);
  checkAndMoveFile('wishimage', 100000, $newname);
}

$names = explode('/', $newname);

$name = $names[count($names) -1];

$query = "update gclWish set filepath = '{$name}' where wishId =" .$wishId; 
$db -> query($query);


$_SESSION['wish'] = $row['wish'];
$_SESSION['priority'] = $row['priority'];
$_SESSION['desc'] = $row['wishDesc'];
$_SESSION['link'] = $row['weblink'];
$_SESSION['path'] = $row['filepath'];
$_SESSION['bought'] = $row['bought'];

header("Location:index.php"); 
exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit Wish</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">    
</head>
<body>
  <div id="container">

    <?php include "header.php"; ?>
    
    <main>
      <form id="addwish" action="" method="post"  novalidate>
        <input type="hidden" name="wishId" value="<?php echo $wishid ?> ">
  <div>
   <label for="item">Wishlist Item:</label>
   <input name="item" id="item" type="text" size="50" value="<?php echo $row['wish'] ?>" maxlength="255"/>
   <span class ="warning"><?php echo $errormsg['item']?></span>
 </div>      	

 <div>
  <label for="completed">Priority:</label>
  <input name="completed" id="completed" type="range" value="<?php echo $row["priority"]; ?>" min="1" max="5" />
</div>

<div>        
 <label for="descr" class="tarealabel">Description:</label>
 <textarea name="descr" id="descr" cols="55" rows="15"><?php echo $row["wishDesc"]; ?></textarea>
 <span class ="warning"><?php echo $errormsg['descr']?></span>
</div>

<div>
  <label for="link">Weblink:</label>
  <input name="link" id="link" placeholder="www.onlinestore.com" type="url" value="<?php echo $row['weblink']; ?>" size="50"/>
  <span class ="warning"><?php echo $errormsg['url']?></span>
</div>

<div>
  <label for="wishimage">Upload Picture</label>
  <input type="hidden" name="MAX_FILE_SIZE" value ="<?php echo "http://loki.trentu.ca/~tylerudall/www_data/".$row["filepath"]; ?>">
  <input name="wishimage" id="wishimage" type="file">
</div>

<div>
  <label for = "purchased"> PURCHASED </label>
  <input type ="checkbox" name = "purchased" id ="purchased" value = "<?php echo $row['bought']; ?>" unchecked>
</div>

<div id="buttons">
  <button type="submit" name="submit">Edit Wish</button>
  <button type="reset" name="reset">Reset</button>
</div>
</form>
</main>

<?php include "footer.php"; ?>

</div>
</body>
</html>