<?php
session_start();
include_once("config.php");


//add product to session or create new one
if(isset($_POST["type1"]) && $_POST["type1"]=='add' && isset($_SESSION["checkin"]) && isset($_SESSION["checkout"]) )
{
	foreach($_POST as $key => $value){ //add all post vars to new_product array
		$new_product[$key] = $value;//filter_var($value, FILTER_SANITIZE_STRING);
    }
	//remove unecessary vars
	unset($new_product['type1']);
	unset($new_product['return_url']); 
	$bungalowType=$new_product['bungalow_type'];
	$new_product['check_in']=$_SESSION["checkin"];
	$new_product['check_out']=$_SESSION["checkout"];
	$checkInDate=$new_product['check_in'];
	$checkOutDate=$new_product['check_out'];
	$num_of_nights=count(createDateRangeArray($checkInDate,$checkOutDate));//number of nights
	$new_product["nights"]=$num_of_nights;
	$new_product['id']=uniqid();
	$bookin_id=$new_product['id'];
		
			
			if(mysqli_query($mysqli,"DESCRIBE temp_table")){
				$check_temp_table_exist=true;
			}
			else{
				$check_temp_table_exist=false;
			}
		
			if($check_temp_table_exist===false){
				
				$temp_booking="CREATE TABLE temp_table(booking_ID varchar(15), checkin date,checkout date, RoomNumber ENUM('A','B','C','D','E','F'), Room_type ENUM('1', '2', '3'))";
				
				if(mysqli_query($mysqli,$temp_booking)){
					echo "temp table created succussfully";
					
				}
				else{
					echo "Failed to create";
					
				}
				$inn="INSERT INTO temp_table(booking_ID, checkin, checkout, RoomNumber, Room_type) SELECT booking_ID, checkin, checkout, RoomNumber, Room_type FROM bookings";
				mysqli_query($mysqli,$inn);
			}


			$sql="SELECT checkin, checkout, RoomNumber FROM temp_table  WHERE Room_type = '$bungalowType' ORDER BY checkin ASC ";
			$result=$mysqli->query($sql);
			while($row = $result->fetch_row()){

				$a[$row['2']][]=$row;
			}
			
			foreach ($a as $key => $value) {
				$g[$key]=array();
				foreach ($value as $o=> $v) {
					
						foreach(createDateRangeArray($v[0],$v[1]) as $k){
						$g[$key][]=$k;
				}
				}	
			}
 	//we need to get product name and price from database.
    $statement = $mysqli->prepare("SELECT type, price FROM bungalow WHERE type=? LIMIT 1");
    $statement->bind_param('s', $new_product['bungalow_type']);
    $statement->execute();
    $statement->bind_result($bungalow_type, $price);
	
	while($statement->fetch()){
		
		//fetch product name, price from db and add to new_product array
        $new_product["bungalow_type"] = $bungalow_type; 
        $new_product["bungalow_price"] = $price;

        //$new_product["availability"]=(availability($checkInDate,$checkOutDate,$g));
        
        
        $_SESSION["bungalow_cart"][] = $new_product; //update or create product session with new item  !!!!!!need to reorganise this it was

       
    } 

    //insert the bungalow price and number of nights so the shopping cart can up date.
    $_SESSION["cart_total"][$new_product['id']]=calculate_numOfNights_bungalow($num_of_nights,$new_product["bungalow_price"]);
   






				$reserve_dates=(reserve_dates($checkInDate,$checkOutDate,$g));
				$count_num_nights=count($reserve_dates);
				

				if(is_array($reserve_dates)){
					foreach ($reserve_dates as $key => $value) {
					$checkin_temp =$value[0];
					$checkout_temp =$value[2];
					$RoomNumber_temp =$value[1];//A
					$booking_id=$bookin_id;//1000;//$new_product['id']; FOR TESTING PURPOSES
					$bungalow_type_temp=$bungalowType;//1;//$new_product['bungalow_type'];//FOR TESTING PURPOSES



					$sql = "INSERT INTO temp_table(booking_ID,checkin, checkout, RoomNumber, Room_type) values ('$booking_id','$checkin_temp','$checkout_temp','$RoomNumber_temp','$bungalow_type_temp') ";
			        if(mysqli_query($mysqli,$sql)){
			        	echo "inserted succussfully";
					}
				}
				}


}



