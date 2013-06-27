<?
require_once("../config/init_database.php");

$a=$_REQUEST[a];
$id=mysql_real_escape_string($_REQUEST[id]);
$email=mysql_real_escape_string($_REQUEST[email]);
$status=mysql_real_escape_string($_REQUEST['status']);
$key = md5(uniqid(rand(), true));
$page="emails";
$table = "emails";

switch ($a) {
	case "add":
		$query="INSERT INTO  `$table` 
				VALUES ( 
				NULL,
				'$email',
				NOW(),
				'$key',
				'$status'
				)";
		$result=mysql_query($query) or die(mysql_error());
		$id = mysql_insert_id();
	break;
	case "update":
		// Update main details 
		$query="UPDATE `$table`
				SET
					`email`='$email',
					`status`='$status'
				WHERE id='$id'
				";
		$result=mysql_query($query) or die(mysql_error() ." " .$query);
	break;
	case "delete":
		// Delete email
		$query="DELETE FROM `$table` WHERE `id`=$id";
		$result=mysql_query($query);
		// Delete reference from lists
		$query1="DELETE FROM `list_email` WHERE `emailid`=$id";
		$result1=mysql_query($query1);
		
	break;

	default:
	echo "Problem..";
	break;
}

$backurl="../index.php?page=" .$page ."&id=" .$id;// ."&id=" .$id;
header("Location:" .$backurl);

?>