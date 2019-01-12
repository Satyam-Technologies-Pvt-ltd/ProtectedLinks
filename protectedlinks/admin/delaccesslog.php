<?php
include "../includes/functions.php";
session_start(); $conn = dbConnect(); 
checkLogin('../login.php');
doLogout('../login.php');
?>	
<?php
if(isset($_POST['access_id'])){
   $access_id=secure_input($_POST['access_id'],"int");
   }
   else{
	   echo '<script language="javascript">';
	
	   echo 'window.location="'.$_SERVER['HTTP_REFERER'].'";';
	   echo '</script>';
   //header("Location:".$_SERVER['HTTP_REFERER']);	
   }
  //if access_id is set Then Delete 
   $result=deleteAccessLogOnAccessId($access_id);	
 //  header("Location:".$_SERVER['HTTP_REFERER']);
    echo '<script language="javascript">';
	
	   echo 'window.location="'.$_SERVER['HTTP_REFERER'].'";';
	   echo '</script>';	
?>