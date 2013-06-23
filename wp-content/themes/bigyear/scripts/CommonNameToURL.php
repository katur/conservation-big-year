<?php	
	$query = "SELECT common_name, id FROM aou_list";	
	$result = mysql_query($query) or die(mysql_error());
	
	while ($row = mysql_fetch_assoc($result)) {
		$common_name = $row["common_name"];
		$id = $row["id"];
		
		// Change common name to be URL-friendly (remove spaces)
		$common_name = str_replace(array(" ", "'"), "", $common_name);
		
		$subquery = "UPDATE aou_list SET url_common_name = '$common_name' WHERE id = $id";
		$subresult = mysql_query($subquery) or die(mysql_error());
				
		echo $common_name . $id . "<br>";
	}
?>