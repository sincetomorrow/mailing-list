<?
$l=1;
$category=$_GET[category];
$producer=$_GET[producer];
if(!empty($category)) { $and = "WHERE p.categoryid='$category'"; }
if(!empty($producer)) { $and = "WHERE p.brandid='$producer'"; }
	// Magic query 
	$query="SELECT 
	p.*, pt.title producttitle, pt.description productdescription, ct.title catname, c.parentid, cot.title parenttitle, bt.title brandtitle
	FROM products p
	LEFT JOIN (SELECT title,description,productid FROM products_text WHERE languageid='$l') pt ON pt.productid=p.id
	LEFT JOIN categories c ON c.id=p.categoryid
	LEFT JOIN (SELECT * FROM categories_text WHERE languageid='$l') ct ON ct.categoryid=c.id
	LEFT JOIN (SELECT * FROM collections_text WHERE languageid='$l') cot ON cot.collectionid=c.parentid
	LEFT JOIN (SELECT * FROM brands_text WHERE languageid='$l') bt ON bt.brandid=p.brandid
	$and
	ORDER BY c.parentid, p.categoryid, p.code, p.`order` ASC, producttitle, `p`.`id` DESC";
	$result=mysql_query($query) or die(mysql_error());
	
	$cid = "";
	$scid = "";
	$header = "";
	$products = "<ul>";
	$products .= "<li><a href='index.php?page=product'><strong>Νέο Προϊόν</strong> <img src='images/icon15_new.png' style='vertical-align:middle'/></a></li><br/><br/>";
	while($row=mysql_fetch_assoc($result)) {
		if($row[parentid]!=$cid) {
			if($cid!="") { $products .= "<br/>"; }
			$products .= "<li class='nolineheight'><h2 class='blue'>$row[parenttitle]</h2></li><br/>";
		}
		$cid = $row[parentid];
		if($row[catname]!=$scid) {
			if($scid!="") { $products .= "<br/>"; }
			//$header .= " <a href='#$row[categoryid]'>$row[catname]</a> / ";
			$products .= "<li class='nolineheight grey'><h2 class='blue'><a name='$row[categoryid]'>$row[catname]</a></h2></li><br/>";
		}
		$scid = $row[catname];
		$products .= "<li class='nolineheight' style='padding-bottom:5px; padding-top:5px;'>"; 
		$products .= generateTag2($row[status]); 
		if($row[fav]==1) { $products .= "<a href='#' action='fav' id='$row[id]' isfav='0' class='isfav'><img src='images/icon15_fav1.png' style='display:inline-block; vertical-align:middle;'/></a>"; }
		else { $products .= "<a href='#' action='fav' id='$row[id]' isfav='1' class='isfav'><img src='images/icon15_fav0.png' style='display:inline-block; vertical-align:middle;'/></a>"; }
		$products .= " <span class='width100'> Σειρά: <strong>$row[order]</strong></span><span class='width100'>Κωδικός: <strong>$row[code]</strong></span>";
		//$products .= "<span style='display:inline-block; margin-right:15px; float:left; text-align:left' class='width125'><img src='galleries/thumbnails/$row[image]' height='70'/></span>";
		if(empty($row[producttitle])) { $title="Δεν έχει τίτλο."; } else { $title=$row[producttitle]; }
		$products .= "
		<span style='display:inline-block; float:left;'>
			Τίτλος: <a href='index.php?page=product&id=$row[id]' class='link'>
				<strong>$title</strong>
				<span class='button green link'>edit</span> <a href='library/product.php?page=products&a=delete&id=$row[id]' class='red link'>delete</a>
			</a>
			<br/>";
		if(!empty($row[productdescription])) { $row[productdescription] .= " $row[productdescription]<br/>"; }
		$products .= "</li>";
//			Εικόνα: <span style='font-size:0.8em'>$row[image]</span> 
//			Παραγωγός: <strong><a href='index.php?page=brand&id=$row[brandid]' class='link'>$row[brandtitle]</a></strong><br/>
		$products .= "
			<br/>";
	}
	$products .= "</ul>";
	echo $header;
	echo $products;
?>