<?php
include "../includes/functions.php"; //including Function File
session_start(); $conn = dbConnect();  //Connecting to Database
checkLogin('../index.php');//is user is loged in if not redirect to login page
doLogout('../index.php'); //Log out redirect to Login page
?>
<?php 
$sort="id";
 $odb="DESC";
 $app_query=" ORDER BY ac_id DESC";
if(isset($_GET['odb']) && $_GET['odb']<>"")

{
	if($_GET['odb']=="ASC")
	{
    $odb="ASC";
	}else
	{  $odb="DESC";}
}
if(isset($_GET['sort'])&& $_GET['sort']<>"")
{	
$sort=$_GET['sort'];
switch($sort)
{
case "id":
$app_query=" ORDER BY ac_id  ". $odb;
break;
case "file":
$app_query=" ORDER BY showfilename  ". $odb;
break;
case "link":
$app_query=" ORDER BY url  ". $odb;
break;
case "ac_time":
$app_query=" ORDER BY time  ". $odb;
break;
case "ip":
$app_query=" ORDER BY ipaddress  ". $odb;
break;
case "ref":
$app_query=" ORDER BY  referer ". $odb;
break;
case "sts":
$app_query=" ORDER BY  status ". $odb;
break;
	
	
}

}
?>
<?php 
///////////////Pagination///////////////////////////////////
$curr_page=0;
if(isset($_GET['url_pro'])&& intval($_GET['url_pro'])>0)

{
	$page_name=$_SERVER['REQUEST_URI'];
	}else{
$page_name=$_SERVER['PHP_SELF'];}
if (strpos($page_name, '?') == false) {
  $page_name.="?";
} 

$max_rec=20;
$start=0;
//findng total record
if(isset($_GET['url_pro']) && intval($_GET['url_pro'])>0) 
{
	$url_pro=intval($_GET['url_pro']);
	$sql_total_query="SELECT COUNT(*) as total FROM accesslogs,permissions WHERE accesslogs.urlid=permissions.id AND urlid='{$url_pro}'";
	}else{
$sql_total_query="SELECT COUNT(*) as total FROM accesslogs,permissions WHERE accesslogs.urlid=permissions.id";
	}
$res_total=mysqli_query($conn,$sql_total_query);
$total=mysqli_result($res_total,0,"total");

$total_page=ceil($total/$max_rec)-1;
if(isset($_GET['page'])&& intval($_GET['page'])>0)
{
	$curr_page=intval($_GET['page'])-1;
	if($curr_page>$total_page)
    {$curr_page=0;}
    $start=$curr_page*$max_rec;	
	
}



