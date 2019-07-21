<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header ("Location: index.php");
}
$username = $_SESSION['username'];
?>

Menu
<p>
<a href="page1.php">page1</a><br>
<a href="page2.php">page2</a><br>

<form action='action.php' method='post'>
<input type='hidden' name='action' value='logout'>
<input type='hidden' name='submit' />
<button onClick='submit();'>Logout</button>
</form>

<?
if($username == "admin"){
?>

<form action='action.php' method='post'>
<input type='hidden' name='action' value='list'>
<input type='hidden' name='submit' />
<button onClick='submit();'>List</button>
</form>

<?
}
?>
