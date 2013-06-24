<?
// Menu 
$menu = array(
	//array("one"=>"brand",		"many"=>"brands",		"title"=>"Παραγωγοί","url"=>""),
	array("one"=>"list",		"many"=>"lists",		"title"=>"MAILING LISTS","url"=>""),
	array("one"=>"email",		"many"=>"emails",		"title"=>"E-MAILS","url"=>"")/*,
	array("one"=>"",			"many"=>"",				"title"=>"&nbsp;","url"=>""),
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
		$query="SELECT SQL_CALC_FOUND_ROWS l.*, COUNT(le.emailid) total
		FROM lists l
		LEFT JOIN list_email le ON le.listid=l.id
		GROUP BY l.id
		ORDER BY id ASC";
		$result=mysql_query($query) or die(mysql_error());
		// Get total results 
		$total_records = mysql_query("SELECT FOUND_ROWS() as `found_rows`;") or die(mysql_error());
		while($fr=mysql_fetch_assoc($total_records)) { $tot = $fr[found_rows]; }
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
		echo "<ul>";
		while($row=mysql_fetch_assoc($result)) {
			$name = $row[name];
			$id = $row[id];
			echo "<li>"; //generateTag($row[status]);
			echo "
				<span class='width40'><a href='library/list.php?a=delete&id=$row[id]' class='iconbt'><img src='images/bt_delete.png' $confirmdelete/></a></span>
				<a href='index.php?page=list&id=$row[id]' class='width300'><strong>$row[name]</strong></a>
				<span class='width50'>
					<a href='index.php?page=emails&listid=$row[id]'>$row[total]</a>
					<a href='index.php?page=email&listid=$row[id]' class='iconbt icons_right'><img src='images/bt_add.png'/></a>
				</span>
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
		$lim = "LIMIT 0,50";
		if(!empty($listid)) {
			$sql2 = "SELECT name FROM lists WHERE id='$listid'";
			$res2 = mysql_query($sql2);
			while($row2 = mysql_fetch_assoc($res2)) {
				$list_name = $row2[name];	
			}
		}
		// Magic query 
		if(!empty($keyword)) { $and1="e.email LIKE '%$keyword%'"; $is++; $lim=""; }
		if(!empty($listid)) { $and2 = "le.listid='$listid'"; $list=true; $is++; $lim=""; }
		
		if($is==1) { $and = "WHERE $and1 $and2"; }
		else if($is==2) { $and = "WHERE $and1 AND $and2"; }
		else { $and = ""; }
		
		$query="SELECT SQL_CALC_FOUND_ROWS e.*, COUNT(le.listid) total
		FROM emails e
		LEFT JOIN list_email le ON le.emailid=e.id
		$and
		GROUP BY e.id
		ORDER BY e.id DESC
		$lim";
		$result=mysql_query($query) or die(mysql_error());
		// Get total results 
		$total_records = mysql_query("SELECT FOUND_ROWS() as `found_rows`;") or die(mysql_error());
		while($fr=mysql_fetch_assoc($total_records)) { $tot = $fr[found_rows]; }
		// Show options 
		echo "
		<div class='options inset grey'>";
		if(!empty($listid)) {
			echo "<a href='index.php?page=list&id=$listid' class='title'>$list_name</a>
			<a href='#' class='iconbt_bigger'><img src='images/bt_download.png'/></a><br/>";
		}
		echo "
			<a href='#' class='dark_grey'>Subscribed: 3400</span>, <a href='#'>Unsubscribed: 100</a>, <a href='#' class='dark_grey'>Total: $tot</a>
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
			<br/>
		<li>Σύνολο e-mail: " .mysql_num_rows($result) ."</li><br/>";
		// Δείξε το 
		while($row=mysql_fetch_assoc($result)) {
			$email = $row[email];
			$status = $row[status];
			echo "<li>"; //generateTag($row[status]);
			echo "
				<span class='width40'>
					<a href='library/email.php?a=delete&id=$row[id]' class='iconbt'><img src='images/bt_delete.png' $confirmdelete/></a>
					<a href='#' class='iconbt'><img src='images/bt_confirmed.png'/></a>
				</span>
				<span class='width300 black'>$row[email]</span>
				<a href='index.php?page=email&id=$row[id]' class='show width70 black ' style='font-weight:500;'>
					$row[total] lists <span class='iconbt icons_right'><img src='images/bt_add.png'/></span>
				</a>
				<div class='more'>"; 
			// If listid is defined, show lists on click, Else show the edit email form 
			// (for the first case, get the lists and put them in a list to be hidden just in case)
			if(!empty($listid)) {
				$sql1 = "SELECT l.*, le.emailid isthere, le.confirmed
				FROM lists l 
				LEFT JOIN (SELECT * FROM list_email WHERE emailid='$row[id]') le ON l.id=le.listid
				GROUP BY l.id
				";
				$res1 = mysql_query($sql1) or die(mysql_error());
				$lists="";
				while($row1 = mysql_fetch_assoc($res1)) {
					$list_name = $row1[name];
					$confirmed = $row1[confirmed];
					$isthere = $row1[isthere];
					$lists .= "
						<span class='width40'>&nbsp;</span>
						<span class='width335' style='text-align:right;font-weight:500;'>$list_name</span>
						<span class='icons_right'>";
					if(!empty($isthere)) { $lists .= "<a href='#' class='iconbt'><img src='images/bt_add2.png'/></a>"; }
					else { $lists .= "<a href='#' class='iconbt'><img src='images/bt_add2list.png'/></a>"; }
					
					//if($confirmed==1) { $lists .= "<a href='#' class='iconbt'><img src='images/bt_add2.png'/></a>"; }
					
					$lists .= "</span>
						<br/>";
				}
				echo $lists;
			}
			else { include("views/item/email.php"); }
			echo "</div>
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