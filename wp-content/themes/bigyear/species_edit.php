<?php get_header(); 
	include("katherine_connect.php");
	/*
	Template Name: Species Edit
	Copyright (c) 2013 Katherine Erickson
	*/
?>

<div id="site-content full-width">
	Edit the maps, essay, or flickr code for a particular species.

	<?php
		// get the common name from the GET variable (visible in the url). 
		// the php-format url is available via htaccess file.
		$url_common_name = mysql_real_escape_string($_GET['url_common_name']);

		// If there is data from a POST
		if ($_POST) {		
			// grab the variables from the POST variable
			$flickr_code = mysql_real_escape_string($_POST['what_issues']);
			$cornell_map = mysql_real_escape_string($_POST['cornell_map']);
			$ebird_map = mysql_real_escape_string($_POST['ebird_map']);
			$essay = mysql_real_escape_string($_POST['why_concern']);

			$query = "UPDATE species_list
				SET flickr_code = '$flickr_code', essay = '$essay',
				ebird_map = '$ebird_map', cornell_map = '$cornell_map'
				WHERE url_common_name = '$url_common_name'
			";

			$result = mysql_query($query) or die(mysql_error());

			// php function to set the header to redirect to VIEW page
			header("Location: /species/$url_common_name");

		// if there is not data from a post	
		} else {
			// define MySQL query to access fields for current species
			$query = "SELECT
				flickr_code, essay, ebird_map, cornell_map
				FROM species_list
				WHERE url_common_name = '$url_common_name'
			";

			// run query
			$result = mysql_query($query) or die(mysql_error());

			// if no result
			if (mysql_num_rows($result) == 0) {
				echo "That is not a bird.";
			
			} else if (mysql_num_rows($result) > 1) {
				echo "Too many birds match that url common name.";

			} else { // if one result
				// Define variables
				$row = mysql_fetch_assoc($result))
				$common_name = $row["common_name"];
				$flickr_code = $row["flickr_code"];
				$essay = $row["essay"];
				$cornell_map = $row["cornell_map"];
				$ebird_map = $row["ebird_map"];

				?>		
				
				<h1><?php echo $common_name; ?></h1>
				<form method='post' action='./<?php echo $url_common_name; ?>'>
					<h3>Main Photo</h3>
										
					<h3>Cornell Range Map</h3>
					<input name='cornell_map' type='text' size='150' value='<?php echo $cornell_map; ?>'>
						
					<h3>Ebird Dynamic Map</h3>
					<input name='ebird_map' type='text' size='150' value='<?php echo $ebird_map; ?>'>
					
					<h3>Essay</h3>
					<textarea name='essay' rows='100' cols='100'><?php echo $essay; ?></textarea>
				
					<br><br>
					<input type='submit' value='Submit to Database'></input>
				</form>
	<?php	}
	}
	?>	
</div>
