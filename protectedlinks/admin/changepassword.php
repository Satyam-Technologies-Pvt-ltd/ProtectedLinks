<?php

include "../includes/functions.php"; //including Function File
session_start(); $conn = dbConnect();  //Connecting to Database
checkLogin('../index.php');//is user is loged in if not redirect to login page
doLogout('../index.php'); //Log out redirect to Login page
if(isset($_POST['submit']))
{
$old_pass="";
$new_pass="";
$con_pas="";	
if(isset($_POST['oldpass']) && ($_POST['oldpass']<>""))
{$old_pass=$_POST['oldpass'];}//if old posted old password is not blank initilize them to a variable
if(isset($_POST['new_psw'])&& $_POST['new_psw']<>"")
{$new_pass=secure_input($_POST['new_psw'],"string");} //new password is not blank then initilize to a variable

//else{ // if posted variables is blank send an Error message
	
	//$error="<span class='smspan'><img src='../images/error_icon.png' alt='' />Please Enter  Current Password  !</span>";

//}
if(isset($_POST['con_psw']) && $_POST['con_psw']<>"")

{$con_pas=$_POST['con_psw'];} 

///checking blank entry


if( trim($old_pass)<>"" or trim($new_pass)<>""){
	if(trim($new_pass)==trim($con_pas)) {//if poasetd new password and confirmed password matched
	
	$sts=changePassWord($_SESSION['username'],$old_pass,$new_pass,'userlogin'); //change the password 
	
	if($sts==true){ //display a success message
	
	$msg="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Your settings has been saved.! </span/>";}
		
	else{ //if change password fail send error messsage
	
	$error="<span class='smspan'><img src='../images/error_icon.png' alt='' />Your Current Password is not correct.!</span> ";}
			
		
	}else { //new password not matched to confirmend then send an error message
	
		$error="<span class='smspan'><img src='../images/error_icon.png' alt='' />Please confirm your password.!</span>";}
		
		
	} //end of trim($new_pass)==trim($con_pas)
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
$msg="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Your settings has been saved.! </span/>";
}else{$error="<span class='smspan'><img src='../images/error_icon.png' alt='' />Image size must be 252 *52 or less.! </span/>";
 unlink("../images/".$filename);
 }
}else{
//$msg="Please Upload a valid image file";
$error="<span class='smspan'><img src='../images/error_icon.png' alt='' />Please Upload a valid image file.!</span> ";


}
}

         //setting Product Image  display or not
if(isset($_POST['p_logo']))
{
$udate_product_query="update settings set product_logo='yes'";
$msg="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Your settings has been saved.! </span/>";
}
else{
$udate_product_query="update settings set product_logo='no'";
$msg="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Your settings has been saved.! </span/>";
}

$res=mysqli_query($conn,$udate_product_query) or die(mysqli_error($conn));//

if(isset($_POST['s_field']))
{
if($_POST['s_field']<>"")
{
$search_field=secure_input($_POST['s_field'],"string");
$query_search_opt="update  settings set default_search='$search_field'";
$res=mysqli_query($conn,$query_search_opt) or die(mysqli_error($conn));
$msg="<span class='smscrr'><img src='../images/information_icon.png' alt='' />Your settings has been saved.! </span/>";

}
}
}
///getting settings
$query_settings="Select * from settings LIMIT 1";
$result_settings=mysqli_query($conn,$query_settings)or die(mysqli_error($conn));
if(mysqli_num_rows($result_settings)){
$logo=mysqli_result($result_settings,0,"logo");
$procuct_logo=mysqli_result($result_settings,0,"product_logo");
$default_search=mysqli_result($result_settings,0,"default_search");



}


