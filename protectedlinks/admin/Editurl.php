<?php 
include "../includes/functions.php";

session_start(); $conn = dbConnect(); 

doLogout('../index.php');

checkLogin('../index.php');

///Getting Link Permission Data

if(isset($_POST['urlid'])){
	
	
	$urlid=secure_input($_POST['urlid'],"int"); 

	}
	
	else{
		

	header("Location:searchresult.php");
		
			
		}
		
if(isset($_POST['submit'])) { //If Submit from Edit Button
	$new_url=secure_input($_POST['url'],"string"); 
	
	if(isset($_POST['iprestrict']))
	$new_iprestrict=secure_input($_POST['iprestrict'],"string");
	else{
		$new_iprestrict="";
		}
	$new_filename=secure_input($_POST['f_name'],"string");
	 
	 $new_title=secure_input($_POST['f_title'],"string");
	$new_desc=secure_input($_POST['f_desc'],"string");
	 
	$new_downatt=secure_input($_POST['downloadattempts'],"int");
	
	$new_exptime=secure_input($_POST['exptime'],"int"); 
	if(isset($_POST['ipaccessno'])){
	$new_ipaccessno=secure_input($_POST['ipaccessno'],"int"); 
	}else
	{
		$new_ipaccessno=0;
		}
	//$new_downcode=randomcode();
	$new_restrictedip=secure_input($_POST['restrictedip'],"string");
 	
	$user_type=secure_input($_POST['usertype'],"string");

	$new_exprespect=secure_input($_POST['exprespect'],"string");
	
	$sql = "UPDATE permissions SET url='$new_url', showfilename='$new_filename',title='$new_title',description='$new_desc',exptime='$new_exptime', iprestrict='$new_iprestrict',ipaccessno='$new_ipaccessno', restrictedip='$new_restrictedip', usertype='$user_type',expnos='$new_downatt',exptimerespect='$new_exprespect' WHERE id='$urlid'";
                   
				$result=mysqli_query($conn,$sql) or die(mysqli_error($conn));	
				
                if($result)
				
				{
					if(getUseHtaccess()==1){
				$msg="Link Successfully Saved ,Your Download Link  is : <b>".downloadPath()."/".$_POST['downc']."</b>";	
					}
					else{					
					
				$msg="Link Successfully Saved ,Your Download Link  is : <b>".downloadPath()."/download.php?downcode=".$_POST['downc']."</b>";
				}
				
				}
				
				else
				
				{
					
				$msg="Link can not be altered ! Please try agian";
				
				}

		}
		
   $link = getLinkPermissionsAdmin($urlid); //getting link detail based on request
   
   if($link==false){$error="Link Not Found In Database";}
   
   $url= $link['url']; 
   
   $filename=$link['showfilename'];
   
   $filetitle=$link['title'];
   
   $description=$link['description'];
   
   $usertype =  $link['usertype']; 
   
   $iprestrict =   $link['iprestrict'];  
   
   $ipaccessno = $link['ipaccessno']; 
   
   $restrictedip=$link['restrictedip'];
   
   $expnos  =  $link['expnos']; 
   
   $exptime  = $link['exptime']; 
   
   $firstaccess   = $link['firstaccess']; 
   
   $timestamp = $link['timestamp']; 
   
   $exptimerespect = $link['exptimerespect']; 
   
    $downcode=$link['downcode'];
   
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | CREATE PROTECTED LINKS</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.all.css">
<script src="../jquery/jquery-1.7.1.js"></script>
<script src="../jquery/jquery.ui.core.js"></script>
<script src="../jquery/jquery.ui.widget.js"></script>
<script src="../jquery/jquery.ui.datepicker.js"></script>
 <script language="javascript" src="js.js">
	 url_sts="Valid";
 fname_sts="Valid";
  des_sts="Valid";
   exptime_sts="Valid";
   alip_sts="Valid";
  ipc_sts="Valid";
   resip_sts="Valid";
     alatp_sts="Valid";
	</script> 
<script>
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
		
