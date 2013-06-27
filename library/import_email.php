<?
require_once("../config/init_database.php");
// isValidEmail($email) 
function isValidEmail($email){ 
    //return filter_var($email, FILTER_VALIDATE_EMAIL);
    return (bool)preg_match("`^[a-z0-9!#$%&'*+\/=?^_\`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_\`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$`i", trim($email));
}

$listid = mysql_real_escape_string($_REQUEST['listid']);
$status=1; //mysql_real_escape_string($_REQUEST['status']);
$delimiter=mysql_real_escape_string($_REQUEST['delimiter']);
if(empty($delimiter)) { $delimiter=","; }

// Gathers emails and explode them, and clean them a bit
$emails = $_REQUEST[emails];
$emails = htmlspecialchars($emails);
$emails = preg_replace('~[\r\n]+~',' ', $emails);
$emails = explode($delimiter,$emails);

$page="emails";
$table = "emails";
$marks = array(array(25,0),array(50,0),array(75,0),array(100,0));
$i=0;
$string = "";
$arrInvalid = array();
$total_emails = count($emails);
$arrExist = array();
$arr = array();

echo "Queuing emails..<br/>";
while($i<$total_emails) {
	$email = $emails[$i];
	$isVald = isValidEmail($email);
	
	array_push($arr,array("email"=>$email,"valid"=>$isVald));
	if(empty($isVald)) {
		array_push($arrInvalid,array("email"=>$email,"valid"=>$isVald));
	}
	else {
		// Check if email already exists
		$query0 = "SELECT * FROM $table WHERE email='$email'";
		$result0 = mysql_query($query0);
		if(mysql_num_rows($result0)>0) {
			// Υπάρχει στην βάση το e-mail, οπότε σπρώξτο στην array ότι υπάρχει και αν είναι valid
			while($row0=mysql_fetch_assoc($result0)) { $emailid=$row0[id]; }
			array_push($arrExist,array("email"=>$email,"valid"=>$isVald));
			// Και πρόσθεσε το στο list_email έτσι και αλλιώς αγνοώντας αν υπάρχει error για δεύτερη φορά
			if(!empty($listid)) {
				$sql2 = "INSERT INTO  `list_email` VALUES ( '$listid','$emailid','1',NOW(),'1')";
				$res2=@mysql_query($sql2);
			}
		}
		else {
			// Δεν υπάρχει, οπότε το βάζεις στην database 
			$key = md5(uniqid(rand(), true));
			$query="INSERT INTO  `$table` VALUES ( NULL,'$email',NOW(),'$key','$status')";
			$result=@mysql_query($query);
			// Παίρνεις το emailid	
			$emailid = mysql_insert_id();
			// Και πρόσθεσε το στο list_email 
			if(!empty($listid)) {
				
				$sql2 = "INSERT INTO  `list_email` VALUES ( '$listid','$emailid','1',NOW(),'1')";
				$res2=@mysql_query($sql2);
			}
		
		}
	}
	
	$perc = ($i/$total_emails)*100;
	$perc = round($perc);

	$ii=0;
	while($ii<count($marks)) {
		if( $marks[$ii][1]==0 && $perc>=$marks[$ii][1]) {
			$marks[$ii][1]=1;
			if($ii > 0) { echo " > "; }
			echo "<strong>" .$marks[$ii][0] ."</strong>%";
		}
		$ii++;
	}
	$i++;	
}
echo "<br/>";

if(!empty($listid)) { echo "Imported in list ID $listid > "; }

echo "Total: $total_emails, Same e-mail: " .count($arrExist) .", invalid email: " .count($arrInvalid) ."<br/>
Below are the invalid e-mails:<br/>
<span class='red'>";
$i=0;
while($i<count($arrInvalid)) {
	echo $arrInvalid[$i]["email"] ."<br/>";
	$i++;
}
echo "</span></div>";


$backurl="../index.php?page=" .$page ."&id=" .$id;// ."&id=" .$id;
//header("Location:" .$backurl);

?>