function calculate_numOfNights_bungalow($nights,$price){
        $total=$nights * $price;
        return $total;
    }




function  reserve_dates($ci,$co,$un){
  	$unavailable=$un; //array possesing the unavailable dates
	$checkin=$ci;
	$checkout=$co;
	$each_bungalow=array();
	$available=1;

		foreach(createDateRangeArray($checkin,$checkout) as $p){
		$selected[]=$p;//dates the client selected

		}
		
		$availableRooms=array();

		//organises what rooms are available on each day. no input if 
		//no available rooms
		foreach ($selected as $fox => $values) {
			
			foreach ($unavailable as $keys => $value) {
				if(!in_array($values, $value)){
					$availableRooms[$values][]=$keys;
					$arr[$values]=$keys;

				}
			}
			
		}
		//checks if any combination of rooms is available on each given day, returns true 
		//if atleast one room is available on the date. False if there are no available rooms.
		foreach ($selected as $key => $value) {
			if(!array_key_exists($value, $availableRooms)){
				$available=2;

			}
			
		}
		
		
		$first_date=key($arr);//gets the key of the first value
		$num=reset($arr); //gets the first value
		
		$new_arr[]=array($first_date,$num);
		foreach ($arr as $key => $value) {
			if($value != $num){
				
				//$last_day=$key;
				$last_day=strtotime('-1 day',strtotime($key));
				$last_day=date('Y-m-d',$last_day);
				$num=$value;

				$first_date=$key;
		
				$ner=array($first_date,$num);
				$new_arr[]=$ner;
				$last[]=$last_day;
				
				

			}
		}
		end($arr);
		$pet=key($arr);
		foreach ($new_arr as $key => $value) {
			$new_arr[$key][]=isset($last[$key])? $last[$key]:$pet ;
		}
		if($available===1){
			return $new_arr;
		}
		else{
			return $available;
		}
		
}
function availability($ci,$co,$un){//returns 1 if selected dates are available, 2 is unavailable
	$unavailable=$un; //array possesing the unavailable dates
	$checkin=$ci;
	$checkout=$co;
	$available=1;

		foreach(createDateRangeArray($checkin,$checkout) as $p){
		$selected[]=$p;//dates the client selected
		}	
		$availableRooms=array();

		//organises what rooms are available on each day. no input if 
		//no available rooms
		foreach ($selected as $fox => $values) {
			
			foreach ($unavailable as $keys => $value) {
				if(!in_array($values, $value)){
					$availableRooms[$values][]=$keys;
				}
			}		
		}
		//var_dump($selected);
		//checks if any combination of rooms is available on each given day, returns true 
		//if atleast one room is available on the date. False if there are no available rooms.
		foreach ($selected as $key => $value) {
			if(!array_key_exists($value, $availableRooms)){
				$available=2;
			}	
		}
		return $available;
}


function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),    substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),         substr($strDateTo,8,2)-1,           substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}

	
	//remove an item from product session
	if(isset($_POST["remove_code"]) && is_array($_POST["remove_code"])){
		$book_id=$_POST["remove_code"];// turn this into an array
		

		foreach ($_SESSION["bungalow_cart"] as $index => $val15) {
			foreach ($book_id as $key => $value) {
				
				if($val15['id']===$value){
					$sql="DELETE FROM temp_table where booking_ID='$value' ";
					mysqli_query($mysqli,$sql);
					unset($_SESSION["bungalow_cart"][$index]);
					unset($_SESSION["cart_total"][$value]);
				}
			}
			
		}
		
		/*foreach($_POST["remove_code"] as $key){//fix here
			unset($_SESSION["bungalow_cart"][$key]);
		}*/	
	}


//back to return url
$return_url = (isset($_POST["return_url"]))?urldecode($_POST["return_url"]):''; //return url
header('Location:'.$return_url);






?>