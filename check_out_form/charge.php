<?php
session_start();
include_once("../config.php");

$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
  <meta charset="utf-8">
  <!-- If you delete this meta tag World War Z will become a reality -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking | Maikhao Beach Bungalows</title>

  <!-- Foundation Icons -->
  <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">

  <!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
  <link rel="stylesheet" href="../../foundation/css/normalize.css">
  <link rel="stylesheet" href="../../foundation/css/foundation.css">

  <!-- If you are using the gem version, you need this only -->
  <link rel="stylesheet" href="../../foundation/css/app.css">
  <link rel="stylesheet" href="../../style.css">
  <link rel="stylesheet" href="style.css">
 
  <script src="../../foundation/js/vendor/modernizr.js"></script>

 
  
  

  <!-- Google fonts -->
  <link href='https://fonts.googleapis.com/css?family=Lobster|Satisfy|Lobster+Two:400,400italic|Montserrat:700|Dosis:300' rel='stylesheet' type='text/css'>

</head>
<body>

  <!-- navigation -->
     
        <div class="contain-to-grid sticky">
  <nav class="top-bar" data-topbar role="navigation" data-options="sticky_on: large">
  <ul class="title-area">
    <li class="name">
      <h1><a href="../index.html">Maikhao Beach Bungalows</a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>

  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
      <li><a href="../about/index.html">About</a></li>
      <li class="divider"></li>
      <li><a href="../bungalows/index.html">Bungalows</a></li>
      <li><a href="../restaurant/index.html">Ton-Tan Restaurant</a></li>
      <li><a href="../beach/index.html">Beach</a></li>
      <li><a href="../minimart/index.html">Minimart</a></li>
      <li class="divider"></li>
      <li><a href="../contact/index.html">Contact</a></li>
      <li class="active"><a href="../booking/index.php">Book Now</a></li>
      <li class="show-for-large-up"><a href=""> +66 (0)81 895 1233</a></li>
    </ul>

    <!-- Left Nav Section 
    <ul class="left">
      <li><a href="#">Left Nav Button</a></li>
    </ul>
  </section>-->
</nav></div>
<!-- end of nav -->

  <!-- Content here -->
  <div class="medium-12 columns center" >
    <div class="medium-4 columns center" >

  <h1>PAY</h1>


  <form name="checkoutForm" method="POST" action="checkout.php">
    <input type="hidden" name="description" value="" />
    <script type="text/javascript" src="https://cdn.omise.co/card.js"
      data-key="pkey_test_55h9uu7e2uhdytifkrz"
      data-image="PATH_TO_LOGO_IMAGE"
      data-frame-label="Maikhao Beach Bungalows"
      data-button-label="Pay now"
      data-submit-label="Pay"
      data-location="yes"
      data-amount="<?php echo $_SESSION["total_price"];?>"
      data-currency="thb"
      >
    </script>
    <!--the script will render <input type="hidden" name="omiseToken"> for you automatically-->
</form>
</div>
   <!-- end of content -->

  <!-- Footer -->
<footer class="margin-top-onehundred">
    <div class="medium-12 column">
      <div class="medium-4 column">
      <p>&copy; 2015</p> <h1>Maikhao Beach Bungalows </h1>
      <p>Address: 128 Moo 3, T. Maikhao, A. Thalang, Phuket, Thailand 83110</p>
    </div>
    <div class="medium-4 column">
      <p>Telephone: +66 (0)81 895 1233</p>
      <p>Email: bmaikhao_beach@hotmail.com</p>
      <a href="../../contact/index.html" class="small button">Book Now</a>
    </div>
    <div class="medium-4 column">
      <p>Copyright Information</p>
      <p>Terms and Conditions</p>
      <ul>
        <li><a href="../../about/index.html">About Us</a></li>
        <li><a href="../../bungalows/index.html">View Bungalows</a></li>
      </ul>
    </div>
    </div>
</footer>

<script src="../../foundation/js/vendor/jquery.js"></script>
  <script src="../../foundation/js/foundation.min.js"></script>
  <script>
    $(document).foundation();
</script>
<script src="https://cdn.omise.co/omise.js"></script>
<script>
  Omise.setPublicKey("PUBLIC KEY");//your public key
</script>
<script type="text/javascript">
$("#checkout").submit(function () {

  var form = $(this);

  // Disable the submit button to avoid repeated click.
  form.find("input[type=submit]").prop("disabled", true);

  // Serialize the form fields into a valid card object.
  var card = {
    "name": form.find("[data-omise=holder_name]").val(),
    "number": form.find("[data-omise=number]").val(),
    "expiration_month": form.find("[data-omise=expiration_month]").val(),
    "expiration_year": form.find("[data-omise=expiration_year]").val(),
    "security_code": form.find("[data-omise=security_code]").val()
  };

  // Send a request to create a token then trigger the callback function once
  // a response is received from Omise.
  //
  // Note that the response could be an error and this needs to be handled within
  // the callback.
  Omise.createToken("card", card, function (statusCode, response) {
    if (response.object == "error") {
      // Display an error message.
      $("#token_errors").html(response.message);

      // Re-enable the submit button.
      form.find("input[type=submit]").prop("disabled", false);
    } else {
      // Then fill the omise_token.
      form.find("[name=omise_token]").val(response.id);

      // Remove card number from form before submiting to server.
      form.find("[data-omise=number]").val("");
      form.find("[data-omise=security_code]").val("");

      // submit token to server.
      form.get(0).submit();
    };
  });

  // Prevent the form from being submitted;
  return false;

});</script>



</body>
</html>