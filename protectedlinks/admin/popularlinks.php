<?php
include "../includes/functions.php"; //including Function File
session_start(); $conn = dbConnect();  //Connecting to Database
checkLogin('../index.php');//is user is loged in if not redirect to login page
doLogout('../index.php'); //Log out redirect to Login page
 
///////////////Pagination///////////////////////////////////
$curr_page=0;
$curr_page=0;
$page_name=$_SERVER['PHP_SELF'];
if (strpos($page_name, '?') == false) {
  $page_name.="?";
} 
$max_rec=25;
$start=0;

//findng total record
$sql_total_query="SELECT  COUNT( * ) AS count
FROM accesslogs
GROUP BY urlid
ORDER BY count DESC  ";
$res_total=mysqli_query($conn,$sql_total_query);
$total=mysqli_num_rows($res_total);
$total_page=ceil($total/$max_rec)-1;
if(isset($_GET['page'])&& intval($_GET['page'])>0)
{
	$curr_page=intval($_GET['page'])-1;
	if($curr_page>$total_page)
    {$curr_page=0;}
    $start=$curr_page*$max_rec;	
	
}
//////////////////////////////////

///////////Sorting////////////////////////

///////////////////////shorting
$sort="id";
 $odb="DESC";
 $app_query=" ORDER BY id DESC";
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
$app_query=" ORDER BY id  ". $odb;
break;
case "file":
$app_query=" ORDER BY urlid ". $odb;
break;
case "link":
$app_query=" ORDER BY urlid  ". $odb;
break;

case "dwn":
$app_query=" ORDER BY count ". $odb;
break;

	
	
}

}



///////////////////////////////////
$pop_query="SELECT urlid, COUNT( * ) AS count
FROM accesslogs
GROUP BY urlid
".$app_query." LIMIT $start,$max_rec
";
$pop_res=mysqli_query($conn,$pop_query) or die(mysqli_error($conn));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | POPULAR LINKS</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.ui.all.css">
<script src="../jquery/jquery-1.7.1.js"></script>
<script src="../jquery/jquery.ui.core.js"></script>
<script src="../jquery/jquery.ui.widget.js"></script>
<script src="../jquery/jquery.ui.datepicker.js"></script>
<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "../images/calendar_icon.png",
			buttonImageOnly: true
		});
		$( "#datepicker1" ).datepicker({
			
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
                    	<a href="index.html"><img src="../images/logo.png" alt="" /></a>
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
        	<h2>Popular Link's</h2>
            </div>
            <?php  if ($odb=="ASC"){$odb="DESC";}else{$odb="ASC";}?>
   
           	  <table border="0" cellpadding="5" cellspacing="0">
  <tr class="bg1">
    <td width="59" height="30" style="line-height:30px;">
	
	<?php echo "<a href='".$page_name."&sort=id&odb=".$odb."'class='t_head' ><strong>ID.</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='id'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='id'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</a></div>";
	 ?></td>
    <td width="189" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=file&odb=".$odb."' class='t_head'><strong>Link Profile</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='file'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='file'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</a></div>";
	 ?></td>
    <td width="365" height="30" style="line-height:30px;">
	<?php echo "<a href='".$page_name."&sort=link&odb=".$odb."' class='t_head'><strong>Protected Links</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='link'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='link'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</a></div>";
	 ?></td>
    <td width="110" height="30" style="line-height:30px;"><strong><?php echo "<a href='".$page_name."&sort=dwn&odb=".$odb."' class='t_head'>Downloads</strong><div class='link'>";
	 if($odb=="DESC" and $sort=='dwn'){
	 echo "<img src='../images/sortdown.jpg' alt='#' />";}
	 else if($odb=="ASC" and $sort=='dwn'){
	  echo "<img src='../images/shortup.jpg' alt='#' />";
	 
	 }
	 
	 echo "</a></div>";
	 ?>
	</td>
    <td width="51" height="30" style="line-height:30px;"><strong>View</strong>
      </td> <td width="45" height="30" style="line-height:30px;"><strong>Edit</strong>
     </td>
  </tr>
  <?php
 
    while($pop_row=mysqli_fetch_assoc($pop_res))
   {
	  
	     
        $f_info=getFileDescription($pop_row['urlid']);
		
   echo "<tr>";
   echo " <td>".$pop_row['urlid']."</td>";
   echo " <td class='blue'><a href='linkprofile.php?urlid=".$pop_row['urlid']."'>".getShortName($f_info['showfilename'],25)."</a></td>";
  echo "  <td><a href='".$f_info['url']."' title='".$f_info['url']."' target='_new' class='t_head'>".getShortName($f_info['url'],50)."</td>";
   echo "<td class='red'>".$pop_row['count']."</td>";
    echo "<td><span class='break'><a href='linkprofile.php?urlid=".$pop_row['urlid']."' ><img src='../images/eye_icon.png' alt='#' /></a></span></td>";
	echo "<td><span class='break'><form method ='post' action='Editurl.php'>
  
  <input type='hidden' name='urlid' value='".$pop_row['urlid']."'/>
  <input type='submit' name='edit' class='editbtn' value=''/>
  
  
  </form> </span></td>";
  echo '</tr>';
   }?>
</table>

            </div>
<div id="layoutbtm">
            	<table width="200" border="0" cellspacing="0">
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
             <div id="footer_right"><a href="index.php">Home</a>  |  <a href="createurl.php">Crate Link</a>  |  <a href="manage.php">Manage Link</a>   |  <a href="accesslogs.php">Access logs</a></div>
        </div>
        </div>
        </div>
</div>
</body>
</html>