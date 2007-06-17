<?php
/*
Page:           _config-rating.php
Created:        Aug 2006
Last Mod:       Mar 18 2007
Holds info for connecting to the db, and some other vars
--------------------------------------------------------- 
ryan masuga, masugadesign.com
ryan@masugadesign.com 
--------------------------------------------------------- */

//Connect to  your rating database

$rating_units		= 10;  //		number of units for the rating bar
$rating_tableID		= "pluginversionid"; // 		name of the key value in the rating table
$rating_tableName   = 'plugin_version'; //	name of the table where the ratings are recorded
$rating_recordUrl 	= "db.php"; //	non javascript rating script location
$rating_unitwidth   = 20; // 		the width (in pixels) of each rating unit (star, etc.)
$rating_static		= FALSE; // 	static or dynamic rating bar (to invoke static change FALSE to static)
// 									if you changed your graphic to be 50 pixels wide, 
//									you should change the value above
	
?>