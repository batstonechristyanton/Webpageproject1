<?php
  require_once("includes/library.php");  //include library file
  
  session_start();
  if(!isset($_SESSION['username'])){
    header("Location:login.php");
    exit();
  }
  $db = new Db(); //construct database object
  $name="";
  $email="";
  $username="";
  $password="";
  $password2="";
  
  $title="";
  $fromdate="";
  $todate="";
  $listPass="";
  $token ="";
  $tokenExpiry ="";
  
  if(isset($_POST['submit'])){ 

    $name=$_POST['name'];
    $email=$_POST['email'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    
    $title=$_POST['title'];
    $fromdate=$_POST['fromdate'];
    $todate=$_POST['todate'];
    $listPass=$_POST['lpassword'];
    $token ="";
    $tokenExpiry ="2018/10/23 00:00:00";
    $userId = $_SESSION['id'];
    if($password || $password2 == null)
    {
      $query = "update gclUsers set username = ?, name = ?, email = ?, listTitle = ?, availFrom = ?, availTo = ?, listPassword = ?, token = ?, tokenExpiry = ? where userID = ?";
      $type='sssssssssi'; //create type string (string,string, integer)
      
      //create parameter array
      $params= array(&$username, &$name, &$email, &$title, &$fromdate, &$todate, &$listPass, &$token, &$tokenExpiry, &$userId);
      //run query
      $db->query_param($query,$type,$params);
    }
    else if($password == $password2)
    {
      //if(!$passerror){
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $query = "update gclUsers set username = ?, name = ?, email = ?, userPassword = ?, listTitle = ?, availFrom = ?, availTo = ?, listPassword = ?, token = ?, tokenExpiry = ? where userID = ?";
    $type='ssssssssssi'; //create type string (string,string, integer)
    
    //create parameter array
    $params= array(&$username, &$name, &$email, &$hash, &$title, &$fromdate, &$todate, &$listPass, &$token, &$tokenExpiry, &$userId);
    //run query
    $db->query_param($query,$type,$params);
  }
  header("Location:index.php"); 
  exit(); 
}

    //delete account
if(isset($_POST['deleteaccount']))
{
  $query = "DELETE FROM  gclUsers WHERE userID = ?";
        $type='i'; //create type integer
        $userId = $_SESSION['id'];
        $params= array(&$userId); //build parameter array
        //run query
        $db->query_param($query, $type, $params);

        $query = "DELETE FROM gclWish WHERE fk_userID = ?";
        $type='i'; //create type integer
        $fk_userID = $_SESSION['id'];
        $params= array(&$fk_userID); //build parameter array
        //run query
        $db->query_param($query, $type, $params);
        setcookie("uNameRember","",1);
        header("Location:login.php"); 
        exit();
        
      }
    //var_dump(isset($_POST['deleteaccount']));
      ?>

      <!DOCTYPE html>
      <html lang="en">
      <head>
        <title>Edit Account</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">    
      </head>
      <body>
        <div id="container">
          
          <?php include "header.php"; ?>
          
          <main>
            <form id="editaccount" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
             <fieldset>
               <legend>User Details</legend>
               
               <div>
                 <label for="name" class="first">Name:</label>
                 <input name="name" id="name" type="text" size="25" maxlength="50" placeholder="Santa Claus" value="<?php if(isset($_SESSION['name'])){echo $_SESSION['name'];}?>" required/>
               </div> 
               
               <div>
                 <label for="email">Email:</label>
                 <input name="email" id="email" type="text" size="25" maxlength="100" placeholder="santa@northpole.org" value="<?php echo $_SESSION['email']; ?>" required/>
               </div>      	
               
               <div>
                 <label for="username">User Name:</label>
                 <input name="username" id="username" type="text" size="25" maxlength="25" value="<?php echo $_SESSION['username']; ?>" required/>
               </div>      	
               
               <p class="note">To leave password the same, leave fields blank</p>
               <div>
                 <label for="password">Password:</label>
                 <input name="password" id="password" type="text" size="25"  placeholder="*******" maxlength="100" value="" />
               </div> 
                 
               <div>
                 <label for="password2">Re-Enter Password:</label>
                 <input name="password2" id="password2" type="password" size="25"  placeholder="*******"  maxlength="100" value="" />
               </div>                 
             </fieldset>
             
             <fieldset>
               <legend>List Details</legend>
               <div>
                 <label for="title" class="first">Grownup Christmas List Title:</label>
                 <input name="title" id="title" type="text" size="25" maxlength="100" value="<?php echo $_SESSION['title']; ?>" required/>
               </div> 
               
               <div>
                 <label for="fromdate">List available from:</label>
                 <input name="fromdate" id="fromdate" type="text" value="<?php echo $_SESSION['fromdate']; ?>" required />   
               </div>   
               <div>
                 <label for="todate">List available to:</label>
                 <input name="todate" id="todate" type="text" value="<?php echo $_SESSION['todate']; ?>" required />  
               </div> 

               <div>
                 <label for="lpassword">List Password:</label>
                 <input name="lpassword" id="lpassword" type="password" size="25"  maxlength="100" value="<?php echo $_SESSION['lpass']; ?>" required/>
                 <span class="note2">A password to provide people so they can see/remove things from your list</span>
               </div>     	  
             </fieldset>
             
             <div id="buttons">
              <button type="submit" name="submit">Save Account Info</button>
              <button type="reset" name="reset">Reset</button>
              <button type="submit" name="deleteaccount" id="deleteaccount">Delete Account</button>
            </div>
          </form>
        </main>
        
        <?php include "footer.php"; ?>
        
      </div>
    </body>
    </html>