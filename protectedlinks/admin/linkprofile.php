<?php 
include "../includes/functions.php";
session_start(); $conn = dbConnect(); 
doLogout('../index.php');
checkLogin('../index.php');
///Getting Link Permission Data
if(isset($_GET['urlid'])){
	if(intval($_GET['urlid'])<=0){
		
		header("Location:searchresult.php");	
	}
	$urlid=secure_input($_GET['urlid'],"int");
	}
else{

	}
	//$urlid=0;
   $link = getLinkPermissionsAdmin($urlid);
    if($link==false){$error="Link Not Found In Database";
   header("Location:searchresult.php");
   }
   $url= $link['url']; 
   $filename=$link['showfilename'];
   $description=$link['description'];
   $downcode=$link['downcode'];
   $usertype =  $link['usertype']; 
   $iprestrict =   $link['iprestrict'];
   $restrictedip=$link['restrictedip'];  
   $ipaccessno = $link['ipaccessno']; 
   $expnos  =  $link['expnos']; 
   $exptime  = $link['exptime']; 
   $firstaccess   = $link['firstaccess']; 
   $timestamp = $link['timestamp']; 
   $exptimerespect = $link['exptimerespect']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINK | LINK PROFILE</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.all.css">
<style>
.hover {
	font-weight: bold;
}
 .page{ margin:5px; } 
