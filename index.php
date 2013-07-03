<?
$version = "1.1";
// Menu 
$menu = array(
	//array("one"=>"brand",		"many"=>"brands",		"title"=>"Παραγωγοί","url"=>""),
	array("one"=>"list",		"many"=>"lists",		"title"=>"MAILING LISTS","url"=>""),
	array("one"=>"email",		"many"=>"emails",		"title"=>"E-MAILS","url"=>""),
	array("one"=>"",			"many"=>"",				"title"=>"$version","url"=>"")/*,
	array("one"=>"",			"many"=>"",				"title"=>"Κεντρική Site","url"=>"../"),
	array("one"=>"",			"many"=>"",				"title"=>"Αποσύνδεση","url"=>"logout.php")*/
);
// Requires 
require_once("config/init_session.php");
require_once("include/header.php");
require_once("library/functions.php");
require_once("config/init_database.php");
// Variables 
$page=$_REQUEST[page];
$id=$_REQUEST[id];
$listid=$_REQUEST[listid];
$message=$_REQUEST[message];
$itemsPerPage=30;
$root="index.php";
$confirmdelete = "onclick=\"return confirm('Είσαι σίγουρος;');\"";
// makeString($array) 
function makeString($array){
	$string = "index.php?";
     foreach ($array as $key => $value){
        if($key!="leaf" && $key!="key" && $key!="orderby") {
			$string .= "$key=$value&";
		}
        if(is_array($value)){ //If $value is an array, print it as well!
            makeString($value);
        }
    }
	return $string;
}
$getstring = makeString($_GET);

echo "<div class='main'>";

