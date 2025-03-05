<?php 
session_start();
if(isset($_SESSION['username'])){
   header('Location: admin.php');
}
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username == 'admin' && $password == '21072004'){
        $_SESSION['username'] = $username;
        echo "Login Success";
        header('Location: admin.php');
}
}
?>

<form action = "log_in.php" method = "post">
    Username: <input type = "text" name = "username" >
    Passwword: <input type = "password" name = "password" >
    <button type = "submit" name = "login">Login</button>
</form>


