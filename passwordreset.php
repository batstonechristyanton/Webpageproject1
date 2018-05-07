<!DOCTYPE html>
<html lang="en">
<head>
  <title>Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">    
</head>
<body>
  <div id="container">
   
   <?php include "header.php"; ?> 
   
   <main>
    <form action="" method="post"  id="reset2" enctype="text/plain">
      <fieldset>
        <legend>Password Reset - Step Two</legend>
        
        
        <div>
         <label for="password">Please enter your new password:</label>
         <input name="password" id="password" type="text" size="25" maxlength="100"/>
       </div>    
       <div>
         <label for="password2">Please re-enter your new password:</label>
         <input name="password2" id="password2" type="text" size="25" maxlength="100"/>
       </div>     	
       
     </fieldset> 
     
     <div id="buttons">
      <button type="submit" name="submit">Reset Password</button>
      <button type="reset" name="reset">Reset</button>
    </div>
  </form>
</main>

<?php include "footer.php"; ?>

</div>
</body>
</html>