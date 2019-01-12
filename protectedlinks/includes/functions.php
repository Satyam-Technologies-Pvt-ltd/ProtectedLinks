<?php 
/*including settings file*/
include_once ("settings.php"); 
/*************Login System*****************/
//Do Login
function doLogin($username, $password, $usertable, $successurl){
	$conn = dbConnect();
	$username = trim($username);
	$password = trim($password);
    $password= trim($password);
    $password=md5($password);
$query_user = "SELECT * FROM {$usertable} WHERE username= '{$username}' AND password = '{$password}' LIMIT 1";
$result_user = mysqli_query($conn, $query_user )	or die( mysqli_error($conn) );

if( mysqli_num_rows($result_user)==1){
	$_SESSION['userid'] = mysqli_result($result_user, 0, 'id');
	$_SESSION['username'] = mysqli_result($result_user, 0, 'username');
	$_SESSION['usergroup']=mysqli_result($result_user,0,'group');
	header('Location:'.$successurl);
    exit;
    } else{

	return false;
     
	  }
} 
//check Login
function checkLogin($loginpage){

if(!isset($_SESSION['userid'])|| !isset($_SESSION['username'])||$_SESSION['username']=="" || $_SESSION['userid']=="" ){
	header('Location:'.$loginpage);

}
} 
 //Loout
