
<?php 
require_once("includes/library.php");  //include library file
// defining the varibales for the database staoring

$errormsg=array('item' => "", 'descr' => "", 'url' => "");

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

if(isset($_POST['purchased'])){ 

  $purchased = 1;
}   
else{
 $purchased=0;
}

$query = "insert into gclWish (fk_userID, wish, priority, wishDesc, weblink,bought) values (?,?,?,?,?,?)";
$type='isissi'; 

    //create parameter array
$params= array(&$fk_userID, &$item, &$priority, &$wishDesc,&$link,&$purchased);
    //run query
$wishId = $db->insert_param($query,$type,$params);

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
  <title>Add Wish</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">    
</head>
<body>
  <div id="container">

    <?php include "header.php"; ?>

    <main>
      <form id="addwish" action="" method="post"  novalidate enctype="multipart/form-data">

        <div>
         <label for="item">Wishlist Item:</label>
         <input name="item" id="item" type="text" size="50" maxlength="255"/>
         <span class ="warning"><?php echo $errormsg['item']?></span>
       </div>      	

       <div>
        <label for="completed">Priority:</label>
        <input name="completed" id="completed" type="range" value="1" min="1" max="5" />
      </div>

      <div>        
       <label for="descr" class="tarealabel">Description:</label>
       <textarea name="descr" id="descr" cols="55" rows="15"></textarea>
       <span class ="warning"><?php echo $errormsg['descr']?></span>
     </div>

     <div>
      <label for="link">Weblink:</label>
      <input name="link" id="link" placeholder="www.onlinestore.com" type="url" size="50"/>
      <span class ="warning"><?php echo $errormsg['url']?></span>
    </div>

    <div>
      <label for="wishimage">Upload Picture</label>
      <input type="hidden" name="MAX_FILE_SIZE" value ="2097152">
      <input name="wishimage" id="wishimage" type="file">
    </div>

    <div>
      <label for = "purchased"> PURCHASED </label>
      <input type ="checkbox" name = "purchased" id ="purchased" value = "0" unchecked>
    </div>

    <div id="buttons">
      <button type="submit" name="submit">Save Wish</button>
      <button type="reset" name="reset">Reset</button>
    </div>
  </form>
</main>

<?php include "footer.php"; ?>

</div>
</body>
</html>