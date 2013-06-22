<?
if( !session_start( ) ) session_start( );

// showForm 
function showForm($msg) {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Διαχείρηση Περιεχομένου</title>
		<link rel="stylesheet" href="styles/cms.css" type="text/css"/>
		<link rel="stylesheet" href="styles/formating.css" type="text/css"/>
		</head>
		
		<body>kk' .$db_name .'
		<div class="main login">	
			<h2>CMS</h2>
			<hr/>
			<form action="login.php" method="post">
					<input name="username" type="text" value="username" size="40"/><br/>
					<input name="password" type="password" value="password" size="40"/><br/>
					<div class="error">' .$msg .'</div><br/>
					<input name="submit" type="submit" value="Login"/></li>
				<ul/>
			</form>
		</div>
		</body>
		</html>';
}

$username=$_POST[username];
$password=md5($_POST[password]);

if($username!='' && $password!='' && $error!=1) {
	require_once("config/init_database.php");
	//echo "kk";
	//echo $db_name;
	$sql="SELECT * 
	FROM users
	WHERE username='$username' 
	AND password='$password'
	AND type='1'
	AND status='1'
	";
	$result=mysql_query($sql) or die(mysql_error() .' / ' .$sql);
	$rows=@mysql_num_rows($result);
   	if ($rows==1){
   		//session_register($_SESSION["authenticated"]);
		$_SESSION["userid"]=$rows[id];
		$_SESSION["authenticated"]="true";
		header( "Location: index.php" );
		//exit;
	} 
	else {
   		$error=1; 
		showForm("Ανεπιτυχής σύνδεση, δοκιμάστε πάλι");
		exit;
	}
}
else {
	showForm("");
}

?>