function doLogout($loginpage){
  if(isset($_GET['logout'])){
	  
  $_SESSION['userid']="";
  
  $_SESSION['username']="";
  $_SESSION['usergroup']=="";
  unset($_SESSION);
  session_destroy();
  header('Location:'.$loginpage);
  }
} 
//Change Password
function changePassWord($username, $password,$newpassword,$usertable)
{ global $conn;
	$retval=false;
    $username = trim($username);
	//echo "user name:".$username."<br/>";
	$password = trim($password); 
	//echo "Password old".$password."<br/>";
    $password = md5($password);
	//echo "encrypted password".$password."<br/>";

	$newpassword=trim($newpassword);
	//echo "new password ".$newpassword."<br/>";
	//checking user has entered correct old password
    $query = "SELECT * FROM {$usertable} WHERE username= '{$username}' AND password = '{$password}' LIMIT 1";
    $result = mysqli_query($conn, $query)	or die( mysqli_error($conn) );
	if(mysqli_num_rows($result)==1 ) //if password is correct then change to new 
	{
		
	$newpassword=md5($newpassword);
	$query2="UPDATE {$usertable} SET password = '{$newpassword}' WHERE 	username= '{$username}'";
	$q_res=mysqli_query($conn,$query2)or die(mysqli_error($conn));
	
	$retval= true;	
	//echo "password changed <br/>";
	
	}else{
		
		$retval= false;
		}
	return $retval;
	
}
/*************Database Functions*****************/
/*function for database connection*/
//Connect To Database
function dbConnect(){	
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS) or die("MySQL Error: " . mysqli_error($conn));
mysqli_select_db($conn,DBNAME ) or die("MySQL Error: " . mysqli_error($conn));
date_default_timezone_set(getTimezoneSetting());
return $conn;
} 
//get Link Permission.
function getLinkPermissions($downloadcode){
$conn = dbConnect();	
$downloadcode=secure_input($downloadcode,"string");	
$query = "SELECT * FROM permissions WHERE downcode = '{$downloadcode}' LIMIT 1";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$rowcount = mysqli_num_rows($result);
$linkperm = array();
if($rowcount > 0){
$linkperm['urlid']  =  mysqli_result($result, 0, 'id');
$linkperm['url'] =  mysqli_result($result, 0, 'url');
$linkperm['showfilename']=mysqli_result($result, 0, 'showfilename');
$linkperm['title']=mysqli_result($result,0,'title');
$linkperm['description']=mysqli_result($result, 0, 'description');
$linkperm['usertype'] =  mysqli_result($result, 0, 'usertype');
$linkperm['iprestrict'] = mysqli_result($result, 0, 'iprestrict');
$linkperm['ipaccessno'] = mysqli_result($result, 0, 'ipaccessno');
//restricted ip
$linkperm['restrictedip'] = mysqli_result($result, 0, 'restrictedip');
//
$linkperm['expnos']  = mysqli_result($result, 0, 'expnos');
$linkperm['exptime']  = mysqli_result($result, 0, 'exptime');
$linkperm['firstaccess']   = mysqli_result($result, 0, 'firstaccess');
$linkperm['timestamp']= mysqli_result($result, 0, 'timestamp');
$linkperm['exptimerespect'] = mysqli_result($result, 0, 'exptimerespect');
$linkperm['notdl'] = mysqli_result($result, 0, 'notdl');
return $linkperm;
}
else{
    return false;
}
} 
///getting Link Permission for Admin purpose.
function getLinkPermissionsAdmin($urlid){
	global $conn;
$urlid=secure_input($urlid,"string");
$query = "SELECT * FROM permissions WHERE id= '{$urlid}' LIMIT 1";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$rowcount = mysqli_num_rows($result);
$linkperm = array();
if($rowcount > 0){
$linkperm['urlid']  =  mysqli_result($result, 0, 'id');
$linkperm['url'] =  mysqli_result($result, 0, 'url');
$linkperm['showfilename']=mysqli_result($result, 0, 'showfilename');
$linkperm['title']=mysqli_result($result,0,"title");
$linkperm['description']=mysqli_result($result, 0, 'description');
$linkperm['usertype'] =  mysqli_result($result, 0, 'usertype');
$linkperm['iprestrict'] = mysqli_result($result, 0, 'iprestrict');
$linkperm['ipaccessno'] = mysqli_result($result, 0, 'ipaccessno');
///restricted ip
$linkperm['restrictedip'] = mysqli_result($result, 0, 'restrictedip');
/////
$linkperm['expnos']  = mysqli_result($result, 0, 'expnos');
$linkperm['exptime']  = mysqli_result($result, 0, 'exptime');
$linkperm['firstaccess']   = mysqli_result($result, 0, 'firstaccess');
$linkperm['timestamp']= mysqli_result($result, 0, 'timestamp');
$linkperm['exptimerespect'] = mysqli_result($result, 0, 'exptimerespect');
$linkperm['downcode'] = mysqli_result($result, 0, 'downcode');

return $linkperm;
}
else{
    return false;
}
} 
//create AccessLog .
function createLog($urlid, $status,$referer ){
$local_ip =  getip();
	global $conn;
$date=date('Y-m-d H:i:s',time());
$query_log = "INSERT INTO accesslogs (time,urlid, status, ipaddress, referer) VALUES('{$date}','{$urlid}', '{$status}', '{$local_ip}','$referer')" ;
$result_log = mysqli_query($conn,$query_log) or die(mysqli_error($conn));    
if($result_log){return false;}
else {return true;}

} 
//Getting Last Access Date Of Link.
function LinkLastAccessedDate($linkid)
	{
			global $conn;
		$return_on_not_found='New';
	if(checkVar($linkid))
	{
	$query_link = "SELECT MAX(time) AS time FROM accesslogs WHERE urlid = '{$linkid}'  LIMIT 1";
    $result_link = mysqli_query($conn,$query_link)	or die(mysqli_error($conn));	
	if(mysqli_num_rows($result_link)==1){	
        $time = mysqli_result($result_link,0,'time');
		if($time==""){return $return_on_not_found;}
        return $time;
        }else{return $return_on_not_found;}
		
	}else{return $return_on_not_found;}	
		
	}
	