$("#jset").click(function(){
	//cancle button is clicked reset all validation 
	 url_sts="Valid";
     fname_sts="Valid";
     des_sts="Valid";
     exptime_sts="Valid";
     alip_sts="Valid";
     ipc_sts="Valid";
     resip_sts="Valid";
     alatp_sts="Valid";
	$("#msg_url").hide();
    $("#msg_fname").hide();
    $("#msg_exptime").hide();
    $("#msg_allowip").hide();
    $("#msg_ipaccessno").hide();
    $("#msg_resip").hide();
    $("#msg_allow_atmp").hide();
	$("#rset").trigger('click');
	$("#ipaccessno").css("background-color","white");	
	$("#iprestrict").css("background-color","white");	
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
   
    <script language="javascript" >
	  ///initially all validation  Valriable is valid
	
	function validateMe(name)
	{
	
	switch(name)
	{
		
	case "url":
	urlValidate();
	break;
	
	case "time":
	expTime();
	break;	
	
	case "allow_ip":
	allowIPValidate();
	break;
	
	case "ip_accessno":
	ipCountValidate();
	break;
	
	case "res_ip":
	restrictedIPValidate();
	break;
	 
	case "allow_atmp":
	 allowAttemptValidate(); 
	 break;
	 
	 case "f_name":
	 fileName();
	  break;
	}
	
		
	}	 
		 
	
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
                    		<li><a href="setting.php" title="Settings"><img src="../images/settings_icon.png" alt="Log out" /></a></li>
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
        	<?php if( isset($msg) ) echo "<div align='center'>".$msg ."</div>"?>

            </div>
            <div id="heading">
              <h2>Edit  protected links </h2> 
             </div>
            <div id="user1">
              <table width="589" border="0" >
                <tr>
                  <td width="86" >Link Type</td>
                  <?php  $st_active='width="75"class="bg1"';
				          $st_disble='width="88" style="color:#a9a9a9;"';
						  ?>
                  <td width="107" <?php if($usertype=='s'){echo $st_active; }else{echo $st_disble;}?>>Single User</td>
                  <td width="151"    <?php if($usertype=='m'){echo $st_active; }else{echo $st_disble;}?>>Multipal User</td>
                  <!--<td width="84" class="bg1">Single User </td>
    				<td width="91" style="color:#a9a9a9;">Multipal User</td>-->
                  <td width="74" >Code</td>
                  <?php //echo downloadPath()."/download.php?downcode=".$downcode; ?>
                  <td width="180" ><a href="<?php echo downloadPath()."/download.php?downcode=".$downcode;?>" title="<?php echo downloadPath()."/download.php?downcode=".$downcode;?>" target="_blank" id="dcode" style="color:#06F;" onmouseover="selectText ('dcode')">
                    <?php  echo $downcode;?>
                  </a></td>
                </tr>
              </table>
           </div>
            <h3>File Information</h3>
            
   	    <div id="table_top">
            	  <table width="100%"  border="0">
				    <tr>
   						  <td width="143" height="49">Link URL</td>
   						  <td width="470"><label for="textfield">
   						  <input type="text"name="url"  id ="url"size="40" maxlength="300" 
  value="<?php echo $url;?>" onchange="validateMe('url')"/>
					  </label></td>
   						  <td width="343"><div id="msg_url"></div></td>
					</tr>
  						<tr>
    					  <td height="45"><p>File</p></td>
    					  <td><label for="textfield"></label>
					      <input type="text" name="f_name"  id ="f_name"size="40" maxlength="255" value="<?php echo $filename;?>"
 onchange="validateMe('f_name')"/></td>
    					  <td><div id="msg_fname" ></div></td>
  						</tr>
                        <tr>
                          <td height="45">Title</td>
                          <td><input type="text" name="f_title"  id ="f_title"size="40" maxlength="255" value="<?php echo $filetitle;?>"
 onchange="validateMe('f_title')"/></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
   						  <td height="45">Description</td>
   						  <td><label for="textfield"></label>
					      <input type="text" name="f_desc"  id ="f_desc"size="40" maxlength="300" value="<?php echo $description;?>"
/><br /></td>
   						  <td><div id="msg_fdesc"  class="table4"></div></td>
  						</tr>
				  </table>

          </div>
                <h3>Restriction</h3>
              <div id="restriction">
               	<table width="804" border="0">
  						<tr>
                        	<td width="142" height="46">Link Type</td>
    						<td width="39"> <input type="radio" name="usertype" value="s"   <?php 

if($usertype=='s')
{echo "checked='checked'";}		
 
?>  onchange="disable_multiple('s')"/></td>
    						<td width="103">Single User</td>
                            <td width="31"><input type="radio" name="usertype" value="m"  onchange="disable_multiple('m')"  
 <?php 

if($usertype=='m')
{echo "checked='checked'";}		

?>
 /></td>
                            <td width="553">Multiple User</td>
  						</tr>
				</table>
                <table width="600" border="0">
					  <tr>
                       	  <td width="141">Add Time Restrictions</td>
   						  <td width="168"><input name="exptime"  id="exptime"

onchange="validateMe('time')"
 value="<?php echo $exptime;?>" maxlength="5" n />
</td>
   						  <td width="32">After</td>
                          <td width="30"><input type="radio" name="exprespect" value="L"
id= "l_downRadio"
<?php 
if($exptimerespect=='L')
{echo "checked='checked'";}		

?>/></td>
                          <td width="84">Link Creation</td>
                          <td width="33"><span id="fdln" ><input type="radio" name="exprespect" value="F" id= "f_downRadio"
<?php 

	if($usertype=='m'){echo "disabled='disabled'";}
if($exptimerespect=='F' )
{echo "checked='checked'";}		

?>

/></span></td>
                      <td width="100"><span id="fdln1"> First Download</span></td>
                      <td width="268"><div id="msg_exptime"></div></td>
					  </tr>
			    </table>
              </div>
                 <div id="restriction">
                  <table width="700" border="0">
					  <tr>
                       	  <td width="142">Permitted IP</td>
   						  <td width="359"><textarea name="iprestrict" cols="30" rows="3"   id="iprestrict" onchange="validateMe('allow_ip')"><?php echo $iprestrict;		
?></textarea></td>
                          <td width="375">
                          	<div class="table2">
                            <img src="../images/information_icon.png" alt="" /><span>Comma Separated List</span></div><div id="msg_allowip"  ></div>
                         </td>
   						  
					  </tr>
                   </table>
                   <table border="0">
                      <tr>
                       	  <td width="133" height="81">Permit First (Count)
IP Addresses</td>
					    <td width="170"><input name="ipaccessno" type="text" id= "ipaccessno" onchange="validateMe('ip_accessno')" 
value="
<?php 

echo $ipaccessno;		
 ?>
" maxlength="5" />


</td>
                          <td width="573">
                       <div class="table3">
                            <img src="../images/information_icon.png" alt="" /><span>Ex: 1 means permit First IP Address accessing the Link.</span></div>
                         <div id="msg_ipaccessno" ></div></td> 
					  </tr>
				  </table>
              </div>
                  <div id="restriction">
                  <table width="700" border="0">
					  <tr>
                       	  <td width="138">Restricted IP</td>
   						  <td width="364"><textarea name="restrictedip" cols="30" rows="3"  id="res_ip" onchange="validateMe('res_ip')"><?php echo $restrictedip;?></textarea></td>
                          <td width="374">
                        <!--	<div class="table4">
                            <img src="../images/error_icon.png" alt="" /><strong>Error :</strong><span>Comma Separated List</span></div>-->
                         	<div class="table2">
                            <img src="../images/information_icon.png" alt="" /><span>Comma Separated List</span></div>                      <div id="msg_resip"  ></div>
                        </td>
   						  
					  </tr>
                    </table>
</div>
                  <h3>Attempts Restriction</h3>
                  <div id="restriction">
                  	<table width="700" border="0">
                      <tr>
                       	  <td width="130" height="34">Allowed Attempts</td>
					    <td width="160"><input name="downloadattempts" type="text"   id="allow_atmp"
  onchange="validateMe('allow_atmp')" value="<?php 
echo $expnos;		
?>" maxlength="5"/>
</td>
                          <td width="586">
                         
                          <span class="myspan">Times&nbsp;&nbsp;</span>
                             <div id="msg_allow_atmp"  ></div>
                         </td>
   						  
					  </tr>
                    </table>
                      
                  </div>
                  <div id="submit">
                      <table width="294" border="0">
                      <tr>
                       	  <td width="167" height="36"></td>
					    <td width="107">
                         <input type="button" name="cleaer" value="Cancel" id="jset"/>
                       </td>
                          <td width="112">
                          	<input type="submit" value="Save" name="submit"/>
                           
                         </td>
   						  
					  </tr>
				  </table>
                  
              </div>

          </div>
                

            </div>
             <input name="Reset" type="reset"  id="rset" value="" />
            <input type='hidden' name='urlid' value="<?php echo $urlid;?>"/>
            <input type="hidden" name ="downc" value="<?php echo $downcode;?>"/>
</form>
        <div id="footer">
        	<div id="footerlayout">
        	<div id="footer_left"><p>&copy; 2010 All rights reserved.</p></div>
             <div id="footer_right"> <a href="index.php">Home</a>  |  <a href="createurl.php">Create Link</a>  |  <a href="manage.php">Manage Link</a>   |  <a href="accesslogs.php">Access logs</a></div>
        </div>
        </div>
</div>
</body>
</html>