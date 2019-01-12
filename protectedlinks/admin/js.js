// JavaScript Document
//for setting page
  function change_chkbx_val()
	 {

	 if($("#p_logo_chbx").checked==true)
	 {
	 $("#p_logo_chbx").val("yes");
	 }else
	 {
	 $("#p_logo_chbx").val("no");
	 }
	 
	 }
	  function getFile(){
     
	   
		
		 $("#upfile").click();
		  
    }
	 function showfilename()
	 {
	  $("#s_file").val($("#upfile").val());
	 
	 }
	//setting page function end
////this function is used to select the text in browser
function selectText(id) {
 
    if ( document.body.createTextRange) { // for IE
        var range =  document.body.createTextRange();
        range.moveToElementText(document.getElementById(id));
        range.select();
    } else if (window.getSelection) { //for  moz, opera, webkit
        var selection = window.getSelection();            
        var range =  document.createRange();
        range.selectNodeContents(document.getElementById(id));
        selection.removeAllRanges();
        selection.addRange(range);
    }
}


function disable_multiple(rVal)
	{
		
	
		var radioVal=rVal;
		
		
		
		if(radioVal=='m')
		{
			document.forms["frmmain"].exprespect[1].checked = false;
			
			document.forms["frmmain"].exprespect[0].checked = true;
			
			$("#fdln").hide();
		 $("#fdln1").hide();
		
		} else {
		
			$("#fdln").show();
			$("#fdln1").show();
			}
		
		}
