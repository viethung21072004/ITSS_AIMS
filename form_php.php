
<?php 
$Error = "";
if(isset($_POST['submit'])){
    if(empty($_POST['name'])){
        $Error = "Name is required";
    }else{
        $name = $_POST['name'];
        echo $name;
    }
}
?>

<form action="" method="post">
    Name: <input type="text" name="name">
    <br>
    <input type="submit" name="submit">
    <br>
    <?php 
    echo $Error;
    ?>
</form>