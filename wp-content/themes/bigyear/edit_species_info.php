<?php get_header(); 
	include("katherine_connect.php");
	/*
	Template Name: Edit Species Info
	Copyright (c) 2013 Katherine Erickson
	*/
	
	// get the common name from the GET variable (visible in the url). 
	$url_common_name = mysql_real_escape_string($_GET['common_name']);
		
	$query = "SELECT
		common_name, flickr_code, essay, ebird_map, cornell_map
		FROM species_list
		WHERE url_common_name = '$url_common_name'
	";

	// run query
	$result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($result) == 0) {
		echo "$url_common_name is not a bird.";
	} else if (mysql_num_rows($result) > 1) {
		echo "Too many birds match that url common name.";
	} else { // if one result
		// Define variables
		$row = mysql_fetch_assoc($result);
		$common_name = $row["common_name"];
		$flickr_code = $row["flickr_code"];
		$essay = $row["essay"];
		$cornell_map = $row["cornell_map"];
		$ebird_map = $row["ebird_map"];

		echo "
			<div class='site-content full-width edit-species'>
				<h2 class='species-subtitle'>
					Edit information for <b>$common_name</b>
				</h2>
			
				<form method='post' action='/process-species-info/?common_name=$url_common_name'>
					<h3>Flickr Code for main photo (\"medium\")</h3>
					<textarea name='flickr_code' rows='3' cols='110'>$flickr_code</textarea>
										
					<h3>Essay</h3>
					<textarea name='essay' rows='10' cols='110'>$essay</textarea>
					
					<h3>Cornell Range Map</h3>
					<input name='cornell_map' type='text' size='144' value='$cornell_map'>
						
					<h3>Ebird Dynamic Map</h3>
					<input name='ebird_map' type='text' size='144' value='$ebird_map'>
				
					<br><br>
					<input type='submit' value='Submit to Database'></input>
				</form>
			</div>
		";
	}
?>
