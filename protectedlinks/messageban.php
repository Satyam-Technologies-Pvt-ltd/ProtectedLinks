<?php 
include "includes/functions.php";
session_start(); $conn = dbConnect(); 
//doLogout('../index.php');

//checkLogin('../index.php');
if(isset($_SESSION['usergroup'])&& $_SESSION['usergroup']=='a')
{//no need for checking admin
$status='d';

}
if(!isset($_SERVER['HTTP_REFERER']) && (!isset($_GET['ern']) || intval($_GET['ern'])<=0) )
{

$error = "Altering Default Error  Message Not Allowed";

}
else{
if(isset($_GET['ern']) && intval($_GET['ern'])>0)
{
$er_no=$_GET['ern'];
switch($er_no)
{
case 1:
$error = "Your Ip Is not allowed to access this tink !";
break;
case 2:
 $error = "You are not allowed to Download file!";
break;
case 3:
$error = "You have exceeded the maximum number of IP addresses allowed";
break;
case 4:
$error = "You have exceeded the maximum number of downloads Allowed."; 
break;
case 5:
 $error = "This link has been expired.";  
break;
case 6:
$error = "The file does not exist.";   
break;
case 7:
$error = "The file does not exist.";   
break;
case 10:
$error="Thank You For Downloading";
default:
$error = " Your Ip Is Ban To Access Any Resource on This Site ! Please Contect To Administrator";   	
}	
}}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="wrapper">
		<!--header end-->
        <div id="download1">
        	<div id="down_layout">
        	<div id="down_logo"><a href="index.html"><img src="images/logo.png" alt="protect" /></a>
            </div>
            <div id="down_main">
            	<h1 class="red">
                <?php echo $error;?>
               </h1>
                             
                </div>
            </div>
            </div>
        </div>
     
</div>
</body>
</html>