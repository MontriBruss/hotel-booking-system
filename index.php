<?php
session_start();
include_once("config.php");

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
  <link rel="stylesheet" href="../foundation/css/normalize.css">
  <link rel="stylesheet" href="../foundation/css/foundation.css">

  <!-- If you are using the gem version, you need this only -->
  <link rel="stylesheet" href="../foundation/css/app.css">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="style.css">

  <link rel="stylesheet" href="Pikaday/css/pikaday.css">
  <link rel="stylesheet" href="Pikaday/css/site.css">
 
  <script src="../foundation/js/vendor/modernizr.js"></script>

 
  
  

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
  
<div class="row">
    <div class="medium-9 columns">
     <form  action="datechecker.php" method="POST" id="datepicker">
     <!-- <label for="datepicker"><br></label>-->
        <div class="medium-6 columns">
                  <B>Check in</B>
                  <input type="text" id="datepicker_1" name="check_in" value="" /> 
                          
        </div>
        <div class="medium-6 columns">
                  <B>Check Out</B>
                  <input type="text" id="datepicker_2" name="check_out" value=""/>  
                  <input type="submit" value="Show files"/>
                          
        </div>
      </form>
    </div>

</div>  
<div class="row">
  
    <div class="medium-9 columns">
    <!-- View Cart Box Start -->
     <h1 align="center">Bungalows </h1>
    <!-- Products List Start -->
    <?php
            $results = $mysqli->query("SELECT type, description, img_loc, price FROM bungalow ORDER BY id ASC");
            if($results){ 
                      if(isset($_SESSION["check_in"]) && isset($_SESSION["check_out"])) {
                        $cin= $_SESSION["check_in"];
                        $cout=$_SESSION["check_out"];

                      } 
           
            //fetch results set as object and output HTML
            while($obj = $results->fetch_object())
            { 
               
                $bungalows_item = <<<EOT
                <div class="medium-4 columns">        
                <form method="post" action="cart_update.php">
                <div class="bungalows-content"><h3>{$obj->type}</h3>         
                <div class="bungalows-thumb"><img src="{$obj->img_loc}"></div>
                <div class="bungalows-desc">{$obj->description}</div>
                Price {$currency}{$obj->price} 

                <input type="hidden" name="bungalow_type" value="{$obj->type}" />
                <input type="hidden" name="type1" value="add" />
                <input type="hidden" name="return_url" value="{$current_url}" />
                <div align="center"><button type="submit" class="bungalow_cart">Add</button></div>
                </div>                                                   
                </form>

                </div>                                              
EOT;

                echo $bungalows_item;
                
                }
                

         }
    ?> 
    
</div>
  <div class="medium-3 columns end">
      
 <?php

    if(isset($_SESSION['bungalow_cart']) && count($_SESSION["bungalow_cart"])>0){
        

        echo '<div class="cart-view-table-front" id="view-cart">';
        echo '<h3>Your Booking Cart</h3>';
        echo '<form method="post" action="cart_update.php">';
        echo '<table width="100%"  cellpadding="6" cellspacing="0">';
        echo '<tbody>';

        $total =0;
        $b = 0;
        //filling the session variables
        foreach ($_SESSION["bungalow_cart"] as $cart_itm)
                {
                  
                  $booking_id=$cart_itm["id"];
                   $delete_index=key($_SESSION["bungalow_cart"]);
                  //$delete_index=key($cart_itm);
                  $bungalow_type = $cart_itm["bungalow_type"];
                  $bungalow_price = $cart_itm["bungalow_price"];
                  $checkin=$cart_itm["check_in"];
                  $checkout=$cart_itm["check_out"];
                  $nights=$cart_itm["nights"];
                  //$num_rooms = $cart_itm["num_rooms"];
                  //$available=$cart_itm["availability"];
                  $bg_color = ($b++%2==1) ? 'odd' : 'even'; //zebra stripe
                  echo '<tr class="'.$bg_color.'">';
                  echo '<td>'.$booking_id.'</td>';
                  //echo '<td><B>No.Rooms</b> <br>'.$num_rooms.'</td>';
                  echo '<td><B>Bungalow</B> <br>'.$bungalow_type.'</td>';
                  echo '<td><B>Price</B> <br>'.$bungalow_price.'</td>';
                  echo '<td><B>In</B> <br>'.$checkin.'</td>';
                  echo '<td><B>Out</B> <br>'.$checkout.'</td>';
                  //echo '<td><B>available</B> <br>'.$available.'</td>';
                 
                 
                  echo '<td><input type="checkbox" name="remove_code[]" value="'.$booking_id.'" /> Remove</td>';
                 // echo '<input type="hidden" name="delete_booking[]" value="'.$booking_id.'" />'; 
                  echo '</tr>';
                  //$subtotal = ($bungalow_price * $num_rooms);
                  //$total = ($total + $subtotal);
                  /*echo '<tr>';
                  echo '<td>subtotal<br>'. $subtotal.'</td>';
                  echo '</tr>';
                  echo '<tr>';
                  echo '<td>total<br>'. $total.'</td>';
                  echo '</tr>';*/
                   
                }



        echo '<tr>'; 

          echo '<td>';
          echo '<button type="submit">Update</button>';
          echo '</td>';

          echo '<td>';
          echo '<a href="check_out_form/form.php" class="button">Checkout</a>';
          echo '</td>';

          echo '<td>';
          echo "Total: ".$currency.calculate_total();
          echo '</td>';

        echo "</tr>";

        echo '</tbody>';
        echo '</table>';
        
        $current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
        echo '</form>';
        echo '</div>';

    }
    $_SESSION["total_price"]=calculate_total()*100;

    function calculate_total(){
      $total=0;
      foreach ($_SESSION["cart_total"] as $key => $value) {
       $total=$total+$value;
      }
      return $total;
    }



    ?>
    <!-- View Cart Box End -->

  </div>

 
</div>



<div class="row margin-top-twenty">
  <div class="medium-12 columns">
    <h1>Google Maps</h1>
    <img src="http://placehold.it/900x350" />
  </div>
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
      <a href="../contact/index.html" class="small button">Book Now</a>
    </div>
    <div class="medium-4 column">
      <p>Copyright Information</p>
      <p>Terms and Conditions</p>
      <ul>
        <li><a href="../about/index.html">About Us</a></li>
        <li><a href="../bungalows/index.html">View Bungalows</a></li>
      </ul>
    </div>
    </div>
</footer>

  <!-- Scripts below -->

  <script src="../foundation/js/vendor/jquery.js"></script>
  <script src="../foundation/js/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
  <script src="moment.js"></script>
  <script src="Pikaday/pikaday.js"></script>
   <script>

    var picker1 = new Pikaday(
    {
        field: document.getElementById('datepicker_1'),
        format: 'YYYY-MM-DD',
        firstDay: 1,
        minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
   
     var picker2 = new Pikaday(
    {
        field: document.getElementById('datepicker_2'),
        format: 'YYYY-MM-DD',
        firstDay: 1,
        minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
   
  

    </script>
</body>
</html>