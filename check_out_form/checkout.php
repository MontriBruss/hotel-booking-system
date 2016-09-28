<?php
session_start();

require_once '/omise-php-master/lib/Omise.php';
define('OMISE_API_VERSION', '2015-11-17');
// define('OMISE_PUBLIC_KEY', 'PUBLIC_KEY');
// define('OMISE_SECRET_KEY', 'SECRET_KEY');
define('OMISE_PUBLIC_KEY', 'PUBLIC_KEY');//YOUR PUBLIC KEY
define('OMISE_SECRET_KEY', 'SECRET_KEY');//YOUR SECRET KEY

$charge = OmiseCharge::create(array(
  'amount' => $_SESSION["total_price"],
  'currency' => 'thb',
  'card' => $_POST["omiseToken"]
));

if ($charge['status'] == 'successful') {
  echo 'Success';
  echo "<br> An email has been sent to.";
} else {
  echo 'Fail';
}

echo "<button>Return Home</button>";

var_dump($_SESSION["bungalow_cart"]);
//print('<pre>');
//print_r($charge);
//print('</pre>');
?>