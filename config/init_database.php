<?php

require_once("db_config.php");

$connection  = @mysql_connect( $db_host, $db_user, $db_password ) or 
               die( mysql_error());

mysql_select_db( $db_name, $connection );

mysql_query( "SET NAMES UTF8" );
mysql_query( "SET CHARACTER SET UTF8" );

?>