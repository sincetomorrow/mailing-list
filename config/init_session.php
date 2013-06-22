<?php

if( !session_start( ) ) session_start( );

if (!isset($_SESSION["authenticated"]))
{ 
	# by default move to an empty 
	$temp='no';
	//header( "Location: login.php" );
	//exit;
}
$temp='yes';

?>