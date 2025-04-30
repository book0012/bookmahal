<?php
ob_start(); // Output buffering start
session_start();
session_unset(); 
session_destroy();
header("Location: home.php"); 
exit();
ob_end_flush(); // Output buffering end
?>