//Delete Link
function deleteLink($urlid)
{
		global $conn;
   if(checkVar($urlid)){
   $urlid=secure_input($urlid,"int");
   $link_query="DELETE FROM permissions WHERE id='{$urlid}'";
   $res=mysqli_query($conn,$link_query)or die(mysqli_error($conn));
   return true;
	}else{
	return false;	
	}
	
}
//Deleting Access Logs based on Url
function deleteAccessLogOnUrl($urlid)
{
		global $conn;
if(checkVar($urlid)){
   $urlid=secure_input($urlid,"int");
   $link_query="DELETE FROM accesslogs WHERE urlid='{$urlid}'";
   $res=mysqli_query($conn,$link_query)or die(mysqli_error($conn));
   return true;
   }else{
	return false;	
   }	
}
//Deleting Access Log on Access id
function deleteAccessLogOnAccessId($access_id)
{
		global $conn;
if(checkVar($access_id)){
   $urlid=secure_input($access_id,"int");
   $link_query="DELETE FROM accesslogs WHERE id='{$access_id}'";
   $res=mysqli_query($conn,$link_query)or die(mysqli_error($conn));
   return true;
   }else{
	return false;	
   }	
}
//Deleting All logs Where urlid=0 means direct accressd file Which is not present 
function deleteAccessLogOfInvalidLinks()
{
		global $conn;

   $urlid=secure_input($urlid,"int");
   $link_query="DELETE FROM accesslogs WHERE urlid='0'";
   $res=mysqli_query($conn,$link_query)or die(mysqli_error($conn));
   return true;
  
}

//Download Today
function numberOfDownloas($param='today')
{
		global $conn;	
switch($param)
{	
case "today":
$today=Date("Y-m-d");

$sql="SELECT Count( id ) AS total
FROM accesslogs
WHERE time LIKE '$today%'
AND STATUS = 'd'";
break;
case "week":
//$date=Date("Y-m-d H:i:s");
$date=strtotime("-1 week");
$date=Date("Y-m-d H:i:s",$date);

$sql="SELECT Count( id ) AS total
FROM accesslogs
WHERE time >= '$date'
AND STATUS = 'd'";
break;

default:
break;
return false;	
}
$result=mysqli_query($conn,$sql) or die (mysqli_error($conn));
$total=mysqli_result($result,0,'total');	

return $total;
}
//get All Applied Restriction
function getRestrictions($urlid)
{
		global $conn;
$sql="SELECT * FROM permissions WHERE id='{$urlid}'";

$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

$rowcount = mysqli_num_rows($result);

$retVal="No Restriction";

if($rowcount > 0){
	
$usertype=  mysqli_result($result, 0, 'usertype');

if($usertype=='s')

{$retVal="Single User";}
else
{$retVal="Multiple User";}

$ipres = mysqli_result($result, 0, 'iprestrict');
if($ipres<>"")
{$retVal." | Allowed Ip"; }

$ipcount = mysqli_result($result, 0, 'ipaccessno');
if($ipcount>0)
{$retVal.=" | IP Count";}

$res_ip = mysqli_result($result, 0, 'restrictedip');
if($res_ip<>""){$retVal.=" | Restricted IP";}

$down_attp  = mysqli_result($result, 0, 'expnos');

if($down_attp >0)
{$retVal.=" | Downloas Attampt";}

$exp_time  = mysqli_result($result, 0, 'exptime');
if($exp_time>0){$retVal.=" | Time Restriction";}

$download_critria= mysqli_result($result, 0, 'exptimerespect');
if($download_critria="L"){$retVal.=" | After Link Creation";}
else{$retVal.=" | After First Download";}


}
return $retVal;
}
//no Of Download of a Link
function getDownloadCount($urlid,$param='d')
{
		global $conn;
switch($param)
{
case "d":
$sql="SELECT COUNT(*)as total FROM accesslogs WHERE status='d' AND urlid='{$urlid}'";
break;
case "f":
$sql="SELECT COUNT(*)as total FROM accesslogs WHERE status='f' AND urlid='{$urlid}'";
}
$res=mysqli_query($conn,$sql)or die(mysqli_error($conn));
$total=mysqli_result($res,0,"total");
return $total;
}
//Maximum Attampt By An IP
function getMaxAttamptByIP($urlid,$param='d')
{
		global $conn;
switch($param)
{
case "d":
$sql="SELECT `ipaddress` , COUNT( * ) AS total
FROM accesslogs
WHERE urlid = '{$urlid}'
AND STATUS = 'd'
GROUP BY `ipaddress`
ORDER BY total DESC";
break;
case "f":
$sql="SELECT `ipaddress` , COUNT( * ) AS total
FROM accesslogs
WHERE urlid = '{$urlid}'
AND STATUS = 'f'
GROUP BY `ipaddress`
ORDER BY total DESC";
}
$res=mysqli_query($conn,$sql)or die(mysqli_error($conn));
if(mysqli_num_rows($res)==0){
	
	return 0;}else{
$total=mysqli_result($res,0,"total");
return $total;}
}

