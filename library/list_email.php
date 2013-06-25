<?
require_once("../config/init_database.php");

$a=$_REQUEST[a];
$emailid=mysql_real_escape_string($_REQUEST[emailid]);
$listid=mysql_real_escape_string($_REQUEST[listid]);
$confirmed=mysql_real_escape_string($_REQUEST[confirmed]);
$status=mysql_real_escape_string($_REQUEST['status']);

$page="emails";
$table = "list_email";

switch ($a) {
	case "add":
		$query="INSERT INTO  `$table` 
				VALUES ( 
				'$listid',
				'$emailid',
				'$confirmed',
				'$status'
				)";
		$result=mysql_query($query) or die(mysql_error());
	break;
	case "addall":
		if(!empty($listid)) {
			$sql1 = "SELECT * FROM emails";
			$res1 = mysql_query($sql1) or die(mysql_error());
			while($row1=mysql_fetch_assoc($res1)) {
				$sql2 = "INSERT INTO  `$table` 
				VALUES ( 
				'$listid',
				'$row1[id]',
				'1',
				'1'
				)";
				$res2=@mysql_query($sql2);
			}
		}
	break;
	case "delete":
		// Delete reference from lists
		$query="DELETE FROM `list_email` WHERE `emailid`='$emailid' AND listid='$listid'";
		$result=mysql_query($query) or die(mysql_error());
		
	break;

	default:
	echo "Problem..";
	break;
}

$backurl="../index.php?page=" .$page ."&id=" .$id;// ."&id=" .$id;
if($page=="addall") {
	$backurl.="&listid=$listid";
	header("location:$backurl");
}

?>