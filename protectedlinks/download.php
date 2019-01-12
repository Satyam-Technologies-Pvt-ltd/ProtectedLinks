<?php 
include "includes/functions.php";
session_start(); $conn = dbConnect(); 

//if(!session_start()){session_start();}
//initilizing initial settings 
$client_logo_file="";
$result_setting="";
//query to get setting
$setting_query ="select * from settings";
$result_setting=mysqli_query($conn,$setting_query) or die(mysqli_error($conn));
if(mysqli_num_rows($result_setting)) {//if any row exists in setting table
$client_logo_file=mysqli_result($result_setting,0,"logo"); //get Logo image name
$product_logo_setting=mysqli_result($result_setting,0,"product_logo"); //get product image setting to display product logo or not
}


if(isset($_GET['downcode'])&& $_GET['downcode']<>"" ){ //if download code is in url query string

$downcode = $_GET['downcode']; //get code in a variable
if(isBanIp())  //if ip is in restricted list
{
header("Location: messageban.php?ern=7"); //redirrect to message page
exit();	
}    

$downdata = getLinkPermissions($downcode); //Getting all  Link settings

   if($downdata != 0){
	
   $urlid=  $downdata['urlid']; //Link Database Id
   
   $url= $downdata['url'];    //Actual Url of File
  
   $showname=$downdata['showfilename'];
   
   $title=$downdata['title'];
   
   $description= $downdata['description']; 
 
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
   
if($notadownload==TRUE && isset($downcode)&& $downcode!=""){

	header("location:downloadbac.php?downcode=".$downcode);
}
}}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | DOWNLOAD </title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="wrapper">
		<!--header end-->
        <div id="download1">
        	<div id="down_layout">
        <div id="down_logo">
       <?php  if($product_logo_setting<> "" and strtolower($product_logo_setting)=='yes') //if settings in 
	   {//product logo
        echo  '<div id ="p_logo"><a href="admin/index.php"><img src="images/logo.png" alt="Protected Link" /></a></div>';
		//client logo
		if($client_logo_file<>"" and file_exists("images/".$client_logo_file)==true) //if client logo exist then display
		{
           echo '<div id="c_logo"><img src="images/'.$client_logo_file.'" alt ="image of logo"/></div>';
	    }
		
		}//if product logo is not set to diaplay
		else {
		if($client_logo_file<>"" and file_exists("images/".$client_logo_file)==true) //if client logo exist then display
		{
           echo '<div id="p_logo"><img src="images/'.$client_logo_file.'" alt ="image of logo"/></div>';
	    }
		
		
		}
		 ?>
		<?php 
		?>
            </div> 
           
            <div id="down_main">
            	<?php if(isset($downcode)&& $downcode!=""){
				echo '<h2>Download your file</h2>';}?>
                <div class="protect">
            <!--  <h1>Protected Links</h1>-->
              <h1><?php if(isset($title) && $title!=""){echo $title;}else if(isset($showname)){echo $showname; }?></h1>
            <!--  <ul>
                	<li><img src="images/star.png" alt="" /></li>
                    <li><img src="images/star.png" alt="" /></li>
                    <li><img src="images/star.png" alt="" /></li>
                    <li><img src="images/star.png" alt="" /></li>
                    <li><img src="images/star2.png" alt="" /></li>
                </ul>-->
                </div>
                <div class="file_download">
              
                	<?php if(isset($downcode)&& $downcode!=""){
			echo "<div class='file'><a href='downloadbac.php?downcode=".$downcode."'id='down'>Download Now</a></div>";
					}else{
					echo '<h1>File does not exist</a>';
					}
					?>
					
                    <div class="describe">
                
                    	<?php if(isset($showname)){ echo "<p>FILE:". $showname ."</p>";}?>
                        <?php if(isset( $description)){echo "<p>Description: ".$description."</p>";}?> 
                    </div>
                </div>
            </div>
            </div>
        </div>
        
        
</div>
</body>
</html>