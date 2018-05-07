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
      <form id="reset1" action="" method="post" enctype="text/plain">
        <fieldset>
          <legend>Password Reset - Step One</legend>
          
          <p class="note">Please enter your CGL username, a password reset email will be sent to the email address on file.</p>
          
          <div>
           <label for="username">User Name:</label>
           <input name="username" id="username" type="text" size="25" maxlength="100"/>
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