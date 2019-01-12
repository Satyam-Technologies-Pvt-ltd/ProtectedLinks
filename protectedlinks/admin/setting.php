<?php

include "../includes/functions.php"; //including Function File
session_start(); $conn = dbConnect();  //Connecting to Database
checkLogin('../index.php');//is user is loged in if not redirect to login page
doLogout('../index.php'); //Log out redirect to Login page

///getting settings
$query_settings_old="Select * from settings LIMIT 1";
$result_settings_old=mysqli_query($conn,$query_settings_old)or die(mysqli_error($conn));
if(mysqli_num_rows($result_settings_old)){
$logo=mysqli_result($result_settings_old,0,"logo");
$procuct_logo=mysqli_result($result_settings_old,0,"product_logo");
$default_search=mysqli_result($result_settings_old,0,"default_search");
$accesstimef=mysqli_result($result_settings_old,0,"accesstime_f");
$usehtaccessf=mysqli_result($result_settings_old,0,"usehtaccess_f");
$default_tz=mysqli_result($result_settings_old,0,"default_timezone");
$uploads_path=mysqli_result($result_settings_old,0,"uploads_path");
////////////////////////
}

if(isset($_POST['submit']))
{

      //user Logo Image uploader 
if (isset($_FILES["c_logo"]) && $_FILES["c_logo"]["name"])
{

if(isValidImageFile($_FILES["c_logo"]["name"])==true)
{

$ext=getFileExtension($_FILES["c_logo"]["name"]);

$filename=time();
$filename=$filename.$ext;

move_uploaded_file($_FILES["c_logo"]["tmp_name"],"../images/".$filename);
//image size validation
list($width, $height) =getimagesize("../images/".$filename);
if($width<=252 and $height <=52)
{
///
$udate_logo_query="update settings set logo='$filename'";
$res=mysqli_query($conn,$udate_logo_query) or die(mysqli_error($conn));
$msg_clogo="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved </span/>";
}else{$error_clogo="<span class='smspan'><img src='../images/error_icon.png' alt='' />Image size must be 252 *52 or less.! </span/>";
 unlink("../images/".$filename);
 }
}else{
//$msg="Please Upload a valid image file";
$error_clogo="<span class='smspan'><img src='../images/error_icon.png' alt='' />Please Upload a valid image file.!</span> ";


}
}

         //setting Product Image  display or not
if(isset($_POST['p_logo']))
{
$udate_product_query="update settings set product_logo='yes'";
$msg_plogo="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";
}
else{
$udate_product_query="update settings set product_logo='no'";
$msg_plogo="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";
}

$res=mysqli_query($conn,$udate_product_query) or die(mysqli_error($conn));//

if(isset($_POST['s_field']))
{
if($_POST['s_field']!==$default_search)
{
$search_field=secure_input($_POST['s_field'],"string");
$query_search_opt="update  settings set default_search='$search_field'";
$res=mysqli_query($conn,$query_search_opt) or die(mysqli_error($conn));
$msg_search="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";

}
}

if(isset($_POST['tz_field']) && $default_tz !== $_POST['tz_field']){

$timezone_field=secure_input($_POST['tz_field'],"string");
$query_timezone_opt="update  settings set default_timezone='{$timezone_field}'";
$res=mysqli_query($conn,$query_timezone_opt) or die(mysqli_error($conn));
$msg_timezone="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";	
}
if(isset($_POST['accesstime_f']) && $accesstimef !== $_POST['accesstime_f'] ){

$accesstimef_field=secure_input($_POST['accesstime_f'],"string");

$query_accesstimef_opt="update  settings set accesstime_f='{$accesstimef_field}'";
$res=mysqli_query($conn,$query_accesstimef_opt) or die(mysqli_error($conn));
$msg_accesstimef="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";	
}

if(isset($_POST['use_htaccess']) && $usehtaccessf !== $_POST['use_htaccess'] ){

$usehtaccessf_field=$_POST['use_htaccess'];

$getfolders = currentpageurl();
$getfolders = str_replace('http://','', $getfolders);
 $getfolders = str_replace('setting.php','', $getfolders);

$getfolders = explode('/',$getfolders); 
 array_pop($getfolders);
 array_pop($getfolders);
$getfolders[0]='';
 $getfolders = implode('/', $getfolders);

$query_usehtaccessf_opt="update  settings set usehtaccess_f='{$usehtaccessf_field}'";
$res=mysqli_query($conn,$query_usehtaccessf_opt) or die(mysqli_error($conn));
$msg_usehtaccessf="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";	
}


if(isset($_POST['uploads_path']) && $uploads_path !== $_POST['uploads_path'] ){

$uploads_path_field=secure_input($_POST['uploads_path'],"string");

$query_uploads_path_opt="update  settings set uploads_path='{$uploads_path_field}'";
$res=mysqli_query($conn,$query_uploads_path_opt) or die(mysqli_error($conn));
$msg_uploads_path="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Saved! </span/>";	
}

}
///getting settings
$query_settings="Select * from settings LIMIT 1";
$result_settings=mysqli_query($conn,$query_settings)or die(mysqli_error($conn));
if(mysqli_num_rows($result_settings)){
$logo=mysqli_result($result_settings,0,"logo");
$procuct_logo=mysqli_result($result_settings,0,"product_logo");
$default_search=mysqli_result($result_settings,0,"default_search");
$accesstimef=mysqli_result($result_settings,0,"accesstime_f");
$default_tz=mysqli_result($result_settings,0,"default_timezone");
$uploads_path=mysqli_result($result_settings,0,"uploads_path");
////////////////////////

$old_pass="";
$new_pass="";
$con_pas="";

if(isset($_POST['oldpass']) && ($_POST['oldpass']<>""))

{$old_pass=$_POST['oldpass'];}//if old posted old password is not blank initilize them to a variable
if(isset($_POST['new_psw'])&& $_POST['new_psw']<>"")

{$new_pass=secure_input($_POST['new_psw'],"string");} //new password is not blank then initilize to a variable

if(isset($_POST['con_psw']) && $_POST['con_psw']<>"")

{$con_pas=$_POST['con_psw'];} 

///checking blank entry


if( trim($old_pass)<>"" or trim($new_pass)<>""){

	if(trim($new_pass)==trim($con_pas)) {//if poasetd new password and confirmed password matched
	

	$sts=changePassWord($_SESSION['username'],$old_pass,$new_pass,'userlogin'); //change the password 
	
	if($sts==true){ //display a success message
	
//	echo "<br/>new pass word " .$_POST['con_psw'];
	
 //   echo "<br/>varification password ".$_POST['con_psw'];
	
 //   echo "<br/>old password ".$_POST['oldpass'];
	
	$msg_pass="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Your settings has been saved4.! </span/>";}
		
	else{ //if change password fail send error messsage
	
	$error_pass="<span class='smspan'><img src='../images/error_icon.png' alt='' />Your Current Password is not correct.!</span> ";}
			
		
	}
else { //new password not matched to confirmend then send an error message
	
		$error_pass="<span class='smspan'><img src='../images/error_icon.png' alt='' />Please confirm your password.!</span>";}
		
		
	} //end of trim($new_pass)==trim($con_pas)




/////////


}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | SETTINGS </title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.all.css">
<script src="../jquery/jquery-1.7.1.js"></script>
<script src="../jquery/jquery.ui.core.js"></script>
<script src="../jquery/jquery.ui.widget.js"></script>
<script src="../jquery/jquery.ui.datepicker.js"></script>
   <script language="javascript" src="js.js"></script>