//////////////////////////////////

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS|ACCESS LOGS</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="../css/jquery.ui.all.css">
<script src="../jquery/jquery-1.7.1.js"></script>
<script src="../jquery/jquery.ui.core.js"></script>
<script src="../jquery/jquery.ui.widget.js"></script>
<script src="../jquery/jquery.ui.datepicker.js"></script>
 <script language="javascript">
	
	//function for confomation When Delete Button is pressed
	
	function delete_confirm(ac_id)
	{	
	var res=confirm("Are You Sure To Delete Access Log No.: "+ac_id);
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
        
        
        <div id="layout">
        	<div id="layouthd">
            <div id="heading">
        	<h2>Access logs</h2>
            </div>
             <?php
		  if(isset($_GET['url_pro']) && intval($_GET['url_pro'])>0) 
{
	
	$url_pro=intval($_GET['url_pro']);
		  $sql="SELECT  permissions.url,permissions.downcode,permissions.showfilename,accesslogs.id as ac_id,accesslogs.urlid,accesslogs.time,accesslogs.ipaddress,accesslogs.status,accesslogs.referer from permissions,accesslogs WHERE accesslogs.urlid=permissions.id  AND accesslogs.urlid='{$url_pro}' ".$app_query." LIMIT $start,$max_rec";
}else{
	$sql="SELECT  permissions.url,permissions.downcode,permissions.showfilename,accesslogs.id as ac_id,accesslogs.time,accesslogs.urlid,accesslogs.ipaddress,accesslogs.status,accesslogs.referer from permissions,accesslogs WHERE accesslogs.urlid=permissions.id   ".$app_query." LIMIT $start,$max_rec";
	
	}  //toggle orderby
		   if ($odb=="ASC"){$odb="DESC";}else{$odb="ASC";}?>
    <table border="0" cellpadding="5" cellspacing="0">
    <tr class="bg1">
    <td width="44" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=id&odb=".$odb."'  class='t_head'><strong>No.</strong> <div class='link'>";
	 if($odb=="DESC" and $sort=='id'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='id'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?>
	 
	 </td>
	 
    <td width="130" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=file&odb=".$odb."' class='t_head'><strong>File Name</strong> <div class='link'>";
	 if($odb=="DESC" and $sort=='file'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='file'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?>
	 
    
    </td>
    <td width="139" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=link&odb=".$odb."' class='t_head'><strong>Download URL</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='link'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='link'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?>
	
	</td>
    <td width="147" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=ac_time&odb=".$odb."' class='t_head'><strong>Access Time</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='ac_time'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='ac_time'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?></td>
    <td width="105" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=ip&odb=".$odb."' class='t_head'><strong>IP Address</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='ip'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='ip'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?>
    
    </td>
    <td width="107" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=ref&odb=".$odb."' class='t_head'><strong>Referer</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='ref'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='ref'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?>
    </td>
    <td width="80" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=sts&odb=".$odb."' class='t_head'><strong>Status</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='sts'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='sts'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</div></a>";
	 ?></td>
    <td width="61" height="30" style="line-height:30px;"><strong> Del</strong>
     </td>
  </tr>
 
  <?php 
  $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	 
	if( mysqli_num_rows($result)>0 ) //if Number Of Record Is Grater Then 0
	
	{
	
		
		$srn=0;
    while($row = mysqli_fetch_array($result)) //Loop to Create Table Row
    { 
	$srn++;
	if ($srn%2==0){$class='class="bg1"';}else{$class="";}
	
    echo " <tr".$class.">";
 
    echo " <td>".$row['ac_id']."</td>";
   
    echo "<td class='blue'><a href='linkprofile.php?urlid=".$row['urlid']."'>".getShortName($row['showfilename'],23)."</a></td>";
	
    echo " <td title='".$row['url']."'>".getShortName($row['url'],20)."</td>";
    $tformat =getAccessTimeFormatSetting();
    echo " <td>".timeInterval($row['time'],date('y-m-d H:i:s'), $tformat)." </td>";
   
    echo "<td>".$row['ipaddress']."</td>";
 
    if($row['referer']=="Direct"){$referer="Direct";}else{$referer="Indirect";}
	
    echo "<td title='".$row['referer']."'>".$referer."</td>";
	
	if(trim($row['status'])=='d'){$img="../images/auth.jpg"; $accesstxt = "Authorized";}else{$img="../images/unauth.jpg";$accesstxt = "Unauthorized";}
	

   echo " <td ><a href=\"#\" title=\"{$accesstxt}\"><img src='".$img."' alt=\"\" /></a></td>";
   echo " <td><span class=\"break\">
   <form method ='post' action='delaccesslog.php' onsubmit='return delete_confirm(\"".$row['ac_id']."\")'>
    <input type='hidden' name='access_id' value='".$row['ac_id']."'/>
    <input type='submit' name='delete' value='' class='delbtn' />
    </form>
  </td>";
 echo "</tr>";
  }
  }
 ?>
 
</table>

            </div>
            <div id="layoutbtm">
            	<table width="250" border="0">
                
                
  <tr>
  <?php
$prev=$curr_page;
if($prev<=0){$prev=1;}
echo "<td>Page</td>";
  echo "<td width='40'>";
echo "<div class='small'><select name='gotopage' onchange='location=this.options[this.selectedIndex].value'>";
  
for($a=1;$a<=$total_page+1;$a++)
{
	
	 if($a-1==$curr_page){$sel="selected='selected'";}else{$sel="";}
	
     echo "<option value='".$page_name."&page=".($a)."'".$sel.">".$a."</option>";
		

}
echo "</select></div></td>";
echo "<td width='70'><a href='".$page_name."&page=".$prev."'class='btnaslink'>PREVIOUS</a></td>";
$pg_count=$curr_page;
if($pg_count==0){$pg_count=1;}

for($i=1;$i<=3;$i++)
{
	if($pg_count==$curr_page+1)
	{
		$cls="hilightpageno";
	}
		else{
		$cls="";}
	
	if($pg_count>$total_page+1){break;}
echo " <td width='17'><a href='".$page_name."&page=".($pg_count)."' class='".$cls."'> ".($pg_count)." </a></td>";
	//}
	$pg_count++;
}

$next=$curr_page+2;
if($curr_page-2>$total_page){$next=1;}
echo "<td width='63'><a href='".$page_name."&page=".$next."'class='btnaslink'>NEXT</a></td>";//}

echo "</td>";
?>
  
  </tr> 
</table>

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