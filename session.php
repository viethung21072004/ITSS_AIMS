<?php
    session_start();
    $_SESSION['name'] = "VHung";
  //  echo $_SESSION['name'];
    unset($_SESSION['name']);
?>