<script language="javascript">
	$(function() {
	
		$( "#datepicker" ).datepicker({
			dateFormat: 'dd-mm-yy',
			showOn: "button",
			buttonImage: "../images/calendar_icon.png",
			buttonImageOnly: true
		});
		$( "#datepicker1" ).datepicker({
			dateFormat: 'dd-mm-yy',
			showOn: "button",
			buttonImage: "../images/calendar_icon.png",
			buttonImageOnly: true
		});
		//css hack for ie8
		$("#lower_header table td:last-child").css("width","90px");
		$("#lower_header table td:last-child").css(" margin","0 0 0 20px");
		$("#lower_header table td:last-child").css(" border","1px solid #eaeaea");
		$("#lower_header table input:last-child").css("width","90px");
		$("#lower_header table input:last-child").css("padding","0 10px 0 10px");
		//
	});
	</script>
   
	</head>

<body>
<div id="wrapper">
  <div id="header">
        		<div id="top_feader">
                	<div id="logo">
                    	<a href="index.php"><img src="../images/logo.png" alt="" /></a>
                    </div>
                    <div id="wel_admin">
                    	<h4>Welcome <?php echo $_SESSION['username'];?></h4>
                    	<ul>
                    	<li><a href="changepassword.php" title="Settings"><img src="../images/settings_icon.png" alt="Log out" /></a></li>
                    		<li><a href="accesslogs.php?logout=yes" title="Logout"><img src="../images/icon2.png" alt="" /></a></li>
                    	</ul>
                  </div>
                </div>
                <div id="navigation">
                	<div id="left_nav">
                    	<ul>
                        	<li><a href="index.php">Home</a></li>
                            <li><img src="../images/nav_line.jpg" alt="" /></li>
                            <li><a href="createurl.php" class="active">Create Link</a></li>
                            <li><img src="../images/nav_line.jpg" alt="" /></li>
                            <li><a href="manage.php">Manage Link</a></li>
                            <li><img src="../images/nav_line.jpg" alt="" /></li>
                            <li><a href="accesslogs.php">Access Logs</a></li>
                            <li><img src="../images/nav_line.jpg" alt="" /></li>
                        </ul>
                    </div>
                    <div id="right_nav">
                         <div id="download"><strong>Downloads</strong></div>
                         <div class="count">
                         	<span>Today</span>
                            <div class="count1"><?php echo numberOfDownloas("today");?></div>
                         </div> 
                         <div class="count">
                         	<span>This week</span>
                            <div class="count2"><?php echo numberOfDownloas("week");?></div>
                         </div>   
                    </div>
                </div>
                 <?php include("search.php");?>
		</div><!--header end-->

  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"  name= "frmmain" enctype="multipart/form-data" onsubmit="return ValidatonFinal();">
      <div id="layout">
        	<div id="layouthd">
            <div id="heading">
        	

            </div>
            <div id="heading" style="margin-bottom:10px;">
        	<h2>setting</h2>
            </div>
           <div align='left' style='margin:0 0 0 300px;'>
            <?php if(isset($error)){echo $error ;}else if( isset($msg) ) {echo $msg ;}?>
            </div>
           <h3>Logo Settings</h3>
            	<div id="table_top-set">
            	  <table width="100%"  border="0">
                  <tr>  <?php 
						  if(file_exists("../images/".$logo) and $logo <>"")
						  {
						  echo "<tr>";
                          echo" <td>Preview Logo</td>";
                          echo "<td>";
						  echo "<img src='../images/".$logo."' height='52px' width='252px'/>";
						  echo "</td>"; 
						  }
						  
						  ?>
                          </tr>
                          <tr height="10px;"></tr>
				    <tr>
                    
   						  <td width="90" height="49">Your Logo</td>
				    <td width="334" onmouseout="showfilename()"><label for="textfield" ></label>
					      <!--<input type="file" name="c_logo"  />-->
                           <div id="c_browse" style="height: 34px; width:297px; cursor:pointer;" onclick="getFile()"><input type="text" id="s_file" class="f_upload" readonly="readonly"/> <span class="button_browse">Browse</span></div>
    <!-- this is your file input tag, so i hide it!--> 
   <div style='height: 0px;width:0px; overflow:hidden;'><input id="upfile" type="file" value="upload" name= "c_logo" /></div>
    <!-- here you can have file submit button or you can write a simple script to upload the file automatically--></td>
   						  <td width="343"><div id="msg_url"><?php if(isset($msg_clogo)){echo $msg_clogo;}else if(isset($error_clogo)){echo $error_clogo;}?></div></td>
  						</tr>
                        
                        
  						<tr>
    					  <td height="45"><p></p></td>
    					  <td> <div class="table3"><input type="checkbox" name="p_logo" value="yes" <?php if(strtolower($procuct_logo)=='yes'){echo 'checked="checked"';}?> id="p_logo_chbx" />
                           <span>Display Protected Link Logo on Download Page </span></div>
                       
    					  <td><div id="msg_fname" ><?php if(isset($msg_clogo)){echo $msg_plogo;}else if(isset($error_plogo)){echo $error_plogo;}?></div></td>
  						</tr>
                
                        
					</table>

          </div>
          
  <h3><b>Your Time Zone</b></h3>
                <div id="restriction">
                 <table border="0">
                      <tr>
                       	  <td width="127" height="81">Default Time Zone</td>
					    <td width="366">
                        
                        <div class='divonsetting'>
                      <div class="sty-set">
					