//is The Ip is Ban

function isBanIp($ip=0)
{
		global $conn;
	if($ip==0){
$current_ip=getip();
	} else{
		$current_ip=$ip;
		}
$sql="SELECT ip FROM banip WHERE ip='$current_ip'";
$res=mysqli_query($conn,$sql) or die(mysqli_error($conn));
if(mysqli_num_rows($res)>0)
{return true;}
else{return false;}
	
}
/**********Utility Functions*****************/
//Get User IP
function getip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
} 
// Generate Random Code 	 
function randomcode(){	                               
$nofiles = 10000;
for($i=0; $i< strlen($nofiles)*2; $i++){
$randchars[] = chr(rand(97,122));
 }
$timestring = (string)time();
$code = '';	
$i = 0;
foreach($randchars as $randchar){
$code .= $randchar;
    if($i<strlen($timestring)){
    $code .= $timestring{$i};
    }
$i++;	
}
return $code;
} 
//Get Reffer
function getfererer(){
    
       $local_ip = getip();
       if(isset($_SERVER['HTTP_REFERER'])){
       $referer = $_SERVER['HTTP_REFERER'];
	   return $referer;
       }
      else{
         $referer = "Direct";  
         return $referer;
}
}
//Checkvars
function checkVar($var){
		global $conn;
   if((isset($var))&& !empty($var)&&($var!='')&&($var!=false) && ($var!=0)){
	
    return true;
   }
   else {return false;}
} 
//securing user input
function secure_input($value,$datatype)
{global $conn;
	
switch($datatype)
{
case "int": return intval($value);
case "double": return doubleval($value);
case "string":
case "text":
$value=strip_tags($value);
$value=mysqli_real_escape_string($conn, $value);
return $value;
default:return false;
}
	
}
//Time Interval
function timeInterval($firstTime, $lastTime, $timeformat=''){
	
    // convert to unix timestamps
	
    $firstTime=strtotime($firstTime);
   
    $lastTime=strtotime($lastTime);
   
   if($firstTime>$lastTime)
   {
	return false;   
   }
   
   // perform subtraction to get the difference (in seconds) between times
   
     $difference=$lastTime-$firstTime;
   
     //convert to week
 
  
     $week=$difference/604800; //7 *24 *3600=604800
     
     if($timeformat!=''){
     	return date($timeformat,$firstTime);
     }
   
     $week=abs(floor($week));
   
     if( $week > 4 ){ //if week is more then 4 return date.
   
	 return date("d-m-Y",$firstTime);
	 
	 }else if($week <=4 && $week>0){// if week less then 4 calclate Days Gap
   
	
	 $days=abs(floor(($difference-($week*604800))/86400));//24*3600=86400
	
	 return $week." Week & ".$days." Days ago"; //return days and week
	 
     }else if($week==0){ //if week is zero then we only calculat days nad hours
	   
	 $days=abs(floor($difference/86400));
	
	 if($days!=0){ //if days >0 the calculate hours
		
	 $hour=abs(floor(($difference-($days*86400))/3600));
	 
	 return $days." Day & " .$hour ." hour ago" ;	 //return days and hours
	
	 }else{// if day is zero calculate hours and minutes 
		
	 $hour=abs(floor($difference/3600));
	
	 if($hour!=0){ //if hour >zero calculate min 
		
	 $mins=abs(floor(($difference-($hour*3600))/60));
	 return $hour." Hour & ".$mins." Minutes ago ";
	
	 }else{//if all conditions fail then calculate minutes and return 
		
	 $mins=abs(floor($difference/60));
	 if($mins==0){return "Few seconds ago ";}//if minutes=0 return few seconds
	 else{
	 return $mins ." Minutes ";}
	 }
    }
  }
}
/**********Core Functions*****************/
//permission By Ip
function permissionByIP($iprestrict){
	  
    if($iprestrict<>""){
		
    $local_ip = trim(getip());
  
    $ipaddressarr= explode(',', $iprestrict);

    if(in_array($local_ip, $ipaddressarr)==true){
      
     $permit = true;   
    }
	
    else{
		
    $permit = false; 
	
    }
	
}

else {$permit = true; }

return $permit;

}
//Checking Ip is restricted or not 
function checkRestrictedIP($restrictedip){ //ok


    if($restrictedip<>""){ //if This Restriction is set
		
    $local_ip = trim(getip());    //getting current ip
 
  
    $ipaddressarr= explode(',', $restrictedip); //get an array of restricted ip from comma seperated string
	//echo "<br/>Restricted IP";
	
    if(in_array($local_ip, $ipaddressarr)==true){ //if current ip is in restricted ip array
	
     $permit = false;  //permisssion not granted
	 //echo "return ning False";
	   
    } else{            //else ip not found in restrictesd ip array then permission granted
		
		//echo "returning true ip not found in list";
    $permit = true;  
	
    }
	return $permit;
	
   } else {     //if restricted ip is blank then this permission is not ser :permission granted
	//echo "retruning Default";
	$permit = true; 
	
	}

return $permit;

} 
 
