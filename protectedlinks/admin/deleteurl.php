<?php
include "../includes/functions.php";
session_start(); $conn = dbConnect(); 
checkLogin('../login.php');
doLogout('../login.php');
?>	
<?php

if(isset($_POST['urlid'])){
   $urlid=secure_input($_POST['urlid'],"int");
   }
   else{
	//header("Location:".$_SERVER['HTTP_REFERER']);
	 echo '<script language="javascript">';
	
	   echo 'window.location="'.$_SERVER['HTTP_REFERER'].'";';
	   echo '</script>';
   }
  
   $result=deleteLink($urlid);	
   if($result==true){  //if link  deleted successfully then delete accesslog and redirect to manage page   
	deleteAccessLogOnUrl($urlid);
    //header("Location:".$_SERVER['HTTP_REFERER']);
	 echo '<script language="javascript">';
	
	   echo 'window.location="'.$_SERVER['HTTP_REFERER'].'";';
	   echo '</script>';	
	}
	else{ //if result not deleted retrun to manage page with status 0
	//header("Location:"."manage.php");
	 echo '<script language="javascript">';
	
	   echo 'window.location="'.$_SERVER['HTTP_REFERER'].'";';
	   echo '</script>';	
	}



?>