<select name="tz_field" >
    <option value="Etc/UTC" label="UTC (Coordinated Universal Time)" <?php if($default_tz == 'Etc/UTC'){echo 'selected="selected"';} ?> >UTC (Coordinated Universal Time)</option>
    <option value="Europe/London" label="UTC (Coordinated Universal Time) Dublin, Edinburgh, London" <?php if($default_tz == 'Europe/London'){echo 'selected="selected"';} ?> >UTC (Coordinated Universal Time) Dublin, Edinburgh, London</option>
    <option value="Africa/Casablanca" label="UTC (no DST) Tangiers, Casablanca" <?php if($default_tz == 'Africa/Casablanca'){echo 'selected="selected"';} ?> >UTC (no DST) Tangiers, Casablanca</option>
    <option value="Europe/Lisbon" label="UTC+00:00 Lisbon" <?php if($default_tz == 'Europe/Lisbon'){echo 'selected="selected"';} ?> >UTC+00:00 Lisbon</option>
    <option value="Africa/Algiers" label="UTC+01:00 Algeria" <?php if($default_tz == 'Africa/Algiers'){echo 'selected="selected"';} ?> >UTC+01:00 Algeria</option>
    <option value="Europe/Berlin" label="UTC+01:00 Berlin, Stockholm, Rome, Bern, Brussels" <?php if($default_tz == 'Europe/Berlin'){echo 'selected="selected"';} ?> >UTC+01:00 Berlin, Stockholm, Rome, Bern, Brussels</option>
    <option value="Europe/Paris" label="UTC+01:00 Paris, Madrid" <?php if($default_tz == 'Europe/Paris'){echo 'selected="selected"';} ?> >UTC+01:00 Paris, Madrid</option>
    <option value="Europe/Prague" label="UTC+01:00 Prague, Warsaw" <?php if($default_tz == 'Europe/Prague'){echo 'selected="selected"';} ?> >UTC+01:00 Prague, Warsaw</option>
    <option value="Europe/Athens" label="UTC+02:00 Athens, Helsinki, Istanbul" <?php if($default_tz == 'Europe/Athens'){echo 'selected="selected"';} ?> >UTC+02:00 Athens, Helsinki, Istanbul</option>
    <option value="Africa/Cairo" label="UTC+02:00 Cairo" <?php if($default_tz == 'Africa/Cairo'){echo 'selected="selected"';} ?> >UTC+02:00 Cairo</option>
    <option value="EET" label="UTC+02:00 Eastern Europe" <?php if($default_tz == 'EET'){echo 'selected="selected"';} ?> >UTC+02:00 Eastern Europe</option>
    <option value="Africa/Harare" label="UTC+02:00 Harare, Pretoria" <?php if($default_tz == 'Africa/Harare'){echo 'selected="selected"';} ?> >UTC+02:00 Harare, Pretoria</option>
    <option value="Asia/Jerusalem" label="UTC+02:00 Israel" <?php if($default_tz == 'Asia/Jerusaleme'){echo 'selected="selected"';} ?> >UTC+02:00 Israel</option>
    <option value="Asia/Baghdad" label="UTC+03:00 Baghdad, Kuwait, Nairobi, Riyadh" <?php if($default_tz == 'Asia/Baghdad'){echo 'selected="selected"';} ?> >UTC+03:00 Baghdad, Kuwait, Nairobi, Riyadh</option>
    <option value="Asia/Tehran" label="UTC+03:30 Tehran" <?php if($default_tz == 'Asia/Tehran'){echo 'selected="selected"';} ?> >UTC+03:30 Tehran</option>
    <option value="Asia/Tbilisi" label="UTC+04:00 Abu Dhabi, Muscat, Tbilisi, Kazan" <?php if($default_tz == 'Asia/Tbilisi'){echo 'selected="selected"';} ?> >UTC+04:00 Abu Dhabi, Muscat, Tbilisi, Kazan</option>
    <option value="Asia/Yerevan" label="UTC+04:00 Armenia" <?php if($default_tz == 'Asia/Yerevan'){echo 'selected="selected"';} ?> >UTC+04:00 Armenia</option>
    <option value="Europe/Moscow" label="UTC+04:00 Moscow, St. Petersburg, Volgograd" <?php if($default_tz == 'Europe/Moscow'){echo 'selected="selected"';} ?> >UTC+04:00 Moscow, St. Petersburg, Volgograd</option>
    <option value="Asia/Kabul" label="UTC+04:30 Kabul" <?php if($default_tz == 'Asia/Kabul'){echo 'selected="selected"';} ?> >UTC+04:30 Kabul</option>
    <option value="Asia/Karachi" label="UTC+05:00 Islamabad, Karachi" <?php if($default_tz == 'Asia/Karachi'){echo 'selected="selected"';} ?> >UTC+05:00 Islamabad, Karachi</option>
    <option value="Asia/Tashkent" label="UTC+05:00 Tashkent" <?php if($default_tz == 'Asia/Tashkent'){echo 'selected="selected"';} ?> >UTC+05:00 Tashkent</option>
    <option value="Asia/Calcutta" label="UTC+05:30 Mumbai, Kolkata, Chennai, New Delhi"  <?php if($default_tz == 'Asia/Calcutta'){echo 'selected="selected"';} ?> >UTC+05:30 Mumbai, Kolkata, Chennai, New Delhi</option>
    <option value="Asia/Katmandu" label="UTC+05:45 Kathmandu, Nepal" <?php if($default_tz == 'Asia/Katmandu'){echo 'selected="selected"';} ?> >UTC+05:45 Kathmandu, Nepal</option>
    <option value="Asia/Almaty" label="UTC+06:00 Almaty, Dhaka" <?php if($default_tz == 'Asia/Almaty'){echo 'selected="selected"';} ?> >UTC+06:00 Almaty, Dhaka</option>
    <option value="Asia/Yekaterinburg" label="UTC+06:00 Sverdlovsk" <?php if($default_tz == 'Asia/Yekaterinburg'){echo 'selected="selected"';} ?> >UTC+06:00 Sverdlovsk</option>
    <option value="Asia/Bangkok" label="UTC+07:00 Bangkok, Jakarta, Hanoi" <?php if($default_tz == 'Asia/Bangkok'){echo 'selected="selected"';} ?> >UTC+07:00 Bangkok, Jakarta, Hanoi</option>
    <option value="Asia/Omsk" label="UTC+07:00 Omsk, Novosibirsk" <?php if($default_tz == 'Asia/Omsk'){echo 'selected="selected"';} ?> >UTC+07:00 Omsk, Novosibirsk</option>
    <option value="Asia/Shanghai" label="UTC+08:00 Beijing, Chongqing, Urumqi" <?php if($default_tz == 'Asia/Shanghai'){echo 'selected="selected"';} ?> >UTC+08:00 Beijing, Chongqing, Urumqi</option>
    <option value="Australia/Perth" label="UTC+08:00 Hong Kong SAR, Perth, Singapore, Taipei" <?php if($default_tz == 'Australia/Perth'){echo 'selected="selected"';} ?> >UTC+08:00 Hong Kong SAR, Perth, Singapore, Taipei</option>
    <option value="Asia/Krasnoyarsk" label="UTC+08:00 Krasnoyarsk" <?php if($default_tz == 'Asia/Krasnoyarsk'){echo 'selected="selected"';} ?> >UTC+08:00 Krasnoyarsk</option>
    <option value="Asia/Irkutsk" label="UTC+09:00 Irkutsk (Lake Baikal)" <?php if($default_tz == 'Asia/Irkutsk'){echo 'selected="selected"';} ?> >UTC+09:00 Irkutsk (Lake Baikal)</option>
    <option value="Asia/Tokyo" label="UTC+09:00 Tokyo, Osaka, Sapporo, Seoul" <?php if($default_tz == 'Asia/Tokyo'){echo 'selected="selected"';} ?> >UTC+09:00 Tokyo, Osaka, Sapporo, Seoul</option>
    <option value="Australia/Adelaide" label="UTC+09:30 Adelaide" <?php if($default_tz == 'Australia/Adelaide'){echo 'selected="selected"';} ?> >UTC+09:30 Adelaide</option>
    <option value="Australia/Darwin" label="UTC+09:30 Darwin" <?php if($default_tz == 'Australia/Darwin'){echo 'selected="selected"';} ?> >UTC+09:30 Darwin</option>
    <option value="Australia/Brisbane" label="UTC+10:00 Brisbane" <?php if($default_tz == 'Australia/Brisbane'){echo 'selected="selected"';} ?> >UTC+10:00 Brisbane</option>
    <option value="Pacific/Guam" label="UTC+10:00 Guam, Port Moresby" <?php if($default_tz == 'Pacific/Guam'){echo 'selected="selected"';} ?> >UTC+10:00 Guam, Port Moresby</option>
    <option value="Australia/Sydney" label="UTC+10:00 Sydney, Melbourne" <?php if($default_tz == 'Australia/Sydney'){echo 'selected="selected"';} ?> >UTC+10:00 Sydney, Melbourne</option>
    <option value="Asia/Yakutsk" label="UTC+10:00 Yakutsk (Lena River)" <?php if($default_tz == 'Asia/Yakutsk'){echo 'selected="selected"';} ?> >UTC+10:00 Yakutsk (Lena River)</option>
    <option value="Australia/Hobart" label="UTC+11:00 Hobart" <?php if($default_tz == 'Australia/Hobart'){echo 'selected="selected"';} ?> >UTC+11:00 Hobart</option>
    <option value="Asia/Vladivostok" label="UTC+11:00 Vladivostok" <?php if($default_tz == 'Asia/Vladivostok'){echo 'selected="selected"';} ?> >UTC+11:00 Vladivostok</option>
    <option value="Pacific/Kwajalein" label="UTC+12:00 Eniwetok, Kwajalein" <?php if($default_tz == 'Pacific/Kwajalein'){echo 'selected="selected"';} ?> >UTC+12:00 Eniwetok, Kwajalein</option>
    <option value="Pacific/Fiji" label="UTC+12:00 Fiji Islands, Marshall Islands" <?php if($default_tz == 'Pacific/Fiji'){echo 'selected="selected"';} ?> >UTC+12:00 Fiji Islands, Marshall Islands</option>
    <option value="Asia/Kamchatka" label="UTC+12:00 Kamchatka" <?php if($default_tz == 'Asia/Kamchatka'){echo 'selected="selected"';} ?> >UTC+12:00 Kamchatka</option>
    <option value="Asia/Magadan" label="UTC+12:00 Magadan, Solomon Islands, New Caledonia" <?php if($default_tz == 'Asia/Magadan'){echo 'selected="selected"';} ?> >UTC+12:00 Magadan, Solomon Islands, New Caledonia</option>
    <option value="Pacific/Auckland" label="UTC+12:00 Wellington, Auckland" <?php if($default_tz == 'Pacific/Auckland'){echo 'selected="selected"';} ?> >UTC+12:00 Wellington, Auckland</option>
    <option value="Pacific/Apia" label="UTC+13:00 Apia (Samoa)" <?php if($default_tz == 'Pacific/Apia'){echo 'selected="selected"';} ?> >UTC+13:00 Apia (Samoa)</option>
    <option value="Atlantic/Azores" label="UTC-01:00 Azores, Cape Verde Island" <?php if($default_tz == 'Atlantic/Azores'){echo 'selected="selected"';} ?> >UTC-01:00 Azores, Cape Verde Island</option>
    <option value="Atlantic/South_Georgia" label="UTC-02:00 Mid-Atlantic" <?php if($default_tz == 'Atlantic/South_Georgia'){echo 'selected="selected"';} ?> >UTC-02:00 Mid-Atlantic</option>
    <option value="America/Buenos_Aires" label="UTC-03:00 E Argentina (BA, DF, SC, TF)" <?php if($default_tz == 'America/Buenos_Aires'){echo 'selected="selected"';} ?> >UTC-03:00 E Argentina (BA, DF, SC, TF)</option>
    <option value="America/Fortaleza" label="UTC-03:00 NE Brazil (MA, PI, CE, RN, PB)" <?php if($default_tz == 'America/Fortaleza'){echo 'selected="selected"';} ?> >UTC-03:00 NE Brazil (MA, PI, CE, RN, PB)</option>
    <option value="America/Recife" label="UTC-03:00 Pernambuco" <?php if($default_tz == 'America/Recife'){echo 'selected="selected"';} ?> >UTC-03:00 Pernambuco</option>
    <option value="America/Sao_Paulo" label="UTC-03:00 S &amp; SE Brazil (GO, DF, MG, ES, RJ, SP, PR, SC, RS)" <?php if($default_tz == 'America/Sao_Paulo'){echo 'selected="selected"';} ?> >UTC-03:00 S &amp; SE Brazil (GO, DF, MG, ES, RJ, SP, PR, SC, RS)</option>
    <option value="America/St_Johns" label="UTC-03:30 Newfoundland" <?php if($default_tz == 'America/St_Johns'){echo 'selected="selected"';} ?> >UTC-03:30 Newfoundland</option>
    <option value="America/Halifax" label="UTC-04:00 Atlantic Time (Canada)" <?php if($default_tz == 'America/Halifax'){echo 'selected="selected"';} ?> >UTC-04:00 Atlantic Time (Canada)</option>
    <option value="America/La_Paz" label="UTC-04:00 La Paz" <?php if($default_tz == 'America/La_Paz'){echo 'selected="selected"';} ?> >UTC-04:00 La Paz</option>
    <option value="America/Caracas" label="UTC-04:30 Caracas" <?php if($default_tz == 'America/Caracas'){echo 'selected="selected"';} ?> >UTC-04:30 Caracas</option>
    <option value="America/Bogota" label="UTC-05:00 Bogota, Lima" <?php if($default_tz == 'America/Bogota'){echo 'selected="selected"';} ?> >UTC-05:00 Bogota, Lima</option>
    <option value="America/New_York" label="UTC-05:00 Eastern Time (US &amp; Canada)" <?php if($default_tz == 'America/New_York'){echo 'selected="selected"';} ?> >UTC-05:00 Eastern Time (US &amp; Canada)</option>
    <option value="America/Indiana/Indianapolis" label="UTC-05:00 Eastern Time - Indiana - most locations" <?php if($default_tz == 'America/Indiana/Indianapolis'){echo 'selected="selected"';} ?> >UTC-05:00 Eastern Time - Indiana - most locations</option>
    <option value="America/Chicago" label="UTC-06:00 Central Time (US &amp; Canada)" <?php if($default_tz == 'America/Chicago'){echo 'selected="selected"';} ?> >UTC-06:00 Central Time (US &amp; Canada)</option>
    <option value="America/Indiana/Knox" label="UTC-06:00 Eastern Time - Indiana - Starke County" <?php if($default_tz == 'America/Indiana/Knox'){echo 'selected="selected"';} ?> >UTC-06:00 Eastern Time - Indiana - Starke County</option>
    <option value="America/Mexico_City" label="UTC-06:00 Mexico City, Tegucigalpa" <?php if($default_tz == 'America/Mexico_City'){echo 'selected="selected"';} ?> >UTC-06:00 Mexico City, Tegucigalpa</option>
    <option value="America/Managua" label="UTC-06:00 Nicaragua" <?php if($default_tz == 'America/Managua'){echo 'selected="selected"';} ?> >UTC-06:00 Nicaragua</option>
    <option value="America/Regina" label="UTC-06:00 Saskatchewan" <?php if($default_tz == 'America/Regina'){echo 'selected="selected"';} ?> >UTC-06:00 Saskatchewan</option>
    <option value="America/Phoenix" label="UTC-07:00 Arizona" <?php if($default_tz == 'America/Phoenix'){echo 'selected="selected"';} ?> >UTC-07:00 Arizona</option>
    <option value="America/Denver" label="UTC-07:00 Mountain Time (US &amp; Canada)" <?php if($default_tz == 'America/Denver'){echo 'selected="selected"';} ?> >UTC-07:00 Mountain Time (US &amp; Canada)</option>
    <option value="America/Los_Angeles" label="UTC-08:00 Pacific Time (US &amp; Canada); Los Angeles" <?php if($default_tz == 'America/Los_Angeles'){echo 'selected="selected"';} ?> >UTC-08:00 Pacific Time (US &amp; Canada); Los Angeles</option>
    <option value="America/Tijuana" label="UTC-08:00 Pacific Time (US &amp; Canada); Tijuana" <?php if($default_tz == 'America/Tijuana'){echo 'selected="selected"';} ?> >UTC-08:00 Pacific Time (US &amp; Canada); Tijuana</option>
    <option value="America/Nome" label="UTC-09:00 Alaska" <?php if($default_tz == 'America/Nome'){echo 'selected="selected"';} ?> >UTC-09:00 Alaska</option>
    <option value="Pacific/Honolulu" label="UTC-10:00 Hawaii" <?php if($default_tz == 'Pacific/Honolulu'){echo 'selected="selected"';} ?> >UTC-10:00 Hawaii</option>
    <option value="Pacific/Midway" label="UTC-11:00 Midway Island, Samoa" <?php if($default_tz == 'Pacific/Midway'){echo 'selected="selected"';} ?> >UTC-11:00 Midway Island, Samoa</option>
