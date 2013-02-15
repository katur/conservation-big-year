<?php
    function gallery($species_id) {
        $query = "SELECT flickr_small, flickr_large			
			FROM photos
			WHERE species_id = '$species_id'
		";

		// run query
		$result = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($result)) {
			// define variables
			while ($row = mysql_fetch_assoc($result)) {
				$flickr_small = $row["flickr_small"];
				$flickr_large = $row["flickr_large"];
				// make the gallery here
			}
		}
    }
    
    function flickr_edit($name, $value, $name2, $value2) {
		echo "
			flickr href: <input name='$name' type='text' size='150' value='$value'>
			<br>flickr src: <input name='$name2' type='text' size='150' value='$value2'>
		";
	}
?>