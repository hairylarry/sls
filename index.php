<?php
session_start();
if (!(isset($_SESSION["login"]) && $_SESSION["login"] != "")) {
?>

<html>
<title>SLS - Simple Login System</title>
<body>

<?php
  include "/home/deltaboo/hairylar.sql"; //connect 

  echo "SLS - Simple Login System<p>";

  $sql = "SELECT * FROM login WHERE username='admin'"; 
  if(!$result = mysqli_query($gs,$sql)){
    die("There was an error running the admin query [" . $gs->error . "]");
  }
  if ($result->num_rows == 0){
    echo "No admin user. Please enter admin password now.";
?>  

<form name='adminform' method='post' action='action.php'>
<table border=1><tr><td align=right>
Username
</td><td>
admin
</td></tr><tr><td align=right> 
Password
</td><td>
<input type='text' name='password'>
</td></tr></table>
<input type='hidden' name='action' value='admin'>
<input type='hidden' name='submit' />
<input name='submit' type='submit' value='Submit'>
</form>

<?  
  }else{
    echo "Login";
?>

<form name='loginform' method='post' action='action.php'>
<table border=1><tr><td align=right>
Username
</td><td>
<input type='text' name='username' id='username'>
</td></tr><tr><td align=right> 
Password
</td><td>
<input type='text' name='password' id='password'>
</td></tr></table>
<input type='hidden' name='action' value='loginsubmit'>
<input type='hidden' name='submit' />
<input name='submit' type='submit' value='Submit'>
</form>

<?
  }
}else{
  header ("Location: menu.php");
}
?>

</body>
</html>