</select>
                      </div>  
                      </div>


</td>
                        <td width="383">
              <div class="table3">
                            <!--<img src="../images/information_icon.png" alt="" /><span>Ex: 1 means permit First IP Address accessing the Link.</span>--></div>
                         <div id="msg_ipaccessno" ><?php if(isset($msg_timezone)){echo $msg_timezone;}else if(isset($error_timezone)){echo $error_plogo;}?></div></td> 
			       </tr>
				  </table>
              </div>          
          
              <h3><b>Setting </b></h3>
                <div id="restriction">
                 <table border="0">
                      <tr>
                       	  <td width="127" height="81">Default Search Option </td>
					    <td width="366">
                        
                        <div class='divonsetting'>
                      <div class="sty-set">
					
                      <select name="s_field" >
 
                      <option value="pfile" <?php if(strtolower($default_search)=='pfile'){echo "selected='selected'";}?>>Profile Name</option>
                      <option value="dcode" <?php if(strtolower($default_search)=='dcode'){echo "selected='selected'";}?>>Download Code</option>
                      <option value="file" <?php if(strtolower($default_search)=='file'){echo "selected='selected'";}?>>File Name</option>
                      <option value="ip" <?php if(strtolower($default_search)=='ip'){echo "selected='selected'";}?>>IP Address</option>
                      </select>
                      </div>  
                      </div>


