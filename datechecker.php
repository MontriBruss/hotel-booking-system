<?php
session_start();

$checkin=$_POST['check_in'];
$checkout=$_POST['check_out'];

$_SESSION['checkin']=$checkin;
$_SESSION['checkout']=$checkout;

echo $_SESSION['checkin'];
echo "<br>".$_SESSION['checkout'];
header('Location: index.php');



?>