//permission By Ipcount
function permissionByIPCount($urlid,$ipaccessno,$usertype){
		global $conn;
    if( checkVar($ipaccessno) ){
		
	$ipaccessno = secure_input($ipaccessno, "int");
	
    //QUERY TO FIND DISTINCT IP FROM ACCESS LOG GET IP
   
    $query_access = "SELECT DISTINCT ipaddress FROM accesslogs WHERE urlid = '{$urlid}'  AND status = 'd'";
	    
    $result_access = mysqli_query($conn,$query_access) or die(mysqli_error($conn)); 
	 
    $rowcount = mysqli_num_rows($result_access);
	
	
	/*IF TOTAL NUMBER OF ALLOWED IP IS LESS THEN DISTINCT IP FOUND IN ACCESSLOG TWO CONDITION AGAIN EVALUATED
	 1.current ip already hava accessed this link :-Permission Granted .  
	 2.current ip accessing link first time :then check two condition 
	 a)more acesscount is allowed: permission granted .
	 b)current ip excedded no of accescount allowed : permission not granted;
	*/
	
    if( $rowcount <= $ipaccessno ){ 
	
	$current_ip=getip();
	
	//QUERYTO FIND ENTRY OF SUCCESSFUL DOWNLOAD BY CURRENT IP
	
    $query_check= "SELECT  id FROM accesslogs WHERE urlid = '{$urlid}'  AND status = 'd' AND ipaddress='{$current_ip}'";
	 
	$check_ip_res=mysqli_query($conn, $query_check)or die(mysqli_error($conn));
	
	if(mysqli_num_rows($check_ip_res)){//if current ip is in access log Then Permission Granted
	
        $permit = true;
	
       	return $permit;
		
   } else { //if current ip doed not successfuly download then 
		       
	if( $rowcount+1 <= $ipaccessno ){//is more ip acesscount is allowed premission granted.
		
		$permit = true;
		
		return $permit;
		
	  } else {  //otherwise permission not granted
	
	  $permit = false;
	
	  return $permit;
	
	  }
            
     }
      
   } else  { //if accesscount excedded accesscount then permisssion false
	
    $permit = false;
	
	return $permit;
	
    }
	   
    } else {
	
	// Default Condition Executed if this restriction is not set"
			
	$permit = true;
	
    return $permit;
	
	} //not set return true

}
//Permission By accessCount
function permissionByAccessCount($urlid, $expnos, $usertype){ 
	global $conn;
    if(checkVar($expnos)){
		
	$urlid=secure_input($urlid,"int");
	
    if( $usertype == 'm' ){
		
    $local_ip = getip();
	
    $query_access = "SELECT * FROM accesslogs WHERE urlid = '{$urlid}' AND status = 'd' AND ipaddress = '{$local_ip}'"; 
	
    } else{
		
    $query_access = "SELECT * FROM accesslogs WHERE urlid = '{$urlid}' AND status = 'd'";
	
    }
	
    $result_access = mysqli_query($conn,$query_access) or die(mysqli_error($conn));
	  
    $rowcount = mysqli_num_rows($result_access);
	
     if( $rowcount < $expnos ){  
	 
    $permit = true;  
	  
    } else {
		
    $permit = false; 
	      
    } 
	  
    } else {
		
	$permit = true; 
	
	}
	
    return $permit;
} 
//Permission By Expiry Time 
function permissionByExpiryTime($urlid, $exptime, $linkcreationtime, $firstaccess, $exptimerespect,$usertype ){
		global $conn;
    if(checkVar($exptime)){
         
          $currtime = time();
		
		 
        $urlid= secure_input($urlid, "int");
		 
      
       
        if( checkVar($firstaccess)==false ){ //if $firstaccess is blank or Null Then set $firstaccess
            
		$firstaccessdb = date("Y-m-d H:i:s", $currtime); 
          
        $query_facess = "UPDATE permissions SET firstaccess = '{$firstaccessdb}' WHERE id = '{$urlid}' LIMIT 1";
           
		$result_faccess = mysqli_query($conn,$query_facess) or die(mysqli_error($conn));
		
		$firstaccess = strtotime(date("Y-m-d H:i:s",$currtime));
		
		
        } else {
		$firstaccess = strtotime($firstaccess);
			}
		
        if( $exptimerespect=='L' || $usertype == 'm' ){ 
 
       
        $timediff = $currtime-strtotime($linkcreationtime);
       
        if( $timediff < $exptime*3600 ){
       
	    $permit = true;   
           
		}
            
	    else{
           
		$permit = false;   
        
		}
        
		}
       
	    else{
          
		$timediff = $currtime - $firstaccess;
		   
		
           
		if( $timediff < $exptime *3600){
                 
		$permit = true;   
      
		 }
          
		else{
          
		$permit = false;
      
         }   
        
		 }
      } 
    
    else {$permit = true; }
  
    return $permit;
    
    } 
	
