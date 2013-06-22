<?
$menu = array(
	//array("one"=>"brand",		"many"=>"brands",		"title"=>"Παραγωγοί","url"=>""),
	array("one"=>"list",	"many"=>"lists",		"title"=>"Λίστες","url"=>""),
	array("one"=>"email",		"many"=>"emails",		"title"=>"E-mails","url"=>""),
	array("one"=>"",			"many"=>"",				"title"=>"&nbsp;","url"=>""),
	array("one"=>"",			"many"=>"",				"title"=>"Κεντρική Site","url"=>"../"),
	array("one"=>"",			"many"=>"",				"title"=>"Αποσύνδεση","url"=>"logout.php")
);
require_once("config/init_session.php");
require_once("include/header.php");
require_once("library/functions.php");
require_once("config/init_database.php");
echo "<div class='main'>";

// Variables 
$page=$_REQUEST[page];
$id=$_REQUEST[id];
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

switch($page) {

case "lists": 
	$query="SELECT SQL_CALC_FOUND_ROWS l.*, COUNT(le.emailid) total
	FROM lists l
	LEFT JOIN list_email le ON le.listid=l.id
	GROUP BY l.id
	ORDER BY id ASC";
	$result=mysql_query($query) or die(mysql_error());
	
	// Get total results 
	$total_records = mysql_query("SELECT FOUND_ROWS() as `found_rows`;") or die(mysql_error());
	while($fr=mysql_fetch_assoc($total_records)) { $tot = $fr[found_rows]; }
	
	echo "<ul>$tot
	<li>
		<a href='#' class='show'>+</a>
		<div class='more'>";
	include("views/item/list.php");
	echo "</div>
	</li>
	<br/>";
	while($row=mysql_fetch_assoc($result)) {
		$name = $row[name];
		$id = $row[id];
		echo "<li>"; generateTag($row[status]);
		echo "<a href='index.php?page=list&id=$row[id]' class='width300'><strong>$row[name]</strong></a>
			<span class='width50'>$row[total]</span>
			<a href='index.php?page=email&listid=$row[id]' class='iconbt'><img src='images/ml_add'/></a>
			<a href='library/list.php?a=delete&id=$row[id]' class='iconbt'><img src='images/ml_delete' $confirmdelete/></a>
		</li><br/>";
	}
	echo "</ul>";
break;
case "list": 
	include_once("views/item/list.php");
break;

case "emails": 
	$keyword = $_GET[keyword];
	$listid = $_GET[listid];
	$is = 0;
	$lim = "LIMIT 0,50";
	if(!empty($keyword)) { $and1="e.email LIKE '%$keyword%'"; $is++; $lim=""; }
	if(!empty($listid)) { $and2 = "WHERE le.listid='$listid'"; $list=true; $is++; $lim=""; }
	
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

	echo "<ul>
	<li>
		<a href='#' class='show'>Εισαγωγή e-mail</a>
		<div class='more'>";
	include("views/item/email.php");
	echo "</div>
	</li><br/>
	<li>
		<a href='#' class='show'>Εισαγωγή πολλών e-mails</a>
		<div class='more'>
			<form class='many_emails'>
				<textarea style='width:420px;height:105px' name='emails'></textarea><br/>
				Delimiter: <input type='text' name='delimiter' value=',' style='width:20px;'/>
				<input type='submit'/>
			</form>
			<div class='outcome'></div>
			<br/>
		</div>
	</li><br/>
	<br/>
	<li>
		<form>
			<input type='hidden' name='page' value='$page'/>
			<input style='width:220px;' name='keyword' value='$keyword'/>
			<input type='submit' value='Search'/>
		</form>
	</li>
	<br/>
	<li>Σύνολο e-mail: " .mysql_num_rows($result) ."</li><br/>";
	
	while($row=mysql_fetch_assoc($result)) {
		$email = $row[email];
		$status = $row[status];
		echo "<li>"; generateTag($row[status]);
		echo "
			<strong class='width300'>$row[email]</strong>
			<span class='width50'>$row[total]</span>
			<a href='index.php?page=email&id=$row[id]' class='iconbt show'><img src='images/ml_edit'/></a>
			<a href='#' class='iconbt'><img src='images/ml_add'/></a>
			<a href='#' class='iconbt'><img src='images/ml_active'/></a>
			<a href='library/email.php?a=delete&id=$row[id]' class='iconbt'><img src='images/ml_delete' $confirmdelete/></a>
			<div class='more'>";
		include("views/item/email.php");
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