/////
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
                            <li><a href="createurl.php">Create Link</a></li>
                            <li><img src="../images/nav_line.jpg" alt="" /></li>
                            <li><a href="manage.php" class="active">Manage Link</a></li>
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
		<!--header end-->
        <div id="login_bg">
        	
        	  <div id="login_main">
            	<div class="login_layout">
        	<h2>Settings</h2>
            
             <?php if(isset($error))
			 {  echo '<div align="center">'.$error.'</div>';}
			 else if(isset($msg)){
              echo '<div align="center">'.$msg.'</div>';
             }?>    

                       
           	
                	<form name="user_logo" method="post" enctype="multipart/form-data"  >
                      <div id="login">
                  	<table width="400" border="0">
                    <tr>
    					  <td  height="45"><b>Logo Setting</b></td>
  						</tr>
                    <tr>
    					  <td width="106" height="45">Your Logo</td>
    					  <td width="474" onmouseout="showfilename()"><label for="textfield" ></label>
					      <!--<input type="file" name="c_logo"  />-->
                           <div id="c_browse" style="height: 34px; width:297px; cursor:pointer;" onclick="getFile()"><input type="text" id="s_file" class="f_upload" readonly="readonly"/> <span class="button_browse">Browse</span></div>
    <!-- this is your file input tag, so i hide it!--> 
   <div style='height: 0px;width:0px; overflow:hidden;'><input id="upfile" type="file" value="upload" name= "c_logo" /></div>
    <!-- here you can have file submit button or you can write a simple script to upload the file automatically--></td>
                          
                          <?php 
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
                        <tr>
    					 <td>
    					&nbsp;&nbsp; &nbsp;<input type="checkbox" name="p_logo" value="yes" <?php if(strtolower($procuct_logo)=='yes'){echo 'checked="checked"';}?> id="p_logo_chbx" />                     </td>
                         <td  > Display Protected Link Logo on Download Page </td>
  						</tr>
                      <!--     </table>
                 
     <input type="submit" name="logosave"  class="login_button" value="Save" />
        </form>
 <form name="changepass" method="post" >
                        	<table width="400" border="0">-->
                      <tr>
                        <td  height="45"><b> Search Setting </b></td>
                      </tr>
                       <tr>
                        <td  height="45" > Default Search Option </td>
                         <td  height="45" >
                         <div class='divonsetting'>
                      <div class="sty">
                      <select name="s_field" >
                     
                      <option value="pfile" <?php if(strtolower($default_search)=='pfile'){echo "selected='selected'";}?>>Profile Name</option>
                      <option value="dcode" <?php if(strtolower($default_search)=='dcode'){echo "selected='selected'";}?>>Download Code</option>
                      <option value="file" <?php if(strtolower($default_search)=='file'){echo "selected='selected'";}?>>File Name</option>
                      <option value="ip" <?php if(strtolower($default_search)=='ip'){echo "selected='selected'";}?>>IP Address</option>
                      </select>
                      </div>  
                      </div>                       
                         </td>
                      </tr>
                      <tr>
    					  <td  height="45" colspan="2" ><b>Change Password</b></td>
  						</tr>
  					
  						<tr>
    					  <td width="106" height="45"><p>Current Password</p></td>
    					  <td width="474" ><label for="textfield"></label>
					      <input type="password" name="oldpass" id="textfield"  class="loginput"/></td>
  						</tr>
                        <tr>
                          <td height="45">New Password</td>
                          <td ><label for="textfield"></label>
					      <input type="password" name="new_psw" id="textfield" class="loginput" /></td>
                        </tr>
                        <tr>
   						  <td height="45" >Confirm Password</td>
   						  <td ><label for="textfield"></label>
					      <input type="password" name="con_psw" id="textfield" class="loginput"/></td>
  						</tr>
					</table>

                </div>
		<!--<div class="login_button"> -->
        <input type="submit" name="submit"  class="login_button" value="Save" />
       <!-- </div>-->
        </form>
		<div class="forgot_link"></div>
          </div>
                </div>
                
                </div>
  
           
 
       <div id="footer">
        	<div id="footerlayout">
        	<div id="footer_left"><p>&copy; 2010 All rights reserved.</p></div>
             <div id="footer_right"> <a href="index.php">Home</a>  |  <a href="createurl.php">Crate Link</a>  |  <a href="manage.php">Manage Link</a>   |  <a href="accesslogs.php">Access logs</a></div>
        </div>
        </div> 
 
 </div>


</body>
</html>