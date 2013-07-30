<?
	if(!empty($id) && $page!="lists") {
		$query3="SELECT * FROM emails WHERE id='$id' LIMIT 0,1";
		$result3=mysql_query($query3) or mysql_error();
		echo "<ul>";
		while($row3=mysql_fetch_assoc($result3)) {
			$email = $row3[email];
			$status = $row3[status];
		}
		$s = "update";
		$form_title = "Update e-mail";
		$e = "<a href='library/email.php?page=email&a=delete&id=$id'><input type='button' value='Διαγραφή' $confirmdelete/></a>
				<input type=\"submit\" value=\"Αποθήκευση\" class='button'/>";
		echo "</ul>";
	}
	else {
		$s = "add";
		$form_title = "Add new e-mail";
		$e = "<input type=\"submit\" value=\"Καταχώρηση\" class='button' />";	
	}
	echo "
	<form method=\"post\" action='library/email.php'>
		<input type=\"hidden\" name=\"a\" value='$s'/>
		<input type=\"hidden\" name=\"id\" value='$id'/>
		<input type=\"hidden\" name=\"listid\" value='$listid'/>
		<span class='width150'>$form_title</span><br/>
		<input type='text' name='email' value='$email'/> ";
		//statusDD_1($status);
		echo "
		<br/>
		$e
	</form>
	<br/>";
?>