//================================================================
var url_sts="";
var fname_sts="";
var des_sts="";
var exptime_sts="";
var alip_sts="";
var ipc_sts="";
var resip_sts="";
var alatp_sts="";
function urlValidate(vurl)
	{ 
	errorimg = ((typeof vurl === 'undefined') ? "../images/error_icon.png" : "images/error_icon.png");
	infoimg = ((typeof vurl === 'undefined') ? "../images/information_icon.png" : "images/information_icon.png");		
		
	vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;	
	msg="";
	
	//	alert(infoimg);
	$("#url").val( refineString($("#url").val()));
	myVal=$("#url").val();
//	alert(myVal.indexOf($("#u_file").val()));
	if(($("#url").val()=='' || myVal.indexOf($("#u_file").val()) < 0 )&& vurl!='validate.php' ){
		$("#u_file").replaceWith($("#u_file").clone(true)); //alert($("#u_file").val());
		$("#u_file").val(""); 
	}
 
	$("#url").val(myVal);//alert(myVal);
	if(myVal==""){
		$("#msg_url").html(); return;}
		url_sts="X";
	 $.post(vurl, { name: "url", value: myVal }, function(data) { //alert(data);
		if(myVal.match("^(http|https|ftp)://")==null && data.toUpperCase()!='AFILE'   )
		{
				 myVal="http://"+$("#url").val();
				 $("#url").val(myVal);
		}
	    if(data.toUpperCase()=='VALID'||data.toUpperCase()=='AFILE' )
		 { //alert(vurl);
		 	if(vurl=='validate.php'|| myVal.match("^(http|https|ftp)://")!=null){
			msg='<div class="table3"><img src="'+infoimg+'" alt="" /><span>Valid</span></div>';	 
			}
			else if(vurl!='validate.php' && myVal.match("^(http|https|ftp)://")==null){
			msg='<div class="anerror"><img src="'+ errorimg+'" alt="" /><span>Relative File Paths allowed after <a href="index.php" target="_blank">Login</a>.</span></div>';
			}
		}	
		else if(data.toUpperCase()=='WEBPAGE'){
		
		msg='<div class="table3"><input type="checkbox" class="notadownload" name="notadownload" value="1"><img src="'+infoimg+'" alt="" /><span>Valid! Show As Web Page or Image</span></div>';	
		}
		else{
			msg='<div class="table4"><img src="'+ errorimg+'" alt="" /><span>'+data+'</span></div>';
		
			}
      $("#msg_url").html(msg);
	  url_sts=data;
    })

	}
	
	function fileValidate(vurl){
		

	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';	 			
	 vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;	
	 
	 $("#msg_url").text("");
	 	msg = "";
		 $("#msg_url").html(msg);	
		file = $("#u_file").val(); //alert(file);
	 $.post(vurl, { name: "file", value: file }, function(data) {	
	 	if(data!='INVALID'){ 
	 	$("#url").val(data) ;//alert($("#url").val());
 	/*	msg='<div class="table3"><input type="checkbox" class="notadownload" name="replacefile" value="1"><img src="../images/information_icon.png" alt="" /><span>Replace file if it  already exists. </span></div>';	*/
		// $("#msg_url").html("");	
	 	}
		});
		fileurl = $("#u_file").val() ;
	 $.post(vurl, { name: "fileurl", value: fileurl}, function(data) {	
	 	if(data=='EXISTS' && vurl=='validate.php'){ 
 		msg='<div class="table3"><input type="checkbox" class="notadownload" name="replacefile" value="1"><img src="'+infoimg+'" alt="" /><span>Replace file if it  already exists. </span></div>';	
		 $("#msg_file").html(msg);	
	 	}
	 	else{
	 	msg = "";
		 $("#msg_file").html(msg);	
	 	}
		})		
	}
	function fileName(vurl)
	{
	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';		
		$("#msg_fname").show();

		$("#f_title").val($("#f_name").val());
				
	 $("#f_name").val( refineString($("#f_name").val()));
	 myVal=$("#f_name").val();	
	 
	 if(myVal=="")
	 {
		  fname_sts="X"
		  msg='<div class="table4"><img src="'+errorimg+'" alt="" /><span>Please Enter a Title /Name Of File</span></div>';
	 $("#msg_fname").html(msg)	; 
	
	 }
	 else{$("#msg_fname").hide();
	 fname_sts="VALID";
	 
	 }
	 
	}
	
	function expTime(vurl)
	{
	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';		
			 vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;
		$("#msg_exptime").show();
$("#msg_exptime").html('');

	 $("#exptime").val( refineString($("#exptime").val()));
	 myVal=$("#exptime").val();
	exptime_sts="X";
     $.post(vurl, { name: "pi", value: myVal }, function(data) {
		 if(data.toUpperCase()=="VALID")
		 {
			// alert( $("#msg_exptime").html());
		 $("#msg_exptime").html('');
		 
	     $("#msg_exptime").hide();
		 exptime_sts=data;
		 }
		 
		 else if(myVal!=0 || myVal!=""){  
		
	msg='<div class="table4"><img src="'+errorimg+'" /><span>'+data+'</span></div>';
		 $("#msg_exptime").html(msg);
		
		 exptime_sts="X";
		 }
   
    })
	}
	
	function allowIPValidate(vurl)
	{
			 vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;
	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';			 
			 vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;		
   $("#iprestrict").val( refineString($("#iprestrict").val()));
   $("#msg_allowip").show();
   myVal=$("#iprestrict").val();

   myVal=removeCommNewLinrCombination(myVal);
   
   resIP=$("#res_ip").val();
	 
   resIP= removeCommNewLinrCombination(resIP);
   
   if(myVal!="")
   {
	   $("#ipaccessno").val(""); 
	   $("#ipaccessno").attr("disabled",true);
	   $("#ipaccessno").css("background-color","#D8D8D8");	
   }
   else
   {
	   
	$("#ipaccessno").attr("disabled",false);
	  $("#ipaccessno").css("background-color","white");	
   }
  
  alip_sts="X";
    $.post(vurl, { name: "allow_ip", value: myVal ,rIP: resIP}, function(data) {
     if(data.toUpperCase()=="VALID"){$("#iprestrict").val(myVal);
	  $("#msg_allowip").html('');
	 $("#msg_allowip").hide();
	 alip_sts=data;
	 }else if(data==""){
		  $("#msg_allowip").html('');
	 $("#msg_allowip").hide();
	  alip_sts="";
		 }
	 else {  
	
	msg='<div class="table4"><img src="'+errorimg+'" /><span>'+data+'</span></div>';
		 
		
		 $("#msg_allowip").html(msg);alip_sts=data;}
    })

	}
	
	function ipCountValidate(vurl)
	{
			 
	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';		
	vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;
		$("#msg_ipaccessno").show();
		$("#ipaccessno").val( refineString($("#ipaccessno").val()));
		myVal=$("#ipaccessno").val();
	
		myVal=$.trim(myVal);
		
		 if(myVal!="")
   {
	   $("#iprestrict").val(""); 
	   $("#iprestrict").attr("disabled",true);
	     $("#iprestrict").css("background-color","#D8D8D8");	
   }
   else
   {
	   
	$("#iprestrict").attr("disabled",false);
	 $("#iprestrict").css("background-color","white");
   }
   ipc_sts="X";
     $.post(vurl, { name: "ip_accessno", value: myVal }, function(data) {
    
	if(data.toUpperCase()=="VALID"){$("#msg_ipaccessno").hide();
	ipc_sts=data;
	
	}else if(data==""){
		 $("#msg_ipaccessno").html('');
$("#msg_ipaccessno").hide();
		 }
	 else {  
	
	msg='<div class="table4"><img src="'+errorimg+'" /><span>'+data+'</span></div>';
		 
	
	
	$("#msg_ipaccessno").html(msg);
	ipc_sts=data;
	}
	
    })


  
	}
	
	
	function restrictedIPValidate(vurl)
	{
	
		
	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';		 
 vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;
	$("#msg_resip").show();
     $("#res_ip").val( refineString($("#res_ip").val()));
     resIP=$("#res_ip").val();
	 
	 resIP= removeCommNewLinrCombination(resIP);
	 $("#iprestrict").val( refineString($("#iprestrict").val()));
     alIP=$("#iprestrict").val();
     alIP=removeCommNewLinrCombination(alIP);
	 resip_sts="X";
     $.post(vurl, { name: "res_ip", value: resIP,aIP: alIP }, function(data) {
    if(data.toUpperCase()=="VALID"){$("#res_ip").val(resIP);
	$("#msg_resip").hide();
	resip_sts=data;
	}else if(data==""){
		$("#msg_resip").html('');
$("#msg_resip").hide();
resip_sts="";
		 }
	 else { 
	msg='<div class="table4"><img src="'+errorimg+'" /><span>'+data+'</span></div>';
		 
	$("#msg_resip").html(msg);
	resip_sts=data;
	}
    })
	}

	function allowAttemptValidate(vurl)
	{
 	
 	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';	 
vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;
	$("#msg_allow_atmp").show();
	$("#allow_atmp").val( refineString($("#allow_atmp").val()));
	myVal=$("#allow_atmp").val();
   
	//$("#msg_allow_atmp").load("validate.php?name=allow_atmp&value="+myVal);
  alatp_sts="X";
		 $.post(vurl, { name: "allow_atmp", value: myVal }, function(data) {
			 if(data.toUpperCase()=="VALID")
			 { 
			  $("#msg_allow_atmp").hide();
			 alatp_sts=data;
			
			 }else if(data==""){
		   $("#msg_allow_atmp").html('');
          $("#msg_allow_atmp").hide();
		 }
	 else { 
	msg='<div class="table4"><img src="'+errorimg+'" /><span>'+data+'</span></div>';
		 
		$("#msg_allow_atmp").html(msg);
		alatp_sts=data;
	
		}
     
    })
	}
	function refineString(string)
	{
		string=$.trim(string);
		var lastChar = string.substr(string.length - 1);
		if(!isAlphaNumeric(lastChar)){string=string.substr(0,string.length - 1); }
		
		string=removeSpaces(string) ;
		return string;

	}
	function isAlphaNumeric(str) 
	{
    return /^[a-zA-Z0-9]$/.test(str);
    }
	function removeSpaces(string) {
 return string.split(' ').join('');
}
function removeCommNewLinrCombination(string)
{
	string=$.trim(string);
	
	
string = string.replace(/,\n/g, ",");

string = string.replace(/\n,/g, ",");
string = string.replace(/\n/gm,",");
	return string;
	
	
}
function ValidatonFinal(vurl)
{
	errorimg = (typeof vurl === 'undefined') ? '../images/error_icon.png' : 'images/error_icon.png';
	infoimg = (typeof vurl === 'undefined') ? '../images/information_icon.png' : 'images/information_icon.png';	
	vurl = (typeof vurl === 'undefined') ? 'validate.php' : vurl;
	//mUrl= $.trim($("#msg_url").html());
	mUrl= url_sts; // alert(mUrl);
//	alert(vurl+" " +mUrl.toUpperCase());
//	alert($("#f_name").val()=="");

	myVal = $("#url").val();
	fVal = $("#u_file").val(); //alert(fVal);
//	alert(myVal+" "+fVal);
//	alert(mUrl.toUpperCase());
    if(mUrl=="")

	{
		if($("#url").val()==""){
			msg='<div class="anerror"><img src="'+errorimg+'" alt="" /><span>Url  can not be Blank</span></div>';
		$("#msg_url").html(msg);
	//	alert('dfs');
	return false; }
	
	}
	
	else   if(mUrl.toUpperCase()!="VALID" && mUrl.toUpperCase()!="WEBPAGE"  && mUrl.toUpperCase()!="AFILE"  
	&& (myVal.indexOf(fVal) < 0 || fVal=="") && vurl!='validate.php' )
	{ //alert("sc");
			msg='<div class="anerror"><img src="'+errorimg+'" alt="" /><span>The URL, file or webpage is invalid.</span></div>';
		$("#msg_url").html(msg);		
		return false;}
	else   if(vurl!='validate.php'  && mUrl.toUpperCase()=="AFILE" && ( myVal.indexOf(fVal) < 0 || fVal=="")  )
	{
						msg='<div class="anerror"><img src="'+ errorimg+'" alt="" /><span>Relative File Paths allowed after <a href="index.php" target="_blank">Login</a>.</span></div>';
		$("#msg_url").html(msg);		
		return false;}	
 
   // fName= $("#msg_fname").html();
   fName= fname_sts;
	fName=$.trim(fName);//alert(fName);
	msg='<div class="table4"><img src="'+errorimg+'" alt="" /><span> File Name can not be Blank</span></div>';
	if(fName=="")
	{
		if( $("#f_name").val()==""){
		$("#msg_fname").html(msg);
			return false;}
	}else if(fName.toUpperCase()!="VALID"){
		$("#msg_fname").html(msg);
		
	return false;
		
		}

    //eTime=$.trim($("#msg_exptime").html());
	eTime=exptime_sts;
	if(eTime==""){}
    else if(eTime.toUpperCase()!="VALID" ){ 
	
	
	
	return false;}
	
//	alIP=$.trim( $("#msg_allowip").html());
alIP=alip_sts;
	if(alIP==""){}
   else if(alIP.toUpperCase()!="VALID"){ 
   
   
	
   return false;}
	
	ipAcc=ipc_sts;
	//ipAcc= $.trim($("#msg_ipaccessno").html());
	if(ipAcc==""){}
	
	else  if(ipAcc.toUpperCase()!="VALID"){ 
		
	
	return false;}
	  
	rIP=  resip_sts;
	 // rIP= $.trim($("#msg_resip").html());
	  if(rIP==""){}
	    else if(rIP.toUpperCase()!="VALID"){
				   
			 return false;}
	alAtm=	 alatp_sts;
		//alAtm=$.trim($("#msg_allow_atmp").html());
		if(alAtm==""){}
	   else if(alAtm.toUpperCase()!="VALID"){  return false;}
	   
	}
	///Pagination 
	//table paging ip allow ip
 function PaginatePermittedIP(){
 var rows=$('#ip1').find('tr').length; 
 var no_rec_per_page=5; 
 var no_pages= Math.ceil(rows/no_rec_per_page);
 var $pagenumbers=$('<div id="layoutbtm1"></div>');
 $('<td id="ipp">PREVIOUS</td>').appendTo('.ipnav1 table tr '); 
    
 for(i=0;i<no_pages;i++)  
  {   
   $('<td><span class="page" >'+(i+1)+'</span></td>').appendTo('.ipnav1 table tr ');  
   }  
   $('<td id="ipn">NEXT</td>').appendTo('.ipnav1 table tr ');  
  
  $('.page').hover( function(){$(this).addClass('hover');},function(){$(this).removeClass('hover');}); 
  $('#ip1').find('tr').hide(); 
  var tr=$('#ip1 tr');  
  for(var i=0;i<=no_rec_per_page-1;i++)  
  {
	  $(tr[i]).show();
    
  } 
 
  $('#layoutbtm1  table tr td span').click(function(event){
	 
	curr_page=$(this).text()-1;
  $('#ip1').find('tr').hide();
     $("#layoutbtm1  table tr td span:eq("+(curr_page)+")").css("font-weight", "bold");
     $("#layoutbtm1  table tr td span:lt("+(curr_page)+")").css("font-weight", "normal");
	   $("#layoutbtm1  table tr td span:gt("+(curr_page)+")").css("font-weight", "normal");
  for(var i=($(this).text()-1)*no_rec_per_page; i<=$(this).text()*no_rec_per_page-1; i++) 
  {       
  $(tr[i]).show();   
 
   } 
   $('#ipapage').val(curr_page+1);
  
   });
   //previous 
	$('#ipp').click(function(event){
		$('#ipapage').val($('#ipapage').val()-1);
  if($('#ipapage').val()<=0){return;}
	cp=$('#ipapage').val()-1;
  $('#ip1').find('tr').hide();
  for(var i=($('#ipapage').val()-1)*no_rec_per_page; i<=$('#ipapage').val()*no_rec_per_page-1; i++) 
  {      

  $(tr[i]).show();   
 
   } 
     $("#layoutbtm1  table tr td span:eq("+(cp)+")").css("font-weight", "bold");
     $("#layoutbtm1  table tr td span:lt("+(cp)+")").css("font-weight", "normal");
	   $("#layoutbtm1  table tr td span:gt("+(cp)+")").css("font-weight", "normal");
  
  
		
		
   });
   
   	
		//next
  $('#ipn').click(function(event){
	//alert($('#ipapage').val());
	
if( $('#ipapage').val()>=no_pages || $('#ipapage').val()<0){$('#ipapage').val(0)}
	 
  $('#ip1').find('tr').hide();
cp1=Number($('#ipapage').val());

  for(var i=($('#ipapage').val())*no_rec_per_page; i<=($('#ipapage').val())*no_rec_per_page+no_rec_per_page-1; i++) 
  {      
  
  $(tr[i]).show();   
 
   } 
  $('#ipapage').val(Number($('#ipapage').val())+1);
  
 
 $("#layoutbtm1 table tr td span:eq("+(cp1)+")").css("font-weight", "bold");
     $("#layoutbtm1  table tr td span:lt("+(cp1)+")").css("font-weight", "normal");
	   $("#layoutbtm1 table tr td span:gt("+(cp1)+")").css("font-weight", "normal");
	  
	 if(cp1<=0)
	   {
		   $("#layoutbtm1  table tr td span:eq("+(0)+")").css("font-weight", "bold");
     //$("#layoutbtm2  table tr td span:lt("+(cp1)+")").css("font-weight", "normal");
	   $("#layoutbtm1 table tr td span:gt("+(0)+")").css("font-weight", "normal");
		   
		   }

   });	
	}
	//Table PAgin Restrivted Ip////////
	function PaginateRestrictedIP(){
var rows=$('#ip2').find('tr').length; 
		
 var no_rec_per_page=5; 
 var no_pages= Math.ceil(rows/no_rec_per_page);
var $pagenumbers=$('<div id="layoutbtm2"></div>');
 
$('<td id="ipp1">PREVIOUS</td>').appendTo('#layoutbtm2  table tr ');  

//alert($('#iprpage').val()); 
 for(var i=Number($('#iprpage').val())-1;i<no_pages;i++)  
  {  

 // alert($('#iprpage').val());
   $('<td><span class="page">'+(i+1)+'</span></td>').appendTo('#layoutbtm2  table tr ');  
 
   }  
   $('<td id="ipn1">NEXT</td>').appendTo('#layoutbtm2  table tr');  
  
 $('.page').hover( function(){$(this).addClass('hover');},function(){$(this).removeClass('hover');}); 
  $('#ip2').find('tr').hide(); 
  var tr=$('#ip2 tr');  
  for(var i=0;i<=no_rec_per_page-1;i++)  
  {$(tr[i]).show();
    
  } 
 
  $('#layoutbtm2  table tr td span').click(function(event){
	curr_page=$(this).text()-1;
  $('#ip2').find('tr').hide();
   $("#layoutbtm2  table tr td span:eq("+(curr_page)+")").css("font-weight", "bold");
     $("#layoutbtm2  table tr td span:lt("+(curr_page)+")").css("font-weight", "normal");
	   $("#layoutbtm2  table tr td span:gt("+(curr_page)+")").css("font-weight", "normal");
  for(var i=($(this).text()-1)*no_rec_per_page; i<=$(this).text()*no_rec_per_page-1; i++) 
  {       
  $(tr[i]).show();   
 
   } 
   $('#iprpage').val(curr_page+1);
 
   });
   //previous 
	$('#ipp1').click(function(event){
		
		
		
		
	//	if($('#iprpage').val()<0){$('#iprpage').val(1);return;}
		$('#iprpage').val($('#iprpage').val()-1);
  if($('#iprpage').val()<=0){return;}
	cp=$('#iprpage').val()-1;
  $('#ip2').find('tr').hide();
  for(var i=($('#iprpage').val()-1)*no_rec_per_page; i<=$('#iprpage').val()*no_rec_per_page-1; i++) 
  {      

  $(tr[i]).show();   
 
   } 
     $("#layoutbtm2  table tr td span:eq("+(cp)+")").css("font-weight", "bold");
     $("#layoutbtm2  table tr td span:lt("+(cp)+")").css("font-weight", "normal");
	   $("#layoutbtm2  table tr td span:gt("+(cp)+")").css("font-weight", "normal");
  
		
   });
   
   	
		//next
  $('#ipn1').click(function(event){
		//	alert("NEXT:"+$('#iprpage').val());
		
if($('#iprpage').val()>=no_pages || $('#iprpage').val()<0){$('#iprpage').val(0)}

	 
  $('#ip2').find('tr').hide();
cp1=$('#iprpage').val();
  for(var i=($('#iprpage').val())*no_rec_per_page; i<=($('#iprpage').val())*no_rec_per_page+no_rec_per_page-1; i++) 
  {      
  
  $(tr[i]).show();   
 
   } 

  $('#iprpage').val(Number($('#iprpage').val())+1);
  
 $("#layoutbtm2  table tr td span:eq("+(cp1)+")").css("font-weight", "bold");
     $("#layoutbtm2  table tr td span:lt("+(cp1)+")").css("font-weight", "normal");
	   $("#layoutbtm2  table tr td span:gt("+(cp1)+")").css("font-weight", "normal");
	 
	 
	 if(cp1<0)
	   {
		   $("#layoutbtm2  table tr td span:eq("+(0)+")").css("font-weight", "bold");
     //$("#layoutbtm2  table tr td span:lt("+(cp1)+")").css("font-weight", "normal");
	   $("#layoutbtm2  table tr td span:gt("+(0)+")").css("font-weight", "normal");
		   
		   }
   });	}
   
   function resetFormElement(e) {
  e.wrap('<form>').closest('form').get(0).reset();
  e.unwrap();

  // Prevent form submission
  e.stopPropagation();
  e.preventDefault();
}

function clearAll(){
	 $('.alert').text("");
	  $('#msg_url').text("");
 $('.error').text("");
 $('.msg_url').text("");
  $('.msg_file').text("");
   $('#msg_fname').text("");
  $('#msg_exptime').text("");
    $('#msg_allow_atmp').text("");
 $('.alert').hide();
	
}