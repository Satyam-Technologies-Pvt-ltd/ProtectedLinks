<?php 
include "includes/functions.php";
session_start(); $conn = dbConnect(); 

//if(isset($_SESSION['usergroup'])&& $_SESSION['usergroup']=='a')
//{//no need for checking admin
//$status='d';

//}else{
if(isset($_GET['downcode'])){

$downcode = $_GET['downcode'];
if(isBanIp())
{
header("Location: messageban.php?ern=7");
exit();	
}    
$errorno=0;
$downdata = getLinkPermissions($downcode); //Getting all  Link settings

   if($downdata != 0){
	
   $urlid=  $downdata['urlid']; //Link Database Id
   
   $url= $downdata['url'];    //Actual Url of File
   
   $usertype =  $downdata['usertype']; //Getting User Type Permitted Single=s ,Multiple=m
   
   $iprestrict =   $downdata['iprestrict']; //Ip Address Allow To Access Link
    
   $ipaccessno = $downdata['ipaccessno'];  //No Of Access By an Individual Ip Address
   
   $restrictedip=$downdata['restrictedip'];//gives restricted ip address string (comma seperated)
  
   $expnos  =  $downdata['expnos'];  //Maximum Download Attempt
   
   $exptime  = $downdata['exptime']; //Time Of Expiration of link
   
   $firstaccess   = $downdata['firstaccess']; //When The Link was  accessed 
   
   $timestamp = $downdata['timestamp']; //Time Stamp Of  creation 
   
   $notadownload = $downdata['notdl'];
   
   $exptimerespect = $downdata['exptimerespect'];  //Expiration Time  with Respect To :First Download 'F' or After Link Creation :'L'
  if(checkRestrictedIP( $restrictedip)==false)//if ip is restricted 
  {
	  //$error = "Your Ip Is Not Allowed To access This Link !";
	  
	  $errorno=1;
 
  $status = 'f'; 
	  
	  }
 else if( permissionByIP($iprestrict) == false ){ //if download attampet made  from Different Ip other then allowed
	
  //$error = "You are not allowed to Download file!";
  $errorno=2;
  $status = 'f';
 
  }
 else if( permissionByIPCount($urlid, $ipaccessno, $usertype) == false ){//if more attampt is  to be made by allowed ip 
	
 //$error = "You have exceeded the maximum number of IP Addresses Allowed";
	$errorno=3;
 $status = 'f';
	 
 }

 else if( permissionByAccessCount($urlid, $expnos, $usertype) == false ){ //if Number Of Access is more then allowed 
	
 //$error = "You have exceeded the maximum number of downloads Allowed."; 
  $errorno=4;
 $status = 'f';
  
 }
 
 else if( permissionByExpiryTime($urlid, $exptime, $timestamp, $firstaccess, $exptimerespect, $usertype ) == false ){//if time of link is expire

  // $error = "This Link has expired.";  
   $errorno=5;
   $status = 'f';
 }

 else {
    $status = 'd';
 }             
 }
 else{
	
 $status = 'w';
    
 //$error = "The file does not exist.";   
  $errorno=6;
 }
 
 if(!isset($urlid)){$urlid = 0;}
 
$referer=getfererer();

 createLog($urlid, $status,$referer ); //Creating Log 
   
 }
 
/*
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 
 <head>
 
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    
	<meta name="author" content="anupa" />
    
	<title>Download</title>
    
 </head>
 
 <body>*/ 

 if(isset($errorno)&&($errorno>0 && $errorno <8)){
	
    header("Location: messageban.php?ern=".$errorno);
	
 }
//}
// echo $status; exit;
 if( isset($status)&&( $status == 'd')){  //if Every Thing Is Fine The Download Start 

$filename = $url;
  $extension = fileexten($filename);
	
    $fakename = fakefilename($downcode);
	
  $mime = contenttype($extension);

   // set_time_limit(0);
if($notadownload==FALSE){	
    header('Pragma: public');
    header('Expires: 0'); 
    header("Content-Type:".$mime);
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Disposition: attachment;filename='.$fakename.'.'.$extension);
    header("Content-Transfer-Encoding: binary");
    header('Connection: Keep-Alive');	
//    ob_clean();
	
//    flush();
    
//   @readfile($filename);
	        $file = @fopen($filename,"rb");
			while(!feof($file))
			{
				print(@fread($file, 1024*8));
				ob_flush();
				flush();
			}

 }
 else{
 	
 	$mime = getContentType($filename);
 $mime = ($mime=='application/force-download')?"":$mime;	
    header("Content-Type:".$mime);
    
    if(strpos($mime, 'text')==FALSE){
 	  $extension = fileexten($filename);
      header('Content-Disposition: inline;filename='.$fakename.'.'.$extension);  	
    }
 	    
	echo @file_get_contents($filename);

    }
}

 ?>