// getting download  path
	
function downloadPath()
	{
		
	if( @$_SERVER["HTTPS"] == "on" ){
		
	$address="https://" ;
	
	} else { 
	
	$address = "http://";
	
	}

    if ($_SERVER["SERVER_PORT"] != "80") {
	   
    $address .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	
    } else {
		
    $address .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	
    }
	$download_path=dirname(dirname($address));
   return $download_path;

}

//Fila Extension


function fileexten($filename){                          // Get File extension from filename string.

$filenamesplit =explode('.',$filename);
$extension = $filenamesplit[count($filenamesplit)-1];
return $extension;
} 

//////fileDescription
function getFileDescription($urlid)
{   
	global $conn;
    $ret=array();
	$ret['showfilename']="0";
	$ret['url']="Invalid Attampt Made By Users";
	if($urlid==0)
	{
	return $ret;
	}
	else
	{
$query="SELECT  url,showfilename FROM permissions WHERE id='{$urlid}'";
$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
if(mysqli_num_rows($res)==0){$ret['showfilename'] ="";$ret['showfilename']=""; return $ret;}
$ret['showfilename']  =  mysqli_result($res, 0, 'showfilename');
$ret['url'] =  mysqli_result($res, 0, 'url');
return $ret;
    }
	
}
//Shorting The String

function getShortName($string, $length = 35 )
 {
  $retval="";
  $string=trim($string);
  $str_length=strlen($string);
  if($str_length>=$length)
  {
	$retval=substr($string,0,$length);
	$retval.="....";  
	return $retval;
  }	
  return $string; 
	 
}    
//Fakefile name 
function fakefilename($code){   
		global $conn;
$client_ip =   getip();

$query_f = "SELECT showfilename FROM permissions WHERE downcode ='$code' LIMIT 1";
$result_f = @mysqli_query($conn,$query_f)or die(mysqli_error($conn));    //Function to get pretended filename while downloading file. 
//writedberror($result_f);
        if(mysqli_num_rows($result_f)==1){	
        $fakefname = mysqli_result($result_f,0,'showfilename');
        
        }
		else 
        {$fakefname = "download"; }
        
          return $fakefname;
        } 	
