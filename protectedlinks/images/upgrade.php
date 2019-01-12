<?php
include "includes/settings.php";
$conn = mysqli_connect(DBHOST, DBUSER, DBPASS);
mysqli_select_db( $conn,DBNAME);

upgrade_v_1_2_1($conn);
upgrade_v_1_2_3($conn);
upgrade_v_1_2_4($conn);

function upgrade_v_1_2_4($conn){

	$sql = "SELECT COUNT(*) 
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE   TABLE_SCHEMA = '".DBNAME."' AND
							TABLE_NAME = 'settings' AND 
                            COLUMN_NAME = 'uploads_path'";
 $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$result = mysqli_fetch_array($query); //print_r($result);
if($result['COUNT(*)']<1){
$sql1 = "AlTER TABLE settings ADD COLUMN uploads_path varchar(254) NOT NULL";
$query1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));

$sql2 = "update settings set uploads_path = '../../../../pldata' where id = 1";
$query2 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
}
}

function upgrade_v_1_2_3($conn){

	$sql = "SELECT COUNT(*) 
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE   TABLE_SCHEMA = '".DBNAME."' AND
					TABLE_NAME = 'settings' AND 
                            COLUMN_NAME = 'accesstime_f'";
$query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$result = mysqli_fetch_array($query);
if($result['COUNT(*)']<1){
$sql1 = "AlTER TABLE settings ADD COLUMN accesstime_f varchar(50) NOT NULL";
$query1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));
}
}

function upgrade_v_1_2_1($conn){

	$sql = "SELECT COUNT(*) 
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE   TABLE_SCHEMA = '".DBNAME."' AND
							TABLE_NAME = 'settings' AND 
                            COLUMN_NAME = 'default_timezone'";
$query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$result = mysqli_fetch_array($query);
if($result['COUNT(*)']<1){
$sql1 = "AlTER TABLE settings ADD COLUMN default_timezone varchar(225) NOT NULL";
$query1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));

$sql2 = "update settings set default_timezone = 'UTC'";
$query1 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
}
}

?>