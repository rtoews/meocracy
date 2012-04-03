<?php

//function dbconnect(){
$conn = mysql_connect("meocracy2.db.3410806.hostedresource.com","meocracy2" , "Badlsd22") or die(mysql_error());
mysql_select_db('meocracy2', $conn) or die(mysql_error());
//}

/////////////////////////// Session functions ///////////////////////////////

function on_session_start($save_path, $session_name) {
	error_log($session_name);
}

function on_session_end() {
	// Nothing needs to be done in this function since we used a persistent connection.
}

function on_session_read($key) {
	error_log($key);
	$stmt = "select CityKey from tsiSession ";
	$stmt .= "where SessionID ='$key' ";
	$stmt .= "and unix_timestamp(SessionExpiration) > unix_timestamp(date_add(now(),interval 1 hour))";
	$sth = mysql_query($stmt);

	if($sth)
	{
		$row = mysql_fetch_array($sth);
		return($row['CityKey']);
	}
	else
	{
		return $sth;
	}
}
function on_session_write($key, $val) {
	error_log("$key = $value");
	$val = addslashes($val);
	$insert_stmt  = "insert into tsiSession values('$key', ";
	$insert_stmt .= "'$val',unix_timestamp(date_add(now(), interval 1 hour)))";   ///////////////////////////// 1 hour session length ////////////////////////////

	$update_stmt  = "update tsiSession set CityKey ='$val', ";
	$update_stmt .= "SessionExpiration = unix_timestamp(date_add(now(), interval 1 hour))";
	$update_stmt .= "where SessionID ='$key '";
	
	// First we try to insert - if that doesn't succeed it means the session is already in the table and we try to update
	
	mysql_query($insert_stmt);
	
	$err = mysql_error();
	
	if ($err != 0)
	{
		error_log( mysql_error());
		mysql_query($update_stmt);
	}
}

function on_session_destroy($key) {
	mysql_query("delete from tsiSession where SessionID = '$key'");
}

function on_session_gc($max_lifetime) 
{
	mysql_query("delete from tsiSession where unix_timestamp(SessionExpiration) < unix_timestamp(now())");
}
	
    	    
// Set the save handlers
session_set_save_handler("on_session_start",   "on_session_end",
			"on_session_read",    "on_session_write",
			"on_session_destroy", "on_session_gc");
session_start();

?>