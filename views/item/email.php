<?
	if($_GET[page]!="emails") { echo "<p><a href='index.php?page=emails' class='back'>Πίσω</a></p>"; }
	if(!empty($id)) {
		$query1="SELECT * FROM emails WHERE id='$id' LIMIT 0,1";
		$result1=mysql_query($query1) or mysql_error();
		echo "<ul>";
		while($row=mysql_fetch_assoc($result1)) {
			$email = $row[email];
			$status = $row[status];
		}
		$s = "update";
		$e = "<a href='library/email.php?page=email&a=delete&id=$id'><input type='button' value='Διαγραφή' $confirmdelete/></a>
				<input type=\"submit\" value=\"Αποθήκευση\" class='button'/>";
		echo "</ul>";
	}
	else {
		$s = "add";
		$e = "<input type=\"submit\" value=\"Καταχώρηση\" class='button' />";	
	}
	echo "
	<form method=\"post\" action='library/email.php'>
		<input type=\"hidden\" name=\"a\" value='$s'/>
		<input type=\"hidden\" name=\"id\" value='$id'/>
		<input type=\"hidden\" name=\"listid\" value='$listid'/>
		<span class='width75'>E-mail</span><input type='text' name='email' value='$email'/> ";
		statusDD_1($status);
		echo "
		<br/>
		$e
	</form>
	<br/>";
?>