</td>
                        <td width="383">
              <div class="table3">
                            <!--<img src="../images/information_icon.png" alt="" /><span>Ex: 1 means permit First IP Address accessing the Link.</span>--></div>
                         <div id="msg_ipaccessno" ><?php if(isset($msg_search)){echo $msg_search;}else if(isset($error_search)){echo $error_plogo;}?></div></td> 
			       </tr>
			       
 <tr>                   
   						  <td width="135" height="49">Access Time Format</td>
				    <td width="366">
					<div class='divonsetting'>
				     <div class="sty-set">
				                            <select name="accesstime_f" >
 					 <option value="" <?php if($accesstimef==''){echo "selected='selected'";}?>>Default</option>
                      <option value="Y-m-d H:i:s" <?php if($accesstimef=='Y-m-d H:i:s'){echo "selected='selected'";}?>>2012-03-24 17:45:12</option>
                      <option value="l jS F Y g:ia" <?php if($accesstimef=='l jS F Y g:ia'){echo "selected='selected'";}?>>Saturday 24th March 2012 5:45pm</option>
                      <option value="d/m/Y H:i:s" <?php if($accesstimef=='d/m/Y H:i:s'){echo "selected='selected'";}?>>24/03/2012 17:45:12</option>
                      <option value="Y-m-d h:i:s" <?php if($accesstimef=='Y-m-d h:i:s'){echo "selected='selected'";}?>>2014-02-13 02:42:48</option>
                      
                       <option value="l jS F Y h:i:s A" <?php if($accesstimef=='l jS F Y h:i:s A'){echo "selected='selected'";}?>>Thursday 29th May 2014 11:21:09 AM</option>
                      <option value="F j, Y, g:i a" <?php if($accesstimef=='F j, Y, g:i a'){echo "selected='selected'";}?>>March 10, 2001, 5:16 pm</option>
                      <option value="D, d M Y H:i:s O" <?php if($accesstimef=='D, d M Y H:i:s O'){echo "selected='selected'";}?>>Thu, 29 May 2014 11:21:09 +0300</option>
                      
                      </select> </div>
					  <td width="384"><div id="msg_url"><?php if(isset($msg_accesstimef)){echo $msg_accesstimef;}else if(isset($error_accesstimef)){echo $error_accesstimef;}?></div></div></td>
					  </tr>	
 <tr>                   
   						  <td width="135" height="49">Uploads Path</td>
				    <td width="357">
	 <input type="textfield" name="uploads_path" id="textfield"   class="loginput" value="<?php if($uploads_path==''){echo '../../../pldata';}else{echo $uploads_path;} ?>" />
