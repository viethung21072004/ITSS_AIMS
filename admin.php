<?php
session_start();
if(!isset($_SESSION['username'])){
    header('Location: log_in.php');
}
?>

<h1>Admin Page</h1>
<h1>Admin Page</h1>
<form action="log_out.php" method="post">
    <button type="submit" name="logout">Logout</button>
</form>

