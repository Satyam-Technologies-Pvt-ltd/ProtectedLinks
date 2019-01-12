<?php

include "includes/functions.php";
session_start(); $conn = dbConnect(); 

if(isset($_POST['submit'])){
$error = doLogin($_POST['username'],$_POST['password'],'userlogin', 'admin/index.php' );
if($error==false){$error="<div align='center'>Invalid Username and Password.</div>";}
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PROTECTED LINKS | LOGIN </title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
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
		
        <div id="login_bg">
        	<div id="login_layout">
        	<div id="down_logo"><a href="index.html"><img src="images/logo.png" alt="protect" /></a>
            </div>
           
            <div id="login_main">
            	<div class="login_layout">
        	<h2>Please Login</h2>
             <?php if(isset($error)){
    echo '<div class="table4"><img src="images/error_icon.png" alt="" /><span class="smspan">'.$error.'</span></div>';
}?>    
                        <form name="login" method="post">
           	  <div id="login">
                	
                  	<table width="200" border="0">
                    
  					
  						<tr>
    					  <td height="45"><p>Username</p></td>
    					  <td><label for="textfield"></label>
					      <input type="text" name="username" id="textfield" style="width:300px;" class="form-control" value="admin"/></td>
  						</tr>
                        <tr>
   						  <td height="45">Password</td>
   						  <td><label for="textfield"></label>
					      <input type="password" name="password" id="textfield" style="width:300px;" class="form-control" value="admin"/></td>
  						</tr>
					</table>

                </div>
		<!--<div class="login_button"> -->
        <input type="submit" name="submit"  class="login_button" value="LOGIN" />
       <!-- </div>-->
        </form>
	<!--<div class="forgot_link"><a href="#">Forgot you Pawword?</a></div>-->
          </div>
                </div>
                
                </div>
            </div>
          <div id="footer" style="clear:both;">
        	<div id="footerlayout">
        	<div id="footer_left"><p>&copy; 2010 All rights reserved.</p></div>
             <div id="footer_right"> </div>
        </div>
        </div>      
            
            </div>
      
       
        </div>
        
        
</div>
</body>
</html>