</style>
<script src="../jquery/jquery-1.7.1.js" language="javascript"></script>
<script src="../jquery/jquery.ui.core.js" language="javascript"></script>
<script src="../jquery/jquery.ui.widget.js" language="javascript"></script>
<script src="../jquery/jquery.ui.datepicker.js" language="javascript"></script>
<script language="javascript" src="js.js"></script>
<script language="javascript">
	
	//function for confomation When Delete Button is pressed
	
	function delete_confirm(url)
	{	
	var res=confirm("Are You Sure To Delete Link: "+url);
	if(res==false)
	{
	return false;	
	}	
	}
	
	</script>
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
		//Pagination of Allow Ip
		PaginatePermittedIP();
	    PaginateRestrictedIP();
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
                    		<li><a href="setting.php" title="Settings"><img src="../images/settings_icon.png" alt="Log out" /></a></li>
                    		<li><a href="accesslogs.php?logout=yes" title="Logout"><img src="../images/icon2.png" alt="" /></a></li>
                    	</ul>
                  </div>
                </div>
                <div id="navigation">
                	<div id="left_nav">
                    	<ul>
                        	<li><a href="index.php" class="active">Home</a></li>
                            <li><img src="../images/nav_line.jpg" alt="" /></li>
                            <li><a href="createurl.php">Create Link</a></li>
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
        
        <div id="layout">
        	<div id="layouthd">
            <div id="heading">
        	<h2>link profile</h2>
            <ul>
            	<li></li>
              <li></li>
            </ul>
            </div>
            <div id="user1">
            	 	<table width="558" border="0">
  					<tr>
    				<td width="75">Link Type</td>
                   <?php  $st_active='width="75"class="bg1"';
				          $st_disble='width="88" style="color:#a9a9a9;"';
						  ?>
                  <td width="83"  <?php if($usertype=='s'){echo $st_active; }else{echo $st_disble;}?>>Single User</td>
    			  <td width="95"   <?php if($usertype=='m'){echo $st_active; }else{echo $st_disble;}?>>Multipal User</td>
                   
    				<!--<td width="84" class="bg1">Single User </td>
    				<td width="91" style="color:#a9a9a9;">Multipal User</td>-->
    				<td width="44">Code</td>
                    <?php //echo downloadPath()."/download.php?downcode=".$downcode; ?>
                    
                    
    				<td width="159"><a href="<?php if(getUseHtaccess()==1){echo downloadPath()."/".$downcode;}else{ echo downloadPath()."/download.php?downcode=".$downcode;}?>" title="<?php if(getUseHtaccess()==1){echo downloadPath()."/".$downcode;}else{ echo downloadPath()."/download.php?downcode=".$downcode;}?>" target="_blank" id="dcode" style="color:#06F;" onmouseover="selectText ('dcode')"><?php  echo $downcode;?></a></td>
    				<td width="53" class="bg1" style="text-align:center; text-transform:uppercase;"><form method ="post" action="Editurl.php">
  
  <input type="hidden" name="urlid" value="<?php echo $urlid;?>"/>
  <input type='submit' name='edit'  value='EDIT' class='cleanbtn' />
  
  
  </form></td>
    				<td width="69" class="bg1" style="text-align:center; text-transform:uppercase;">
                  <form method ='post' action='deleteurl.php' onsubmit='return delete_confirm("<?php echo $url;?>")'>
  
  <input type='hidden' name='urlid' value='<?php echo $urlid;?>'/>
  
  <input type='submit' name='delete'   value='DELETE'  class='cleanbtn'/>
  
  </form>
                  
  </td>
  					</tr>
				</table>

            </div>
            	<div id="table_top1">
                	<h2>Protetected Link Profile Information</h2>
                    <div id="right_table">
                    	<table width="185" border="0">
  							<tr>
    						<td width="105">Creation Date</td>
    						<td><strong style="background:url(../images/blackbg.png) no-repeat; width:65px; height:18px;"><?php echo date("d-m-Y",strtotime($timestamp));?></strong></td>
  							</tr>
  							<tr>
    						<td>Failed Download</td>
    						<td><strong style="background:url(../images/redbg.png) no-repeat; width:31px; height:18px;"><?php echo getDownloadCount($urlid,"f"); ?></strong></td>
  							</tr>
  							<tr>
    						<td>Total Download</td>
    						<td><strong style="background:url(../images/greenbg.png) no-repeat; width:41px; height:18px;"><?php echo getDownloadCount($urlid,"d");?></strong></td>
  							</tr>
  							<tr>
    						<td>Download <br />
    						  Attempts By IP</td>
    						<td><strong style="background:url(../images/bluebg.png) no-repeat; width:41px; height:18px;"><?php echo getMaxAttamptByIP($urlid,$param='d')+getMaxAttamptByIP($urlid,$param='f');?></strong></td>
  							</tr>
						</table>

                    </div>
               	  <table width="200" border="0">
  						<tr>
   						  <td width="129" height="23">Download Url</td>
   						  <td width="451"><a href="<?php  echo $url;?>" title="<?php  echo $url;?>"  target="_new"><?php  echo getShortName($url,60);?></a></td>
  						</tr>
  						<tr>
    					  <td height="25"><p>Protected Link</p></td>
    					  
   					  
    					  <td><a href="<?php if(getUseHtaccess()==1){echo downloadPath()."/".$downcode;}else{ echo downloadPath()."/download.php?downcode=".$downcode;} ?>" title="<?php if(getUseHtaccess()==1){echo downloadPath()."/".$downcode;}else{ echo downloadPath()."/download.php?downcode=".$downcode; }?>" target="_new"><?php  echo getShortName(downloadPath()."/download.php?downcode=".$downcode,60);?></a></td>
  						</tr>
                        <tr>
   						  <td height="23">Profile Name</td>
   						  <td><a href="#"><?php  echo $filename;?></a></td>
  						</tr>
                        <tr>
                          <td height="25">Description</td>
                          <td><?php if($description==""){echo "Not Available";} else{echo $description; }?></td>
                        </tr>
                        <tr>
   						  <td height="25">Time Restriction</td>
   						  <td><?php  if($exptime>0){ echo $exptime ." hr.";
   if($exptimerespect=='L')
    {echo " After Link Creation";}
   else
   {
	 echo " After First Download";  
	 }
  }else{
	  
	  echo "Not Set";
	  }
	?></td>
  						</tr>
                        <tr>
                          <td height="25">Maximum IPs Allowed</td>
                          <td><?php if($ipaccessno>0){echo  $ipaccessno ;} else {echo "Not Set";}?></td>
                        </tr>
                        <tr>
                          <td height="25">Maximum Attempts</td>
                          <td><?php if($expnos>0){echo $expnos;} else { echo "Not Set";}?></td>
                        </tr>
					</table>
                    

                </div>
                <div id="heading2">
                <?php 

if($iprestrict<>""){
$al_ipar=explode(",",$iprestrict);
$count_al=count($al_ipar);
}else{
	$count_al=0;
	}?>
                	<h5>Permitted IP Addresss</h5>
                    <select><option>
	<?php echo $count_al;?>
</option></select>
                </div>
              <div id="ipaddress" class="ipa">
              <input type="hidden" name="ipapage" id='ipapage' value="1"/>
               	<table width="200" border="0" id='ip1'>
  					
                   <?php 
					echo "<tr>";
					$rowno=1;
					for($i=1;$i<=$count_al;$i++)
					{
						
						echo "<td>".$al_ipar[$i-1]."</td>";
			            if($i%7==0){ 
						$rowno++;
						if($rowno%2==0){
						 echo "<tr/><tr class='bg1'>";
						} else{
							 echo "</tr><tr >";
							}  
						
						}
					
					}
					echo "</tr>";
					?>
            
				</table>

                
              </div>
             
                 <div id="layoutbtm1" class='ipnav1'>
            		<table border="0">
  						<tr>
    						
    						
  						</tr>
					</table>

            </div>
                  <?php 
			  if($count_al==0)
			  {
				  echo '<script language="javascript">';
				  
				  echo '$("#layoutbtm1").hide(); ';
				  echo '</script>'; 
				  
				}
			      ?> 
              <div id="heading2">
                  <h5>Denied IP Addresss</h5>
                  <?php 
