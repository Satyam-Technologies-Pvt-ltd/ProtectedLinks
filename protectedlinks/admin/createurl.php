<?php 
include "../includes/functions.php";

session_start(); $conn = dbConnect(); 

doLogout('../index.php');

checkLogin('../index.php');
$sts=false;
if(isset($_POST['submit']))
    { 
	$error="";
	$fileuploaded=false;
	if(!isset($_POST['notadownload']) && isset($_FILES['u_file']['tmp_name']) && $_FILES['u_file']['tmp_name']!=''){
	$target_dir = getUploadsPathSetting();
	$target_file = $target_dir .'/'. basename($_FILES["u_file"]["name"]);
	$FileType = pathinfo($target_file,PATHINFO_EXTENSION);	
	
		if (!file_exists($target_dir) && $target_dir!='') {
	    mkdir($target_dir, 0777, true);
		}
	
		if (file_exists($target_file)) {
	   
		    if( isset($_POST['replacefile'])){
		    	unlink($target_file);
		    }
		    else{
				$dl_error="A File of same name as the uploaded file already exists.";		    	
		    }
		}
	  	if (move_uploaded_file($_FILES["u_file"]["tmp_name"], $target_file)) {
	  		
	    if($target_dir!=='' && strpos($target_file, $target_dir)!==FALSE ){
     	$url = secure_input( $target_file,"string");
     	$fileuploaded=true;
   		}
	        
	    } else {
	        $dl_error =  "Sorry, there was an error uploading your file.";
	    }
		
	}
	else if(isset($_FILES['u_file']['tmp_name']) && $_FILES['u_file']['tmp_name']!=''){
		$dl_error = "Please upload a downloadable file only.";
	}
	if(!$fileuploaded){
		$url=secure_input($_POST['url'],"string"); 	
	}
	if(strpos($url, 'https://')===FALSE && strpos($url, 'http://')===FALSE ){
		$url = secure_input( substr($url, 3),"string");
	}
	$showfilename=secure_input($_POST['f_name'],"string");
	$title=secure_input($_POST['f_title'],"string");
	$description=secure_input($_POST['f_desc'],"string");
	if(isset($_POST['iprestrict'])){
	$iprestrict=secure_input($_POST['iprestrict'],"string");
	
	}else{$iprestrict="";}
	if(isset($_POST['ipaccessno'])){
	$ipaccessno=secure_input($_POST['ipaccessno'],"int"); 
	}else{$ipaccessno=0;}
	///////////RESTRICTED IP////////////////////////
	$restrictedip=secure_input($_POST['restrictedip'],"string");
	/////////////////////////////////////////////////
	///////////// Ip Count Validation //////////////////////
	
	if($iprestrict<>"" )
	{
		
	$iparray=explode(",",$iprestrict);
	
	$no_of_ip=count($iparray);
	
	
	if( $no_of_ip > $ipaccessno && $error=="")
	
	{
		
	$error="Permit (count) Ip address Can not be less Then Number of Ip Address ";	
		
	}
	}
	else{$error="";}
	
	if($ipaccessno==0)
	
	{$error="";	}
	
	if(isset($dl_error) && $dl_error!=''){
		$error = $dl_error;
	}
	
	

	////////////////////////////////////////////////////////
	
	$downatt=secure_input($_POST['downloadattempts'],"int");
	
	$exptime=secure_input($_POST['exptime'],"int"); 
	
	$downcode=randomcode();
	
	$time=date("Y-m-d H:i:s",time());
	
	$exprespect=secure_input($_POST['exprespect'],"string");
	
	$user_type=secure_input($_POST['usertype'],"string");
	//if Error Msg is not blank
	
	$notdownload =isset($_POST['notadownload'])? secure_input( $_POST['notadownload'],"int"):0; 


	if( $error=="") {
		
		
	$sql = "INSERT INTO `permissions` 
	( `url`, `showfilename`,`title`,`description`,`notdl`, `exptime`, `iprestrict`, `ipaccessno`,`restrictedip`, `usertype`,`expnos`,`exptimerespect`,`downcode`,`timestamp` )
	 VALUES ( '$url','$showfilename','$title','$description','$notdownload', '$exptime', '$iprestrict', '$ipaccessno','$restrictedip', '$user_type','$downatt','$exprespect','$downcode','$time')";  
	 
				$result=mysqli_query($conn,$sql) or die(mysqli_error($conn));	
				
                if( $result ){
					if(getUseHtaccess()==1){
					$msg="Link Created Successfully ,Your Download Link  is : <b>".downloadPath()."/".$downcode."</b>";	
					}
					else{
				$msg="Link Created Successfully ,Your Download Link  is : <b>".downloadPath()."/download.php?downcode=".$downcode."</b>";						
					}

				
				} else {
					
				$msg="Link can not be created ! Please try agian";
				
				}
	}///End of Error Validation
	
		}
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | CREATE PROTECTED LINKS</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.all.css" />
<script src="../jquery/jquery-1.7.1.js"></script>
<script src="../jquery/jquery.ui.core.js"></script>
<script src="../jquery/jquery.ui.widget.js"></script>
<script src="../jquery/jquery.ui.datepicker.js"></script>
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
		//css hack for ie8
		$("#lower_header table td:last-child").css("width","90px");
		$("#lower_header table td:last-child").css(" margin","0 0 0 20px");
		$("#lower_header table td:last-child").css(" border","1px solid #eaeaea");
		$("#lower_header table input:last-child").css("width","90px");
		$("#lower_header table input:last-child").css("padding","0 10px 0 10px");
		//

		
	});
	</script>
    <script language="javascript" src="js.js">
	</script> 
    <script language="javascript" >
	
	function validateMe(name)
	{
	
	switch(name)
	{
		
	case "url":
	urlValidate();
	break;
	
	case "file":
	fileValidate();
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
        	<h2>Create protected links
        	  
        	</h2>
            </div>
            <h3>File Information</h3>
            	<div id="table_top">
            	  <table width="100%"  border="0">
				    <tr>
   						  <td width="97" height="49">Download URL </td>
<td width="463"><label for="textfield"></label>
					      <input type="text" name="url"  id ="url" size="40" maxlength="300" 
 onchange="validateMe('url'); clearAll();"/></td>
					  <td width="316"><div id="msg_url"></div></td>
					</tr>
				
				    <tr>
   						  <td width="97" height="49"><b>OR</b> Upload File</td>
<td width="463"><label for="textfield"></label>

					      <input type="file" name="u_file"  id ="u_file" size="40" maxlength="300" 
 onchange="validateMe('file')"/></td>
					  <td width="316"><div id="msg_file"><?php if(isset($dl_error)){echo '<div class="table4"><img src="../images/error_icon.png" alt="" /><span>'.$dl_error.'</span></div>' ; }  ?></div></td>
					</tr>					
  						<tr>
    					  <td height="45"><p>File</p></td>
    					  <td><label for="textfield"></label>
					      <input type="text" name="f_name"  id ="f_name" size="40" maxlength="255" 
 onchange="validateMe('f_name')"/></td>
    					  <td><div id="msg_fname" ></div></td>
  						</tr>
                        <tr>
                          <td height="45">Title</td>
                          <td><input type="text" name="f_title"  id ="f_title" size="40" maxlength="255" 
 onchange="validateMe('f_title')"/></td>
                          <td><div id="msg_ftitle" ></div></td>
                        </tr>
                        <tr>
   						  <td height="45">Description</td>
   						  <td><label for="textfield"></label>
					      <input type="text" name="f_desc"  id ="f_desc" size="40" maxlength="300" 
/><br /></td>
   						  <td><div id="msg_fdesc"  class="table4"></div></td>
  						</tr>
			
					
					</table>

          </div>
                <h3>File Restrictions</h3>
              <div id="restriction">
               	<table width="804" border="0">
  						<tr>
                        	<td width="135" height="46">Link Type</td>
    						<td width="46"> <input type="radio" name="usertype" value="s"  <?php 
if(isset($error) && $error <>""){
if($user_type=='s')
{echo "checked='checked'";}		
}else{echo "checked='checked'";} 
?> onchange="disable_multiple('s')"/></td>
    						<td width="103">Single User</td>
                            <td width="31"><input type="radio" name="usertype" value="m"  onchange="disable_multiple('m')"  
 <?php 
if(isset($error) && $error <>""){
if($user_type=='m')
{echo "checked='checked'";}		
} 
?>
 /></td>
                            <td width="553">Multiple User</td>
  						</tr>
					</table>
                <table width="600" border="0">
					  <tr>
                       	  <td width="133">Expiry Time (in hrs)</td>
   						  <td width="162"><input name="exptime" class="input_1"  id="exptime"

onchange="validateMe('time')"
value=" 
<?php 
if(isset($error) && $error <>""){
echo $exptime;		
} 
?>" maxlength="5" n />
</td>
   						  <td width="38">After</td>
                          <td width="28"><input type="radio" name="exprespect" value="L"
id= "l_downRadio"
<?php 
if(isset($error) && $error <>""){
if($exprespect=='L')
{echo "checked='checked'";}		
}else{echo "checked='checked'";} 
?>/></td>
                          <td width="82">Link Creation</td>
                          <td width="34"><span id="fdln" ><input type="radio" name="exprespect" value="F" id= "f_downRadio"
<?php 
if(isset($error) && $error <>""){
	if($user_type=='m'){echo "disabled='disabled'";}
if($exprespect=='F' )
{echo "checked='checked'";}		
}
?>

/></span></td>
                      <td width="101"><span id="fdln1"> First Download</span></td>
                      <td width="278"><div id="msg_exptime"></div></td>
					  </tr>
				  </table>
              </div>
                 <div id="restriction">
                  <table width="700" border="0">
					  <tr>
                       	  <td width="133">Permitted IP</td>
   						  <td width="368"><textarea name="iprestrict" cols="30" rows="3"   id="iprestrict" onchange="validateMe('allow_ip')"><?php if(isset($error) && $error <>""){echo $iprestrict;		
}?></textarea></td>
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
					    <td width="268"><input name="ipaccessno" type="text" class="input_1" id= "ipaccessno" onchange="validateMe('ip_accessno')" value="
<?php 
if(isset($error) && $error <>""){
echo $ipaccessno;		
} ?>" size="40" maxlength="5" />


</td>
                          <td width="475">
                       <div class="table3">
                            <img src="../images/information_icon.png" alt="" /><span>Ex: 1 means permit First IP Address accessing the Link.</span></div>
                         <div id="msg_ipaccessno" ></div></td> 
					  </tr>
				  </table>
              </div>
                  <div id="restriction">
                  <table width="700" border="0">
					  <tr>
                       	  <td width="127">Restricted IP</td>
   						  <td width="365"><textarea name="restrictedip" cols="30" rows="3"  id="res_ip" onchange="validateMe('res_ip')"></textarea></td>
                          <td width="384">
                        <!--	<div class="table4">
                            <img src="../images/error_icon.png" alt="" /><strong>Error :</strong><span>Comma Separated List</span></div>-->
                         	<div class="table2">
                            <img src="../images/information_icon.png" alt="" /><span>Comma Separated List</span></div>                      <div id="msg_resip"  ></div>
                            </td>
   						  
					  </tr>
                      </table>
</div>
                  <div id="restriction">
                  	<table width="700" border="0">
                      <tr>
                       	  <td width="118" height="34">Allowed Attempts</td>
					    <td width="172"><input name="downloadattempts" type="text"    id="allow_atmp"  onchange="validateMe('allow_atmp')" value="
<?php 
if(isset($error) && $error <>""){
echo $downatt;		
} 
?>
" maxlength="5"/>
</td>
                          <td width="586">
                         
                         <img src="../images/information_icon.png" alt="" /> <span class="myspan">Number of Downloads&nbsp;&nbsp;</span>
                             <div id="msg_allow_atmp"  ></div>
                         </td>
   						  
					  </tr>
                      </table>
                      
                  </div>
                  <div id="submit">
                      <table width="294" border="0">
                      <tr>
                       	  <td width="167" height="36"></td>
					    <td width="107"><input name="Reset" type="reset" value="Cancel" /></td>
                          <td width="112">
                          	<input type="submit" value="Create" name="submit"/>
                         </td>
   						  
					  </tr>
				  </table>
                  
              </div>

          </div>
                

            </div>
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