switch($page) {
	case "lists": 
		// Magic query 
		$sql1="SELECT * FROM lists ORDER BY id ASC";
		$res1=mysql_query($sql1) or die(mysql_error());
		$num1 = mysql_num_rows($res1);
		// Show options 
		echo "
		<div class='options inset grey'>
			<span class='dark_grey'>Subscribed: 3400</span>, Unsubscribed: 100, <span class='dark_grey'><span class='dark_grey'>Total: $tot</span>
			<br/>
			<span class='green'>
				<span><a href='#' class='show'>New list</a> / <div class='more'>"; include("views/item/list.php"); echo "	</div><span>
				<span><a href='#'>Add e-mail</a></span> / 
				<span><a href='#'>Import multiple e-mails</a></span>
			</span>
		</div>";
		// Δείξτα 
		echo "<ul>";
		while($row1=mysql_fetch_assoc($res1)) {
			$name = $row1[name];
			$listid = $row1[id];
			$id_ = $row1[id];
			// Get total emails in list 
			$total_emails = 0;
			$sql2 = "SELECT COUNT(emailid) total, listid FROM list_email WHERE listid='$listid'";
			$res2 = mysql_query($sql2) or die(mysql_error());
			while($row2=mysql_fetch_assoc($res2)) { $total_emails = $row2[total]; }
			echo "<li>"; //generateTag($row[status]);
			echo "
				<span class='width40'><a href='library/list.php?a=delete&id=$id_' class='iconbt'><img src='images/bt_delete.png' $confirmdelete/></a></span>
				<a href='index.php?page=list&id=$id_' class='width300'><strong>$name</strong></a>
				<span class='width30'>
					<a href='index.php?page=emails&listid=$id_'>$total_emails</a>
				</span>
				<a href='index.php?page=email&listid=$id_' class='iconbt icons_right show'><img src='images/bt_add.png'/></a>
				<div class='more'>"; include("views/item/email.php"); echo "</div>
			</li><br/>";
		}
		echo "</ul>";
	break;
	case "list": 
		// Show options 
		echo "
		<div class='options inset grey'>
			<span class='dark_grey'>Subscribed: 3400</span>, Unsubscribed: 100, <span class='dark_grey'><span class='dark_grey'>Total: $tot</span>
			<br/>
			<span class='green'>
				<span><a href='#'>Add e-mail</a></span> / 
				<span><a href='#'>Import multiple e-mails</a></span>
			</span>
		</div>";
		include_once("views/item/list.php");
	break;
	
	case "emails": 
		// Variables 
		$keyword = $_GET[keyword];
		$listid = $_GET[listid];
		$is = 0;
		$lim = "LIMIT 0,20";
		// If list is defined, get the list name and more, αλλιώς δες απλά πόσα e-mail έχεις
		if(!empty($listid)) {
			// Get list name
			$sql2 = "SELECT l.name FROM lists l WHERE l.id='$listid'";
			$res2 = mysql_query($sql2);
			while($row2 = mysql_fetch_assoc($res2)) { $list_name = $row2[name];	}
			// Get total emails in list 
			$subscribed_emails = 0;
			$sql3 = "SELECT COUNT(emailid) total, listid FROM list_email WHERE listid='$listid'";
			$res3 = mysql_query($sql3) or die(mysql_error());
			while($row3=mysql_fetch_assoc($res3)) { $subscribed_emails = $row3[total]; }
		}
		else {
			// Get total emails 
			$total_emails = 0;
			$sql3 = "SELECT COUNT(email) total FROM emails";
			$res3 = mysql_query($sql3) or die(mysql_error());
			while($row3=mysql_fetch_assoc($res3)) { $total_emails = $row3[total]; }
			// Get total emails in lists (inverted) 
			$subscribed_emails = 0;
			$sql4 = "SELECT COUNT(DISTINCT e.id) total_email 
			FROM emails e
			INNER JOIN list_email le ON le.emailid=e.id";
			
			$res4 = mysql_query($sql4) or die(mysql_error());
			while($row4=mysql_fetch_assoc($res4)) { $subscribed_emails = $row4[total_email]; }
			
			$unsubscribed_emails = $total_emails-$subscribed_emails;
		}
		// Magic query 
		if(!empty($keyword)) { $and1="e.email LIKE '%$keyword%'"; $is++; $lim=""; }
		if(!empty($listid)) { $and2 = "le.listid='$listid'"; $and_="LEFT JOIN list_email le ON le.emailid=e.id"; $list=true; $is++; }
		if(!empty($listid) && $_GET[all]=="1") { $and2 = "le.listid='$listid'"; $lim=""; }
		
		if($is==1) { $and = "WHERE $and1 $and2"; }
		else if($is==2) { $and = "WHERE $and1 AND $and2"; }
		else { $and = ""; }
		$lim = "LIMIT 0,20";
		$query = "SELECT e.*
		FROM emails e
		$and_
		$and
		ORDER BY e.id DESC
		$lim";
		$result=mysql_query($query) or die(mysql_error());
		// Show options 
		echo "<div class='options inset grey'>";
		
		if(!empty($listid)) {
			echo "<a href='index.php?page=list&id=$listid' class='title'>$list_name</a>
			<a href='#' class='iconbt_bigger'><img src='images/bt_download.png'/></a><br/>
			<a href='#' class='dark_grey'>Total: $total_emails</span>, <a href='#'>Subscribed: $subscribed_emails</a>, <a href='#' class='grey'>Unsubscribed: $unsubscribed_emails</a>";
		}
		else {
			echo "<a href='#' class='dark_grey'>Total: $total_emails</span>, <a href='#'>Subscribed: $subscribed_emails</a>, <a href='#' class='grey'>Unsubscribed: $unsubscribed_emails</a>";
		}
		echo "
			
			<br/>
			<span>
				<a href='#' class='show green'>Add e-mail</a> / 
				<div class='more'>"; include("views/item/email.php"); echo "</div>
			</span>
			<span> 
				<a href='#' class='show green'>Import multiple e-mails</a>
				<div class='more'>
					<form class='many_emails'>
						<input type='hidden' name='listid' value='$listid'/>
						<textarea style='width:420px;height:105px' name='emails'></textarea><br/>
						Delimiter: <input type='text' name='delimiter' value=',' style='width:20px;'/>
						<input type='submit'/>
					</form>
					<div class='outcome'></div>
					<br/>
				</div>
			</span>
		</div>";
		// Search 
		echo "
		<ul>
			<li>
				<form>
					<input type='hidden' name='page' value='$page'/>
					<input type='hidden' name='listid' value='$listid'/>
					<input style='width:220px;' name='keyword' value='$keyword'/>
					<input type='submit' value='Search'/>
				</form>
			</li>
			<br/>";
		echo "<li class='grey'>Showing the latest 20 e-mail</li><br/>";
		// Δείξε το 
		while($row=mysql_fetch_assoc($result)) {
			$email = $row[email];
			$status = $row[status];
			// Get total list in which it is in 
			$total_lists = 0;
			$sql2 = "SELECT COUNT(listid) total, emailid FROM list_email le WHERE emailid='$row[id]'";
			$res2 = mysql_query($sql2) or die(mysql_error());
			while($row2=mysql_fetch_assoc($res2)) { $total_lists = $row2[total]; }
			
			echo "<li>"; //generateTag($row[status]);
			echo "
				<span class='width40'>
					<a href='library/email.php?a=delete&id=$row[id]' class='iconbt'><img src='images/bt_delete.png' $confirmdelete/></a>
					<a href='#' class='iconbt'><img src='images/bt_confirmed.png'/></a>
				</span>
				<span><a href='# 'class='width300 black show'>$row[email]</a><div class='more'>"; include("views/item/email.php"); echo "</div></span>
				<span>
					<a href='index.php?page=email&id=$row[id]' class='show width70 black ' style='font-weight:500;'>
						$total_lists lists <span class='iconbt icons_right'><img src='images/bt_add.png'/></span>
					</a>
					<div class='more'>" .showLists($row['id']) ."</div>
				</span>
			</li><br/>";
		}
		echo "</ul>";
	break;
	case "email": 
		include_once("views/item/email.php");
	break;
	default:
		echo "<span class='note'>Επιλέξτε μια ενότητα από πάνω.</span>";
	break;
}
echo "</div>";
include_once("include/footer.php");
?>