//Function returns Mime type depending


function contenttype($ext){                               
$mime_types=array();
$mime_types['ai']    ='application/postscript';
$mime_types['asx']   ='video/x-ms-asf';
$mime_types['au']    ='audio/basic';
$mime_types['avi']   ='video/x-msvideo';
$mime_types['bmp']   ='image/bmp';
$mime_types['css']   ='text/css';
$mime_types['doc']   ='application/msword';
$mime_types['eps']   ='application/postscript';
$mime_types['exe']   ='application/octet-stream';
$mime_types['gif']   ='image/gif';
$mime_types['htm']   ='text/html';
$mime_types['html']  ='text/html';
$mime_types['ico']   ='image/x-icon';
$mime_types['jpe']   ='image/jpeg';
$mime_types['jpeg']  ='image/jpeg';
$mime_types['jpg']   ='image/jpeg';
$mime_types['js']    ='application/x-javascript';
$mime_types['mid']   ='audio/mid';
$mime_types['mov']   ='video/quicktime';
$mime_types['mp3']   ='audio/mpeg';
$mime_types['mpeg']  ='video/mpeg';
$mime_types['mpg']   ='video/mpeg';
$mime_types['pdf']   ='application/pdf';
$mime_types['pps']   ='application/vnd.ms-powerpoint';
$mime_types['ppt']   ='application/vnd.ms-powerpoint';
$mime_types['ps']    ='application/postscript';
$mime_types['pub']   ='application/x-mspublisher';
$mime_types['qt']    ='video/quicktime';
$mime_types['rtf']   ='application/rtf';
$mime_types['svg']   ='image/svg+xml';
$mime_types['swf']   ='application/x-shockwave-flash';
$mime_types['tif']   ='image/tiff';
$mime_types['tiff']  ='image/tiff';
$mime_types['txt']   ='text/plain';
$mime_types['wav']   ='audio/x-wav';
$mime_types['wmf']   ='application/x-msmetafile';
$mime_types['xls']   ='application/vnd.ms-excel';
$mime_types['zip']   ='application/zip';
	if(array_key_exists($ext,$mime_types)){
	$mimetype = $mime_types[$ext];
	}
    else{ $mimetype = 'application/force-download';}
return $mimetype;
}
     
////////////////VALIDATION FUNCTION//////////////////////////////
function isValidURL($url)
{ 
if(strpos($url, 'http')===FALSE && strpos($url, 'ftp')===FALSE && strpos($url, 'https')===FALSE  ) {
		return file_exists($url);
}
if(@fopen($url,'r')==false) {
	return false;
}
 
if($_SERVER['HTTP_HOST']=='localhost')
	{ 
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url); 
	}