</div>
					  <td width="384"><div id="msg_url"><?php if(isset($msg_uploads_path)){echo $msg_uploads_path;}else if(isset($error_uploads_path)){echo $error_uploads_path;}?></td>
					  </tr>	
 <tr>                   
   						  <td width="135" height="49">Use Htaccess</td>
				    <td width="357">
<span>	 <input type="radio" name="use_htaccess" id="radio"   class="radio" value="1" />Yes &nbsp;&nbsp;</span>
	 <span>	 <input type="radio" name="use_htaccess" id="radio"   class="radio" value="0" />No</span>
</td>
					  <td width="384"><div id="msg_url"><?php if(isset($msg_usehtaccessf)){echo $msg_usehtaccessf;}else if(isset($error_use_htaccess)){echo $error_use_htaccess;}?></td>
					  </tr>						  					 				  		       
				  </table>
              </div>
                  
<h3>Change Password</h3>
                  <div id="restriction">
                  	<table width="100%"  border="0">
				    <tr>
   						  <td width="135" height="49">Current Password</td>
				    <td width="357"><label for="textfield"></label>
				        <input type="password" name="oldpass" id="textfield"  class="loginput"/>
					  <td width="384"><div id="msg_url"><?php if(isset($msg_pass)){echo $msg_pass;}else if(isset($error_pass)){echo $error_pass;}?></div></td>
					  </tr>
  						<tr>
    					  <td height="45"><p>New Password</p></td>
    					  <td><label for="textfield"></label>
   					   <input type="password" name="new_psw" id="textfield" class="loginput" /></td>
    					  <td><div id="msg_fname" ></div></td>
  						</tr>
                        <tr>
                          <td height="45">Confirm New Password</td>
                          <td><input type="password" name="con_psw" id="textfield" class="loginput"/></td>
                        
                        </tr>
                        
					</table>
                      
                  </div>
                  
                  
                  
                  <div id="submit">
                      <table width="294" border="0">
                      <tr>
                   	    <td width="148" height="36"></td>
					    
                      <td width="242">
                   	   <input type="submit" value="save" name="submit"/>
                         </td>
   						  
					  </tr>
				  </table>
                  
              </div>

          </div>
                

            </div>
</form>
        <div id="footer">
        	<div id="footerlayout">
        	<div id="footer_left"><p>&copy; 2012 All rights reserved.</p></div>
             <div id="footer_right"> <a href="index.php">Home</a>  |  <a href="createurl.php">Crate Link</a>  |  <a href="manage.php">Manage Link</a>   |  <a href="accesslogs.php">Access logs</a></div>
        </div>
        </div>
</div>
</body>
</html>