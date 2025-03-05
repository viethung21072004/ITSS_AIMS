<?php
// echo $_POST['name'];
// echo "<br>";
// echo $_POST['gmail'];
//echo $_POST['submit'];
// echo date('Y-m-d H:i:s');
// echo readfile('form_php.php');
$in = fopen('read.txt','r');
echo fread($in,filesize('read.txt'));
?>