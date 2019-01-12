<?php
include "../includes/functions.php"; //including Function File
session_start(); $conn = dbConnect();  //Connecting to Database
checkLogin('../index.php');//is user is loged in if not redirect to login page
doLogout('../index.php'); //Log out redirect to Login page

if(isset($_POST['ip']))

{
  $ip=$_POST['ip'];
  $ip=trim($ip);
  $date=Date("Y-m-d H:i:s");
  if(isValidIP($ip))
  {
	  if(isBanIp($ip))
	  { $sql="DELETE FROM banip  WHERE ip='$ip'";}
	  else{
	  $sql="INSERT INTO banip (ip,date) VALUES('$ip','$date')";}
	  $res=mysqli_query($conn,$sql)or die(mysqli_error($conn));
	  header("Location:".$_SERVER['HTTP_REFERER']);
	  
   }
   
}



?>