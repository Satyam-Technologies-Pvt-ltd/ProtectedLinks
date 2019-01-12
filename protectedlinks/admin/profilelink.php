 <?php 
include "../includes/functions.php";
session_start(); $conn = dbConnect(); 
doLogout('../login.php');
checkLogin('../login.php');
///Getting Link Permission Data
if(isset($_POST['urlid'])){
	$urlid=secure_input($_POST['urlid'],"int"); //if id of link is not set eturn back to reffer page
	}
	else{
	header("Location:".$_SERVER['HTTP_REFERER']);
		}
   $link = getLinkPermissionsAdmin($urlid); //getting link details for admin
   if($link==false){$error="Link Not Found In Database";header("Location:".$_SERVER['HTTP_REFERER']);}
   $url= $link['url']; //url location of file
   $usertype =  $link['usertype'];  //link type single /multiple user
   $iprestrict =   $link['iprestrict'];   //ip based restriction if any
   $ipaccessno = $link['ipaccessno'];  //no of ip allowed
   $expnos  =  $link['expnos']; // no f attempt
   $exptime  = $link['exptime']; //expiry time in hour
   $firstaccess   = $link['firstaccess']; 
   $timestamp = $link['timestamp']; //date time of cretion
   $exptimerespect = $link['exptimerespect']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="Anupam Rekha" />
	<title>Create Link</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="workArea">
	<div id="header">
    	<div id="logo">
        	<div class="locked_links_text"></div>
            <div class="locked"></div>
            <!--nav state-->  
            <div id="nav_menu">
            <div id="nav">
            <div id="navL">
            <div id="navR">     
            <div class="menuL"><a href="singleuser.php" ><span class="text_hidden" >Single</span></a></div>
            <div class="menuM"><a href="manage.php">Manage Links</a></div>
            <div class="menuR"><a href="createurl.php" ><span class="text_hidden" >Multiple</span></a></div>
            <div id="navCentar"></div>
            <div class="menu_right_L"></div>
            <div class="menuM"><a href="accesslogs.php">Access Logs</a></div>
            <div class="menu_right_R"><a href="accesslogs.php?logout=yes" >Logout</a></div>
            </div>
            </div>            
            </div>
        	</div>
            <!--nav end-->
        </div>
    </div>
<div id="content">
<?php if(isset($msg)) echo "<div align='center'>".$msg."</div>";

?>
<form action="<?php  if(isset($new_downcode)){echo "Editurl.php?urlid=".$urlid;}?>" method="post" enctype="multipart/form-data" onsubmit="return checkfunction();">

 <img src="../images/multiple.jpg" width="105" class="heding" height="22" />
 <!--user Type-->
 <div class="table"><strong class="lebal">Link Type</strong>
 <input type="radio" name="usertype" value="s" <?php if($usertype=='s'){echo "checked='checked'";}?>/>
 &nbsp;Single User&nbsp;&nbsp;
 <input type="radio" name="usertype" value="m"  <?php if($usertype=='m'){echo "checked='checked'";}?>/>Multiple User <br /></span></div>
 <!--user type End-->
 
 <!--URL-->
<div class="table"> <strong class="lebal">Your URL</strong><input type="text" class="input1" name="url" value="<?php echo $url;?>" size="40" maxlength="300" /><br /><input type='hidden' name='linkid' value="<?php echo $urlid;?>"/></div>
<!--URL End-->

<!--Add Time Restriction-->
<div class="table"><strong class="lebal" >Add Time Rstrictions</strong><input type="text" size="40" name="exptime" class="input_1" value="<?php echo $exptime;?>" checked="true" />
<span class="lebal1">After &nbsp;&nbsp;
<input type="radio" name="exprespect" value="L" <?php  if($exptimerespect=='L')echo 'checked="true"';?> />&nbsp; Link Creation &nbsp;&nbsp;<input type="radio" name="exprespect" value="F"  <?php  if($exptimerespect=='F')echo 'checked="true"';?> />First Download <br /></span></div> 
<!--End of Add Time Restriction-->

<!--IP Address Restriction-->
<div class="table"> <strong class="lebal" ><h4>Allow IP Address Restrictions: </h4></strong></div>
<div class="table"> <label class="lebal" >Allow IP Addresses</label><input type="text" class="input1" name="iprestrict" size="40" maxlength="300" value="<?php echo $iprestrict;?>" /><div class="lebal1"> Comma Separated List<br /></div></div>
<!--End of IP Address Restriction-->

<!--Permit first IP Adddress Count-->
<div class="table"> <label class="lebal" >Permit First (Count) IP Addresses</label><input type="text" class="input_1" name="ipaccessno" size="40" value="<?php echo $ipaccessno;?>" /><div class="lebal1"> ex: 1 means permit First IP Address accessing the Link.<br /></div></div>
<!--Permit first IP Adddress Count-->

<!--Download Attempts and  Submit Buttons-->
<div class="table"> <strong class="lebal" ><h4>Add Download Attempts Restrictions: </h4></strong></div>
<div class="table"> <label class="lebal" >Allowed Attempts</label>
<input type="text" name="downloadattempts" class="input_1" size="40" value="<?php echo $expnos;?>" />
<input  type="hidden" value="Apply Now" name="submit" />
<input  type="hidden" value="<?php echo $urlid;?>" name="urlid" />
<input class="batton" type="image" value="Apply Now" name="submit" src="../images/batton.jpg" />
<!--End Of Download Submit Buttons-->

</form>
  </div>
   <!--footer state-->
  <div id="footer"><div align="center">Coding & Designed By<a href="http://www.satyamtechnologies.com"> Satyam Technologies</a></div></div>
  <!--footer end-->
</div>
</body>
</html>