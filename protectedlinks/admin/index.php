<?php
session_start(); 
include "../includes/functions.php"; //including Function File
$conn = dbConnect();  //Connecting to Database
checkLogin('../index.php');//is user is loged in if not redirect to login page
doLogout('../index.php'); //Log out redirect to Login page
//Getting Newely Created Links.
$link_query="SELECT id, url,showfilename,timestamp,downcode FROM permissions  
ORDER BY id DESC 
LIMIT 23";
$link_res=mysqli_query($conn, $link_query) or die(mysqli_error($conn));
//Gettirng Popular Download
$pop_query="SELECT urlid, COUNT( * ) AS count
FROM accesslogs
GROUP BY urlid
ORDER BY count DESC
LIMIT 10 ";
$pop_res=mysqli_query($conn,$pop_query) or die(mysqli_error($conn));
//gettinf Suspicious Access
$act_query="SELECT ipaddress, COUNT( * ) AS count
FROM `accesslogs`
WHERE STATUS = 'f'
GROUP BY ipaddress
ORDER BY count DESC
LIMIT 10";
$act_res=mysqli_query($conn,$act_query) or die (mysqli_error($conn));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | INDEX </title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.all.css">
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
    <script language="javascript">
	function ban_confirm(ip,msg)
	{
	var res=confirm(msg +ip);
	if(res==false)
	{
	return false;	
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
        	<div class="layouthd2">
        	<h2>Recently Created</h2>
            
           	  <table width="437" border="0" cellpadding="5" cellspacing="0">
  <tr class="bg1">
    <td width="105" height="30" style="line-height:30px;"><strong>Link Profile</strong></td>
    <td width="115"  height="30" style="line-height:30px;"><strong>Protected Link</strong></td>
    <td width="160" height="30" style="line-height:30px;"><strong>Date</strong></td>
  </tr>
  
  <?php 
 
  while($row_link=mysqli_fetch_assoc($link_res))
  {
	 
   echo "<tr>";
    echo "<td class='blue'><a href='linkprofile.php?urlid=".$row_link['id']."'title='".$row_link['showfilename']."'>".getShortName($row_link['showfilename'],15)."</a></td>";
    					if(getUseHtaccess()==1){
     echo "<td title='".downloadPath()."/".$row_link['downcode']."' ><a href='".downloadPath()."/".$row_link['downcode']."' target='_blank' class='t_head'>". getShortName($row_link['downcode'],15)."</a></td>";	
					}
					else{					
     echo "<td title='".downloadPath()."/download.php?downcode=".$row_link['downcode']."' ><a href='".downloadPath()."/download.php?downcode=".$row_link['downcode']."' target='_blank' class='t_head'>". getShortName($row_link['downcode'],15)."</a></td>";
     }
      $tformat =getAccessTimeFormatSetting();
   echo"<td>".timeInterval($row_link['timestamp'],date('y-m-d H:i:s'), $tformat)."</td>";
 echo ' </tr>';
  }?>
</table>
		<div class="view"><a href="manage.php" class="abuttn">View All</a></div>

          </div>
          	<div class="layouthd2">
        	<h2>Popular Files</h2>
           	  <table width="437" border="0" cellpadding="5" cellspacing="0">
  <tr class="bg1">
    <td width="94" height="30" style="line-height:30px;"><strong>Link Profile</strong></td>
    <td width="188"  height="30" style="line-height:30px;"><strong>Download URL</strong></td>
    <td width="98" height="30" style="line-height:30px;"><strong>Downloads</strong></td>
  </tr>
 <?php while($row_pop=mysqli_fetch_assoc($pop_res))
  {
	  if($row_pop['urlid']>0){
		   $pop_des= getFileDescription($row_pop['urlid']);
  echo "<tr>";
    echo "<td class='blue'><a href='linkprofile.php?urlid=".$row_pop['urlid']."'>".getShortName($pop_des['showfilename'],10)."</a></td>";
   echo" <td title='".$pop_des['url']."' ><a href='".$pop_des['url']."'  target='_blank' class='t_head'>".getShortName($pop_des['url'],30)."</a></td>";
    echo "<td>".$row_pop['count']."</td>";
 echo " </tr>";
	  }}
 ?>
  
</table>
		<div class="view"><a href="popularlinks.php" class="abuttn">View All</a></div>

          </div>
            <div class="layouthd2" style="margin:20px 0 0 20px;">
        	<h2>Action Required</h2>
           	  <table width="437" border="0" cellpadding="5" cellspacing="0">
  <tr class="bg1">
    <td height="30" style="line-height:30px;"><strong>IP Addredss</strong></td>
    <td  height="30" style="line-height:30px;"><strong>Access Count</strong></td>
    <td height="30" style="line-height:30px;"><strong>Ban</strong></td>
  </tr>
    <?php
  
  
 
   while($row_act=mysqli_fetch_assoc($act_res))
  {
	  
	
  echo "<tr>";
    echo "<td class='blue'>".$row_act['ipaddress']."</td>";
  echo"<td>".$row_act['count']."</td>";
    echo "<td>";
	if(isBanIp($row_act['ipaddress'])){$cls= "banbtn"; $cn_msg="Are You Sure To Remove  Ban of IP: ";}
	  else{$cls= "removebanbtn";$cn_msg="Are You Sure To Ban IP:  ";}
      echo"<form method ='post' action='banip.php' onsubmit='return ban_confirm(\"".$row_act['ipaddress']."\",\"".$cn_msg."\")'>

      <input type='hidden' name='ip' value='".$row_act['ipaddress']."'/>
  
      <input type='submit' name='ban' value='' class='".$cls."'/>
  
      </form> ";
	
	
	echo "</td>";
 echo "</tr>";
  }?>
</table>
		<div class="view"><a href="actionrequire.php" class="abuttn">View All</a></div>

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