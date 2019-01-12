
<div id="lower_header">
                	<form id="header_table" action="searchresult.php" method="get">
                		<table width="894" border="0" cellspacing="5">
  							<tr>
   							 	<td width="174"><input name="s_val" type="text" value="" />
						 	    <img src="../images/search_icon.png" alt="" /></td>
   							  <td width="170">
                                <?php 
								$option_selected="";
								$query_serach_setting="select default_search from settings LIMIT 1";
								$result_setting=mysqli_query($conn,$query_serach_setting)or die(mysqli_error($conn));
								if(mysqli_num_rows($result_setting))
								{
								$option_selected=mysqli_result($result_setting,0,"default_search");
								
								}
								
								?>
                              <div class="sty">  <select name="s_field" >
<!--<option value="">Search Field</option>-->
<option value="pfile" <?php if(strtolower($option_selected)=="pfile"){echo "selected='selected'";}?>>Profile Name</option>
<option value="dcode" <?php if(strtolower($option_selected)=="dcode"){echo "selected='selected'";}?>>Download Code</option>
<option value="file" <?php if(strtolower($option_selected=="file")){echo "selected='selected'";}?>>File Name</option>
<option value="ip"  <?php if(strtolower($option_selected=="ip") ){echo "selected='selected'";}?>>IP Address</option>

</select>
   </div>                            
                                <!--<input name="" type="text" value="File Name" />-->
   							<!--   <a href="#"><img src="../images/down_icon.png" alt="" /></a>--></td>
   							  <td width="160"><input name="s_fdate" type="text" value="" id="datepicker" />
   							 </td>
   							  <td width="177"><input name="s_tdate" type="text" value="" id="datepicker1" />
   							 </a></td>
                              <td width="173"><input name="send"  type="submit"  alt="" value="Go" /></td>
                                
						  </tr>
						</table>
                        </form>

                </div>
<!--<hr/>
<div style='width:100%; height:50px; background-color:#FDFDFD; padding-top:20px'>
<form action="searchresult.php" method="get">

<span style=" margin-left:50px;">Search <input type="text"  name='s_val' /></span>
<span style=" margin-left:30px;">
<select name="s_field" >
<option value="">Search Field</option>
<option value="pfile">Profile Name</option>
<option value="dcode">Download Code</option>
<option value="file">File Name</option>
<option value="ip">IP Address</option>

</select></span>
<span style=" margin-left:30px;">
From <input type="text" name="s_fdate" id="datestart"/>
</span>

<span style=" margin-left:20px;">
To <input type="text" name="s_tdate" id="dateend"/>
</span>
<span style=" margin-left:15px;">
 <input type="submit" name="send" value="Go.."/>
</span>
</form>
</div>
-->