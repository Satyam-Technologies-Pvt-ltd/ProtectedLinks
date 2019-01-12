<?php
include "../includes/functions.php"; //including Function File

$conn = dbConnect();
if(isset($_POST['name'])&& isset($_POST['value']) && $_POST['name']<>"" && $_POST['value']<>"")
{
$name=$_POST['name'];
//echo $name;
$value=$_POST['value'];

//echo $value;
$msg="";
switch($name)
{
case "url":
$value=addHttp($value);
if(!isValidURL($value))
{
	$msg="Your  Url is not in Correct format.";
}
else if(strpos($value, 'http://')===FALSE && strpos($value, 'ftp:')===FALSE && strpos($value, 'https:')===FALSE &&  file_exists($value) ) 
{
	$msg= "AFile";
}
else if(strpos(getContentType($value), 'text')!==FALSE||strpos(getContentType($value), 'image')!==FALSE){
		$msg= "Webpage";
}
else
{
	$msg= "Valid";
}
break;

case "file":
$path = getUploadsPathSetting(); 
$getfile = explode('\\', $_POST['value']);
$getfile = $getfile[count($getfile)-1];
$path = $path.'/'.$getfile;
$path = str_replace('//','/',$path);
echo $path;
break;

case "fileurl":
$path = getUploadsPathSetting(); 
$getfile = explode('\\', $_POST['value']);
$getfile = $getfile[count($getfile)-1];
$path = $path.'/'.$getfile;
$path = str_replace('//','/',$path);

if(file_exists($path)){
	echo 'EXISTS';
}
break;

case "pi":

if(!isPositiveInt($value))
{
$msg= "Enter only Complete Hours(eg:1,2,3..) ";
}
else
{
	$msg="Valid";
}
break;

case "allow_ip":	

$retVal= getInvalideIpList($value);
  if($retVal==false)
  {
	$msg="Valid";
  }
  else{
	$msg="Invalid IP ! Please Verify : ".$retVal;
	 break;
   }
   if(isset($_POST['rIP'])){$rIP=$_POST['rIP'];}
   else{$rIP="";}

   $retVal=matchIp($value,$rIP);
   if($retVal<>"")
   {
   $msg="Listed ip alreay in Restricted List ".$retVal;
   }
 break;

case "ip_accessno":
if(!isPositiveInt($value))
{
$msg= "Please Enter a Whole Number ";
}
else
{
	$msg="Valid";
}
break;
 
 case "res_ip":
 $retVal= getInvalideIpList($value);
  if($retVal==false)
  {
	$msg="Valid";
  }
  else{
	$msg="Invalid Ip ! Please Varify : ".$retVal;
	 break;
   }
   $aIP=$_POST['aIP'];
   $retVal=matchIp($value,$aIP);
   if($retVal<>"")
   {
   $msg="Listed ip alreay in Allow List ".$retVal;
   }
 break;
 case "allow_atmp":
 if(!isPositiveInt($value))
{
$msg= "Please Enter Whole Number ";
}
else
{
	$msg="Valid";
}
break;
default:
$msg="Nothing";

}
echo $msg;
}

?>