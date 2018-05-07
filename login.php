<?php
include "includes/library.php";

$db = new DB(); 
$error = false;
$username = '';
  //Check if the cookie is set or not 
if(isset($_COOKIE['uNameRember'])){
 $username=$_COOKIE['uNameRember'];
}

if(isset($_POST['submit'])){
  $username = $_POST["username"];
  $password = $_POST["password"];
  $query = "SELECT * FROM  gclUsers WHERE username = ?";
    $type='s'; //create type string
    $params= array(&$username); //build parameter array
    //run query
    $result= &$db->select_param($query, $type, $params);
    if($result->num_rows >0){
      //fetch row of result set into associative array
      $row = $result->fetch_assoc(); 
      
      // sets the cookie for 100 days 
      if(isset($_POST['remember'])){
        setcookie('uNameRember', $username, time()+60*60*24*100);
      }
      // password verify check authentication 
      if (password_verify($password, $row['userPassword']) && $username == $row['username']) {
          //redirect to main page
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['userID'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['password'] = $row['userPassword'];
        $_SESSION['title'] = $row['listTitle'];
        $_SESSION['fromdate'] = $row['availFrom'];
        $_SESSION['todate'] = $row['availTo'];
        $_SESSION['lpass'] = $row['listPassword'];
        header('Location: index.php');
        exit();
      }else {
        $error=true;
      }  
    } 
  } 
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">    
  </head>
  <body>
    <div id="container">
      
      <?php include "header.php"; ?> 
      
      <main>
        <form id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
         <fieldset>
           <legend>Login Credentials</legend>
           
           <div>
             <label for="username" class="first">User Name:</label>
             <input name="username" id="username" type="text" size="25" value ="<?php echo $username; ?>"> 
           </div>      	
           
           <div>
             <label for="password">Password:</label>
             <input name="password" id="password" type="password" size="25" maxlength="100" />
           </div>
           <div>
             <label for="password">Remember Me:</label>
             <input name="remember" id="remember" type="checkbox" value="remember">
             
           </div>
           <a href="password.html">Forgot password</a>
         </fieldset>
         <div id="buttons">
          <button type="submit" name="submit">Login</button>
          <button type="reset" name="reset">Reset</button>
        </div>
      </form>
    </main>

    <?php include "footer.php"; ?>

  </div>
</body>
</html>