if($restrictedip<>""){
$res_ipar=explode(",",$restrictedip);
$count_res=count($res_ipar);
}else{$count_res=0;}
?>
                  <select><option><?php echo  $count_res;?></option></select>
                  </div>
                  
                  <div id="ipaddress" class="ipr">
                   <input type="hidden" name="iprpage" id='iprpage' value="1"/>
               	<table width="200" border="0" id ='ip2'>
                   <?php 
					echo "<tr>";
					$rowno=1;
					for($i=1;$i<=$count_res;$i++)
					{
						
						echo "<td>".$res_ipar[$i-1]."</td>";
			            if($i%7==0){ 
						$rowno++;
						if($rowno%2==0){
						 echo "<tr/><tr class='bg1'>";
						} else{
							 echo "</tr><tr >";
							}  
						
						}
					
					}
					echo "</tr>";
					?>
  					
                    
				</table>

                
              </div>
<div id="layoutbtm2" class "ipnav2">
            		<table border="0">
  						<tr>
    						
  						</tr>
					</table>
				
            </div>
              <?php 
			  if($count_al==0)
			  {
				  echo '<script language="javascript">';
				  echo '$("#layoutbtm2").hide(); ';
				  echo '</script>'; 
				  
				}
			      ?> 
            <div id="heading">
           
        	<h2>Recent Access</h2>
            
            </div>
            <table border="0" cellpadding="5">
            <tr class="bg1"></tr>
            <tr class="bg1">
              <td width="150"><strong>IP Address</strong><a href="#"><img src="../images/up_arrow.png" class="drop" alt="" /></a></td>
              <td width="150"><strong style="margin:7px 0 0 0;">REFERER</strong>
                <ul>
                  <li><a href="#"><img src="../images/up_arrow.png" alt="" /></a></li>
                  <li><a href="#"><img src="../images/down_arrow.png" alt="" /></a></li>
                </ul></td>
              <td width="121"><strong style="margin:7px 0 0 0;">ACCESS TIME</strong>
                <ul>
                  <li><a href="#"><img src="../images/up_arrow.png" alt="" /></a></li>
                  <li><a href="#"><img src="../images/down_arrow.png" alt="" /></a></li>
                </ul></td>
              <td width="90"><strong style="margin:7px 0 0 0;">Status</strong>
                <ul>
                  <li><a href="#"><img src="../images/up_arrow.png" alt="" /></a></li>
                  <li><a href="#"><img src="../images/down_arrow.png" alt="" /></a></li>
                </ul></td>
            </tr>
            <?php
$sql="SELECT * FROM accesslogs WHERE urlid='{$urlid}'  ORDER By time DESC LIMIT 10";
$res_acc=mysqli_query($conn,$sql) or die(mysqli_error($conn));
$r_c=0;
  
  $bgc="bgcolor='#FFFFFF'";
while($row_acc=mysqli_fetch_assoc($res_acc))
{
	 $r_c++;
  echo "<tr>";
    echo "<td><a href='#'>".$row_acc['ipaddress']."</a></td>";
	if($row_acc['referer']=="Direct")
	{
		 $referer="Direct";
		 echo " <td title='".$row_acc['referer']."'>".$referer."</td>";
		}
		else{
			 $referer="Indirect";
			 echo " <td title='".$row_acc['referer']."'><a href='".$row_acc['referer']."'target='_new'>".$referer."  </a></td>";
		}
 
 $tformat =getAccessTimeFormatSetting();
 
   echo "<td>".timeInterval($row_acc['time'],date("y-m-d H:i:s"), $tformat)."</td>";
if($row_acc['status']=='d')
   {
	  echo "<td><a href='#'><img src='../images/auth.jpg' alt='' title='Authorized' /></a></td>";
	   }
   else{
	 echo"<td><a href='#'><img src='../images/unauth.jpg' alt=''  title='Unauthorized' /></a></td>";
	   }
   
 echo "</tr>";
}
  
  ?>
            </table>
            <div id="view"><a href="accesslogs.php?url_pro=<?php echo $urlid;?>" style=" color: #000;" >View All</a></div>
          </div>
                

            </div>
        <div id="footer">
        	<div id="footerlayout">
        	<div id="footer_left"><p>&copy; 2010 All rights reserved.</p></div>
              <div id="footer_right"> <a href="index.php">Home</a>  |  <a href="createurl.php">Create Link</a>  |  <a href="manage.php">Manage Link</a>   |  <a href="accesslogs.php">Access logs</a></div>
        </div>
        </div>
</div>
</body>
</html>