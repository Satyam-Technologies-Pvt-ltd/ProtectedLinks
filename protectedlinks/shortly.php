<?php 
include "includes/functions.php";

session_start(); $conn = dbConnect(); 

$sts=false;
if(isset($_POST['submit']))
    { 
	$error="";
	$fileuploaded=false;
	if(!isset($_POST['notadownload']) && isset($_FILES['u_file']['tmp_name']) && $_FILES['u_file']['tmp_name']!=''){
	$target_dir = getUploadsPathSetting();
//	echo $target_dir.'<br />';
	if(file_exists($target_dir)==FALSE)
	{
		$target_dir = substr($target_dir, 3);	
	}
//	echo $target_dir.'<br />';	exit;

	$target_file = $target_dir .'/'.time().'/'. basename($_FILES["u_file"]["name"]);
	$FileType = pathinfo($target_file,PATHINFO_EXTENSION);	
	
		if (!file_exists($target_dir .'/'.time().'/') && $target_dir!='') {
	    mkdir($target_dir .'/'.time().'/', 0777, true);
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
	  		
	    if($target_dir!=='' && strpos($target_file, $target_dir)!==FALSE){
     //	$url = secure_input( substr($target_file, 3),"string");
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
	
	
	$showfilename=secure_input($_POST['f_name'],"string");
	$title=secure_input($_POST['f_name'],"string");
	$description="";
	if(isset($_POST['iprestrict'])){
	$iprestrict=secure_input($_POST['iprestrict'],"string");
	
	}else{$iprestrict="";}
	if(isset($_POST['ipaccessno'])){
	$ipaccessno=secure_input($_POST['ipaccessno'],"int"); 
	}else{$ipaccessno=0;}
	///////////RESTRICTED IP////////////////////////
	if(isset($_POST['restrictedip'])){
	$restrictedip=secure_input($_POST['restrictedip'],"string");
	}
	else{
	$restrictedip="";
		
	}
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
	if(isset($_POST['exprespect'])){
	$exprespect=secure_input($_POST['exprespect'],"string");
	
	}
	else{
	$exprespect="";	
	}	
	$user_type=secure_input($_POST['usertype'],"string");
	//if Error Msg is not blank
	
	$notdownload =isset($_POST['notadownload'])? secure_input( $_POST['notadownload'],"int"):0; 


	if( $error=="") {
		
		
	$sql = "INSERT INTO `permissions` 
	( `url`, `showfilename`,`title`,`description`,`notdl`, `exptime`, `iprestrict`, `ipaccessno`,`restrictedip`, `usertype`,`expnos`,`exptimerespect`,`downcode`,`timestamp` )
	 VALUES ( '$url','$showfilename','$title','$description','$notdownload', '$exptime', '$iprestrict', '$ipaccessno','$restrictedip', '$user_type','$downatt','$exprespect','$downcode','$time')";  
	 
				$result=mysqli_query($conn,$sql) or die(mysqli_error($conn));	
				
                if( $result ){
               	$currentpage = currentpageurl()	;
               	$currentpage = explode('/',$currentpage);
               	$thispage= array_pop($currentpage);
               	$currentpage = implode('/',$currentpage);
					if(getUseHtaccess()==1){
				$msg='Link Created Successfully ,Your Download Link  is : <b><a href="'.$currentpage.'/'.$downcode.'" target="_blank" >'.$currentpage.'/'.$downcode.'</a></b>';
					}
					else{
						
						
				$msg='Link Created Successfully ,Your Download Link  is : <b><a href="'.$currentpage.'/download.php?downcode='.$downcode.'" target="_blank" >'.$currentpage.'/download.php?downcode='.$downcode.'</a></b>';
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

<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>PROTECTED LINKS | CREATE PROTECTED LINKS</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/jquery.ui.all.css" />
<script src="jquery/jquery-1.7.1.js"></script>
<script src="jquery/jquery.ui.core.js"></script>
<script src="jquery/jquery.ui.widget.js"></script>

    <script language="javascript" src="admin/js.js">
	</script> 
    <script language="javascript" >
	
	function validateMe(name)
	{
	
	switch(name)
	{
		
	case "url":
	urlValidate('admin/validate.php');
	break;
	
	case "file":
	fileValidate('admin/validate.php');
	break;	
	case "time":
	expTime('admin/validate.php');
	break;	
	
	case "allow_ip":
	allowIPValidate('admin/validate.php');
	break;
	
	case "ip_accessno":
	ipCountValidate('admin/validate.php');
	break;
	
	case "res_ip":
	restrictedIPValidate('admin/validate.php');
	break;
	 
	case "allow_atmp":
	 allowAttemptValidate('admin/validate.php'); 
	 break;
	 
	 case "f_name":
	 fileName('admin/validate.php');
	  break;
	}
	
		
	}	 
		 
	
	</script> 
 <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->  
</head>

<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
<div id="wrapper">
		<div id="header">
        		<div id="top_feader">
                	<div id="logo">
                    	<a href="index.php"><img src="images/logo.png" alt="" /></a>
                    </div>
                </div>
                <div id="navigation" >
                	<div id="left_nav" style="height:20px;">
                    	<ul>
                        	<li><a  href="shortly.php" class="active" style="height:20px;">Home</a></li>
                            <li><img src="images/nav_line.jpg" alt="" style="height:20px;" /></li>
                            <li><a href="admin/manage.php" style="height:20px;">Manage</a></li>
                                                    </ul>
                    </div>
                    <div id="right_nav">
                         <div id="download" style="font-size:12px;"><strong>Downloads</strong></div>
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

		</div><!--header end-->

  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post"  name= "frmmain" enctype="multipart/form-data" onsubmit="return ValidatonFinal('admin/validate.php');">
      <div id="layout"  class="col-lg-12">
        
       
        	<h2> Short Link   	  
        	</h2>
    	
        	
      <div class="row">
    <div class="col-sm-1" >
    </div>
	<div class="col-sm-10" style="padding-top: 40px; padding-bottom: 20px;">
	
	               <div>
        	<?php if( isset($msg) ) echo '<div align="center" class="alert alert-success">'.$msg ."</div>"?>

            </div> 
	<div id="msg_url" ></div>

		<div id="msg_file" ><?php if(isset($dl_error)){echo '<div class="anerror">
 <img src="images/error_icon.png" alt="" /><span>'.$dl_error.'</span></div>' ; }  ?></div>
 
 </div>
	</div>
     
          
  <div class="row">
    <div class="col-sm-1" >
	
	
	</div>
    <div class="col-sm-6">
	<div class="input-group input-group-lg">

					<span class="input-group-addon glyphicon glyphicon-link" id="basic-addon1"></span>      <input type="text" name="url" class="form-control"  placeholder="Enter Your URL"  id ="url" size="40" maxlength="300" 
 onchange="validateMe('url'); clearAll();" /></div>
	
	</div>
    <div class="col-sm-3">
    
<span class="btn btn-default btn-file btn-lg"> <span class="glyphicon glyphicon-cloud-upload"></span><input type="file" name="u_file"  id ="u_file"size="40" maxlength="300" 
 onchange="validateMe('file')"/></span>	<input type="submit" value="Protect" class="btn btn-primary btn-lg" name="submit"/>
	</div>
    <div class="col-sm-3">
	

 
 </div>
   
  </div>
  
  
               <h3>Protection</h3>
 


 
            
  <div class="row">
    <div class="col-sm-1" >
	
	
	</div>

    <div class="col-sm-3">
    
 <div class="input-group input-group-sm">

<input type="text" name="f_name"  id ="f_name" size="40" maxlength="255" 
 onchange="validateMe('f_name')" class="form-control"  placeholder="Title"/>
					</div>

	</div>
    <div class="col-sm-2">
	    	<div class="input-group input-group-sm">

<input name="downloadattempts" type="text" class="form-control"   id="allow_atmp"  onchange="validateMe('allow_atmp')" value="
<?php 
if(isset($error) && $error <>""){
echo $downatt;		
} 
?>
" maxlength="5" placeholder="Allowed Attempts"/>
					</div>
             
					   
 
 </div>
    <div class="col-sm-2">
    
 <div class="input-group input-group-sm">

<input name="exptime" type="text" class="form-control"   id="exptime"  onchange="validateMe('time')" value="
<?php 
if(isset($error) && $error <>""){
echo $downatt;		
} 
?>
" maxlength="5" placeholder="Expiry Time ( hrs )"/>
					</div>   
    	    	
	</div>
    <div class="col-sm-4">
   	<div id="msg_fname" ></div>
    <div id="msg_exptime"></div>
    <div id="msg_allow_atmp"  ></div>
    </div>
     
  </div>			  
  
              </div>

      
  <div class="row">
    <div class="col-sm-1" >
	
	
	</div>
    <div class="col-sm-3">
    <div class="switch-field">
               <div class="switch-title">For Multiple Users</div>
 <input type="radio" name="usertype" value="s" id="switch_left"  <?php 
if(isset($error) && $error <>""){
if($user_type=='s')
{echo "checked='checked'";}		
}else{echo "checked='checked'";} 
?> onchange=""/><label for="switch_left">Yes</label><input type="radio" name="usertype" value="m"  onchange=""  id="switch_right"
 <?php 
if(isset($error) && $error <>""){
if($user_type=='m')
{echo "checked='checked'";}		
} 
?>
 /><label for="switch_right">No</label></div>
	
	</div>
    <div class="col-sm-8"></div>
  </div>
        
</form>
                <div id="footer">
        	<div id="footerlayout">
        	<div id="footer_left"><p>&copy; 2010 All rights reserved.</p></div>
             <div id="footer_right"> <a href="shortly.php">Home</a>  |  <a href="index.php">Admin</a>  |  <a href="admin/manage.php">Manage Link</a>   |  <a href="admin/accesslogs.php">Analytics</a> | <a href="../doc/Documentation.pdf" target="_blank">Documentation</a></div>
        </div>
        </div>
</div>
</body>
</html>