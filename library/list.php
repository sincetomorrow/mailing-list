<?
require_once("../config/init_database.php");

$a=$_REQUEST[a];
$id=mysql_real_escape_string($_REQUEST[id]);
$name=mysql_real_escape_string($_REQUEST[name]);
$status=mysql_real_escape_string($_REQUEST['status']);
$page="lists";
$table = "lists";

switch ($a) {
	case "add":
		$query="INSERT INTO  `$table` 
				VALUES ( 
				NULL,
				'$name',
				NOW(),
				'$status'
				)";
		$result=mysql_query($query) or die(mysql_error());
	break;
	case "update":
		// Update main details 
		$query="UPDATE `$table`
				SET
					`name`='$name',
					`status`='$status'
				WHERE id='$id'
				";
		$result=mysql_query($query) or die(mysql_error() ." " .$query);
	break;
	case "duplicate":
		$query="INSERT INTO  `$table` 
				VALUES ( 
				NULL,
				'Duplicated list',
				NOW(),
				'1'
				)";
		$result=mysql_query($query) or die(mysql_error());
		$new_listid = mysql_insert_id();				
		
		$query = "SELECT e.*
		FROM emails e
		LEFT JOIN list_email le ON le.emailid=e.id
		WHERE le.listid='$id'
		ORDER BY e.id DESC";
		$result=mysql_query($query) or die(mysql_error());
		while($row=mysql_fetch_assoc($result)) {
			$emailid=$row[id];
			$sql2 = "INSERT INTO `list_email` VALUES ('$new_listid','$emailid','1',NOW(),'1')";
			$res2=@mysql_query($sql2);
		}
		
	break;
	case "delete":
		$query="DELETE FROM `$table` WHERE `id`=$id";
		$result=mysql_query($query);
	break;

	default:
	echo "Problem..";
	break;
}

$backurl="../index.php?page=" .$page ."&id=" .$id;// ."&id=" .$id;
header("Location:" .$backurl);

?>