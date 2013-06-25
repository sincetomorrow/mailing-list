<? 
// showLists($emailid) 
function showLists($emailid) {
	
	// Query 
	$sql1 = "SELECT l.*, le.emailid isthere, le.confirmed
	FROM lists l 
	LEFT JOIN (SELECT * FROM list_email WHERE emailid='$emailid') le ON l.id=le.listid
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
		if($isthere>0) { 
			$lists .= "<a href='#' emailid='$emailid' listid='$row1[id]' a='delete' class='iconbt email2list'>
				<img src='images/bt_listemail.png'/>
			</a>";
		}
		else { 
			$lists .= "<a href='#' emailid='$emailid' listid='$row1[id]' a='add' class='iconbt email2list'>
				<img src='images/bt_add2.png'/>
			</a>";
		}
		
		//if($confirmed==1) { $lists .= "<a href='#' class='iconbt'><img src='images/bt_add2.png'/></a>"; }
		
		$lists .= "</span>
			<br/>";
	}
	return $lists;
}
				
				
// typeDd($t) 
function typeDd($t) {
	echo "<select name='type'>";
	echo "<option>Διαλέξτε τί θα είναι</option>";
	echo "<option value='product' " ;
	if($t=="product") { echo "selected"; }
	echo ">Προϊόν</option>";
	echo "<option value='service' " ;
	if($t=="service") { echo "selected"; }
	echo ">Υπηρεσία</option>";
	echo "<option value='text' " ;
	if($t=="text") { echo "selected"; }
	echo ">Κείμενο απλό</option>";
	echo "<option value='subpage' " ;
	if($t=="subpage") { echo "selected"; }
	echo ">Υποσελίδα κειμένου</option>";
	echo "</select>";	
}
// clientsDD($id) 
function showClients($id) {
	echo "<select name='client'>";
	$queryc="SELECT * FROM clients";
	$resultc=mysql_query($queryc);
	while($rowc=mysql_fetch_assoc($resultc)) {
		echo "<select name='$rowc[id]'";
		if($rowc[id]==$id) { echo " selected"; }
		echo ">$rowc[titlegr]</select>";
	}
	echo "</select>";
}
// projectsDD($id)  
function showProjects($id) {
	echo "<select name='project'>";
	$queryp="SELECT * FROM projects";
	$resultp=mysql_query($queryp);
	while($rowp=mysql_fetch_assoc($resultp)) {
		echo "<select name='$rowp[id]'";
		if($rowp[id]==$id) { echo " selected"; }
		echo ">$rowp[titlegr]</select>";
	}
	echo "</select>";
}
// rootPagesDD($id)  
function rootPagesDD($id) {
	$string = "<select name='parentid'><option value='0'>-</option>";
	$query="SELECT 
	p.*, pt.title
	FROM pages p
	LEFT JOIN (SELECT title,pageid FROM pages_text WHERE languageid='1') pt
	ON pt.pageid=p.id
	WHERE `p`.`parentid`=0
	ORDER BY `p`.`parentid`, `p`.`order` ASC
	";
	$result=mysql_query($query) or die(mysql_error());
	while($row=mysql_fetch_assoc($result)) {
		$string .= "<option value='$row[id]'";
		if($row[id]==$id) { $string .= " selected"; }
		$string .= ">$row[title]</option>";
	}
	$string .= "</select>";
	return $string;
}
// statusDD($id) 
function statusDD($id) {
	echo "<select name='status'>";
	echo "<option value='1'"; if($id=='1') { echo " selected"; } echo ">Εκκρεμεί</option>";
	echo "<option value='2'"; if($id=='2') { echo " selected"; } echo ">Εκτελέστηκε</option>";
	echo "<option value='0'"; if($id=='0') { echo " selected"; } echo ">Ακυρώθηκε</option>";
	echo "</select>";
}
// statusDD_1($id)  
function statusDD_1($id) {
	echo "<select name='status'>";
	echo "<option value='1'"; if($id=='1') { echo " selected"; } echo ">Ενεργό</option>";
	echo "<option value='0'"; if($id=='0') { echo " selected"; } echo ">Ανενεργό</option>";
	echo "</select>";
}
// getPagingQuery($sql, $itemPerPage) 
function getPagingQuery($sql, $itemPerPage) {
   if (isset($_GET['leaf']) && (int)$_GET['leaf'] > 0) {
      $leaf = (int)$_GET['leaf'];
   } else {
      $leaf = 1;
   }

   // start fetching from this row number
   $offset = ($leaf - 1) * $itemPerPage;

   return $sql . " LIMIT $offset, $itemPerPage";
} 
// usertypes($id)  
function usertypes($id) {
	echo "<select name='type'>";
	echo "<option value='1'"; if($id=='1') { echo " selected"; } echo ">Διαχειριστής</option>";
	echo "<option value='2'"; if($id=='2') { echo " selected"; } echo ">Πωλητής</option>";
	echo "<option value='3'"; if($id=='3') { echo " selected"; } echo ">Πελάτης</option>";
	echo "<option value='4'"; if($id=='4') { echo " selected"; } echo ">Συνεργάτης</option>";
	echo "</select>";
}
// generateTag($id) 
function generateTag($act) {
	echo "<span class='onofftag ";
	if($act=='0') { echo " greybg"; }
	echo "'>&nbsp;&nbsp;&nbsp;</span>&nbsp;";
}
// generateTag2($id) 
function generateTag2($act) {
	$string = "<span class='onofftag ";
	if($act=='0') { $string .= " greybg"; }
	$string .= "'></span>";
	return $string;
}
// justUpdated($id,$curid) 
function justUpdated($id,$curid) {
	if($id==$curid) { echo " <span class='note'> (Μόλις ενημερώθηκε)</span>"; }	
}
// productsDD($id) 
function productsDD($id) {
	require_once("../config/init_database.php");
	$query="SELECT * FROM products WHERE type='product'";
	$result=mysql_query($query);
	
	echo "<select name='productid' class='paper'>";
	while($row=mysql_fetch_assoc($result)) {
		
		$total=$row[cost]/$row[a4];
		
		echo "<option value='$row[id]'";
		if($row[id]==$id) { echo " selected"; }
		echo ">$row[title]</option>";
	}
	echo "</select>";
}
// userType($id) 
function userType($id) {
	if($id==1) { echo "Διαχειριστής"; }	
	else if($id==2) { echo "Πωλητής"; }	
	else if($id==3) { echo "Πελάτης"; }	
	else if($id==4) { echo "Συνεργάτης"; }	
}
// categoriesDD($id) 
function categoriesDD($id) {
	$query="SELECT 
	c.*, ct.title, cot.title parenttitle 
	FROM categories c 
	LEFT JOIN (SELECT * FROM categories_text WHERE languageid='1') ct
	ON ct.categoryid=c.id
	LEFT JOIN (SELECT * FROM collections_text WHERE languageid='1') cot
	ON cot.collectionid=c.parentid
	ORDER BY c.parentid, c.order
	";
	$result=mysql_query($query) or die(mysql_error());
	
	echo "<select name='categoryid'>";
	while($row=mysql_fetch_assoc($result)) {
				
		echo "<option value='$row[id]'";
		if($row[id]==$id) { echo " selected"; }
		echo ">$row[parenttitle] > $row[title]</option>";
	}
	echo "</select>";
}
// brandsDD($id) 
function brandsDD($id) {
	$query="SELECT 
	b.*, bt.title
	FROM brands b 
	LEFT JOIN (SELECT * FROM brands_text WHERE languageid='1') bt ON bt.brandid=b.id
	ORDER BY b.id, b.order
	";
	$result=mysql_query($query) or die(mysql_error());
	
	echo "<select name='brandid'>";
	while($row=mysql_fetch_assoc($result)) {
				
		echo "<option value='$row[id]'";
		if($row[id]==$id) { echo " selected"; }
		echo ">$row[title]</option>";
	}
	echo "</select>";
}
// collectionsDD($id) 
function collectionsDD($id) {
	$query="SELECT 
	c.*, ct.title 
	FROM collections c 
	LEFT JOIN (SELECT * FROM collections_text WHERE languageid='1') ct
	ON ct.collectionid=c.id
	";
	$result=mysql_query($query) or die(mysql_error());
	
	echo "<select name='parentid'>";
	while($row=mysql_fetch_assoc($result)) {
		
		$total=$row[cost]/$row[a4];
		
		echo "<option value='$row[id]'";
		if($row[id]==$id) { echo " selected"; }
		echo ">$row[title]</option>";
	}
	echo "</select>";
}
// isValidEmail($email) 
function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

?>