else
	{
return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url);
}
}
//Validating IP
function isValidIP($ip)
{
	return preg_match( "/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $ip);
	
}
//Validating Integer
function isPositiveInt($number)
{
	
return preg_match("/^[0-9]+$/", $number);
	
}
//refine String of ip
function refineIpString($ip_string)
{
	
 
 $ip_string=trim($ip_string);//removes leading and ending Whitespace char
 
 
$ip_string = preg_replace('/[\r\n]+/', ",", $ip_string);

$ip_string=preg_replace('/[ \t]+/', '',$ip_string);


 $last = $ip_string[ strlen($ip_string) - 1 ];// finding last char 
 
 if(!isAlphaNumeric($last) )
 
 {  //if last char is is not alpha-numeric  then must remove it
 
 $ip_string=substr( $ip_string, 0, strlen($ip_string) - 1 );//getting refiend string 
 
 }
 
 return $ip_string;
 
}
 //is string Alphanumeric
function isAlphaNumeric($string)
 
 {
	 return preg_match("/^[a-zA-Z0-9]$/",$string);
	 
	}

function replaceCommaNewLinePattern($string)
{
	$string = preg_replace("/,\n/", ",",$string);

//$string = $string.replace(/\n,/g, ",");
$string = preg_replace("/\n,/", ",",$string);
	return $string;
}
//get a list of invalid ip Iddress
function getInvalideIpList($ip_string)
{
 $ip_string=refineIpString($ip_string);//getting Refined IpString


 $pattern="/[,\n\r]/"; //string seperation char: , nad new line char  /n 
          
 $iparray=preg_split($pattern, $ip_string);	 //break ipstring into array 

 $invalid_ip=""; //initially invalid ip is blank

 foreach( $iparray as $value )
    {
	
	 $value=trim($value); //trimming ip one by one
	 
	 if( ! isValidIP($value) )
	 {
		//if aftter trimming ip not valid appand invali_ip string
	  
		$invalid_ip.=$value;
		$invalid_ip.=" , ";
		
	  }
	 
    }
 
   if($invalid_ip=="")//if invalid ip is blank means all ip is in correct format return zero
   {
	   
    return false;
 
   } 
   else
   {
	  //otherwise return invalid ip list String for message
   return $invalid_ip;
   }
 }
//matching Two Ip List
function matchIp($ip1,$ip2)
 {
	 
	 $matched_ip="";
	 if($ip1=="" or $ip2==""){return false;}
	 $ip1=refineIpString($ip1);
	 $ip2=refineIpString($ip2);
	 $ip_ar_1=explode(",",$ip1);
	 $ip_ar_2=explode(",",$ip2);
	 foreach($ip_ar_1 as $value)
	 {
	 if (in_array($value,$ip_ar_2))
	 {
     $matched_ip.=$value." , ";
	 }
	 
	 }
	 $matched_ip=trim($matched_ip);
	 return $matched_ip;
	 
 }
//adding Http At the begining of Url if not exist
function addHttp($url) { //echo strstr($url,'a');
    if (!preg_match("~^(?:f|ht)tps?://~i", $url) && !file_exists($url) ) {
        $url = "http://" . $url;
     }
    return $url;
}
//Checking The File Type is Image 
function isValidImageFile($filename)
{
$filearray = explode('.', $filename);	
$ext = end($filearray);
$ext=strtolower($ext);
$imgValidExt= array("jpg","jpeg","gif","bmp","png");
	if(in_array($ext,$imgValidExt))
	{return true;}
	else
	{
	return false;
	}
}
//getting File Extension
 function getFileExtension($filename)
{
$filearray = explode('.', $filename);
$ext = end($filearray);
$ext = substr(strrchr($filename, '.'), 1);
$ext = substr($filename, strrpos($filename, '.') + 1);
$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $filename);
$exts = explode("[/\\.]", $filename);
$n = count($exts)-1;
$ext = $exts[$n];
return $ext;
}

function getTimezoneSetting(){
	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS) or die("MySQL Error: " . mysqli_error($conn));
	mysqli_select_db($conn,DBNAME ) or die("MySQL Error: " . mysqli_error($conn));	
	$query = "select default_timezone from settings";
	$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	return mysqli_result($result, 0, 'default_timezone');
}

function getAccessTimeFormatSetting(){
		$conn = dbConnect();
	$query = "select accesstime_f from settings";
	$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
	return mysqli_result($result, 0, 'accesstime_f');
}

function getUseHtaccess(){
			$conn = dbConnect();
	$query = "select usehtaccess_f from settings";
	$result = mysqli_query( $conn,$query) or die(mysqli_error($conn));
	return mysqli_result($result, 0, 'usehtaccess_f');
}

function getUploadsPathSetting(){
		global $conn;
	$query = "select uploads_path from settings";
	$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
	return mysqli_result($result, 0, 'uploads_path');
}

function getContentType($url){
	$headers = 	@get_headers($url); //print_r($headers);
	$contenttype = 'text/html';
	if(empty($headers)){
		return '';
	}
	foreach($headers as $key=>$value){
		if($key == 'Content-Type'|| strpos($value, 'Content-Type')!==FALSE){
			$contenttype = is_array($value)?$value[0]: $value;
		}
	}
	$contenttype = str_replace(array('Content-Type', ':', ' '), '',$contenttype);
	
	return $contenttype;
}

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
} 

function currentpageurl(){                               //function sdl_to return current page url                        
return (!empty($_SERVER['HTTPS']) ? 'https://': 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
?>