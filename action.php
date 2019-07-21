<?php
session_start();
if (!(isset($_SESSION["login"]) && $_SESSION["login"] != "")) {
  header ("Location: index.php");
}
$username = $_SESSION["username"];
if ($username <> "admin") {
  header ("Location: menu.php");
}
?>

<html>
<title>SLS - Simple Login System</title>
<body>

<?php
include "/home/deltaboo/hairylar.sql"; //connect 

echo "SLS - Simple Login System - <a href='menu.php'>Menu</a><p>";

if(isset($_POST["submit"])){
  if($_POST["action"] == "admin"){
    $password = $_POST["password"];
    $phash = password_hash($password, PASSWORD_DEFAULT); 
    $sql = "INSERT into login (username,password) VALUES ('admin','$phash')";
    if(!$result = mysqli_query($gs,$sql)){
      echo "<br>error connecting to database trying to submit login account<br>";
    }else{  
      echo "<br>Password Saved. Please login now.<br>";
      echo "<a href=''>Login</a><br>";
      exit();
    }  
  }

  if($_POST["action"] == "newsubmit"){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $phash = password_hash($password, PASSWORD_DEFAULT); 
    $sql = "INSERT into login (username,password) VALUES ('$username','$phash')";
    if(!$result = mysqli_query($gs,$sql)){
      echo "<br>error connecting to database trying to submit login account<br>";
    }else{
      echo "Login account $username submitted.<br>";
?>

<form action='' method='post'>
<input type='hidden' name='action' value='list'>
<input type='hidden' name='submit' />
<button onClick='submit();'>List</button>
</form>

<?
    }
    exit();
  }

  if($_POST["action"] == "list"){
    $sql = "SELECT * FROM login ORDER BY username"; 
    if(!$result = mysqli_query($gs,$sql)){
      echo "<br>error connecting to database<br>";
    }else{
    echo "<table><tr><td valign=top>";
    echo "&nbsp;".$result->num_rows." Login accounts";
?>

</td><td width=20>
</td><td>

<form action='' method='post'>
<input type='hidden' name='action' value='new'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Add New Account</button>
</form>

<?
    echo "</td></tr></table><hr>";
    echo "<table><tr><td valign=top>";
      while ($row=mysqli_fetch_row($result)){
        $id = $row[0];
        $username = $row[1];
?>

&nbsp;<?=$username ?>&nbsp;
</td><td>
<form action='' method='post'>
<input type='hidden' name='action' value='edit'>
<input type='hidden' name='id' value='<?=$id ?>'>
<input type='hidden' name='username' value='<?=$username?>'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Edit</button>
</form>
</td><td>
<form action='' method='post'>
<input type='hidden' name='action' value='delete'>
<input type='hidden' name='id' value='<?=$id ?>'>
<input type='hidden' name='username' value='<?=$username ?>'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Delete</button>
</form>

<?
        echo "</td></tr><tr><td valign=top>";
      }
    }
    echo "</td></tr></table><hr>";
    exit();
  }

  if($_POST["action"] == "new"){
?>

<form name='newform' method='post' action=''>
<table border=1><tr><td align=right>
Username
</td><td>
<input type='text' name='username' id='username'>
</td></tr><tr><td align=right> 
Password
</td><td>
<input type='text' name='password' id='password'>
</td></tr></table>
<input type='hidden' name='action' value='newsubmit'>
<input type='hidden' name='submit'>
<input name='submit' type='submit' value='Submit'>
</form>

<?
    exit();
  }

  if($_POST["action"] == "edit"){
    $id = $_POST["id"];
    $username = $_POST["username"];
?>

<form id='editform' name='editform' method='post' action=''>
<table border=1><tr><td align=right>
Username
</td><td>
<input type='text' name='username' id='username' value='<?=$username?>'>
</td></tr><tr><td align=right> 
Password
</td><td>
<input type='text' name='password' id='password'>
</td></tr><tr><td>
<input type='hidden' name='action' value='editsubmit'>
<input type='hidden' name='id' value='<?=$id?>'>
<input type='hidden' name='submit' />
<input name='submit' type='submit' value='Submit'>
</td><td>
Enter new password to change it.<br>
Leave password blank to keep old password.
</form>
</td></tr></table>

<?
    exit();
  }

  if($_POST["action"] == "editsubmit"){
    $id = $_POST["id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (trim($password) <> ""){
      $phash = password_hash($password, PASSWORD_DEFAULT); 
    }  

    if (trim($password) <> ""){
      $sql = "UPDATE login SET username='$username', password='$phash' WHERE id = '$id'"; 
    }else{
      $sql = "UPDATE login SET username='$username' WHERE id = '$id'"; 
    }
    
    if(!$result = mysqli_query($gs,$sql)){
      echo "<br>error connecting to database trying to delete login account<br>";
    }else{
      echo "Login account $username updated.<br>";
?>

<form action='' method='post'>
<input type='hidden' name='action' value='list'>
<input type='hidden' name='submit' />
<button onClick='submit();'>List</button>
</form>

<?
      exit();
    }
  }

  if($_POST["action"] == "delete"){
    $id = $_POST["id"];
    $username = $_POST["username"];
    echo "Really delete $username?";
?>

<form action='' method='post'>
<input type='hidden' name='action' value='reallydelete'>
<input type='hidden' name='id' value='<?=$id ?>'>
<input type='hidden' name='username' value='<?=$username ?>'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Delete</button>
</form>

<?
  exit();
}

  if($_POST["action"] == "reallydelete"){
    $id = $_POST["id"];
    $username = $_POST["username"];

    $sql = "DELETE FROM login WHERE id = '$id'"; 
    if(!$result = mysqli_query($gs,$sql)){
      echo "<br>error connecting to database trying to delete login account<br>";
    }else{
      echo "Login account $username deleted.<br>";
      if($username == "admin"){
        session_start();
        $_SESSION["login"] = "";
?>

<form action='index.php' method='post'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Login</button>
</form>
	  
<?
      }else{
?>

<form action='' method='post'>
<input type='hidden' name='action' value='list'>
<input type='hidden' name='submit' />
<button onClick='submit();'>List</button>
</form>

<?
        exit();
      }  
    }
  }

  if($_POST["action"] == "loginsubmit"){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT password FROM login WHERE username = '$username'"; 
    if(!$result = mysqli_query($gs,$sql)){
      echo "<br>error connecting to database trying to delete login account<br>";
    }else{
      $row=mysqli_fetch_row($result);

      if (password_verify($password, $row[0])) {
        session_start();
        $_SESSION["login"] = "1";
        $_SESSION["username"] = "$username";
        header ("Location: menu.php");
      }else{
        echo "Invalid login. Please try again";
        session_start();
        $_SESSION["login"] = "";
?>

<form action='' method='post'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Login</button>
</form>

<?
      }
      exit();
    }
  }
  
  if($_POST["action"] == "logout"){
    session_start();
    $_SESSION = array();
    // delete the session cookie.
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();header("Location: loggedout.php");
  }
}	 

