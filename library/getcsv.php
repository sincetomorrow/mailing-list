<?php

/*----------------------------------------------------------------------*/
/**
 * @author      : Panagiotis Branis
 * @created     : 11/07/2012 09:00pm
 * @modified    : 11/07/2012 19:30pm
 * @file        : getcsv.php
 * @description : It gathers the active email from the db and writes them to a csv file
 * @memo        : 
 */
 
require_once("../config/init_database.php");

// Variables 
$list = array();
$status = $_REQUEST['status'];
$keyword = $_REQUEST['keyword'];
$listid = $_REQUEST['listid'];
// Bypass variables 
$status = 1;

$file = "file_" .date("y_m_d");
if($listid!="") { $file .= "_$listid"; }
if($keyword!="") { $file .= "_$keyword"; }
$file .= ".csv";
$lim = "LIMIT 0,20"; // In the following lines, the lim is being set to ""

// Magic query 
if(!empty($keyword)) { $and1="e.email LIKE '%$keyword%'"; $is++; $lim=""; }
if(!empty($listid)) { $and2 = "le.listid='$listid'"; $and_="LEFT JOIN list_email le ON le.emailid=e.id"; $lim=""; $is++; }
if($is==1) { $and = "WHERE e.status='$status' AND $and1 $and2"; }
else if($is==2) { $and = "WHERE e.status='$status' AND $and1 AND $and2"; }
else { $and = "WHERE e.status='$status'"; }

$query = "SELECT e.*
FROM emails e
$and_
$and
ORDER BY e.id DESC
$lim";
$result=mysql_query($query) or die(mysql_error());
// Loop throught the results and write them to the array 
while($row=mysql_fetch_assoc($result)) {
	$email = $row[email];
	$status = $row[status];
	$arr = array("$email");
	
	array_push($list,$arr);
}
// Open file and write the results in csv format 
$fp = fopen($file,'w');
foreach ($list as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);

// Echo the file path 
echo "<a href='$file'>$file</a>";
?>