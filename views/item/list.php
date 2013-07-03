<?
	if(!empty($id)) {
		$query2="SELECT * FROM lists WHERE id='$id' LIMIT 0,1";
		$result2=mysql_query($query2) or mysql_error();
		echo "<ul>";
		while($row2=mysql_fetch_assoc($result2)) {
			$name = $row2[name];
			$status = $row2[status];
		}
		$s = "update";
		$e = "<a href='library/list.php?page=lists&a=delete&id=$id'><input type='button' value='Διαγραφή' $confirmdelete/></a>
				<input type=\"submit\" value=\"Αποθήκευση\" class='button'/>";
		echo "</ul>";
	}
	else {
		$s = "add";
		$e = "<input type=\"submit\" value=\"Καταχώρηση\" class='button' />";	
	}
	echo "
	<form method=\"post\" action='library/list.php'>
		<input type=\"hidden\" name=\"a\" value='$s'/>
		<input type=\"hidden\" name=\"id\" value='$id'/>
		<input type=\"hidden\" name=\"listid\" value='$listid'/>
		<span class='width75'>Όνομα</span><input type='text' name='name'  value='$name'/>";
		statusDD_1($status);
		echo "
		<br/>
		$e
	</form>
	<br/>";
?>